<?php

require_once(dirname(__FILE__) . "/../config/config.php");
require_once(dirname(__FILE__) . "/../util/date_time.php");

class Day_Numbers_Redis
{
    public static function main(){

        date_default_timezone_set('UTC');

        $start_time = microtime();

        try{

            // create insert datas
            $arr_rand_nums = array();
            for($i = 1; $i <= 10;$i++) {
                $arr_rand_nums[] = rand(RAND_MIN, RAND_MAX);
            }

            $dbh = new Redis();
            $dbh->connect('localhost', 6379);
            $dbh->select(0);
            for($i = 0; $i <  count($arr_rand_nums); $i++) {
                $dbh->set($i, $arr_rand_nums[$i]);
            }

        }catch(Exception $e){

            echo $e->getMessage();
        }

        $end_time = microtime();

        echo "実行時間：" . Date_Time::diff_micro_time($start_time, $end_time) . "\n";

    }

}

$day_numbers = new Day_Numbers_Redis();
$day_numbers->main();
