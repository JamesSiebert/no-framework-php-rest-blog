<?php
    class Post {
        // Database
        private $conn;
        private string $table = 'posts';

        // Post properties
        public int $id;
        public int $category_id;
        public string $category_name;
        public string $title;
        public string $body;
        public string $author;
        public string $image_id;
        public string $ip_address;
        public string $created_at;

        // Constructor - Runs on instantiation of this class
        public function __construct($db) {
            $this->conn = $db;
        }

        // Get/Read Posts
        public function read() {
            $query = sprintf('
                SELECT
                    c.name as category_name,
                    i.filename as image_filename,
                    i.updated_at as image_updated_at,
                    p.id,
                    p.category_id,
                    p.title,
                    p.body,
                    p.author,
                    p.image_id,
                    p.ip_address,
                    p.created_at
                FROM
                    %s p
                LEFT JOIN
                    categories c ON p.category_id = c.id
                LEFT JOIN
                    images i ON p.image_id = i.id
                ORDER BY
                    p.created_at DESC
            ', $this->table);

            // Prepare Statement (PDO)
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt->execute();

            return $stmt;
        }

        // Get single post
        public function read_single() {
            $query = sprintf('
                SELECT
                    c.name as category_name,
                    p.id,
                    p.category_id,
                    p.title,
                    p.body,
                    p.author,
                    p.created_at
                FROM
                    %s p
                LEFT JOIN
                    categories c ON p.category_id = c.id
                WHERE
                    p.id = ?
                LIMIT 0,1
            ', $this->table);

            // Prepare Statement (PDO)
            $stmt = $this->conn->prepare($query);

            // Bind ID - the 1st parameter should bind to this id
            $stmt->bindParam(1, $this->id);

            // Execute query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            $this->title = $row['title'];
            $this->body = $row['body'];
            $this->author = $row['author'];
            $this->category_id = $row['category_id'];
            $this->category_name = $row['category_name'];
        }

        // Create post
        public function create(): bool
        {
            // Create query
            $query = sprintf('
                INSERT INTO %s
                SET
                    title = :title,
                    body = :body,
                    author = :author,
                    category_id = :category_id,
                    image_id = :image_id,
                    ip_address = :ip_address
            ', $this->table);

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data - sanitise
            $this->title = htmlspecialchars(strip_tags($this->title));
            $this->body = htmlspecialchars(strip_tags($this->body));
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            // $this->image_id
            // $this->ip_address


            // Bind data
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':body', $this->body);
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->bindParam(':image_id', $this->image_id);
            $stmt->bindParam(':ip_address', $this->ip_address);

            // Execute query
            if($stmt->execute()) {
                return true;
            }

            // Print errors
            printf("Error: %s. \n", $stmt->error);
            return false;
        }

        // Update post
        public function update(): bool
        {
            // Create query
            $query = sprintf('
                UPDATE %s
                SET
                    title = :title,
                    body = :body,
                    author = :author,
                    category_id = :category_id
                WHERE
                    id = :id
            ', $this->table);  // ? named parameters or positional (? or :id)

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data - sanitise
            $this->title = htmlspecialchars(strip_tags($this->title));
            $this->body = htmlspecialchars(strip_tags($this->body));
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':body', $this->body);
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->bindParam(':id', $this->id);

            // Execute query
            if($stmt->execute()) {
                return true;
            }

            // Print errors
            printf("Error: %s. \n", $stmt->error);
            return false;
        }

        // Delete post
        public function delete(): bool
        {
            // Create query
            $query = sprintf('DELETE FROM %s WHERE id = :id', $this->table);

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Sanitise data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':id', $this->id);

            // Execute query
            if($stmt->execute()) {
                return true;
            }

            // Print errors
            printf("Error: %s. \n", $stmt->error);
            return false;
        }
    }
