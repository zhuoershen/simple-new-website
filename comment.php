<?php
if (session_status() == PHP_SESSION_NONE) {
        session_start();
}   
if($_POST['token'] !== "!@#$%"){
        die("Request forgery detected");
}

$story_id = (string) @$_SESSION['story_id'];
$username = (string) @$_SESSION['username'];
require 'database.php';
    $flag=true;
    if(empty($_POST)){
        printf("<p>Comment can not be empty!</p>");
        $flag=false;
    }
            
    if($flag==true){
        $comment = $_POST['comment'];
        $stmt = $mysqli->prepare("select id from users where username=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('s',$username);
        $stmt->execute();
        $stmt->bind_result($id);
        $stmt->fetch();
        $stmt->close();
        // printf("%s  %s   %s",$username,$id,$comment);
        $stmt = $mysqli->prepare("insert into comments (user_id, story_id, content) values (?, ?, ?)");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('sss',$id,$story_id,$comment);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();
        header("Location: ".$_SERVER['HTTP_REFERER']);
        // the may be bad according to this:
        // http://stackoverflow.com/a/5285044/3993373
    }


?>
