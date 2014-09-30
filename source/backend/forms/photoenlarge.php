<?

if(isset($_GET["photo_id"])) {
    echo '<div class="largePhoto"><img src="http://org.ntnu.no/cdpgroup4/images/large/' . $_GET["photo_id"] . '.jpg" /></div>';
}

?>