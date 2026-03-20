<?php

declare (strict_types=1);
namespace Geo_Ip2\Model;

use Geo_Ip2\Util;
/**
 * This class provides the GeoIP2 Anonymous IP model.
 */
class Anonymous_Ip implements \JsonSerializable
{
    /**
     * @var bool this is true if the IP address belongs to
     *           any sort of anonymous network
     */
    public readonly bool $is_anonymous;
    /**
     * @var bool This is true if the IP address is
     *           registered to an anonymous VPN provider. If a VPN provider does not
     *           register subnets under names associated with them, we will likely only
     *           flag their IP ranges using the isHostingProvider property.
     */
    public readonly bool $is_anonymous_vpn;
    /**
     * @var bool this is true if the IP address belongs
     *           to a hosting or VPN provider (see description of isAnonymousVpn property)
     */
    public readonly bool $is_hosting_provider;
    /**
     * @var bool this is true if the IP address belongs to
     *           a public proxy
     */
    public readonly bool $is_public_proxy;
    /**
     * @var bool this is true if the IP address is
     *           on a suspected anonymizing network and belongs to a residential ISP
     */
    public readonly bool $is_residential_proxy;
    /**
     * @var bool this is true if the IP address is a Tor
     *           exit node
     */
    public readonly bool $is_tor_exit_node;
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
        $this->is_anonymous = $raw['is_anonymous'] ?? false;
        $this->is_anonymous_vpn = $raw['is_anonymous_vpn'] ?? false;
        $this->is_hosting_provider = $raw['is_hosting_provider'] ?? false;
        $this->is_public_proxy = $raw['is_public_proxy'] ?? false;
        $this->is_residential_proxy = $raw['is_residential_proxy'] ?? false;
        $this->is_tor_exit_node = $raw['is_tor_exit_node'] ?? false;
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
        $js['is_anonymous'] = $this->is_anonymous;
        $js['is_anonymous_vpn'] = $this->is_anonymous_vpn;
        $js['is_hosting_provider'] = $this->is_hosting_provider;
        $js['is_public_proxy'] = $this->is_public_proxy;
        $js['is_residential_proxy'] = $this->is_residential_proxy;
        $js['is_tor_exit_node'] = $this->is_tor_exit_node;
        $js['ip_address'] = $this->ip_address;
        $js['network'] = $this->network;
        return $js;
    }
}