<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class TokenNotExistException
 * @package AppBundle\Exception
 */
class TokenNotExistException extends AppException
{
    /**
     * @var int
     */
    protected $code = Response::HTTP_NOT_FOUND;
}