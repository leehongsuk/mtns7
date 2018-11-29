<?
    session_start();

    // 정상적으로 로그인 했는지 체크한다.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = '../index_com.php'</script>";
    }
    else
    {
        include "config.php";

        $connect=dbconn();

        mysql_select_db($cont_db) ; // 해당배급사를 구하고


        // 자동 스코어 생성
        // 반드시 배급사는 영화를 1개이상 선택하여야 하고
        // 실무자 역시 배급사간 선택한 영화를 선택하여야 하며,
        // 도착보고가 되어 회차정보나, 요금정보가 있어야 한다.
        if  ($AutoMakeScore)
        {
            // 실무자와 배급사가 잡고 있는 상영관들을 전부나열한다.
            $qry_silmoojafilmsupply = mysql_query("Select * From bas_silmoojatheather As silmooja,     ".
                                                  "              bas_filmsupplytitle  As filmsupply    ".
                                                  "Where silmooja.Open = filmsupply.Open               ".
                                                  "  And silmooja.Film = filmsupply.Film               ".
                                                  "  And filmsupply.FilmSupply = '".$filmsupplyCode."' ",$connect) ;

            while ($silmoojafilmsupply_data = mysql_fetch_array($qry_silmoojafilmsupply))
            {
                $Silmooja     = $silmoojafilmsupply_data["Silmooja"] ; // 실무자코드
                $Theather     = $silmoojafilmsupply_data["Theather"] ; // 상영관
                $Room         = $silmoojafilmsupply_data["Room"] ;
                $silmoojaOpen = $silmoojafilmsupply_data["Open"] ;     // 영화
                $silmoojaFilm = $silmoojafilmsupply_data["Film"] ;

                $sSingoName = get_singotable($silmoojaOpen,$silmoojaFilm,$connect) ;  // 신고 테이블 이름..
                $sAccName   = get_acctable($silmoojaOpen,$silmoojaFilm,$connect) ;    // accumulate 이름..
                $sDgrName   = get_degree($silmoojaOpen,$silmoojaFilm,$connect) ;
                $sDgrpName  = get_degreepriv($silmoojaOpen,$silmoojaFilm,$connect) ;

                // 실무자가 잡고 있는 상영관으로 신고된 오늘 자료가 존재하는지 확인한다.
                $qry_cntSingo = mysql_query("Select Count(*) As cntSingo        ".
                                            "  From ".$sSingoName."             ".
                                            " Where Theather  = '".$Theather."' ".
                                            "   And Room      = '".$Room."'     ".
                                            "   And SingoDate = '".$WorkDate."' ",$connect) ;
                $cntSingo_data  = mysql_fetch_array($qry_cntSingo) ;
                if  ($cntSingo_data)
                {
                    if  ($cntSingo_data["cntSingo"] == 0) // 신고자료가 없는경우
                    {
                        $qry_showroom = mysql_query("Select * From bas_showroom        ".
                                                    " Where Theather = '".$Theather."' ".
                                                    "   And Room     = '".$Room."'     ",$connect) ;
                        $showroom_data = mysql_fetch_array($qry_showroom) ;
                        if ($showroom_data)
                        {
                            $Location = $showroom_data["Location"] ;  // 상영관 소재지역명
                        }

                        $existdegree = true ;

                        // 오늘회차 존재여부확인 ..
                        $qry_degreepriv = mysql_query("Select * From ".$sDgrpName."          ".
                                                      " Where Silmooja = '".$Silmooja."'     ".
                                                      "   And WorkDate = '".$WorkDate."'     ".
                                                      "   And Open     = '".$silmoojaOpen."' ".
                                                      "   And Film     = '".$silmoojaFilm."' ".
                                                      "   And Theather = '".$Theather."'     ".
                                                      "   And Room     = '".$Room."'         ",$connect) ;
                        $degreepriv_data  = mysql_fetch_array($qry_degreepriv) ;
                        if  (!$degreepriv_data)
                        {
                            $existdegree = false ;
                        }

                        // 각각의 회차만큼 ....
                        $qry_temp = mysql_query("Select * From ".$sDgrName."              ".
                                                " Where Silmooja = '".$Silmooja."'     ".
                                                "   And Open     = '".$silmoojaOpen."' ".
                                                "   And Film     = '".$silmoojaFilm."' ".
                                                "   And Theather = '".$Theather."'     ".
                                                "   And Room     = '".$Room."'         ",$connect) ;
                        $temp_data  = mysql_fetch_array($qry_temp) ;

                        if  ($temp_data)
                        {
                            $qry_degree = mysql_query("Select * From ".$sDgrName."              ".
                                                      " Where Silmooja = '".$Silmooja."'     ".
                                                      "   And Open     = '".$silmoojaOpen."' ".
                                                      "   And Film     = '".$silmoojaFilm."' ".
                                                      "   And Theather = '".$Theather."'     ".
                                                      "   And Room     = '".$Room."'         ",$connect) ;
                        }
                        else
                        {
                            $qry_degree = mysql_query("Select * From ".$sDgrName."              ".
                                                      " Where Theather = '".$Theather."'     ".
                                                      "   And Room     = '".$Room."'         ",$connect) ;
                        }

                        while ($degree_data  = mysql_fetch_array($qry_degree))
                        {
                            $Degree         = $degree_data["Degree"] ;   // 각각의 회차.
                            $degreeTime     = $degree_data["Time"] ;     // 각각의 시간.
                            $degreeDiscript = $degree_data["Discript"] ; // 각각의 상영관이름.

                            if  ($existdegree == false)
                            {
                                mysql_query("Insert Into ".$sDgrpName."     ".
                                            "Values (                       ".
                                            "         '".$Silmooja."',      ".
                                            "         '".$WorkDate."',      ".
                                            "         '".$silmoojaOpen."',  ".
                                            "         '".$silmoojaFilm."',  ".
                                            "         '".$Theather."',      ".
                                            "         '".$Room."',          ".
                                            "         '".$Degree."',        ".
                                            "         '".$degreeTime."',    ".
                                            "         '".$degreeDiscript."' ".
                                            "        )                      ",$connect) ;
                            }


                            $existunitprice = true ;

                            // 오늘요금 존재여부확인 ..
                            $qry_unitpricespriv = mysql_query("Select * From bas_unitpricespriv      ".
                                                              " Where Silmooja = '".$Silmooja."'     ".
                                                              "   And WorkDate = '".$WorkDate."'     ".
                                                              "   And Open     = '".$silmoojaOpen."' ".
                                                              "   And Film     = '".$silmoojaFilm."' ".
                                                              "   And Theather = '".$Theather."'     ".
                                                              "   And Room     = '".$Room."'         ",$connect) ;
                            $unitpricespriv_data  = mysql_fetch_array($qry_unitpricespriv) ;
                            if  (!$unitpricespriv_data)
                            {
                                $existunitprice = false ;
                            }

                            // 각각의 요금만큼 .....
                            $qry_unitprices = mysql_query("Select * From bas_unitprices      ".
                                                          " Where Theather = '".$Theather."' ".
                                                          "   And Room     = '".$Room."'     ",$connect) ;
                            while ($unitprices_data  = mysql_fetch_array($qry_unitprices))
                            {
                                $UnitPrice         = $unitprices_data["UnitPrice"] ; // 각각의 요금.
                                $unitpriceDiscript = $unitprices_data["Discript"] ;  // 각각의 상영관이름.

                                if  ($existdegree == false)
                                {
                                    mysql_query("Insert Into bas_unitpricespriv    ".
                                                "Values (                          ".
                                                "         '".$Silmooja."',         ".
                                                "         '".$WorkDate."',         ".
                                                "         '".$silmoojaOpen."',     ".
                                                "         '".$silmoojaFilm."',     ".
                                                "         '".$Theather."',         ".
                                                "         '".$Room."',             ".
                                                "         '".$UnitPrice."',        ".
                                                "         '".$unitpriceDiscript."' ".
                                                "        )                         ",$connect) ;
                                }

                                //
                                // 신고자료를 강제로 만든다....
                                //
                                $qry_showroomorder = mysql_query("Select * From bas_showroomorder                ".
                                                                 " Where FilmSupply = '".$filmsupplyCode."'      ".
                                                                 "   And Theather   = '".$Theather."'            ".
                                                                 "   And Room       = '".$Room."'                ",$connect) ;
                                if  ($showroomorder_data = mysql_fetch_array($qry_showroomorder))
                                {
                                    $RoomOrder = $showroomorder_data["Seq"] ;
                                }
                                else
                                {
                                    $RoomOrder = -1 ;
                                }

                                mysql_query("Insert Into ".$sSingoName."        ".
                                            " Values (  '".date("YmdHis")."',   ".
                                            "           '".$WorkDate."',        ".
                                            "           '".$Silmooja."',        ".
                                            "           '".$filmsupplyCode."',  ".
                                            "           '".$Location."',        ".
                                            "           '".$Theather."',        ".
                                            "           '".$Room."',            ".
                                            "           '".$silmoojaOpen."',    ".
                                            "           '".$silmoojaFilm."',    ".
                                            "           '',          ".//////////// 9月5日 //////
                                            "           '".$Degree."',          ".
                                            "           '".$UnitPrice."',       ".
                                            "           '0',                    ".
                                            "           '0',                    ".
                                            "           '',                     ".
                                            "           '".$RoomOrder."'        ".
                                            " )                                 ",$connect) ;
                            }
                        }
                    }
                }
            }



            // 실무자종영처리정보와 배급사가 잡고 있는 상영관들을 전부나열한다.
            $qry_silmoojafilmsupply = mysql_query("Select * From bas_silmoojatheatherfinish As silmoojafinish, ".
                                                  "              bas_filmsupplytitle  As filmsupply            ".
                                                  "Where silmoojafinish.Open = filmsupply.Open                 ".
                                                  "  And silmoojafinish.Film = filmsupply.Film                 ".
                                                  "  And filmsupply.FilmSupply = '".$filmsupplyCode."'         ",$connect) ;

            while ($silmoojafilmsupply_data = mysql_fetch_array($qry_silmoojafilmsupply))
            {
                $Silmooja     = $silmoojafilmsupply_data["Silmooja"] ; // 실무자코드
                $Theather     = $silmoojafilmsupply_data["Theather"] ; // 상영관
                $Room         = $silmoojafilmsupply_data["Room"] ;
                $silmoojaOpen = $silmoojafilmsupply_data["Open"] ;     // 영화
                $silmoojaFilm = $silmoojafilmsupply_data["Film"] ;

                // 실무자가 잡고 있는 상영관으로 신고된 오늘 자료가 존재하는지 확인한다.
                $qry_cntSingo = mysql_query("Select Count(*) As cntSingo        ".
                                            "  From ".$sSingoName."             ".
                                            " Where Theather  = '".$Theather."' ".
                                            "   And Room      = '".$Room."'     ".
                                            "   And SingoDate = '".$WorkDate."' ",$connect) ;
                $cntSingo_data  = mysql_fetch_array($qry_cntSingo) ;
                if  ($cntSingo_data)
                {
                    if  ($cntSingo_data["cntSingo"] == 0) // 신고자료가 없는경우
                    {
                        $qry_showroom = mysql_query("Select * From bas_showroom        ".
                                                    " Where Theather = '".$Theather."' ".
                                                    "   And Room     = '".$Room."'     ",$connect) ;
                        $showroom_data = mysql_fetch_array($qry_showroom) ;
                        if ($showroom_data)
                        {
                            $Location = $showroom_data["Location"] ;  // 상영관 소재지역명
                        }

                        $existdegree = true ;

                        // 오늘회차 존재여부확인 ..
                        $qry_degreepriv = mysql_query("Select * From ".$sDgrpName."          ".
                                                      " Where Silmooja = '".$Silmooja."'     ".
                                                      "   And WorkDate = '".$WorkDate."'     ".
                                                      "   And Open     = '".$silmoojaOpen."' ".
                                                      "   And Film     = '".$silmoojaFilm."' ".
                                                      "   And Theather = '".$Theather."'     ".
                                                      "   And Room     = '".$Room."'         ",$connect) ;
                        $degreepriv_data  = mysql_fetch_array($qry_degreepriv) ;
                        if  (!$degreepriv_data)
                        {
                            $existdegree = false ;
                        }

                        // 각각의 회차만큼 ....
                        $qry_temp = mysql_query("Select * From ".$sDgrName."              ".
                                                  " Where Silmooja = '".$Silmooja."'     ".
                                                  "   And Open     = '".$silmoojaOpen."' ".
                                                  "   And Film     = '".$silmoojaFilm."' ".
                                                  "   And Theather = '".$Theather."'     ".
                                                  "   And Room     = '".$Room."'         ",$connect) ;
                        $temp_data  = mysql_fetch_array($qry_temp) ;

                        if  ($temp_data)
                        {
                            $qry_degree = mysql_query("Select * From ".$sDgrName."              ".
                                                      " Where Silmooja = '".$Silmooja."'     ".
                                                      "   And Open     = '".$silmoojaOpen."' ".
                                                      "   And Film     = '".$silmoojaFilm."' ".
                                                      "   And Theather = '".$Theather."'     ".
                                                      "   And Room     = '".$Room."'         ",$connect) ;
                        }
                        else
                        {
                            $qry_degree = mysql_query("Select * From ".$sDgrName."              ".
                                                      " Where Theather = '".$Theather."'     ".
                                                      "   And Room     = '".$Room."'         ",$connect) ;
                        }



                        while ($degree_data  = mysql_fetch_array($qry_degree))
                        {
                            $Degree         = $degree_data["Degree"] ;   // 각각의 회차.
                            $degreeTime     = $degree_data["Time"] ;     // 각각의 시간.
                            $degreeDiscript = $degree_data["Discript"] ; // 각각의 상영관이름.

                            if  ($existdegree == false)
                            {
                                mysql_query("Insert Into ".$sDgrpName."     ".
                                            "Values (                       ".
                                            "         '".$Silmooja."',      ".
                                            "         '".$WorkDate."',      ".
                                            "         '".$silmoojaOpen."',  ".
                                            "         '".$silmoojaFilm."',  ".
                                            "         '".$Theather."',      ".
                                            "         '".$Room."',          ".
                                            "         '".$Degree."',        ".
                                            "         '".$degreeTime."',    ".
                                            "         '".$degreeDiscript."' ".
                                            "        )                      ",$connect) ;
                            }


                            $existunitprice = true ;

                            // 오늘요금 존재여부확인 ..
                            $qry_unitpricespriv = mysql_query("Select * From bas_unitpricespriv      ".
                                                              " Where Silmooja = '".$Silmooja."'     ".
                                                              "   And WorkDate = '".$WorkDate."'     ".
                                                              "   And Open     = '".$silmoojaOpen."' ".
                                                              "   And Film     = '".$silmoojaFilm."' ".
                                                              "   And Theather = '".$Theather."'     ".
                                                              "   And Room     = '".$Room."'         ",$connect) ;
                            $unitpricespriv_data  = mysql_fetch_array($qry_unitpricespriv) ;
                            if  (!$unitpricespriv_data)
                            {
                                $existunitprice = false ;
                            }

                            // 각각의 요금만큼 .....
                            $qry_unitprices = mysql_query("Select * From bas_unitprices      ".
                                                          " Where Theather = '".$Theather."' ".
                                                          "   And Room     = '".$Room."'     ",$connect) ;
                            while ($unitprices_data  = mysql_fetch_array($qry_unitprices))
                            {
                                $UnitPrice         = $unitprices_data["UnitPrice"] ; // 각각의 요금.
                                $unitpriceDiscript = $unitprices_data["Discript"] ;  // 각각의 상영관이름.

                                if  ($existdegree == false)
                                {
                                    mysql_query("Insert Into bas_unitpricespriv    ".
                                                "Values (                          ".
                                                "         '".$Silmooja."',         ".
                                                "         '".$WorkDate."',         ".
                                                "         '".$silmoojaOpen."',     ".
                                                "         '".$silmoojaFilm."',     ".
                                                "         '".$Theather."',         ".
                                                "         '".$Room."',             ".
                                                "         '".$UnitPrice."',        ".
                                                "         '".$unitpriceDiscript."' ".
                                                "        )                         ",$connect) ;
                                }

                                //
                                // 신고자료를 강제로 만든다....
                                //
                                $qry_showroomorder = mysql_query("Select * From bas_showroomorder                ".
                                                                 " Where FilmSupply = '".$filmsupplyCode."'      ".
                                                                 "   And Theather   = '".$Theather."'            ".
                                                                 "   And Room       = '".$Room."'                ",$connect) ;
                                if  ($showroomorder_data = mysql_fetch_array($qry_showroomorder))
                                {
                                    $RoomOrder = $showroomorder_data["Seq"] ;
                                }
                                else
                                {
                                    $RoomOrder = -1 ;
                                }

                                mysql_query("Insert Into ".$sSingoName."        ".
                                            " Values (  '".date("YmdHis")."',   ".
                                            "           '".$WorkDate."',        ".
                                            "           '".$Silmooja."',        ".
                                            "           '".$filmsupplyCode."',  ".
                                            "           '".$Location."',        ".
                                            "           '".$Theather."',        ".
                                            "           '".$Room."',            ".
                                            "           '".$silmoojaOpen."',    ".
                                            "           '".$silmoojaFilm."',    ".
                                            "  '',          ".//////////// 9月5日 //////
                                            "           '".$Degree."',          ".
                                            "           '".$UnitPrice."',       ".
                                            "           '0',                    ".
                                            "           '0',                    ".
                                            "           ''    )                 ",$connect) ;
                            }
                        }
                    }
                }
            }
        }  // End of  if  ($AutoMakeScore)

        if  ($ClearAcc) // 메모리 클리어
        {
            $sQuery = "Delete From ".$sAccName." " ;
            mysql_query($sQuery,$connect) ;
        }
?>
<html>
  <link rel=stylesheet href=./style.css type=text/css>
  <META HTTP-EQUIV=Content-Type CONTENT="text/html; charset=euc-kr">

  <head>
  <title>배급사업무</title>
  </head>

  <body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
     <? echo "<b>".$UserName . "</b>님을 환영합니다!" ; ?>
     <a href="../index_com.php?actcode=logout"><b>[LogOut]</b></a>
     <center>

        <table cellpadding=0 cellspacing=0 border=0>
        <tr height=25>
           <td align=left colspan=2>
           <ol>

             <li><b><a href="wrk_filmsupply_1.php?BackAddr=wrk_filmsupply.php">조회일자설정</a></b></li>
                    <a href="wrk_filmsupply_1.php?BackAddr=wrk_filmsupply.php">(<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>)</a><br><br>
             <li><b><a href="wrk_filmsupply_2.php?BackAddr=wrk_filmsupply.php">개별지역설정</a></b></li><br><br>
             <li><b><a href="wrk_filmsupply_3.php?BackAddr=wrk_filmsupply.php">복합지역설정</a></b></li><br><br>
             <li><b><a OnClick="window.open('wrk_filmsupply_Gongji.php','','status=0,menubar=0, scrollbars=yes,resizable=yes,width=350,height=300')">실무자공지발송</a></b></li><br><br>
             <li><b><a href="wrk_filmsupply_5.php?BackAddr=wrk_filmsupply.php">등록된실무자</a></b></li><br><br>
             <li><b><a href="wrk_filmsupply_S1.php?BackAddr=wrk_filmsupply.php">영화선택</a></b></li><br>
             <?
             // 배급사가 선택된 영화들을 나열하여 개개별로 실무자를 지정할 수 있도록한다.

             $qryFilmsupplytitle = mysql_query("Select * From bas_filmtitle ",$connect) ;
             while  ($filmsupplytitle_data = mysql_fetch_array($qryFilmsupplytitle))
             {
                 $Filmsupplytitle_Title = $filmsupplytitle_data["Name"] ;

                 $FilmTitle = $filmsupplytitle_data["Open"] . $filmsupplytitle_data["Code"] ;

                 ?>
                 <a OnClick="location.href='wrk_filmsupply_S2.php?FilmTitle=<?=$FilmTitle?>&BackAddr=wrk_filmsupply.php'"><?=$Filmsupplytitle_Title?></a>
                 <a OnClick="location.href='SmsChkTheather.php?FilmTitle=<?=$FilmTitle?>&logged_UserId=<?=$logged_UserId?>&logged_Name=<?=$logged_Name?>&BackAddr=wrk_filmsupply.php'">SMS</a>
                 <a OnClick="window.open('wrk_film_Gongji.php?FilmTitle=<?=$FilmTitle?>','','status=0,menubar=0, scrollbars=yes,resizable=yes,width=450,height=600')">공지</a>
                 <br>
                 <?
             }
             ?>
             <br>
             <?

             $sQuery = "Select * From MofidyLimitDate " ;
             $qry_MdfLmtDat = mysql_query($sQuery,$connect) ;
             if  ($MdfLmtDat_data = mysql_fetch_array($qry_MdfLmtDat) )
             {
                 $MdfLmtDat = $MdfLmtDat_data["Value"] ;
             }
             else
             {
                 $MdfLmtDat = 0 ;
             }
             ?>
             <li> <b><a href="MofidyLimitDate.php?logged_UserId=<?=$logged_UserId?>&logged_Name=<?=$logged_Name?>&BackAddr=wrk_filmsupply.php">PHP수정일자 설정(<?=$MdfLmtDat?>)</a></b><br>
             <br>
             <li> <b><a href="<?=$PHP_SELF?>?ClearAcc=Yes">메모리 비우기</a></b><br>
             <BR>

             <br><br>
           </ol>
           </td>
        </tr>
        </table>



        <b><a href="wrk_filmsupply_Link.php?logged_UserId=<?=$logged_UserId?>&WorkDate=<?=$WorkDate?>&BackAddr=wrk_filmsupply.php">일일보고서</a></b><br>



     </center>
  </body>

</html>

<?
    }

     mysql_close($connect);
?>