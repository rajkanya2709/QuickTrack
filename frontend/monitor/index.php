<html>
    <head>
        <title>QuickTrack | Monitor</title>
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
        $ip = $_GET["ip"];
        if (!isset($email) and !isset($ip)) {
        } 
        else {
            if(validate_email($email) and validate_ip($ip)) {
                $backendquery = "/var/www/html/gosolutionchallenge/backend/quicktrack -ip=" . (string) $ip . " -email=" . (string) $email;
                exec($backendquery);
                echo '<div class="alert alert-success alert-dismissible fade in">';
                echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                echo '<div><strong>All Set!</strong> If everything went right you should see a report in your inbox soon! Don\'t forget to check your spam folder.</div>';
                echo '</div>';
               // exec("/var/www/html/gosolutionchallenge/backend/quicktrack -ip=8.8.8.8 -email=umairnehri9747@quicktrack.dev");
            } elseif (isset($email) and isset($ip)) {
                echo `<br><br>`;
                echo `<br>`;
                echo '<div class="alert alert-danger alert-dismissible fade in">';
                echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                echo '<strong>Warning!</strong> You have provided an Invalid Email or IP Address! Please try again and make sure that you provide us with an IPv4 address and not IPv6.';
                echo '</div>';
            }
        }
        ?>
        <div class="bg-image"></div>
        <div class="bg-text">
            <a href="../">
                <img src="../assets/logonikbrab.jpeg" alt="logo">
            </a>
            <form action="../monitor/" method="GET">
                <h3 class="webtitle">Enter Email</h3>
                <input type="email" name="email" placeholder="Email">
                <h3 class="webtitle"> IP Address</h3>
                <input type="text" name="ip" placeholder="IP Address">
                <br><br>
                <!-- <button>Monitor</button> -->
                <input class="but" type="submit" name="Submit" value="MONITOR">
            </form>
            <h4 class="or">or</h4>
            <a href="../monitorme/"><button class="bt-monitor but">MONITOR ME</button></a>
        </div>
    </body>
</html>

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

function validate_email($email) {
    // Remove all illegal characters from email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    // Validate Email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // echo("$email is a valid email address");
        return TRUE;
    } else {
        // echo("$email is not a valid email address");
        return FALSE;
    }
}


error_reporting(0);
// Declare variable and store it to email

?>