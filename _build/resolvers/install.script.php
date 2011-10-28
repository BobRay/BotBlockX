<?php

/**
 * BotBlockX resolver script - runs on install.
 *
 * Copyright 2011 Bob Ray <http://bobsguides.com>
 * @author Bob Ray <http://bobsguides.com>
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
 * Description: Resolver script for BotBlockX package
 * @package botblockx
 * @subpackage build
 */

/* Example Resolver script */

/* The $modx object is not available here. In its place we
 * use $object->xpdo
 */

$modx =& $object->xpdo;

/* Remember that the files in the _build directory are not available
 * here and we don't know the IDs of any objects, so resources,
 * elements, and other objects must be retrieved by name with
 * $modx->getObject().
 */

/* Connecting plugins to the appropriate system events and
 * connecting TVs to their templates is done here.
 *
 * Be sure to set the name of the category in $category.
 *
 * You will have to hand-code the names of the elements and events
 * in the arrays below.
 */

$pluginEvents = array('OnPageNotFound', 'OnHandleRequest');
$plugins = array('BotBlockX');

$category = 'BotBlockX';

$hasPlugins = true;
$hasTemplates = false;
$hasTemplateVariables = false;

 /* If the following variable is set to true, this script will set
  * the existing system settings below. I like these setting, which
  * improve the Manager speed and usability (IMO), but you should
  * generally avoid setting existing system settings for another
  * user unless absolutely necessary for your component. Note that
  *  the changes will remain even if the component is uninstalled
  */

 $hasExistingSettings = false;

/* These existing system settings will always be set during the install */
if ($hasExistingSettings) {
    $settings = array(
        'feed_modx_news_enabled'=> false,
        'feed_modx_security_enabled'=> false,
        'auto_check_pkg_updates' => false,
        'default_per_page' => '100',
        'automatic_alias' => true,
    );
}

/* set to true to connect property sets to elements */
$connectPropertySets = true;


$success = true;

$modx->log(xPDO::LOG_LEVEL_INFO,'Running PHP Resolver.');
switch($options[xPDOTransport::PACKAGE_ACTION]) {
    /* This code will execute during an install */
    case xPDOTransport::ACTION_INSTALL:
        /* Assign plugins to System events */
        if ($hasPlugins) {
            foreach($plugins as $k => $plugin) {
                $pluginObj = $modx->getObject('modPlugin',array('name'=>$plugin));
                if (! $pluginObj) $modx->log(xPDO::LOG_LEVEL_INFO,'cannot get object: ' . $plugin);
                if (empty($pluginEvents)) $modx->log(xPDO::LOG_LEVEL_INFO,'Cannot get System Events');
                if (!empty ($pluginEvents) && $pluginObj) {

                    $modx->log(xPDO::LOG_LEVEL_INFO,'Assigning Events to Plugin ' . $plugin);

                    foreach($pluginEvents as $k => $event) {
                        $intersect = $modx->newObject('modPluginEvent');
                        $intersect->set('event',$event);
                        $intersect->set('pluginid',$pluginObj->get('id'));
                        $intersect->save();
                    }
                }
            }
        }
        /* Connect TVs to Templates. It's assumed that all TVs
         * will be connected to all package templates. If you
         * want to connect different TVs to different templates
         * you need to rewrite this.
         */

        if ($hasTemplates && $hasTemplateVariables) {
            $categoryObj = $modx->getObject('modCategory',array('category'=> $category));
            if (! $categoryObj) {
                $modx->log(xPDO::LOG_LEVEL_INFO,'Coult not retrieve category object: ' . $category);
            } else {
                $categoryId = $categoryObj->get('id');
            }

            $modx->log(xPDO::LOG_LEVEL_INFO,'Attempting to attach TVs to Templates');
            $ok = true;
            $templates = $modx->getCollection('modTemplate', array('category'=> $categoryId));
            if (!empty($templates)) {

                $tvs = $modx->getCollection('modTemplateVar', array('category'=> $categoryId));

                if (!empty($tvs)) {
                    foreach ($templates as $template) {
                        foreach($tvs as $tv) {
                            $tvt = $modx->newObject('modTemplateVarTemplate');
                            if ($tvt) {
                                $r1 = $tvt->set('templateid', $template->get('id'));
                                $r2 = $tvt->set('tmplvarid', $tv->get('id'));
                                if ($r1 && $r2) {
                                    $tvt->save();
                                } else {
                                    $ok = false;
                                    $modx->log(xPDO::LOG_LEVEL_INFO,'Could not set TemplateVarTemplate fields');
                                }
                            } else {
                                $ok = false;
                                $modx->log(xPDO::LOG_LEVEL_INFO,'Could not create TemplateVarTemplate');
                            }
                        }
                    }
                } else {
                    $ok = false;
                    $modx->log(xPDO::LOG_LEVEL_INFO,'Could not retrieve TVs in category: ' . $category);
                }

            } else {
                $ok = false;
                $modx->log(xPDO::LOG_LEVEL_INFO,'Could not retrieve Templates in category: ' . $category);
            }

            if ($ok) {
                $modx->log(xPDO::LOG_LEVEL_INFO,'TVs attached to Templates successfully');
            } else {
                $modx->log(xPDO::LOG_LEVEL_INFO,'Failed to attach TVs to Templates');
            }
        }

    /* This code will execute during an upgrade */
    case xPDOTransport::ACTION_UPGRADE:

        /* put any upgrade tasks (if any) here such as removing
           obsolete files, settings, elements, resources, etc.
        */

        $success = true;
        break;

    /* This code will execute during an uninstall */
    case xPDOTransport::ACTION_UNINSTALL:
        $modx->log(xPDO::LOG_LEVEL_INFO,'Uninstalling . . .');
        $success = true;
        break;

}
$modx->log(xPDO::LOG_LEVEL_INFO,'Script resolver actions completed');
return $success;