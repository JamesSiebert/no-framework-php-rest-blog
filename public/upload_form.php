<?php

// Include CSRF class
require_once ("CSRF.php");

// CSRF Token
$token = CSRF::createTokenOnly(30);
$showTokenDebug = true; // For demo only

// Messages from server
$successMessage = isset($_GET['successMessage']) ? sanitiseText($_GET['successMessage']) : '';
$errorMessage = isset($_GET['errorMessage']) ? sanitiseText($_GET['errorMessage']) : '';

// Re-Fill params on server validation failure - could have also used $_SESSION
$title = isset($_GET['title']) ? sanitiseText($_GET['title']) : '';
$body = isset($_GET['body']) ? sanitiseText($_GET['body']) : '';
$author = isset($_GET['author']) ? sanitiseText($_GET['author']) : '';
$category = isset($_GET['category']) ? sanitiseText($_GET['category']) : '';

// Trigger error styles on form
$titleErr = isset($_GET['titleErr']) ? filter_var($_GET['titleErr'], FILTER_VALIDATE_BOOLEAN) : false;
$bodyErr = isset($_GET['bodyErr']) ? filter_var($_GET['bodyErr'], FILTER_VALIDATE_BOOLEAN) : false;
$authorErr = isset($_GET['authorErr']) ? filter_var($_GET['authorErr'], FILTER_VALIDATE_BOOLEAN) : false;
$categoryErr = isset($_GET['categoryErr']) ? filter_var($_GET['categoryErr'], FILTER_VALIDATE_BOOLEAN) : false;
$imageErr = isset($_GET['imageErr']) ? filter_var($_GET['imageErr'], FILTER_VALIDATE_BOOLEAN) : false;

function sanitiseText($data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    // Only allow letters, numbers & whitespace
    if (!preg_match("/^[a-zA-Z0-9-' ]*$/", $data)) {
        // TODO show error message
        return '';
    }
    return $data;
}
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>No Framework PHP REST API</title>
</head>
<body>

    <!--Simple Navbar-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand">My Blog!</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="./index.php">View Feed</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="upload_form.php">Create a post</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="mx-auto" style="width: 400px;">
            <div class="card border-primary mb-3 mt-5">
                <div class="card-body text-primary">
                    <h5 class="card-title mb-2">Create a post</h5>
                    <div class="mt-3">
                        <div class="mt-2 alert alert-info" role="alert">
                            Example Info

                            <!-- Token expiry info for demo only -->
                            <?php if($showTokenDebug){
                                echo '<br><br>Your IP Address: ' . $_SERVER["REMOTE_ADDR"] . '<br><br>';
                                echo 'CSRF TESTING:<br>CurrentTime: ' . time() . '<br>Token Expire: ' . $_SESSION["csrf-token-expire"] . '<br>';
                                echo '<a href="time_check.php" target="_blank">Show current timestamp</a>';
                            }?>

                        </div>
                        <!-- Messages from server-->
                        <?php if($successMessage) {echo "<div class='alert alert-success' role='alert'>$successMessage</div>";}?>
                        <?php if($errorMessage) {echo "<div class='alert alert-danger' role='alert'>$errorMessage</div>";}?>
                    </div>

                    <form action="../api/post/create.php" method="POST" enctype="multipart/form-data">

                        <!-- CSRF Token -->
                        <!-- could also use CSRF::createToken() to generate token and field -->
                        <input type='hidden' name='csrf-token' value='<?php echo $token ?>' />

                        <div class="mb-3">
                            <label for="formControlInputName" class="form-label">Title</label>
                            <input
                                name="title"
                                value="<?PHP echo $title; ?>"
                                type="text" class="form-control <?PHP echo $titleErr ? 'is-invalid' : ''; ?>"
                                id="formControlInputName"
                                placeholder="Post title"
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label for="formControlTextareaBody" class="form-label">Body</label>
                            <textarea
                                name="body"
                                class="form-control <?PHP echo $bodyErr ? 'is-invalid' : ''; ?>"
                                id="formControlTextareaBody"
                                rows="5"
                                placeholder="Post body here"
                                required
                            ><?PHP echo $body; ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="formControlInputAuthor" class="form-label">Author</label>
                            <input
                                name="author"
                                value="<?PHP echo $author; ?>"
                                type="text" class="form-control <?PHP echo $authorErr ? 'is-invalid' : ''; ?>"
                                id="formControlInputAuthor"
                                placeholder="Author"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="formControlInputCategoryID" class="form-label">Category ID</label>
                            <input
                                name="category_id"
                                value="<?PHP echo $category; ?>"
                                type="text"
                                class="form-control <?PHP echo $categoryErr ? 'is-invalid' : ''; ?>"
                                id="formControlInputCategoryID"
                                placeholder="Category ID e.g 1"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="formFile" class="form-label">Image file</label>
                            <input name="fileToUpload" class="form-control <?PHP echo $imageErr ? 'is-invalid' : ''; ?>" type="file" id="fileToUpload" required>
                            <small>(Ideal size: 400px wide by 300px high)</small>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Create Post</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
