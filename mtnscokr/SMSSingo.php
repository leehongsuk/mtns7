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

    set_time_limit(0) ; // 긴 처리작업이 있을 때만 쓴다.....

    $Today = time()-(3600*7) ; // 새벽 7시 까지 오늘로 간주한다...

    if (!$WorkDate)
    {
       $WorkDate = date("Ymd",$Today) ;
    }

    if (!$SingoDate)
    {
        $SingoDate = $WorkDate ;  // SingoDate 를 지정하고 날릴경우를 대비해서
    }

    // 하루 전날을 구한다.
    $AgoDate = date("Ymd",strtotime("-1 day",strtotime(substr($WorkDate,0,4)."-".substr($WorkDate,4,2)."-".substr($WorkDate,6,2).""))) ;

    include "config.php";

    $connect = dbconn();

    /*
    echo "신고일자".$SingoTime ;
    echo "폰번호".$PhonNo ;
    echo "극장코드".$Theather ;
    echo "필름코드".$Film2 ;
    echo "관".$Room ;
    echo "회차".$Degree ;
    echo "내용".$Content ;
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

    if  ($Action=="77") // 시간표 등록1
    {
        mysql_select_db($cont_db) ;

        if  ($Content) // 회차정보내용이 있을때..
        {
            // 영화코드로 영화를 찾는다.
            $sQuery = "Select * From bas_filmtitle ".
                      " Where Code = '".$Film2."'  " ;
            $QryFilmtitle = mysql_query($sQuery,$connect) ;
            if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
            {
                $Open = $ArrFilmtitle["Open"] ; // 영화 코드
                $Film = $ArrFilmtitle["Code"] ; //

                $sSingoName = get_singotable($Open,$Film,$connect) ;  // 신고 테이블 이름..
                $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate 이름..
                $sDgrName   = get_degree($Open,$Film,$connect) ;
                $sDgrpName  = get_degreepriv($Open,$Film,$connect) ;

                $sQuery = "Select * From bas_showroom        ".
                          " Where Theather = '".$Theather."' ".
                          "   And Room     = '".$Room."'     " ;
                $QryRoom = mysql_query($sQuery,$connect) ;
                if  ($ArrRoom = mysql_fetch_array($QryRoom))
                {
                    // 상영관 좌석수 갱신..
                    $sQuery = "Update bas_showroom               ".
                              "   Set Seat     = '".$Seat."'     ".
                              " Where Theather = '".$Theather."' ".
                              "   And Room     = '".$Room."'     " ;
                    mysql_query($sQuery,$connect) ;

                    // 회차 자료가있다면 다지운다,
                    $sQuery = "Delete From ".$sDgrpName."         ".
                              " Where Silmooja = '".$Silmooja."'  ".
                              "   And WorkDate = '".$SingoDate."' ".
                              "   And Open     = '".$Open."'      ".
                              "   And Film     = '".$Film."'      ".
                              "   And Theather = '".$Theather."'  ".
                              "   And Room     = '".$Room."'      " ;
                    mysql_query($sQuery,$connect) ;

                    // 회차 자료가있다면 다지운다,
                    $sQuery = "Delete From ".$sDgrName."         ".
                              " Where Silmooja = '".$Silmooja."' ".
                              "   And Theather = '".$Theather."' ".
                              "   And Room     = '".$Room."'     ".
                              "   And Open     = '".$Open."'     ".
                              "   And Film     = '".$Film."'     " ;
                    mysql_query($sQuery,$connect) ;

                    $Items1 = explode("|", $Content); // "|" 로 파싱,,,

                    foreach ($Items1 as $Item1)
                    {
                        if   ($Item1=='')  break ;

                        //echo $Items2[0]."~".$Items2[1]."~".$Items2[2]."~".$Items2[3]."~".$Items2[4]."<br>" ;

                        $Items2 = explode("~", $Item1); // "~" 로 파싱,,, 회차~시간표


                        $Degree = $Items2[0] ; // 회차 ..
                        $Time   = $Items2[1] ; // 시간표 ..


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
                    echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"해당하는 극장코드가 없읍니다. : (".$Theather.")" ;
                }
            }
            else
            {
                echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"해당하는 영화코드가 없읍니다. : (".$Film2.")" ;
            }
        }
        else
        {
            echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"회차정보내용이 없읍니다. : (".$Theather.")" ;
        }
    }

    if  ($Action=="78") // 시간표 등록2
    {
        mysql_select_db($cont_db) ;

        if  ($Content) // 회차정보내용이 있을때..
        {
            // 영화코드로 영화를 찾는다.
            $sQuery = "Select * From bas_filmtitle ".
                      " Where Code = '".$Film2."'  " ;
            $QryFilmtitle = mysql_query($sQuery,$connect) ;
            if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
            {
                $Open = $ArrFilmtitle["Open"] ; // 영화 코드
                $Film = $ArrFilmtitle["Code"] ; //

                $sSingoName = get_singotable($Open,$Film,$connect) ;  // 신고 테이블 이름..
                $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate 이름..
                $sDgrName   = get_degree($Open,$Film,$connect) ;
                $sDgrpName  = get_degreepriv($Open,$Film,$connect) ;

                $sQuery = "Select * From bas_showroom        ".
                          " Where Theather = '".$Theather."' ".
                          "   And Room     = '".$Room."'     " ;
                $QryRoom = mysql_query($sQuery,$connect) ;
                if  ($ArrRoom = mysql_fetch_array($QryRoom))
                {
                    // 상영관 좌석수 갱신..
                    $sQuery = "Update bas_showroom               ".
                              "   Set Seat = '".$Seat."'         ".
                              " Where Theather = '".$Theather."' ".
                              "   And Room     = '".$Room."'     " ;
                    mysql_query($sQuery,$connect) ;


                    $Items1 = explode("|", $Content); // "|" 로 파싱,,,

                    foreach ($Items1 as $Item1)
                    {
                        if   ($Item1=='')  break ;

                        //echo $Items2[0]."~".$Items2[1]."~".$Items2[2]."~".$Items2[3]."~".$Items2[4]."<br>" ;

                        $Items2 = explode("~", $Item1); // "~" 로 파싱,,, 회차~시간표


                        $Degree = $Items2[0] ; // 회차 ..
                        $Time   = $Items2[1] ; // 시간표 ..


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
                    echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"해당하는 극장코드가 없읍니다. : (".$Theather.")" ;
                }
            }
            else
            {
                echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"해당하는 영화코드가 없읍니다. : (".$Film2.")" ;
            }
        }
        else
        {
            echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"회차정보내용이 없읍니다. : (".$Theather.")" ;
        }
    }

    if  ($Action=="98")   // 예상 스코어보고...
    {


        mysql_select_db($cont_db) ; // 1번 디비

        $Room2 = sprintf("%02d",$Room) ; // 2자리 룸코드.. // 이것때문에 자료가 안들어 갔나??

        if  ($Content)
        {
            // 영화코드로 영화를 찾는다.
            $sQuery = "Select * From bas_filmtitle  ".
                      " Where Code = '".$Film2."'   " ;
            $QryFilmtitle = mysql_query($sQuery,$connect) ;
            if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
            {
                $Open = $ArrFilmtitle["Open"] ; // 영화코드 ..
                $Film = $ArrFilmtitle["Code"] ; //

                $sSingoName = get_singotable($Open,$Film,$connect) ;  // 신고 테이블 이름..
                $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate 이름..
                $sDgrName   = get_degree($Open,$Film,$connect) ;
                $sDgrpName  = get_degreepriv($Open,$Film,$connect) ;
                $sShowroomorder = get_showroomorder($Open,$Film,$connect) ;


                // 극장코드로 극장을 찾는다.
                $sQuery = "Select * From bas_showroom        ".
                          " Where Theather = '".$Theather."' ".
                          "   And Room     = '".$Room2."'    " ;
                $QryRoom = mysql_query($sQuery,$connect) ;
                if  ($ArrRoom = mysql_fetch_array($QryRoom))
                {
                    $Location = $ArrRoom["Location"] ; // 극장의 위치코드..

                    if  ($Sparate == "00") //  지정일 신고자료
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
                    if  ($Sparate == "22") // 이전날 신고자료
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

                    $Items1 = explode("|", $Content); // "|" 로 파싱,,,

                    foreach ($Items1 as $Item1)
                    {
                        if   ($Item1=='')  break ;

                        // echo $Items2[0]."~".$Items2[1]."<br>" ;

                        $Items2 = explode("~", $Item1); // "~" 로 파싱,,,

                        $UnitPrice = $Items2[0] ; // 금액
                        $Score     = $Items2[1] ; // 스코어


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

                        if   ($Sparate == "00")  //  지정일 신고자료
                        {
                            if  ($UnitPrice >= $MinPrice) // 최소 금액.
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
                                          "  '',          ".//////////// 9月5日 //////
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

                                    if   ($sOpenHoure == "24")  $sOpenHoure = (string)"00" ;  // 시간보정
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

                                if  ($singoDegree=="99") // 심야 인경우 ..
                                {
                                    $nGapTime = (strtotime("$sSendTime") - strtotime("$sOpenTime")) / 60 ; // 상영시간과 전송시간의 차이를 분으로

                                    if  ( (($sOpenHoure >= "22") && ($sOpenHoure <= "23")) && (($sSendHoure >= "00") && ($sSendHoure <= "07")) )
                                    {
                                        $nGapTime = $nGapTime + (24*60) ;
                                    }
                                }
                                else
                                {
                                    $nGapTime = (strtotime("$sSendTime") - strtotime("$sOpenTime")) / 60 ; // 상영시간과 전송시간의 차이를 분으로
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
                        if  ($Sparate == "22") //  이전날 신고자료
                        {
                            if  ($UnitPrice >= $MinPrice) // 최소 금액.
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
                                      "  '',          ".//////////// 9月5日 //////
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


                        // 당일누계
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
                            $TotSumNumPersons += $ArrNumPersons["SumNumPersons"] ; // 총합계 .
                            $TotTotAmount     += $ArrNumPersons["SumTotAmount"] ;  // 총금액 .

                            $sQuery = "Select Accu, TotAccu, AcMoney, TotAcMoney   ".
                                      "  From ".$sAccName."                        ".
                                      " Where WorkDate   = '".$SingoDate."'        ".
                                      "   And Silmooja   = '".$Silmooja."'         ".
                                      "   And Theather   = '".$Theather."'         ".
                                      "   And Open       = '".$Open."'             ".
                                      "   And Film       = '".$Film."'             ".
                                      "   And UnitPrice  = '".$UnitPrice."'        " ;
                            $QryAccumulate = mysql_query($sQuery,$connect) ;
                            if  ($ArrAccumulate = mysql_fetch_array($QryAccumulate))  // 만일 누계정보가 있을 경우
                            {
                                // 당일누계 Update

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
                                // 당일누계 Insert
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

                            // 누계 Delete
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
                        $MaxDegree = $ArrMaxDegree["MaxDegree"] ; // 마지막 회차를 구한다.
                    }

                    if  (($Degree!="01") && ($Degree == $MaxDegree))  // 수신받은 회차가 마지막회차인 경우..
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
                             echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"" .  $Theather . " " . $Room . "관 " . $OutPut . "합계" . $TotNumPersons . "\"\r\n" ;
                        }
                    }


                    //******************************************************************************** 07/02/05
                    $OutPut = "" ;
                    $TotNumPersons = 0 ;

                    if  ($Degree=="01") // 1회차인 경우
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
                                 $TimeTable .= ($nDegree."회".$ArrDegreeTime["Time"]) ;
                             }
                             else
                             {
                                 $TimeTable .= ("심야".$ArrDegreeTime["Time"]) ;
                             }

                             $OutPut = $OutPut . ( substr($ArrSingo["UnitPrice"],0,2) . "-" . $ArrSingo["sumNumPersons"] . " " ) ;
                        }

                        echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"". $Theather." ".$Film." ".$TimeTable ."\"" ;
                    }
                }
                else
                {
                    echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"해당하는 극장코드가 없읍니다. : (".$Theather.")" ;
                }
            }
            else
            {
                echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"해당하는 영화코드가 없읍니다. : (".$Film2.")" ;
            }
        }


    }


    if  ($Action=="9393")  //  당일 전지역 전체합계 예상
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

                $sSingoName = get_singotable($Open,$Film,$connect) ;  // 신고 테이블 이름..
                $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate 이름..
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
            $Message = $Message . "서울" . number_format($sumNumPersons) ;

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
            $Message = $Message . "경기" . number_format($sumNumPersons) ;



            $AddedLoc = "  ( Location = '200'      ". // 부산
                        "  or   Location = '600'   ". // 울산
                        "  or   Location = '207'   ". // 김해
                        "  or   Location = '205'   ". // 진주
                        "  or   Location = '208'   ". // 거제
                        "  or   Location = '202'   ". // 마산
                        "  or   Location = '211'   ". // 사천
                        "  or   Location = '212'   ". // 거창
                        "  or   Location = '213'   ". // 양산
                        "  or   Location = '201' ) " ; // 창원

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
            $Message = $Message . "부산" . number_format($sumNumPersons) ;


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
            $AddedLoc .= " and Location <> '100' "  ; // 서울
            $AddedLoc .= " and Location <> '200' "  ; // 부산
            $AddedLoc .= " and Location <> '600' "  ; // 울산
            $AddedLoc .= " and Location <> '207' "  ; // 김해
            $AddedLoc .= " and Location <> '205' "  ; // 진주
            $AddedLoc .= " and Location <> '208' "  ; // 거제
            $AddedLoc .= " and Location <> '202' "  ; // 마산
            $AddedLoc .= " and Location <> '211' "  ; // 사천
            $AddedLoc .= " and Location <> '212' "  ; // 거창
            $AddedLoc .= " and Location <> '213' "  ; // 양산
            $AddedLoc .= " and Location <> '201' "  ; // 창원
            $AddedLoc .= ")" ;

            // 경기 + 서울 + 부산 + 울산 + 창원 + 김해 를 제외한 나머지를 지방으로 한다.
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
            $Message = $Message . "지방" . number_format($sumNumPersons) ;

            $Message = $Message . "전체" . number_format($TotScr) ."명" ;

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

                $sSingoName = get_singotable($Open,$Film,$connect) ;  // 신고 테이블 이름..
                $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate 이름..
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
            $Message = $Message . "서울" . number_format($sumNumPersons) ;

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
            $Message = $Message . "경기" . number_format($sumNumPersons) ;



            $AddedLoc = "  ( Location = '200'      ". // 부산
                        "  or   Location = '600'   ". // 울산
                        "  or   Location = '207'   ". // 김해
                        "  or   Location = '205'   ". // 진주
                        "  or   Location = '208'   ". // 거제
                        "  or   Location = '202'   ". // 마산
                        "  or   Location = '211'   ". // 사천
                        "  or   Location = '212'   ". // 거창
                        "  or   Location = '213'   ". // 양산
                        "  or   Location = '201' ) " ; // 창원

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
            $Message = $Message . "부산" . number_format($sumNumPersons) ;


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
            $AddedLoc .= " and Location <> '100' "  ; // 서울
            $AddedLoc .= " and Location <> '200' "  ; // 부산
            $AddedLoc .= " and Location <> '600' "  ; // 울산
            $AddedLoc .= " and Location <> '207' "  ; // 김해
            $AddedLoc .= " and Location <> '205' "  ; // 진주
            $AddedLoc .= " and Location <> '208' "  ; // 거제
            $AddedLoc .= " and Location <> '202' "  ; // 마산
            $AddedLoc .= " and Location <> '211' "  ; // 사천
            $AddedLoc .= " and Location <> '212' "  ; // 거창
            $AddedLoc .= " and Location <> '213' "  ; // 양산
            $AddedLoc .= " and Location <> '201' "  ; // 창원
            $AddedLoc .= ")" ;

            // 경기 + 서울 + 부산 + 울산 + 창원 + 김해 를 제외한 나머지를 지방으로 한다.
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
            $Message = $Message . "지방" . number_format($sumNumPersons) ;


            $Message = $Message . "전체" . number_format($TotScr) ."명" ;

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

        // 영화코드로 영화를 찾는다.
        $sQuery = "Select * From bas_filmtitle   ".
                  " Where Code = '".$Film2."'    " ;
        $QryFilmtitle = mysql_query($sQuery,$connect) ;
        if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $Open      = $ArrFilmtitle["Open"] ;
            $Film      = $ArrFilmtitle["Code"] ;
            $Room2     = sprintf("%02d",$Room) ;

            $sSingoName = get_singotable($Open,$Film,$connect) ;  // 신고 테이블 이름..
            $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate 이름..
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
                 echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"" .  $Theather . " " . $Room . "관 " . $OutPut . "합계" . $TotNumPersons . "\"\r\n" ;
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

        // 영화코드로 영화를 찾는다.
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

            $sSingoName = get_singotable($Open,$Film,$connect) ;  // 신고 테이블 이름..
            $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate 이름..
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
                 echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"" .  $Theather . " " . $Room . "관 " . $OutPut . "합계" . $TotNumPersons . "\"" ;
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

        // 영화코드로 영화를 찾는다.
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

            $sSingoName = get_singotable($Open,$Film,$connect) ;  // 신고 테이블 이름..
            $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate 이름..
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

                 $OutPut = $OutPut . ( $ArrSingo["ShowDgree"] . "회" . $ArrSingo["sumNumPersons"] . " " ) ;
            }

            if  ($OutPut <> "")
            {
                 echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"" .  $Theather . " " . $Room . "관 " . $OutPut . "합계" . $TotNumPersons . "\"" ;
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

        // 영화코드로 영화를 찾는다.
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

            $sSingoName = get_singotable($Open,$Film,$connect) ;  // 신고 테이블 이름..
            $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate 이름..
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
                 echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"" .  $Theather . " "  . $OutPut . "합계" . $TotNumPersons . "\"" ;
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
            // 영화코드로 영화를 찾는다.
            $sQuery = "Select * From bas_filmtitle   ".
                      " Where Code = '".$Film2."'    " ;
            $QryFilmtitle = mysql_query($sQuery,$connect) ;
            if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
            {
                $Open = $ArrFilmtitle["Open"] ;
                $Film = $ArrFilmtitle["Code"] ;

                $sSingoName = get_singotable($Open,$Film,$connect) ;  // 신고 테이블 이름..
                $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate 이름..
                $sDgrName   = get_degree($Open,$Film,$connect) ;
                $sDgrpName  = get_degreepriv($Open,$Film,$connect) ;

                $Room2 = sprintf("%02d",$Room) ;


                $Items1 = explode("|", $Content); // "|" 로 파싱,,,

                foreach ($Items1 as $Item1)
                {
                    if   ($Item1=='')  break ;

                    $Items2 = explode("~", $Item1); // "~" 로 파싱,,,

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
                        echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"해당하는 극장코드가 없읍니다. : (".$Theather.")" ;
                    }
                }
            }
            else
            {
                echo "AT\$LGTSNDSM=".$PhonNo.",01024690081,4098,\"해당하는 영화코드가 없읍니다. : (".$Film2.")" ;
            }
        }
    }








    if  ($Action=="save_all")
    {
        mysql_select_db($cont_sms) ;

        // Mem_RecvCur_ 의 내용..
        $Items1 = explode("|", $Content); // "|" 로 파싱,,,

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


        $Items1 = explode("^", $Content); // "^" 로 파싱,,,

        foreach ($Items1 as $Item1)
        {
            if   ($Item1=='')  continue ;



            $Items2 = explode("~", $Item1); // "~" 로 파싱,,,

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