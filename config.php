<?
    /////////////////////////////////////////////////////////////////////////////////
    $MinPrice = 500 ; // 최소 금액.

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
                  " Where UserId = '".$_UserId."'   " ;
        $QryFilmproduce = mysql_query($sQuery,$_connect) ;
        if  ($ArrFilmproduce = mysql_fetch_array($QryFilmproduce))
        {
            $sCode = $ArrFilmproduce["Code"] ;
        }

        return $sCode ;
    }

    function get_singotable($_Open,$_Code,$_connect)
    {
        $sSingoName = "" ;

        $sQuery = "Select SingoName            ".
                  "  From bas_filmtitle        ".
                  " Where Open = '".$_Open."'  ".
                  "   And Code = '".$_Code."'  " ;
//eq($sQuery);
        $QryFilmtitle = mysql_query($sQuery,$_connect) ;
        if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $sSingoName = $ArrFilmtitle["SingoName"] ;
        }

        return $sSingoName ;
    }

    function get_acctable($_Open,$_Code,$_connect)
    {
        $sAccName = "" ;

        $sQuery = "Select AccName              ".
                  "  From bas_filmtitle        ".
                  " Where Open = '".$_Open."'  ".
                  "   And Code = '".$_Code."'  " ;
//eq($sQuery);
        $QryFilmtitle = mysql_query($sQuery,$_connect) ;
        if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $sAccName = $ArrFilmtitle["AccName"] ;
        }

        return $sAccName ;
    }

    function get_degree($_Open,$_Code,$_connect)
    {
        $sDegree = "" ;

        $sQuery = "Select DgrName              ".
                  "  From bas_filmtitle        ".
                  " Where Open = '".$_Open."'  ".
                  "   And Code = '".$_Code."'  " ;
//eq($sQuery);
        $QryFilmtitle = mysql_query($sQuery,$_connect) ;
        if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $sDegree = $ArrFilmtitle["DgrName"] ;
        }

        return $sDegree ;
    }

    function get_mofidylimitdate($_connect)
    {
        $nValue = 0 ;

        $sQuery = "Select Value From MofidyLimitDate " ;
        $QryMofidyLimit = mysql_query($sQuery,$_connect) ;
        if  ($ArrMofidyLimit = mysql_fetch_array($QryMofidyLimit))
        {
            $nValue = $ArrMofidyLimit["Value"] ;
        }

        return $nValue ;
    }

    function get_degreepriv($_Open,$_Code,$_connect)
    {
        $sDegreePriv = "" ;

        $sQuery = "Select DgrpName             ".
                  "  From bas_filmtitle        ".
                  " Where Open = '".$_Open."'  ".
                  "   And Code = '".$_Code."'  " ;
        $QryFilmtitle = mysql_query($sQuery,$_connect) ;
        if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $sDegreePriv = $ArrFilmtitle["DgrpName"] ;
        }

        return $sDegreePriv ;
    }

    function get_showroomorder($_Open,$_Code,$_connect)
    {
        $sRoomOrder = "" ;

        $sQuery = "Select RoomOrder            ".
                  "  From bas_filmtitle        ".
                  " Where Open = '".$_Open."'  ".
                  "   And Code = '".$_Code."'  " ;
        $QryRoomOrder = mysql_query($sQuery,$_connect) ;
        if  ($ArrRoomOrder = mysql_fetch_array($QryRoomOrder))
        {
            $sRoomOrder = $ArrRoomOrder["RoomOrder"] ;
        }

        return $sRoomOrder ;
    }

    function get_GikumAount($_UnitPrice, $_NumPersons )
    {
        return round($_UnitPrice / 1.03) * $_NumPersons ;
    }

    function get_GikumAount2($_UnitPrice, $_GikumRate, $_NumPersons )
    {
        return round($_UnitPrice / $_GikumRate) * $_NumPersons ;
    }

?>
