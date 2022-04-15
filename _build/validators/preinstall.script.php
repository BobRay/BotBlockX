<?php
/**
 * BotBlockX pre-install script
 *
 * Copyright 2011-2022 Bob Ray <https://bobsguides.com>
 * @author Bob Ray <https://bobsguides.com>
 * 10/12/2011
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
 * BotBlockX; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package botblockx
 */
/**
 * Description: Validator makes sure filemtime() and fileatime() are enabled.
 * @package botblockx
 * @subpackage build
 */
/**
 * @package botblockx
 * Validators execute before the package is installed. If they return
 * false, the package install is aborted. This example checks to make
 * sure that the filemtime() and fileatime() functions are enables and
 * aborts the install if they are not.
 */

/* The $modx object is not available here. In its place we
 * use $object->xpdo
 */

$modx =& $object->xpdo;



switch($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
        $modx->log(xPDO::LOG_LEVEL_INFO,'Running PHP Validator.');
        $modx->log(xPDO::LOG_LEVEL_INFO,'Making sure filemtime is enabled ');
        $success = true;

        $file = MODX_CORE_PATH . 'cache/deleteme';
   $fp = fopen($file,'w');

   fclose($fp);

   if (! touch($file, time(), time() )) {
            $modx->log(xPDO::LOG_LEVEL_INFO,'TOUCH FAILED');

   }
   if (! touch($file, time(), time() + 1 )) {
            $modx->log(xPDO::LOG_LEVEL_INFO,'TOUCH FAILED');
   }
   clearstatcache();
   /* if filemtime and fileatime are enabled, these should differ */
   if (filemtime($file) == fileatime($file) ) {
       $success = false;
       $modx->log(xPDO::LOG_LEVEL_ERROR,'Your server does not have fileatime enabled. This plugin will not run properly without it -- install aborted.');
   } else {
       $modx->log(xPDO::LOG_LEVEL_INFO,'Test Succeeded - install will continue.');
   }
   unlink($file);
        break;
    
   /* These cases must return true or the upgrade/uninstall will be cancelled */
   case xPDOTransport::ACTION_UPGRADE:
        $success = true;
        break;

    case xPDOTransport::ACTION_UNINSTALL:
        $success = true;
        break;
}

return $success;