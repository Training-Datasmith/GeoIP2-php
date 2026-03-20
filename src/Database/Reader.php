<?php

declare (strict_types=1);
namespace Geo_Ip2\Database;

use Geo_Ip2\Exception\Address_Not_Found_Exception;
use Geo_Ip2\Model\Anonymous_Ip;
use Geo_Ip2\Model\Anonymous_Plus;
use Geo_Ip2\Model\Asn;
use Geo_Ip2\Model\City;
use Geo_Ip2\Model\Connection_Type;
use Geo_Ip2\Model\Country;
use Geo_Ip2\Model\Domain;
use Geo_Ip2\Model\Enterprise;
use Geo_Ip2\Model\Isp;
use Geo_Ip2\Provider_Interface;
use Max_Mind\Db\Reader as DbReader;
use Max_Mind\Db\Reader\Invalid_Database_Exception;
use Max_Mind\Db\Reader\Metadata;
/**
 * Instances of this class provide a reader for the GeoIP2 database format.
 * IP addresses can be looked up using the database specific methods.
 *
 * ## Usage ##
 *
 * The basic API for this class is the same for every database. First, you
 * create a reader object, specifying a file name. You then call the method
 * corresponding to the specific database, passing it the IP address you want
 * to look up.
 *
 * If the request succeeds, the method call will return a model class for
 * the method you called. This model in turn contains multiple record classes,
 * each of which represents part of the data returned by the database. If
 * the database does not contain the requested information, the attributes
 * on the record class will have a `null` value.
 *
 * If the address is not in the database, an
 * {@link \GeoIp2\Exception\AddressNotFoundException} exception will be
 * thrown. If an invalid IP address is passed to one of the methods, a
 * SPL {@link \InvalidArgumentException} will be thrown. If the database is
 * corrupt or invalid, a {@link \MaxMind\Db\Reader\InvalidDatabaseException}
 * will be thrown.
 */
class Reader implements Provider_Interface
{
    private readonly Db_Reader $db_reader;
    private readonly string $db_type;
    /**
     * Constructor.
     *
     * @param string        $filename the path to the GeoIP2 database file
     * @param array<string> $locales  list of locale codes to use in name property
     *                                from most preferred to least preferred
     *
     * @throws InvalidDatabaseException if the database is corrupt or invalid
     */
    public function __construct(
        string $filename,
        /** @var array<string> */
        public readonly array $locales = ['en']
    )
    {
        $this->db_reader = new Db_Reader($filename);
        $this->db_type = $this->db_reader->metadata()->database_type;
    }
    /**
     * This method returns a GeoIP2 City model.
     *
     * @param string $ipAddress an IPv4 or IPv6 address as a string
     *
     * @throws AddressNotFoundException if the address is not in the database
     * @throws InvalidDatabaseException if the database is corrupt or invalid
     * @throws \BadMethodCallException  if this database type is not supported
     */
    public function city(string $ip_address): City
    {
        return $this->model_for(City::class, 'City', $ip_address);
    }
    /**
     * This method returns a GeoIP2 Country model.
     *
     * @param string $ipAddress an IPv4 or IPv6 address as a string
     *
     * @throws AddressNotFoundException if the address is not in the database
     * @throws InvalidDatabaseException if the database is corrupt or invalid
     * @throws \BadMethodCallException  if this database type is not supported
     */
    public function country(string $ip_address): Country
    {
        return $this->model_for(Country::class, 'Country', $ip_address);
    }
    /**
     * This method returns a GeoIP2 Anonymous IP model.
     *
     * @param string $ipAddress an IPv4 or IPv6 address as a string
     *
     * @throws AddressNotFoundException if the address is not in the database
     * @throws InvalidDatabaseException if the database is corrupt or invalid
     * @throws \BadMethodCallException  if this database type is not supported
     */
    public function anonymous_ip(string $ip_address): Anonymous_Ip
    {
        return $this->flat_model_for(Anonymous_Ip::class, 'GeoIP2-Anonymous-IP', $ip_address);
    }
    /**
     * This method returns a GeoIP Anonymous Plus model.
     *
     * @param string $ipAddress an IPv4 or IPv6 address as a string
     *
     * @throws AddressNotFoundException if the address is not in the database
     * @throws InvalidDatabaseException if the database is corrupt or invalid
     * @throws \BadMethodCallException  if this database type is not supported
     */
    public function anonymous_plus(string $ip_address): Anonymous_Plus
    {
        return $this->flat_model_for(Anonymous_Plus::class, 'GeoIP-Anonymous-Plus', $ip_address);
    }
    /**
     * This method returns a GeoLite2 ASN model.
     *
     * @param string $ipAddress an IPv4 or IPv6 address as a string
     *
     * @throws AddressNotFoundException if the address is not in the database
     * @throws InvalidDatabaseException if the database is corrupt or invalid
     * @throws \BadMethodCallException  if this database type is not supported
     */
    public function asn(string $ip_address): Asn
    {
        return $this->flat_model_for(Asn::class, 'GeoLite2-ASN', $ip_address);
    }
    /**
     * This method returns a GeoIP2 Connection Type model.
     *
     * @param string $ipAddress an IPv4 or IPv6 address as a string
     *
     * @throws AddressNotFoundException if the address is not in the database
     * @throws InvalidDatabaseException if the database is corrupt or invalid
     * @throws \BadMethodCallException  if this database type is not supported
     */
    public function connection_type(string $ip_address): Connection_Type
    {
        return $this->flat_model_for(Connection_Type::class, 'GeoIP2-Connection-Type', $ip_address);
    }
    /**
     * This method returns a GeoIP2 Domain model.
     *
     * @param string $ipAddress an IPv4 or IPv6 address as a string
     *
     * @throws AddressNotFoundException if the address is not in the database
     * @throws InvalidDatabaseException if the database is corrupt or invalid
     * @throws \BadMethodCallException  if this database type is not supported
     */
    public function domain(string $ip_address): Domain
    {
        return $this->flat_model_for(Domain::class, 'GeoIP2-Domain', $ip_address);
    }
    /**
     * This method returns a GeoIP2 Enterprise model.
     *
     * @param string $ipAddress an IPv4 or IPv6 address as a string
     *
     * @throws AddressNotFoundException if the address is not in the database
     * @throws InvalidDatabaseException if the database is corrupt or invalid
     * @throws \BadMethodCallException  if this database type is not supported
     */
    public function enterprise(string $ip_address): Enterprise
    {
        return $this->model_for(Enterprise::class, 'Enterprise', $ip_address);
    }
    /**
     * This method returns a GeoIP2 ISP model.
     *
     * @param string $ipAddress an IPv4 or IPv6 address as a string
     *
     * @throws AddressNotFoundException if the address is not in the database
     * @throws InvalidDatabaseException if the database is corrupt or invalid
     * @throws \BadMethodCallException  if this database type is not supported
     */
    public function isp(string $ip_address): Isp
    {
        return $this->flat_model_for(Isp::class, 'GeoIP2-ISP', $ip_address);
    }
    private function model_for(string $class, string $type, string $ip_address): object
    {
        [$record, $prefix_len] = $this->get_record($class, $type, $ip_address);
        $record['traits']['ip_address'] = $ip_address;
        $record['traits']['prefix_len'] = $prefix_len;
        return new $class($record, $this->locales);
    }
    private function flat_model_for(string $class, string $type, string $ip_address): object
    {
        [$record, $prefix_len] = $this->get_record($class, $type, $ip_address);
        $record['ip_address'] = $ip_address;
        $record['prefix_len'] = $prefix_len;
        return new $class($record);
    }
    /**
     * @return array{0:array<string, mixed>, 1:int}
     */
    private function get_record(string $class, string $type, string $ip_address): array
    {
        if (!str_contains($this->db_type, $type)) {
            $method = lcfirst((new \ReflectionClass($class))->get_short_name());
            throw new \BadMethodCallException("The {$method} method cannot be used to open a {$this->db_type} database");
        }
        [$record, $prefix_len] = $this->db_reader->get_with_prefix_len($ip_address);
        if ($record === null) {
            throw new Address_Not_Found_Exception("The address {$ip_address} is not in the database.");
        }
        if (!\is_array($record)) {
            // This can happen on corrupt databases. Generally,
            // MaxMind\Db\Reader will throw a
            // MaxMind\Db\Reader\InvalidDatabaseException, but occasionally
            // the lookup may result in a record that looks valid but is not
            // an array. This mostly happens when the user is ignoring all
            // exceptions and the more frequent InvalidDatabaseException
            // exceptions go unnoticed.
            throw new Invalid_Database_Exception("Expected an array when looking up {$ip_address} but received: " . \gettype($record));
        }
        return [$record, $prefix_len];
    }
    /**
     * @throws \InvalidArgumentException if arguments are passed to the method
     * @throws \BadMethodCallException   if the database has been closed
     *
     * @return Metadata object for the database
     */
    public function metadata(): Metadata
    {
        return $this->db_reader->metadata();
    }
    /**
     * Closes the GeoIP2 database and returns the resources to the system.
     */
    public function close(): void
    {
        $this->db_reader->close();
    }
}