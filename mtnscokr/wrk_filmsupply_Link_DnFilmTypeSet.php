<?
    set_time_limit(0) ; // 긴 처리작업이 있을 때만 쓴다.....

    session_start();

    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";        // {[데이터 베이스]} : 환경설정

        $connect = dbconn() ;        // {[데이터 베이스]} : 연결

        mysql_select_db($cont_db) ;  // {[데이터 베이스]} : 디비선택


        $FilmCode = sprintf("%02d",$FilmCode) ;

        $Titlename = get_titlename($FilmOpen,$FilmCode,$connect) ;
        //echo mb_convert_encoding($Titlename,"UTF-8","EUC-KR");

        $FilmType    = get_FilmType($FilmOpen,$FilmCode,$connect) ;
        $FilmTypePrv = get_FilmTypePrv($FilmOpen,$FilmCode,$connect) ;
        $sSingoName  = get_singotable($FilmOpen,$FilmCode,$connect) ;

        $sQuery = "Select Count(*) As CntFilmType        ".
                  "  From ".$FilmTypePrv."               ".
                  " Where WorkDate = '".$WorkDate."'     ".
                  "   And Open     = '".$FilmOpen."'     ".
                  "   And Code     = '".$FilmCode."'     ".
                  "   And Theather = '".$txtTheather."'  ".
                  "   And Room     = '".$txtRoom."'      " ; //echo $sQuery."<BR>" ;
        $QryFilmType = mysql_query($sQuery,$connect) ;
        if  ($ArrFilmType = mysql_fetch_array($QryFilmType))
        {
            $CntFilmType = $ArrFilmType["CntFilmType"] ;
        }
        if  ($CntFilmType == 0)
        {
             $sQuery = "Insert Into ".$FilmTypePrv."  ".
                       " Value                        ".
                       "(                             ".
                       "   '".$WorkDate."',           ".
                       "   '".$FilmOpen."',           ".
                       "   '".$FilmCode."',           ".
                       "   '".$txtTheather."',        ".
                       "   '".$txtRoom."',            ".
                       "   '".$txtValue."'            ".
                       ")                             " ;
             mysql_query($sQuery,$connect) ;//echo $sQuery."<BR>" ;
        }
        $sQuery = "Update ".$FilmTypePrv."               ".
                  "   Set Type = '".$txtValue."'         ".
                  " Where WorkDate >= '".$WorkDate."'    ".
                  "   And Open     = '".$FilmOpen."'     ".
                  "   And Code     = '".$FilmCode."'     ".
                  "   And Theather = '".$txtTheather."'  ".
                  "   And Room     = '".$txtRoom."'      " ; //echo $sQuery."<BR>" ;
        mysql_query($sQuery,$connect) ;


        $sQuery = "Select Count(*) As CntFilmType ".
                  "  From ".$FilmType."           ".
                  " Where Open = '".$FilmOpen."'  ".
                  "   And Code = '".$FilmCode."'  " ; //echo $sQuery."<BR>" ;
        $QryFilmType = mysql_query($sQuery,$connect) ;
        if  ($ArrFilmType = mysql_fetch_array($QryFilmType))
        {
            $CntFilmType = $ArrFilmType["CntFilmType"] ;
        }
        if  ($CntFilmType == 0)
        {
             $sQuery = "Insert Into ".$FilmType."     ".
                       " Value                        ".
                       "(                             ".
                       "   '".$FilmOpen."',           ".
                       "   '".$FilmCode."',           ".
                       "   '".$txtTheather."',        ".
                       "   '".$txtRoom."',            ".
                       "   '".$txtValue."'            ".
                       ")                             " ;
        }
        else
        {
             $sQuery = "Update ".$FilmType."                  ".
                       "   Set Type = '".$txtValue."'         ".
                       " Where Open     = '".$FilmOpen."'     ".
                       "   And Code     = '".$FilmCode."'     ".
                       "   And Theather = '".$txtTheather."'  ".
                       "   And Room     = '".$txtRoom."'      " ;
        }
        mysql_query($sQuery,$connect) ; //echo $sQuery."<BR>" ;

        $sQuery = "Update ".$sSingoName."                   ".
                  "   Set FilmType  = '".$txtValue."'       ".//////////// 9月5日 //////
                  " Where SingoDate = '".$WorkDate."'       ".
                  "   And Theather  = '".$txtTheather."'    ".
                  "   And Room      = '".$txtRoom."'        ".
                  "   And Open      = '".$FilmOpen."'       ".
                  "   And Film      = '".$FilmCode."'       " ;     //echo $sQuery."<BR>" ;
        mysql_query($sQuery,$connect) ;

        mysql_close($connect);
    }
?>