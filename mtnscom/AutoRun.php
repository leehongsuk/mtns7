<?
    include "config.php";

    $Today    = time();//-(3600*7) ; // 새벽 7시 까지 오늘로 간주한다...  
    $WorkDate = date("Ymd",$Today) ; //

    $Now       = strtotime('now');

    $sCurrDay  = date("Ymd",$Now) ; // 현재일...
    $sCurrTime = date("H",strtotime('+1 hours',$Now)) ;   // 현재시간+1시간...

    // 3일전 자료는 지운다..
    $sAgo1Day = date("Ymd",strtotime('-3 day',$Now)) ; // 

    
    $connect=dbconn();

    mysql_select_db($cont_db) ;

    
    



    $sQuery = "Delete From tmp_daylytotal0         ".
              " Where WorkDate <= '".$sAgo1Day."'  " ; 
    mysql_query($sQuery,$connect) ;        

    $sQuery = "Delete From tmp_daylytotal4         ".
              " Where WorkDate <= '".$sAgo1Day."'  " ; 
    mysql_query($sQuery,$connect) ;        


    $sQuery = "Select Open, Film, FilmSupply,          ".
              "       Max( SingoDate ) AS MaxSingoDate ".
              "  From wrk_singo                        ".
              " Group By Open, Film                    " ;
    $QRY_MaxSingoDate = mysql_query($sQuery,$connect) ;
    while ($OBJ_MaxSingoDate = mysql_fetch_object($QRY_MaxSingoDate))
    {
        $FilmTileOpen   = $OBJ_MaxSingoDate->Open ;
        $FilmTileFilm   = $OBJ_MaxSingoDate->Film ;
        $filmsupplyCode = $OBJ_MaxSingoDate->FilmSupply ;
        $FilmTitle      = $FilmTileOpen.$FilmTileFilm ;

        $sSingoName = get_singotable($FilmTileOpen,$FilmTileFilm,$connect) ;  // 신고 테이블 이름..

        if   ($FilmTileFilm == '00') // 분리된영화의통합코드
        {
             $FilmCond = " Open = '".$FilmTileOpen."' " ;
        }
        else
        {
             $FilmCond = "    Open = '".$FilmTileOpen."' ".
                         "And Film = '".$FilmTileFilm."' " ;
        }

        //  오늘 자료가 있는경우..
        if  ($OBJ_MaxSingoDate->MaxSingoDate >= $WorkDate)
        {            
            // 당일합계
            $ModifyScore  = 0 ; // 수정스코어(당일)
            if  ($filmsupplyCode=="20003") // 예상 수정정보 - bas_modifyscore 적용 ....
            {
                $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore     ".
                          "  From bas_modifyscore                          ".
                          " Where ".$FilmCond."                            ".
                          "   And ModifyDate = '".$WorkDate."'             " ;
                $qry_modifyscore  = mysql_query($sQuery,$connect) ;                         
                if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
                {
                    $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ; 
                }
            }
            
            $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                      "  From ".$sSingoName."                    ".
                      " Where SingoDate  = '".$WorkDate."'       ".
                      "   And ".$FilmCond."                      ".
                      "   And FilmSupply = '".$filmsupplyCode."' " ;
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