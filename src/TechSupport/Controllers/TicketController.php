<?php

namespace TechSupport\Controllers;

use TechSupport\Exceptions\NotFoundException;
use TechSupport\Services\Db;
use TechSupport\View\View;
use TechSupport\Models\Tickets\Ticket;
use TechSupport\Models\Users\User;
use TechSupport\Models\Users\UsersAuthService;
use TechSupport\Exceptions\MissingDataException;

class TicketController
{
    /** @var View */
    private $view;

    public function __construct()
    {
        $this->user = UsersAuthService::getUserByToken();
        $this->view = new View(__DIR__ . '/../../../templates');
        $this->view->setVar('user', $this->user);
    }

    public function view(int $ticketId)
    {
        $result = Ticket::getById($ticketId);
    
        if (empty($result)) {
            throw new NotFoundException();
            return;
        }
    
        $this->view->renderHtml('tickets/showTicket.php', ['ticket' => $result]);
    }

    public function edit(int $ticketId): void
    {
        $ticket = ticket::getById($ticketId);
        if(!empty($_POST)) {
            $ticket->editTicket($_POST['title'],$_POST['text']);
            $ticket->saveToDb();
        }
        //$ticket = ticket::getById($ticketId);
        //$this->view->renderHtml('tickets/editTicket.php',['title'=>$ticket->getTitle(),'text'=>$ticket->getText()]);
        $this->view->renderHtml('tickets/editTicket.php',['ticket'=>$ticket]);

        if ($ticket === null) {
            throw new NotFoundException();
            return;
        }
        //$ticket->editTicket($_POST['title'],$_POST['text']);
        //$ticket->saveToDb();
        //$ticket->setTitle('Новое название статьи');
        //$ticket->setText('Новый текст статьи');
        //var_dump($ticket); 
    }

    public function insert(): void
{
    //$userEx = UsersAuthService::getUserByToken();
    //var_dump(UsersAuthService::getUserByToken()->getId());
    //var_dump($userEx->getId());
    if(!empty(($_POST['title'])&&( $_POST['text']) )) {
        try{
    $ticket = new Ticket();
    $ticket->createNewTicket($_POST['title'],$_POST['text']);
    $ticket->saveToDb();
    $this->view->renderHtml('tickets/ticketCreatedSuccessfully.php');
    return;
        }
        catch (MissingDataException $e) {
            $this->view->renderHtml('main/loginPage.php', ['error' => $e->getMessage()]);
        }
    //var_dump($ticket);
    }
    $this->view->renderHtml('tickets/createTicket.php');
}

public function delete(int $ticketId): void
{
    $ticket = Ticket::getById($ticketId);
    $ticket->delete();
}
}