<?php

declare(strict_types = 1);

namespace App\Model;

use App\DB;
use App\Helpers\Flash;
use App\Http\Request;
use Exception;
use PDOException;

class ContentModel
{
    private int|null $user_id;
    private array $data;

    public function __construct(
        private Request $request,
        private DB $db
    )
    {

    }

    public function processPost(int $user_id, string $title, string $content): bool
    {
        try {
            if(empty($title)) {
                Flash::set('title', 'Title cannot be empty');
                throw new \Exception('title is empty');
            }

            if(empty($content)) {
                Flash::set('content', 'Content cannot be empty');
                throw new \Exception('content is empty');
            }

            $this->db->beginTransaction();

            $query = 'INSERT INTO posts(title, user_id, content)
                      VALUES(:title, :user_id, :content)';

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'title'   => $title,
                'user_id' => $user_id,
                'content' => $content
            ]);

            $this->db->commit();

            return true;
        } catch(\Throwable $e) {
            if($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            
            return false;
        }
    }

    public function processEditPost(int $content_id, string $title, string $content): bool
    {
        try {
            if(empty($title)) {
                Flash::set('title', 'Title cannot be empty');
                throw new \Exception('title is empty');
            }

            if(empty($content)) {
                Flash::set('content', 'Content cannot be empty');
                throw new \Exception('content is empty');
            }
            
            $this->db->beginTransaction();

            $query = 'UPDATE posts SET title = :title, content = :content WHERE id = :content_id';

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'title'      => $title,
                'content'    => $content,
                'content_id' => $content_id
            ]);
            
            $this->db->commit();

            return true;
            
        } catch(\Throwable $e) {
            if($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            
            return false;
        }
        
    }

    public function deletePost(int $post_id)
    {
        try {
            $query = "DELETE FROM posts WHERE id = :post_id";

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'post_id' => $post_id
            ]);

            return true;
            
        } catch(PDOException $e) {

            return false;
        }

    }

    public function belongsToUser(int $user_id, int $post_id): bool
    {
            $query = 'SELECT EXISTS(
                SELECT 1 FROM posts WHERE user_id = :user_id AND id = :post_id
            ) AS record_exists';

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'user_id' => $user_id,
                'post_id' => $post_id
            ]);

            $record = $stmt->fetch()['record_exists'];

            if(!$record) {
                return false;
            }

            return true;
    }

    public function getPost(int $content_id): array|null
    {
        $query = 'SELECT title, content, user_id FROM posts WHERE id = :content_id';

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$content_id]);

            $result = $stmt->fetch();

        } catch(PDOException $e) {
            throw new Exception($e->getCode() . $e->getMessage());
        }

        if($result) {
            $data = [
                'title' => $result['title'],
                'content' => $result['content'],
                'author_id' => $result['user_id']
            ];
        }

        return $data ?? null;
    }

    public function getUserPosts(int $user_id, int|null $limit = null, int $offset = 0): array|null
    {
        if($limit) {
            $query = 'SELECT id FROM posts WHERE user_id = :user_id ORDER BY id DESC LIMIT :offset, :limit';

            try {
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    'user_id' => $user_id,
                    'offset'  => $offset,
                    'limit'   => $limit
                ]);

                $result = $stmt->fetchAll();

                return $result ?? null;

            } catch(PDOException $e) {
                return null;
            }
        } 

        $query = 'SELECT id FROM posts WHERE user_id = :user_id ORDER BY id DESC';

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'user_id' => $user_id,
            ]);

            $result = $stmt->fetchAll();

            return $result ?? null;

        } catch(PDOException $e) {
            return null;
        }
    }

    public function hasMorePosts(int $offset = 0, int $user_id = -1, array $usedIDs = []): bool
    {
        if($user_id !== -1) {
            try {
                $query = 'SELECT COUNT(*) FROM posts WHERE user_id = :user_id';
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    'user_id' => $user_id
                ]);

                $result = $stmt->fetchColumn();

                return $offset < $result;
            } catch(PDOException $e) {
                return false;
            }
        }

        try {
            $placeholders = rtrim(str_repeat('?,', count($usedIDs)), ',');

            $query = "SELECT id FROM posts 
                    WHERE id NOT IN($placeholders) 
                    ORDER BY RAND()";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ...$usedIDs
            ]);

            $result = $stmt->fetchAll();

            if(count($result) > 0) {
                return true;
            }

            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getRandomIds(int $limit, array $usedIDs = [-1]): array|null
    {
        $placeholders = rtrim(str_repeat('?,', count($usedIDs)), ',');

        $query = "SELECT id FROM posts 
                WHERE id NOT IN($placeholders) 
                ORDER BY RAND() 
                LIMIT ?";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ...$usedIDs ,
                $limit
            ]);

            $result = $stmt->fetchAll();

        } catch(PDOException $e) {
            throw new Exception($e->getCode() . $e->getMessage());
        }

        $id = [];

        if($result) {
            foreach($result as $elem) {
                $id[] = $elem['id'];
            }
        }

        return $id ?? null;
    }
}