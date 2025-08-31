<?php

abstract class Connection {
    private static $pdo;
    private static $configs = [
        'dsn' => 'mysql:host=localhost;dbname=db_sorteio',
        'user' => 'root',
        'pass' => ''
    ];

    public static function getPdo() {
        if(!self::$pdo) {
            self::$pdo = new PDO(self::$configs['dsn'], self::$configs['user'], self::$configs['pass']);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$pdo;
    }
}