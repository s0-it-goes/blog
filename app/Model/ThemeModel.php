<?php

declare(strict_types = 1);

namespace App\Model;

use App\DB;

class ThemeModel
{
    public function __construct(
        private DB $db
    )
    {
    }

    public function saveTheme(int $userID, string $theme) : bool
    {
        try {
            $query = 'UPDATE users
                      SET theme=:theme
                      WHERE id =:user_id';

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'user_id' => $userID,
                'theme'   => $theme
            ]);

            return true;

        } catch(\Throwable $e) {

            return false;
        }
    }

    public function getTheme(int $userID): string|null
    {
        try {
            $query = 'SELECT theme FROM users WHERE id=:user_id';
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'user_id' => $userID
            ]);

            return $stmt->fetch()['theme'];

        } catch(\Throwable $e) {

            return null;
        }
    }
}