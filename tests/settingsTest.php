<?php
use PHPUnit\Framework\TestCase;

class SettingsTest extends TestCase
{
    protected $pathToSettings;

    public function setUp(): void
    {
        $this->pathToSettings =  __DIR__.'/../src/settings.php';
    }

    public function testIfSettingsExist(): void
    {
        $this->assertFileExists($this->pathToSettings);
    }

    /**
     * @depends testIfSettingsExist
     */
    public function testIfDBConfigurationCorrect (): void
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