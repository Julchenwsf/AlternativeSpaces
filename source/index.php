<?
$title = "Index";
include_once("header.php");
?>

<link rel="stylesheet" href="styles/token-input.css" type="text/css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="js/data.js"></script>
<script type="text/javascript" src="js/jquery.tokeninput.js"></script>
<script type="text/javascript" src="js/mapSearch.js"></script>

    <div id="eventMapSearch">
        <input type="text" id="input-interest-search" />
    </div>

    <div id="eventMapContainer">
        <div id="eventMap"></div>
        <div id="eventMapSidebar"></div>
    </div>


<?
include_once("footer.php");
?>