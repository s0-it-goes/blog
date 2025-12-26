<?php

declare(strict_types=1);

namespace App\Controller;

use App\Helpers\CutTheContent;
use App\Http\Request;
use App\Model\ContentModel;
use App\Model\ProfileModel;
use App\View;

class HomeController
{
    private int $user_id;

    public function __construct(
        private Request $request,
        private ContentModel $contentModel,
        private ProfileModel $profileModel
    )
    {
        if(!$request->getSession('auth')) {
            header("Location: /authorization");
            die();
        }

        $this->user_id = $this->request->getSession()['user_id'];
    }
    public function index(): View
    {
        $postId = $this->contentModel->getRandomIds(3);

        $posts = [];
        
        foreach($postId as $key => $id) {
            $posts[$key] = [
                'title' => $title[], 
                'content' => $content[],
                'author_id' => $author_id[],
            ] = $this->contentModel->getPost($id);
            $posts[$key]['post_id'] = $id;
            $posts[$key]['content'] = CutTheContent::cut($posts[$key]['content'], 65);
        }

        for($i = 0; $i < count($posts); $i++) {
            $posts[$i]['author'] = $this->profileModel->getData($posts[$i]['author_id'])['username'];
        }
        
        ['avatarSrc' => $avatarSrc] = $this->profileModel->getData($this->user_id) ?? null;

        return View::make('home', [
            'avatar'    => $avatarSrc,
            'posts' => $posts,
        ]);
    }
}