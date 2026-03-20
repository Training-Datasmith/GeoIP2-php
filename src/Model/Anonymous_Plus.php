<?php

declare (strict_types=1);
namespace Geo_Ip2\Model;

/**
 * This class provides the GeoIP Anonymous Plus model.
 */
class Anonymous_Plus extends Anonymous_Ip
{
    /**
     * @var int|null a score ranging from 1 to 99 that is our percent
     *               confidence that the network is currently part of
     *               an actively used VPN service
     */
    public readonly ?int $anonymizer_confidence;
    /**
     * @var string|null The last day that the network was sighted in our
     *                  analysis of anonymized networks. This is in the ISO
     *                  8601 date format, e.g., "2025-04-21".
     */
    public readonly ?string $network_last_seen;
    /**
     * @var string|null The name of the VPN provider (e.g., NordVPN,
     *                  SurfShark, etc.) associated with the network.
     */
    public readonly ?string $provider_name;
    /**
     * @ignore
     *
     * @param array<string, mixed> $raw
     */
    public function __construct(array $raw)
    {
        parent::__construct($raw);
        $this->anonymizer_confidence = $raw['anonymizer_confidence'] ?? null;
        $this->network_last_seen = $raw['network_last_seen'] ?? null;
        $this->provider_name = $raw['provider_name'] ?? null;
    }
    /**
     * @return array<string, mixed>|null
     */
    public function jsonSerialize(): ?array
    {
        $js = parent::jsonSerialize();
        if ($this->anonymizer_confidence !== null) {
            $js['anonymizer_confidence'] = $this->anonymizer_confidence;
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