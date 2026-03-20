<?php

declare(strict_types=1);

/**
 * Example: Look up geolocation data from a local MaxMind DB (.mmdb) file.
 *
 * Download GeoLite2 databases for free at https://dev.maxmind.com/geoip/geolite2-free-geolocation-data
 */

require_once __DIR__ . '/../vendor/autoload.php';

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;

$dbPath = '/path/to/GeoLite2-City.mmdb'; // adjust to your local path

$reader = new Reader($dbPath);

try {
    $record = $reader->city('128.101.101.101');

    echo 'Country:   ' . ($record->country->name ?? 'n/a') . PHP_EOL;
    echo 'ISO code:  ' . ($record->country->isoCode ?? 'n/a') . PHP_EOL;
    echo 'City:      ' . ($record->city->name ?? 'n/a') . PHP_EOL;
    echo 'Latitude:  ' . ($record->location->latitude ?? 'n/a') . PHP_EOL;
    echo 'Longitude: ' . ($record->location->longitude ?? 'n/a') . PHP_EOL;

} catch (AddressNotFoundException $e) {
    echo 'Address not found in database.' . PHP_EOL;
} finally {
    $reader->close();
}
