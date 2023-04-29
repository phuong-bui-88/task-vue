<?php

namespace App\Exceptions;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class InvalidLoginException extends Exception
{
    public function __construct()
    {
    }

    public function render() {
        return response(['name' => ['Invalid username, email or password']], Response::HTTP_NOT_FOUND);
    }
}
