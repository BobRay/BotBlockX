/**
 * BlockLogReport
 * Copyright 2011-2022 Bob Ray
 *
 * BlockLogReport is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * BlockLogReport is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * BlockLogReport; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package botblockx
 * @author Bob Ray <https://bobsguides.com>
  
 *
 * Description: The BlockLogReport snippet presents the contents of the block log as a table.
 *
 * /
 
/* Modified: November 1, 2011 */
/* Modified: June 30, 2013 by sottwell to make it a Dashboard widget */
/* embedded clear-log form, changing the name to clearbbxlog */
 
 
$file = MODX_CORE_PATH . '/logs/ipblock.log';
$cellWidth = empty($scriptProperties['cell_width'])? 30 : $scriptProperties['cell_width'];
$tableWidth = empty($scriptProperties['table_width'])? '80%' : $scriptProperties['table_width'];
if (isset($_POST['clearbbxlog'])) {
    file_put_contents($file, "");
}
$fp = fopen ($file, 'r');
$output = '';
if ($fp) {
    $output = '<table class="classy" style="width:100%;">';
    $output .= '<thead><tr><th>IP</th><th>Host</th><th>Time</th><th>User Agent</th><th>Type</th></tr></thead><tbody>';
    $i = 0;
    while (($line = fgets($fp)) !== false) {
        $style = $i%2? 'style="background:#F9F9F9"' : 'style="background:#fff;"';
        $line = trim($line);
        if (strpos($line,'#' == 0) || empty($line)) continue;
        $lineArray = explode('`',$line);
        $output .= '<tr $style>';
        foreach($lineArray as $item) {
            $item = strip_tags($item);
            $bLogLine = htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
            $output .= '<td style="word-break:break-all;border-bottom:1px solid #E5E5E5" width = "' . $cellWidth . '">' . $item . '</td>';
        }
        $output .= '</tr>';
        $i++;
    }
    $output .= '</tbody></table>';
    $output .= '<hr>
     <form action="" method="post">
    <input type="submit" name="clearbbxlog" value="Clear Log">
    </form>';
    fclose($fp);
} else {
    $output = 'Could not open: ' . $file;
}
 
return $output;