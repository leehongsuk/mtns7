<?
    if  ($test=='1')
    {
    $filename = "/usr/local/apache2/htdocs/debug/qry.txt" ;
        //if  (!file_exists($filename)) unlink($filename);
        $fd = fopen ($filename, "a");

        fwrite($fd, "aaa"."\r\n");

    fclose ($fd);
    echo "ss";
    }

    set_time_limit(0) ; // �� ó���۾��� ���� ���� ����.....

    $Today = time()-(3600*7) ; // ���� 7�� ���� ���÷� �����Ѵ�...

    if (!$WorkDate)
    {
       $WorkDate = date("Ymd",$Today) ;
    }

    if (!$SingoDate)
    {
        $SingoDate = $WorkDate ;  // SingoDate �� �����ϰ� ������츦 ����ؼ�
    }

    // �Ϸ� ������ ���Ѵ�.
    $AgoDate = date("Ymd",strtotime("-1 day",strtotime(substr($WorkDate,0,4)."-".substr($WorkDate,4,2)."-".substr($WorkDate,6,2).""))) ;

    include "config.php";

    $connect = dbconn();

    /*
    echo "�Ű�����".$SingoTime ;
    echo "����ȣ".$PhonNo ;
    echo "�����ڵ�".$Theather ;
    echo "�ʸ��ڵ�".$Film2 ;
    echo "��".$Room ;
    echo "ȸ��".$Degree ;
    echo "����".$Content ;
    */
    if  ($Action=="99") //
    {
        mysql_select_db($cont_db) ;

        $sQuery = "Select * From bas_theather     ".
                  " Where Code = '".$Theather."'  " ;
        $QryTheather = mysql_query($sQuery,$connect) ;
        if  ($ArrTheather = mysql_fetch_array($QryTheather))
        {
            $sQuery = "Update bas_theather            ".
                      "   Set UserId = '".$UserID."', ".
                      "       UserPw = '".$UserPW."'  ".
                      " Where Code = '".$Theather."'  " ;
            mysql_query($sQuery,$connect) ;
        }

    }

    if  ($Action=="77") // �ð�ǥ ���1
    {
        mysql_select_db($cont_db) ;

        if  ($Content) // ȸ������������ ������..
        {
            // ��ȭ�ڵ�� ��ȭ�� ã�´�.
            $sQuery = "Select * From bas_filmtitle ".
                      " Where Code = '".$Film2."'  " ;
            $QryFilmtitle = mysql_query($sQuery,$connect) ;
            if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
            {
                $Open = $ArrFilmtitle["Open"] ; // ��ȭ �ڵ�
                $Film = $ArrFilmtitle["Code"] ; //

                $sSingoName = get_singotable($Open,$Film,$connect) ;  // �Ű� ���̺� �̸�..
                $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate �̸�..
                $sDgrName   = get_degree($Open,$Film,$connect) ;
                $sDgrpName  = get_degreepriv($Open,$Film,$connect) ;

                $sQuery = "Select * From bas_showroom        ".
                          " Where Theather = '".$Theather."' ".
                          "   And Room     = '".$Room."'     " ;
                $QryRoom = mysql_query($sQuery,$connect) ;
                if  ($ArrRoom = mysql_fetch_array($QryRoom))
                {
                    // �󿵰� �¼��� ����..
                    $sQuery = "Update bas_showroom               ".
                              "   Set Seat     = '".$Seat."'     ".
                              " Where Theather = '".$Theather."' ".
                              "   And Room     = '".$Room."'     " ;
                    mysql_query($sQuery,$connect) ;

                    // ȸ�� �ڷᰡ�ִٸ� �������,
                    $sQuery = "Delete From ".$sDgrpName."         ".
                              " Where Silmooja = '".$Silmooja."'  ".
                              "   And WorkDate = '".$SingoDate."' ".
                              "   And Open     = '".$Open."'      ".
                              "   And Film     = '".$Film."'      ".
                              "   And Theather = '".$Theather."'  ".
                              "   And Room     = '".$Room."'      " ;
                    mysql_query($sQuery,$connect) ;

                    // ȸ�� �ڷᰡ�ִٸ� �������,
                    $sQuery = "Delete From ".$sDgrName."         ".
                              " Where Silmooja = '".$Silmooja."' ".
                              "   And Theather = '".$Theather."' ".
                              "   And Room     = '".$Room."'     ".
                              "   And Open     = '".$Open."'     ".
                              "   And Film     = '".$Film."'     " ;
                    mysql_query($sQuery,$connect) ;

                    $Items1 = explode("|", $Content); // "|" �� �Ľ�,,,

                    foreach ($Items1 as $Item1)
                    {
                        if   ($Item1=='')  break ;

                        //echo $Items2[0]."~".$Items2[1]."~".$Items2[2]."~".$Items2[3]."~".$Items2[4]."<br>" ;

                        $Items2 = explode("~", $Item1); // "~" �� �Ľ�,,, ȸ��~�ð�ǥ


                        $Degree = $Items2[0] ; // ȸ�� ..
                        $Time   = $Items2[1] ; // �ð�ǥ ..


                        if  ((($Degree >= 1 ) && ($Degree <= 10 )) || ($Degree == 99))
                        {
                            $Degree2 = sprintf("%02d",$Degree) ;

                            $sQuery = "Insert Into ".$sDgrName." ".
                                      "Values                    ".
                                      "(                         ".
                                      "    '".$Theather."',      ".
                                      "    '".$Room."',          ".
                                      "    '".$Degree2."',       ".
                                      "    '".$Silmooja."',      ".
                                      "    '".$Open."',          ".
                                      "    '".$Film."',          ".
                                      "    '".$Time."',          ".
                                      "    '".$Discript."'       ".
                                      ")                         " ;
                            mysql_query($sQuery,$connect) ;

                            $sQuery = "Insert Into ".$sDgrpName."  ".
                                      "Values                      ".
                                      "(                           ".
                                      "  '".$Silmooja."',          ".
                                      "  '".$SingoDate."',         ".
                                      "  '".$Open."',              ".
                                      "  '".$Film."',              ".
                                      "  '".$Theather."',          ".
                                      "  '".$Room."',              ".
                                      "  '".$Degree2."',           ".
                                      "  '".$Time."',              ".
                                      "  '".$Discript."'           ".
                                      ")                           " ;
                            mysql_query($sQuery,$connect) ;
                        }
                    }
                }
                else
                {
                    echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"�ش��ϴ� �����ڵ尡 �����ϴ�. : (".$Theather.")" ;
                }
            }
            else
            {
                echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"�ش��ϴ� ��ȭ�ڵ尡 �����ϴ�. : (".$Film2.")" ;
            }
        }
        else
        {
            echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"ȸ������������ �����ϴ�. : (".$Theather.")" ;
        }
    }

    if  ($Action=="78") // �ð�ǥ ���2
    {
        mysql_select_db($cont_db) ;

        if  ($Content) // ȸ������������ ������..
        {
            // ��ȭ�ڵ�� ��ȭ�� ã�´�.
            $sQuery = "Select * From bas_filmtitle ".
                      " Where Code = '".$Film2."'  " ;
            $QryFilmtitle = mysql_query($sQuery,$connect) ;
            if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
            {
                $Open = $ArrFilmtitle["Open"] ; // ��ȭ �ڵ�
                $Film = $ArrFilmtitle["Code"] ; //

                $sSingoName = get_singotable($Open,$Film,$connect) ;  // �Ű� ���̺� �̸�..
                $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate �̸�..
                $sDgrName   = get_degree($Open,$Film,$connect) ;
                $sDgrpName  = get_degreepriv($Open,$Film,$connect) ;

                $sQuery = "Select * From bas_showroom        ".
                          " Where Theather = '".$Theather."' ".
                          "   And Room     = '".$Room."'     " ;
                $QryRoom = mysql_query($sQuery,$connect) ;
                if  ($ArrRoom = mysql_fetch_array($QryRoom))
                {
                    // �󿵰� �¼��� ����..
                    $sQuery = "Update bas_showroom               ".
                              "   Set Seat = '".$Seat."'         ".
                              " Where Theather = '".$Theather."' ".
                              "   And Room     = '".$Room."'     " ;
                    mysql_query($sQuery,$connect) ;


                    $Items1 = explode("|", $Content); // "|" �� �Ľ�,,,

                    foreach ($Items1 as $Item1)
                    {
                        if   ($Item1=='')  break ;

                        //echo $Items2[0]."~".$Items2[1]."~".$Items2[2]."~".$Items2[3]."~".$Items2[4]."<br>" ;

                        $Items2 = explode("~", $Item1); // "~" �� �Ľ�,,, ȸ��~�ð�ǥ


                        $Degree = $Items2[0] ; // ȸ�� ..
                        $Time   = $Items2[1] ; // �ð�ǥ ..


                        if  ((($Degree >= 1 ) && ($Degree <= 10 )) || ($Degree == 99))
                        {
                            $Degree2 = sprintf("%02d",$Degree) ;

                            $sQuery = "Select * From ".$sDgrpName."       ".
                                      " Where Silmooja = '".$Silmooja."'  ".
                                      "   And WorkDate = '".$SingoDate."' ".
                                      "   And Open     = '".$Open."'      ".
                                      "   And Film     = '".$Film."'      ".
                                      "   And Theather = '".$Theather."'  ".
                                      "   And Room     = '".$Room."'      ".
                                      "   And Degree   = '".$Degree2."'   " ;
                            $QryDegreepriv = mysql_query($sQuery,$connect) ;
                            if  ($ArrDegreepriv = mysql_fetch_array($QryDegreepriv) )
                            {
                                $sQuery = "Update ".$sDgrpName."              ".
                                          "   Set Time     = '".$Time."'      ".
                                          " Where Silmooja = '".$Silmooja."'  ".
                                          "   And WorkDate = '".$SingoDate."' ".
                                          "   And Open     = '".$Open."'      ".
                                          "   And Film     = '".$Film."'      ".
                                          "   And Theather = '".$Theather."'  ".
                                          "   And Room     = '".$Room."'      ".
                                          "   And Degree   = '".$Degree2."'   " ;
                                mysql_query($sQuery,$connect) ;
                            }
                            else
                            {
                                $sQuery = "Insert Into ".$sDgrpName."  ".
                                          "Values                      ".
                                          "(                           ".
                                          "  '".$Silmooja."',          ".
                                          "  '".$SingoDate."',         ".
                                          "  '".$Open."',              ".
                                          "  '".$Film."',              ".
                                          "  '".$Theather."',          ".
                                          "  '".$Room."',              ".
                                          "  '".$Degree2."',           ".
                                          "  '".$Time."',              ".
                                          "  '".$Discript."'           ".
                                          ")                           " ;
                                mysql_query($sQuery,$connect) ;
                            }


                            $sQuery = "Select * From ".$sDgrName."        ".
                                      " Where Silmooja = '".$Silmooja."'  ".
                                      "   And Open     = '".$Open."'      ".
                                      "   And Film     = '".$Film."'      ".
                                      "   And Theather = '".$Theather."'  ".
                                      "   And Room     = '".$Room."'      ".
                                      "   And Degree   = '".$Degree2."'   " ;
                            $QryDegree = mysql_query($sQuery,$connect) ;
                            if  ($ArrDegree = mysql_fetch_array($QryDegree) )
                            {
                                $sQuery = "Update ".$sDgrName."               ".
                                          "   Set Time     = '".$Time."'      ".
                                          " Where Silmooja = '".$Silmooja."'  ".
                                          "   And Open     = '".$Open."'      ".
                                          "   And Film     = '".$Film."'      ".
                                          "   And Theather = '".$Theather."'  ".
                                          "   And Room     = '".$Room."'      ".
                                          "   And Degree   = '".$Degree2."'   " ;
                                mysql_query($sQuery,$connect) ;
                            }
                            else
                            {
                                $sQuery = "Insert Into ".$sDgrName."  ".
                                          "Values                     ".
                                          "(                          ".
                                          "    '".$Theather."',       ".
                                          "    '".$Room."',           ".
                                          "    '".$Degree2."',        ".
                                          "    '".$Silmooja."',       ".
                                          "    '".$Open."',           ".
                                          "    '".$Film."',           ".
                                          "    '".$Time."',           ".
                                          "    '".$Discript."'        ".
                                          ")                          " ;
                                mysql_query($sQuery,$connect) ;
                            }
                        }
                    }
                }
                else
                {
                    echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"�ش��ϴ� �����ڵ尡 �����ϴ�. : (".$Theather.")" ;
                }
            }
            else
            {
                echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"�ش��ϴ� ��ȭ�ڵ尡 �����ϴ�. : (".$Film2.")" ;
            }
        }
        else
        {
            echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"ȸ������������ �����ϴ�. : (".$Theather.")" ;
        }
    }

    if  ($Action=="98")   // ���� ���ھ��...
    {


        mysql_select_db($cont_db) ; // 1�� ���

        $Room2 = sprintf("%02d",$Room) ; // 2�ڸ� ���ڵ�.. // �̰Ͷ����� �ڷᰡ �ȵ�� ����??

        if  ($Content)
        {
            // ��ȭ�ڵ�� ��ȭ�� ã�´�.
            $sQuery = "Select * From bas_filmtitle  ".
                      " Where Code = '".$Film2."'   " ;
            $QryFilmtitle = mysql_query($sQuery,$connect) ;
            if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
            {
                $Open = $ArrFilmtitle["Open"] ; // ��ȭ�ڵ� ..
                $Film = $ArrFilmtitle["Code"] ; //

                $sSingoName = get_singotable($Open,$Film,$connect) ;  // �Ű� ���̺� �̸�..
                $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate �̸�..
                $sDgrName   = get_degree($Open,$Film,$connect) ;
                $sDgrpName  = get_degreepriv($Open,$Film,$connect) ;
                $sShowroomorder = get_showroomorder($Open,$Film,$connect) ;


                // �����ڵ�� ������ ã�´�.
                $sQuery = "Select * From bas_showroom        ".
                          " Where Theather = '".$Theather."' ".
                          "   And Room     = '".$Room2."'    " ;
                $QryRoom = mysql_query($sQuery,$connect) ;
                if  ($ArrRoom = mysql_fetch_array($QryRoom))
                {
                    $Location = $ArrRoom["Location"] ; // ������ ��ġ�ڵ�..

                    if  ($Sparate == "00") //  ������ �Ű��ڷ�
                    {
                        $sQuery = "Delete From ".$sSingoName."           ".
                                  " Where SingoDate  = '".$SingoDate."'  ".
                                  "   And Silmooja   = '".$Silmooja."'   ".
                                  "   And Theather   = '".$Theather."'   ".
                                  "   And Room       = '".$Room2."'      ".
                                  "   And Open       = '".$Open."'       ".
                                  "   And Film       = '".$Film."'       ".
                                  "   And ShowDgree  = '".$Degree."'     " ;

                        mysql_query($sQuery,$connect) ;
                    }
                    if  ($Sparate == "22") // ������ �Ű��ڷ�
                    {
                        $sQuery = "Delete From ".$sSingoName."           ".
                                  " Where SingoDate  = '".$AgoDate."'    ".
                                  "   And Silmooja   = '".$Silmooja."'   ".
                                  "   And Theather   = '".$Theather."'   ".
                                  "   And Room       = '".$Room2."'      ".
                                  "   And Open       = '".$Open."'       ".
                                  "   And Film       = '".$Film."'       ".
                                  "   And ShowDgree  = '".$Degree."'     " ;
                        mysql_query($sQuery,$connect) ;
                    }

                    $Items1 = explode("|", $Content); // "|" �� �Ľ�,,,

                    foreach ($Items1 as $Item1)
                    {
                        if   ($Item1=='')  break ;

                        // echo $Items2[0]."~".$Items2[1]."<br>" ;

                        $Items2 = explode("~", $Item1); // "~" �� �Ľ�,,,

                        $UnitPrice = $Items2[0] ; // �ݾ�
                        $Score     = $Items2[1] ; // ���ھ�


                        $sQuery = "Select * From ".$sShowroomorder."   ".
                                  " Where Theather = '".$Theather."'   ".
                                  "   And Room     = '".$Room2."'      " ;
                        $QryShowroomorder = mysql_query($sQuery,$connect) ;
                        if  ($ArrShowroomorder = mysql_fetch_array($QryShowroomorder))
                        {
                            $RoomOrder = $ArrShowroomorder["Seq"] ;
                        }
                        else
                        {
                            $RoomOrder = -1 ;
                        }

                        if   ($Sparate == "00")  //  ������ �Ű��ڷ�
                        {
                            if  ($UnitPrice >= $MinPrice) // �ּ� �ݾ�.
                            {
                                $sQuery = "Insert Into ".$sSingoName."  ".
                                          "Values                       ".
                                          "(                            ".
                                          "  '".$SingoTime."',          ".
                                          "  '".$SingoDate."',          ".
                                          "  '".$Silmooja."',           ".
                                          "  '".$Location."',           ".
                                          "  '".$Theather."',           ".
                                          "  '".$Room2."',              ".
                                          "  '".$Open."',               ".
                                          "  '".$Film."',               ".
                                          "  '',          ".//////////// 9��5�� //////
                                          "  '".$Degree."',             ".
                                          "  '".$UnitPrice."',          ".
                                          "  '".$Score."',              ".
                                          "  '".$UnitPrice * $Score."', ".
                                          "  '".$PhonNo."',             ".
                                          "  '".$RoomOrder."'           ".
                                          ")                            " ;

                                mysql_query($sQuery,$connect) ;

                                ///////////////////////////////////////////////////////////
                                $sOpenTime = "00:00:00" ;

                                $sQuery = "Select Time From ".$sDgrpName."       ".
                                          " Where Silmooja = '".$Silmooja."'     ".
                                          "   And WorkDate = '".$SingoDate."'    ".
                                          "   And Open     = '".$Open."'         ".
                                          "   And Film     = '".$Film."'         ".
                                          "   And Theather = '".$Theather."'     ".
                                          "   And Room     = '".$Room2."'        ".
                                          "   And Degree   = '".$Degree."'       " ;
                                $QryDegreeTime = mysql_query($sQuery,$connect) ;
                                if  ($ArrDegreeTime = mysql_fetch_array($QryDegreeTime))
                                {
                                    $sOpenHoure = (string)SubStr($ArrDegreeTime["Time"],0,2) ;
                                    $sOpenMinut = (string)SubStr($ArrDegreeTime["Time"],2,2) ;

                                    if   ($sOpenHoure == "24")  $sOpenHoure = (string)"00" ;  // �ð�����
                                    if   ($sOpenHoure == "25")  $sOpenHoure = (string)"01" ;
                                    if   ($sOpenHoure == "26")  $sOpenHoure = (string)"02" ;
                                    if   ($sOpenHoure == "27")  $sOpenHoure = (string)"03" ;
                                    if   ($sOpenHoure == "28")  $sOpenHoure = (string)"04" ;
                                    if   ($sOpenHoure == "29")  $sOpenHoure = (string)"05" ;
                                    if   ($sOpenHoure == "30")  $sOpenHoure = (string)"06" ;

                                    $sOpenTime  = $sOpenHoure . ":" . $sOpenMinut . ":00" ;
                                }
                                $sSendHoure = date("H") ;
                                $sSendMinut = date("i") ;
                                $sSendTime = $sSendHoure.":".$sSendMinut.":00" ;

                                if  ($singoDegree=="99") // �ɾ� �ΰ�� ..
                                {
                                    $nGapTime = (strtotime("$sSendTime") - strtotime("$sOpenTime")) / 60 ; // �󿵽ð��� ���۽ð��� ���̸� ������

                                    if  ( (($sOpenHoure >= "22") && ($sOpenHoure <= "23")) && (($sSendHoure >= "00") && ($sSendHoure <= "07")) )
                                    {
                                        $nGapTime = $nGapTime + (24*60) ;
                                    }
                                }
                                else
                                {
                                    $nGapTime = (strtotime("$sSendTime") - strtotime("$sOpenTime")) / 60 ; // �󿵽ð��� ���۽ð��� ���̸� ������
                                }

                                $sQuery = "Delete From wrk_silmoosiljuk       ".
                                          " Where Code     = '".$Silmooja."'  ".
                                          "   And WorkDate = '".$SingoDate."' ".
                                          "   And Theather = '".$Theather."'  ".
                                          "   And Room     = '".$Room2."'     ".
                                          "   And Degree   = '".$Degree."'    ".
                                          "   And Open     = '".$Open."'      ".
                                          "   And Film     = '".$Film."'      " ;
                                mysql_query($sQuery,$connect) ;

                                $sQuery = "Insert Into wrk_silmoosiljuk             ".
                                          "Values                                   ".
                                          "(                                        ".
                                          "      '".$Silmooja."',                   ".
                                          "      '".$SingoDate."',                  ".
                                          "      '".$Theather."',                   ".
                                          "      '".$Room2."',                      ".
                                          "      '".$Degree."',                     ".
                                          "      '".$Open."',                       ".
                                          "      '".$Film."',                       ".
                                          "      '".$PhonNo."',                     ".
                                          "      '".$sOpenHoure.":".$sOpenMinut."', ".
                                          "      '".$sSendHoure.":".$sSendMinut."', ".
                                          "       ".$nGapTime."                     ".
                                          ")                                        " ;
                                mysql_query($sQuery,$connect) ;
                                /////////////////////////////////////////////////////////////////

                            }
                        }
                        if  ($Sparate == "22") //  ������ �Ű��ڷ�
                        {
                            if  ($UnitPrice >= $MinPrice) // �ּ� �ݾ�.
                            {
                            $sQuery = "Insert Into ".$sSingoName."  ".
                                      "Values                       ".
                                      "(                            ".
                                      "  '".$SingoTime."',          ".
                                      "  '".$AgoDate."',            ".
                                      "  '".$Silmooja."',           ".
                                      "  '".$Location."',           ".
                                      "  '".$Theather."',           ".
                                      "  '".$Room2."',              ".
                                      "  '".$Open."',               ".
                                      "  '".$Film."',               ".
                                      "  '',          ".//////////// 9��5�� //////
                                      "  '".$Degree."',             ".
                                      "  '".$UnitPrice."',          ".
                                      "  '".$Score."',              ".
                                      "  '".$UnitPrice * $Score."', ".
                                      "  '".$PhonNo."',             ".
                                      "  '".$RoomOrder."'           ".
                                      ")                            " ;
                            mysql_query($sQuery,$connect) ;
                            }
                        }


                        // ���ϴ���
                        $sQuery = "Select Sum(NumPersons) As SumNumPersons,   ".
                                  "       Sum(TotAmount)  As SumTotAmount     ".
                                  "  From ".$sSingoName."                     ".
                                  " Where SingoDate <= '".$SingoDate."'       ".
                                  "   And Theather  = '".$Theather."'         ".
                                  "   And Open      = '".$Open."'             ".
                                  "   And Film      = '".$Film."'             ".
                                  "   And UnitPrice = '".$UnitPrice."'        " ;

                        $QrySingo2 = mysql_query($sQuery,$connect) ;
                        if  ($ArrNumPersons = mysql_fetch_array($QrySingo2))
                        {
                            $TotSumNumPersons += $ArrNumPersons["SumNumPersons"] ; // ���հ� .
                            $TotTotAmount     += $ArrNumPersons["SumTotAmount"] ;  // �ѱݾ� .

                            $sQuery = "Select Accu, TotAccu, AcMoney, TotAcMoney   ".
                                      "  From ".$sAccName."                        ".
                                      " Where WorkDate   = '".$SingoDate."'        ".
                                      "   And Silmooja   = '".$Silmooja."'         ".
                                      "   And Theather   = '".$Theather."'         ".
                                      "   And Open       = '".$Open."'             ".
                                      "   And Film       = '".$Film."'             ".
                                      "   And UnitPrice  = '".$UnitPrice."'        " ;
                            $QryAccumulate = mysql_query($sQuery,$connect) ;
                            if  ($ArrAccumulate = mysql_fetch_array($QryAccumulate))  // ���� ���������� ���� ���
                            {
                                // ���ϴ��� Update

                                $sQuery = "Update ".$sAccName."                                        ".
                                          "   Set Accu       = '".$ArrNumPersons["SumNumPersons"]."',   ".
                                          "       AcMoney    = '".$ArrNumPersons["SumTotAmount"]."',    ".
                                          "       Location   = '".$Location."',                         ".
                                          "       TodayScore = '".$Score."',                            ".
                                          "       TodayMoney = '".($UnitPrice * $Score)."'              ".
                                          " Where WorkDate   = '".$SingoDate."'                         ".
                                          "   And Silmooja   = '".$Silmooja."'                          ".
                                          "   And Theather   = '".$Theather."'                          ".
                                          "   And Open       = '".$Open."'                              ".
                                          "   And Film       = '".$Film."'                              ".
                                          "   And UnitPrice  = '".$UnitPrice."'                         " ;
                                mysql_query($sQuery,$connect) ;
                            }
                            else
                            {
                                // ���ϴ��� Insert
                                $sQuery = "Insert Into ".$sAccName."                    ".
                                          "Values                                       ".
                                          "(                                            ".
                                          "    '".$SingoDate."',                        ".
                                          "    '".$Silmooja."',                         ".
                                          "    '".$Theather."',                         ".
                                          "    '".$Open."',                             ".
                                          "    '".$Theather."',                         ".
                                          "    '".$UnitPrice."',                        ".
                                          "    '".$ArrNumPersons["SumNumPersons"]."',   ".
                                          "    '0',                                     ".
                                          "    '".$ArrNumPersons["SumTotAmount"]."',    ".
                                          "    '0',                                     ".
                                          "    '".$Location."',                         ".
                                          "    '".$Score."',                            ".
                                          "    '".($UnitPrice * $Score)."'              ".
                                          ")                                            " ;
                                mysql_query($sQuery,$connect) ;
                            }

                            // ���� Delete
                            $sQuery = "Delete From ".$sAccName."              ".
                                      " Where WorkDate   > '".$SingoDate."'   ".
                                      "   And Silmooja   = '".$Silmooja."'    ".
                                      "   And Theather   = '".$Theather."'    ".
                                      "   And Open       = '".$Open."'        ".
                                      "   And Film       = '".$Film."'        " ;
                            mysql_query($sQuery,$connect) ;
                        }
                    }

                    //******************************************************************************** 07/02/25


                    $sQuery = "Select Max(Degree) As MaxDegree       ".
                              "  From ".$sDgrpName."                 ".
                              " Where Silmooja = '".$Silmooja."'     ".
                              "   And WorkDate = '".$SingoDate."'    ".
                              "   And Open     = '".$Open."'         ".
                              "   And Film     = '".$Film."'         ".
                              "   And Theather = '".$Theather."'     ".
                              "   And Room     = '".$Room2."'        " ;
                    $QryMaxDegree = mysql_query($sQuery,$connect) ;
                    if  ($ArrMaxDegree = mysql_fetch_array($QryMaxDegree))
                    {
                        $MaxDegree = $ArrMaxDegree["MaxDegree"] ; // ������ ȸ���� ���Ѵ�.
                    }

                    if  (($Degree!="01") && ($Degree == $MaxDegree))  // ���Ź��� ȸ���� ������ȸ���� ���..
                    {
                        $sQuery = "Select UnitPrice,                        ".
                                  "       Sum(NumPersons) As sumNumPersons  ".
                                  "  From ".$sSingoName."                   ".
                                  " Where SingoDate = '".$SingoDate."'      ".
                                  "   And Theather  = '".$Theather."'       ".
                                  "   And Room      = '".$Room2."'          ".
                                  "   And Open      = '".$Open."'           ".
                                  "   And Film      = '".$Film."'           ".
                                  "   And NumPersons > 0                    ".
                                  " Group By UnitPrice                      ".
                                  " Order By UnitPrice Desc                 " ;
                        $QrySingo = mysql_query($sQuery,$connect) ;
                        while ($ArrSingo = mysql_fetch_array($QrySingo))
                        {
                             $TotNumPersons += $ArrSingo["sumNumPersons"] ;

                             $OutPut = $OutPut . ( $ArrSingo["UnitPrice"]/100 . " " . $ArrSingo["sumNumPersons"] . " " ) ;
                        }

                        if  ($OutPut <> "")
                        {
                             echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"" .  $Theather . " " . $Room . "�� " . $OutPut . "�հ�" . $TotNumPersons . "\"\r\n" ;
                        }
                    }


                    //******************************************************************************** 07/02/05
                    $OutPut = "" ;
                    $TotNumPersons = 0 ;

                    if  ($Degree=="01") // 1ȸ���� ���
                    {
                        $sQuery = "Select Count(*) As CntDegreepriv      ".
                                  "  From ".$sDgrpName."                 ".
                                  " Where Silmooja = '".$Silmooja."'     ".
                                  "   And WorkDate = '".$SingoDate."'    ".
                                  "   And Open     = '".$Open."'         ".
                                  "   And Film     = '".$Film."'         ".
                                  "   And Theather = '".$Theather."'     ".
                                  "   And Room     = '".$Room2."'        ".
                                  " Order By Degree                      " ;
                        $QryCntDegreepriv = mysql_query($sQuery,$connect) ;
                        $ArrCntDegreepriv = mysql_fetch_array($QryCntDegreepriv) ;
                        if  ($ArrCntDegreepriv["CntDegreepriv"] == 0)
                        {
                            $sQuery = "Select * From ".$sDgrName."           ".
                                      " Where Silmooja = '".$Silmooja."'     ".
                                      "   And Open     = '".$Open."'         ".
                                      "   And Film     = '".$Film."'         ".
                                      "   And Theather = '".$Theather."'     ".
                                      "   And Room     = '".$Room2."'        ".
                                      " Order By Degree                      " ;

                            $QryDegree = mysql_query($sQuery,$connect) ;
                            while ($ArrDegree = mysql_fetch_array($QryDegree))
                            {
                                 $sQuery = "Insert Into ".$sDgrpName."      ".
                                           "Values                          ".
                                           "(                               ".
                                           "  '".$Silmooja."',              ".
                                           "  '".$SingoDate."',             ".
                                           "  '".$Open."',                  ".
                                           "  '".$Film."',                  ".
                                           "  '".$Theather."',              ".
                                           "  '".$Room2."',                 ".
                                           "  '".$ArrDegree["Degree"]."',   ".
                                           "  '".$ArrDegree["Time"]."',     ".
                                           "  '".$ArrDegree["Discript"]."'  ".
                                           ")                               " ;
                                 mysql_query($sQuery,$connect) ;
                            }
                        }

                        $TimeTable = "" ;

                        $sQuery = "Select Degree, Time                   ".
                                  "  From ".$sDgrpName."                 ".
                                  " Where Silmooja = '".$Silmooja."'     ".
                                  "   And WorkDate = '".$SingoDate."'    ".
                                  "   And Open     = '".$Open."'         ".
                                  "   And Film     = '".$Film."'         ".
                                  "   And Theather = '".$Theather."'     ".
                                  "   And Room     = '".$Room2."'        ".
                                  " Order By Degree                      " ;
                        $QryDegreeTime = mysql_query($sQuery,$connect) ;
                        while ($ArrDegreeTime = mysql_fetch_array($QryDegreeTime))
                        {
                             if  ($TimeTable != "" )
                             {
                                 $TimeTable .= "," ;
                             }

                             $nDegree = $ArrDegreeTime["Degree"] + 0 ;
                             if  ($nDegree != 99)
                             {
                                 $TimeTable .= ($nDegree."ȸ".$ArrDegreeTime["Time"]) ;
                             }
                             else
                             {
                                 $TimeTable .= ("�ɾ�".$ArrDegreeTime["Time"]) ;
                             }

                             $OutPut = $OutPut . ( substr($ArrSingo["UnitPrice"],0,2) . "-" . $ArrSingo["sumNumPersons"] . " " ) ;
                        }

                        echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"". $Theather." ".$Film." ".$TimeTable ."\"" ;
                    }
                }
                else
                {
                    echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"�ش��ϴ� �����ڵ尡 �����ϴ�. : (".$Theather.")" ;
                }
            }
            else
            {
                echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"�ش��ϴ� ��ȭ�ڵ尡 �����ϴ�. : (".$Film2.")" ;
            }
        }


    }


    if  ($Action=="9393")  //  ���� ������ ��ü�հ� ����
    {
        mysql_select_db($cont_db) ;

        $TotScr  = 0 ;

        // $LGTRM:01025656581,20060425090155,4098,"8282 05"
        $sQuery = "Select * From bas_custom       ".
                  " Where Film    = '".$Film2."'  ".
                  "   And PhoneNo = '".$PhonNo."' " ;
        $QryCustom = mysql_query($sQuery,$connect) ;
        if  ($ArrCustom = mysql_fetch_array($QryCustom))
        {
            $sQuery = "Select * From bas_filmtitle ".
                      " Where Code = '".$Film2."'  " ;
            $QryFilmtitle = mysql_query($sQuery,$connect) ;
            if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
            {
                $OutPut = "" ;
                $TotNumPersons = 0 ;

                $Open      = $ArrFilmtitle["Open"] ;
                $Film      = $ArrFilmtitle["Code"] ;

                $sSingoName = get_singotable($Open,$Film,$connect) ;  // �Ű� ���̺� �̸�..
                $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate �̸�..
                $sDgrName   = get_degree($Open,$Film,$connect) ;
                $sDgrpName  = get_degreepriv($Open,$Film,$connect) ;
            }
            $Message = "" ;


            $sQuery = "Select Sum(NumPersons) As sumNumPersons  ".
                      "  From ".$sSingoName."                   ".
                      " Where Film      = '".$Film."'           ".
                      "   And SingoDate = '".$WorkDate."'       ".
                      "   And Location  = '100'                 " ;
            $QryNumPersons = mysql_query($sQuery,$connect) ;
            if ($ArrNumPersons = mysql_fetch_array($QryNumPersons))
            {
                 $sumNumPersons =  $ArrNumPersons["sumNumPersons"] ;
            }
            else
            {
                $sumNumPersons =  0 ;
            }

            $TotScr += $sumNumPersons ;
            $Message = $Message . "����" . number_format($sumNumPersons) ;

            $AddedLoc = "" ;
            $sQuery = "select Location from bas_filmsupplyzoneloc ".
                      " Where Zone = '04'                         " ;
            $QryZoneloc = mysql_query($sQuery,$connect) ;
            while ($ArrZoneloc = mysql_fetch_array($QryZoneloc))
            {
                 if  ($AddedLoc == "")
                     $AddedLoc .= "( Location = '".$ArrZoneloc["Location"]."' "  ;
                 else
                     $AddedLoc .= " or Location = '".$ArrZoneloc["Location"]."' "  ;
            }
            $AddedLoc .= ")" ;

            $sQuery = "Select Sum(NumPersons) As sumNumPersons  ".
                      "  From ".$sSingoName."                   ".
                      " Where Film      = '".$Film."'           ".
                      "   And SingoDate = '".$WorkDate."'       ".
                      "   And ".$AddedLoc."                     " ;
            $QryNumPersons = mysql_query($sQuery,$connect) ;
            if ($ArrNumPersons = mysql_fetch_array($QryNumPersons))
            {
                $sumNumPersons =  $ArrNumPersons["sumNumPersons"] ;
            }
            else
            {
                $sumNumPersons =  0 ;
            }

            $TotScr += $sumNumPersons ;
            $Message = $Message . "���" . number_format($sumNumPersons) ;



            $AddedLoc = "  ( Location = '200'      ". // �λ�
                        "  or   Location = '600'   ". // ���
                        "  or   Location = '207'   ". // ����
                        "  or   Location = '205'   ". // ����
                        "  or   Location = '208'   ". // ����
                        "  or   Location = '202'   ". // ����
                        "  or   Location = '211'   ". // ��õ
                        "  or   Location = '212'   ". // ��â
                        "  or   Location = '213'   ". // ���
                        "  or   Location = '201' ) " ; // â��

            $sQuery = "Select Sum(NumPersons) As sumNumPersons  ".
                      "  From ".$sSingoName."                   ".
                      " Where Film      = '".$Film."'           ".
                      "   And SingoDate = '".$WorkDate."'       ".
                      "   And ".$AddedLoc."                     " ;
            $QryNumPersons = mysql_query($sQuery,$connect) ;
            if ($ArrNumPersons = mysql_fetch_array($QryNumPersons))
            {
                $sumNumPersons =  $ArrNumPersons["sumNumPersons"] ;
            }
            else
            {
                $sumNumPersons =  0 ;
            }
            $TotScr += $sumNumPersons ;
            $Message = $Message . "�λ�" . number_format($sumNumPersons) ;


            $AddedLoc = "" ;
            $sQuery = "select Location from bas_filmsupplyzoneloc ".
                      " Where Zone = '04'                         " ;
            $QryZoneloc = mysql_query($sQuery,$connect) ;
            while ($ArrZoneloc = mysql_fetch_array($QryZoneloc))
            {
                 if  ($AddedLoc == "")
                     $AddedLoc .= "( Location <> '".$ArrZoneloc["Location"]."' "  ;
                 else
                     $AddedLoc .= " and Location <> '".$ArrZoneloc["Location"]."' "  ;
            }
            $AddedLoc .= " and Location <> '100' "  ; // ����
            $AddedLoc .= " and Location <> '200' "  ; // �λ�
            $AddedLoc .= " and Location <> '600' "  ; // ���
            $AddedLoc .= " and Location <> '207' "  ; // ����
            $AddedLoc .= " and Location <> '205' "  ; // ����
            $AddedLoc .= " and Location <> '208' "  ; // ����
            $AddedLoc .= " and Location <> '202' "  ; // ����
            $AddedLoc .= " and Location <> '211' "  ; // ��õ
            $AddedLoc .= " and Location <> '212' "  ; // ��â
            $AddedLoc .= " and Location <> '213' "  ; // ���
            $AddedLoc .= " and Location <> '201' "  ; // â��
            $AddedLoc .= ")" ;

            // ��� + ���� + �λ� + ��� + â�� + ���� �� ������ �������� �������� �Ѵ�.
            $sQuery = "Select Sum(NumPersons) As sumNumPersons  ".
                      "  From ".$sSingoName."                   ".
                      " Where Film      = '".$Film."'           ".
                      "   And SingoDate = '".$WorkDate."'       ".
                      "   And ".$AddedLoc."                     " ;
            $QryNumPersons = mysql_query($sQuery,$connect) ;
            if ($ArrNumPersons = mysql_fetch_array($QryNumPersons))
            {
                 $sumNumPersons =  $ArrNumPersons["sumNumPersons"] ;
            }
            else
            {
                $sumNumPersons =  0 ;
            }
            $TotScr += $sumNumPersons ;
            $Message = $Message . "����" . number_format($sumNumPersons) ;

            $Message = $Message . "��ü" . number_format($TotScr) ."��" ;

            echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"" ."(".$Film.")".  $Message . "\"" ;
        }
    }


    if  ($Action=="9292")
    {
        mysql_select_db($cont_db) ;

        $TotScr  = 0 ;

        // $LGTRM:01025656581,20060425090155,4098,"8282 05"
        $sQuery = "Select * From bas_custom       ".
                  " Where Film    = '".$Film2."'  ".
                  "   And PhoneNo = '".$PhonNo."' " ;
        $QryCustom = mysql_query($sQuery,$connect) ;
        if  ($ArrCustom = mysql_fetch_array($QryCustom))
        {
            $sQuery = "Select * From bas_filmtitle   ".
                      " Where Code = '".$Film2."'    " ;
            $QryFilmtitle = mysql_query($sQuery,$connect) ;
            if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
            {
                $OutPut = "" ;
                $TotNumPersons = 0 ;

                $Open = $ArrFilmtitle["Open"] ;
                $Film = $ArrFilmtitle["Code"] ;

                $sSingoName = get_singotable($Open,$Film,$connect) ;  // �Ű� ���̺� �̸�..
                $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate �̸�..
                $sDgrName   = get_degree($Open,$Film,$connect) ;
                $sDgrpName  = get_degreepriv($Open,$Film,$connect) ;
            }
            $Message = "" ;


            $sQuery = "Select Sum(NumPersons) As sumNumPersons  ".
                      "  From ".$sSingoName."                   ".
                      " Where Film      = '".$Film."'           ".
                      "   And Location  = '100'                 " ;
            $QryNumPersons = mysql_query($sQuery,$connect) ;
            if ($ArrNumPersons = mysql_fetch_array($QryNumPersons))
            {
                 $sumNumPersons =  $ArrNumPersons["sumNumPersons"] ;
            }
            else
            {
                $sumNumPersons =  0 ;
            }

            $TotScr += $sumNumPersons ;
            $Message = $Message . "����" . number_format($sumNumPersons) ;

            $AddedLoc = "" ;
            $sQuery = "select Location from bas_filmsupplyzoneloc ".
                      " Where Zone = '04'                         " ;
            $QryZoneloc = mysql_query($sQuery,$connect) ;
            while ($ArrZoneloc = mysql_fetch_array($QryZoneloc))
            {
                 if  ($AddedLoc == "")
                     $AddedLoc .= "( Location = '".$ArrZoneloc["Location"]."' "  ;
                 else
                     $AddedLoc .= " or Location = '".$ArrZoneloc["Location"]."' "  ;
            }
            $AddedLoc .= ")" ;

            $sQuery = "Select Sum(NumPersons) As sumNumPersons  ".
                      "  From ".$sSingoName."                   ".
                      " Where Film      = '".$Film."'           ".
                      "   And ".$AddedLoc."                     " ;
            $QryNumPersons = mysql_query($sQuery,$connect) ;
            if ($ArrNumPersons = mysql_fetch_array($QryNumPersons))
            {
                $sumNumPersons =  $ArrNumPersons["sumNumPersons"] ;
            }
            else
            {
                $sumNumPersons =  0 ;
            }

            $TotScr += $sumNumPersons ;
            $Message = $Message . "���" . number_format($sumNumPersons) ;



            $AddedLoc = "  ( Location = '200'      ". // �λ�
                        "  or   Location = '600'   ". // ���
                        "  or   Location = '207'   ". // ����
                        "  or   Location = '205'   ". // ����
                        "  or   Location = '208'   ". // ����
                        "  or   Location = '202'   ". // ����
                        "  or   Location = '211'   ". // ��õ
                        "  or   Location = '212'   ". // ��â
                        "  or   Location = '213'   ". // ���
                        "  or   Location = '201' ) " ; // â��

            $sQuery = "Select Sum(NumPersons) As sumNumPersons  ".
                      "  From ".$sSingoName."                   ".
                      " Where Film      = '".$Film."'           ".
                      "   And ".$AddedLoc."                     " ;
            $QryNumPersons = mysql_query($sQuery,$connect) ;
            if ($ArrNumPersons = mysql_fetch_array($QryNumPersons))
            {
                $sumNumPersons =  $ArrNumPersons["sumNumPersons"] ;
            }
            else
            {
                $sumNumPersons =  0 ;
            }
            $TotScr += $sumNumPersons ;
            $Message = $Message . "�λ�" . number_format($sumNumPersons) ;


            $AddedLoc = "" ;
            $sQuery = "select Location from bas_filmsupplyzoneloc ".
                      " Where Zone = '04'                         " ;
            $QryZoneloc = mysql_query($sQuery,$connect) ;
            while ($ArrZoneloc = mysql_fetch_array($QryZoneloc))
            {
                 if  ($AddedLoc == "")
                     $AddedLoc .= "( Location <> '".$ArrZoneloc["Location"]."' "  ;
                 else
                     $AddedLoc .= " and Location <> '".$ArrZoneloc["Location"]."' "  ;
            }
            $AddedLoc .= " and Location <> '100' "  ; // ����
            $AddedLoc .= " and Location <> '200' "  ; // �λ�
            $AddedLoc .= " and Location <> '600' "  ; // ���
            $AddedLoc .= " and Location <> '207' "  ; // ����
            $AddedLoc .= " and Location <> '205' "  ; // ����
            $AddedLoc .= " and Location <> '208' "  ; // ����
            $AddedLoc .= " and Location <> '202' "  ; // ����
            $AddedLoc .= " and Location <> '211' "  ; // ��õ
            $AddedLoc .= " and Location <> '212' "  ; // ��â
            $AddedLoc .= " and Location <> '213' "  ; // ���
            $AddedLoc .= " and Location <> '201' "  ; // â��
            $AddedLoc .= ")" ;

            // ��� + ���� + �λ� + ��� + â�� + ���� �� ������ �������� �������� �Ѵ�.
            $sQuery = "Select Sum(NumPersons) As sumNumPersons  ".
                      "  From ".$sSingoName."                   ".
                      " Where Film      = '".$Film."'           ".
                      "   And ".$AddedLoc."                     " ;
            $QryNumPersons = mysql_query($sQuery,$connect) ;
            if ($ArrNumPersons = mysql_fetch_array($QryNumPersons))
            {
                 $sumNumPersons =  $ArrNumPersons["sumNumPersons"] ;
            }
            else
            {
                $sumNumPersons =  0 ;
            }
            $TotScr += $sumNumPersons ;
            $Message = $Message . "����" . number_format($sumNumPersons) ;


            $Message = $Message . "��ü" . number_format($TotScr) ."��" ;

            echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"" ."(".$Film.")".  $Message . "\"" ;
        }
    }




    if  ($Action=="5555")
    {
        mysql_select_db($cont_db) ;

        if  ($SpDate <> "")
        {
            $SingoDate = "20".$SpDate ;
        }

        // ��ȭ�ڵ�� ��ȭ�� ã�´�.
        $sQuery = "Select * From bas_filmtitle   ".
                  " Where Code = '".$Film2."'    " ;
        $QryFilmtitle = mysql_query($sQuery,$connect) ;
        if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $Open      = $ArrFilmtitle["Open"] ;
            $Film      = $ArrFilmtitle["Code"] ;
            $Room2     = sprintf("%02d",$Room) ;

            $sSingoName = get_singotable($Open,$Film,$connect) ;  // �Ű� ���̺� �̸�..
            $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate �̸�..
            $sDgrName   = get_degree($Open,$Film,$connect) ;
            $sDgrpName  = get_degreepriv($Open,$Film,$connect) ;

            $OutPut = "" ;
            $TotNumPersons = 0 ;


            $sQuery = "Select UnitPrice,                        ".
                      "       Sum(NumPersons) As sumNumPersons  ".
                      "  From ".$sSingoName."                   ".
                      " Where SingoDate = '".$SingoDate."'      ".
                      "   And Theather  = '".$Theather."'       ".
                      "   And Room      = '".$Room2."'          ".
                      "   And Open      = '".$Open."'           ".
                      "   And Film      = '".$Film."'           ".
                      "   And NumPersons > 0                    ".
                      " Group By UnitPrice                      ".
                      " Order By UnitPrice Desc                 " ;
            $QrySingo = mysql_query($sQuery,$connect) ;
            while ($ArrSingo = mysql_fetch_array($QrySingo))
            {
                 $TotNumPersons += $ArrSingo["sumNumPersons"] ;

                 $OutPut = $OutPut . ( $ArrSingo["UnitPrice"]/100 . " " . $ArrSingo["sumNumPersons"] . " " ) ;
            }

            if  ($OutPut <> "")
            {
                 echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"" .  $Theather . " " . $Room . "�� " . $OutPut . "�հ�" . $TotNumPersons . "\"\r\n" ;
            }

            $sQuery = "Insert Into wrk_smsreq          ".
                      "Values                          ".
                      "(                               ".
                      "  '".$SingoDate."',             ".
                      "  '".substr($SingoTime,8,6)."', ".
                      "  '".$PhonNo."',                ".
                      "  '',                           ".
                      "  '".$Theather."',              ".
                      "  '".$Room."',                  ".
                      "  '".$Film."',                  ".
                      "  '".$Action."'                 ".
                      ")                               " ;
            mysql_query($sQuery,$connect) ;
        }
    }


    if  ($Action=="5100")
    {
        mysql_select_db($cont_db) ;

        if  ($SpDate <> "")
        {
            $SingoDate = "20".$SpDate ;
        }

        // ��ȭ�ڵ�� ��ȭ�� ã�´�.
        $sQuery = "Select * From bas_filmtitle   ".
                  " Where Code = '".$Film2."'    " ;
        $QryFilmtitle = mysql_query($sQuery,$connect) ;
        if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $OutPut = "" ;
            $TotNumPersons = 0 ;

            $Open      = $ArrFilmtitle["Open"] ;
            $Film      = $ArrFilmtitle["Code"] ;
            $Room2     = sprintf("%02d",$Room) ;

            $sSingoName = get_singotable($Open,$Film,$connect) ;  // �Ű� ���̺� �̸�..
            $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate �̸�..
            $sDgrName   = get_degree($Open,$Film,$connect) ;
            $sDgrpName  = get_degreepriv($Open,$Film,$connect) ;

            $sQuery = "Select UnitPrice,                        ".
                      "       Sum(NumPersons) As sumNumPersons  ".
                      "  From ".$sSingoName."                   ".
                      " Where SingoDate <= '".$SingoDate."'     ".
                      "   And Theather   = '".$Theather."'      ".
                      "   And Room       = '".$Room2."'         ".
                      "   And Open       = '".$Open."'          ".
                      "   And Film       = '".$Film."'          ".
                      "   And NumPersons > 0                    ".
                      " Group By UnitPrice                      ".
                      " Order By UnitPrice Desc                 " ;
            $QrySingo = mysql_query($sQuery,$connect) ;
            while ($ArrSingo = mysql_fetch_array($QrySingo))
            {
                 $TotNumPersons += $ArrSingo["sumNumPersons"] ;

                 $OutPut = $OutPut . ( $ArrSingo["UnitPrice"]/100 . " " . $ArrSingo["sumNumPersons"] . " " ) ;
            }

            if  ($OutPut <> "")
            {
                 echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"" .  $Theather . " " . $Room . "�� " . $OutPut . "�հ�" . $TotNumPersons . "\"" ;
            }

            $sQuery = "Insert Into wrk_smsreq          ".
                      "Values                          ".
                      "(                               ".
                      "  '".$SingoDate."',             ".
                      "  '".substr($SingoTime,8,6)."', ".
                      "  '".$PhonNo."',                ".
                      "  '',                           ".
                      "  '".$Theather."',              ".
                      "  '".$Room."',                  ".
                      "  '".$Film."',                  ".
                      "  '".$Action."'                 ".
                      ")                               " ;
            mysql_query($sQuery,$connect) ;
        }
    }

    if  ($Action=="5200")
    {
        mysql_select_db($cont_db) ;

        // ��ȭ�ڵ�� ��ȭ�� ã�´�.
        $sQuery = "Select * From bas_filmtitle   ".
                  " Where Code = '".$Film2."'    " ;
        $QryFilmtitle = mysql_query($sQuery,$connect) ;
        if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $OutPut = "" ;
            $TotNumPersons = 0 ;

            $Open  = $ArrFilmtitle["Open"] ;
            $Film  = $ArrFilmtitle["Code"] ;
            $Room2 = sprintf("%02d",$Room) ;

            $sSingoName = get_singotable($Open,$Film,$connect) ;  // �Ű� ���̺� �̸�..
            $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate �̸�..
            $sDgrName   = get_degree($Open,$Film,$connect) ;
            $sDgrpName  = get_degreepriv($Open,$Film,$connect) ;

            $sQuery = "Select ShowDgree,                        ".
                      "       Sum(NumPersons) As sumNumPersons  ".
                      "  From ".$sSingoName."                   ".
                      " Where SingoDate = '".$SingoDate."'      ".
                      "   And Theather  = '".$Theather."'       ".
                      "   And Room      = '".$Room2."'          ".
                      "   And Open      = '".$Open."'           ".
                      "   And Film      = '".$Film."'           ".
                      "   And NumPersons > 0                    ".
                      " Group By ShowDgree                      ".
                      " Order By ShowDgree                      " ;
            $QrySingo = mysql_query($sQuery,$connect) ;
            while ($ArrSingo = mysql_fetch_array($QrySingo))
            {
                 $TotNumPersons += $ArrSingo["sumNumPersons"] ;

                 $OutPut = $OutPut . ( $ArrSingo["ShowDgree"] . "ȸ" . $ArrSingo["sumNumPersons"] . " " ) ;
            }

            if  ($OutPut <> "")
            {
                 echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"" .  $Theather . " " . $Room . "�� " . $OutPut . "�հ�" . $TotNumPersons . "\"" ;
            }

            $sQuery = "Insert Into wrk_smsreq          ".
                      "Values                          ".
                      "(                               ".
                      "  '".$SingoDate."',             ".
                      "  '".substr($SingoTime,8,6)."', ".
                      "  '".$PhonNo."',                ".
                      "  '',                           ".
                      "  '".$Theather."',              ".
                      "  '".$Room."',                  ".
                      "  '".$Film."',                  ".
                      "  '".$Action."'                 ".
                      ")                               " ;
            mysql_query($sQuery,$connect) ;
        }
    }

    if  ($Action=="5300")
    {
        mysql_select_db($cont_db) ;

        $SingoDate1 = "20".$SpDate."01" ;
        $SingoDate2 = "20".$SpDate."31" ;

        // ��ȭ�ڵ�� ��ȭ�� ã�´�.
        $sQuery = "Select * From bas_filmtitle   ".
                  " Where Code = '".$Film2."'    " ;
        $QryFilmtitle = mysql_query($sQuery,$connect) ;
        if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $OutPut = "" ;
            $TotNumPersons = 0 ;

            $Open  = $ArrFilmtitle["Open"] ;
            $Film  = $ArrFilmtitle["Code"] ;
            $Room2 = sprintf("%02d",$Room) ;

            $sSingoName = get_singotable($Open,$Film,$connect) ;  // �Ű� ���̺� �̸�..
            $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate �̸�..
            $sDgrName   = get_degree($Open,$Film,$connect) ;
            $sDgrpName  = get_degreepriv($Open,$Film,$connect) ;

            $sQuery = "Select UnitPrice,                        ".
                      "       Sum(NumPersons) As sumNumPersons  ".
                      "  From ".$sSingoName."                   ".
                      " Where SingoDate >= '".$SingoDate1."'    ".
                      "   And SingoDate <= '".$SingoDate2."'    ".
                      "   And Theather   = '".$Theather."'      ".
                      "   And Open       = '".$Open."'          ".
                      "   And Film       = '".$Film."'          ".
                      "   And NumPersons > 0                    ".
                      " Group By UnitPrice                      ".
                      " Order By UnitPrice Desc                 " ;
            $QrySingo = mysql_query($sQuery,$connect) ;
            while ($ArrSingo = mysql_fetch_array($QrySingo))
            {
                 $TotNumPersons += $ArrSingo["sumNumPersons"] ;

                 $OutPut = $OutPut . ( $ArrSingo["UnitPrice"]/100 . " " . $ArrSingo["sumNumPersons"] . " " ) ;
            }

            if  ($OutPut <> "")
            {
                 echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"" .  $Theather . " "  . $OutPut . "�հ�" . $TotNumPersons . "\"" ;
            }

            $sQuery = "Insert Into wrk_smsreq          ".
                      "Values                          ".
                      "(                               ".
                      "  '".$SingoDate."',             ".
                      "  '".substr($SingoTime,8,6)."', ".
                      "  '".$PhonNo."',                ".
                      "  '',                           ".
                      "  '".$Theather."',              ".
                      "  '".$Room."',                  ".
                      "  '".$Film."',                  ".
                      "  '".$Action."'                 ".
                      ")                               " ;
            mysql_query($sQuery,$connect) ;
        }
    }





    if  ($Action=="111111")  //////////////////////
    {
        mysql_select_db($cont_db) ;

        if  ($Content)
        {
            // ��ȭ�ڵ�� ��ȭ�� ã�´�.
            $sQuery = "Select * From bas_filmtitle   ".
                      " Where Code = '".$Film2."'    " ;
            $QryFilmtitle = mysql_query($sQuery,$connect) ;
            if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
            {
                $Open = $ArrFilmtitle["Open"] ;
                $Film = $ArrFilmtitle["Code"] ;

                $sSingoName = get_singotable($Open,$Film,$connect) ;  // �Ű� ���̺� �̸�..
                $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate �̸�..
                $sDgrName   = get_degree($Open,$Film,$connect) ;
                $sDgrpName  = get_degreepriv($Open,$Film,$connect) ;

                $Room2 = sprintf("%02d",$Room) ;


                $Items1 = explode("|", $Content); // "|" �� �Ľ�,,,

                foreach ($Items1 as $Item1)
                {
                    if   ($Item1=='')  break ;

                    $Items2 = explode("~", $Item1); // "~" �� �Ľ�,,,

                    //echo $Items2[0]."~".$Items2[1]."~".$Items2[2]."~".$Items2[3]."~".$Items2[4]."<br>" ;

                    $Degree   = $Items2[0] ;
                    $Degree2  = sprintf("%02d",$Degree) ;
                    $Time     = $Items2[1] ;

                    $sQuery = "Select * From bas_showroom        ".
                              " Where Theather = '".$Theather."' ".
                              "   And Room     = '".$Room2."'    " ;
                    $QryShowroom = mysql_query($sQuery,$connect) ;
                    if  ($ArrShowroom = mysql_fetch_array($QryShowroom))
                    {
                        if  (($Seat!="0") && ($Seat!=""))
                        {
                            $sQuery = "Update bas_showroom               ".
                                      "   Set Seat     = '".$Seat."'     ".
                                      " Where Theather = '".$Theather."' ".
                                      "   And Room     = '".$Room2."'    " ;
                            mysql_query($sQuery,$connect) ;
                        }

                        $Discript = $ArrShowroom["Discript"] ;

                        $sQuery = "Select * From ".$sDgrpName."          ".
                                  " Where Silmooja = '".$Silmooja."'     ".
                                  "   And WorkDate = '".$ObjectDate."'   ".
                                  "   And Open     = '".$Open."'         ".
                                  "   And Film     = '".$Film."'         ".
                                  "   And Theather = '".$Theather."'     ".
                                  "   And Room     = '".$Room2."'        ".
                                  "   And Degree   = '".$Degree2."'      " ;
                        $QryDegreepriv = mysql_query($sQuery,$connect) ;
                        if  ($ArrDegreepriv = mysql_fetch_array($QryDegreepriv))
                        {
                            $sQuery = "Update ".$sDgrpName."                 ".
                                      "   Set Time     = '".$Time."',        ".
                                      "       Discript = '".$Discript."'     ".
                                      " Where Silmooja = '".$Silmooja."'     ".
                                      "   And WorkDate = '".$ObjectDate."'   ".
                                      "   And Open     = '".$Open."'         ".
                                      "   And Film     = '".$Film."'         ".
                                      "   And Theather = '".$Theather."'     ".
                                      "   And Room     = '".$Room2."'        ".
                                      "   And Degree   = '".$Degree2."'      " ;
                            mysql_query($sQuery,$connect) ;
                        }
                        else
                        {
                            $sQuery = "Insert Into ".$sDgrpName."   ".
                                      "Values                       ".
                                      " (                           ".
                                      "      '".$Silmooja."',       ".
                                      "      '".$ObjectDate."',     ".
                                      "      '".$Open."',           ".
                                      "      '".$Film."',           ".
                                      "      '".$Theather."',       ".
                                      "      '".$Room2."',          ".
                                      "      '".$Degree2."',        ".
                                      "      '".$Time."',           ".
                                      "      '".$Discript."'        ".
                                      " )                           " ;
                            mysql_query($sQuery,$connect) ;
                        }
                    }
                    else
                    {
                        echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"�ش��ϴ� �����ڵ尡 �����ϴ�. : (".$Theather.")" ;
                    }
                }
            }
            else
            {
                echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"�ش��ϴ� ��ȭ�ڵ尡 �����ϴ�. : (".$Film2.")" ;
            }
        }
    }








    if  ($Action=="save_all")
    {
        mysql_select_db($cont_sms) ;

        // Mem_RecvCur_ �� ����..
        $Items1 = explode("|", $Content); // "|" �� �Ľ�,,,

        foreach ($Items1 as $Item1)
        {
            $sQuery = "Insert Into save_all  ".
                      "Values                ".
                      "(                     ".
                      "      ".$SingoDate.", ".
                      "      NULL,           ".
                      "      '".$Item1."'    ".
                      ")                     " ;
            mysql_query($sQuery,$connect) ;
        }
    }

    if  ($Action=="save_error")
    {
        mysql_select_db($cont_sms) ;


        $Items1 = explode("^", $Content); // "^" �� �Ľ�,,,

        foreach ($Items1 as $Item1)
        {
            if   ($Item1=='')  continue ;



            $Items2 = explode("~", $Item1); // "~" �� �Ľ�,,,

            foreach ($Items2 as $Item2)
            {
                $PhoneNo = $Items2[0] ; //
                $SmsData = $Items2[1] ; //
            }

            if  ($SmsData!="")
            {
                $sQuery = "Insert Into save_error  ".
                          "Values                  ".
                          "(                       ".
                          "      '".$SingoDate."', ".
                          "      NULL,             ".
                          "      '".$PhoneNo."',   ".
                          "      '".$SmsData."'    ".
                          ")                       " ;
                mysql_query($sQuery,$connect) ;
            }
        }
    }

    mysql_close($connect);
?>