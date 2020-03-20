<?php


namespace App\Services;


class Database
{
    /** @var \PDO */
    private $pdo;

    /**
     *  Установления соединения с БД
     */
    public function  __construct()
    {
        $dbOptions = (require __DIR__.'/../../settings.php')['db'];

        $this->pdo = new \PDO(
            'mysql:host='.$dbOptions['host'].';dbname='.$dbOptions['dbname'],
            $dbOptions['user'],
            $dbOptions['password']
        );
        $this->pdo->exec('SET NAMES UTF8');
    }

    /**
     * Выполнение запроса к БД
     * @param string $sql SQL-запрос
     * @param array $params Параметры
     *
     * @return array|null Массив данных или null
     */
    public function query(string $sql, $params = []) : ?array
    {
        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute($params);

        if(false === $result)
        {
            return null;
        }

        return  $sth->fetchAll();
    }
}