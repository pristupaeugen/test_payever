<?php

namespace StoreBundle\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class BusinessException
 * @package StoreBundle\Exception
 */
class BusinessException extends StoreException
{
    /**
     * @var int
     */
    protected $code = Response::HTTP_UNAUTHORIZED;
}