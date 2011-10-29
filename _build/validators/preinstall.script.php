<?php
/**
 * Mycomponent pre-install script
 *
 * Copyright 2011 Bob Ray <http://bobsguides.com>
 * @author Bob Ray <http://bobsguides.com>
 * 10/12/2011
 *
 * Mycomponent is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * Mycomponent is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Mycomponent; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package botblockx
 */
/**
 * Description: Example validator checks for existence of getResources
 * @package botblockx
 * @subpackage build
 */
/**
 * @package botblockx
 * Validators execute before the package is installed. If they return
 * false, the package install is aborted. This example checks for
 * the installation of getResources and aborts the install if
 * it is not found.
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