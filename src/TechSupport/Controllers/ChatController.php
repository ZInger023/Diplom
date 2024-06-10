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

class ChatController extends BaseController
{
    /** @var View */
    private $view;


    public function insert(): void
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
    
        if ((!empty($data['message']))&&(!empty($data['ticketId']))) {
            $chat = new Chat();
            $chat->createNewChat($data['message'], $data['ticketId']);
            $chat->saveToDb();
    
            // Получаем обновленные данные чата
            $chatMessages = Chat::getAllChatsForTicket('ticket_id', $data['ticketId']);
            $chatData = array_map(function($chat) {
                return $chat->toArray();
            }, $chatMessages);
            $ticket = Ticket::getById($data['ticketId']);
            $user = UsersAuthService::getUserByToken();
            if ($user->getRole() == 'user') {
            $this->sendEmailToManager('Отправка сообщения в чат', $ticket);
            }
            else if ($user->getRole() == 'manager') {
                if ($ticket->getStatus == 'viewed') {
                $ticket->setStatus('answered');
                $ticket->saveToDb();
                }
                $this->sendEmailToUser('Отправка сообщения в чат', $ticket);
            }
    
            // Отправляем обновленные данные в формате JSON
            header('Content-Type: application/json');
            echo json_encode($chatData);
        }
    }

    public function getChatMessages(): void
    {
         // Получение ID тикета из URL
         $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
         preg_match('/tickets\/getChatMessages\/(\d+)/', $urlPath, $matches);
         $ticketId = $matches[1] ?? 0;
 
         if ($ticketId == 0) {
             header('Content-Type: application/json');
             echo json_encode(['error' => 'Invalid ticket ID']);
             return;
         }
 
         $chatMessages = Chat::getAllChatsForTicket('ticket_id', $ticketId);
         $chatData = array_map(function($chat) {
             return $chat->toArray();
         }, $chatMessages);
 
         header('Content-Type: application/json');
         echo json_encode($chatData);
        } 
}