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
    'donthit' => '0',
    'hidemenu' => '1',
),'',true,true);
$resources[1]->setContent(file_get_contents($sources['build'] . 'data/resources/botblockreport.content.html'));

return $resources;
