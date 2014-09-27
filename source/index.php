<?
include_once("backend/PageBuilder.php");

$content = '    <div id="eventMapSearch">
                    <input type="text" id="input-interest-search" />
                </div>

                <div id="eventMapContainer">
                    <div id="eventMap"></div>
                    <div id="eventMapSidebar"></div>
                </div>';


$pb = new PageBuilder("Index");
$pb->addCSSImport("styles/token-input.css");
$pb->addJSImport("https://maps.googleapis.com/maps/api/js?sensor=false");
$pb->addJSImport("js/data.js");
$pb->addJSImport("js/jquery.tokeninput.js");
$pb->addJSImport("js/mapSearch.js");

$pb->appendContent($content);

echo $pb->toHTML();
?>