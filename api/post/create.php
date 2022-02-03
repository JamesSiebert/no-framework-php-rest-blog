<?php
    session_start();

    // Headers - Open access to anybody
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    require_once(__DIR__ . '../../../vendor/autoload.php');
    require_once '../../public/CSRF.php';

    include_once '../../config/Database.php';
    include_once '../../models/Posts.php';
    include_once '../../models/Image.php';

    use Ramsey\Uuid\Uuid;

    $tokenExp = $_SESSION['csrf-token-expire'];

    if (CSRF::validateToken($_POST['csrf-token'], $_SESSION['csrf-token-expire'])) {

        // CSRF OK

        // Instantiate DB & connect
        $database = new Database();
        $db = $database->connect();

        $postTitle = $_POST["title"];
        $postBody = $_POST["body"];
        $postAuthor = $_POST["author"];
        $postCategory = $_POST["category_id"];


        $imageUUID = Uuid::uuid4();

        // Instantiate image object
        $image= new Image($db);
        $image->id = $imageUUID;
        $image->filename = 'test-img-1.jpg';


        if($image->create()) {

            // IMAGE CREATION SUCCESS - CREATE POST

            // Instantiate blog post object
            $post = new Post($db);

            // Get data from POST
            $post->title = $postTitle;
            $post->body = $postBody;
            $post->author = $postAuthor;
            $post->category_id = $postCategory;
            $post->image_id = $imageUUID;
            $post->ip_address = '192.168.1.1';

            // Create post
            if($post->create()) {
                // Success - Redirect back with params
                $returnString = "successMessage=Post Successful";

                // Also possible to redirect to index
                header("Location: /public/upload_form.php?$returnString");
            } else {

                // TODO Break this down into specific error areas
                $returnString = "errorMessage=Validation Error&title=$postTitle&titleErr=true&body=$postBody&bodyErr=true&author=$postAuthor&authorErr=true&category=$postC&categoryErr=true&imageErr=true";

                // Fail - redirect back with params
                header("Location: /public/upload_form.php?$returnString");
            }

        } else {

            // IMAGE CREATION FAILED

            // TODO Break this down into specific error areas
            $returnString = "errorMessage=Image Creation Error&title=$post->title&titleErr=false&body=$post->body&bodyErr=false&author=$post->author&authorErr=false&category=$post->category_id&categoryErr=false&imageErr=true";

            // Fail - redirect back with params
            header("Location: /public/upload_form.php?$returnString");

        }





    } else {
        // CSRF Fail - redirect back with message
        $returnString = "errorMessage=CSRF Error please try again";
        header("Location: /public/upload_form.php?$returnString");
    }



