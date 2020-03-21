<?php
use PHPUnit\Framework\TestCase;

class SettingsTest extends TestCase
{
    protected $pathToSettings;

    public function setUp(): void
    {
        $this->pathToSettings =  __DIR__.'/../src/settings.php';
    }

    /**
     * @coversNothing
     */
    public function testSettingsFileExist(): void
    {
        $this->assertFileExists($this->pathToSettings);
    }

    /**
     * @coversNothing
     * @depends testSettingsFileExist
     */
    public function testDatabaseConfigurationCorrect (): void
    {
        $settings = $this->getSettings();
        $this->assertIsArray($settings);
        $this->assertArrayHasKey('db', $settings, 'Не задана конфигурация подключения к БД');
        $this->assertIsArray($settings['db']);

        $this->assertCount(4, $settings['db']);
        $this->assertArrayHasKey('host', $settings['db']);
        $this->assertArrayHasKey('dbname', $settings['db']);
        $this->assertArrayHasKey('user', $settings['db']);
        $this->assertArrayHasKey('password', $settings['db']);
    }

    /**
     * @coversNothing
     */
    public function getSettings()
    {
        return (require $this->pathToSettings);
    }
}