<?php


namespace App\Models\Articles;


use App\Models\Users\User;

class Article
{
    private $title;
    private $text;
    private $author;

    public function __construct(string $title, string $text, User $author)
    {
        $this->title = $title;
        $this->text = $text;
        $this->author = $author;
    }

    /**
     * @return string Вернуть заголовок
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string Вернуть содержимое
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return User Вернуть автора
     */
    public function getAuthor(): User
    {
        return $this->author;
    }
}