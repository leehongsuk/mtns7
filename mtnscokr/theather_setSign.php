<?
    include "config.php";        // {[������ ���̽�]} : ȯ�漳��
                    
    $connect = dbconn() ;        // {[������ ���̽�]} : ����

    mysql_select_db($cont_db) ;  // {[������ ���̽�]} : �����

    $TheatherName = "" ;

    $Now       = strtotime('now');
    $sCurrTime = date("YmdHis",$Now) ; // ����ð�...
    $sCurrDay  = date("Ymd",$Now) ;    // ��������...

    if  ($sCurrDay != $ObjectDay) 
    {
        $sQuery = "Select * From bas_theather    ".
                  " Where Code = '".$Theather."' " ;  
        $QryTheather = mysql_query($sQuery,$connect) ;
        if  ($ArrTheather = mysql_fetch_array($QryTheather))
        {
            $TheatherName = $ArrTheather["Discript"] ; // ������� ���Ѵ�.

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


    mysql_close($connect);       // {[������ ���̽�]} : ����
?>                                       