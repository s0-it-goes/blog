<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Helpers\CutTheContent;
use App\Http\Request;
use App\Model\ContentModel;
use App\Model\ProfileModel;
use App\Model\ThemeModel;
use Throwable;

class AjaxController
{
    private int $user_id;
    public function __construct(
        private ThemeModel $themeModel,
        private ContentModel $contentModel,
        private ProfileModel $profileModel,
        private Request $request,
    )
    {
        $this->user_id = (int) $this->request->getSession('user_id');
    }

    public function theme()
    {
        $theme = $this->request->getPost('theme') ?? 'light';

        $result = $this->themeModel->saveTheme($this->user_id, $theme);
        
        if($result) {
            $_SESSION['theme'] = $theme;
        }

        echo $_SESSION['theme'];
    }

    public function loadMoreUserPosts()
    {
        
        header('Content-Type: application/json');

        $offset = (int) $this->request->getGet('offset') ?? 3;
        $limit  = (int) $this->request->getGet('limit') ?? 5;
        $words  = $this->request->getGet('words') === null ? 10 : (int) $this->request->getGet('words');

        try {
            $postsIds = $this->contentModel->getUserPosts($this->user_id, $limit, $offset);
            $posts = [];
            if(count($postsIds) > 0) {
                foreach($postsIds as $key => $post_id) {
                    $posts[$key] = $this->contentModel->getPost($post_id['id']);
                    $posts[$key]['post_id'] = $post_id['id'];
                    $posts[$key]['content'] = CutTheContent::cut($posts[$key]['content'], $words);
                }
            }

            $hasMore = $this->contentModel->hasMorePosts(user_id: $this->user_id, offset: $offset+$limit);
            
            $offset += $limit;

            echo json_encode([
                'success' => true,
                'posts'   => $posts ?? null,
                'hasMore' => $hasMore,
                'limit' => $limit,
                'offset' => $offset
            ]);

        } catch (Throwable $e) {
            echo json_encode([
                'success' => false,
                'error'   => $e->getCode()
            ]);
        }
    }

    public function loadMoreRandomPosts()
    {
        
        header('Content-Type: application/json');

        $limit  = $this->request->getGet('limit') === null ? 5 : (int) $this->request->getGet('limit');
        $words  = $this->request->getGet('words') === null ? 10 : (int) $this->request->getGet('words');
        $shownIds = explode(',', $this->request->getGet('shownids'));

        try {
            $postsIds = $this->contentModel->getRandomIds($limit, $shownIds);
            $posts = [];
            if(count($postsIds) > 0) {
                foreach($postsIds as $key => $post_id) {
                    $posts[$key] = $this->contentModel->getPost($post_id);
                    $posts[$key]['post_id'] = $post_id;
                    $posts[$key]['content'] = CutTheContent::cut($posts[$key]['content'], $words);
                    $shownIds[] = $post_id;
                }

                for($i = 0; $i < count($posts); $i++) {
                    $posts[$i]['author'] = $this->profileModel->getData($posts[$i]['author_id'])['username'];
                }
            }

            $hasMore = $this->contentModel->hasMorePosts(usedIDs: $shownIds);

            echo json_encode([
                'success' => true,
                'posts'   => $posts,
                'hasMore' => $hasMore
            ]);

        } catch (Throwable $e) {
            echo json_encode([
                'success' => false,
                'error'   => $e->getCode()
            ]);
        }
    }
}