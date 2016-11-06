<?php

require_once(dirname(__FILE__) . "/../config/config.php");
require_once(dirname(__FILE__) . "/../util/date_time.php");
require_once(dirname(__FILE__) . "/../model/day_numbers.php");
require_once(dirname(__FILE__) . "/../model/trn_day_numbers.php");

class Day_Numbers
{
    public static function main(){

        date_default_timezone_set('UTC');

        $start_time = microtime();

        try{

            $dbh = new PDO(DEV_DSN, DEV_USER, DEV_PASSWORD);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // create insert datas
            $arr_rand_nums = array();
            for($i = 1; $i <= MAX_TRN_DATA_NUMBERS;$i++) {
                $arr_rand_nums[] = array($i, rand(RAND_MIN, RAND_MAX));
            }

            // Insert trunsaction data
            $model_trn_day_numbers = new Model_Trn_Day_Numbers();
            $model_trn_day_numbers->truncate($dbh);
            $model_trn_day_numbers->multi_insert($dbh, $arr_rand_nums);

            // Insert Ranking data
            $day_ranking = $model_trn_day_numbers->select_day_ranking($dbh);
            $model_day_numbers = new Model_Day_Numbers();
            $model_day_numbers->multi_insert($dbh, $day_ranking);

        }catch(Exception $e){

            echo $e->getMessage();
        }

        $end_time = microtime();

        echo "実行時間：" . Date_Time::diff_micro_time($start_time, $end_time) . "\n";

    }

}

$day_numbers = new Day_Numbers();
$day_numbers->main();
