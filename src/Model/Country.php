<?php

declare (strict_types=1);
namespace Geo_Ip2\Model;

use Geo_Ip2\Record\Continent;
use Geo_Ip2\Record\Country as CountryRecord;
use Geo_Ip2\Record\Max_Mind;
use Geo_Ip2\Record\Represented_Country;
use Geo_Ip2\Record\Traits;
/**
 * Model class for the data returned by GeoIP2 Country web service and database.
 *
 * See https://dev.maxmind.com/geoip/docs/web-services?lang=en for more details.
 */
class Country implements \JsonSerializable
{
    /**
     * @var Continent continent data for the requested IP address
     */
    public readonly Continent $continent;
    /**
     * @var CountryRecord Country data for the requested IP address. This
     *                    object represents the country where MaxMind believes
     *                    the end user is located.
     */
    public readonly Country_Record $country;
    /**
     * @var MaxMind data related to your MaxMind account
     */
    public readonly Max_Mind $maxmind;
    /**
     * @var CountryRecord Registered country data for the requested IP address.
     *                    This record represents the country where the ISP has
     *                    registered a given IP block and may differ from the
     *                    user's country.
     */
    public readonly Country_Record $registered_country;
    /**
     * @var RepresentedCountry Represented country data for the requested IP
     *                         address. The represented country is used for
     *                         things like military bases. It is only present
     *                         when the represented country differs from the
     *                         country.
     */
    public readonly Represented_Country $represented_country;
    /**
     * @var Traits data for the traits of the requested IP address
     */
    public readonly Traits $traits;
    /**
     * @ignore
     *
     * @param array<string, mixed> $raw
     * @param list<string>         $locales
     */
    public function __construct(array $raw, array $locales = ['en'])
    {
        $this->continent = new Continent($raw['continent'] ?? [], $locales);
        $this->country = new Country_Record($raw['country'] ?? [], $locales);
        $this->maxmind = new Max_Mind($raw['maxmind'] ?? []);
        $this->registered_country = new Country_Record($raw['registered_country'] ?? [], $locales);
        $this->represented_country = new Represented_Country($raw['represented_country'] ?? [], $locales);
        $this->traits = new Traits($raw['traits'] ?? []);
    }
    /**
     * @return array<string, mixed>|null
     */
    public function jsonSerialize(): ?array
    {
        $js = [];
        $continent = $this->continent->jsonSerialize();
        if (!empty($continent)) {
            $js['continent'] = $continent;
        }
        $country = $this->country->jsonSerialize();
        if (!empty($country)) {
            $js['country'] = $country;
        }
        $maxmind = $this->maxmind->jsonSerialize();
        if (!empty($maxmind)) {
            $js['maxmind'] = $maxmind;
        }
        $registered_country = $this->registered_country->jsonSerialize();
        if (!empty($registered_country)) {
            $js['registered_country'] = $registered_country;
        }
        $represented_country = $this->represented_country->jsonSerialize();
        if (!empty($represented_country)) {
            $js['represented_country'] = $represented_country;
        }
        $traits = $this->traits->jsonSerialize();
        if (!empty($traits)) {
            $js['traits'] = $traits;
        }
        return $js;
    }
}