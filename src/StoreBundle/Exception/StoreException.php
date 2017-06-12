<?php

namespace StoreBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class StoreException
 * @package StoreBundle\Exception
 */
class StoreException extends HttpException
{
    /**
     * StoreException constructor.
     * @param string $message
     * @param null $code
     */
    public function __construct($message, $code = null)
    {
        parent::__construct($this->getCode(), $message);
    }
}