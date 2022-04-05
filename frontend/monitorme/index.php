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

        function validate_ip($ip){
            //split ip address in to array by dot
            $ip_segments = explode('.', $ip);
            // Always 4 segments needed
            if (count($ip_segments) !== 4) {
                return FALSE;
            }
            // IP can not start with 0
            if ($ip_segments[0][0] == '0'){
                return FALSE;
            }
            // Check each segment
            foreach ($ip_segments as $segment){
            // IP segments must be digits and can not be
            // longer than 3 digits or greater then 255
                if ($segment == '' OR preg_match("/[^0-9]/", $segment) OR $segment > 255 OR strlen($segment) > 3){
                    return FALSE;
                }
            }
            return TRUE;
        }

        error_reporting(0); 
        $email = $_GET["email"];
    if (!isset($email)) {
    } else {
        $ip = getenv('HTTP_CLIENT_IP')?:
        getenv('HTTP_X_FORWARDED_FOR')?:
        getenv('HTTP_X_FORWARDED')?:
        getenv('HTTP_FORWARDED_FOR')?:
        getenv('HTTP_FORWARDED')?:
        getenv('REMOTE_ADDR');
    
        if(validate_email($email) && validate_ip($ip)){
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
            echo '<strong>Warning!</strong> Please make sure that you have provided a valid Email and your public IP is in IPv4 format and not IPv6. You can check your public IP <u><a href="https://whatismyipaddress.com/">here</a></u>.';
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
