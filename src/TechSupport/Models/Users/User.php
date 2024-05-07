<?php

namespace TechSupport\Models\Users;

use TechSupport\Models\BaseModel;
use TechSupport\Exceptions\MissingDataException;

class User extends BaseModel
{
    protected $nickname;
    protected $email;
    protected $password_hash;
    protected $auth_token;
  //protected $email_verified_at;
    protected $role;

    /*public function __construct(string $name,string $email,string $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    } */

    static public function validateRegistration(array $userData)
    {
        if (!preg_match('/^[a-zA-Z0-9]+$/', $userData['nickname'])) {
            throw new MissingDataException('Ваш никнейм содержит недопустимые символы!');
        }
        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new MissingDataException('Адрес электронной почты введен неправильно!');
        }
        if (mb_strlen($userData['password']) < 7) {
            throw new MissingDataException('Пароль должен состоять не менее чем из 7 символов!');
        }
        if (static::findElementByColumn('nickname', $userData['nickname']) !== null) {
            throw new MissingDataException('Это имя занято.');
        }
        if (static::findElementByColumn('email', $userData['email']) !== null) {
            throw new MissingDataException('Пользователь с таким почтовым адресом уже существует.');
        }
        return User::createUser($userData);
    }

    public static function createUser(array $userData): User
{
    $user = new User();
    $user->nickname = $userData['nickname'];
    $user->email = $userData['email'];
    $user->password_hash = password_hash($userData['password'], PASSWORD_DEFAULT);
    $user->role = 'user';
    $user->auth_token = sha1(random_bytes(100)) . sha1(random_bytes(100));
    $user->saveToDb();

    return $user;
}

public static function login(array $loginData): User
{
    if (empty($loginData['nickname'])) {
        throw new MissingDataException('Не передан nickname');
    }

    if (empty($loginData['password'])) {
        throw new MissingDataException('Не передан password');
    }

    $user = User::findElementByColumn('nickname', $loginData['nickname']);
    if ($user === null) {
        throw new MissingDataException('Нет пользователя с таким nickname');
    }

    if (!password_verify($loginData['password'], $user->getPasswordHash())) {
        throw new MissingDataException('Неправильный пароль');
    }

    /*if (!$user->isConfirmed) {
        throw new MissingDataException('Пользователь не подтверждён');
    } */

    $user->refreshAuthToken();
    $user->saveTODb();

    return $user;
}

public function getPasswordHash(): string
{
    return $this->password_hash;
}

private function refreshAuthToken()
{
    $this->auth_token = sha1(random_bytes(100)) . sha1(random_bytes(100));
}

    public function getAuthToken(): string
    {
        return $this->auth_token;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    protected static function getTableName(): string 
    {
        return 'users';
    }
}