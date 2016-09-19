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
        <p>Please Enter Title Here</p>
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="POST">
            <textarea name="title" cols="80" rows="2" required></textarea>
        <p>Please Enter Content Here</p>
            <textarea name="content" cols="80" rows="30" required></textarea>
        <p><input type="submit" name="submit" value="Submit"/></p>    
        </form>
            
        <?php
            if($_POST['token'] !== "!@#$%"){
				die("Request forgery detected");
                }
            $title = $_POST['title'];
            $content = $_POST['content'];
            require 'database.php';
            $story_id = (string) @$_SESSION['story_id'];
            //printf("%s",$story_id);
            $flag=true;
            if(!empty($_POST)){
                //if(isset($_POST['title'])&&isset($_POST['content'])){
                    if(!empty($_POST['title'])){
                        //printf("<p><strong>%s</strong></p>\n",htmlspecialchars($_POST['title']));
                    }else{
                        printf("<p>Title can not be empty!</p>");
                        $flag=false;
                    }
                    if(!empty($_POST['content'])){
                        //echo nl2br(htmlspecialchars($_POST['content']));
                    }else{
                        printf("<p>Contents can not be empty!</p>");
                        $flag=false;
                    }
                    if($flag==true){
                        $title = $_POST['title'];
                        $content = $_POST['content'];
                         
                        $stmt = $mysqli->prepare("update stories set title=?, story=? where id=?");
                        $stmt->bind_param('sss',$title,$content,$story_id);
                        if(!$stmt){
                            printf("Query Prep Failed: %s\n", $mysqli->error);
                            exit;
                        }
                        $stmt->execute();
                        $stmt->close();
                        printf("<p><strong>You have successfully edited a new story!</strong></p>");
                    }
               // }   
            }
        ?>
        </body>
    </html>