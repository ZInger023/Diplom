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
            throw new MissingDataException('Токен пуст.');
            return null;
        }

        [$userId, $authToken] = explode(':', $token, 2);

        $user = User::getById((int) $userId);

        if ($user == null) {
            throw new MissingDataException('Для этого нужно быть авторизованным пользователем.');
        }

        if ($user->getAuthToken() !== $authToken) {
            return null;
        }
        $user->setActivityTime();
        $user->saveToDb();
        return $user;
    }
}