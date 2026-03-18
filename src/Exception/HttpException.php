<?php

declare(strict_types=1);

namespace GeoIp2\Exception;

/**
 *  This class represents an HTTP transport error.
 */
class HttpException extends GeoIp2Exception
{
    public function __construct(
        string $message,
        int $httpStatus,
        /**
         * The URI queried.
         */
        public string $uri,
        ?\Exception $previous = null
    ) {
        parent::__construct($message, $httpStatus, $previous);
    }
}
