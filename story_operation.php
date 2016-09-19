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
$story_id = (string) @$_SESSION['story_id'];
    printf("Welcome %s to edit",$_SESSION['username']);

    require 'database.php';
    $stmt = $mysqli->prepare("select title, story from stories where id=?");
    $stmt->bind_param('s', $story_id);
	if(!$stmt){
		pinrtf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
    $stmt->execute();
    $stmt->bind_result($title, $content);
    $stmt->fetch();
    
   
    $stmt->close();
    $_SESSION['story_id'] = $story_id;

?>
        
<p>Please Enter Title Here</p>
<form action="edit.php" method="POST">
    <textarea name="title" cols="80" rows="2" required><?php printf("%s", $title); ?></textarea>
<p>Please Enter Content Here</p>
    <textarea name="content" cols="80" rows="30" required><?php printf("%s", $content); ?></textarea>
<p><input type="submit" name="submit" value="Submit"/></p>
<input type="hidden" name="token" value="!@#$%" />
</form>
