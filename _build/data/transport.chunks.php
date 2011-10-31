<?php
/**
 * BotBlockX transport chunks
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
 * Description: Array of chunk objects for BotBlockX package
 * @package botblockx
 * @subpackage build
 */

$chunks = array();

$chunks[1]= $modx->newObject('modChunk');
$chunks[1]->fromArray(array(
    'id' => 1,
    'name' => 'SlowScraperTpl',
    'description' => 'Slow Scraper Warning Chunk for BotBlockX',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/slowscraper.chunk.tpl'),
    'properties' => '',
),'',true,true);

$chunks[2]= $modx->newObject('modChunk');
$chunks[2]->fromArray(array(
    'id' => 2,
    'name' => 'FastScraperTpl',
    'description' => 'Fast Scraper Warning Chunk for BotBlockX',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/fastscraper.chunk.tpl'),
    'properties' => '',
),'',true,true);
$chunks[3]= $modx->newObject('modChunk');
$chunks[3]->fromArray(array(
    'id' => 3,
    'name' => 'AppealTpl',
    'description' => 'Appeal Chunk for BotBlockX',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/appeal.chunk.tpl'),
    'properties' => '',
),'',true,true);
$chunks[4]= $modx->newObject('modChunk');
$chunks[4]->fromArray(array(
    'id' => 4,
    'name' => 'ReflectMessageTpl',
    'description' => 'Go Away message for Reflect Violators',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/reflectmessage.chunk.tpl'),
    'properties' => '',
),'',true,true);

$chunks[5]= $modx->newObject('modChunk');
$chunks[5]->fromArray(array(
    'id' => 5,
    'name' => 'PageNotFoundLog',
    'description' => 'Log "file" for PageFileNotFound plugin. Data will only be written here if the "target" property is set to "CHUNK"',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/pagenotfoundlog.chunk.tpl'),
    'properties' => '',
),'',true,true);
return $chunks;