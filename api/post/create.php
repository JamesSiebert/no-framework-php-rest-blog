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

    $postParams = array(
        'title'=> sanitiseBasic($_POST["title"]),
        'body' => sanitiseBasic($_POST["body"]),
        'author' => sanitiseBasic($_POST["author"]),
        'category' => sanitiseBasic($_POST["category_id"]),
        'ip_address' => sanitiseBasic($_SERVER["REMOTE_ADDR"])
    );


    $imageProcessSuccess = false;
    $imageUUID = Uuid::uuid4();

    if (CSRF::validateToken($_POST['csrf-token'], $_SESSION['csrf-token-expire'])) {

        // CSRF OK

        // Instantiate DB & connect
        $database = new Database();
        $db = $database->connect();


        // IMAGE UPLOADER

        $target_dir = "../../public/images/";
        $target_file_original = $target_dir . basename($_FILES["fileToUpload"]["name"]); // original name replaced
        $imageFileType = strtolower(pathinfo($target_file_original,PATHINFO_EXTENSION));
        $target_file_name = $imageUUID . '.' . $imageFileType;
        $target_file = $target_dir .  $target_file_name;// destination file UUID.jpg
        $uploadOk = 1;


        // Check if image file is an actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                // Image checks failed
                redirectTo(
                    'upload_form',
                    'File must be an image',
                    null,
                    $postParams,
                    true,
                    false,
                    'imageErr=true'
                );

                $uploadOk = 0;
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {

            // Image exists (UUID name - unlikely)
            redirectTo(
                'upload_form',
                'Name conflict try uploading your image again',
                null,
                $postParams,
                true,
                false,
                'imageErr=true'
            );

            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {

            // Image size check failed
            redirectTo(
                'upload_form',
                'Image must be less than 5mb',
                null,
                $postParams,
                true,
                false,
                'imageErr=true'
            );

            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png") {

            // MIME Type check failed
            redirectTo(
                'upload_form',
                'Sorry only jpg and png images allowed',
                null,
                $postParams,
                true,
                false,
                'imageErr=true'
            );

            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error

        if ($uploadOk == 0) {

            // Image checks failed
            redirectTo(
                'upload_form',
                'Image upload error',
                null,
                $postParams,
                true,
                false,
                'imageErr=true'
            );

        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

                // Image checks and upload completed - ok to continue with post
                $imageProcessSuccess = true;

            } else {
                // Image failed to upload
                redirectTo(
                    'upload_form',
                    'Error uploading image',
                    null,
                    $postParams,
                    true,
                    false,
                    'imageErr=true'
                );
            }
        }

        // IMAGE UPLOAD SUCCESS - ADD TO DB

        if($imageProcessSuccess){
            // Instantiate image object
            $image= new Image($db);
            $image->id = $imageUUID;
            $image->filename = $target_file_name;
        }


        // Create Image
        if($imageProcessSuccess && $image->create()) {

            // Instantiate blog post object
            $post = new Post($db);

            $post->title = $postParams['title'];
            $post->body = $postParams['body'];
            $post->author = $postParams['author'];
            $post->category_id = $postParams['category'];
            $post->image_id = $imageUUID;
            $post->ip_address = $postParams['ip_address'];

            // Create post
            if($post->create()) {

                // Successfully created post
                redirectTo(
                    'upload_form',
                    null,
                    'Post Successful',
                    $postParams,
                    false,
                    false,
                    null
                );

            } else {

                // Failed to create post
                redirectTo(
                    'upload_form',
                    'Please choose a new unique title and try again',
                    null,
                    $postParams,
                    true,
                    true,
                    'imageErr=true'
                );
            }

        } else {

            // IMAGE CREATION FAILED
            redirectTo(
                'upload_form',
                'Image Creation Error',
                null,
                $postParams,
                true,
                false,
                'imageErr=true'
            );
        }

    } else {
        // CSRF Fail - redirect back with message
        redirectTo(
            'upload_form',
            'CSRF Error please try again',
            null,
            $postParams,
            true,
            false,
            null
        );
    }

    // Handles redirection and passing submitted params back to the form.
    function redirectTo($destination, $errorMessage, $successMessage, $postParams, $returnParams, $returnParamErrors, $append) {

        $returnString = '';

        if($errorMessage) {
            $message = "errorMessage=$errorMessage";
            $returnString = appendToResponse($returnString, $message);
        }

        if($successMessage) {
            $message = "successMessage=$successMessage";
            $returnString = appendToResponse($returnString, $message);
        }

        // Returns the posted params back to the form so they can be auto re-filled
        if ($returnParams){
            $params =
                "title=" . $postParams['title'] .
                "&body=" . $postParams['body'] .
                "&author=" . $postParams['author'] .
                "&category=" . $postParams['category'];
            $returnString = appendToResponse($returnString, $params);
        }

        // Attach params for form field error styles
        if ($returnParamErrors) {
            $paramErrors = "titleErr=true&bodyErr=true&authorErr=true&categoryErr=true";
            $returnString = appendToResponse($returnString, $paramErrors);
        }

        // For anything extra
        if ($append) {
            $returnString = appendToResponse($returnString, $append);
        }


        // Redirect back to upload for with params
        header("Location: /public/$destination.php$returnString");

        // failsafe
        exit('EXIT');
    }

    function appendToResponse($returnString, $message): string
    {
        // if return string has a value already prefix with '&'
        if($returnString){
            $returnString = "$returnString&$message";
        } else{
            $returnString = "?$message";
        }
        return $returnString;
    }

    function sanitiseBasic($data): string
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }
