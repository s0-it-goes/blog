<?php

namespace App\Controller;

use App\Helpers\Flash;
use App\Http\Request;
use App\Model\AuthModel;
use App\Model\ThemeModel;
use App\View;

class AuthController
{
    public function __construct(
        private AuthModel $authModel,
        private Request $request,
        private ThemeModel $themeModel
    )
    {
        if($this->request->getServer('auth')) {
            header("Location: /");
            die();
        }
    }
    public function index(): string
    {
        $authError = Flash::get('authError');
        $empty = Flash::get('empty');
        
        return View::make('authorization/login', [
            'flash' => [
                'authError' => $authError,
                'empty' => $empty
            ]
        ]);
    }

    public function process(): void
    {
        $result = $this->authModel->authorization();

        if(!$result) {
            Flash::set('error', 'wrong login or password');

            header('Location: /authorization');
            die();
        }
        
        session_regenerate_id(true);
        $_SESSION['auth']    = true;
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['theme']   = $this->themeModel->getTheme($result['id']);

        header('Location: /');
        die();
    }
}