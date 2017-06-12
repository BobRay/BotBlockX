<?php

/* For reference. This is Alex's original code */

/* ------------------------------------------------------------------------
 * this copy of Alex Kemp's, PHP coded, bot-block & anti-scrapers routine
 * was published at http://www.searchlores.org in April 2006
 * and is part of the bots.htm section.
 * ------------------------------------------------------------------------
 * Items prepended with an underscore are Constants that need to be define()'d
 * somewhere in your scripts before this snippet of code gets used:
 * eg (*nix):
	define( '_B_DIRECTORY', '/full/path/on/server/to/block/dir/' );
	define( '_B_LOGFILE', 'logfile.name' );
	define( '_B_LOGMAXLINES', '1000' );
 * These Constants can be variables or even constant-values within the code - your choice.
 *
 * The following will be useful for bug-checking (do not use on a live server):
	error_reporting( E_ALL );
 * (thanks Umbra)
 * ...and remember to remove these comments before use!
 *
 * Following code snippet needs to be processed as early as possible:
 * A possible means of auto-prepending the code is in comments at bottom.
 */
// -------------- Start blocking badly-behaved bots : top code -------
	$oldSetting	= ignore_user_abort( TRUE );	// otherwise can screw-up logfile
	if( !empty( $GLOBALS[ '_SERVER' ])) {
		$_SERVER_ARRAY	= '_SERVER';
	} elseif( !empty( $GLOBALS[ 'HTTP_SERVER_VARS' ])) {
		$_SERVER_ARRAY	= 'HTTP_SERVER_VARS';
	} else {
		$_SERVER_ARRAY	= 'GLOBALS';
	}
	global ${$_SERVER_ARRAY};
	$ipRemote	= ${$_SERVER_ARRAY}[ 'REMOTE_ADDR' ];
	$bInterval	= 7;		// secs; check interval (best > 5 < 30 secs)
	$bMaxVisit	= 14;		// Max visits allowed within $bInterval (MUST be > $bInterval)
	/* ------------------------------------------------------------------------
	 * The default trip-rate is 2 / sec ($bMaxVisit / $bInterval = 14 / 7) which means,
	 * at the absolute worst-case scenario, a fast scraper will get 999 pages in 500 secs.
	 * That is a lot, but a reasonable compromise without using some form of whitelist-exclusions
	 * (my attitude is a-scraper-is-a-scraper whether they are wearing an admittance-badge or not;
	 * it is their behaviour which determines their status).
	 * ------------------------------------------------------------------------
	 */
	$bPenalty	= 60;		// Seconds before visitor is allowed back
	$bTotVisit	= 1000;	// tot visits within $bStartOver (0==no slow-scraper block)
	$bStartOver	= 86400;	// secs, default 1 day; restart tracking
	$ipLength	= 3;		// integer; 2=255 files, 3=4,096 files (best > 1 < 6)
	$ipLogFile	= _B_DIRECTORY . _B_LOGFILE;
	if( $ipLength > 3 ) {
		// this addition is untested by the author; 4=65,025 files, 5=1,044,480 files
		$bDirPrefix	= 'b_';
		$tmp			= substr( md5( $ipRemote ), -$ipLength );
		$ipFile		= _B_DIRECTORY . $bDirPrefix . substr( $tmp, 0, 2 );	// 255 dirs
		if( !is_dir( $ipFile )) {
			$oldMask	= umask( 0777 );
			if( !mkdir( $ipFile, 0700 )) die( "Failed to create dir: '$ipFile'" );
			umask( $oldMask );
		}
		$ipFile		.= '/'. substr( $tmp, 2 );	// change to back-slash for Windows
	} else {
		// this is original (tested) coding
		$ipFile		= _B_DIRECTORY . substr( md5( $ipRemote ), -$ipLength );
	}
	$bLogLine	= '';
	$time			= $startTime = $hitsTime = time();
	if( file_exists( $ipFile )) {
		$startTime	= filemtime( $ipFile );	// modification time
		$hitsTime	= fileatime( $ipFile );	// access time
		$hitsTime++;
		$visits		= $hitsTime - $startTime;
		$duration	= $time - $startTime;	// secs
		if( $duration > $bStartOver ) {		// restart tracking
			$startTime	= $hitsTime = $time;
			$duration	= $visits = 1;
		} else if( $duration < 1 ) $duration = 1;
		// test for slow scrapers
		if(
			( $bTotVisit > 0 ) and
			( $visits >= $bTotVisit )
		) {
			$useragent	= ( isset( ${$_SERVER_ARRAY}[ 'HTTP_USER_AGENT' ]))
				? ${$_SERVER_ARRAY}[ 'HTTP_USER_AGENT' ]
				: '<unknown user agent>';
			$wait			= ( int ) $bStartOver - $duration + 1;	// secs
			header( 'HTTP/1.0 503 Service Unavailable' );
			header( 'Content-Type: text/html' );
			header( "Retry-After: $wait" );
			header( 'Content-Type: text/html' );
			echo "<html><body><p><b>Server under undue load</b><br />";
			echo "$visits visits from your IP-Address within the last ". (( int ) $duration / 3600 ) ." hours. Please wait ". (( int ) $wait / 3600 ) ." hours before retrying.</p></body></html>";
			$bLogLine	= "$ipRemote ". date( 'd/m/Y H:i:s' ) ." $useragent (slow scraper)\n";
		// test for fast scrapers
		} elseif(
			( $visits >= $bMaxVisit ) and
			(( $visits / $duration ) > ( $bMaxVisit / $bInterval ))
		) {
			$startTime	= $time;
			$hitsTime	= $time + (( $bMaxVisit * $bPenalty ) / $bInterval );
			$wait			= ( int ) $hitsTime - $startTime + 1;
			$useragent	= ( isset( ${$_SERVER_ARRAY}[ 'HTTP_USER_AGENT' ]))
				? ${$_SERVER_ARRAY}[ 'HTTP_USER_AGENT' ]
				: '<unknown user agent>';
			header( 'HTTP/1.0 503 Service Unavailable' );
			header( "Retry-After: $wait" );
			header( 'Connection: close' );
			header( 'Content-Type: text/html' );
			echo "<html><body><p><b>Server under heavy load</b><br />";
			echo "You are scraping this site too quickly. Please wait at least $wait secs before retrying.</p></body></html>";
			$bLogLine	= "$ipRemote ". date( 'd/m/Y H:i:s' ) ." $useragent (fast scraper)\n";
		}
		// log badly-behaved bots, then nuke 'em
		if( $bLogLine ) {
			touch( $ipFile, $startTime, $hitsTime );
			$log			= file( $ipLogFile );				// flock() disabled in some kernels (eg 2.4)
			if( $fp = fopen( $ipLogFile, 'a' )) {			// tiny danger of 2 threads interfering; live with it
				if( count( $log ) >= _B_LOGMAXLINES ) {	// otherwise grows like Topsy
					fclose( $fp );									// fopen,fclose put close together as possible
					while( count( $log ) >= _B_LOGMAXLINES ) array_shift( $log );
					array_push( $log, $bLogLine );
					$bLogLine	= implode( '', $log );
					$fp		= fopen( $ipLogFile, 'w' );
				}
				fputs( $fp, $bLogLine );
				fclose( $fp );
			}
			exit();
		}
	}
	touch( $ipFile, $startTime, $hitsTime );
	ignore_user_abort( $oldSetting );
// -------------- Stop blocking badly-behaved bots : top code --------
/* Optional (untested):
 * Following is for those fanatical to stop fast-scrapers; it allows
 * later threads to notify earlier threads that a fast-scrape is in
 * progress, and thus stop normal output. It is especially useful for
 * long-duration scripts (perhaps slow sql calls, whatever).
 *
 * Notes:
 * 1 Output caching (normally via ob_start()) will need to be in effect
 *   (any previous output will throw errors on the header() calls otherwise).
 * 2 The original $b* variables (and $time ) in the top-code script need
 *   to be preserved unchanged.
 *
 * Following code snippet needs to be processed as late as possible:
// -------------- Start blocking badly-behaved bots : bot code -------
	$oldSetting	= ignore_user_abort( TRUE );
	clearstatcache();
	$hitsTime	= fileatime( $ipFile );
	$startTime	= filemtime( $ipFile );
	$visits		= $hitsTime - $startTime;
	$duration	= $time - $startTime;	// secs
	if( $duration < 1 ) $duration = 1;
	// test for fast scrapers
	if(
		( $visits >= $bMaxVisit ) and
		(( $visits / $duration ) > ( $bMaxVisit / $bInterval ))
	) {
		ob_end_clean();	// discards all cached output
		$startTime	= $time;
		$hitsTime	= $time + (( $bMaxVisit * $bPenalty ) / $bInterval );
		$wait			= ( int ) $hitsTime - $startTime + 1;
		$useragent	= ( isset( ${$_SERVER_ARRAY}[ 'HTTP_USER_AGENT' ]))
			? ${$_SERVER_ARRAY}[ 'HTTP_USER_AGENT' ]
			: '<unknown user agent>';
		header( 'HTTP/1.0 503 Service Unavailable' );
			header( "Retry-After: $wait" );
		header( 'Connection: close' );
		header( 'Content-Type: text/html' );
		echo "<html><body><p><b>Server under heavy load</b><br />";
		echo "You are scraping this site too quickly. Please wait at least $wait secs before retrying.</p></body></html>";
		$bLogLine	= "$ipRemote ". date( 'd/m/Y H:i:s' ) ." $useragent (bot:fast scraper)\n";
	}
	// log badly-behaved bots, then nuke 'em
	if( $bLogLine ) {
		touch( $ipFile, $startTime, $hitsTime );
		$log			= file( $ipLogFile );				// flock() disabled in some kernels (eg 2.4)
		if( $fp = fopen( $ipLogFile, 'a' )) {			// tiny danger of 2 threads interfering; live with it
			if( count( $log ) >= _B_LOGMAXLINES ) {	// otherwise grows like Topsy
				fclose( $fp );									// fopen,fclose put close together as possible
				while( count( $log ) >= _B_LOGMAXLINES ) array_shift( $log );
				array_push( $log, $bLogLine );
				$bLogLine	= implode( '', $log );
				$fp		= fopen( $ipLogFile, 'w' );
			}
			fputs( $fp, $bLogLine );
			fclose( $fp );
		}
		exit();
	}
	ignore_user_abort( $oldSetting );
// -------------- Stop blocking badly-behaved bots : bot code --------
 * Comments:
 *
 * Linux file-systems can be mounted with atime disabled; this script will
 * fail on those systems. To check:

ls -lu # show access time (atime)
ls -lt # show modification time (mtime)
cat some-file
ls -lu # and re-check some-file's time; it should have changed

 * One means of calling the script early is by placing it into it's own file
 * (include the <?php ... ?> start-end tags) and adding the following to .htaccess:

<IfModule mod_php4.c>
   php_value auto_prepend_file "/server/path/to/file/block_bad_bots_top.php"
</IfModule>

 * (change to mod_php5 - whatever - if using PHP5)
 *
 * Note on prepended files: if using Sessions, or Headers, or Cookies, be careful
 * not to make any output within the file of any kind (will throw an error). Be
 * especially careful of a space (whatever) hiding before/after the <?php ... ?>
 * start-end tags (thanks saltlakejohn).
 *
 * If using the optional bottom-code, a similar route can be taken:

<IfModule mod_php4.c>
   php_value auto_append_file "/server/path/to/file/block_bad_bots_bot.php"
</IfModule>

 * Note that "block_bad_bots_*.php" does NOT have to be web-accessible in this instance,
 * although it does have to be readable by the Apache client.
 *
 * The long-term scraper block can be switched off by setting $bTotVisit = 0;
 * that will NOT affect the fast-scraper blocking, or 24-hour reset.
 *
 * As written, the routine will catch *any* bot that trips either the fast- or slow-triggers.
 * This may worry some people! Here are 3 solutions:
 *
 * 1 Use the (non-standard) Crawl-delay directive in robots.txt:

User-agent: msnbot
Crawl-delay: 90
Disallow: /cgi-bin

 * (90 seconds is a bit less than 1000 requests per day)
 * Yahoo, MSN, and Ask Jeeves/Teoma all make use of this directive. Use '*' (no quotes)
 * to specify *any* bot that recognises it.
 *
 * 2 Use an IP-based white-list:

	function ipIsInNet( $ip, $net ) {
		if( preg_match( '/^([^\/]+)\/([^\/]+)$/', $net, $ms )) {
			$mask = 0xFFFFFFFF << ( 32 - $ms[2] );
			return ( ip2long( $ip ) & $mask ) == ( ip2long( $ms[1] ) & $mask );
		}
		return false;
	}
	$oldSetting	= ignore_user_abort( TRUE );
	if( !empty( $GLOBALS[ '_SERVER' ])) {
		$_SERVER_ARRAY	= '_SERVER';
	} elseif( !empty( $GLOBALS[ 'HTTP_SERVER_VARS' ])) {
		$_SERVER_ARRAY	= 'HTTP_SERVER_VARS';
	} else {
		$_SERVER_ARRAY	= 'GLOBALS';
	}
	global ${$_SERVER_ARRAY};
	$ipRemote		= ${$_SERVER_ARRAY}[ 'REMOTE_ADDR' ];
	if(
		ipIsInNet( $ipRemote, '64.62.128.0/20' ) or			// Gigablast has blocks 64.62.128.0 - 64.62.255.255
		ipIsInNet( $ipRemote, '66.154.100.0/22' ) or			// Gigablast has blocks 66.154.100.0 - 66.154.103.255
		ipIsInNet( $ipRemote, '64.233.160.0/19' ) or			// Google has blocks 64.233.160.0 - 64.233.191.255
		ipIsInNet( $ipRemote, '66.249.64.0/19' ) or			// Google has blocks 66.249.64.0 - 66.249.95.255
		ipIsInNet( $ipRemote, '72.14.192.0/19' ) or			// Google has blocks 72.14.192.0 - 72.14.239.255
		ipIsInNet( $ipRemote, '72.14.224.0/20' ) or
		ipIsInNet( $ipRemote, '216.239.32.0/19' ) or			// Google has blocks 216.239.32.0 - 216.239.63.255
		ipIsInNet( $ipRemote, '66.196.64.0/18' ) or			// Inktomi has blocks 66.196.64.0 - 66.196.127.255
		ipIsInNet( $ipRemote, '66.228.160.0/19' ) or			// Overture has blocks 66.228.160.0 - 66.228.191.255
		ipIsInNet( $ipRemote, '68.142.192.0/18' ) or			// Inktomi has blocks 68.142.192.0 - 68.142.255.255
		ipIsInNet( $ipRemote, '72.30.0.0/16' ) or				// Inktomi has blocks 72.30.0.0 - 72.30.255.255
		ipIsInNet( $ipRemote, '64.4.0.0/18' ) or				// MS-Hotmail has blocks 64.4.0.0 - 64.4.63.255
		ipIsInNet( $ipRemote, '65.52.0.0/14' ) or				// MS has blocks 65.52.0.0 - 65.55.255.255
		ipIsInNet( $ipRemote, '207.46.0.0/16' ) or			// MS has blocks 207.46.0.0 - 207.46.255.255
		ipIsInNet( $ipRemote, '207.68.128.0/18' ) or			// MS has blocks 207.68.128.0 - 207.68.207.255
		ipIsInNet( $ipRemote, '207.68.192.0/20' ) or
		ipIsInNet( $ipRemote, '65.192.0.0/11' ) or			// Teoma has blocks 65.192.0.0 - 65.223.255.255
		( substr( $ipRemote, 0, 13 ) == '66.194.55.242' )	// Ocelli
	) {
		// let well-behaved bots through
	} else {
		// block routine
	}

 * 3 Use a User-Agent-based white-list (warning!: User-Agents are easily faked):

	$oldSetting	= ignore_user_abort( TRUE );
	if( !empty( $GLOBALS[ '_SERVER' ])) {
		$_SERVER_ARRAY	= '_SERVER';
	} elseif( !empty( $GLOBALS[ 'HTTP_SERVER_VARS' ])) {
		$_SERVER_ARRAY	= 'HTTP_SERVER_VARS';
	} else {
		$_SERVER_ARRAY	= 'GLOBALS';
	}
	global ${$_SERVER_ARRAY};
	$ipRemote		= ${$_SERVER_ARRAY}[ 'REMOTE_ADDR' ];
	$ref			= ${$_SERVER_ARRAY}[ 'HTTP_USER_AGENT' ];
	$bot			= 'N';
	// Check if it is a 'good' bot
	$agents		= array( 'Googlebot', 'Yahoo', 'msnbot', 'Jeeves', 'Mediapartners' );
	foreach( $agents as $agent ) {
		if( strpos( $ref, $agent ) !== FALSE ) {
			$bot = 'Y';
		}
	}
	if( $bot == 'Y' ) {
		// let well-behaved bots through
	} else {
		// block routine
	}

 * I take the view that bots (just like people) should be judged by their behaviour,
 * rather than their parents. As one prime example, the Google Mozilla-bot has reliably
 * been reported to crash database-based sites with 20 page-requests/second across an
 * extended period. However, you may wish to let some bots hammer your site at will,
 * and routines 2 or 3 above will allow that.
 *
 * Directory permissions: `_B_DIRECTORY` needs to pre-exist *and* be read-writeable by
 * the apache-group. It is best if it is not web-accessible.
 *
 * Both $ipLogFile and $ipFile are created on-the-fly if not already existing.
 *
 * `_B_DIRECTORY` will fill with many thousands of zero-byte files. That is normal
 * behaviour. The files ($ipFile) are used to track users by IP-address. As zero-byte
 * files they do not represent any disk-space risk and may be ignored. They do still
 * represent a resource-consumption risk, but this has not affected my server at all.
 *
 * $ipLogFile will only appear when a bot is blocked, and will roll-over when
 * _B_LOGMAXLINES is reached. Routine to read it left as an excercise for the reader!!!
 * (sample code at http://www.modem-help.co.uk/help/diary20040526.html)
 *
 * Fast-Scraper reset logic:
 * The fast-scraper test is: (( $visits / $duration ) > ( $bMaxVisit / $bInterval )).
 * Therefore, blocking needs to stop when (( $visits / $duration ) == ( $bMaxVisit / $bInterval )).
 * Since $duration wants to be == $bPenalty, that equation can be solved for $visits.
 * As the test does not begin until ( $visits >= $bMaxVisit ), it is imperative that
 * ( $bMaxVisit / $bInterval ) >= 1.
 *
 * Changelog:
 * 2006-03-13 array_shift modified to correct over-sized logs.
 * 2006-03-05 added extra IPs to Whitelist routine (thanks incrediBILL).
 * 2006-02-16 added Retry-After (see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.37).
 * 2006-01-24 fast-scraper block reset fixed ($bPenalty now accurate); $fileMTime, $fileATime renamed.
 * 2006-01-03 code addition for $ipLength>3 for v busy sites (thanks inbound)
 * 2005-12-19 added bottom code (thanks incrediBILL) + re-wrote IP-based white-list (thanks Hanu)
 * 2005-12-17 added comments at bottom + made start-over duration a variable.
 * 2005-11-20 set TAB=3 spaces; see also http://www.webmasterworld.com/forum88/10425.htm
 * 2003-01-11 original code via xlcus; see http://www.webmasterworld.com/forum88/119.htm
 *
 * Reminder for future additions (thanks incrediBILL):
 *    1 user dashboard
 *    2 spider trap
 *    3 Firewall-based option for block
 *
 * Alex Kemp
 * modem-help.com
 */
?>