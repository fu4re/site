<?php
Abstract Class baseController
{
    protected $registry;

    function __construct($registry) {
        $this->registry = $registry;
    }

    // containing index method
    abstract function index();
}