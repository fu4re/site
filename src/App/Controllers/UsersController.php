<?php


namespace App\Controllers;

use App\Exceptions\AuthorizationException;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\NotFoundException;
use App\Models\Users\User;
use App\Services\EmailSender;
use App\Services\UsersActivationService;
use App\Services\UsersAuthorizationService;
use http\Header;

class UsersController extends AbstractController
{
    /**
     * Регистрация пользователя
     * @throws \App\Exceptions\DBException
     */
    public function signUp()
    {
        if (!empty($_POST)) {
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

    /**
     * Активация пользователя
     *
     * @param int|string $userId
     * @param string $activationCode
     *
     * @throws NotFoundException
     * @throws \App\Exceptions\DBException
     */
    public function activate( $userId, string $activationCode)
    {
        $user = User::getById((int)$userId);
        if ($user == null)
        {
            throw new NotFoundException();
        }
        $isCodeValid = UsersActivationService::checkActivationCode($user, $activationCode);
        if ($isCodeValid) {
            $user->activate();
            $this->view->renderHtml('users/activateSuccessful.php');
        }else{
            throw new NotFoundException();
        }
    }

    /**
     * Авторизация пользователя
     */
    public function signIn()
    {
        if($this->user != null)
        {
            header('Location: /', true, 303); //Пользователь уже авторизовался
            exit;
        }
        if (!empty($_POST)) {
            try {
                $this->user = User::login($_POST);
                UsersAuthorizationService::createToken($this->user);
                header('Location: /', true, 303); //Пользователь уже авторизовался
                exit;
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/login.php', ['error' => $e->getMessage()]);
                return;
            }
        }
        $this->view->renderHtml('users/login.php');
    }

    public function exit()
    {
        UsersAuthorizationService::removeToken();
        header('Location: /', true, 303); //Пользователь уже авторизовался
        exit;
    }
}