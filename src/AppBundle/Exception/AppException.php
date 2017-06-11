<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class AppException
 * @package AppBundle\Exception
 */
class AppException extends HttpException
{
    /**
     * AppException constructor.
     * @param string $message
     * @param null $code
     */
    public function __construct($message, $code = null)
    {
        parent::__construct($this->getCode(), $message);
    }
}