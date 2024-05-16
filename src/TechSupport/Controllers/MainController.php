<?php

namespace TechSupport\Controllers;

use TechSupport\View\View;
use TechSupport\Models\Users\UsersAuthService;
use TechSupport\Models\Users\User;
use TechSupport\Models\Tickets\Ticket;
use TechSupport\Services\Db;
use TechSupport\Models\Exceptions\MissingDataException;

class MainController
{
    private $view;

    public function __construct()
    {
        $this->user = UsersAuthService::getUserByToken();
        $this->view = new View(__DIR__ . '/../../../templates');
        $this->view->setVar('user', $this->user);
    }

    public function main()
    {
        try {
           $user =  UsersAuthService::getUserByToken();
        }
        catch(MissingDataException $e) {
            $this->view->renderHtml('main/loginPage.php', ['error' => $e->getMessage()]);
        }       
        self::renderByRole( $user);
    }

    public function renderByRole(User $user) //Вынести в TicketController и вызывать тут его?
    {
        if ($user->getRole() == 'user') {
            $tickets = Ticket::findElementByColumn('author_id', $user->getId());
                if(!empty($tickets)) {
                    $ticketsData = array_map(function($ticket) {
                    return $ticket->toArray();
                    }, $tickets);
                $this->view->renderHtml('tickets/showAllTickets.php', ['tickets' => $ticketsData,'user' => UsersAuthService::getUserByToken()]);
                }
                else {
                    $this->view->renderHtml('tickets/showAllTickets.php', ['tickets' => $tickets,'user' => UsersAuthService::getUserByToken()]);
                }
            }
        else {
            $tickets = Ticket::findAll();
            $ticketsData = array_map(function($ticket) {
                return $ticket->toArray();
            }, $tickets);
            $this->view->renderHtml('tickets/showToAdmin.php', ['tickets' => $ticketsData,'user' => UsersAuthService::getUserByToken()]);
        }
    }

}