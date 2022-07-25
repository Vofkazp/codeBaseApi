<?php

class Db {

    public static function getConnection() {
        $paramsPath = ROOT . '/components/db_params.php';
        $params = include($paramsPath);
        $dsn = "pgsql:host={$params['host']} port={$params['port']} dbname={$params['dbname']}";
        $db = new PDO($dsn, $params['user'], $params['password']);
        return $db;
    }

}
