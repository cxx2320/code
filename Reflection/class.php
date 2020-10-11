<?php

class Cxx
{
    public $name = '';

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
$reflect = new \ReflectionClass('Cxx');
// var_dump($reflect->getConstructor());
$instance = $reflect->newInstanceArgs(['sda']);
var_dump($instance);
$instance = $reflect->newInstanceArgs(['sad']);
var_dump($instance);
