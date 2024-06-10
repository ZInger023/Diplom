<?php

namespace TechSupport\Controllers;

use TechSupport\Exceptions\NotFoundException;
use TechSupport\Services\Db;
use TechSupport\View\View;
use TechSupport\Models\Tickets\Ticket;
use TechSupport\Models\Images\Image;
use TechSupport\Models\Users\User;
use TechSupport\Models\Users\UsersAuthService;
use TechSupport\Services\EmailSender;
use TechSupport\Models\Exceptions\MissingDataException;

class TicketController extends BaseController
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
        $ticket_images = Image::queryToTicketImagesTable($ticketId);
        if (empty($result)) {
            throw new NotFoundException();
            return;
        }
        if(!empty($ticket_images)) {
            $images = [];
            foreach ($ticket_images as $ticket_image) {
                $image = Image::getImage($ticket_image->image_id);
                array_push($images, $image);
            }
        $this->view->renderHtml('tickets/showTicket.php', ['ticket' => $result,'images' =>$images,'user'=>$this->user]);
        }
        else{
            $this->view->renderHtml('tickets/showTicket.php', ['ticket' => $result,'user'=>$this->user]);
        }
    }

    public function edit(int $ticketId): void
    {
        try {
        $user =  UsersAuthService::getUserByToken();
        $ticket = ticket::getById($ticketId);
        $ticket_images = Image::queryToTicketImagesTable($ticketId);
        if (empty($ticket)) {
            throw new NotFoundException();
            return;
        }
    }
    catch (NotFoundException $e) {
        header('Location: /users/login');
        exit();
        }
        catch (MissingDataException $e) {
            $this->view->renderHtml('main/loginPage.php', ['error' => $e->getMessage()]);
        }
        if(!empty($ticket_images)) {
            $images = [];
            foreach ($ticket_images as $ticket_image) {
                $image = Image::getImage($ticket_image->image_id);
                array_push($images, $image);
            }
         }
        if(!empty($_POST)) {
            if (!empty($_POST['delete_images'])) {
                foreach ($_POST['delete_images'] as $imageId) {
                    $image = Image::getById($imageId);
                    if ($image) {
                        $image->deleteFromTicketImagesTable($ticket->getId());
                        $image->deleteFromDb();
                    }
                }
            }
            if (!empty($_FILES['new_images']['name'][0])) {
                $imageIds = [];
                foreach ($_FILES['new_images']['name'] as $key => $name) {
                    $tmp_name = $_FILES['new_images']['tmp_name'][$key];
                    $path = "../images/" . basename($name);
                    $moving_path = "../www/images/" . basename($name);
        
                    if (move_uploaded_file($tmp_name, $moving_path)) {
                        $image = new Image();
                        $image->setPath($path);
                        $image->saveToDb();
                        $imageIds[] = $image->getId();
                    } else {
                        echo "Произошла ошибка при загрузке файла.";
                    }
                }

                foreach ($imageIds as $imageId) {
                    Image::insertToTicketImagesTable($ticket->getId(), $imageId);
                }

            }
            $ticket->editTicket($_POST['title'],$_POST['text']);
            $ticket->saveToDb();
            $this->view->renderHtml('tickets/ticketEditedSuccessfully.php',['ticket'=>$ticket]);
        }
        else {
            $this->view->renderHtml('tickets/editTicket.php',['ticket'=>$ticket,'images' => $images]);
        }
    }

    public function insert(): void
{
    if(!empty($_POST)) {
        try {
            $imageIds = [];
            $ticket = new Ticket();
            $ticket->createNewTicket($_POST['title'],$_POST['text']);
            $ticket->saveToDb();
            if (!empty($_FILES['image']['name'][0])) {
                $imageIds = [];
                foreach ($_FILES['image']['name'] as $key => $name) {
                    $tmp_name = $_FILES['image']['tmp_name'][$key];
                    $path = "../images/" . basename($name);
                    $moving_path = "../www/images/" . basename($name);
                    if (move_uploaded_file($tmp_name, $moving_path)) {
                        $image = new Image();
                        $image->setPath($path);
                        $image->saveToDb();
                        $imageIds[] = $image->getId();
                    } else {
                        echo "Произошла ошибка при загрузке файла.";
                    }
                }
            
                foreach($imageIds as $imageId) {
                    $image->insertToTicketImagesTable($ticket->getId(), $imageId);
                }
            }
            $user =  UsersAuthService::getUserByToken();
            $this->sendEmailToAllManagers('Cоздание заявки.', $user->getName());
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
        $user =  UsersAuthService::getUserByToken();
        $ticket = Ticket::getById($ticketId);
        if ($user->getRole() == 'user') {
            $this->sendEmailToManager('Удаление заявки', $ticket);
        }
        try{
        $ticket->deleteTicket();
        }
        catch (MissingDataException $e) {
                $this->view->renderHtml('main/loginPage.php', ['error' => $e->getMessage()]);
        }
        header('Location: /');
        exit();
}

public function setManager(int $ticketId): void
{
        $ticket = Ticket::getById($ticketId);
        $ticket->setManager();
        $ticket->setStatus('viewed');
        $ticket->saveToDb();
        header('Location: /');
        exit();
}

}