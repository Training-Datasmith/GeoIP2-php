<?php

declare (strict_types=1);
namespace Geo_Ip2\Record;

/**
 * Contains data about your account.
 *
 * This record is returned by all location services and databases.
 */
class Max_Mind implements \JsonSerializable
{
    /**
     * @var int|null the number of remaining queries you
     *               have for the service you are calling
     */
    public readonly ?int $queries_remaining;
    /**
     * @ignore
     *
     * @param array<string, mixed> $record
     */
    public function __construct(array $record)
    {
        $this->queries_remaining = $record['queries_remaining'] ?? null;
    }
    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $js = [];
        if ($this->queries_remaining !== null) {
            $js['queries_remaining'] = $this->queries_remaining;
        }
        return $js;
    }
}