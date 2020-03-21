<?php
class Registry
{
    private $vars = array();


    /**
     * @param string $value
     * @param mixed $value
     */
    public function __set($index, $value) : void {
        $this->vars[$index] = $value;
    }

    /**
     * @param mixed $index
     * @return mixed
     */
    public function __get($index) {
        return $this->vars[$index];
    }

}