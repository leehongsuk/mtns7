<?
    header("Content-Type: text/html; charset=euc-kr");

    set_time_limit(0) ; // 긴 처리작업이 있을 때만 쓴다.....

    include "config.php";

    $connect = dbconn();

    mysql_select_db($cont_db) ;


    if  ($_Type == "FilmtitleUpdate")
    {
        $mtnsName  = iconv("utf-8","euc-kr",$_mtnsName) ;
        $lotteName = iconv("utf-8","euc-kr",$_lotteName) ;

        $sQuery = "Select * From xls_lotte_filmtitle  \n".
                  " Where mtnsName = '$mtnsName'      \n" ;
        $QryXlsFilmtitle = mysql_query($sQuery,$connect) ;
        if  ($ArrXlsFilmtitle = mysql_fetch_array($QryXlsFilmtitle))
        {
            $sQuery = "Update xls_lotte_filmtitle       \n".
                      "   Set lotteName = '$lotteName'  \n".
                      " Where mtnsName = '$mtnsName'    \n" ;
            mysql_query($sQuery,$connect) ;
        }
        else
        {
            $sQuery = "Insert Into xls_lotte_filmtitle  \n".
                      " Value                           \n".
                      "(                                \n".
                      "       '$lotteName',             \n".
                      "       '$mtnsName'               \n".
                      ")                                \n" ;
            mysql_query($sQuery,$connect) ;
        }
    }

    if  ($_Type == "FilmtitleDelete")
    {
        $mtnsName  = iconv("utf-8","euc-kr",$_mtnsName) ;

        $sQuery = "Select * From xls_lotte_filmtitle  \n".
                  " Where mtnsName = '$mtnsName'      \n" ;
        $QryXlsFilmtitle = mysql_query($sQuery,$connect) ;
        if  ($ArrXlsFilmtitle = mysql_fetch_array($QryXlsFilmtitle))
        {
            $sQuery = "Delete From xls_lotte_filmtitle  \n".
                      " Where mtnsName = '$mtnsName'    \n" ;
            mysql_query($sQuery,$connect) ;
        }
    }

    if  ($_Type == "TheatherUpdate")
    {
        $mtnsName  = iconv("utf-8","euc-kr",$_mtnsName) ;
        $lotteName = iconv("utf-8","euc-kr",$_lotteName) ;

        $sQuery = "Select * From xls_lotte_theather   \n".
                  " Where mtnsName = '$mtnsName'      \n" ;
        $QryXlsFilmtitle = mysql_query($sQuery,$connect) ;
        if  ($ArrXlsFilmtitle = mysql_fetch_array($QryXlsFilmtitle))
        {
            $sQuery = "Update xls_lotte_theather        \n".
                      "   Set lotteName = '$lotteName'  \n".
                      " Where mtnsName = '$mtnsName'    \n" ;
            mysql_query($sQuery,$connect) ;
        }
        else
        {
            $sQuery = "Insert Into xls_lotte_theather   \n".
                      " Value                           \n".
                      "(                                \n".
                      "       '$lotteName',             \n".
                      "       '$mtnsName'               \n".
                      ")                                \n" ;
            mysql_query($sQuery,$connect) ;
        }
    }

    if  ($_Type == "TheatherDelete")
    {
        $mtnsName  = iconv("utf-8","euc-kr",$_mtnsName) ;

        $sQuery = "Select * From xls_lotte_theather   \n".
                  " Where mtnsName = '$mtnsName'      \n" ;
        $QryXlsFilmtitle = mysql_query($sQuery,$connect) ;
        if  ($ArrXlsFilmtitle = mysql_fetch_array($QryXlsFilmtitle))
        {
            $sQuery = "Delete From xls_lotte_theather   \n".
                      " Where mtnsName = '$mtnsName'    \n" ;
            mysql_query($sQuery,$connect) ;
        }
    }

    mysql_close($connect);
?>

