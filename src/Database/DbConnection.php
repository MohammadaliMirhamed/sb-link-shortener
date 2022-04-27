<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOException;

class DbConnection
{
    protected static $instance;

	protected function __construct() {}

	public static function getInstance() {

		if(empty(self::$instance)) {

            $flags = [
                // Turn off persistent connections
                PDO::ATTR_PERSISTENT => false,
                // Enable exceptions
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                // Emulate prepared statements
                PDO::ATTR_EMULATE_PREPARES => true,
                // Set default fetch mode to array
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];
        
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $port = $_ENV['DB_PORT'] ?? '3306';
            $dbname = $_ENV['DB_DATABASE'] ?? 'slim_test';
            $username = $_ENV['DB_USERNAME'] ?? 'root';
            $password = $_ENV['DB_PASSWORD'] ?? '';
            $charset = $_ENV['charset'] ?? 'utf8mb4';
            $flags = $flags ?? [];
            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";

            try {
				self::$instance = new PDO($dsn, $username, $password);
				self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);  
				self::$instance->query('SET NAMES utf8');
				self::$instance->query('SET CHARACTER SET utf8');

			} catch(PDOException $error) {
				echo $error->getMessage();
			}

		}

		return self::$instance;
	}

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

}