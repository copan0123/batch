<?php

class Date_Time
{
    /*
    * UTC microtime Date format
    */
    public static function get_unix_micro_time()
    {
        $arr_time = explode('.', microtime(true));

        return date('Y-m-d H:i:s', $arr_time[0]) . '.' . $arr_time[1];

    }

    /*
    * diff microtime
    */
    public static function diff_micro_time($s_time, $e_time)
    {
        $arr_e_time = explode(' ', $e_time);
        $arr_s_time = explode(' ', $s_time);

        return ((float)$arr_e_time[0]-(float)$arr_s_time[0]) + ((float)$arr_e_time[1]-(float)$arr_s_time[1]);

    }

}
