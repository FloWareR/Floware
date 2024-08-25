<?php

namespace App;

class Database {
  // Store the connection in a private variable
  private $connection; 

  // Create a connection to the database
  public function __construct() {
    $host = $_ENV['DB_HOST'];
    $user = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASS'];
    $dbname = $_ENV['DB_DATABASE'];

    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

    try {
      $this->connection = new \PDO($dsn, $user, $password);
      $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      
    } catch (\PDOException $e) {
      echo 'Connection failed: ' . $e->getMessage();
    }
  }

  // Return the connection
  public function getConnection() {
    return $this->connection;
  }

}