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
        $this->db = new Database();
    }

    /**
     * Главный метод
     */
    public function main()
    {
        $articles = $this->db->query('SELECT * FROM `articles`', [], Article::class);
        $this->view->renderHtml('main/main.php', ['articles' => $articles]);
    }
}