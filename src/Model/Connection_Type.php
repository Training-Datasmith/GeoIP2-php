<?php

declare (strict_types=1);
namespace Geo_Ip2\Model;

use Geo_Ip2\Util;
/**
 * This class provides the GeoIP2 Connection-Type model.
 */
class Connection_Type implements \JsonSerializable
{
    /**
     * @var string|null The connection type may take the
     *                  following values: "Dialup", "Cable/DSL", "Corporate", "Cellular", and
     *                  "Satellite". Additional values may be added in the future.
     */
    public readonly ?string $connection_type;
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
        $this->connection_type = $raw['connection_type'] ?? null;
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
        if ($this->connection_type !== null) {
            $js['connection_type'] = $this->connection_type;
        }
        $js['ip_address'] = $this->ip_address;
        $js['network'] = $this->network;
        return $js;
    }
}