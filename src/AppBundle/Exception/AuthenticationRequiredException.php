<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthenticationRequiredException
 * @package AppBundle\Exception
 */
class AuthenticationRequiredException extends AppException
{
    /**
     * @var int
     */
    protected $code = Response::HTTP_UNAUTHORIZED;
}