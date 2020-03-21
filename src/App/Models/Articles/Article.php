<?php


namespace App\Models\Articles;

use App\Models\ActiveRecordEntity;
use App\Models\Users\User;

class Article extends ActiveRecordEntity
{
    /** @var string  */
    protected $title;
    /** @var string  */
    protected $text;
    /** @var string */
    protected $authorId;
    /** @var string */
    protected $createdAt;

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
        return User::getById($this->authorId);
    }

    public function setTitle(string $new_title) : void
    {
        $this->title = $new_title;
    }

    public function setText(string $new_text) : void
    {
        $this->text = $new_text;
    }

    public function setAuthor(User $author) : void
    {
        $this->authorId = $author->getId();
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

    /**
     * @return int Возвращаем id автора
     */
    public function getAuthorId(): int
    {
        return (int) $this->authorId;
    }

    /**
     * @return string Возвращает название таблицы
     */
    protected static function getTableName(): string
    {
        return 'articles';
    }

    private function underscoreToCamelCase(string $source): string
    {
        return lcfirst(str_replace('_', '', ucwords($source, '_')));
    }
}