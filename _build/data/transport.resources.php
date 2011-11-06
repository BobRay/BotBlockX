<?php
/**
 * Resource objects for the BotBlockX package
 * @author Bob Ray <http://bobsguides.com>
 * 10/12/2011
 *
 * @package botblockx
 * @subpackage build
 */

$resources = array();

$modx->log(modX::LOG_LEVEL_INFO,'Packaging resource: Bot Block Report<br />');
$resources[1]= $modx->newObject('modResource');
$resources[1]->fromArray(array(
    'id' => 1,
    'class_key' => 'modResource',
    'context_key' => 'web',
    'type' => 'document',
    'contentType' => 'text/html',
    'pagetitle' => 'Bot Block Report',
    'longtitle' => 'Bot Block Report',
    'description' => 'Shows the formatted content of the BotBlockX block log',
    'alias' => 'bot-block-report',
    'published' => '1',
    'parent' => '0',
    'isfolder' => '0',
    'richtext' => '0',
    'menuindex' => '',
    'searchable' => '0',
    'cacheable' => '1',
    'menutitle' => 'Bot Block Report',
    'hidemenu' => '1',
),'',true,true);
$resources[1]->setContent(file_get_contents($sources['build'] . 'data/resources/botblockreport.content.html'));

$modx->log(modX::LOG_LEVEL_INFO,'Packaging resource: File Not Found Log Report<br />');
$resources[2]= $modx->newObject('modResource');
$resources[2]->fromArray(array(
    'id' => 2,
    'class_key' => 'modResource',
    'context_key' => 'web',
    'type' => 'document',
    'contentType' => 'text/html',
    'pagetitle' => 'Page Not Found Log Report',
    'longtitle' => 'Page Not Found Log Report',
    'description' => 'Shows the formatted content of the Page Not Found Log',
    'alias' => 'page-not-found-log-report',
    'published' => '0',
    'parent' => '0',
    'isfolder' => '0',
    'richtext' => '0',
    'menuindex' => '',
    'searchable' => '0',
    'cacheable' => '1',
    'menutitle' => 'Page Not Found Log Report',
    'hidemenu' => '1',
),'',true,true);
$resources[2]->setContent(file_get_contents($sources['build'] . 'data/resources/pagenotfoundlogreport.content.html'));

$modx->log(modX::LOG_LEVEL_INFO,'Packaging resource: Reflect Block Log Report<br />');
$resources[3]= $modx->newObject('modResource');
$resources[3]->fromArray(array(
    'id' => 3,
    'class_key' => 'modResource',
    'context_key' => 'web',
    'type' => 'document',
    'contentType' => 'text/html',
    'pagetitle' => 'Reflect Block Log Report',
    'longtitle' => 'Reflect Block Log Report',
    'description' => 'Shows the formatted content of the Reflect Block Log (note: alias cannot contain the word "reflect")',
    'alias' => 'reflct-block-log-report',
    'published' => '0',
    'parent' => '0',
    'isfolder' => '0',
    'richtext' => '0',
    'menuindex' => '',
    'searchable' => '0',
    'cacheable' => '1',
    'menutitle' => 'Reflect Block Log Report',
    'hidemenu' => '1',
),'',true,true);
$resources[3]->setContent(file_get_contents($sources['build'] . 'data/resources/reflectblocklogreport.content.html'));



return $resources;
