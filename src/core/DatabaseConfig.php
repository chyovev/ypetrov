<?php

abstract class DatabaseConfig {
    const PARAMS = [
        // development db configuration
        [
            'driver'        => 'pdo_mysql',
            'host'          => 'localhost',
            'dbname'        => 'database',
            'user'          => 'username',
            'password'      => 'password',
            'charset'       => 'utf8mb4',
            'driverOptions' => ['1002' => "SET NAMES 'UTF8MB4' COLLATE 'utf8mb4_general_ci'"],
        ],

        // production db configuration
        [
            'driver'        => 'pdo_mysql',
            'host'          => 'localhost',
            'dbname'        => 'database',
            'user'          => 'username',
            'password'      => 'password',
            'charset'       => 'utf8mb4',
            'driverOptions' => ['1002' => "SET NAMES 'UTF8MB4' COLLATE 'utf8mb4_general_ci'"],
        ],
    ];

    ///////////////////////////////////////////////////////////////////////////
    public static function getParams() {
        return self::PARAMS[IS_PROD];
    }
    
}