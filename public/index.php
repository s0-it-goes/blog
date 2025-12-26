<?php

declare(strict_types = 1);

use App\Http\Request;

session_start();
//unset($_SESSION);
if(!isset($_SESSION['auth'])) {
    $_SESSION['auth'] = false;
}

use App\App;
use App\Config\DBConfig;
use App\Container;
use App\Router;
use App\Routes;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

define('VIEWS_PATH', __DIR__ . '/../views/');
define('CONFIG_PATH', __DIR__ . '/../app/Config/');
define('STORAGE_PATH', __DIR__ . '/../storage/');

$container = new Container();

$container->set(Router::class, fn(Container $c) => new Router($c));
$container->set(Request::class, fn() => new Request($_GET, $_POST, $_SESSION, $_COOKIE, $_SERVER, $_FILES));

$routes = $container->get(Routes::class);
$router = $routes->getRouter();
$config = new DBConfig($_ENV);
$request = $container->get(Request::class);

(new App(
    $container, 
    $routes, 
    $router,
    $config,
    $request
))->run();