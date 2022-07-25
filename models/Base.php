<?php

class Base
{
    public static function getList()
    {
        $db = Db::getConnection();
        $result = $db->query("SELECT table_name FROM information_schema.tables WHERE table_schema NOT IN ('information_schema','pg_catalog');");
        $listArray = array();
        $i = 0;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $listArray[$i]['name'] = $row['table_name'];
            $i++;
        }
        $result = array();
        $result['TotalElement'] = $i;
        $result['content'] = $listArray;
        return $result;
    }
    
}
