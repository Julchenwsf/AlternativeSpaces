<?php

function unixTimeToStringDate($t){
    if(date('d')==date('d', $t)) return "Today at " . date('H:i', $t);
    return date('j. M Y \a\t H:i', $t);
}