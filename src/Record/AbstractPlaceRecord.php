<?php

declare (strict_types=1);
namespace Geo_Ip2\Record;

abstract class Abstract_Place_Record extends Abstract_Named_Record
{
    /**
     * @var int|null A value from 0-100 indicating MaxMind's
     *               confidence that the location level is correct. This attribute is only available
     *               from the Insights service and the GeoIP2 Enterprise database.
     */
    public readonly ?int $confidence;
    /**
     * @var int|null The GeoName ID for the location level. This attribute
     *               is returned by all location services and databases.
     */
    public readonly ?int $geoname_id;
    /**
     * @ignore
     *
     * @param array<string, mixed> $record
     * @param list<string>         $locales
     */
    public function __construct(array $record, array $locales = ['en'])
    {
        parent::__construct($record, $locales);
        $this->confidence = $record['confidence'] ?? null;
        $this->geoname_id = $record['geoname_id'] ?? null;
    }
    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $js = parent::jsonSerialize();
        if ($this->confidence !== null) {
            $js['confidence'] = $this->confidence;
        }
        if ($this->geoname_id !== null) {
            $js['geoname_id'] = $this->geoname_id;
        }
        return $js;
    }
}