<?php


namespace App\Controllers;


use App\Services\Database;
use App\View\View;

/**
 * Контроллер страницы статей
 * Class ArticlesController
 * @package App\Controllers
 */
class ArticlesController
{
    /** @var View */
    private $view;
    /** @var Database*/
    private $db;

    /**
     * Запуск контроллера и инициализация вида
     * ArticlesController constructor
     */
    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../../../templates');
        $this->db = new Database();
    }

    /**
     * Показать одну статью
     * @param int $articleId Id статьи
     */
    public function view(int $articleId)
    {
        $result = $this->db->query(
            'SELECT * FROM `articles` WHERE id = :id;',
            [':id' => $articleId]
        );

        if ($result === []) // 404
        {
            $this->view->renderHtml('errors/404.php', [], 404);
            return;
        }

        $this->view->renderHtml('articles/view.php', ['article' => $result[0]]);
    }
}