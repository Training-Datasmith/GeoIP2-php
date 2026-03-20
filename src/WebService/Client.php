<?php

declare (strict_types=1);
namespace Geo_Ip2\Web_Service;

use Geo_Ip2\Exception\Address_Not_Found_Exception;
use Geo_Ip2\Exception\Authentication_Exception;
use Geo_Ip2\Exception\Geo_Ip2exception;
use Geo_Ip2\Exception\Http_Exception;
use Geo_Ip2\Exception\Invalid_Request_Exception;
use Geo_Ip2\Exception\Out_Of_Queries_Exception;
use Geo_Ip2\Model\City;
use Geo_Ip2\Model\Country;
use Geo_Ip2\Model\Insights;
use Geo_Ip2\Provider_Interface;
use Max_Mind\Exception\Insufficient_Funds_Exception;
use Max_Mind\Exception\Ip_Address_Not_Found_Exception;
use Max_Mind\Exception\Web_Service_Exception;
use Max_Mind\Web_Service\Client as WsClient;
/**
 * This class provides a client API for all the GeoIP2 web services.
 * The services are Country, City Plus, and Insights. Each service returns
 * a different set of data about an IP address, with Country returning the
 * least data and Insights the most.
 *
 * Each web service is represented by a different model class, and these model
 * classes in turn contain multiple record classes. The record classes have
 * attributes which contain data about the IP address.
 *
 * If the web service does not return a particular piece of data for an IP
 * address, the associated attribute is not populated.
 *
 * The web service may not return any information for an entire record, in
 * which case all of the attributes for that record class will be empty.
 *
 * ## Usage ##
 *
 * The basic API for this class is the same for all of the web service end
 * points. First you create a web service object with your MaxMind `$accountId`
 * and `$licenseKey`, then you call the method corresponding to a specific end
 * point, passing it the IP address you want to look up.
 *
 * If the request succeeds, the method call will return a model class for
 * the service you called. This model in turn contains multiple record
 * classes, each of which represents part of the data returned by the web
 * service.
 *
 * If the request fails, the client class throws an exception.
 */
class Client implements Provider_Interface
{
    private readonly Ws_Client $client;
    private static string $base_path = '/geoip/v2.1';
    public const VERSION = 'v3.3.0';
    /**
     * Constructor.
     *
     * @param int                  $accountId  your MaxMind account ID
     * @param string               $licenseKey your MaxMind license key
     * @param list<string>         $locales    list of locale codes to use in name property
     *                                         from most preferred to least preferred
     * @param array<string, mixed> $options    array of options. Valid options include:
     *                                         * `host` - The host to use when querying the web
     *                                         service. To query the GeoLite2 web service
     *                                         instead of the GeoIP2 web service, set the
     *                                         host to `geolite.info`. To query the Sandbox
     *                                         GeoIP2 web service instead of the production
     *                                         GeoIP2 web service, set the host to
     *                                         `sandbox.maxmind.com`. The sandbox allows you to
     *                                         experiment with the API without affecting your
     *                                         production data.
     *                                         * `timeout` - Timeout in seconds.
     *                                         * `connectTimeout` - Initial connection timeout in seconds.
     *                                         * `proxy` - The HTTP proxy to use. May include a schema, port,
     *                                         username, and password, e.g.,
     *                                         `http://username:password@127.0.0.1:10`.
     */
    public function __construct(
        int $account_id,
        string $license_key,
        /** @var list<string> */
        public readonly array $locales = ['en'],
        // Promoted and readonly
        array $options = []
    )
    {
        // This is for backwards compatibility. Do not remove except for a
        // major version bump.
        // @phpstan-ignore-next-line
        if (\is_string($options)) {
            $options = ['host' => $options];
        }
        $options['host'] ??= 'geoip.maxmind.com';
        $options['userAgent'] = $this->user_agent();
        $this->client = new Ws_Client($account_id, $license_key, $options);
    }
    private function user_agent(): string
    {
        return 'GeoIP2-API/' . self::VERSION;
    }
    /**
     * This method calls the City Plus service.
     *
     * @param string $ipAddress IPv4 or IPv6 address as a string. If no
     *                          address is provided, the address that the web service is called
     *                          from will be used.
     *
     * @throws AddressNotFoundException  if the address you provided is not in our database (e.g.,
     *                                   a private address).
     * @throws AuthenticationException   if there is a problem with the account ID or license key
     *                                   that you provided
     * @throws OutOfQueriesException     if your account is out of queries
     * @throws InvalidRequestException   if your request was received by the web service but is
     *                                   invalid for some other reason.  This may indicate an issue
     *                                   with this API. Please report the error to MaxMind.
     * @throws HttpException             if an unexpected HTTP error code or message was returned.
     *                                   This could indicate a problem with the connection between
     *                                   your server and the web service or that the web service
     *                                   returned an invalid document or 500 error code
     * @throws GeoIp2Exception           This serves as the parent
     *                                   class to the above exceptions. It will be thrown directly
     *                                   if a 200 status code is returned but the body is invalid.
     * @throws \InvalidArgumentException if something other than a single IP address or "me" is
     *                                   passed to the method
     */
    public function city(string $ip_address = 'me'): City
    {
        return $this->response_for('city', City::class, $ip_address);
    }
    /**
     * This method calls the Country service.
     *
     * @param string $ipAddress IPv4 or IPv6 address as a string. If no
     *                          address is provided, the address that the web service is called
     *                          from will be used.
     *
     * @throws AddressNotFoundException  if the address you provided is not in our database (e.g.,
     *                                   a private address).
     * @throws AuthenticationException   if there is a problem with the account ID or license key that you provided
     * @throws OutOfQueriesException     if your account is out of queries
     * @throws InvalidRequestException   if your request was received by the web service but is
     *                                   invalid for some other reason.  This may indicate an
     *                                   issue with this API. Please report the error to MaxMind.
     * @throws HttpException             if an unexpected HTTP error
     *                                   code or message was returned. This could indicate a problem
     *                                   with the connection between your server and the web service
     *                                   or that the web service returned an invalid document or 500
     *                                   error code.
     * @throws GeoIp2Exception           This serves as the parent class to the above exceptions. It
     *                                   will be thrown directly if a 200 status code is returned but
     *                                   the body is invalid.
     * @throws \InvalidArgumentException if something other than a single IP address or "me" is
     *                                   passed to the method
     */
    public function country(string $ip_address = 'me'): Country
    {
        return $this->response_for('country', Country::class, $ip_address);
    }
    /**
     * This method calls the Insights service. Insights is only supported by
     * the GeoIP2 web service. The GeoLite2 web service does not support it.
     *
     * @param string $ipAddress IPv4 or IPv6 address as a string. If no
     *                          address is provided, the address that the web service is called
     *                          from will be used.
     *
     * @throws AddressNotFoundException  if the address you provided is not in our database (e.g.,
     *                                   a private address).
     * @throws AuthenticationException   if there is a problem with the account ID or license key
     *                                   that you provided
     * @throws OutOfQueriesException     if your account is out of queries
     * @throws InvalidRequestException   if your request was received by the web service but is
     *                                   invalid for some other reason.  This may indicate an
     *                                   issue with this API. Please report the error to MaxMind.
     * @throws HttpException             if an unexpected HTTP error code or message was returned.
     *                                   This could indicate a problem with the connection between
     *                                   your server and the web service or that the web service
     *                                   returned an invalid document or 500 error code
     * @throws GeoIp2Exception           This serves as the parent
     *                                   class to the above exceptions. It will be thrown directly
     *                                   if a 200 status code is returned but the body is invalid.
     * @throws \InvalidArgumentException if something other than a single IP address or "me" is
     *                                   passed to the method
     */
    public function insights(string $ip_address = 'me'): Insights
    {
        return $this->response_for('insights', Insights::class, $ip_address);
    }
    /**
     * Generic helper method to call an endpoint and return the corresponding model.
     *
     * @template TModel of City|Country|Insights
     *
     * @param 'city'|'country'|'insights' $endpoint  the endpoint name
     * @param class-string<TModel>        $class     The specific model class string (e.g., City::class)
     * @param string                      $ipAddress the IP address or 'me'
     *
     * @throws AddressNotFoundException
     * @throws AuthenticationException
     * @throws OutOfQueriesException
     * @throws InvalidRequestException
     * @throws HttpException
     * @throws GeoIp2Exception
     * @throws \InvalidArgumentException
     *
     * @return TModel the corresponding model object, matching the passed class string
     */
    private function response_for(string $endpoint, string $class, string $ip_address): City|Country|Insights
    {
        if ($ip_address !== 'me' && !filter_var($ip_address, \FILTER_VALIDATE_IP)) {
            throw new \InvalidArgumentException("The value \"{$ip_address}\" is not a valid IP address.");
        }
        $path = implode('/', [self::$base_path, $endpoint, $ip_address]);
        try {
            $service = (new \ReflectionClass($class))->get_short_name();
            $body = $this->client->get('GeoIP2 ' . $service, $path);
        } catch (Ip_Address_Not_Found_Exception $ex) {
            throw new Address_Not_Found_Exception($ex->get_message(), $ex->get_status_code(), $ex);
        } catch (\Max_Mind\Exception\Authentication_Exception $ex) {
            throw new Authentication_Exception($ex->get_message(), $ex->get_status_code(), $ex);
        } catch (Insufficient_Funds_Exception $ex) {
            throw new Out_Of_Queries_Exception($ex->get_message(), $ex->get_status_code(), $ex);
        } catch (\Max_Mind\Exception\Invalid_Request_Exception $ex) {
            throw new Invalid_Request_Exception($ex->get_message(), $ex->get_error_code(), $ex->get_status_code(), $ex->get_uri(), $ex);
        } catch (\Max_Mind\Exception\Http_Exception $ex) {
            throw new Http_Exception($ex->get_message(), $ex->get_status_code(), $ex->get_uri(), $ex);
        } catch (Web_Service_Exception $ex) {
            throw new Geo_Ip2exception($ex->get_message(), $ex->get_code(), $ex);
        }
        return new $class($body, $this->locales);
    }
}