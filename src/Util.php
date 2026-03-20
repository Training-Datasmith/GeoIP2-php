<?php

declare (strict_types=1);
namespace Geo_Ip2;

class Util
{
    /**
     * This returns the network in CIDR notation for the given IP and prefix
     * length. This is for internal use only.
     *
     * @internal
     *
     * @ignore
     */
    public static function cidr(string $ip_address, int $prefix_len): string
    {
        $ip_bytes = inet_pton($ip_address);
        $network_bytes = str_repeat("\x00", \strlen($ip_bytes));
        $cur_prefix = $prefix_len;
        for ($i = 0; $i < \strlen($ip_bytes) && $cur_prefix > 0; $i++) {
            $b = $ip_bytes[$i];
            if ($cur_prefix < 8) {
                $shift_n = 8 - $cur_prefix;
                $b = \chr(0xff & \ord($b) >> $shift_n << $shift_n);
            }
            $network_bytes[$i] = $b;
            $cur_prefix -= 8;
        }
        $network = inet_ntop($network_bytes);
        return "{$network}/{$prefix_len}";
    }
}