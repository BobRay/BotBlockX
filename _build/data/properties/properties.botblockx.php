<?php

/**
 * Default properties for the BotBlockX snippet
 * @author Bob Ray <http://bobsguides.com>
 * 10/12/2011
 *
 * @package botblockx
 * @subpackage build
 */

/* ToDo: remove some of these */

$properties = array(
    array(
        'name' => 'use_whitelist',
        'desc' => 'botblockx_use_whitelist_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => '1',
        'lexicon' => 'botblockx:properties',
    ),

    array(
        'name' => 'total_visits',
        'desc' => 'botblockx_total_visits_desc',
        'type' => 'integer',
        'options' => '',
        'value' => '1500',
        'lexicon' => 'botblockx:properties',
    ),
    array(
        'name' => 'start_over_secs',
        'desc' => 'botblockx_start_over_secs_desc',
        'type' => 'integer',
        'options' => '',
        'value' => '43200',
        'lexicon' => 'botblockx:properties',
    ),

    array(
        'name' => 'ip_length',
        'desc' => 'botblockx_ip_length_desc',
        'type' => 'list',
        'options' => array(
            array(
                'name' => 'two (255 Files)',
                'value' => '2',
                'menu' => '',
            ),
            array(
                'name' => 'three (4,096 files)',
                'value' => '3',
                'menu' => '',
            ),
            array(
                'name' => 'four (65,025 files)',
                'value' => '4',
                'menu' => '',
            ),
            array(
                'name' => 'five (1,044,480 files)',
                'value' => '5',
                'menu' => '',
            ),
        ),
        'value' => '3',
        'lexicon' => 'botblockx:properties',
    ),

 );

return $properties;