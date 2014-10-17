<?
$path = substr(realpath("."), 0, strpos(realpath("."), "/source")+7) . "/";
include_once($path . "backend/db/DBPhotos.php");
include_once($path . "backend/functions/image.php");
include_once($path . "backend/functions/log.php");


if(isset($_FILES['image'])) {
    if(!isLoggedIn() && isset($_POST["username"]) && isset($_POST["password"])) login($_POST["username"], $_POST["password"]);

    $status = uploadImage($_POST["title"], $_POST["interests"], $_POST["description"], $_FILES["image"]);
    $response = array("success" => empty($status) == "OK", "response" => $status);
    echo json_encode($response);
}


function uploadImage($title, $interests, $description, $image) {
    $errors = array();
    if(!isLoggedIn()) $errors[] = "You must be logged in to upload!";

    $fileErrors = array(null, "The file is to big", "The file is to big", "Only part of the file was uploaded", "No file was uploaded", null,
        "Missing a temporary folder", "Failed to write file to disk", "File upload stopped by extension");
    $uploaddir = '/home/others/groupswww/cdpgroup4/images/';
    $valid_exts = array('jpeg', 'jpg');
    $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
    if(!in_array($ext, $valid_exts)) $errors[] = "Invalid file type, only .jpeg and .jpg";
    if($image['error'] != 0) $errors[] = $fileErrors[$image["error"]];

    $coordinates = getEXIFGPS($image["tmp_name"]);
    if(!$coordinates) $errors[] = "Image has no coordinates, turn on your GPS";
    list($lat, $lng) = $coordinates;

    if(!empty($errors)) return $errors;
    $photoID = addPhoto($_SESSION["username"], $title, $lat, $lng, $interests, $description);
    if(!is_numeric($photoID)) return $photoID;

    if(!(scaleImage($image, 1024, 1024, $uploaddir . "large/" . $photoID . "." . $ext) and scaleImage($image, 240, 240, $uploaddir . "thumb/" . $photoID . "." . $ext))) {
        removePhoto($photoID);
        $errors[] = "Something went wrong! :(";
    }
    return $errors;
}

if(isset($_GET["upload"])) {
    echo '<form action="uploadphoto.php" method="post" enctype="multipart/form-data">
    <input name="title" type="text" placeholder="Title" /><br/>
	<textarea name="description" rows="4" cols="45" placeholder="Description">Example description</textarea><br />
    <input name="interests" type="text" value="1000" /><br/>
    <input name="image" type="file" /><br/>
	<input type="submit" value="Upload!" name="submit" />
    </form>';
}

?>