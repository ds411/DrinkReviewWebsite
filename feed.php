<?php

if(!isset($_SESSION)) session_start();

require_once "vendor/autoload.php";
require_once "database/generated-conf/config.php";
require_once "sessionAuth.php";

$title = "Your Feed";

$content = <<<EOF
    <div class='new-post'>
        <form action='createPost.php' method='POST' id='postForm'>
            <textarea maxlength='200' id='postArea' name="body" class='post-area' onfocus='focusFunc()' onblur='blurFunc()'></textarea>
            <button type="button" class="btn btn-success" id='postBtn'>Post</button>
        </form>
    </div>
	<div class='row feed-info'>
		<div class='col-md-2 feed-types'>
			<ul class="list-group list-group-flush">
			  <li class="list-group-item">
			  	<a href='#'> Your Feed </a>
			  </li>
			  <li class="list-group-item">
			  	<a href='#'> Website Feed </a>
			  </li>
			</ul>
		</div>
		<div id='feed-post-container' class='col-md-10'>
			%s
		</div>
	</div>
    <script>

    function focusFunc() {
        $('.post-area').animate({height:"5em"}, 500);
    }
    function blurFunc() {
        if (!$.trim($("#postArea").val())) {
            $('.post-area').animate({height:"2.5em"}, 500);
        }
    }
    
    var page = 0;
    var morePages = true;
    
    $(window).scroll(function(event) {
        if($(window).scrollTop() === $(document).height() - $(window).height()) {
            if(morePages) {
                $.ajax({
                    url:'feedScroll.php',
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
    
    $('#postBtn').click(function(event) {
        $.post({
            url:'createPost.php',
            data:$('#postForm').serialize(),
            success:function(data) {
                $('#postArea').val("");
                if (!$.trim($("#postArea").val())) {
                    $('.post-area').animate({height:"2.5em"}, 500);
                }
                if(data.indexOf('<') !== -1) {
                    $('#feed-post-container').prepend(data);
                }
            }
        });
    });
    </script>
EOF;

$friends = FriendQuery::create()
    ->select('Friend.Friend_Username')
    ->findByUsername($_SESSION['username']);

$initialFeedPosts = PostQuery::create()
    ->where('Post.Username IN ?', $friends)
    ->_or()
    ->where('Post.Username = ?', $_SESSION['username'])
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
        $rating = "<p class='rating'>" . $review->getRating() . "</p>";
    }
    $initialFeed .=
        "<div class='feed-post'><p><a href='profile.php?u=$username' class='feed-user'>$username</a>$drink</p>$rating<p class='feed-body'>$body</p><p class='feed-time'>Posted on $timestamp</p></div>";
}

$content = sprintf($content, $initialFeed);
?>