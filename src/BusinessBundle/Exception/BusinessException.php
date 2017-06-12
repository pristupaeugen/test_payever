<?php

namespace BusinessBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class BusinessException
 * @package BusinessBundle\Exception
 */
class BusinessException extends HttpException
{
    /**
     * CustomerException constructor.
     * @param string $message
     * @param null $code
     */
    public function __construct($message, $code = null)
    {
        parent::__construct($this->getCode(), $message);
    }
}