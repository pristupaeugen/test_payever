<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class TokenIsNotSetException
 * @package AppBundle\Exception
 */
class TokenIsNotSetException extends AppException
{
    /**
     * @var int
     */
    protected $code = Response::HTTP_UNAUTHORIZED;
}