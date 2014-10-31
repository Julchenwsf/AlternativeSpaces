<?php

function getShareButtons($url, $title, $description) {
    $url = urlencode($url);
    $description = urlencode($description);

    return <<<EOT
<ul class="share-buttons">
	<li><a href="https://www.facebook.com/sharer/sharer.php?u=$url&t=$title" target="_blank"><img src="/img/share/Facebook.png"></a></li>
	<li><a href="https://twitter.com/intent/tweet?source=$url&text=$title:%20$url" target="_blank" title="Tweet"><img src="/img/share/Twitter.png"></a></li>
	<li><a href="https://plus.google.com/share?url=$url" target="_blank" title="Share on Google+"><img src="/img/share/Google+.png"></a></li>
	<li><a href="http://www.tumblr.com/share?v=3&u=$url&t=$title&s=" target="_blank" title="Post to Tumblr"><img src="/img/share/Tumblr.png"></a></li>
	<li><a href="http://pinterest.com/pin/create/button/?url=$url&description=$description" target="_blank" title="Pin it"><img src="/img/share/Pinterest.png"></a></li>
	<li><a href="http://www.reddit.com/submit?url=$url&title=$title" target="_blank" title="Submit to Reddit"><img src="/img/share/Reddit.png"></a></li>
	<li><a href="mailto:?subject=$title&body=$description:%20$url" target="_blank" title="Email"><img src="/img/share/Email.png"></a></li>
</ul>
EOT;
}

?>