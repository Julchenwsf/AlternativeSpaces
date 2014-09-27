<?
include_once("backend/PageBuilder.php");

$sidebar = '    <div id="sidebarSearchFlex">
                    <aside id="sidebarSearch">
                        <b>Search</b>
                        <div id="sidebarSearchMap"></div>
                        <div id="sidebarSearchInput">
                            <input type="text" id="input-interest-search" />
                        </div>
                    </aside>
                </div>';


$pb = new PageBuilder("Photos");
$pb->addCSSImport("styles/token-input.css");
$pb->addJSImport("https://maps.googleapis.com/maps/api/js?sensor=false");
$pb->addJSImport("js/jquery.tokeninput.js");
$pb->addJSImport("js/2DSearch.js");

$pb->appendContent('<div id="searchResults"></div>');
$pb->addContentSibling($sidebar);

echo $pb->toHTML();
?>