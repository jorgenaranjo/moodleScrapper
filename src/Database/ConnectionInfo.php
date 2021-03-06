<?php
/**
 * Created by PhpStorm.
 * User: isaac
 * Date: 15/11/15
 * Time: 05:02 PM
 */
namespace Crayon\Database;

class ConnectionInfo extends ConnectionInfoWrapper
{
    const USERNAME = '';
    const PASSWORD = '';
    const HOST = 'localhost';
    const DBNAME = '';

    /**
     * Composes a DSN string.
     * @return string
     */
    public static function dsn()
    {
        $dsn = 'mysql:host=' . static::HOST . ';dbname=' . static::DBNAME;

        return $dsn;
    }

    /**
     * Factory like return method.
     */
    public static function create()
    {
        return parent::create(static::dsn(), static::USERNAME, static::PASSWORD);
    }
}
