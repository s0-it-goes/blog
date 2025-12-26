<?php

namespace App\Controller;

use App\App;
use App\Helpers\Flash;
use App\Http\Request;
use App\Model\RegisterModel;
use App\View;

class RegisterController
{
    public function __construct(
        private RegisterModel $registerModel,
        private Request $request
    )
    {
        if($this->request->getSession('auth')) {
            header("Location: /");
            die();
        }
    }

    public function index()
    {
        $emptyError = Flash::get('empty');
        $loginError = Flash::get('login');
        $emailError = Flash::get('email');

        return View::make('authorization/register', 
        [
            'flash' => [
                'empty' => $emptyError,
                'login' => $loginError,
                'email' => $emailError
            ]
        ]);
    }

    public function process()
    {

        $result = $this->registerModel->register();

        if(!$result) {
            header("Location: /registration");
            die();
        }

        session_regenerate_id(true);
        $_SESSION['auth'] = true;
        $_SESSION['user_id'] = $this->registerModel->getID();

        header("Location: /");
        die();
    }

    public function success()
    {
        return View::make('authorization/success');
    }
}