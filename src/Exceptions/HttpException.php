<?php
namespace Young\Framework\Exceptions;

class HttpException extends Exception{
    public $code = 400;
    public $message = "HTTP Exception";
}