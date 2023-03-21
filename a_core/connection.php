<?php
  class Db {
    private static $instance = NULL;

    private function __construct() {}

    private function __clone() {}

    public static function getInstance() {
      if (!isset(self::$instance)) {
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        self::$instance = new PDO('mysql:host='.get_config('db_host').';dbname='.get_config('db_database').'', get_config('db_user'), get_config('db_password'), $pdo_options);
      }
      return self::$instance;
    }
  }
?>