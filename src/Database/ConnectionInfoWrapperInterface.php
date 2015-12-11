<?php
/**
 * Created by PhpStorm.
 * User: isaac
 * Date: 15/11/15
 * Time: 04:49 PM
 */

namespace Crayon\Database;


interface ConnectionInfoWrapperInterface
{
    /**
     * Sets DSN name.
     *
     * @param string $dsn
     */
    public function setDsn($dsn);

    /**
     * Gets DSN name.
     *
     * @return string
     */
    public function getDsn();

    /**
     * Sets login credentials for connection.
     *
     * @param string $username
     * @param string $password
     *
     * @return
     */
    public function setIdentifiers($username, $password);

    /**
     * Gets login credentials for connection.
     *
     * @return array
     */
    public function getIdentifiers();

    /**
     * Sets connection options (if needed).
     *
     * @param array $options
     *
     * @return
     */
    public function setOptions(array $options);

    /**
     * Gets Sets connection options.
     *
     * @return array
     */
    public function getOptions();
}