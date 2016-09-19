<?php
if (session_status() == PHP_SESSION_NONE) {
        session_start();
}   
?>
<!DOCTYPE html>
    <html>
    <head>
        <title>here the user can edit comments</title>
        <link rel="stylesheet" type="text/css" href="./index.css" />
    </head>
<body>
<!-- header -->
        <div id="header" role="banner">
            <div id="header_bottom_left">
                <a href="./" id="header_img" class="default_header" title>
                    <img src="img/header_icon.png" alt="cynic" id="banner_img" ></img>
                </a>
                <!-- how to create a banner img here?-->
            </div>
        </div>
<?php
if($_POST['token'] !== "!@#$%"){
        die("Request forgery detected");
}
$comment_id = $_POST['delete'];
require 'database.php';
$stmt = $mysqli->prepare("delete from comments where id=?");
$stmt->bind_param('s', $comment_id);
if(!$stmt){
				pinrtf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
$stmt->execute();
printf("You have successfully deleted comments");
header("Location:".$_SERVER['HTTP_REFERER']);
?>
</body>
    </html>