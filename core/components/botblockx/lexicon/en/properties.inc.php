<?php
/**
 * BotBlockX
 *
 *
 *
 * BotBlockX is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * BotBlockX is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * BotBlockX; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package botblockx
 */
/**
 * Properties (property descriptions) Lexicon Topic
 *
 * @package botblockx
 * @subpackage lexicon
 */

/* BotBlockX Default Property descriptions */

$_lang['botblockx_log_max_lines_desc'] = 'Maximum number of lines in the log. Default: 300';
$_lang['botblockx_use_whitelist_desc'] = 'Use whitelist for known good bots. Default: Yes';
$_lang['botblockx_total_visits_desc'] = 'Slow scrapers must make fewer than this number of visits in the interval set by start_over_secs. Set to `none` to disable the slow scraper check. Default: 1500. You can reduce this for small sites, but watch the log (and show the appeal Tpl).';
$_lang['botblockx_start_over_secs_desc'] = 'Seconds between tracking restarts for slow scrapers. Default :43200 (12 hours)';
$_lang['botblockx_ip_length_desc'] = 'Determines the maximum number of possible IP files to be stored. There will be one, zero-length, IP file for each unique visitor to the site. If there are more visitors than the limit, IP files will be shared, which is no problem unless two visitors with similar IPs visit on the same day -- and that will only be a problem if either one is badly behaved. 2=255 files, 3=4,096 files 4=65,025 files, 5=1,044,480 files. Default: 3';

/* BlockLogReport Property Descriptions */

$_lang['blr_table_width_desc'] = 'Table width for report. Default: 80%';
$_lang['blr_cell_width_desc'] = 'Cell width for report. Default: 30';