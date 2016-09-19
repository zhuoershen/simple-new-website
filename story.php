<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>here we tell the sotry</title>
	<meta charset="UTF-8"/>
	<link rel="stylesheet" type="text/css" href="./index.css" />
</head>
<body>
	<div id="header" role="banner">
		<div id="header_bottom_left">
			<a href="./" id="header_img" class="default_header" title>
				<img src="img/header_icon.png" alt="cynic" id="banner_img" ></img>
			</a>
			<!-- how to create a banner img here?-->
		</div>
	</div>

	<!-- login status -->
	<div class="side">
		<div class="spacer">
			you are login in as
			<?php
				if (!empty($_SESSION["username"])) {
					echo $_SESSION["username"];
					$flag = true;
				} else {
					echo "guest";
					$flag = false;
				}
			?>
		</div>
	</div>

	<div class="content">
		<!-- pass the variable using url and get -->
		<!-- the info include title, author and time created -->
		<!-- need session to store story id and user id or comment id and user id -->

		<?php
			
			if (empty($_GET['story_id'])) {
				header("Location: index.php");
				exit;
			}

			$story_id =  $_GET['story_id'];
			require 'database.php';

			$stmt = $mysqli->prepare("select id, username, title, story, tag, created, upvotes, last_edited from stories where id=?");
			$stmt->bind_param('s', $story_id);
			if(!$stmt){
				pinrtf("Query Prep Failed: %s\n", $mysqli->error);
				exit;
			}
			$stmt->execute();
			$stmt->bind_result($id, $author, $title, $story, $tag, $created, $upvotes, $last_edited);
			
			$stmt->fetch();
			if (is_null($id)) {
				header("Location: index.php");
				exit;
			}
			$stmt->close();
			$_SESSION['story_id'] = $story_id;
			$_SESSION['title'] = $title;
			
		?>
		<div class="story_info" id="story_info">
			<div class="story_title" id="story_info"><?php printf("%s", $title); ?></div>
			<div class="" id="story_author"><?php printf("%s @ %s", $author, date($created)); ?></div>
		</div>
		<div class="story_content" id="story_content">
			<div>
				<p><?php echo $story; ?></p>
			</div>
		</div>
		<div class="comments">
			<!-- php here to generat all comments -->
		</div>
	</div>
	<?php if (!empty($_SESSION['username']) and $_SESSION['username']==$author) {
		echo '
		<form action="story_operation.php" method="post">
			<button id="edit_button" type ="submit" name="edit" value="story_operation" class="button">edit</button>
			<input type="hidden" name="token" value="!@#$%" />
		</form>
		<form action="delete.php" method="post">
			<button id="delete_button" type ="submit" name="delete" value="story_operation" class="button"/>delete</button>
			<input type="hidden" name="token" value="!@#$%" />
		</form>';
	}
	if(!empty($_SESSION['username'])){
		echo '
		<p>Please Enter Comment Here</p>
        <form action="comment.php" method="POST">
            <textarea name="comment" cols="80" rows="6" required></textarea>
			<input type="hidden" name="token" value="!@#$%" />
        <p><input type="submit" name="submit" value="Submit"/></p>    
        </form>
		';
	}
	$stmt = $mysqli->prepare("select users.username, comments.created, comments.id, comments.content from comments join users on (comments.user_id = users.id) where comments.story_id=?");
	$stmt->bind_param('s', $story_id);
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->execute();
	$stmt->bind_result($user, $comment_created, $comment_id, $comment);
	while ($stmt->fetch()){
		echo '<div class="comment_info">'.htmlspecialchars($user).'@'.htmlspecialchars(date($comment_created)).'</div>';
		echo '<div class="comment">'.htmlspecialchars($comment).'</div>';
		if($flag==true){
			if($_SESSION['username']==$user){
				echo '
				<div>
				<form action="edit_comment.php" method="post">
					<button class="button edit_button" type ="submit" name="edit" value="'.$comment_id.'" >edit</button>
					<input type="hidden" name="token" value="!@#$%" />
				</form>
				<form action="delete_comment.php" method="post">
					<button class="button delete_button" type ="submit" name="delete" value="'.$comment_id.'" />delete</button>
					<input type="hidden" name="token" value="!@#$%" />
				</form></div><br>';
			}
		}
		echo "<br><hr>";
	}
	
	$stmt->close();
	
	?>
	
		
</body>
</html>