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
        'name' => 'reflect_block',
        'desc' => 'botblockx_reflect_block_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => '1',
        'lexicon' => 'botblockx:properties',
    ),
    array(
        'name' => 'interval',
        'desc' => 'botblockx_interval_desc',
        'type' => 'integer',
        'options' => '',
        'value' => '7',
        'lexicon' => 'botblockx:properties',
    ),
     array(
        'name' => 'max_visits',
        'desc' => 'botblockx_max_visits_desc',
        'type' => 'integer',
        'options' => '',
        'value' => '14',
        'lexicon' => 'botblockx:properties',
    ),
    array(
        'name' => 'penalty',
        'desc' => 'botblockx_penalty_desc',
        'type' => 'integer',
        'options' => '',
        'value' => '60',
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
    array(
        'name' => 'show_slow_appeal',
        'desc' => 'botblockx_show_slow_appeal_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => '1',
        'lexicon' => 'botblockx:properties',
    ),
     array(
        'name' => 'show_fast_appeal',
        'desc' => 'botblockx_show_fast_appeal_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => '1',
        'lexicon' => 'botblockx:properties',
    ),
    array(
        'name' => 'appeal_tpl',
        'desc' => 'botblockx_appeal_tpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'AppealTpl',
        'lexicon' => 'botblockx:properties',
    ),
    array(
        'name' => 'slow_tpl',
        'desc' => 'botblockx_slow_tpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'SlowScraperTpl',
        'lexicon' => 'botblockx:properties',
    ),
    array(
        'name' => 'fast_tpl',
        'desc' => 'botblockx_fast_tpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'FastScraperTpl',
        'lexicon' => 'botblockx:properties',
    ),
    array(
        'name' => 'reflect_message_tpl',
        'desc' => 'botblockx_reflect_message_tpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'ReflectMessageTpl',
        'lexicon' => 'botblockx:properties',
    ),
 );

return $properties;