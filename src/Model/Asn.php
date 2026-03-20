<?php

declare (strict_types=1);
namespace Geo_Ip2\Model;

use Geo_Ip2\Util;
/**
 * This class provides the GeoLite2 ASN model.
 */
class Asn implements \JsonSerializable
{
    /**
     * @var int|null the autonomous system number
     *               associated with the IP address
     */
    public readonly ?int $autonomous_system_number;
    /**
     * @var string|null the organization
     *                  associated with the registered autonomous system number for the IP
     *                  address
     */
    public readonly ?string $autonomous_system_organization;
    /**
     * @var string the IP address that the data in the model is
     *             for
     */
    public readonly string $ip_address;
    /**
     * @var string The network in CIDR notation associated with
     *             the record. In particular, this is the largest network where all of the
     *             fields besides $ipAddress have the same value.
     */
    public readonly string $network;
    /**
     * @ignore
     *
     * @param array<string, mixed> $raw
     */
    public function __construct(array $raw)
    {
        $this->autonomous_system_number = $raw['autonomous_system_number'] ?? null;
        $this->autonomous_system_organization = $raw['autonomous_system_organization'] ?? null;
        $ip_address = $raw['ip_address'];
        $this->ip_address = $ip_address;
        $this->network = Util::cidr($ip_address, $raw['prefix_len']);
    }
    /**
     * @return array<string, mixed>|null
     */
    public function jsonSerialize(): ?array
    {
        $js = [];
        if ($this->autonomous_system_number !== null) {
            $js['autonomous_system_number'] = $this->autonomous_system_number;
        }
        if ($this->autonomous_system_organization !== null) {
            $js['autonomous_system_organization'] = $this->autonomous_system_organization;
        }
        $js['ip_address'] = $this->ip_address;
        $js['network'] = $this->network;
        return $js;
    }
}