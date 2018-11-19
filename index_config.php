<?
    /////////////////////////////////////////////////////////////////////////////////

    $ToDate = date("Ymd",time()) ; // 오늘 ...

    $Today = time()-(3600*7) ; // 새벽 7시 까지 오늘로 간주한다...

    if (!$WorkDate)
    {
       $WorkDate = date("Ymd",$Today) ;
    }

    // 하루 전날을 구한다.
    $AgoDate  = date("Ymd",strtotime("-1 day",strtotime(substr($WorkDate,0,4)."-".substr($WorkDate,4,2)."-".substr($WorkDate,6,2).""))) ;

    // 하루 다음날을 구한다.
    $TmroDate = date("Ymd",strtotime("+1 day",strtotime(substr($WorkDate,0,4)."-".substr($WorkDate,4,2)."-".substr($WorkDate,6,2).""))) ;

    // 일주일 전날을 구한다.
    $AgoWeek  = date("Ymd",strtotime("-7 day",strtotime(substr($WorkDate,0,4)."-".substr($WorkDate,4,2)."-".substr($WorkDate,6,2).""))) ;

    /////////////////////////////////////////////////////////////////////////////////

    $cont_db = "mtns" ;

    function dbconn()
    {
        global $connect ;

        if(!$connect) $connect = mysql_connect( "localhost", "mtns", "5421")  or  Error("DB 접속시 에러가 발생했습니다");
        //if(!$connect) $connect = mysql_connect( "localhost", "root", "root0922")  or  Error("DB 접속시 에러가 발생했습니다");

        return $connect;
    }

    /////////////////////////////////////////////////////////////////////////////////

    function tmp_query($_Query,$_connect)
    {
        $sQuery = "INSERT INTO `tmp_query`          ".
                  "       ( `SeqNo` , `Content` )   ".
                  "Values ( NULL, \"".$_Query."\" ) " ;
        mysql_query($sQuery,$_connect) ;
    }

    function eq($_Query)  // 내용을 출력한다.
    {
        echo $_Query."<br>" ;
    }



    /////////////////////////////////////////////////////////////////////////////////

    function get_filmproduce_code($_UserId,$_connect)
    {
        $sCode = "" ;

        $sQuery = "Select Code From bas_filmproduce ".
                  " Where UserId = '".$_UserId."'   " ; //echo $sQuery ;
        $QryFilmproduce = mysql_query($sQuery,$_connect) ;
        if  ($ArrFilmproduce = mysql_fetch_array($QryFilmproduce))
        {
            $sCode = $ArrFilmproduce["Code"] ;
        }

        return $sCode ;
    }

?>
