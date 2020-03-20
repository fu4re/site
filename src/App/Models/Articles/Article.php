<?php


namespace App\Models\Articles;


use App\Models\Users\User;

class Article
{
    /** @var int */
    private $id;
    /** @var string  */
    private $title;
    /** @var string  */
    private $text;
    /** @var string */
    private $authorId;
    /** @var string */
    private $createdAt;

    /**
     * @return int Вернуть ID
     */
    public function getId(): int
    {
        return $this->id;
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

    /**
     * Попытка задать закрытые свойства
     * @param $name mixed Имя свойства
     * @param $value mixed Значение свойства
     */
    public function __set($name, $value)
    {
        $camelCaseName = $this->underscoreToCamelCase($name);
        $this->$camelCaseName = $value;
    }

    private function underscoreToCamelCase(string $source): string
    {
        return lcfirst(str_replace('_', '', ucwords($source, '_')));
    }
}