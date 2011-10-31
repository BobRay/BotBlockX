<?php

/**
 * BotBlockX Plugin
 * Copyright 2011 Bob Ray <http://bobsguides.com>
 * @author Alex Kemp <modem-help.co.uk>
 * @author (Revolution) Bob Ray <http://bobsguides.com>
 * Download original: http://download.modem-help.co.uk/non-modem/PHP/Rogue-Bot-Blocking/
 * 10/25/2011
 *
 */

/* botblockx.plugin.php

    05 Aug 08 started - this slower version will block all bad bots,
    whilst letting ALL good bots through.

    Copyright 2008 (c) Alex Kemp - All Rights Reserved.
                      All Responsibility Yours.
  
    This code is released under the GNU LGPL. Go read it over here:
    http://www.gnu.org/copyleft/lesser.html

    Note: This code will not work under Windows without PHP 5.3.0+
*/

/* -------------- Start blocking badly-behaved bots  ------- */
/*  The following will be useful for bug-checking (do not use on a live server): */
/*   error_reporting( E_ALL ); */

if (!function_exists("my_debug") && $scriptProperties['debug']) {
    function my_debug($message, $clear = false)
    {
        global $modx;

        $chunk = $modx->getObject('modChunk', array('name' => 'Debug'));
        if (!$chunk) {
            $chunk = $modx->newObject('modChunk', array('name' => 'Debug'));
            $chunk->setContent('');
            $chunk->save();
            $chunk = $modx->getObject('modChunk', array('name' => 'Debug'));
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

/* Don't execute in Manager */
if ($modx->context->get('key') == 'mgr') {
    return '';
}
if (!defined('_B_ROOT')) {
    define('_B_ROOT', MODX_BASE_PATH);
}
if (!defined('_B_DIRECTORY')) {
    define('_B_DIRECTORY', _B_ROOT . 'block/');
}
if (!defined('_B_LOGFILE')) {
    define('_B_LOGFILE', 'ipblocklog');
}
if (!defined('_B_LOGMAXLINES')) {
    define('_B_LOGMAXLINES', 1000);
}
if (!defined('_L_DIRECTORY')) {
    define('_L_DIRECTORY', _B_ROOT . 'blocklogs/');
}

if (!function_exists("ipIsInNet")) {
    function ipIsInNet($ip, $net)
    {
        // note that $net is an IP-range in CIDR format
        if (preg_match('/^([^\/]+)\/([^\/]+)$/', $net, $ms)) {
            $mask = 0xFFFFFFFF << (32 - $ms[2]);
            return (ip2long($ip) & $mask) == (ip2long($ms[1]) & $mask);
        }
        return FALSE;
    }
}

if (!function_exists("get_host")) {
    function get_host($ip){
            $ptr= implode(".",array_reverse(explode(".",$ip))).".in-addr.arpa";
            $host = dns_get_record($ptr,DNS_PTR);
            if ($host == null) return $ip;
            else return $host[0]['target'];
    }
}
$props =& $scriptProperties;

$oldSetting = ignore_user_abort(TRUE); // otherwise can screw-up logfile



if (!empty($GLOBALS['_SERVER'])) {
    $_SERVER_ARRAY = '_SERVER';
} elseif (!empty($GLOBALS['HTTP_SERVER_VARS'])) {
    $_SERVER_ARRAY = 'HTTP_SERVER_VARS';
} else {
    $_SERVER_ARRAY = 'GLOBALS';
}

global ${$_SERVER_ARRAY};

$ipRemote = ${$_SERVER_ARRAY}['REMOTE_ADDR'];

/* Nuke reflect violators up front */
if (($modx->event->name == 'OnPageNotFound') && $props['reflect_lock'] && stristr($_SERVER['REQUEST_URI'], 'reflect')) {
            $reflectTpl = empty($props['reflect_message_tpl']) ? 'ReflectMessageTpl' : $props['reflect_message_tpl'];
            $useragent = (isset(${$_SERVER_ARRAY}['HTTP_USER_AGENT']))
                    ? ${$_SERVER_ARRAY}['HTTP_USER_AGENT']
                    : '<unknown user agent>';
            header('HTTP/1.0 503 Service Unavailable');
            header("Retry-After: 86400");
            header('Connection: close');
            header('Content-Type: text/html');
            echo $modx->getChunk($reflectTpl);
            $bLogLine = "$ipRemote`" . get_host($ipRemote) . '`' . date('d/m/Y H:i:s') . "`$useragent`(reflect violater)\n";
}

/* secs; check interval (best > 5 < 30 secs) (default: 7)
* Fast Scrapers will make too many accesses during the interval */
$bInterval = empty($props['interval']) ? 7 : $props['interval'];

/* Max visits allowed within $bInterval (MUST be > $bInterval) Default: 14 */
$bMaxVisit = empty($props['max_visits']) ? 14 : $props['max_visits'];

/* Seconds before visitor is allowed back; Default: 60 */
$bPenalty = empty($props['penalty']) ? 60 : $props['penalty'];

/* For slow scrapers, tot visits allowed within $bStartOver.
*  Set to `none` to disable slow-scraper block (default 1500)
*/
$bTotVisit = empty($props['total_visits']) ? 1500 : $props['total_visits'];
$bTotVisit = $bTotVisit == 'none' || $bTotVisit == 'None' ? 0 : $bTotVisit;

/* secs between tracking restarts (default 12 hours = 43200) */
$bStartOver = empty($props['start_over_secs']) ? 43200 : $props['start_over_secs'];

/* integer used to shorten the md5 hash if you don't need the full length;
* 2=255 files, 3=4,096 files 4=65,025 files, 5=1,044,480 files (should be > 1 < 6)
* (default 3) */
$ipLength = empty($props['ip_length']) ? 3 : $props['ip_length'];

/* Specify whether warnings will contain a message about appealing via
 * the contact page */
$showFastAppeal = @$props['show_fast_appeal'];
$showSlowAppeal = @$props['show_slow_appeal'];

/* Set Tpl chunk names */
$appealTpl = $modx->getOption('appeal_tpl',$props,null);
$appealTpl = empty($appealTpl)? 'AppealTpl' : $appealTpl;
$slowTpl = $modx->getOption('slow_tpl', $props, null);
$slowTpl = empty($slowTpl)? 'SlowScraperTpl' : $appealTpl;
$fastTpl = $modx->getOption('fast_tpl', $props, null);
$fastTpl = empty($fastTpl)? 'FastScraperTpl' : $appealTpl;


/* path to log file */
$ipLogFile = _L_DIRECTORY . _B_LOGFILE;

$ipFile = '';
$bLogLine = '';
$bTime = $startTime = $hitsTime = time();

if ($ipLength > 3) {
    $bDirPrefix = 'b_';
    $tmp = substr(md5($ipRemote), -$ipLength);
    $ipFile = _B_DIRECTORY . $bDirPrefix . substr($tmp, 0, 2); // 255 dirs
    if (!is_dir($ipFile)) {
        $oldMask = umask(0); // prevent umask value interfering
        if (!mkdir($ipFile, 0700)) {
            $modx->log(xPDO::LOG_LEVEL_ERROR, "BotBlockX failed to create dir: '$ipFile'");
        }
        umask($oldMask);
    }
    $ipFile .= DIRECTORY_SEPARATOR . substr($tmp, 2);
} else {
    $ipFile = _B_DIRECTORY . substr(md5($ipRemote), -$ipLength);
}

if (file_exists($ipFile)) {
    clearstatcache();
    $startTime = filemtime($ipFile); // modification time
    $hitsTime = fileatime($ipFile); // access time
    $hitsTime++;
    $visits = $hitsTime - $startTime;
    $duration = $bTime - $startTime; // secs
    if ($props['debug']) {
        my_debug('Duration: ' . $duration);
    }
    if ($duration > $bStartOver) { // restart tracking
        $startTime = $hitsTime = $bTime;
        $duration = $visits = 1;
    } else {
        if ($duration < 1) {
            $duration = 1;
        }
    }

    if (! $props['use_whitelist'] || !( // white-list check
            ipIsInNet($ipRemote, '64.62.128.0/20') or // Gigablast has blocks 64.62.128.0 - 64.62.255.255
            ipIsInNet($ipRemote, '66.154.100.0/22') or // Gigablast has blocks 66.154.100.0 - 66.154.103.255
            ipIsInNet($ipRemote, '64.233.160.0/19') or // Google has blocks 64.233.160.0 - 64.233.191.255
            ipIsInNet($ipRemote, '66.249.64.0/19') or // Google has blocks 66.249.64.0 - 66.249.95.255
            ipIsInNet($ipRemote, '72.14.192.0/19') or // Google has blocks 72.14.192.0 - 72.14.239.255
            ipIsInNet($ipRemote, '72.14.224.0/20') or
            ipIsInNet($ipRemote, '216.239.32.0/19') or // Google has blocks 216.239.32.0 - 216.239.63.255
            ipIsInNet($ipRemote, '66.196.64.0/18') or // Inktomi has blocks 66.196.64.0 - 66.196.127.255
            ipIsInNet($ipRemote, '74.6.0.0/16') or // Inktomi has blocks 74.6.0.0 - 74.6.255.255
            ipIsInNet($ipRemote, '66.228.160.0/19') or // Overture has blocks 66.228.160.0 - 66.228.191.255
            ipIsInNet($ipRemote, '68.142.192.0/18') or // Inktomi has blocks 68.142.192.0 - 68.142.255.255
            ipIsInNet($ipRemote, '72.30.0.0/16') or // Inktomi has blocks 72.30.0.0 - 72.30.255.255
            ipIsInNet($ipRemote, '64.4.0.0/18') or // MS-Hotmail has blocks 64.4.0.0 - 64.4.63.255
            ipIsInNet($ipRemote, '65.52.0.0/14') or // MS has blocks 65.52.0.0 - 65.55.255.255
            ipIsInNet($ipRemote, '207.46.0.0/16') or // MS has blocks 207.46.0.0 - 207.46.255.255
            ipIsInNet($ipRemote, '207.68.128.0/18') or // MS has blocks 207.68.128.0 - 207.68.207.255
            ipIsInNet($ipRemote, '207.68.192.0/20') or
            ipIsInNet($ipRemote, '65.192.0.0/11') or // Teoma has blocks 65.192.0.0 - 65.223.255.255
            (substr($ipRemote, 0, 13) == '66.194.55.242') // Ocelli
    )
    ) {
        // $ipRemote is now NOT any of the above

            /* ********* test for fast scrapers *********** */
        if (
            ($visits >= $bMaxVisit) and
            (($visits / $duration) > ($bMaxVisit / $bInterval))
        ) {
            $startTime = $bTime;
            $hitsTime = $bTime + (($bMaxVisit * $bPenalty) / $bInterval);
            $wait = ( int )$hitsTime - $startTime + 1;
            $useragent = (isset(${$_SERVER_ARRAY}['HTTP_USER_AGENT']))
                    ? ${$_SERVER_ARRAY}['HTTP_USER_AGENT']
                    : '<unknown user agent>';
            header('HTTP/1.0 503 Service Unavailable');
            header("Retry-After: $wait");
            header('Connection: close');
            header('Content-Type: text/html');
            $fields = array(
                'bbx.wait' => $wait,
            );
            $fields['bbx.appeal'] = $showFastAppeal? $modx->getChunk($appealTpl) : '';
            echo $modx->getChunk('FastScraperTpl', $fields);

            $bLogLine = "$ipRemote`" . get_host($ipRemote) . '`' . date('d/m/Y H:i:s') . "`$useragent`(fast scraper)\n";
        }
    } else {
        if ($props['debug']) {
            my_debug('Visit from good bot: ' . ${$_SERVER_ARRAY}['HTTP_USER_AGENT']);
        }
    } /* end of bot-testing */

    if ($props['debug']) {
        my_debug('visits / duration: ' . $visits / $duration);
        my_debug('bMaxVisit / bInterval' . $bMaxVisit / $bInterval);
        my_debug('visits: ' . $visits . "\n");
    }
    // log badly-behaved bots, then nuke 'em
    if ($bLogLine) {
        touch($ipFile, $startTime, $hitsTime);
        $log = file($ipLogFile); // flock() disabled in some kernels (eg 2.4)
        if ($fp = fopen($ipLogFile, 'a')) { // tiny danger of 2 threads interfering; live with it
            if (count($log) >= _B_LOGMAXLINES) { // otherwise grows like Topsy
                fclose($fp); // fopen,fclose put as close together as possible
                while (count($log) >= _B_LOGMAXLINES)
                {
                    array_shift($log);
                }
                array_push($log, $bLogLine);
                $bLogLine = implode('', $log);
                $fp = fopen($ipLogFile, 'w');
            }
            fputs($fp, $bLogLine);
            fclose($fp);
        }
        exit();
    }
} // end of if( file_exists( $ipFile ))

/* set mtime and atime of the ip file for this user */
touch($ipFile, $startTime, $hitsTime);
ignore_user_abort($oldSetting);
// -------------- Stop blocking badly-behaved bots : top code --------