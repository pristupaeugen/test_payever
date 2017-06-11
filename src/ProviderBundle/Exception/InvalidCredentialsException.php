<?php

namespace ProviderBundle\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class InvalidCredentialsException
 * @package ProviderBundle\Exception
 */
class InvalidCredentialsException extends ProviderException
{
    /**
     * @var int
     */
    protected $code = Response::HTTP_BAD_REQUEST;
}