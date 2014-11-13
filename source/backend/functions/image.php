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
    $info = exif_read_data($imageName);
    if (isset($info['GPSLatitude']) && isset($info['GPSLongitude']) &&
        isset($info['GPSLatitudeRef']) && isset($info['GPSLongitudeRef']) &&
        in_array($info['GPSLatitudeRef'], array('E','W','N','S')) && in_array($info['GPSLongitudeRef'], array('E','W','N','S'))) {

        $GPSLatitudeRef  = strtolower(trim($info['GPSLatitudeRef']));
        $GPSLongitudeRef = strtolower(trim($info['GPSLongitudeRef']));

        $lat_degrees_a = explode('/',$info['GPSLatitude'][0]);
        $lat_minutes_a = explode('/',$info['GPSLatitude'][1]);
        $lat_seconds_a = explode('/',$info['GPSLatitude'][2]);
        $lng_degrees_a = explode('/',$info['GPSLongitude'][0]);
        $lng_minutes_a = explode('/',$info['GPSLongitude'][1]);
        $lng_seconds_a = explode('/',$info['GPSLongitude'][2]);

        $lat_degrees = $lat_degrees_a[0] / $lat_degrees_a[1];
        $lat_minutes = $lat_minutes_a[0] / $lat_minutes_a[1];
        $lat_seconds = $lat_seconds_a[0] / $lat_seconds_a[1];
        $lng_degrees = $lng_degrees_a[0] / $lng_degrees_a[1];
        $lng_minutes = $lng_minutes_a[0] / $lng_minutes_a[1];
        $lng_seconds = $lng_seconds_a[0] / $lng_seconds_a[1];

        $lat = ($GPSLatitudeRef  == 's' ? -1.0 : 1.0) * ($lat_degrees+((($lat_minutes*60)+($lat_seconds))/3600));
        $lng = ($GPSLongitudeRef  == 'w' ? -1.0 : 1.0) * ($lng_degrees+((($lng_minutes*60)+($lng_seconds))/3600));

        return array($lat, $lng);
    } else return false;
}


?>