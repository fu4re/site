<?php


namespace App\Controllers;

use App\Exceptions\InvalidArgumentException;
use App\Models\Users\User;
use App\Services\EmailSender;
use App\Services\UsersActivationService;

class UsersController extends AbstractController
{
    public function signUp()
    {
        if (!empty($_POST)) {
            var_dump($_POST);
            try {
                $user = User::signUp($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/signUp.php', ['error' => $e->getMessage()]);
                return;
            }

            if ($user instanceof User) {
                $code = UsersActivationService::createActivationCode($user);

                EmailSender::send($user, 'Активация', 'userActivation.php', [
                    'userId' => $user->getId(),
                    'code' => $code
                ]);
                $this->view->renderHtml('users/signUpSuccessful.php');
                return;
            }
        }

        $this->view->renderHtml('users/signUp.php');
    }
}