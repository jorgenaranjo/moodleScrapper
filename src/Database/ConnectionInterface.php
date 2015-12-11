<?php
/**
 * Created by PhpStorm.
 * User: isaac
 * Date: 15/11/15
 * Time: 03:27 PM
 */

namespace Crayon\Database;


interface ConnectionInterface
{
    /**
     * Creates DB object.
     *
     * @param ConnectionInfoWrapperInterface $info_wrapper
     *
     * @return ConnectionInterface
     */
    public static function create(ConnectionInfoWrapperInterface $info_wrapper);

    /**
     * Connects database to engine.
     * @return bool True if connected.
     */
    public function connect();

    /**
     * Disconnects database from engine.
     * @return mixed
     */
    public function disconnect();
}