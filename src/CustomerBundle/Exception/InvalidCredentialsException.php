<?php

namespace CustomerBundle\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class InvalidCredentialsException
 * @package CustomerBundle\Exception
 */
class InvalidCredentialsException extends CustomerException
{
    /**
     * @var int
     */
    protected $code = Response::HTTP_BAD_REQUEST;
}