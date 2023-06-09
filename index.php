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
    $faridIps = getIps('https://raw.githubusercontent.com/vfarid/cf-ip-scanner-py/483b7dc7cbc1446e7e551c61932e20388830b141/cf-ipv4.txt');
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
    header("Content-Type: text/plain");
    foreach( $generateList as $ip ) {
        $export .= $ip."\n";
    }
    echo $export;

    $writeFile = fopen("export.ipv4", "w") or die("Unable to open file!");
    fwrite($writeFile, trim($export));
    fclose($writeFile);

?>
