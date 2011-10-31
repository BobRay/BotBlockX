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
        'name' => 'verbose',
        'desc' => 'lfnf_verbose_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => '0',
        'lexicon' => 'botblockx:properties',
    ),
    array(
        'name' => 'target',
        'desc' => 'lfnf_target_desc',
        'type' => 'list',
        'options' => array(
            array(
                'name' => 'FILE',
                'value' => 'FILE',
                'menu' => '',
            ),
            array(
                'name' => 'CHUNK',
                'value' => 'CHUNK',
                'menu' => '',
            ),

        ),
        'value' => 'FILE',
        'lexicon' => 'botblockx:properties',
    ),
 );

return $properties;