<?php

namespace App\Exceptions;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class InvalidLoginException extends Exception
{
    public $message;

    public function __construct($message = '')
    {
        $this->message = $message ?: 'Invalid username, email, or password';
        parent::__construct($this->message);
    }

    public function render() {
        return response(['name' => [$this->message]], Response::HTTP_NOT_FOUND);
    }
}
