<?php

if (!function_exists("get_host")) {
    function get_host($ip){
            $ptr = implode(".",array_reverse(explode(".",$ip))).".in-addr.arpa";
            $host = dns_get_record($ptr,DNS_PTR);
            if ($host == null) return $ip;
            else return $host[0]['target'];
    }
}

if (!function_exists("my_debug")) {
    function my_debug($message, $clear = false) {
        global $modx;

        $chunk = $modx->getObject('modChunk', array('name' => 'NotFoundLog'));
        if (!$chunk) {
            $chunk = $modx->newObject('modChunk', array('name' => 'NotFoundLog'));
            $chunk->save();
            $chunk = $modx->getObject('modChunk', array('name' => 'NotFoundLog'));
        } else {
            if ($clear) {
                $content = '';
            } else {
                $content = $chunk->getContent();
            }
        }
        $content .= $message . "\n";
        $chunk->setContent($content);
        $chunk->save();
    }
}

$data['page'] = htmlentities(strip_tags($_SERVER['REQUEST_URI']));
$data['ip'] = htmlentities(strip_tags($_SERVER['REMOTE_ADDR']));
$data['userAgent'] = htmlentities(strip_tags($_SERVER['HTTP_USER_AGENT']));
$data['host'] = get_host($data['ip']);
$msg = implode(',', $data);
my_debug($msg);

    return '';
