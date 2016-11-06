<?php

class Model_Trn_Day_Numbers
{

    public function __construct()
    {

    }

    /*
    * Truncate
    */
    public function truncate($dbh)
    {
        $sql = "TRUNCATE TABLE trn_day_numbers;";
//        $sql = "DELETE FROM trn_day_numbers;";

        try{

            $dbh->beginTransaction();

            $stmt = $dbh->prepare($sql);
            $stmt->execute();

            $dbh->commit();
        } catch (Exception $e){
            $dbh->rollback();
            throw $e;
        }

    }

    /*
    *　Multi　Insert
    */
    public function multi_insert($dbh, $arr_data)
    {
        $sql  = 'INSERT INTO ';
        $sql .= '   trn_day_numbers ';
        $sql .= 'VALUES ';

        // コミット単位の件数で行作成
        for($j = 0; $j <  EXPLUDE_COMMIT_DATA; $j++) {
            $sql .= "(:id{$j}, :num{$j})";
            if($j == EXPLUDE_COMMIT_DATA - 1) {
                $sql .= ';';
            }else{
                $sql .= ',';
            }
        }

        try{

            $dbh->beginTransaction();
            $stmt = $dbh->prepare($sql);

            $commit_cnt = 0;
            for($i = 0; $i < count($arr_data); $i++) {

                $stmt->bindValue(":id{$commit_cnt}", $arr_data[$i][0], PDO::PARAM_INT);
                $stmt->bindValue(":num{$commit_cnt}",$arr_data[$i][1], PDO::PARAM_INT);
                $commit_cnt++;
                // コミット単位でインサートを実行
                if(($i+1) % EXPLUDE_COMMIT_DATA == 0) {
                    $stmt->execute();
                    $commit_cnt = 0;
                }
            }
            $dbh->commit();

        } catch (Exception $e){
            $dbh->rollback();
            throw $e;
        }

    }

    /*
    * Select Day Ranking
    */
    public function select_day_ranking($dbh)
    {
        $sql  = "SELECT ";
        $sql .= "    num ";
        $sql .= "   ,count(num) ";
        $sql .= "FROM ";
        $sql .= "    trn_day_numbers ";
        $sql .= "GROUP BY ";
        $sql .= "    num ";
        $sql .= "ORDER BY ";
        $sql .= "    count(num) desc, num asc ";
        $sql .= "LIMIT " . DAY_RUNKING . ";";

        try{

            $stmt = $dbh->prepare($sql);
            $stmt->execute();

            $ret = $stmt->fetchAll();

            if(!$ret){
                throw new Exception(__CLASS__ . ":" . __FUNCTION__ . ":" . "no data selected");
            }

            return $ret;

        } catch (Exception $e){
            throw $e;
        }

    }

}
