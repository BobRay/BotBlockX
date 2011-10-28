<?php
/**
 * BotBlockX plugin
 *
 * Copyright 2011 Bob Ray <http://bobsguides.com>
 *
 * @author Bob Ray <http://bobsguides.com>
 * @version Version 1.0.0 Beta-1
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
 * @package antihammerx
 */

/**
 * MODx BotBlockX plugin
 *
 * Description: 
 * Events: OnPageNotFound
 *
 * @package antihammerx
 *
 * @property
 */

/* only do this if you need lexicon strings */
$modx->lexicon->load('antihammerx:default');

/* This plugin will be connected to the System Event
 * in the PHP resolver
 * (See _build/resolvers/install.script.php) */

require_once $modx->getOption('ahx.core_path', null, $modx->getOption('core_path') . 'components/antihammerx/') . 'model/antihammerx/antihammerx.class.php';

$ahxObj =  new BotBlockX($modx, $scriptProperties);


$ahxObj->init();



$ahxObj->checkUser();

return '';