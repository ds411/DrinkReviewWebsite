<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

use Propel\Runtime\ActiveQuery\Criteria;

$title = "Your Feed";

$content = <<<EOF
	<div class='row feed-info'>
		<div class='col-2 feed-types'>
			<ul class="list-group list-group-flush">
			  <li class="list-group-item">
			  	<a href='#'> Your Feed </a>
			  </li>
			  <li class="list-group-item">
			  	<a href='#'> Website Feed </a>
			  </li>
			</ul>
		</div>
		<div id='feed-post-container' class='col-10'>
			%s
		</div>
	</div>
    <script>
    
    var page = 0;
    var morePages = true;
    
    $(window).scroll(function(event) {
        if($(window).scrollTop() == $(document).height() - $(window).height()) {
            if(morePages) {
                $.post({
                    url:'feedScroll.php',
                    data:{page:page},
                    success:function(data) {
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
    
    $('#postBtn').click(function(event) {
        $.post({
            url:'createPost.php',
            data:$('#postForm').serialize(),
            success:function(data) {
                console.log(data);
                if(data.indexOf('<') !== -1) {
                    $('#feed-post-container').prepend(data);
                }
            }
        });
    });
    </script>
EOF;

$friends = FriendQuery::create()
    ->select(array('friend_username'))
    ->findByUsername($_SESSION['username']);

$initialFeedPosts = PostQuery::create()
    ->filterByUsername($friends)
    ->_or()
    ->filterByUsername($_SESSION['username'])
    ->orderByCreationtime(Criteria::DESC)
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
        $drink = " &#x3e; <a href='drink/?d=$id>'" . $review->getDrink()->getName() . "</a>";
        $rating = "<p class='rating'>" . $review->getRating() . "</p>";
    }
    $initialFeed .=
        "<div class='feed-post'><p><a href='profile/?u=$username'>$username</a>$drink</p>$rating<p>Posted on $timestamp</p><p>$body</p></div>";
}

$content = sprintf($content, $initialFeed);
?>