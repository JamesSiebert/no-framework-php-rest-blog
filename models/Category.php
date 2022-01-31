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


        // Get all categories
        public function read() {

            // Create query
            $query = sprintf('SELECT id, name, created_at FROM %s ORDER BY created_at DESC', $this->table);

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute statement
            $stmt->execute();

            return $stmt;
        }

        // Get a single category
        public function read_single() {

            // Create query
            $query = sprintf('
            SELECT id, name, created_at FROM %s WHERE id = :category_id LIMIT 0,1
            ', $this->table);

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind category ID to query
            // ID is defined just after Category instantiation
            $stmt->bindParam(':category_id', $this->id);

            // Execute statement
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            $this->name = $row['name'];
        }


    }
