<?php

class Model_Day_Numbers
{

    public function __construct()
    {

    }

    /*
    *　multi Insert
    */
    public function multi_insert($dbh, $arr_data)
    {
        $sql  = 'INSERT INTO ';
        $sql .= '   day_numbers ';
        $sql .= 'VALUES ';

        // 行作成
        for($i = 0; $i <  count($arr_data); $i++) {
            $sql .= "(curdate(), :ranking_id{$i}, :num{$i}, :cnt{$i}),";
        }

        $sql = rtrim($sql, ",");

        try{

            $dbh->beginTransaction();
            $stmt = $dbh->prepare($sql);

            for($i = 0; $i < count($arr_data); $i++) {
                $stmt->bindValue(":ranking_id{$i}", $i+1, PDO::PARAM_INT);
                $stmt->bindValue(":num{$i}",$arr_data[$i][0], PDO::PARAM_INT);
                $stmt->bindValue(":cnt{$i}",$arr_data[$i][1], PDO::PARAM_INT);
            }
            $stmt->execute();

            $dbh->commit();

        } catch (Exception $e){
            $dbh->rollback();
            throw $e;
        }

    }

}
