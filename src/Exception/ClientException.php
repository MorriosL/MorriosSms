<?php


namespace Morrios\Sms\Exception;


use Throwable;

/**
 * Class ClientException
 * @package Morrios\Sms\Exceptions
 */
class ClientException extends MorriosException
{
    /**
     * ClientException constructor.
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errorCode    = $code;
        $this->errorMessage = $message;
    }
}
