<?
    include "config.php";

    $Today    = time();//-(3600*7) ; // ���� 7�� ���� ���÷� �����Ѵ�...  
    $WorkDate = date("Ymd",$Today) ; //

    $Now       = strtotime('now');

    $sCurrDay  = date("Ymd",$Now) ; // ������...
    $sCurrTime = date("H",strtotime('+1 hours',$Now)) ;   // ����ð�+1�ð�...

    // 3���� �ڷ�� �����..
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

        $sSingoName = get_singotable($FilmTileOpen,$FilmTileFilm,$connect) ;  // �Ű� ���̺� �̸�..

        if   ($FilmTileFilm == '00') // �и��ȿ�ȭ�������ڵ�
        {
             $FilmCond = " Open = '".$FilmTileOpen."' " ;
        }
        else
        {
             $FilmCond = "    Open = '".$FilmTileOpen."' ".
                         "And Film = '".$FilmTileFilm."' " ;
        }

        //  ���� �ڷᰡ �ִ°��..
        if  ($OBJ_MaxSingoDate->MaxSingoDate >= $WorkDate)
        {            
            // �����հ�
            $ModifyScore  = 0 ; // �������ھ�(����)
            if  ($filmsupplyCode=="20003") // ���� �������� - bas_modifyscore ���� ....
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
                    // ��� ĳ���� �� �����..
                    $sQuery = "Delete From tmp_timelytotal0        ".
                              " Where WorkDate  = '".$sCurrDay."'  ".
                              "   And WorkTime  = '".$sCurrTime."' ".
                              "   And FilmCode  = '".$FilmTitle."' " ;
                    mysql_query($sQuery,$connect) ;   
                }
            }            
            
            // ĳ���� �����Ѵ�.
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