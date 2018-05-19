<?php
/**
 * IDE: PhpStorm.
 * Created by: Trevor
 * Date: 4/26/18
 * Time: 11:14 AM
 */

require_once './libs/parse/autoload.php';
ob_start();
session_start();

$site_url = 'http://TDocker-MY:8003/'; //'http://mmpuchong.mobile-money.com.my:8003';
$_CONFIG = array(
    'ENVIRONMENT' => 'development', /* development | production */
    'Database' => array( // mysql or postgre
        'DB' => 'MYSQL', // MYSQL | POSTGRES
        'DB_host' => '172.17.0.1',
        'DB_user' => 'root',
        'DB_pass' => 'connect12366123',
        'DB_name' => 'Connect',
        'DB_prefix' => '',
        'DB_port' => 3308
    ),
    'CONNECT' => array(
        'HOST' => 'http://10.10.2.44:1337/',
        'MOUNT' => 'connect',
        'APP_ID' => 'connect',
        'MASTER_KEY' => 'connect@#123'
    ),
    'TABLES' => array(
        'card' => 'Connect.card',
        'customer' => 'NWSC.nwsc_customer',
        'meter_reading' => 'NWSC.nwsc_meter_reading',
        'picklistCollection' => 'ConnectPicklist.pkl_collection',
        'picklistLocation' => 'ConnectPicklist.pkl_location',
        'picklistCustomForm' => 'ConnectPicklist.pkl_custom_form',
        'picklistCustomFormField' => 'ConnectPicklist.pkl_custom_form_field',
        'picklist' => 'ConnectPicklist.pkl_picklist',
        'picklistItem' => 'ConnectPicklist.pkl_picklist_item',
        'picklistType' => 'ConnectPicklist.pkl_document_type',
        'picklistLog' => 'ConnectPicklist.pkl_pick_log'
    ),
    'CONSTANTS' => array(
        'slack_url' => 'https://hooks.slack.com/services/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'
    )
);


?>