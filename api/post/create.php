<?php
    // Headers - Open access to anybody
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Posts.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog post object
    $post = new Post($db);

    // Get data from POST
    $post->title = $_POST["title"];
    $post->body = $_POST["body"];
    $post->author = $_POST["author"];
    $post->category_id = $_POST["category_id"];

//    >> Doesnt work for web form <<
//    Get raw posted data
//    $data = json_decode(file_get_contents("php://input"));

//    $post->title = $data->title;
//    $post->body = $data->body;
//    $post->author = $data->author;
//    $post->category_id = $data->category_id;

    // Create post
    if($post->create()) {
        $returnString = 'success=true';

        // Redirect back with params
        header("Location: /public/index.php?$returnString");
    } else {

        // TODO Break this down into specific error areas
        $returnString = "message=Validation Error&title=$post->title&titleErr=true&body=$post->body&bodyErr=true&author=$post->author&authorErr=true&category=$post->category_id&categoryErr=true&imageErr=true";

        // redirect back with params
        header("Location: /public/upload.php?$returnString");

//        echo json_encode(
//            array('message' => 'Post Not Created')
//        );
    }
