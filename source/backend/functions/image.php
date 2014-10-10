<?

function scaleImage($inputImage, $maxWidth, $maxHeight, $path){
    list($w, $h) = getimagesize($inputImage['tmp_name']);
    $ratio = min($maxWidth/$w, $maxHeight/$h);
    $height = ceil($h * $ratio);
    $width = ceil($w * $ratio);

    $imgString = file_get_contents($inputImage['tmp_name']);
    $image = imagecreatefromstring($imgString);
    $tmp = imagecreatetruecolor($width, $height);
    imagecopyresampled($tmp, $image, 0, 0, 0, 0, $width, $height, $w, $h);

    $out = imagejpeg($tmp, $path, 100);
    imagedestroy($image);
    imagedestroy($tmp);

    return $out;
}


function getEXIFGPS($imageName) {
    $exif = exif_read_data($imageName, 0, true);
    if(!$exif || $exif['GPS']['GPSLatitude'] == '') return false;
    else {
        $lat = $exif['GPS']['GPSLatitude'];
        list($num, $dec) = explode('/', $lat[0]);
        $lat_s = $num / $dec;
        list($num, $dec) = explode('/', $lat[1]);
        $lat_m = $num / $dec;
        list($num, $dec) = explode('/', $lat[2]);
        $lat_v = $num / $dec;

        $lon = $exif['GPS']['GPSLongitude'];
        list($num, $dec) = explode('/', $lon[0]);
        $lon_s = $num / $dec;
        list($num, $dec) = explode('/', $lon[1]);
        $lon_m = $num / $dec;
        list($num, $dec) = explode('/', $lon[2]);
        $lon_v = $num / $dec;

        $gps_int = array($lat_s + $lat_m / 60.0 + $lat_v / 3600.0, $lon_s + $lon_m / 60.0 + $lon_v / 3600.0);
        return $gps_int;
    }
}

?>