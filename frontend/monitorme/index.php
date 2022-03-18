<html>
    <head>
        <title>QuickTrack | Monitor Myself</title>
        <link rel="icon" type="image/x-icon" href="../assets/faviconlogow.jpeg">

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="../stylesheet.css">
        
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        <!--Page body-->
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Aldrich&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        
        <!--Button-->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Langar&display=swap" rel="stylesheet">
    </head>
    <body>
    <?php
        error_reporting(0); 
        $email = $_GET["email"];
    if (!isset($email)) {
    } else {
        if(validate_email($email)) {
            $ip = getenv('HTTP_CLIENT_IP')?:
            getenv('HTTP_X_FORWARDED_FOR')?:
            getenv('HTTP_X_FORWARDED')?:
            getenv('HTTP_FORWARDED_FOR')?:
            getenv('HTTP_FORWARDED')?:
            getenv('REMOTE_ADDR');
        
            $backendquery = "/var/www/html/gosolutionchallenge/backend/quicktrack -ip=" . (string) $ip . " -email=" . (string) $email;
            exec($backendquery);
            echo `<br><br>`;
            echo `<br>`;
            echo '<div class="alert alert-success alert-dismissible fade in">';
            echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
            echo '<strong>All Set!</strong> If everything went right you should see a report in your inbox soon! Don\'t forget to check your spam folder.';
            echo '</div>';
        } elseif (isset($email)) {
            echo `<br><br>`;
            echo `<br>`;
            echo '<div class="alert alert-danger alert-dismissible fade in">';
            echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
            echo '<strong>Warning!</strong> You have provided an Invalid Email or IP Address! Please try again and make sure that your public IP address is in IPv4 and not IPv6 format.';
            echo '</div>';
        }
    }
    ?>
        <div class="bg-image"></div>
        <div class="bg-text">
        <a href="../">
            <img src="../assets/logonikbrab.jpeg" alt="logo" class="center">
        </a>
        <h2 class="webtitle">Enter Your Email</h2>
        <form action ="index.php" method="get">
            <input type="email" name="email" placeholder="Email"><br><br>
            <!-- <button>Submit</button> -->
            <input class="but" type="submit" name="Submit" value="MONITOR">
        </form>
        </div>
    </body>
</html>

<?php
function validate_email($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return TRUE;
    } else {
        //Add something to display to the user, error being that its not a valid email
        return FALSE;
    }
}


?>