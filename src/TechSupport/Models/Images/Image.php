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
        return $entities ? $entities[0] : null;
    }

    protected static function getTableName(): string 
{
    return 'images';
}
/*
public function ticketImagesNavigation($) {
    $sql = 'INSERT INTO ticket_images (' . $columnsViaSemicolon . ') VALUES (' . $paramsNamesViaSemicolon . ');';

    $db = Db::getInstance();
    $db->query($sql, $params2values, static::class);
    //$this->id = $db->getLastInsertId();
} */

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