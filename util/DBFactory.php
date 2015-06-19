<?php
class DBFactory
{
    private static $instance;

    /**
     * @return DBFactory
     */
    public static function &get_instance() {
        if (!self::$instance) {
            self::$instance = new DBFactory();
        }
        return self::$instance;
    }

    //

    /**
     * Returns pdo instance by given name. only one instance of pdo will be created for one name.
     *
     * @param string $name
     * @return PDO
     */
    public function get_pdo($name="default") {
        if (!isset($this->pdo_list[$name])) {
            $this->pdo_list[$name] = $this->load_pdo($name);
        }
        return $this->pdo_list[$name];
    }

    /**
     * Returns new instance of pdo
     *
     * @param string $name
     * @return PDO
     */
    public function load_pdo($name="default") {
        $dbcfg = get_config($name, "database");
        $pdo = new PDO(
            $dbcfg['dsn'],
            @$dbcfg['username'],
            @$dbcfg['password'],
            isset($dbcfg['driver_options']) ? $dbcfg['driver_options'] : array());

        return $pdo;
    }

    public function close_pdo($name="default") {
        if (!isset($this->pdo_list[$name])) {
            return;
        }
        unset($this->pdo_list[$name]);
    }

    public $pdo_list = array();

    public function close_pdo_all() {
        if(!empty($this->pdo_list)) {
            foreach(array_keys($this->pdo_list) as $name) {
                unset($this->pdo_list[$name]);
            }
        }
    }

    private function __construct() {
    }

    public function __destruct() {
    }
}
