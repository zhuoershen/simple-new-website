thing pattern
this file is just for reference, should never be sent to browser


<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://test" data-via="iamakobefan">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

<!-- voting -->
				<!-- maybe this should be changed into a form or use js? and need to hanld duplivate vote-->
				<div class="thing id-story_1 odd link">
					<p class="parent"></p>
					<span class="rank">1</span>
					<div class="midcol">
						<form class="vote_form" action="story_vote.php" method="post">
							<input type='hidden' value='story id' name=story_id>
							<div class="arrow up login-required" data-event-action="upvote" role="button">
								<input type ='submit' name=operation[action] value='upvote' class='button'/>
							</div>
							<div class="score">6477</div>
							<div class="arrow down login-required" data-event-action="downvote" role="button">
								<input type ='submit' name=operation[action] value='downvote' class='button'/>
							</div>
						</form>
					</div>


					<!-- story -->
					<!-- lets put those delete button in the story page -->
					<div class="story">
						<!-- should i use form here? -->
						<!-- <form id="story_form" actoin="story.php/" method="post" name="story_form"> -->
							<p class="title">story title from database</p>
							<p class="detail">
								<time title="??" datetime="???" class="live_time_stamp">?? hours ago</time>
								" by "
								" user name "
							</p>
						<!-- </form> -->
						<ul class="share buttons">
							<li class="share_button">Twitter</li>
							<li class="share_button">Weibo</li>
							<li class="share_button">Facebook</li>
						</ul>
					</div>

				</div>