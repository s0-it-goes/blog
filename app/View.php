<?php

declare(strict_types = 1);

namespace App;

use App\Helpers\Flash;
use App\Exceptions\View\FileDoesNotExistsException;

class View
{
    public function __construct(
        private string $view,
        private array $args
    )
    {
    }

    public static function make(string $view, array $params = []): static
    {
        return new static($view, $params);
    }

    public function render(): string {
        $path = VIEWS_PATH . $this->view . '.php';
        
        if(!file_exists($path)) {
            throw new FileDoesNotExistsException('this view does not exist');
        }

        extract($this->args, EXTR_SKIP);

        ob_start();
        include $path;
        return ob_get_clean();
    }

    public function __toString(): string
    {
        return $this->render();
    }
}