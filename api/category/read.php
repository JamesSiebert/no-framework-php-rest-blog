<?php
    // Headers - Open access to anybody
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog category object
    $category = new Category($db);

    // Blog category query
    $result = $category->read();

    // Get row count
    $num = $result->rowCount();

    // Check if we have any categories
    if($num > 0) {
        // Category array
        $category_arr = array();
        $category_arr['data'] = array();

        // Consideration for: Pagination, version info, etc
        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row); // Allows for the use of $name, $body etc without $row->name

            $category_item = array(
                'id' => $id,
                'name' => $name
            );

            // Push to "data"
            array_push($category_arr['data'], $category_item);
        }

        // Convert to JSON and output
        echo json_encode($category_arr);

    } else {

        // No Categories
        echo json_encode(
            array('message' => 'No Categories Found')
        );
    }
