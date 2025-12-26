<?php

declare(strict_types=1);

namespace App\Model;

use App\DB;
use App\Helpers\Flash;
use App\Http\Request;

class AuthModel
{
    private array $data = [];
    public function __construct(
        private Request $request,
        private DB $db,
    )
    {
        $this->data = $this->request->getPost();
    }

    public function authorization(): array|bool
    {
        try {
            $query = "SELECT username, password_hash, id FROM users WHERE username = ?";

            $name = $this->data['login'];
            $password = $this->data['password'];

            if(trim($password) === '' || trim($name) === '') {
                Flash::set('empty', 'inputs cannot be empty');
                throw new \Exception('inputs cannot be empty');
            }

            $stmt = $this->db->prepare($query);
            $stmt->execute([$name]);
            $result = $stmt->fetch();
            


            if(!$result) {
                throw new \Exception('This data was not found in database');
            }

            $password_hash = $result['password_hash'];
            
            if(!password_verify($password, $password_hash)) {
                
                throw new \Exception('wrong login or password');
            }

            return $result;
            
        } catch(\Throwable $e) {
            Flash::set('authError', 'wrong login or password');

            return false;
        }
    }
}