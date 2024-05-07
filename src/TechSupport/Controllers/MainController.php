<?php

namespace TechSupport\Controllers;

use TechSupport\View\View;
use TechSupport\Models\Users\UsersAuthService;
use TechSupport\Models\Tickets\Ticket;
use TechSupport\Services\Db;

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
        $tickets = Ticket::findAll();
        //var_dump($tickets);
        $this->view->renderHtml('tickets/showAllTickets.php', ['tickets' => $tickets,'user' => UsersAuthService::getUserByToken()]);
    }
}