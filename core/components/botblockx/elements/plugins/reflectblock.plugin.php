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
    $oldSetting = ignore_user_abort( TRUE );   // otherwise can screw-up logfile
    $ipRemote = $_SERVER['REMOTE_ADDR'];
    $useragent = isset($_SERVER['HTTP_USER_AGENT'])
            ? $_SERVER['HTTP_USER_AGENT']
            : '<unknown user agent>';

    @header('HTTP/1.0 503 Service Unavailable');
    @header("Status: 503 Service Temporarily Unavailable");
    header("Retry-After: 86400");
    header('Server: ');
    header('X-Powered-By: ');
    header('Connection: close');
    header('Content-Type: text/html');
    echo  '<html><body><p><b>Go Away</b><br />
 The Reflect snippet does not exist on this site.</p>
 </body></html>';
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
    ignore_user_abort($oldSetting);
    exit();
} else {
    return '';
}