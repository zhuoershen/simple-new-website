<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Cynic, the unkown page of internet</title>
	<meta charset="UTF-8"/>
	<link rel="stylesheet" type="text/css" href="./index.css" />
</head>
<body>
	<!-- header -->
	<div id="header" role="banner">
		<div id="header_bottom_left">
			<a href="./" id="header_img" class="default_header" title>
				<img src="img/header_icon.png" alt="cynic" id="banner_img" >
			</a>
			<!-- how to create a banner img here?-->
		</div>
	</div>

	<!-- side -->
	<div class="side" >
		<div class="sapcer">
			<form action="search.php" id="search" role="search" method="get">
				<!-- role的作用？ -->
				<input type="text" name="search" placeholder="search" tabindex="20" required>
				<input type="submit" value="search" tabindex="22">
			</form>
		</div>

		<?php if (empty($_SESSION) or !array_key_exists("username", $_SESSION)) {
			echo '<div class="spacer" >';
		 ?> 
				<form  method="post" action="login_verify.php" id="login" class="login_form login_form_side">
				<input type="text" name="username" placeholder="user" tabindex="1" maxlength="20">
				<input type="password" name="password" placeholder="password" tabindex="1">

				<div class="submit">
					<span class="throbber"></span>
					<button class="button" type="submit" tabindex="1">login</button>
				</div>
			</form>
			<form action="register.html" id="login" class="reg_form reg_form_side">
				<div class="submit">
					<span class="throbber"></span>
					<button class="button reg_button" id="reg_btn">register</button>
				</div>
			</form>
		<?php
			echo "</div>";			
		}
		?>
		
		<?php if (!empty($_SESSION) and array_key_exists("username", $_SESSION)) {
			echo '<div class="spacer" >';
			echo "<p>Hello, ".htmlspecialchars($_SESSION['username'])."</p>";
		 ?>
			<div class="spacer">
				<div class="sidebox submit submit-link">
					<div class="morelink">
						<a href="submit.php" data-event-action="submit" data-type="subreddit" data-event-detail="link" class="login-required access-required" target="_top">Post New Story</a>
						<!-- 这里login required的作用 -->
						<div class="nub">
						</div>
					</div>
				</div>
			</div>
			
			<form action="logout.php" method="post">
				<button class="button" type="submit" name="logout" value="logout" >logout</button>
			</form>
			
		
		<?php
			echo '</div>';
		}
		?>
	</div>

	<!-- main content -->
	<div class="content" role="main">
		<div class="spacer">
			<!-- for creative portion, upvote for hottest stories -->
			<section hidden id="top_story">
				<div>hottest stories</div>
			</section>
			
			<!-- turn below into PHP, start -->
			<div id="site_table" class="site_table link_listing">
				
				<!-- this part we should have upvotes, story title, story preview -->
				<?php
				require 'database.php';

				$stmt = $mysqli->prepare("select id, username, title, tag, created, upvotes, last_edited from stories order by upvotes");
				if(!$stmt){
					pinrtf("Query Prep Failed: %s\n", $mysqli->error);
					exit;
				}
				$stmt->execute();
				$stmt->bind_result($id, $username, $title, $tag, $created, $upvotes, $last_edited);
				$rank = 1;
				while($stmt->fetch()){
					echo '<div class="thing" id="story_id='.htmlspecialchars($id).'" link>';
					echo '<p class="parent"></p>';
					echo '<span class="rank">'.$rank.'</span>';
					$rank = $rank + 1;
				?>
					<div class="midcol">
						<form class="vote_form" action="story_vote.php" method="post">
							<!-- is the value in correct format? -->
							<input type="hidden" value="story_<?php echo $id; ?>" name="story_id">
							<div class="arrow up login-required" data-event-action="upvote" role="button">
								<input type ="submit" name=operation[action] value="⬆︎" class="button vote_button"/>
							</div>
							<div><?php echo $upvotes; ?></div>
							<div class="arrow down login-required" data-event-action="downvote" role="button">
								<input type ="submit" name=operation[action] value="⬇︎" class="button vote_button">
							</div>
						</form>
					</div>
				<?php
					//$story_url = htmlspecialchars("http://localhost/~rokee/cse503Module3/story.php?story_id=");
					$story_url = htmlspecialchars("http://54.191.250.116/~zhuoershen/newswebsite/story.php?story_id=");
					printf(
							'<div class="story">
								<a class="story_title" href="%s%s">%s</a>
								<p class="story_info">
									by %s @ %s
								</p>
							</div>
							<ul class="share_buttons buttons">
									<li class="share_button">
										<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://54.191.250.116/~zhuoershen/newswebsite/story?id=%d" data-text="I want to share a story!">Tweet</a
									</li>
							</ul>',
							$story_url,
							$id,
							$title,
							$username,
							date($created),
							$id
						);
						// 这个用于替换 line129的路径，懒得打注释符号了
						//<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://localhost/~rokee/cse503Module3/story?id=%d" data-text="I want to share a story!">Tweet</a>
						// <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://54.191.250.116/~zhuoershen/newswebsite/story?id=%d" data-text="I want to share a story!">Tweet</a>
								

					echo "</div>";
				}
				$stmt->close();
				?>
				
				<div class="child"></div>
				<div class="clearleft"></div>
			</div>

			<!-- turn below into PHP, end -->

		</div>
	</div>
</body>
</html>

<script type="text/javascript" src="twitter.js"></script>
<script type="text/javascript">
	var btn = document.getElementById('reg_btn');
    btn.addEventListener('click', function() {
      document.location.href = "register.php";
    });
</script>