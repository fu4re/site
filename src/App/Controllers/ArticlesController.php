<?php


namespace App\Controllers;

use App\Exceptions\NotFoundException;
use App\Models\Articles\Article;
use App\Models\Users\User;
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

    /**
     * Запуск контроллера и инициализация вида
     * ArticlesController constructor
     */
    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../../../templates');
    }

    /**
     * Показать статью
     * @param int $articleId Id статьи
     */
    public function view(int $articleId)
    {
        $article = Article::getById($articleId);

        if ($article === null) // 404
        {
            $this->view->renderHtml('errors/404.php', [], 404);
            return;
        }

        $articleAuthor = User::getById($article->getAuthorId());

        $this->view->renderHtml('articles/view.php', [
            'article' => $article,
            'author' => $articleAuthor
        ]);
    }

    /**
     * Отредактировать статью
     * @param int $articleId Id статьи
     */
    public function edit(int $articleId): void
    {
        $article = Article::getById($articleId);

        if ($article === null) {
            throw new NotFoundException();
        }

        $article->setTitle('Новое название статьи');
        $article->setText('Новый текст статьи');

        $article->save();
    }

    /**
     * Добавить новую статью
     */
    public function add(): void
    {
        $author = User::getById(1);

        $article = new Article();
        $article->setAuthor($author);
        $article->setTitle('Новое название статьи');
        $article->setText('Новый текст статьи');

        $article->save();
    }
}