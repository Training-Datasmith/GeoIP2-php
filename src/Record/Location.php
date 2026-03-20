<?php

declare (strict_types=1);
namespace Geo_Ip2\Record;

/**
 * Contains data for the location record associated with an IP address.
 *
 * This record is returned by all location services and databases besides
 * Country.
 */
class Location implements \JsonSerializable
{
    /**
     * @var int|null The average income in US dollars
     *               associated with the requested IP address. This attribute is only available
     *               from the Insights service.
     */
    public readonly ?int $average_income;
    /**
     * @var int|null The approximate accuracy radius in
     *               kilometers around the latitude and longitude for the IP address. This is
     *               the radius where we have a 67% confidence that the device using the IP
     *               address resides within the circle centered at the latitude and longitude
     *               with the provided radius.
     */
    public readonly ?int $accuracy_radius;
    /**
     * @var float|null The approximate latitude of the location
     *                 associated with the IP address. This value is not precise and should not be
     *                 used to identify a particular address or household.
     */
    public readonly ?float $latitude;
    /**
     * @var float|null The approximate longitude of the location
     *                 associated with the IP address. This value is not precise and should not be
     *                 used to identify a particular address or household.
     */
    public readonly ?float $longitude;
    /**
     * @var int|null the metro code is a no-longer-maintained code for targeting
     *               advertisements in Google
     *
     * @deprecated
     */
    public readonly ?int $metro_code;
    /**
     * @var int|null The estimated population per square
     *               kilometer associated with the IP address. This attribute is only available
     *               from the Insights service.
     */
    public readonly ?int $population_density;
    /**
     * @var string|null The time zone associated with location, as
     *                  specified by the IANA Time Zone Database, e.g., "America/New_York". See
     *                  https://www.iana.org/time-zones.
     */
    public readonly ?string $time_zone;
    /**
     * @ignore
     *
     * @param array<string, mixed> $record
     */
    public function __construct(array $record)
    {
        $this->average_income = $record['average_income'] ?? null;
        $this->accuracy_radius = $record['accuracy_radius'] ?? null;
        $this->latitude = $record['latitude'] ?? null;
        $this->longitude = $record['longitude'] ?? null;
        $this->metro_code = $record['metro_code'] ?? null;
        $this->population_density = $record['population_density'] ?? null;
        $this->time_zone = $record['time_zone'] ?? null;
    }
    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $js = [];
        if ($this->average_income !== null) {
            $js['average_income'] = $this->average_income;
        }
        if ($this->accuracy_radius !== null) {
            $js['accuracy_radius'] = $this->accuracy_radius;
        }
        if ($this->latitude !== null) {
            $js['latitude'] = $this->latitude;
        }
        if ($this->longitude !== null) {
            $js['longitude'] = $this->longitude;
        }
        if ($this->metro_code !== null) {
            $js['metro_code'] = $this->metro_code;
        }
        if ($this->population_density !== null) {
            $js['population_density'] = $this->population_density;
        }
        if ($this->time_zone !== null) {
            $js['time_zone'] = $this->time_zone;
        }
        return $js;
    }
}