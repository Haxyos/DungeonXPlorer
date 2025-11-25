<?php
session_start();
$_SESSION['user_id'] = 'fdqsfqsdf';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script defer src="../../script/profileButtonScript.js"></script>
    <title>profileIcon</title>
</head>

<body>
    <button style="font-size: 1.5em;" id="profileButton">
        ICON PROFILE
    </button>

    <div style="visibility: hidden;font-size: 1.25em; border: solid; padding: 0; margin:0;" id="profileText">
        <?php
        if (isset($_SESSION['user_id'])) {
            echo "
            <a href='#' style='color: black;'>Settings</a><br>
            <form action='../connection/logout.php' method='POST'>
                <input type='submit' value='Sign out' />
            </form>
            ";
        } else {
            echo "<a href='../connection/login.php' style='color: black; '>Sign in</a><br> 
            <a href='../connection/register.php' style='color: black; '>Sign up</a><br> ";
        }
        ?>

    </div>

</body>

</html>