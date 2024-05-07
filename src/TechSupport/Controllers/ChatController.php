<?php

namespace TechSupport\Controllers;

use TechSupport\Exceptions\NotFoundException;
use TechSupport\Services\Db;
use TechSupport\View\View;
use TechSupport\Models\Tickets\Ticket;
use TechSupport\Models\Users\User;
use TechSupport\Models\Users\UsersAuthService;
use TechSupport\Models\Chat\Chat;
use TechSupport\Exceptions\MissingDataException;

class ChatController
{
    /** @var View */
    private $view;


    public function insert(): void
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
    
        if (!empty($data['message'])) {
            $chat = new Chat();
            $chat->createNewChat($data['message'], 6);
            $chat->saveToDb();
    
            // Получаем обновленные данные чата
            $chatMessages = Chat::getAllChatsForTicket('ticket_id', 6);
            $chatData = array_map(function($chat) {
                return $chat->toArray();
            }, $chatMessages);
    
            // Отправляем обновленные данные в формате JSON
            header('Content-Type: application/json');
            echo json_encode($chatData);
        }
    }

    public function getChatMessages(): void
    {
        $chatMessages = Chat::getAllChatsForTicket('ticket_id', 6);
        //var_dump($chatMessages);
        $chatData = array_map(function($chat) {
            return $chat->toArray();
        }, $chatMessages);
        
        $json_data = json_encode($chatData);
        //$json_data = json_encode($chatMessages);

        // Отправляем JSON клиенту
        header('Content-Type: application/json');
        echo $json_data;
        } 
}