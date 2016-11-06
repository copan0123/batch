package main

import (
    "database/sql"
    "fmt"
    "conf"
    "math/rand"
    "time"
    _ "github.com/go-sql-driver/mysql"
    "strings"
)

func main() {

    start_time := time.Now()

    rand.Seed(time.Now().UnixNano())

    // DB open
    db, err := sql.Open(dbconf.DB, dbconf.DB_DSN)
    if err != nil {
        panic(err.Error())
    }
    defer db.Close() // return時に実行

    // create Insert datas
    row_data := map[int]int{}
    for k:=0;k<dbconf.CHANNEL_MAX;k++ {
        for i:=1;i<=dbconf.MAX_TRN_DATA_NUMBERS / CHANNEL_MAX;i++ {
            row_data[k][i] = rand.Intn(dbconf.RAND_MAX)
        }
    }

    // Insert trunsaction data
    query := "TRUNCATE TABLE trn_day_numbers"
    _, err = db.Exec(query)
    if err != nil {
        panic(err.Error())
    }
    sql := "INSERT INTO trn_day_numbers VALUES"
    values := ""
    count := 0
    for k, v := range row_data {
        count = count + 1
        values += fmt.Sprintf("(%v,%v),",k,v)

        if(count == dbconf.EXPLUDE_COMMIT_DATA) {
            values = strings.TrimSuffix(values, ",")
            _, err = db.Exec(sql + values)
            if err != nil {
                panic(err.Error())
            }
            values = ""
            count = 0
        }
    }

    rows, err := db.Query("SELECT count(*) FROM `trn_day_numbers`")
    if err != nil {
        panic(err.Error())
    }
    defer rows.Close() // return時に実行

    for rows.Next() {
        var count int
        if berr := rows.Scan(&count); berr != nil {
            panic(berr.Error())
        }
        fmt.Println(count)
    }

    end_time := time.Now()

    fmt.Printf("実行時間：%f秒\n",(end_time.Sub(start_time)).Seconds())



}
