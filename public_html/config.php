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

$site_url = 'http://mmpuchong.mobile-money.com.my:8002';
$_CONFIG = array(
    'ENVIRONMENT' => 'production', /* development | production */
    'Database' => array( // mysql or postgre
        'DB' => 'MYSQL', // MYSQL | POSTGRES
        'DB_host' => '172.17.0.1',
        'DB_user' => 'root',
        'DB_pass' => 'Money123',
        'DB_name' => 'Connect',
        'DB_prefix' => '',
        'DB_port' => 3306
    ),
    'CONNECT' => array(
        'HOST' => 'http://172.17.0.1:8000/',
        'MOUNT' => 'connect',
        'APP_ID' => 'connect',
        'MASTER_KEY' => 'connect@#123'
    ),
    'TABLES' => array (
        'card' => 'Connect.card',
        'customer' => 'NWSC.nwsc_customer',
        'meter_reading' => 'NWSC.nwsc_meter_reading'
    )
);


?>