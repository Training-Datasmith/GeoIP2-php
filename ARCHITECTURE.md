# GeoIP2-php Architecture

## Purpose

MaxMind's official PHP client for GeoIP2/GeoLite2 data, supporting both the
MaxMind web services (Country, City Plus, Insights) and local MaxMind DB (.mmdb)
binary database files.

## Directory Structure

```
src/GeoIp2/
  Database/
    Reader.php              — reads local .mmdb files (wraps maxmind-db/reader)
  Exception/
    AddressNotFoundException.php
    AuthenticationException.php
    GeoIp2Exception.php     — base exception
    HttpException.php
    InvalidRequestException.php
    OutOfQueriesException.php
  Model/
    AnonymousIp.php         — anonymous IP detection (database only)
    AnonymousPlus.php       — extended anonymous IP data
    Asn.php                 — autonomous system number
    City.php                — city + country data
    ConnectionType.php
    Country.php             — country data (base model)
    Domain.php
    Enterprise.php          — enterprise data (extends City)
    Insights.php            — all available data (web service only)
    Isp.php
  Record/
    AbstractNamedRecord.php — base: names / locale selection
    AbstractPlaceRecord.php — base: confidence + geonameId
    Anonymizer.php
    City.php / Continent.php / Country.php / Location.php / MaxMind.php
    Postal.php / RepresentedCountry.php / Subdivision.php / Traits.php
  WebService/
    Client.php              — HTTP client for MaxMind GeoIP2 web services
  ProviderInterface.php     — shared interface for Database\Reader and WebService\Client
  Util.php                  — IP validation helpers
examples/
  benchmark.php             — database reader benchmark
```

## Key Design Decisions

- **Readonly properties (PHP 8.1+)**: all model and record fields are `readonly`
  so that response objects are immutable after construction.
- **Array constructor pattern**: models are constructed from raw associative arrays
  using `$raw['snake_case_key'] ?? null` — making deserialization from both JSON
  and MMDB data uniform.
- **JsonSerializable**: all models/records implement `\JsonSerializable`; only
  non-null values are included to keep payloads compact.
- **Shared `ProviderInterface`**: `Database\Reader` and `WebService\Client` share
  the same interface so callers can swap between local DB and remote API without
  code changes.
- **Locale-aware names**: `AbstractNamedRecord` iterates a locale preference list
  to return the best available place name.

## Extension Points

- Implement `ProviderInterface` to back the API with a different data source.
- Pass custom `$locales` array to `WebService\Client` for localised place names.
- Use `Database\Reader` for high-volume/offline scenarios; `WebService\Client`
  for small volumes where MMDB files are impractical.

## Dependency Flow

```
Consumer
  ├── WebService\Client  (HTTP via maxmind/web-service-common)
  │     └── Model\{Country, City, Insights} → Record\*
  └── Database\Reader    (binary via maxmind-db/reader)
        └── Model\{Country, City, AnonymousIp, Asn, …} → Record\*
```
