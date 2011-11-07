<?php
/**
 * ReflectBlockLogReport
 * Copyright 2011 Bob Ray
 *
 * ReflectBlockLogReport is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * ReflectBlockLogReport is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * ReflectBlockLogReport; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package botblockx
 * @author Bob Ray <http://bobsguides.com>
 
 *
 * Description: The Reflect BlockLogReport snippet presents the contents of the Reflect Block log as a table.
 *
 * /

/* Modified: November 5, 2011 */


/* ToDo: Allow compressed report, sorted by IP, with one entry per IP and # of hits */

$file = MODX_CORE_PATH . '/blocklogs/reflctblock.log';

$cellWidth = empty($scriptProperties['cell_width'])? 30 : $scriptProperties['cell_width'];
$tableWidth = empty($scriptProperties['table_width'])? '80%' : $scriptProperties['table_width'];

$fp = fopen ($file, 'r');
$output = '';
if ($fp) {
    $output = '<table class="BlockLog" border="1" cellpadding="10" width="' . $tableWidth .  '">';
    $output .= '<tr><th>IP</th><th>Host</th><th>Time</th><th>User Agent</th><th>Type</th></tr>';
    while (($line = fgets($fp)) !== false) {
        $line = trim($line);
        if (strpos($line,'#' == 0) || empty($line)) continue;
        $lineArray = explode('`',$line);
        $output .= '<tr>';
        foreach($lineArray as $item) {
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