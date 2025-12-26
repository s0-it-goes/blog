<?php

namespace App\Http;

class Request
{
    public function __construct(
        private array $get,
        private array $post,
        private array $session,
        private array $cookie,
        private array $server,
        private array $files
    )
    {
    }

    public function getGet(string|null $key = null): array|string|int|null
    {
        if(is_null($key)) {
            return $this->get;
        }

        if(isset($this->get[$key])) {
            return $this->get[$key];
        }

        return null;
    }

    public function getPost(string|null $key = null): array|string|int|null
    {
        if(is_null($key)) {
            return $this->post;
        }

        if(isset($this->post[$key])) {
            return $this->post[$key];
        }

        return null;
    }

    public function getSession(string|null $key = null): array|string|int|null
    {
        if(is_null($key)) {
            return $this->session;
        }

        if(isset($this->session[$key])) {
            return $this->session[$key];
        }

        return null;
    }

    public function getCookie(string|null $key = null): array|string|int|null
    {
        if(is_null($key)) {
            return $this->cookie;
        }

        if(isset($this->cookie[$key])) {
            return $this->cookie[$key];
        }

        return null;
    }

    public function getServer(string|null $key = null): array|string|null
    {   
        if(is_null($key)) {
            return $this->server;
        }

        if(isset($this->server[$key])) {
            return $this->server[$key];
        }

        return null;
    }

    public function getFiles(string|null $key = null): array|string|null
    {   
        if(is_null($key)) {
            return $this->files;
        }

        if(isset($this->files[$key])) {
            return $this->files[$key];
        }

        return null;
    }

}