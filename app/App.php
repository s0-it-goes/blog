<?php

declare(strict_types = 1);

namespace App;

use App\Config\DBConfig;
use App\Http\Request;

class App
{
    private static DB $db;

    public function __construct(
        private Container $container,
        private Routes $routes,
        private Router $router,
        private DBConfig $config,
        private Request $request
    )
    {
        static::$db = new DB(($this->config)->db ?? []);
        $container->set(DB::class, fn() => static::$db);
    }

    public static function db(): DB
    {
        return static::$db;
    }

    public function run()
    {
        $this->routes->registerRoutes();

        //try {
            echo $this->router->resolve(
                $this->request->getServer('REQUEST_URI'), 
                strtolower($this->request->getServer('REQUEST_METHOD')
            ));
            /*
        } catch (\Exception $e) {
            http_response_code(404);
    
            echo View::make('error/404');
        }
            */
    }
}