<?php

namespace ProductBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ProductException
 * @package ProductBundle\Exception
 */
class ProductException extends HttpException
{
    /**
     * ProductException constructor.
     * @param string $message
     * @param null $code
     */
    public function __construct($message, $code = null)
    {
        parent::__construct($this->getCode(), $message);
    }
}