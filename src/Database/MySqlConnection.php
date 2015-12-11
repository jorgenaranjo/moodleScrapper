<?php
/**
 * Created by PhpStorm.
 * User: isaac
 * Date: 15/11/15
 * Time: 03:24 PM
 */

namespace Crayon\Database;


use PDO;
use PDOException;
use PDOStatement;

/**
 * Class MySqlConnection
 * @package Crayon\Database
 * @method bool beginTransaction()
 * @method bool commit()
 * @method mixed errorCode()
 * @method array errorInfo()
 * @method int exec ( string $statement )
 * @method mixed getAttribute ( int $attribute )
 * @method bool inTransaction ()
 * @method string lastInsertId ( string $name = null )
 * @method PDOStatement prepare ( string $statement, array $driver_options = array() )
 * @method PDOStatement query ( string $statement )
 * @method string quote ( string $string, int $parameter_type = PDO::PARAM_STR )
 * @method bool rollBack ()
 * @method bool setAttribute ( int $attribute, mixed $value )
 */
class MySqlConnection implements ConnectionInterface
{
    /** @var ConnectionInfoWrapperInterface $info_wrapper */
    private $info_wrapper = null;
    /** @var PDO $db |null */
    public $db = null;
    /** @var  MySqlConnection */
    private static $instance;

    /**
     * Defines a self creation method for a "semi" factory pattern.
     *
     * @param $wrapper
     */
    private function __construct(ConnectionInfoWrapperInterface $wrapper)
    {
        $this->info_wrapper = $wrapper;
    }

    /**
     * Destroys connection.
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Creates DB object.
     *
     * @param ConnectionInfoWrapperInterface $info_wrapper
     *
     * @return \Crayon\Database\ConnectionInterface
     */
    static function create(ConnectionInfoWrapperInterface $info_wrapper)
    {
        $wrapper = new static($info_wrapper);
        $wrapper->connect();
        $wrapper -> exec("SET NAMES utf8");
        static::$instance = $wrapper;

        return $wrapper;
    }

    /**
     * Gets an instance of actual initializated db handler class.
     * @return MySqlConnection
     * @throws \Exception
     */
    static function getInstance()
    {
        $class = static::class;
        if ( ! is_null(static::$instance) && (static::$instance instanceof $class)) {
            return static::$instance;
        }
        throw new \Exception("La instancia de clase no ha sido inicializada");
    }

    /**
     * Connects database to engine.
     * @return bool True if connected.
     */
    public function connect()
    {
        try {
            list( $user, $pasword ) = $this->info_wrapper->getIdentifiers();
            $db = new PDO(
                $this->info_wrapper->getDsn(),
                $user, $pasword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw $e;
        }
        $this->db = $db;

        return true;
    }

    /**
     * Implements call functions on $db.
     *
     * @param $function
     * @param $args
     *
     * @return mixed
     */
    public function __call($function, $args)
    {
        return call_user_func_array(array($this->db, $function), $args);
    }

    /**
     * Disconnects database from engine.
     * @return mixed
     */
    public function disconnect()
    {
        /**
         * @see http://php.net/manual/es/pdo.connections.php
         */
        $this->db = null;
    }
}