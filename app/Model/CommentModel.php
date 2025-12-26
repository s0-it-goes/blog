<?php

declare(strict_types=1);

namespace App\Model;

use App\DB;
use App\Helpers\Flash;
use App\Http\Request;

class CommentModel
{

    public function __construct(
        private Request $request,
        private DB $db
    )
    {
    }

    public function processComment(int $post_id, int $user_id,  string $comment)
    {
        try {
            $this->db->beginTransaction();

            if(empty($comment)) {
                Flash::set('comment', 'comment cannot be empty');
                throw new \Exception('comment cannot be empty');
            }

            $query = "INSERT INTO comments(post_id, user_id, comment)
                      VALUES(:post_id, :user_id, :comment)";

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'post_id' => $post_id,
                'user_id' => $user_id,
                'comment' => $comment
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

    public function getComment(int $post_id, int $user_id)
    {

    }

    public function getAllComments(int $post_id): array|null
    {
        try {
            $query = "SELECT comments.user_id, comments.comment, comments.created_at, comments.updated_at, users.username
            FROM comments
            INNER JOIN users ON comments.user_id = users.id
            WHERE post_id=:post_id";

            $stmt = $this->db->prepare($query);
            $stmt->execute(['post_id' => $post_id]);

            $result = $stmt->fetchAll();

            return $result;
        } catch(\Throwable $e) {
            
            return null;
        }
    }
}