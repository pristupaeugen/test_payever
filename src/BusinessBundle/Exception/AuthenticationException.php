<?php

namespace BusinessBundle\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthenticationException
 * @package BusinessBundle\Exception
 */
class AuthenticationException extends BusinessException
{
    /**
     * @var int
     */
    protected $code = Response::HTTP_UNAUTHORIZED;
}