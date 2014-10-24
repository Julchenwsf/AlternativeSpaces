<?
include_once("DBConnection.php");

//If HTTP GET has argument "q" set...
if(isset($_GET["q"])) {
    $search = mysql_real_escape_string($_GET["q"]);
    //Get all interests where the interest name starts with the value of the $search
    $result = mysql_query("SELECT * FROM interests WHERE interest_name LIKE '" . $search . "%'") or die(mysql_error());
    $return_arr = array();

    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        array_push($return_arr, $row);
    }
    echo json_encode($return_arr);  //Return JSON encoded reply
}

function getInterest($id) {
    if(!is_numeric($id)) return false;
    $result = mysql_query("SELECT * FROM interests WHERE interest_id='$id'") or die(mysql_error());
    $row = mysql_fetch_assoc($result);
    return $row;
}
?>