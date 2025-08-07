<?php

namespace App\Yandex;

class Category
{
    public string $class;
    public string $name;

    public function __construct($category) {
        $this->class = $category->class;
        $this->name = $category->name;
    }
}