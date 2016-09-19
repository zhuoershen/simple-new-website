<?php
if (session_status() == PHP_SESSION_NONE) {
        session_start();
}   
?>
<!DOCTYPE html>
    <html>
    <head>
        <title>here the user search story</title>
        <link rel="stylesheet" type="text/css" href="./index.css" />
    </head>
    <body>
        <div id="header" role="banner">
            <div id="header_bottom_left">
                <a href="./" id="header_img" class="default_header" title>
                    <img src="img/header_icon.png" alt="cynic"  id="banner_img" ></img>
                </a>
                <!-- how to create a banner img here?-->
            </div>
        </div>
        <p>Search for the stories and authers</p>
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="get">
            <textarea name="search" cols="80" rows="2" required></textarea>
        <input type="submit" class="button"/></p>    
        </form>
        <hr>
        <hr>
        <?php
            require 'database.php';
            $flag=true;
            $search = $_GET['search'];
            $search = explode(" ", $search)[0];
            
            $timer = microtime(true);

            // search for story
            //first round on story content
            $stmt = $mysqli->prepare("select * from stories where locate(?, story)");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmt->bind_param('s',$search);
            $stmt->execute();
            $stmt->bind_result($id, $username, $title, $story, $tag, $created, $upvotes, $last_edited);
            $stories = array();
            while($stmt->fetch()){
                $temp_array = array("id"=>$id, "username"=>$username,"title"=>$title, "story"=>$story, "tag"=>$tag, "created"=>$created, "upvotes"=>$upvotes, "last_edited"=>$last_edited);
                array_push($stories, $temp_array);
            }
            $arrlength=count($stories);

            $rank = array();
            for($x=0;$x<$arrlength;$x++){
                // echo $x." ";
                // print_r($stories[$x]);
                $rank[$stories[$x]["id"]] = 5;
                // echo "<br>";
            }
            //second round on username
            $stmt = $mysqli->prepare("select * from stories where locate(?, username)");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmt->bind_param('s',$search);
            $stmt->execute();
            $stmt->bind_result($id, $username, $title, $story, $tag, $created, $upvotes, $last_edited);
            $username_matched = array();
            while($stmt->fetch()){
                $temp_array = array("id"=>$id, "username"=>$username,"title"=>$title, "story"=>$story, "tag"=>$tag, "created"=>$created, "upvotes"=>$upvotes, "last_edited"=>$last_edited);
                array_push($username_matched, $temp_array);
                if (!array_search($id, array_column($stories, "id"))) {
                    array_push($stories, $temp_array);
                }
            }
            $arrlength=count($username_matched);

           


            // $rank = array();
            for($x=0;$x<$arrlength;$x++){
                // echo $x." ";
                // print_r($username_matched[$x]);
                if (empty($rank[$username_matched[$x]["id"]])) {
                    $rank[$username_matched[$x]["id"]] = 5;
                } else {
                    $rank[$username_matched[$x]["id"]] = $rank[$username_matched[$x]["id"]] + 6;
                }
                // echo "<br>";
            }
            // echo "<hr>";


             //third round by title
            // ??
            // ??


            arsort($rank);
            // echo "ranking: ";
            // print_r($rank);
            // echo "<hr>";
            // echo "stories ";
            // print_r($stories);
            $stmt->close();

            $obj = new ArrayObject($rank);
            $it = $obj->getIterator();
            // echo "<hr>";
            print_r("searching ".$search);echo "<br>";
            echo " fond ".count($rank)." result(s) took about. ".(microtime(true) - $timer)."s";
            echo "<br>"; 
            while ($it->valid()) {
                // echo "it->key ".$it->key()." ".gettype($it->key());
                $result = array_search($it->key(), array_column($stories, "id"));
                // echo " result ".$result." item ".$stories[$result]["id"];
                $it->next();
                // echo "<br>";
        ?>
        <div class="midcol">
            <form class="vote_form" action="story_vote.php" method="post">
                <!-- is the value in correct format? -->
                <input type="hidden" value="story_<?php echo $id; ?>" name="story_id">
                <div class="arrow up login-required" data-event-action="upvote" role="button">
                    <input type ="submit" name=operation[action] value="⬆︎" class="button vote_button"/>
                </div>
                <div><?php echo $stories[$result]["upvotes"]; ?></div>
                <div class="arrow down login-required" data-event-action="downvote" role="button">
                    <input type ="submit" name=operation[action] value="⬇︎" class="button vote_button">
                </div>
            </form>
        </div>
        <?php

        $story_url = htmlspecialchars("http://localhost/~rokee/cse503Module3/story.php?story_id=");
            printf(
                    '<div class="story">
                        <a class="story_title" href="%s%s">%s</a>
                        <p class="story_content_preview">
                        content: %s
                        </p>
                        <p class="story_info">
                            by %s @ %s
                        </p>
                    </div>',
                    $story_url,
                    $stories[$result]["id"],
                    $stories[$result]["title"],
                    $stories[$result]["story"],
                    $stories[$result]["username"],
                    date($stories[$result]["created"])
                );
                        

            echo "</div><hr><br>";
            }
        ?>
        </body>
    </html>