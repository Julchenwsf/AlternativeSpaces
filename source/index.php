<?
include_once("backend/PageBuilder.php");

if(!array_key_exists("type", $_GET) || !in_array($_GET["type"], array('photos', 'events'))) {
    header("Location: /map/photos");
    $type = "photos";
} else $type = $_GET["type"];

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

                        <div id="sidebarFooter">
                            <a onclick="openOverlay(\'about\')">About</a>
                        </div>
                    </aside>
                </div>';

if(isset($_GET["id"])) {
    $sidebar .= '<script type="text/javascript">$(document).ready(function() {openOverlay(\''. $_GET["id"] .'\');});</script>';
}

$pb = new PageBuilder(ucfirst($type));
$pb->addCSSImport("/styles/comments.css");
$pb->addCSSImport("/styles/event.css");

$pb->addJSImport("/js/2DSearch.js");
$pb->addJSImport("/js/comment.js");

$pb->appendContent('<div id="searchResults"></div>');
$pb->addContentSibling($sidebar);

echo $pb->toHTML();
?>