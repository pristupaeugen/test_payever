<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserAuthenticationException
 * @package AppBundle\Exception
 */
class UserAuthenticationException extends AppException
{
    /**
     * @var int
     */
    protected $code = Response::HTTP_BAD_REQUEST;
}