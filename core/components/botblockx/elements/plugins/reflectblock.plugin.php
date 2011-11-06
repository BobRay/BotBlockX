<?php
/**
 * ReflectBlock Plugin
 *
 * Copyright 2011 Bob Ray <http://bobsguides.com>
 *
 * ReflectBlock is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * ReflectBlock is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * ReflectBlock; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package botblockx
 * @subpackage build
 */

/* Sends a 503 and "go away" message to request with the word 'reflect' anywhere in the URL.
 * Important: be sure nothing on your site has the word reflect in the url */

if (!function_exists("get_host")) {
    function get_host($ip)
    {
        $ptr = implode(".", array_reverse(explode(".", $ip))) . ".in-addr.arpa";
        $host = dns_get_record($ptr, DNS_PTR);
        if ($host == null) {
            return $ip;
        }
        else {
            return $host[0]['target'];
        }
    }
}

/* Don't execute in Manager */
if ($modx->context->get('key') == 'mgr') {
    return '';
}

if (stristr($_SERVER['REQUEST_URI'], 'reflect')) {

    if (!empty($GLOBALS['_SERVER'])) {
        $_SERVER_ARRAY = '_SERVER';
    } elseif (!empty($GLOBALS['HTTP_SERVER_VARS'])) {
        $_SERVER_ARRAY = 'HTTP_SERVER_VARS';
    } else {
        $_SERVER_ARRAY = 'GLOBALS';
    }
    global ${$_SERVER_ARRAY};

    $ipRemote = htmlentities(strip_tags($_SERVER['REMOTE_ADDR']));
    $reflectTpl = empty($props['reflect_message_tpl']) ? 'ReflectMessageTpl' : $props['reflect_message_tpl'];
    $useragent = isset(${$_SERVER_ARRAY}['HTTP_USER_AGENT'])
            ? htmlentities(strip_tags(${$_SERVER_ARRAY}['HTTP_USER_AGENT']))
            : '<unknown user agent>';

    @header('HTTP/1.0 503 Service Unavailable');
    @header("Status: 503 Service Temporarily Unavailable");
    header("Retry-After: 86400");
    header('Server: ');
    header('X-Powered-By: ');
    header('Connection: close');
    header('Content-Type: text/html');
    echo $modx->getChunk($reflectTpl);
    $t = gettimeofday();
    $bLogLine = "$ipRemote`" . get_host($ipRemote) . '`' . date('d/m/y H:i:') . substr($t['sec'], -2) . ':' . substr($t['usec'], 0, 3) . "`$useragent`(reflect violator)\n";
    $file = MODX_CORE_PATH . 'blocklogs/reflctblock.log';
    $fp = fopen($file, 'a');
    if ($fp) {
        fwrite($fp, $bLogLine . "\n");
        fclose($fp);
    } else {
        die('Could not open file: ' . $file);
    }
    exit();
} else {
    return '';
}