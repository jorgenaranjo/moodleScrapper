<?php
/**
 * Created by PhpStorm.
 * User: isaac
 * Date: 15/11/15
 * Time: 04:34 PM
 */

namespace Crayon\Database;


abstract class ConnectionInfoWrapper implements ConnectionInfoWrapperInterface
{
    private $dsn = null;
    private $username = null;
    private $password = null;
    private $options = array();

    /**
     * Defines a self creation method for a "semi" factory pattern.
     */
    private function __construct()
    {
    }

    /**
     * Creates an instances of class.
     *
     * @param $dsn
     * @param $database
     * @param $user
     * @param $password
     * @param array $options
     *
     * @return static
     */
    public static function create($dsn, $user, $password, $options = array())
    {
        $wrapper = new static();
        $wrapper->setDsn($dsn);
        $wrapper->setIdentifiers($user, $password);
        $wrapper->setOptions($options);
        return $wrapper;
    }

    /**
     * @inheritdoc
     *
     * @param $username
     * @param $password
     */
    public function setIdentifiers($username, $password)
    {
        $this->username = static::sanitizeStr($username);
        $this->password = static::sanitizeStr($password);
    }

    /**
     * Filters && sanitizes parameters.
     *
     * @param string $str
     *
     * @return mixed
     */
    protected static function sanitizeStr($str)
    {
        $result = filter_var($str, FILTER_SANITIZE_STRING);
        if ($result === false) {
            throw new \InvalidArgumentException("El parámetro {$str} es inválido.");
        }

        return $result;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getDsn()
    {
        return $this->dsn;
    }

    /**
     * @inheritdoc
     *
     * @param $dsn
     */
    public function setDsn($dsn)
    {
        $this->dsn = static::sanitizeStr($dsn);
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    public function getIdentifiers()
    {
        return array($this->username, $this->password);
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @inheritdoc
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }
}