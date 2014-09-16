<?
include_once("DBConnection.php");

//User searching for an interest
if(isset($_GET["q"])) {
    $search = mysql_real_escape_string($_GET["q"]);
    $result = mysql_query("SELECT * FROM interests WHERE interest_name LIKE '" . $search . "%'") or die(mysql_error());
    $return_arr = array();

    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        array_push($return_arr, $row);
    }
    echo json_encode($return_arr);
}

?>