<?php
function convertTo24Hour($timeString) {
    $timeString = str_replace(['Ptg', 'Mlm'], ['PM', 'PM'], $timeString);
    $timeString = str_replace(['Pagi', 'Tghr'], ['AM', 'PM'], $timeString);
    $timeString = preg_replace('/\s+/', '', $timeString);
    $time = DateTime::createFromFormat('h:ia', $timeString);
    if (!$time) {
        $time = DateTime::createFromFormat('hia', $timeString);
    }
    return $time ? $time->format('H:i') : 'Invalid time format';
}
?>