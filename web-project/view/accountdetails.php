<?php
session_start();
require_once '../controllers/post-inc.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User account</title>
    <link href="../css/styleaccountDetails.css" rel="stylesheet" type="text/css">
    <!-- pt iconite in pagina - font awesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Stiluri pentru butoane */
        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .button-container button {
            margin: 0 10px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #285943;
            color: #dfffd5;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .button-container button:hover {
            background-color: #8CD790;
        }

        /* Stiluri pentru modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>

<header>
    <nav class="navbar">
        <a href="../index.php">
            RESOURCE FINDER
        </a>
        <a href="support.php">What we do?</a>
    </nav>

    <?php
    if (isset($_SESSION["userID"])) {
        echo "<div class=\"login\">";
        echo "<a id=\"createPostBtn\">Create New Post</a>";
        echo "</div>";
    }
    ?>
</header>

<div class='all-container'>
    <div class='leftside'>
        <div class='search-section'>
            <form class='search-bar' action='accountdetails.php' method='get'>
                <input type="text" name="subject" placeholder=" check in history" required>
                <button type="submit">Search</button>
            </form>
            <div class="button-container">
                <button onclick="window.location.href='../controllers/logout-inc.php'">Logout</button>
                <button onclick="window.location.href='reset-password.php'">Change Password</button>
                <button onclick="window.location.href='pagetobeimplemented.php'">Change Email</button>
            </div>
        </div>
    </div>

    <div class='rightside'>
        <h2> Review your own searches and stay up to date with your progress! </h2>
        <div class='posts-section'>
            <?php
            if (!isset($_GET['subject'])) {
                $resultData = getPostsUID($_SESSION['userID']);
            } else {
                $subject = $_GET['subject'];
                $resultData = getPostsSearchUID($subject, $_SESSION['userID']);
            }

            $resultData = getPostsUID($_SESSION['userID']);

            // Verificăm dacă $resultData este un array
            if (is_array($resultData)) {
                foreach ($resultData as $post) {
                    echo "<div class='post'>";
                    echo "<h3>" . htmlspecialchars($post['postName']) . "</h3>";
                    echo "<p>" . htmlspecialchars($post['postSubject']) . "</p>";
                    echo "</div>";
                }
            } else {
                // În cazul în care $resultData nu este un array, poți afișa un mesaj de eroare sau trata situația în alt mod
                echo "Nu s-au găsit postări pentru utilizatorul curent.";
            }
            ?>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Create New Post</h2>
        <form action="../controllers/post-inc.php" method="post">
            <input type="text" name="postName" placeholder="Post Name" required><br>
            <textarea name="postSubject" placeholder="Post Subject" required></textarea><br>
            <button type="submit">Post</button>
        </form>
    </div>
</div>

<script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("createPostBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal 
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>

</html>
