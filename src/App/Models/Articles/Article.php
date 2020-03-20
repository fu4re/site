<?php


namespace App\Models\Articles;

use App\Models\ActiveRecordEntity;
use App\Models\Users\User;

class Article extends ActiveRecordEntity
{
    /** @var string  */
    private $title;
    /** @var string  */
    private $text;
    /** @var string */
    private $authorId;
    /** @var string */
    private $createdAt;

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
     * Вернуть все записи статей
     * @var $param array Массив параметров
     *
     * @return array Массив статей
     */
    public static function FindAll(array $param = []) : array
    {
        $db = new Database();
        return $db->query('SELECT * FROM `'. static::getTableName() .'`;', $param, static::class);
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