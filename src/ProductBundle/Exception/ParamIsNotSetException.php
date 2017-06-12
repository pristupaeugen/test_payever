<?php

namespace ProductBundle\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class ParamIsNotSetException
 * @package ProductBundle\Exception
 */
class ParamIsNotSetException extends ProductException
{
    /**
     * @var int
     */
    protected $code = Response::HTTP_UNAUTHORIZED;
}