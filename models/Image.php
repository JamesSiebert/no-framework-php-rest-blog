<?php
class Image {
    // DB
    private $conn;
    private string $table = 'images';

    // Properties
    public string $id;
    public string $filename;
    public string $created_at;
    public string $updated_at;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }


//    // Get all categories
//    public function read() {
//
//        // Create query
//        $query = sprintf('SELECT id, name, created_at FROM %s ORDER BY created_at DESC', $this->table);
//
//        // Prepare statement
//        $stmt = $this->conn->prepare($query);
//
//        // Execute statement
//        $stmt->execute();
//
//        return $stmt;
//    }
//
//    // Get a single category
//    public function read_single() {
//
//        // Create query
//        $query = sprintf('
//            SELECT id, name, created_at FROM %s WHERE id = :category_id LIMIT 0,1
//            ', $this->table);
//
//        // Prepare statement
//        $stmt = $this->conn->prepare($query);
//
//        // Bind category ID to query
//        // ID is defined just after Category instantiation
//        $stmt->bindParam(':category_id', $this->id);
//
//        // Execute statement
//        $stmt->execute();
//
//        $row = $stmt->fetch(PDO::FETCH_ASSOC);
//
//        // Set properties
//        $this->name = $row['name'];
//    }

    // Create a category
    public function create(): bool
    {

        // Query
        $query = sprintf('INSERT INTO %s SET id = :id, filename = :filename', $this->table);

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Sanitise data skipped for filename (filename: <UUID>.jpg)

        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':filename', $this->filename);

        // Execute query
        if($stmt->execute()) {
            return true;
        }

        // Print errors
        printf("Error: %s. \n", $stmt->errors);
        return false;
    }

//    // Update a category
//    public function update() {
//
//        // Query
//        $query = sprintf('UPDATE %s SET name = :name WHERE id = :category_id', $this->table);
//
//        // Prepare statement
//        $stmt = $this->conn->prepare($query);
//
//        // Sanitise data
//        $this->id = htmlspecialchars(strip_tags($this->id));
//        $this->name = htmlspecialchars(strip_tags($this->name));
//
//        // Bind data
//        $stmt->bindParam(':category_id', $this->id);
//        $stmt->bindParam(':name', $this->name);
//
//        // Execute query
//        if($stmt->execute()) {
//            return true;
//        }
//
//        // Print errors
//        printf("Error: s%. \n", $stmt->errors);
//        return false;
//    }
//
//    // Delete a category
//    public function delete() {
//
//        // Query
//        $query = sprintf('DELETE FROM %s WHERE id = :category_id', $this->table);
//
//        // Prepare statement
//        $stmt = $this->conn->prepare($query);
//
//        // Sanitise data
//        $this->id = htmlspecialchars(strip_tags($this->id));
//
//        // Bind data
//        $stmt->bindParam(':category_id', $this->id);
//
//        // Execute query
//        if($stmt->execute()) {
//            return true;
//        }
//
//        // Print errors
//        printf("Errors: %s. \n", $stmt->error);
//        return false;
//    }
}
