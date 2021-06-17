<?php


namespace dutchie027\Thinq\Exceptions;


class InvalidParameterException extends ThinqAPIException
{
    public function __construct($message = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
