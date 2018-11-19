<?
    include "config.php";        // {[데이터 베이스]} : 환경설정
                    
    $connect = dbconn() ;        // {[데이터 베이스]} : 연결

    mysql_select_db($cont_db) ;  // {[데이터 베이스]} : 디비선택

    $TheatherName = "" ;

    $Now       = strtotime('now');
    $sCurrTime = date("YmdHis",$Now) ; // 현재시간...
    $sCurrDay  = date("Ymd",$Now) ;    // 현재일자...

    if  ($sCurrDay != $ObjectDay) 
    {
        $sQuery = "Select * From bas_theather    ".
                  " Where Code = '".$Theather."' " ;  
        $QryTheather = mysql_query($sQuery,$connect) ;
        if  ($ArrTheather = mysql_fetch_array($QryTheather))
        {
            $TheatherName = $ArrTheather["Discript"] ; // 극장명을 구한다.

            $sQuery = "Select * From bas_filmtitle   ".
                      " Where Open = '".$FilmOpen."' ".
                      "   And Code = '".$FilmCode."' " ;  
            $QryFilmtitle = mysql_query($sQuery,$connect) ;
            if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
            {
                $FilmName  = $ArrFilmtitle["Name"] ;

                $sQuery = "Replace Into  chk_extension_day      ".
                          "Values                              ".
                          "(                                   ".
                          "       '".$Theather."',             ".
                          "       '".$ObjectDay."',            ".
                          "       '".$FilmOpen."',             ".
                          "       '".$FilmCode."',             ".
                          "       '".$TheatherName."',         ".
                          "       '".$FilmName."',             ".
                          "       '".$DamDang."',              ".
                          "       '".$sCurrTime."',            ".
                          "       '".$Gubun."'                 ".
                          ")                                   " ;
                mysql_query($sQuery,$connect) ;
            }
        }
    }


    mysql_close($connect);       // {[데이터 베이스]} : 단절
?>                                       