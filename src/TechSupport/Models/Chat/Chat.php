<?php

namespace TechSupport\Models\Chat;
use TechSupport\Models\Users\User;
use TechSupport\Services\Db;
use TechSupport\Models\BaseModel;
use TechSupport\Models\Users\UsersAuthService;
use TechSupport\Exceptions\MissingDataException;

class Chat extends BaseModel
{
    protected   $id;
    protected   $text;
    protected   $author_id;
    protected   $ticket_id;
    protected   $created_at;
/*
 public function __construct(string $title,string $text)
    {
    $this->title = $title;
    $this->text = $text;      
    } */

    public function createNewChat(string $text, int $ticketId)
    {
        if (empty(UsersAuthService::getUserByToken())) {
            throw new MissingDataException('Пользователь не авторизован!');
        } 
    $this->text = $text;
    $this->author_id = UsersAuthService::getUserByToken()->getId();
    $this->ticket_id = $ticketId;
    }

    protected static function getTableName(): string 
{
    return 'chats';
}

public static function getAllChatsForTicket(string $columnName, $value)
{
    $db = Db::getInstance();
    $result = $db->query(
        'SELECT * FROM `' . static::getTableName() . '` WHERE `' . $columnName . '` = :value;',
        [':value' => $value],
        static::class
    );
    if ($result === []) {
        return null;
    }
    return $result;
}

public function toArray() {
    return [
        'id' => $this->id,
        'text' => $this->text,
        'author_id' => $this->author_id,
        'ticket_id' => $this->ticket_id,
        'created_at' => $this->created_at
    ];
}

}