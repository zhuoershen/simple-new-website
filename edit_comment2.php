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
        <!-- <p>Welcome <?php echo $_SESSION['username']; ?></p> -->
         
<?php
if($_POST['token'] !== "!@#$%"){
        die("Request forgery detected");
}
require 'database.php';
$flag=true;
if(empty($_POST)){
    $flag=false;
}
if($flag==true){
    $comment = $_POST['comment'];
    $stmt = $mysqli->prepare("update comments set content=? where id=?");
    $stmt->bind_param('ss',$comment,$_SESSION['comment_id']);
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    $stmt->close();
    printf("<p><strong>You have successfully edited your comment!</strong></p>");  
}
?>
 </body>
    </html>