<?php

namespace TechSupport\Controllers;

use TechSupport\Models\Users\User;
use TechSupport\Services\EmailSender;

class BaseController
{
    public function sendEmailToAllManagers($subject, $username)
    {
            $managers = User::findElementByColumn('role','manager');
            foreach ($managers as $manager) {
                EmailSender::send($manager, $subject, 'managerEmail.php', [
                    'username' => $username,
                    'subject' => $subject
                    ]);
            }
        
    }

    public function sendEmailToManager($subject, $ticket)
    {
            $manager = User::getById($ticket->getManagerId());
            if (!empty($manager)) {
                EmailSender::send($manager, $subject, 'chatMessageEmail.php', [
                    'title' => $ticket->getTitle(),
                    'subject' => $subject
                    ]);
            }
            else {
                  return;
            }
    }

    public function sendEmailToUser($subject, $ticket)
    {
            $manager = User::getById($ticket->getAuthorId());
            if (!empty($manager)) {
                EmailSender::send($manager, $subject, 'chatMessageEmail.php', [
                    'title' => $ticket->getTitle(),
                    'subject' => $subject
                    ]);
            }
            else {
                  return;
            }
    }
}