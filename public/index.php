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
<?php

    // Below is here in case we want to allow redirection to index after a successful post.
    $successMessage = isset($_GET['successMessage']) ? sanitiseText($_GET['successMessage']) : '';
    $errorMessage = isset($_GET['errorMessage']) ? sanitiseText($_GET['errorMessage']) : '';

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

<!--Simple Navbar-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand">My Blog!</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="./index.php">View Feed</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="upload_form.php">Create a post</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="mx-auto" style="width: 400px;">
        <div class="card border-primary mb-3 mt-5">
            <div class="card-body text-primary">
                <h5 class="card-title mb-2">Public Blog Feed</h5>
                <div class="mt-3 alert alert-info" role="alert">
                    Check out our awesome blog!
                </div>
                <div class="mt-3">
                    <!-- Messages from server-->
                    <?php if($successMessage) {echo "<div class='alert alert-success' role='alert'>$successMessage</div>";}?>
                    <?php if($errorMessage) {echo "<div class='alert alert-danger' role='alert'>$errorMessage</div>";}?>
                </div>

                <div>
                    <div id="post-list"></div>
                    <script>
                        fetch('http://no-framework-php-rest-blog.local/api/post/read.php')
                            .then(function (response) {
                                return response.json();
                            })
                            .then(function (data) {
                                appendData(data);
                            })
                            .catch(function (err) {
                                console.log('error: ' + err);
                            });
                        function appendData(data) {

                            // dump to console
                            console.log(data.data);

                            // Get list image list element
                            const postContainer = document.getElementById("post-list");

                            const postArray = data.data

                            postArray.forEach((item, index) => {
                                const div = document.createElement('div');

                                div.innerHTML = `
                                <div class='mb-3 card'>
                                    <img src='https://picsum.photos/400/300?random=${index}' class='card-img-top' alt='User Image'>
                                    <div class='card-body'>
                                        <p class='card-text text-secondary'>
                                            <b>Title:</b> ${item.title}<br>
                                            <b>Body:</b> ${item.body}<br>
                                            <b>Author:</b> ${item.author}<br>
                                            <b>Category:</b> ${item.category_name}
                                        </p>
                                    </div>
                                </div>
                                `;

                                postContainer.appendChild(div);
                            })
                        }
                    </script>


                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
