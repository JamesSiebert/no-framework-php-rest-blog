<?php
    class Database {
        // DB Params - private to this class only
        private string $host = 'localhost';
        private string $db_name = 'no_framework_php_rest_blog';
        private string $username = 'root';
        private string $password = 'root';
        private $conn;

        // DB Connect
        public function connect(): ?PDO
        {
            $this->conn = null;

            try {
                $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}",
                    $this->username, $this->password);

                // Set error mode - allows us to get exceptions when we make queries
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch(PDOException $e) {
                echo "Connection Error: {$e->getMessage()}";
            }

            return $this->conn;
        }
    }
