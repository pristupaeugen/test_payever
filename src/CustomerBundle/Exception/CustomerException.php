<?php

namespace CustomerBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class CustomerException
 * @package CustomerBundle\Exception
 */
class CustomerException extends HttpException
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