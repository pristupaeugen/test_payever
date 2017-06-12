<?php

namespace StoreBundle\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class ParamIsNotSetException
 * @package StoreBundle\Exception
 */
class ParamIsNotSetException extends StoreException
{
    /**
     * @var int
     */
    protected $code = Response::HTTP_UNAUTHORIZED;
}