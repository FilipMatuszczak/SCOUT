<?php

namespace App\Services;

use App\Entity\User;

class PasswordHandler
{
    /**
     * @param $password
     *
     * @return array
     */
    public function generateHashAndSalt($password)
    {
        $salt = $this->getSalt(128);
        $hash = hash('sha512', $password . $salt);

        return [
            User::COLUMN_PASSWORD => $hash,
            User::COLUMN_SALT => $salt,
        ];
    }

    private function getSalt($size)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $size; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }
}