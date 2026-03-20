<?php

declare (strict_types=1);
namespace Geo_Ip2\Exception;

/**
 *  This class represents an HTTP transport error.
 */
class Http_Exception extends Geo_Ip2exception
{
    public function __construct(
        string $message,
        int $http_status,
        /**
         * The URI queried.
         */
        public string $uri,
        ?\Exception $previous = null
    )
    {
        parent::__construct($message, $http_status, $previous);
    }
}