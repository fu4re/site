<?php


namespace App\Models;

use App\Services\Database;

/**
 * Шаблон записи из БД ActiveRecord
 * Class ActiveRecordEntity
 * @package App\Models
 */
abstract class ActiveRecordEntity
{
    /** @var int */
    protected $id;

    /**
     * @return int Вернуть ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function __set(string $name, $value)
    {
        $camelCaseName = $this->underscoreToCamelCase($name);
        $this->$camelCaseName = $value;
    }

    private function underscoreToCamelCase(string $source): string
    {
        return lcfirst(str_replace('_', '', ucwords($source, '_')));
    }

    /**
     * @return static[] Вернуть все записи ищ таблицы
     */
    public static function findAll(): array
    {
        $db = new Database();
        return $db->query('SELECT * FROM `' . static::getTableName() . '`;', [], static::class);
    }

    /**
     * Выбрать 1 запись из таблицы по id
     * @param int $id
     * @return static|null
     */
    public static function getById(int $id): ?self
    {
        $db = new Database();
        $entities = $db->query(
            'SELECT * FROM `' . static::getTableName() . '` WHERE id=:id;',
            [':id' => $id],
            static::class
        );
        return $entities ? $entities[0] : null;
    }

    /**
     * @return string Название таблицы которой принадлежит запись
     */
    abstract protected static function getTableName(): string;
}