<?php
    // Headers - Open access to anybody
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Posts.php';

    // CSRF Token Check

    // Check all required params exist
    if (!isset($_POST['csrf-token']) || !isset($_SESSION['csrf-token']) || !isset($_SESSION['csrf-token-expire'])) {
        exit('CSRF Token not set');
    }

    // If session token is equal to post token
    if ($_SESSION['csrf-token']==$_POST['csrf-token']) {

        // Check if expired
        if (time() >= $_SESSION['csrf-token-expire']) {
            exit('CSRF token expired. Please refresh page.');
        } else {

            // CSRF IS VALID
            unset($_SESSION['token']);
            unset($_SESSION['token-expire']);
            echo 'CSRF OK';





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
            }







        }
    } else { exit('INVALID CSRF TOKEN'); }

