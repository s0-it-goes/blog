<?php

namespace App\Controller;

use App\Helpers\ContentHelper;
use App\Helpers\CutTheContent;
use App\Helpers\Flash;
use App\Helpers\ProfileHelper;
use App\Http\Request;
use App\Model\ContentModel;
use App\Model\ProfileModel;
use App\View;

class ProfileController
{
    private array $post = [];
    private array $files = [];
    private int $user_id;
    public function __construct(
        private Request $request,
        private ProfileModel $profileModel,
        private ContentModel $contentModel
    )
    {
        if(!$this->request->getSession('auth')) {
            header("Location: /authorization");
            die();
        }

        $this->user_id  = $this->request->getSession()['user_id'];
        $this->post     = $this->request->getPost();
        $this->files    = $this->request->getFiles();
    }
    
    public function index()
    {
        ['email' => $email, 'username' => $username, 'avatarSrc' => $avatarSrc] 
        = $this->profileModel->getData($this->user_id);

        $user_posts = $this->contentModel->getUserPosts($this->user_id, 3);

        $posts = [];

        foreach($user_posts as $key => $post_id) {
            $posts[$key] = $this->contentModel->getPost($post_id['id']);
            $posts[$key]['post_id'] = $post_id['id'];
            $posts[$key]['content'] = CutTheContent::cut($posts[$key]['content'], 10);
        }

        $countPosts = count($this->contentModel->getUserPosts($this->user_id));

        return View::make('profile/myprofile', [
            'email'     => $email, 
            'username'  => $username, 
            'avatar'    => $avatarSrc,
            'posts' => $posts,
            'countPosts' => $countPosts
        ]);
    }

    public function edit()
    {
        ['email' => $email, 'username' => $username, 'avatarSrc' => $avatarSrc] 
        = $this->profileModel->getData($this->user_id);

        return View::make('profile/editprofile', [
            'email'     => $email, 
            'username'  => $username,
            'avatar' => $avatarSrc,
            'flash'     => [
                'email'         => Flash::get('email'),
                'username'      => Flash::get('username'),
                'password'      => Flash::get('password'),
                'newpassword'   => Flash::get('newpassword'),
                'avatar'        => Flash::get('avatar')
            ]
        ]);
    }

    public function process()
    {
        $newName            = $this->post['login'] ?? null;
        $newEmail           = $this->post['email'] ?? null;
        $newPassword        = $this->post['newpassword1'] ?? null;
        $newPasswordRepeat  = $this->post['newpassword2'] ?? null;
        $oldPassword        = $this->post['oldpassword'] ?? null;
        $file               = $this->files['avatar'] ?? null;
        $deleteAvatar       = $this->post['deleteAvatar'] ?? null;

        if(!empty($newName)) {
            $this->profileModel->updateUsername($newName);
        }
    
        if(!empty($newEmail)) {
            $this->profileModel->updateEmail($newEmail);
        }

        if(!empty($oldPassword) && !empty($newPassword) && !empty($newPasswordRepeat)) {
            if($newPassword === $newPasswordRepeat) {
                $this->profileModel->updatePassword($oldPassword, $newPassword);
            } else {
                Flash::set('newpassword', 'new password are not similar!');
            }
        }

        if(!empty($file)) {
            if($file['size'] <= 2*1024*1024) {
                $this->profileModel->updateAvatar($file);
            } else {
                Flash::set('avatar', 'max avatar size is 2mb');
            }
        }

        if($deleteAvatar) {
            $this->profileModel->deleteAvatar();
        }
        
        header("Location: /profile/edit");
        die();
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();

        header("Location: /authorization");
        die();
    }

    public function usersProfile()
    {
        $user_profile_id = $this->request->getGet('id');
        ['username' => $username, 'avatarSrc' => $avatar] = $this->profileModel->getData($user_profile_id);

        $user_profile_posts = $this->contentModel->getUserPosts($user_profile_id);
        $posts = [];

        foreach($user_profile_posts as $key => $post_id) {

            $posts[$key] = $this->contentModel->getPost($post_id['id']);
            $posts[$key]['post_id'] = $post_id['id'];
        }

        return View::make('profile/usersprofile', [
            'username' => $username,
            'avatar' => $avatar,
            'posts' => $posts
        ]);
    }
}