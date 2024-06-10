<?php

namespace TechSupport\Controllers;

use TechSupport\View\View;
use TechSupport\Models\Users\User;
use TechSupport\Models\Users\UserActivationService;
use TechSupport\Models\Users\UsersAuthService;
use TechSupport\Models\Images\Image;
use TechSupport\Models\Tickets\Ticket;
use TechSupport\Models\Chat\Chat;
use TechSupport\Services\EmailSender;
use TechSupport\Models\Exceptions\MissingDataException;

class UsersController {
/** @var View */
private $view;

public function __construct()
{
    $this->view = new View(__DIR__ . '/../../../templates');
}

public function login()
    {
        if (!empty($_POST)) {
            try {
                $user = User::login($_POST);
                UsersAuthService::createToken($user);
                header('Location: /');
                exit();
            } catch (MissingDataException $e) {
                $this->view->renderHtml('main/loginPage.php', ['error' => $e->getMessage()]);
                return; 
            }
        }
        $this->view->renderHtml('main/loginPage.php');
    }

public function signUp()
{
    try{
        if(!(empty($_POST))){
            $user =  User::validateRegistration($_POST);
            if ($user instanceof User) {
                    EmailSender::send($user, 'Активация', 'userActivation.php', [
                    'userId' => $user->getId(),
                    'code' => $code
                    ]);
                UsersAuthService::createToken($user);
            header('Location: /');
            exit();
            return;
    }
    }
}
    catch (MissingDataException $e) {
    $this->view->renderHtml('main/registrationPage.php',['error' => $e->getMessage()]);
    return;
    }
    $this->view->renderHtml('main/registrationPage.php');
}

public function myAccount()
{
        try {
            $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            preg_match('/users\/account\/(\d+)/', $urlPath, $matches);
            $userId = $matches[1] ?? 0;
            $user = User::getById($userId);
            $watcher = UsersAuthService::getUserByToken();
            $isProfileOwner = ($watcher->getId() == $user->getId()) ? true : false;
            $user_images = Image::queryToUsersImagesTable($user->getId());
        } catch (MissingDataException $e) {
            $this->view->renderHtml('main/loginPage.php', ['error' => $e->getMessage()]);
            return; 
        }
        $numberOfChats = Chat::countObjectsByColumn('author_id',$user->getId());
            $numberOfTickets = Ticket::countObjectsByColumn('author_id',$user->getId());
        if(!empty($user_images)) {
            $images = [];
            foreach ($user_images as $user_image) {
                $image = Image::getImage($user_image->image_id);
                array_push($images, $image);
            }
            $this->view->renderHtml('main/accountPage.php', ['user' => $user,'images' => $images,'numberOfChats' => $numberOfChats,'numberOfTickets' => $numberOfTickets,'isOwner' =>  $isProfileOwner]);
        }
        else {
            $this->view->renderHtml('main/accountPage.php', ['user' => $user,'numberOfChats' => $numberOfChats,'numberOfTickets' => $numberOfTickets, 'isOwner' =>  $isProfileOwner]);
        }
        }

public function editAccount()
{
    $user =  UsersAuthService::getUserByToken();
    if(!(empty($_POST))){
        $user->setName($_POST['username']);
        $user->setEmail($_POST['email']);
        if (!empty($_FILES['avatar']['name'])) {
            $imageIds = [];
            $tmp_name = $_FILES['avatar']['tmp_name'];
            $path = "../images/" . basename($_FILES['avatar']['name']);
            $moving_path = "../www/images/" . basename($_FILES['avatar']['name']);
            if (move_uploaded_file($tmp_name, $moving_path)) {
                $image = new Image();
                $image->setPath($path);
                $image->saveToDb();
                $imageIds[] = $image->getId();
            } else {
                echo "Произошла ошибка при загрузке файла.";
            }
            foreach($imageIds as $imageId) {
                $image->insertToUsersImagesTable($user->getId(), $imageId);
            }
        }

        $user->saveToDb();
    }
    else {
        $this->view->renderHtml('main/editAccount.php', ['user' => $user]);
}
    }

}