<? include_once("backend/db/DBPhotos.php");

if(isset($_GET["photo"])) {
    addPhoto($_GET["id"], $_GET["title"], $_GET["lat"], $_GET["lng"]);
}


?>