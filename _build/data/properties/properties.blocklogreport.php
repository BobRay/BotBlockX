<?php

/**
 * Default properties for the Block Log Report snippet
 * @author Bob Ray <http://bobsguides.com>
 * 10/31/2011
 *
 * @package botblockx
 * @subpackage build
 */

$properties = array(
    array(
        'name' => 'table_width',
        'desc' => 'blr_table_width_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '80%',
        'lexicon' => 'botblockx:properties',
    ),
    array(
        'name' => 'cell_width',
        'desc' => 'blr_cell_width_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '30',
        'lexicon' => 'botblockx:properties',
    ),
 );

return $properties;