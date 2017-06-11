<?php

namespace ProviderBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ProviderException
 * @package ProviderBundle\Exception
 */
class ProviderException extends HttpException
{
    /**
     * ProviderException constructor.
     * @param string $message
     * @param null $code
     */
    public function __construct($message, $code = null)
    {
        parent::__construct($this->getCode(), $message);
    }
}