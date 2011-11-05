<?php

/**
 * Default properties for the LogFileNotFound plugin
 * @author Bob Ray <http://bobsguides.com>
 * 10/31/2011
 *
 * @package botblockx
 * @subpackage build
 */

$properties = array(
    array(
        'name' => 'table_width',
        'desc' => 'lfnf_table_width_desc',
        'type' => 'integer',
        'options' => '',
        'value' => '80%',
        'lexicon' => 'botblockx:properties',
    ),
    array(
        'name' => 'cell_width',
        'desc' => 'lfnf_dell_width_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '30',
        'lexicon' => 'botblockx:properties',
    ),
 );

return $properties;