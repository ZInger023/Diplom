<?php

namespace TechSupport\Models\Tickets;
use TechSupport\Models\Users\User;
use TechSupport\Services\Db;
use TechSupport\Models\BaseModel;
use TechSupport\Models\Users\UsersAuthService;
use TechSupport\Exceptions\MissingDataException;

class Ticket extends BaseModel
{
    protected   $title;
    protected   $text;
    protected   $author_id;
    protected   $manager_id;
    protected   $created_at;
    protected   $status;
/*
 public function __construct(string $title,string $text)
    {
    $this->title = $title;
    $this->text = $text;      
    } */

    public function createNewTicket(string $title,string $text)
    {
        if (empty(UsersAuthService::getUserByToken())) {
            throw new MissingDataException('Пользователь не авторизован!');
        }
    $this->title = $title;
    $this->text = $text;
    $this->author_id = UsersAuthService::getUserByToken()->getId();
    $this->status = 'open';        
    }

    public function editTicket(string $title,string $text)
    {
        if (empty(UsersAuthService::getUserByToken())) {
            throw new MissingDataException('Пользователь не авторизован!');
        }
    $this->title = $title;
    $this->text = $text;      
    } 

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getAuthor(): User
    {
        return User::getById($this->authorId);
    }

    public function getAuthorId()
    {
        return $this->author_id;
    }
    public function setTitle($newTitle): void
    {
        $this->title = $newTitle;
    }
    public function setText($newText): void
    {
       $this->text = $newText;
    }

    public function setStatus($newStatus): void
    {
       $this->status = $newStatus;
    }

    public function setManager($newManagerId): void
    {
       $this->manager_id = $newManagerId;
    }

    public function setAuthor(User $author): void
    {
        $this->author_id = $author->getId();
    }

    protected static function getTableName(): string 
{
    return 'tickets';
}
}