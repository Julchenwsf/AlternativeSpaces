<?
include_once("backend/PageBuilder.php");

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