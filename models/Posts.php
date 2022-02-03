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
        public function checkForExistingPostTitle() {
            $query = sprintf('
                SELECT
                    p.id,
                    p.image_id,
                    p.ip_address,
                    p.category_id,
                    p.title,
                    p.body,
                    p.author,
                    p.created_at
                FROM
                    %s p
                WHERE
                    p.title = :title
                LIMIT 0,1
            ', $this->table);

            // Prepare Statement (PDO)
            $stmt = $this->conn->prepare($query);

            // Bind ID - the 1st parameter should bind to this id
            $stmt->bindParam(':title', $this->title);

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
            // query to check a specific post title
            $checkQuery = sprintf('
                SELECT
                    p.id,
                    p.title, 
                    p.body,
                    p.author,
                    p.category_id,
                    p.image_id,
                    p.ip_address,
                    p.created_at
                FROM
                    %s p
                WHERE
                    p.title = :title
                LIMIT 0,1
            ', $this->table);

            // Run query
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':title', $this->title);
            $checkStmt->execute();
            $checkRow = $checkStmt->fetch(PDO::FETCH_ASSOC);

            $runUpdate = false;

            if($checkRow) {
                // a post with the same title exists

                if($checkRow['ip_address'] == $this->ip_address) {

                    $runUpdate = true;

                    // IP address match - Use update query
                    $query = sprintf('
                        UPDATE %s
                        SET
                            title = :title,
                            body = :body,
                            author = :author,
                            category_id = :category_id,
                            image_id = :image_id,
                            ip_address = :ip_address
                        WHERE
                            id = :id
                    ', $this->table);  // ? named parameters or positional (? or :id)

                    // TODO Consideration here for deleting the old image
                } else {
                    // Different IP - tell user to choose a new title
                    return false;
                }
            } else {

                // No posts with same title exist - use create query
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
            }

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data - sanitise
            $this->title = sanitiseBasic($this->title);
            $this->body = sanitiseBasic($this->body);
            $this->author = sanitiseBasic($this->author);
            $this->category_id = sanitiseBasic($this->category_id);
            $this->image_id = sanitiseBasic($this->image_id);
            $this->ip_address = sanitiseBasic($this->ip_address);

            // Bind data
            if($runUpdate){
                $stmt->bindParam(':id', $checkRow['id']);
            }
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

        function sanitiseBasic($data): string
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);

            return $data;
        }
    }
