<?
include_once("backend/PageBuilder.php");

$type = $_GET["type"];
if(!in_array($type, array('photos', 'events'))) header("Location: index.php?type=photos");
$sidebar = '    <div id="sidebarSearchFlex">
                    <aside id="sidebarSearch">
                        <b>Search</b>
                        <div id="sidebarSearchAddress">
                            <input type="text" id="input-address-search" placeholder="Address" />
                            <div id="sidebarSearchMap"></div>
                        </div>
                        <div id="sidebarSearchInput">
                            <input type="text" id="input-interest-search" />
                        </div>

                        <div id="sidebarSearchDatabase">
                            <input type="radio" name="database" value="photos" id="photosRadio"' . ($type == "photos" ? " checked" : "") . '>
                            <label for="photosRadio">Photos</label><br>
                            <input type="radio" name="database" value="events" id="eventsRadio" ' . ($type == "events" ? " checked" : "") . '>
                            <label for="eventsRadio">Events</label>
                        </div>
                    </aside>
                </div>';

if(isset($_GET["photo"])) {
    $sidebar .= '<script type="text/javascript">openPhotoOverlay('. $_GET["photo"] .');</script>';
}


$pb = new PageBuilder("Photos");
$pb->addCSSImport("styles/comments.css");
$pb->addCSSImport("styles/event.css");

$pb->addJSImport("js/2DSearch.js");
$pb->addJSImport("js/comment.js");

$pb->appendContent('<div id="searchResults"></div>');
$pb->addContentSibling($sidebar);

echo $pb->toHTML();
?>