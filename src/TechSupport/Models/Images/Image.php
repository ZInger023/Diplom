<?php

namespace TechSupport\Models\Images;
use TechSupport\Models\Users\User;
use TechSupport\Services\Db;
use TechSupport\Models\BaseModel;

class Image extends BaseModel
{
    protected   $id;
    protected   $path;

 /*public function __construct(string $path)
    {
        $this->path = $path;      
    } 
*/
    public function setPath(string $path)
    {
        $this->path = $path;      
    } 
    public function getPath()
    {
        return $this->path;      
    }
    public static function insertToUsersImagesTable(int $user_id,int $image_id)
    {
        $db = Db::getInstance();
        $entities = $db->query(
            'INSERT INTO users_images (user_id, image_id) VALUES (:user_id, :image_id)',
            [':user_id' => $user_id, ':image_id' => $image_id],
            static::class
        );
        return $entities ? $entities[0] : null;
    }

    public static function insertToTicketImagesTable(int $ticket_id,int $image_id)
    {
        $db = Db::getInstance();
        $entities = $db->query(
            'INSERT INTO ticket_images (ticket_id, image_id) VALUES (:ticket_id, :image_id)',
            //'SELECT * FROM ticket_images WHERE ticket_id=:ticket_id;',
            [':ticket_id' => $ticket_id, ':image_id' => $image_id],
            static::class
        );
        return $entities ? $entities[0] : null;
    }

    public static function queryToTicketImagesTable(int $ticket_id)
    {
        $db = Db::getInstance();
        $entities = $db->query(
           // 'INSERT INTO ticket_images (ticket_id, image_id) VALUES (:ticket_id, :image_id)',
            'SELECT * FROM ticket_images WHERE ticket_id=:ticket_id;',
            [':ticket_id' => $ticket_id],
            static::class
        );
        return $entities;
    }

    public static function queryToUsersImagesTable(int $user_id)
    {
        $db = Db::getInstance();
        $entities = $db->query(
           // 'INSERT INTO ticket_images (ticket_id, image_id) VALUES (:ticket_id, :image_id)',
            'SELECT * FROM users_images WHERE user_id=:user_id;',
            [':user_id' => $user_id],
            static::class
        );
        return $entities;
    }

    public function deleteFromDb(): void {
        $db = Db::getInstance();
        $db->query('DELETE FROM images WHERE id = :id', [':id' => $this->id]);
        $db->query('DELETE FROM ticket_images WHERE image_id = :image_id', [':image_id' => $this->id]);
    }

    public function deleteFromTicketImagesTable(int $ticketId): void {
        $db = Db::getInstance();
        $db->query('DELETE FROM ticket_images WHERE ticket_id = :ticket_id AND image_id = :image_id', [
            ':ticket_id' => $ticketId,
            ':image_id' => $this->id
        ]);
    }

    public static function getImage(int $id)
    {
        $db = Db::getInstance();
        $entity = $db->query(
           // 'INSERT INTO ticket_images (ticket_id, image_id) VALUES (:ticket_id, :image_id)',
            'SELECT * FROM images WHERE id=:id;',
            [':id' => $id],
            static::class
        );
        return $entity[0];
    }

    protected static function getTableName(): string 
{
    return 'images';
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