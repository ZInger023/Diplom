<?php

namespace TechSupport\Models\Users;

use TechSupport\Models\Exceptions\MissingDataException;

class UsersAuthService
{
    public static function createToken(User $user): void
    {
        $token = $user->getId() . ':' . $user->getAuthToken();
        setcookie('token', $token, 0, '/', '', false, true);
    }

    public static function getUserByToken(): ?User
    {
        $token = $_COOKIE['token'] ?? '';

        if (empty($token)) {
            throw new MissingDataException('Для этого нужно быть авторизованным пользователем.');
            return null;
        }

        [$userId, $authToken] = explode(':', $token, 2);

        $user = User::getById((int) $userId);

        if ($user == null) {
            throw new MissingDataException('Для этого нужно быть авторизованным пользователем.');
            //return null;
        }

        if ($user->getAuthToken() !== $authToken) {
            return null;
        }

        return $user;
    }
}