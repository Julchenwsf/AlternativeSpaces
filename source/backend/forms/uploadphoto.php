<?
$path = substr(realpath("."), 0, strpos(realpath("."), "/source")+7) . "/";
include_once($path . "backend/db/DBPhotos.php");
include_once($path . "backend/functions/image.php");


if(isset($_FILES['image'])) {
    $status = uploadImage($_POST["title"], $_POST["interests"], $_POST["description"], $_FILES["image"]);
    echo "STATUS: " . $status;
}


function uploadImage($title, $interests, $description, $image) {
    if(!isLoggedIn()) return "You must be logged in to upload!";

    $uploaddir = '/home/others/groupswww/cdpgroup4/images/';
    $valid_exts = array('jpeg', 'jpg');
    $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
    if(!in_array($ext, $valid_exts)) return 'Invalid file type! You can only upload .jpeg, .jpg, .png and .gif.';

    if($image['error'] != 0) {
        switch ($_FILES['file']['error']) {
            case 1:
                return 'The file is to big.';
            case 2:
                return 'The file is to big.';
            case 3:
                return 'Only part of the file was uploaded.';
            case 4:
                return 'No file was uploaded.';
            case 6:
                return "Missing a temporary folder.";
            case 7:
                return "Failed to write file to disk.";
            case 8:
                return "File upload stopped by extension.";
        }
    }

    $coordinates = getEXIFGPS($image["tmp_name"]);
    if(!$coordinates) return "This image has no coordinates attached to it!";
    list($lat, $lng) = $coordinates;
    $photoID = addPhoto($title, $lat, $lng, $interests, $description);

    if(!(scaleImage($image, 1024, 1024, $uploaddir . "large/" . $photoID . "." . $ext) and scaleImage($image, 240, 240, $uploaddir . "thumb/" . $photoID . "." . $ext))) {
        removePhoto($photoID);
        return "Something went wrong! :(";
    }
    return "OK";
}

echo '<form action="uploadphoto.php" method="post" enctype="multipart/form-data">
    <input name="title" type="text" placeholder="Title" /><br/>
	<textarea name="description" rows="4" cols="45" placeholder="Description">Example description</textarea><br />
    <input name="interests" type="text" value="1000" /><br/>
    <input name="image" type="file" /><br/>
	<input type="submit" value="Upload!" name="submit" />
    </form>';

?>