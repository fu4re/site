<?php

namespace App\Controllers;

use App\Models\Articles\Article;
use App\Services\Database;
use App\View\View;

/**
 * Главный контроллер
 * Class MainController
 * @package App\Controllers
 */
class MainController
{
    /** @var View */
    private $view;
    /** @var Database*/
    private $db;

    /**
     * Запуск контроллера и инициализация вида
     * MainController constructor
     */
    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../../../templates');
    }

    /**
     * Главный метод
     */
    public function main()
    {
        $articles = Article::FindAll();
        $this->view->renderHtml('main/main.php', ['articles' => $articles]);
    }
}