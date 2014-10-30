<?
$path = substr(realpath("."), 0, strpos(realpath("."), "/source")+7) . "/";
include_once($path . "backend/forms/commentform.php");
include_once($path . "backend/db/DBPhotos.php");
include_once($path . "backend/functions/sharing.php");
include_once($path . "backend/db/DBInterests.php");

function photoTimeFormat($t){
    if(date('d')==date('d', $t)) return "Today at " . date('H:i', $t);
    return date('j. M y \a\t H:i', $t);
}

if(isset($_GET["id"])) {
    $photoData = getPhotoDetails($_GET["id"]);
    $interests = explode(" ", $photoData["interests"]);
    $interestTitles = "";
    foreach($interests as &$interest){
        $data = getInterest($interest);
        $interestTitles .= '<li class="token-input-token"><p>' .$data["interest_name"] . '</p></li>';

    }
    echo '<div class="largeContentBox"><div id="enlargedPhoto"><img src="http://org.ntnu.no/cdpgroup4/images/large/' . $_GET["id"] . '.jpg" /></div>';
    echo '<div class="photoInfoBoxMain">
            <div class="photoInfoBox">
                <div class="photoInfoBoxTitle">' . $photoData["photo_title"] . '</div>
                <div class="photoInfoBoxDescription">
                ' . photoTimeFormat($photoData["upload_time"]) . '<hr/>' . $photoData["description"]. '<hr/>
                <ul class="token-input-list">'. $interestTitles . '</ul></div>
                ' . getShareButtons("http://folk.ntnu.no/valerijf/div/AlternativeSpaces/source/index.php?type=photos&id=" . $_GET["id"], $photoData["photo_title"], $photoData["description"]) . '</div>
            <div class="photoInfoBox">
                <div class="photoInfoBoxTitle">Comments</div>
                <div class="photoInfoBoxDescription">' . getCommentsForm("p" . $_GET["id"]) . '</div></div></div>';
    echo '</div>';
}

?>