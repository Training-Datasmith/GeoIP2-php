<?php

declare (strict_types=1);
namespace Geo_Ip2\Record;

/**
 * Contains data for the anonymizer record associated with an IP address.
 *
 * This record is returned by the GeoIP2 Insights web service.
 */
class Anonymizer implements \JsonSerializable
{
    /**
     * @var int|null A confidence score from 1-99 indicating our confidence that the IP
     *               address is a VPN. Currently, this is either 30 or 99. This attribute is
     *               only available from the GeoIP2 Insights web service.
     */
    public readonly ?int $confidence;
    /**
     * @var bool This is true if the IP address belongs to any sort of anonymous network.
     *           This attribute is only available from the GeoIP2 Insights web service.
     */
    public readonly bool $is_anonymous;
    /**
     * @var bool This is true if the IP address is registered to an anonymous VPN provider.
     *           If a VPN provider does not register subnets under names associated with them,
     *           we will likely only flag their IP ranges using the isHostingProvider property.
     *           This attribute is only available from the GeoIP2 Insights web service.
     */
    public readonly bool $is_anonymous_vpn;
    /**
     * @var bool This is true if the IP address belongs to a hosting or VPN provider (see
     *           description of isAnonymousVpn property). This attribute is only available from
     *           the GeoIP2 Insights web service.
     */
    public readonly bool $is_hosting_provider;
    /**
     * @var bool This is true if the IP address belongs to a public proxy. This attribute
     *           is only available from the GeoIP2 Insights web service.
     */
    public readonly bool $is_public_proxy;
    /**
     * @var bool This is true if the IP address is on a suspected anonymizing network and
     *           belongs to a residential ISP. This attribute is only available from the GeoIP2
     *           Insights web service.
     */
    public readonly bool $is_residential_proxy;
    /**
     * @var bool This is true if the IP address is a Tor exit node. This attribute is only
     *           available from the GeoIP2 Insights web service.
     */
    public readonly bool $is_tor_exit_node;
    /**
     * @var string|null The date the anonymizer network was last seen in YYYY-MM-DD format.
     *                  This attribute is only available from the GeoIP2 Insights web service.
     */
    public readonly ?string $network_last_seen;
    /**
     * @var string|null The name of the VPN provider, for example, NordVPN or SurfShark.
     *                  This attribute is only available from the GeoIP2 Insights web service.
     */
    public readonly ?string $provider_name;
    /**
     * @ignore
     *
     * @param array<string, mixed> $record
     */
    public function __construct(array $record)
    {
        $this->confidence = $record['confidence'] ?? null;
        $this->is_anonymous = $record['is_anonymous'] ?? false;
        $this->is_anonymous_vpn = $record['is_anonymous_vpn'] ?? false;
        $this->is_hosting_provider = $record['is_hosting_provider'] ?? false;
        $this->is_public_proxy = $record['is_public_proxy'] ?? false;
        $this->is_residential_proxy = $record['is_residential_proxy'] ?? false;
        $this->is_tor_exit_node = $record['is_tor_exit_node'] ?? false;
        $this->network_last_seen = $record['network_last_seen'] ?? null;
        $this->provider_name = $record['provider_name'] ?? null;
    }
    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $js = [];
        if ($this->confidence !== null) {
            $js['confidence'] = $this->confidence;
        }
        if ($this->is_anonymous !== false) {
            $js['is_anonymous'] = $this->is_anonymous;
        }
        if ($this->is_anonymous_vpn !== false) {
            $js['is_anonymous_vpn'] = $this->is_anonymous_vpn;
        }
        if ($this->is_hosting_provider !== false) {
            $js['is_hosting_provider'] = $this->is_hosting_provider;
        }
        if ($this->is_public_proxy !== false) {
            $js['is_public_proxy'] = $this->is_public_proxy;
        }
        if ($this->is_residential_proxy !== false) {
            $js['is_residential_proxy'] = $this->is_residential_proxy;
        }
        if ($this->is_tor_exit_node !== false) {
            $js['is_tor_exit_node'] = $this->is_tor_exit_node;
        }
        if ($this->network_last_seen !== null) {
            $js['network_last_seen'] = $this->network_last_seen;
        }
        if ($this->provider_name !== null) {
            $js['provider_name'] = $this->provider_name;
        }
        return $js;
    }
}