<?php

declare(strict_types = 1);

namespace App\Config;

use App\Controller\AjaxController;
use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Controller\ContentController;
use App\Controller\ProfileController;
use App\Controller\RegisterController;

class RoutesConfig
{
    public function getRoutes()
    {
        return [
            ['method' => 'get',  'route' => '/',                        'action' => [HomeController::class, 'index']],
            ['method' => 'get',  'route' => '/authorization',           'action' => [AuthController::class, 'index']],
            ['method' => 'post', 'route' => '/authorization',           'action' => [AuthController::class, 'process']],
            ['method' => 'get',  'route' => '/registration',            'action' => [RegisterController::class, 'index']],
            ['method' => 'post', 'route' => '/registration',            'action' => [RegisterController::class, 'process']],
            ['method' => 'get',  'route' => '/success',                 'action' => [RegisterController::class, 'success']],
            ['method' => 'get',  'route' => '/profile',                 'action' => [ProfileController::class, 'index']],
            ['method' => 'get',  'route' => '/userprofile',             'action' => [ProfileController::class, 'usersProfile']],
            ['method' => 'get',  'route' => '/profile/edit',            'action' => [ProfileController::class, 'edit']],
            ['method' => 'post', 'route' => '/profile/edit',            'action' => [ProfileController::class, 'process']],
            ['method' => 'post', 'route' => '/profile/logout',          'action' => [ProfileController::class, 'logout']],
            ['method' => 'get',  'route' => '/posts/create',            'action' => [ContentController::class, 'index']],
            ['method' => 'post', 'route' => '/posts/create',            'action' => [ContentController::class, 'process']],
            ['method' => 'get',  'route' => '/posts/edit',              'action' => [ContentController::class, 'edit']],
            ['method' => 'post', 'route' => '/posts/edit',              'action' => [ContentController::class, 'editProcess']],
            ['method' => 'get',  'route' => '/posts/delete',            'action' => [ContentController::class, 'delete']],
            ['method' => 'get',  'route' => '/post',                    'action' => [ContentController::class, 'post']],
            ['method' => 'post', 'route' => '/post',                    'action' => [ContentController::class, 'comment']],

            ['method' => 'post', 'route' => '/ajax/theme',              'action' => [AjaxController::class, 'theme']],
            ['method' => 'post', 'route' => '/ajax/user/loadmore',      'action' => [AjaxController::class, 'loadMoreUserPosts']],
            ['method' => 'post', 'route' => '/ajax/home/loadmore',      'action' => [AjaxController::class, 'loadMoreRandomPosts']],
        ];
    }
}