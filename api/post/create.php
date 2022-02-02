<?php
    session_start();

    // Headers - Open access to anybody
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    require_once '../../public/CSRF.php';
    include_once '../../config/Database.php';
    include_once '../../models/Posts.php';

    $tokenExp = $_SESSION['csrf-token-expire'];

    if (CSRF::validateToken($_POST['csrf-token'], $_SESSION['csrf-token-expire'])) {

        // CSRF OK

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
            // Success - Redirect back with params
            $returnString = "successMessage=Post Successful";

            // Also possible to redirect to index
            header("Location: /public/upload_form.php?$returnString");
        } else {

            // TODO Break this down into specific error areas
            $returnString = "errorMessage=Validation Error&title=$post->title&titleErr=true&body=$post->body&bodyErr=true&author=$post->author&authorErr=true&category=$post->category_id&categoryErr=true&imageErr=true";

            // Fail - redirect back with params
            header("Location: /public/upload_form.php?$returnString");
        }

    } else {
        // CSRF Fail - redirect back with message
        $returnString = "errorMessage=CSRF Error please try again";
        header("Location: /public/upload_form.php?$returnString");
    }



