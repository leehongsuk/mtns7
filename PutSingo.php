<html>
<head></head>
<body>
<?
    function cutNum($Name)
    {
        $leng = strlen($Name) ;
        $cutNum = $Name ;

        for ($i=($leng-1) ; $i >= 0 ; $i--)
        {
            if   ((substr($Name,$i,1) >= "0") &&  (substr($Name,$i,1) <= "9"))
            {
                $cutNum = substr($Name,0,$i-1) ;
            }
            else
            {
                break ;
            }
        }

       return $cutNum;
    }


    set_time_limit(0) ; // �� ó���۾��� ���� ���� ����.....

    /*
    echo "�Ű�����".$SingoDate     ;

    echo "�����".$TheatherName  ;
    echo "�ʸ���".$FilmName      ;
    echo "��".$Room          ;
    echo "�¼�".$Seat          ;
    echo "�ݾ�".$UnitPrice     ;
    echo "���ھ�".$Score         ;
    echo "����".$Content ;
    */

    include "config.php";

    $connect=dbconn();

    //$WorkDate = strtotime(substr($SingoDate,0,4)."-".substr($SingoDate,4,2)."-".substr($SingoDate,6,2)."") ;
    $WorkDate = $SingoDate ;

    $Today = time()-(3600*7) ; // ���� 7�� ���� ���÷� �����Ѵ�...



    if  ($Gubun == "Php") //  ����/û���� CGV
    {
        mysql_select_db($cont_db) ;

        $MdfLmtDat = 0 ;

        $sQuery = "Select * From MofidyLimitDate " ;
        $QryMdfLmtDat = mysql_query($sQuery,$connect) ;
        if  ($ArrMdfLmtDat = mysql_fetch_array($QryMdfLmtDat) )
        {
            $MdfLmtDat = $ArrMdfLmtDat["Value"] ;
        }

        $Ago2Date = date("Ymd",$Today-((3600*24)*($MdfLmtDat))) ;


        if  ($Content)
        {
            $sQuery = "Select * From bas_filmtitle   ".
                      " Where Name = '".$FilmName."' " ;
            $QryFilmtitle = mysql_query($sQuery,$connect) ;
            if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
            {
                $Open = $ArrFilmtitle["Open"] ;
                $Film = $ArrFilmtitle["Code"] ;

                $sSingoName = get_singotable($Open,$Film,$connect) ;  // �Ű� ���̺� �̸�..
                $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate �̸�..
                $sDgrName   = get_degree($Open,$Film,$connect) ;
                $sDgrpName  = get_degreepriv($Open,$Film,$connect) ;
                $sShowroomorder = get_showroomorder($Open,$Film,$connect) ;

                $Items1 = explode("|", $Content); // "|" �� �Ľ�,,,
                foreach ($Items1 as $Item1)
                {
                    if  ($Item1 != "")
                    {
                        $Items2 = explode("~", $Item1); // "~" �� �Ľ�,,,

                        //echo $Items2[0]."~".$Items2[1]."~".$Items2[2]."~".$Items2[3]."~".$Items2[4]."<br>" ;

                        if  ($Items2[0]!="") {  $TheatherName = cutNum($Items2[0]) ;          }
                        if  ($Items2[1]!="") {  $Room2        = sprintf("%02d",$Items2[1]) ;  }
                        if  ($Items2[2]!="") {  $Seat         = $Items2[2] ;                  }
                        $UnitPrice = $Items2[3] ;
                        $Score     = $Items2[4] ;

//eq($Item1." [  ".$TheatherName." ".$UnitPrice.":".$Score." ] \n");
                        if  ($UnitPrice==0)
                        {
                            echo "0 ����� �����ϴ�. (".$TheatherName.")" ;
                        }
                        if  ($UnitPrice<4000)
                        {
                            echo "4000���� ����� �����ϴ�. (".$TheatherName.")" ;
                        }

                        $sQuery = "Select Code                                                                ".
                                  "      ,Location                                                            ".
                                  "      ,IF( '".$SingoDate."' >=  '20160523', GikumRate,  1.03 ) GikumRate   ".
                                  "  From bas_theather                                                        ".
                                  " Where Discript = '".$TheatherName."'                                      " ;
                        $QryTheather = mysql_query($sQuery,$connect) ;
                        if  ($ArrTheather = mysql_fetch_array($QryTheather))
                        {
                            $Theather  = $ArrTheather["Code"] ;
                            $Location  = $ArrTheather["Location"] ;
							$GikumRate = $ArrTheather["GikumRate"] ;

                            $sQuery = "Delete From ".$sSingoName."        ".
                                      "Where SingoDate='".$SingoDate."'   ".
                                      "  And Silmooja='".$Silmooja."'     ".
                                      "  And Location='".$Location."'     ".
                                      "  And Theather='".$Theather."'     ".
                                      "  And Room='".$Room2."'            ".
                                      "  And Open='".$Open."'             ".
                                      "  And Film='".$Film."'             ".
                                      "  And ShowDgree='01'               ".
                                      "  And UnitPrice='".$UnitPrice."'   " ;
                            mysql_query($sQuery,$connect) ;

                            $sQuery = "Select * From ".$sShowroomorder."      ".
                                      " Where Theather   = '".$Theather."'    ".
                                      "   And Room       = '".$Room2."'       " ;
                            $QryShowroomorder = mysql_query($sQuery,$connect) ;
                            if  ($ArrShowroomorder = mysql_fetch_array($QryShowroomorder))
                            {
                                $RoomOrder = $ArrShowroomorder["Seq"] ;
                            }
                            else
                            {
                                $RoomOrder = -1 ;
                            }

                            if  ($Score != "")
                            {
                                $sQuery = "Insert Into ".$sSingoName."  ".
                                          "Values                       ".
                                          "(                            ".
                                          "  '".$SingoDate."100000',    ".
                                          "  '".$SingoDate."',          ".
                                          "  '".$Silmooja."',           ".
                                          "  '".$Location."',           ".
                                          "  '".$Theather."',           ".
                                          "  '".$Room2."',              ".
                                          "  '".$Open."',               ".
                                          "  '".$Film."',               ".
                                          "  '".$FilmType."',           ". //////////// 9��5�� //////
                                          "  '01',                      ".
                                          "  '".$UnitPrice."',          ".
                                          "  '".$Score."',              ".
                                          "  '".$UnitPrice * $Score."', ".
                                          "  '".get_GikumAount2($UnitPrice,$GikumRate,$Score)."', ".
                                          "  '',                        ".
                                          "  '".$RoomOrder."'           ".
                                          ")                            " ; //eq($sQuery);
                                mysql_query($sQuery,$connect) ;
                            }
                            else
                            {

                            }
                        }
                        else
                        {
                            echo "�ش��ϴ� �����ڵ尡 �����ϴ�. : (".$TheatherName.")<br>\r\n" ;
                        }
                    }
                }
            }
            else
            {
                echo "�ش��ϴ� ��ȭ�ڵ尡 �����ϴ�. : (".$FilmName.")<br>\r\n" ;
            }
        }
    }

////////////////////////////////////////////////////////////////////
/*
����^1^310
^2:0910,3:1220,4:1520,5:1815,6:2110,7:0000
^4000.3:1,5:1,6:4
~6000.5:4
~6500.5:8,6:3
~7000.2:1,3:50,4:46,5:48,6:36,7:6
~8000.2:10,3:11,4:22,5:34,6:61,7:27
|
��ǳ^1^354
^2:1155,3:1450,4:1745,5:2040,6:2335
^4000.5:1
~5000.4:2
~5500.2:1
~6500.2:3,3:41,4:39,5:4
~7000.2:44,3:104,4:63,5:61,6:28
~8000.2:3,3:21,4:17,5:70,6:30
|
���� �л�^4^284
^2:1100,3:1405,4:1705,5:2005
^4000.3:2,5:1
~5000.5:6
~5500.5:2
~6000.2:23,3:145,4:65,5:68
~7000.2:29,3:34,4:47,5:125
*/
    if  ($Gubun == "Php2") //  ���� �����ӽ�
    {
        mysql_select_db($cont_db) ;

        $MdfLmtDat = 0 ;

        $sQuery = "Select * From MofidyLimitDate " ;
        $QryMdfLmtDat = mysql_query($sQuery,$connect) ;
        if  ($ArrMdfLmtDat = mysql_fetch_array($QryMdfLmtDat) )
        {
            $MdfLmtDat = $ArrMdfLmtDat["Value"] ;
        }

        $Ago2Date = date("Ymd",$Today-((3600*24)*($MdfLmtDat))) ;


        if  ($Content)
        {
            $sQuery = "Select * From bas_filmtitle   ".
                      " Where Name = '".$FilmName."' " ;
            $QryFilmtitle = mysql_query($sQuery,$connect) ;
            if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
            {
                $Open = $ArrFilmtitle["Open"] ;
                $Film = $ArrFilmtitle["Code"] ;

                $sSingoName     = get_singotable($Open,$Film,$connect) ;  // �Ű� ���̺� �̸�..
                $sAccName       = get_acctable($Open,$Film,$connect) ;    // accumulate �̸�..
                $sDgrName       = get_degree($Open,$Film,$connect) ;
                $sDgrpName      = get_degreepriv($Open,$Film,$connect) ;
                $sShowroomorder = get_showroomorder($Open,$Film,$connect) ;

                $Items1 = explode("|", $Content); // "|" �� �Ľ�,,,
                foreach ($Items1 as $Item1)
                {
                    if  ($Item1 != "")
                    {
                        $Items2 = explode("^", $Item1) ; // "^" �� �Ľ�,,,

                        //echo $Items2[0]."^".$Items2[1]."^".$Items2[2]."^".$Items2[3]."^".$Items2[4]."<br>" ; //����, ��, �¼�, �ð�ǥ, �ݾ�/ȸ���� ���ھ�

                        if  ($Items2[0]!="") {  $TheatherName = cutNum($Items2[0]) ;          }
                        if  ($Items2[1]!="") {  $Room2        = sprintf("%02d",$Items2[1]) ;  }
                        if  ($Items2[2]!="") {  $Seat         = $Items2[2] ;                  }
                        $TimeTables = $Items2[3] ; //echo $TimeTables ."\n" ;
                        $UnitPrices = $Items2[4] ;

                        /*
                        if  ($UnitPrice==0)
                        {
                            echo "0 ����� �����ϴ�. (".$TheatherName.")" ;
                        }
                        if  ($UnitPrice<4000)
                        {
                            echo "4000���� ����� �����ϴ�. (".$TheatherName.")" ;
                        }
                        */
                        $sQuery = "Select Code                                                               ".
                                  "      ,Location                                                           ".
                                  "      ,IF( '".$WorkDate."' >=  '20160523', GikumRate,  1.03 ) GikumRate   ".
                                  "  From bas_theather                                                       ".
                                  " Where Discript = '".$TheatherName."'                                     " ;   //echo $sQuery ;
                        $QryTheather = mysql_query($sQuery,$connect) ;
                        if  ($ArrTheather = mysql_fetch_array($QryTheather))
                        {
                            $Theather  = $ArrTheather["Code"] ;
                            $Location  = $ArrTheather["Location"] ;
							$GikumRate = $ArrTheather["GikumRate"] ;

                            $sQuery = "Delete From ".$sDgrpName."             ".
                                      " Where Silmooja = '".$Silmooja."'      ".
                                      "   And WorkDate = '".$SingoDate."'     ".
                                      "   And Open     = '".$Open."'          ".
                                      "   And Film     = '".$Film."'          ".
                                      "   And Theather = '".$Theather."'      ".
                                      "   And Room     = '".$Room2."'         " ; //echo $sQuery."\n" ;
                            mysql_query($sQuery,$connect) ;

                            $Items7 = explode(",", $TimeTables); // "," �� �Ľ�,,,

                            foreach ($Items7 as $Item7)
                            {
                                $Items8 = explode(":", $Item7); // ":" �� �Ľ�,,,

                                $Degree = sprintf("%02d",$Items8[0]) ;
                                $Time   = $Items8[1] ;

                                //echo "[".$Degree.":".$Time."]" ;

                                $sQuery = "Insert Into ".$sDgrpName."  ".
                                          "Values                      ".
                                          "(                           ".
                                          "    '".$Silmooja."',        ".
                                          "    '".$SingoDate."',       ".
                                          "    '".$Open."',            ".
                                          "    '".$Film."',            ".
                                          "    '".$FilmType."',            ". //////////// 9��5�� //////
                                          "    '".$Theather."',        ".
                                          "    '".$Room2."',           ".
                                          "    '".$Degree."',          ".
                                          "    '".$Time."',            ".
                                          "    '".$degreeDiscript."'   ".
                                          ")                           " ; //echo $sQuery."\n" ;
                                mysql_query($sQuery,$connect) ;
                            }


                            $sQuery = "Delete From ".$sSingoName."        ".
                                      "Where SingoDate='".$SingoDate."'   ".
                                      "  And Silmooja='".$Silmooja."'     ".
                                      "  And Location='".$Location."'     ".
                                      "  And Theather='".$Theather."'     ".
                                      "  And Room='".$Room2."'            ".
                                      "  And Open='".$Open."'             ".
                                      "  And Film='".$Film."'             " ;   //echo $sQuery ;
                                      //"  And ShowDgree='01'               ".
                                      //"  And UnitPrice='".$UnitPrice."'   "
                            mysql_query($sQuery,$connect) ;

                            $sQuery = "Select * From ".$sShowroomorder."      ".
                                      " Where Theather   = '".$Theather."'    ".
                                      "   And Room       = '".$Room2."'       " ;   //echo $sQuery ;
                            $QryShowroomorder = mysql_query($sQuery,$connect) ;
                            if  ($ArrShowroomorder = mysql_fetch_array($QryShowroomorder))
                            {
                                $RoomOrder = $ArrShowroomorder["Seq"] ;
                            }
                            else
                            {
                                $RoomOrder = -1 ;
                            }


                            $Items3 = explode("~", $UnitPrices); // "~" �� �Ľ�,,,

                            //echo $Items3[0]."~".$Items3[1]."<br>" ;   �ݾ�/ȸ���� ���ھ�,   ....

                            foreach ($Items3 as $Item3)
                            {
                                $Items4 = explode(".", $Item3); // "." �� �Ľ�,,,

                                        //echo $Items4[0].".".$Items4[1]."<br>" ; // �ݾ�, ȸ���� ���ھ�

                                $UnitPrice = $Items4[0] ;
                                $Scores    = $Items4[1] ;

                                $Items5 = explode(",", $Scores); // "," �� �Ľ�,,,

                                //echo $Items5[0].".".$Items5[1]."<br>" ; ȸ�� ���ھ�, .......

                                foreach ($Items5 as $Item5)
                                {
                                     $Items6 = explode(":", $Item5); // ":" �� �Ľ�,,,

                                     $Degree = sprintf("%02d",$Items6[0]) ;
                                     $Score  = $Items6[1] ;

                                     //echo $Items6[0].":".$Items6[1]."<br>" ; ȸ��, ���ھ�


                                     $sQuery = "Insert Into ".$sSingoName."                 ".
                                               "Values                                      ".
                                               "(                                           ".
                                               "  '".$SingoDate.date("His")."',             ".
                                               "  '".$SingoDate."',                         ".
                                               "  '".$Silmooja."',                          ".
                                               "  '".$Location."',                          ".
                                               "  '".$Theather."',                          ".
                                               "  '".$Room2."',                             ".
                                               "  '".$Open."',                              ".
                                               "  '".$Film."',                              ".
                                               "  '".$FilmType."',                          ". //////////// 9��5�� //////
                                               "  '".$Degree."',                            ".
                                               "  '".$UnitPrice."',                         ".
                                               "  '".$Score."',                             ".
                                               "  '".$UnitPrice * $Score."',                ".
                                               "  '".get_GikumAount2($UnitPrice,$GikumRate,$Score)."',  ".
                                               "  '',                                       ".
                                               "  '".$RoomOrder."'                          ".
                                               ")                                           " ;  // echo $sQuery."<br>" ;
                                     mysql_query($sQuery,$connect) ;
                                }
                            }
                        }
                        else
                        {
                            echo "�ش��ϴ� �����ڵ尡 �����ϴ�. : (".$TheatherName.")<br>\r\n" ;
                        }
                    }
                }
            }
            else
            {
                echo "�ش��ϴ� ��ȭ�ڵ尡 �����ϴ�. : (".$FilmName.")<br>\r\n" ;
            }
        }
    }

    mysql_close($connect);
?>
</body>
</html>