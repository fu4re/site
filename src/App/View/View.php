<?php


namespace App\View;


class View
{
    private $templatesPath;

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