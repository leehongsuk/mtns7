<?
    $cont_db = "mtns_kofic" ;

    function dbconn()
    {
        global $connect ;

        if(!$connect) $connect = mysql_connect( "localhost", "mtns_kofic", "mtns_kofic")  or  Error("DB 접속시 에러가 발생했습니다");

        return $connect;
    }

?>
