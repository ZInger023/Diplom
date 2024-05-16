<?php

namespace TechSupport\Controllers;

use TechSupport\View\View;
use TechSupport\Models\Users\User;
use TechSupport\Models\Users\UserActivationService;
use TechSupport\Models\Users\UsersAuthService;
//use TechSupport\Services\EmailSender;
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
       //var_dump($user);
       if ($user instanceof User) {
        $code = UserActivationService::createActivationCode($user);

        /*EmailSender::send($user, 'Активация', 'userActivation.php', [
            'userId' => $user->getId(),
            'code' => $code
        ]); */
        UsersAuthService::createToken($user);
        header('Location: /');
        exit();
        //$this->view->renderHtml('tickets/showAllTickets.php',['user' => $user]);
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
}