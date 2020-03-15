<?php


/**
 * @param $time
 * @return string
 */
function convert_time($time)
{
    $second = $time;
    $minutes = 0;
    $hour = 0;
    $day = 0;
    $years =0;
    if($time >= 60)
    {

        $minutes = floor($time/60);
        $second = $time % 60;
        if($minutes >= 60)
        {

            $hour = floor($minutes/60);
            $minutes = $minutes % 60;

            if($hour >= 24)
            {

                $day = floor($hour/24);
                $hour = $hour % 24;
                if($day >=365)
                {
                    $years = floor($day/365);
                    $day = $day % 365;
                }
            }
        }
    }
    $years_string = ($years > 0) ? " $years an(s) " : "";
    $day_string = ($day > 0) ? "$day jour(s) " : "";
    $hour_string = ($hour > 0) ? "$hour heure(s) " : "";
    $minutes_string = ($minutes > 0) ? "$minutes minute(s) " : "";
    $second_string = ($second > 0) ? "$second seconde(s)": "";

    return $years_string.$day_string.$hour_string.$minutes_string.$second_string;
}