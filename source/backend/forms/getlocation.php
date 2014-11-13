<?php
include_once($_SERVER['DOCUMENT_ROOT'] .  "/backend/functions/image.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = getEXIFGPS($_FILES["image"]["tmp_name"]);
    $response = array("success" => $status, "response" => $status);
    echo json_encode($response);
}
?>