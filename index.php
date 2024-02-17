<?php

    // CloudFlare IP Ranges
    // ircf.space

    function getIps($raw) {
        $ips = [];
        $fetch = @file_get_contents($raw);
        if ( isset($fetch) && !empty($fetch) ) {
            $ips = preg_split("/[\f\r\n]+/", $fetch );
        }
        return $ips;
    }

    $bashsizIps = getIps('https://raw.githubusercontent.com/MortezaBashsiz/CFScanner/main/config/cf.local.iplist');
    $safariIps = getIps('https://raw.githubusercontent.com/SafaSafari/ss-cloud-scanner/main/ips.txt');
    $faridIps = getIps('https://raw.githubusercontent.com/vfarid/cf-ip-scanner/main/ipv4.txt');
    $ircfIps = getIps('https://raw.githubusercontent.com/ircfspace/scanner/main/ipv4.list');

    $newList = array_merge($bashsizIps, $safariIps, $faridIps, $ircfIps);
    $newList = array_filter($newList, 'strlen');
    $newList = array_unique($newList);
    natsort($newList);

    $generateList = [];
    foreach( $newList as $ip ) {
        if ( empty($ip) ) {
            continue;
        }
        $explode = explode("/", $ip);
        if ( ! isset($explode[0]) || empty($explode[0]) ) {
            continue;
        }
        $generateList[$explode[0]] = $explode[0].'/24';
    }

    $export = '';
    foreach( $generateList as $ip ) {
        $export .= $ip."\n";
    }

    file_put_contents("export.ipv4", $export);

?>
