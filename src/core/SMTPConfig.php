<?php

abstract class SMTPConfig {
    const PARAMS = [
        // development email settings
        [
            'is_smtp'       => true,
            'has_smtp_auth' => true,
            'smtp_secure'   => '',
            'port'          => 25,
            'host'          => 'mail.example.com',
            'username'      => 'user@example.com',
            'password'      => 'password',
            'from_address'  => 'user@example.com',
            'from_name'     => 'Joe User',
            'charset'       => 'UTF-8',
        ],

        // production email settings
        [
            'is_smtp'       => true,
            'has_smtp_auth' => true,
            'smtp_secure'   => '',
            'port'          => 25,
            'host'          => 'mail.example.com',
            'username'      => 'user@example.com',
            'password'      => 'password',
            'from_address'  => 'user@example.com',
            'from_name'     => 'Joe User',
            'charset'       => 'UTF-8',
        ],
    ];

    ///////////////////////////////////////////////////////////////////////////
    public static function getParams() {
        return self::PARAMS[IS_PROD];
    }
    
}