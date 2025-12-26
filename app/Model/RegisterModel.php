<?php

namespace App\Model;

use App\DB;
use App\Helpers\Flash;
use App\Http\Request;

class RegisterModel
{
    private array $data = [];
    public function __construct(
        private Request $request, 
        private DB $db)
    {
        $this->data = $request->getPost();
    }

    public function register() {
        try {
            $query = 'INSERT INTO users(username, password_hash, email)
                    VALUES(:login, :password, :email)';

            $login = $this->data['login'];
            $email = $this->data['email'];
            $password = password_hash($this->data['password'], PASSWORD_DEFAULT);

            if(empty($login) || empty($email) || empty($password)) {
                Flash::set('empty', 'inputs cannot be empty');
                return false;
            }

            if($this->checkLogin($login)) {
                Flash::set('login', 'login is already occuped by other user');
                return false;
            }

            if($this->checkEmail($email)) {
                Flash::set('email', 'email is already occuped by other user');
                return false;
            }

            $this->db->beginTransaction();

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'login' => $login,
                'email' => $email,
                'password' => $password
            ]);

            $this->db->commit();

            return true;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            echo $e->getMessage() . $e->getCode();
            return false;
        }
    }

    private function checkLogin(string $login): bool
    {
        $stmt = $this->db->prepare('SELECT EXISTS(SELECT username FROM users WHERE username = ?) AS login');
        $stmt->execute([$login]);
        
        return (bool) $stmt->fetch()['login']; // true = login already used
    }

    private function checkEmail(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT EXISTS(SELECT email FROM users WHERE email = ?) AS email');
        $stmt->execute([$email]);
        
        return (bool) $stmt->fetch()['email']; // true = email already used
    }

    public function getID(): int
    {
        $id = (int) $this->db->lastInsertId();

        return $id;
    }
}