<?php


namespace App\View;


class View
{
    private $templatesPath;
    private $extraVars;

    /**
     * View constructor.
     *
     * @param string $templatesPath Путь к папке с шаблонами
     */
    public function __construct(string $templatesPath)
    {
        $this->templatesPath = $templatesPath;
    }

    /**
     * Функция для задания допольнительных переменных в Видах
     * @param string $name Имя
     * @param mixed $value Значение
     */
    public function setVar(string $name, $value): void
    {
        $this->extraVars[$name] = $value;
    }

    /**
     * Отображение HTML
     * @param string $templateName Имя шаблона
     * @param array $vars Массив данных
     * @param int $code Код состояния
     */
    public function renderHtml(string $templateName, array $vars = [], int $code = 200)
    {
        http_response_code($code);
        extract($vars);

        ob_start();
        include $this->templatesPath . '/' . $templateName;
        $buffer = ob_get_contents();
        ob_end_clean();

        echo $buffer;
    }
}