<?php
/**
 * PageNotFoundLogReport
 * Copyright 2011 Bob Ray
 *
 * PageNotFoundLogReport is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * PageNotFoundLogReport is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * PageNotFoundLogReport; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package botblockx
 * @author Bob Ray <http://bobsguides.com>

 *
 * Description: The PageNotFoundLogReport snippet presents the contents of the Page Not Found log as a table.
 *
 * /

/* Modified: November 3, 2011 */


$file = MODX_CORE_PATH . '/blocklogs/pagenotfound.log';
$cellWidth = empty($scriptProperties['cell_width'])? 25 : $scriptProperties['cell_width'];
$tableWidth = empty($scriptProperties['table_width'])? '80%' : $scriptProperties['table_width'];
$fp = fopen ($file, 'r');
$output = '';
if ($fp) {
    $output = '<table class="PageNotFoundLog" border="1" cellpadding="10" width="' . $tableWidth . '">';
    $output .= '<tr><th>Page</th><th>Time</th><th>IP</th><th>Host</th><th>User Agent</th><th>Referer</th></tr>';
    while (($line = fgets($fp)) !== false) {
        $line = trim($line);

        if (strpos($line,'#' == 0) || empty($line)) continue;
        $lineArray = explode('`',$line);
        $output .= '<tr>';
        foreach($lineArray as $item) {
            $item = urldecode($item);
            $output .= '<td style="word-break:break-all;" width = "' . $cellWidth . '">' . $item . '</td>';

        }
        $output .= '</tr>';
    }
    $output .= '</table>';
    fclose($fp);
} else {
    $output = 'Could not open: ' . $file;
}

return $output;