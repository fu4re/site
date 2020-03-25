<?php

namespace App\Controllers;

use App\Services\UsersAuthorizationService;
use App\View\View;

abstract class AbstractController
{
    /** @var View */
    protected $view;

    /** @var User|null */
    protected $user;

    /**
     * AbstractController constructor.
     * @throws \App\Exceptions\AuthorizationException
     * @throws \App\Exceptions\DBException
     */
    public function __construct()
    {
        $this->user = UsersAuthorizationService::getUserByToken();
        $this->view = new View(__DIR__ . '/../../../templates');
        $this->view->setVar('user', $this->user);
    }
}