<?php

namespace TechSupport\Models\Tickets;
use TechSupport\Models\Users\User;
use TechSupport\Services\Db;
use TechSupport\Models\BaseModel;
use TechSupport\Models\Users\UsersAuthService;
use TechSupport\Models\Exceptions\MissingDataException;

class Ticket extends BaseModel
{
    protected   $title;
    protected   $text;
    protected   $author_id;
    protected   $manager_id;
    protected   $created_at;
    protected   $status;

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
        $user = UsersAuthService::getUserByToken();
        if (($user->getId() != $this->author_id) && ( $user->getRole() != 'manager')) {
            throw new MissingDataException('Вы не являетесь автором этой заявки!');
        }
    $this->title = $title;
    $this->text = $text;      
    }

    public function deleteTicket()
    {
        $user = UsersAuthService::getUserByToken();
        if (($user->getId() != $this->author_id) && ( $user->getRole() != 'manager')) {
            throw new MissingDataException('Вы не являетесь автором этой заявки!');
        }
        $this->delete();     
    }

    public function addImageId($id): void
    {
       $this->image_id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getAuthor(): User
    {
        return User::getById($this->authorId);
    }

    public function getAuthorId()
    {
        return $this->author_id;
    }

    public function getManagerId()
    {
        return $this->manager_id;
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

    public function setManager(): void
    {
        $manager = UsersAuthService::getUserByToken();
        if ((empty(UsersAuthService::getUserByToken())) && ($manager->getRole()== 'manager')) {
            throw new MissingDataException('Для этого нужно являться менеджером!');
        }
       $this->manager_id = $manager->getId();
    }

    public function setAuthor(User $author): void
    {
        $this->author_id = $author->getId();
    }

    protected static function getTableName(): string 
{
    return 'tickets';
}
public function toArray() {
    return [
        'id' => $this->id,
        'title' => $this->title,
        'text' => $this->text,
        'author_id' => $this->author_id,
        'status' => $this->status,
        'created_at' => $this->created_at
    ];
}
}