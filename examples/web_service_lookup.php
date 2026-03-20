<?php

declare(strict_types=1);

/**
 * Example: Look up IP geolocation data via the MaxMind GeoIP2 web service.
 *
 * Requires a MaxMind account ID and licence key.
 * @see https://www.maxmind.com/en/geoip2-services-and-databases
 */

require_once __DIR__ . '/../vendor/autoload.php';

use GeoIp2\WebService\Client;
use GeoIp2\Exception\AddressNotFoundException;
use GeoIp2\Exception\AuthenticationException;

$accountId  = (int) getenv('MAXMIND_ACCOUNT_ID');
$licenseKey = (string) getenv('MAXMIND_LICENSE_KEY');

$client = new Client($accountId, $licenseKey);

try {
    // Look up the requesting IP address (pass an explicit IP string for server-side use).
    $record = $client->city('128.101.101.101');

    echo 'Country:      ' . ($record->country->name ?? 'n/a') . PHP_EOL;
    echo 'ISO code:     ' . ($record->country->isoCode ?? 'n/a') . PHP_EOL;
    echo 'City:         ' . ($record->city->name ?? 'n/a') . PHP_EOL;
    echo 'Latitude:     ' . ($record->location->latitude ?? 'n/a') . PHP_EOL;
    echo 'Longitude:    ' . ($record->location->longitude ?? 'n/a') . PHP_EOL;
    echo 'Time zone:    ' . ($record->location->timeZone ?? 'n/a') . PHP_EOL;
    echo 'Postal code:  ' . ($record->postal->code ?? 'n/a') . PHP_EOL;

} catch (AddressNotFoundException $e) {
    echo 'Address not found: ' . $e->getMessage() . PHP_EOL;
} catch (AuthenticationException $e) {
    echo 'Authentication failed: ' . $e->getMessage() . PHP_EOL;
}
