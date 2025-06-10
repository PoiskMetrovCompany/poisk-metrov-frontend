<?php

namespace App\Core\Interfaces\Services;

interface HandlerInterface
{
    public function setNext(HandlerInterface $handler): HandlerInterface;
    public function handle(?array $attributes): ?array;
}
