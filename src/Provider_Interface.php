<?php

declare (strict_types=1);
namespace Geo_Ip2;

interface Provider_Interface
{
    /**
     * @param string $ipAddress an IPv4 or IPv6 address to lookup
     *
     * @return Model\Country a Country model for the requested IP address
     */
    public function country(string $ip_address): Model\Country;
    /**
     * @param string $ipAddress an IPv4 or IPv6 address to lookup
     *
     * @return Model\City a City model for the requested IP address
     */
    public function city(string $ip_address): Model\City;
}