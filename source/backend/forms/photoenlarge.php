<?

if(isset($_GET["photo_id"])) {
    echo '<div class="largeContentBox"><img src="http://org.ntnu.no/cdpgroup4/images/large/' . $_GET["photo_id"] . '.jpg" /></div>
    <div id="comments">here is a comment</div>';
}

?>