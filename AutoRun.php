<?
    set_time_limit(0) ; // 긴 처리작업이 있을 때만 쓴다.....

    
    include "config.php";

    $connect=dbconn();

    mysql_select_db($cont_db) ;        


    $Today    = time();//-(3600*7) ; // 새벽 7시 까지 오늘로 간주한다...  
    $WorkDate = date("Ymd",$Today) ; //

    $Now       = strtotime('now');

    $sCurrDay  = date("Ymd",$Now) ; // 현재일...
    $sCurrTime = date("H",strtotime('+1 hours',$Now)) ;   // 현재시간+1시간...

    // 3일전 자료는 지운다..
    $sAgo1Day = date("Ymd",strtotime('-3 day',$Now)) ; // 
    


    $sQuery = "Delete From tmp_daylytotal0         ".
              " Where WorkDate <= '".$sAgo1Day."'  " ; 
    mysql_query($sQuery,$connect) ;        

    $sQuery = "Delete From tmp_daylytotal4         ".
              " Where WorkDate <= '".$sAgo1Day."'  " ; 
    mysql_query($sQuery,$connect) ;        


    $sQuery = "Select * From bas_filmtitle " ;
    $QryFilmtitle = mysql_query($sQuery,$connect) ;
    while ($QbjFilmtitle = mysql_fetch_object($QryFilmtitle))
    {
        $FilmOpen   = $QbjFilmtitle->Open ;
        $FilmCode   = $QbjFilmtitle->Code ;
        
        if   ($FilmCode == '00') // 분리된영화의통합코드
        {
             $FilmCond = " Open = '".$FilmOpen."' " ;
        }
        else
        {
             $FilmCond = "    Open = '".$FilmOpen."' ".
                         "And Film = '".$FilmCode."' " ;
        }

        $FilmTitle  = $FilmOpen.$FilmCode ;   


        $sSingoName = get_singotable($FilmOpen,$FilmCode,$connect) ;  // 신고 테이블 이름..
        $sAccName   = get_acctable($FilmOpen,$FilmCode,$connect) ;  // accumulate 이름..

        $sQuery = "Select Max( SingoDate ) AS MaxSingoDate ".
                  "  From ".$sSingoName."                  ".
                  " Where Open = '".$FilmOpen."'           ".
                  "   And Film = '".$FilmCode."'           " ;
        $QryMaxSingoDate = mysql_query($sQuery,$connect) ;
        if  ($ObjMaxSingoDate = mysql_fetch_object($QryMaxSingoDate))
        {
            $MaxSingoDate = $ObjMaxSingoDate->MaxSingoDate ;
        }

        

        //  오늘 자료가 있는경우..
        if  ($MaxSingoDate >= $WorkDate)
        {            
            // 당일합계
            $ModifyScore  = 0 ; // 수정스코어(당일)
            
            
            $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore     ".
                      "  From bas_modifyscore                          ".
                      " Where ".$FilmCond."                            ".
                      "   And ModifyDate = '".$WorkDate."'             " ;
            $qry_modifyscore  = mysql_query($sQuery,$connect) ;                         
            if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
            {
                $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ; 
            }
            
            
            $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                      "  From ".$sSingoName."                    ".
                      " Where SingoDate  = '".$WorkDate."'       ".
                      "   And ".$FilmCond."                      " ;
            $qrysingo2 = mysql_query($sQuery,$connect) ;
            if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
            {
                $WorkDateNumPersons = $NumPersons_data["SumNumPersons"] + $ModifyScore ;
            }

            if ($sCurrTime == "00")  $sCurrTime = "24" ;
            if ($sCurrTime == "01")  $sCurrTime = "25" ;
            if ($sCurrTime == "02")  $sCurrTime = "26" ;
            if ($sCurrTime == "03")  $sCurrTime = "27" ;
            if ($sCurrTime == "04")  $sCurrTime = "28" ;
            if ($sCurrTime == "05")  $sCurrTime = "29" ;
            if ($sCurrTime == "06")  $sCurrTime = "30" ;
            if ($sCurrTime == "07")  $sCurrTime = "31" ;
            
            $sQuery = "Select Count(*) As CntTimeTotal     ".
                      "  From tmp_timelytotal0             ".
                      " Where WorkDate  = '".$sCurrDay."'  ".
                      "   And WorkTime  = '".$sCurrTime."' ".
                      "   And FilmCode  = '".$FilmTitle."' " ;
            $QRY_CntTimeTotal = mysql_query($sQuery,$connect) ;
            if  ($OBJ_CntTimeTotal = mysql_fetch_object($QRY_CntTimeTotal))
            {
                if  ($OBJ_CntTimeTotal->CntTimeTotal > 0)
                {
                    // 찌꺼기 캐쉬를 다 지운다..
                    $sQuery = "Delete From tmp_timelytotal0        ".
                              " Where WorkDate  = '".$sCurrDay."'  ".
                              "   And WorkTime  = '".$sCurrTime."' ".
                              "   And FilmCode  = '".$FilmTitle."' " ;
                    mysql_query($sQuery,$connect) ;   
                }
            }            
            
            // 캐쉬를 구성한다.
            $sQuery = "Insert Into tmp_timelytotal0     ".
                      "Values                           ".
                      "(                                ".
                      "   '".$sCurrDay."',              ".
                      "   '".$sCurrTime."',             ".
                      "   '".$FilmTitle."',             ".
                      "   '".$WorkDateNumPersons."'     ".
                      ")                                " ;
            mysql_query($sQuery,$connect) ;        
        }
    }
    mysql_close($connect);

?>