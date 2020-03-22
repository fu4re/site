<?php
use PHPUnit\Framework\TestCase;

use App\Exceptions\DBException;
use App\Services\Database;

class DatabaseTest extends TestCase
{
    protected $dbOptions;

    public function setUp(): void
    {
        $this->dbOptions =  (__DIR__.'/../src/settings.php')['db'] ?? [
                'host' => 'localhost',
                'dbname' => 'test_site',
                'user' => 'root',
                'password' => '',
            ];
    }

    /**
     * @covers Database::setOptions
     * @throws DBException
     */
    public function testExceptionWhenOptionsAreNotSet()
    {
        $this->expectException(DBException::class);
        $this->expectErrorMessage('Ошибка. Не хватает параметров');
        Database::setOptions([]);
    }

    /**
     * @covers Database::getInstance
     * @dataProvider additionProvider2
     * @throws DBException
     */
    public function testInstanceExceptionOnMissingOptions(array $expectedOptions)
    {
        $this->expectException(DBException::class);
        $this->expectErrorMessage('Ошибка. Не заданы параметры подключения');
        Database::getInstance();
    }

    /**
     * @depends testInstanceExceptionOnMissingOptions
     * @covers Database::setOptions
     * @dataProvider additionProvider1
     * @throws DBException
     */
    public function testExceptionWhenOptionsAreNotStrings(array $expectedOptions)
    {
        $this->expectException(DBException::class);
        $this->expectErrorMessage('Ошибка. Параметры не строки');
        Database::setOptions($expectedOptions);
    }

    public function additionProvider1()
    {
        return [
            [[
                'host' => 'localhost',
                'dbname' => 'test_site',
                'user' => 123,
                'password' => '',
            ]],
            [[
                'host' => 213,
                'dbname' => 'test_site',
                'user' => '',
                'password' => '',
            ]],
            [[
                'host' => 'localhost',
                'dbname' => 123,
                'user' => 'root',
                'password' => '',
            ]],
            [[
                'host' => 'localhost',
                'dbname' => 'test_site',
                'user' => 'root',
                'password' => 321,
            ]],
        ];
    }

    public function additionProvider2()
    {
        return [
            [[
                'dbname' => 'test_site',
                'user' => 123,
                'password' => '',
            ]],
            [[
                'host' => 213,
                'user' => '',
                'password' => '',
            ]],
            [[
                'host' => 'localhost',
                'dbname' => 123,
                'password' => '',
            ]],
            [[
                'host' => 'localhost',
                'dbname' => 'test_site',
                'user' => 'root',
            ]],
        ];
    }
}