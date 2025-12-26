<?php

declare(strict_types = 1);

namespace App\Model;

use App\DB;
use App\Helpers\Flash;
use App\Http\Request;

class ProfileModel
{
    private int|null $user_id;
    public function __construct(
        private Request $request,
        private DB $db,
    )
    {
        $this->user_id = $this->request->getSession()['user_id'] ?? null;
    }

    public function updateUsername(string $newName): bool
    {
        try {
            $query = 'UPDATE users SET username = :newname WHERE id = :user_id';

            $stmt = $this->db->prepare($query);
            
            $stmt->execute([
                'newname' => $newName,
                'user_id' => $this->user_id
            ]);

            return true;

        } catch(\PDOException $e) {
            Flash::set('username', 'this username is already used by other user');

            return false;
        }
    }

    public function updateEmail(string $newEmail): bool
    {
        try {
            $query = 'UPDATE users SET email = :newemail WHERE id = :user_id';

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'newemail' => $newEmail,
                'user_id' => $this->user_id
            ]);

            return true;

        } catch(\PDOException $e) {
            Flash::set('email', 'this email is already used by other user');

            return false;
        }
    }

    public function updatePassword(string $oldPassword, string $newPassword): bool
    {
        
        try {
            $stmt = $this->db->prepare('SELECT password_hash FROM users WHERE id = :user_id');
            $stmt->execute(['user_id' => $this->user_id]);
            $password_hash = $stmt->fetchColumn(); // password hash from database
            
            if(!password_verify($oldPassword, $password_hash)) {
                Flash::set('password', 'wrong password');
                
                throw new \Exception('wrong password');
            }
            
            
            $query = 'UPDATE users SET password_hash = :newpassword WHERE id = :user_id';

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'newpassword' => password_hash($newPassword, PASSWORD_DEFAULT),
                'user_id' => $this->user_id
            ]);

            return true;

        } catch(\Throwable $e) {

            return false;
        }
    }

    public function updateAvatar(array $file)
    {
        
        $tmp= $file['tmp_name'];

        if(!file_exists($tmp)) {
            return false;
        }
        
        $name = substr($tmp, 5);
        $extension = match(mime_content_type($tmp)){
            'image/jpeg' => '.jpg',
            'image/png' => '.png',
            'image/gif' => '.gif',
            default => null
        };
        
        $filename = $name . $extension;

        try{
            $this->db->beginTransaction();

            $stmt = $this->db->prepare('SELECT avatar FROM users WHERE id = :user_id');
            $stmt->execute([$this->user_id]);
            $avatarPath = $stmt->fetch()['avatar'];

            $query = 'UPDATE users SET avatar = :filename WHERE id = :user_id';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'filename' => $filename,
                'user_id' => $this->user_id
            ]);

            move_uploaded_file($tmp, STORAGE_PATH . 'profile/avatar/' . $filename);

            $this->db->commit();

            if($avatarPath && file_exists(STORAGE_PATH . 'profile/avatar/' . $avatarPath)) {
                unlink(STORAGE_PATH . 'profile/avatar/' . $avatarPath);
            }

            return true;

        } catch(\Throwable $e) {
            if($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            return false;
        }
        
    }

    public function deleteAvatar()
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare('SELECT avatar FROM users WHERE id = :user_id');
            $stmt->execute([$this->user_id]);
            $avatarPath = $stmt->fetch()['avatar'];

            $query = 'UPDATE users SET avatar = :avatar WHERE id = :user_id';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'avatar' => NULL, 
                'user_id' => $this->user_id
            ]);

            $this->db->commit();

            if(file_exists(STORAGE_PATH . 'profile/avatar/' . $avatarPath)) {
                unlink(STORAGE_PATH . 'profile/avatar/' . $avatarPath);
            }

            return true;

        } catch(\Throwable $e) {
            if($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            return false;
        }
    }

    public function getData($userID): array|null
    {
        $query = "SELECT email, username, avatar FROM users WHERE id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$userID]);
        $result = $stmt->fetch();

        if($result) {
            $data = [
                'email' => $result['email'],
                'username' => $result['username'],
                'avatarSrc' => $result['avatar']
            ];
        }

        return $data ?? null;
    }

}