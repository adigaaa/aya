<?php


namespace App\Models;


use App\Enums\DBConfig;

final class Connection
{
    private static $instance;

    private function __construct(){}

    /**
     * @return mixed
     */
    public static function getConnection()
    {
        if (!static::$instance){
            static::$instance = mysqli_connect(DBConfig::DB_HOST, DBConfig::DB_USERNAME,DBConfig::DB_PASSWORD, DBConfig::DB_NAME);
        }
        return static::$instance;
    }
}