<?php

namespace BusinessBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthenticationException
 * @package BusinessBundle\Exception
 */
class AuthenticationException extends HttpException
{
    /**
     * @var int
     */
    protected $code = Response::HTTP_UNAUTHORIZED;
}