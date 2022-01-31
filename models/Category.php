<?php
    class Category {
        // DB
        private $conn;
        private string $table = 'categories';

        // Properties
        public $id;
        public $name;
        public $created_at;

        // Constructor
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get categories
        public function read() {

            // Create query
            $query = sprintf('SELECT id, name, created_at FROM %s ORDER BY created_at DESC', $this->table);

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute statement
            $stmt->execute();

            return $stmt;
        }
    }
