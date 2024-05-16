<?php

namespace TechSupport\Controllers;

use TechSupport\Exceptions\NotFoundException;
use TechSupport\Services\Db;
use TechSupport\View\View;
use TechSupport\Models\Tickets\Ticket;
use TechSupport\Models\Images\Image;
use TechSupport\Models\Users\User;
use TechSupport\Models\Users\UsersAuthService;
use TechSupport\Models\Exceptions\MissingDataException;

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
        $imageId = Image::queryToTicketImagesTable($ticketId)->image_id;
        $image = new Image();
        $pathToImage = ($image->findElementByColumn('id', $imageId)[0])->getPath();
       // var_dump($pathToImage);
    
        if (empty($result)) {
            throw new NotFoundException();
            return;
        }
        //queryToTicketImagesTable($ticket_id);
        $this->view->renderHtml('tickets/showTicket.php', ['ticket' => $result,'path' =>$pathToImage,'user'=>$this->user]);
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
    if(!empty($_POST)) {
        try {
            $imageIds = [];
            $ticket = new Ticket();
            $ticket->createNewTicket($_POST['title'],$_POST['text']);
            $ticket->saveToDb();
                if (!empty($_FILES)) {
                    foreach ($_FILES['image']['name'] as $key => $name) {
                        $tmp_name = $_FILES['image']['tmp_name'][$key];
                        $path = "../images/" . basename($name);
                        $moving_path = "../www/images/" . basename($name);
                        if (move_uploaded_file($tmp_name, $moving_path)) {
                            $image = new Image();
                            $image->setPath($path);
                            $image->saveToDb();
                            $imageIds[] = $image->getId();
                                foreach($imageIds as $imageId) {
                                    $image->insertToTicketImagesTable( $ticket->getId(),$imageId);
                                }
                        }
                        else {
                            echo "Произошла ошибка при загрузке файла.";
                        }
                    $ticket->addImageId($imageIds);
                    }
                }
            $this->view->renderHtml('tickets/ticketCreatedSuccessfully.php');
            return;
        }
        catch (MissingDataException $e) {
            $this->view->renderHtml('main/loginPage.php', ['error' => $e->getMessage()]);
        }
    }
    $this->view->renderHtml('tickets/createTicket.php');
}

public function delete(int $ticketId): void
{
    $ticket = Ticket::getById($ticketId);
    $ticket->delete();
}
public function setManager(int $ticketId): void
{
    $ticket = Ticket::getById($ticketId);
    $ticket->setManager();
    $ticket->saveToDb();
}
}