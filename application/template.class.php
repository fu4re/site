<?php
class Template
{
    private $registry;
    private $vars = array();

    /**
     * @return void
     */
    public function __construct($registry) {
        $this->registry = $registry;
    }

    /**
     * @param string $index
     * @param mixed $value
     */
    public function __set($index, $value) : void {
        $this->vars[$index] = $value;
    }

    function show($name) {
        $path = __SITE_PATH. '/views/'.$name.'.php';

        if (file_exists($path) == false) {
            throw new Exception('Template not found in '. $path);
            return false;
        }

        foreach ($this->vars as $key => $value) {
            $$key = $value;
        }

        include $path;
    }
}