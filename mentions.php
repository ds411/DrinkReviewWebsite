<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

$title = "Your Mentions";

$content = <<<EOF
    <div class='new-post row'>
        <div class='col-md-2'>
        </div>
    </div>
	<div class='row feed-info'>
		<div class='col-md-2 feed-types'>
			<ul class="list-group list-group-flush">
			  <li class="list-group-item">
			  	<a href='index.php'> Your Feed </a>
			  </li>
			  <li class="list-group-item">
			  	<a href='index.php?s=mentions'> Your Mentions </a>
			  </li>
			</ul>
		</div>
		<div id='feed-post-container' class='col-md-10'>
			%s
		</div>
	</div>
    <script>
    var page = 0;
    var morePages = true;
    
    $(window).scroll(function(event) {
        if($(window).scrollTop() === $(document).height() - $(window).height()) {
            if(morePages) {
                $.ajax({
                    url:'mentionsScroll.php',
					method:'POST',
					async:'false',
                    data:{page:page},
                    success:function(data) {
                        console.log(data);
                        if(data.indexOf('<') != -1) {
                            $('#feed-post-container').append(data);
                            page++;
                        }
                        else if(data === 'End.') {
                            morePages = false;
                        }
                        else {
                            console.log(data);
                        }
                    }
                });
            }
        }
    });
    </script>
EOF;

$mention = '@' . $_SESSION['username'];

$initialFeedPosts = PostQuery::create()
    ->where("Post.Body LIKE '%>$mention<%'")
    ->orderByCreationtime('DESC')
    ->limit(20)
    ->find();

$initialFeed = "";

foreach($initialFeedPosts as $post) {
    $username = $post->getUsername();
    $timestamp = $post->getCreationtime()->format('Y-m-d H:i:s');
    $body = $post->getBody();
    $id = "";
    $drink = "";
    $rating = "";
    if(($review = $post->getReview()) !== null) {
        $id = $review->getDrinkId();
        $drink = " &#x3e; <a href='drink.php?d=$id'>" . $review->getDrink()->getName() . "</a>";
        $rating = "<p class='rating'>Rating: <b>" . $review->getRating() . " / 5</b></p>";
    }
    $initialFeed .=
        "<div class='feed-post'><p><a href='profile.php?u=$username' class='feed-user'>$username</a>$drink</p>$rating<p class='feed-body'>$body</p><hr/><p class='feed-time'>Posted on $timestamp</p></div>";
}

$content = sprintf($content, $initialFeed);
?>