<?php

    // Allows the use of .env files
    declare(strict_types=1);
    require_once('../../vendor/autoload.php');
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.env'); // Path: config/.env
    $dotenv->load();
    $dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);

    class Database {

        // DB Params - private to this class only
        // private string $host = 'localhost';
        // private string $db_name = 'no_framework_php_rest_blog';
        // private string $username = 'root';
        // private string $password = 'root';
        private $conn;

        // DB Connect
        public function connect(): ?PDO
        {
            $this->conn = null;

            try {
                $this->conn = new PDO(sprintf('mysql:host=%s;dbname=%s', $_ENV['DB_HOST'], $_ENV['DB_NAME']),
                    $_ENV['DB_USER'], $_ENV['DB_PASS']);

//                $this->conn = new PDO(sprintf('mysql:host=%s;dbname=%s', $this->host, $this->db_name),
//                    $this->username, $this->password);

                // Set error mode - allows us to get exceptions when we make queries
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch(PDOException $e) {
                echo sprintf('Connection Error: %s', $e->getMessage());
            }

            return $this->conn;
        }
    }
