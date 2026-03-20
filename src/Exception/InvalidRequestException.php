<?php

declare (strict_types=1);
namespace Geo_Ip2\Exception;

/**
 * This class represents an error returned by MaxMind's GeoIP2
 * web service.
 */
class Invalid_Request_Exception extends Http_Exception
{
    public function __construct(
        string $message,
        /**
         * The code returned by the MaxMind web service.
         */
        public string $error,
        int $http_status,
        string $uri,
        ?\Exception $previous = null
    )
    {
        parent::__construct($message, $http_status, $uri, $previous);
    }
}