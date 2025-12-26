<?php

namespace App\Controller;

use App\Helpers\DateFormat;
use App\Helpers\Flash;
use App\Http\Request;
use App\Model\CommentModel;
use App\Model\ContentModel;
use App\Model\ProfileModel;
use App\View;

class ContentController
{
    private int $user_id;
    private string|null $avatarSrc;
    public function __construct(
        private Request $request,
        private ContentModel $contentModel,
        private ProfileModel $profileModel,
        private CommentModel $commentModel
    )
    {
        if(!$request->getSession('auth')) {
            header("Location: /authorization");
            die();
        }

        $this->user_id = $this->request->getSession()['user_id'];
        ['avatarSrc' => $this->avatarSrc] = $this->profileModel->getData($this->user_id) ?? null;
    }

    public function index()
    {
        return View::make('content/create', [
            'avatar' => $this->avatarSrc,
            'flash' => [
                'content' => Flash::get('content'),
                'title'   => Flash::get('title')
            ]
        ]);
    }

    public function process(): never
    {
        $title = $this->request->getPost()['title'];
        $content = $this->request->getPost()['content'];
        
        if($this->contentModel->processPost($this->user_id, $title, $content)) {
            header("Location: /");
            die();
        } else {
            header("Location: /posts/create");
            die();
        }
    }

    public function edit()
    {
        $post_id = $this->request->getGet('post_id');

        if(!$this->contentModel->belongsToUser($this->user_id, $post_id)) {
            header("Location: /profile");
            die();
        }

        $post = $this->contentModel->getPost($post_id);

        return View::make('content/edit', [
            'flash' => [
                'content' => Flash::get('content'),
                'title'   => Flash::get('title')
            ],
            'avatar' => $this->avatarSrc,
            'post'   => $post
        ]);
    }

    public function editProcess(): never
    {
        $title = $this->request->getPost()['title'];
        $content = $this->request->getPost()['content'];
        $post_id = $this->request->getGet('post_id');

        if(!$this->contentModel->processEditPost($post_id, $title, $content)) {
            header("Location: /posts/edit?post_id=" . $post_id);
            die();
        }
        
        header("Location: /");
        die();

    }

    public function delete(): never
    {
        $post_id = $this->request->getGet('post_id');

        if(!$this->contentModel->belongsToUser($this->user_id, $post_id)) {
            header("Location: /profile");
            die();
        }

        $this->contentModel->deletePost($post_id);

        header("Location: /profile");
        die();
    }

    public function post()
    {
        $post_id = $this->request->getGet('post_id');
        
        $post = $this->contentModel->getPost($post_id);
        $comments = $this->commentModel->getAllComments($post_id);

        foreach($comments as $key => $comment) {
            $comments[$key]['created_at'] = DateFormat::formatDate($comments[$key]['created_at']);
            $comments[$key]['updated_at'] = DateFormat::formatDate($comments[$key]['updated_at']);
        }

        ['avatarSrc' => $avatarSrc] = $this->profileModel->getData($this->user_id) ?? null;
        
        return View::make('content/post', [
            'flash' => [
                'comment' => Flash::get('comment')
            ],
            'post' => $post,
            'comments' => $comments,
            'avatar' => $avatarSrc
        ]);
    }

    public function comment(): never
    {

        $post_id = (int) $this->request->getGet('post_id');

        $comment = $this->request->getPost('comment');
        
        $this->commentModel->processComment(
            $post_id,
            $this->user_id,
            $comment
        );
        
        header("Location: /post?post_id=" . $this->request->getGet('post_id'));
        die();
    }
}