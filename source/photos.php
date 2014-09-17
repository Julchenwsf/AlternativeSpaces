<?
$title = "Photos";
include_once("header.php");
?>

<link rel="stylesheet" href="styles/token-input.css" type="text/css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="js/jquery.tokeninput.js"></script>
<script type="text/javascript" src="js/2DSearch.js"></script>

    <aside id="sidebarSearch">
        <b>Search</b>
        <div id="sidebarSearchMap"></div>
        <div id="sidebarSearchInput">
            <input type="text" id="input-interest-search" />
        </div>
    </aside>

    <div id="searchResults"></div>


<?
include_once("footer.php");
?>