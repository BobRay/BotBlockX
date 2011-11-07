<?php
/**
 * BotBlockX transport snippets
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
 * Description:  Array of snippet objects for BotBlockX package
 * @package botblockx
 * @subpackage build
 */

if (! function_exists('getSnippetContent')) {
    function getSnippetContent($filename) {
        $o = file_get_contents($filename);
        $o = str_replace('<?php','',$o);
        $o = str_replace('?>','',$o);
        $o = trim($o);
        return $o;
    }
}
$snippets = array();

$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 1,
    'name' => 'BlockLogReport',
    'description' => 'BlockLogReport snippet for BotBlockX.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/blocklogreport.snippet.php'),
),'',true,true);
//$properties = '';

//$snippets[1]->setProperties($properties);

$snippets[2]= $modx->newObject('modSnippet');
$snippets[2]->fromArray(array(
    'id' => 2,
    'name' => 'PageNotFoundLogReport',
    'description' => 'PageNotFoundLogReport snippet for BotBlockX.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/pagenotfoundlogreport.snippet.php'),
),'',true,true);
$properties = include $sources['data'].'/properties/properties.pagenotfoundlogreport.php';
$snippets[2]->setProperties($properties);


$snippets[3]= $modx->newObject('modSnippet');
$snippets[3]->fromArray(array(
    'id' => 3,
    'name' => 'ReflectBlockLogReport',
    'description' => 'ReflectBlockLogReport snippet for BotBlockX.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/reflectblocklogreport.snippet.php'),
),'',true,true);
$properties = include $sources['data'].'/properties/properties.reflectblocklogreport.php';
$snippets[3]->setProperties($properties);


unset($properties);

return $snippets;