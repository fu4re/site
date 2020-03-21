<?php
class router
{
    private $registry;
    private $path;
    private $args = array();

    public $file;
    public $controller;
    public $action;

    function __construct($registry) {
        $this->registry = $registry;
    }

    //set controller dir path
    function setPath($path) {
        if(is_dir($path) == false) {
            throw new Exception('Invalid controller path: /'. $path. '/');
        }

        $this->path = $path;
    }
    //set the controllers path dir
    //$router->setPath (__SITE_PATH . 'controller');

    // if path was loaded - load the controller:
    private function getController() {
        $route = (empty($_GET['rt'])) ? '' : $_GET['rt'];

        if (empty($route)) {
            $route = 'index';
        }
        else {
            $parts = explode('/', $route);
            $this->controller = $parts[0];
            if(isset($parts[1])) {
                $this->action = $parts[1];
            }
        }

        if (empty($this->controller)) {
            $this->controller = 'index';
        }

        if (empty($this->action)) {
            $this->action = 'index';
        }

        $this->file = $this->path.'/'. $this->controller. '.php';
    }

    public function loader() {
        $this->getController();

        if(is_readable($this->file) == false) {
            echo $this->file;
            die('404 Not Found');
        }

        //include the controller
        include $this->file;

        // new controller class
        $class = $this->controller. 'Controller_';
        $controller = new $class($this->registry);

        if(is_callable(array($controller, $this->action)) == false) {
            $action = 'index';
        }
        else {
            $action = $this->action;
        }

        //run action
        $controller->$action();
    }

}