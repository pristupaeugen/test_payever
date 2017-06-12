<?php

namespace ProductBundle\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class StoreException
 * @package ProductBundle\Exception
 */
class StoreException extends ProductException
{
    /**
     * @var int
     */
    protected $code = Response::HTTP_UNAUTHORIZED;
}