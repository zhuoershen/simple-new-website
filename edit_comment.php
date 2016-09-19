<?php
if (session_status() == PHP_SESSION_NONE) {
        session_start();
}   
?>
<!DOCTYPE html>
    <html>
    <head>
        <title>here the user can input story</title>
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
        $comment_id = $_POST['edit'];
        $stmt = $mysqli->prepare("select content from comments where id=?");
        $stmt->bind_param('s', $comment_id);
        if(!$stmt){
        	pinrtf("Query Prep Failed: %s\n", $mysqli->error);
        	exit;
        }
        $stmt->execute();
        $stmt->bind_result($comment);
        $stmt->fetch();
        $stmt->close();
        ?>
        <p>Please Edit Comment Here</p>
        <form action="edit_comment2.php" method="POST">
            <textarea name="comment" cols="80" rows="6" required><?php echo $comment; ?></textarea>
			<input type="hidden" name="token" value="!@#$%" />	
        <p><input type="submit" name="submit" value="Submit"/></p>    
        </form>
    
        </body>
    </html>