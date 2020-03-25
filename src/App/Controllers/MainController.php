<?php


namespace App\Controllers;

/**
 * Главный контроллер
 * Class MainController
 * @package App\Controllers
 */
class MainController extends AbstractController
{

    /**
     * Главный метод
     */
    public function main()
    {
        $this->view->renderHtml('main/main.php', []);
    }
}