<?
    session_start();

    if  ($ToExel)
    {
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=excel_name.xls");
        header("Content-Description: GamZa Excel Data");
    }



    // 정상적으로 로그인 했는지 체크한다.
    if ((!$logged_UserId) || ($logged_UserId==""))
    {
       echo "<script language='JavaScript'>window.location = 'index.php'</script>";
    }
    else
    {

        include "config.php";

        if (!$FromDate)
        {
            $FromDate = date("Ymd",$Today) ;
        }
        if (!$ToDate)
        {
            $ToDate = date("Ymd",$Today) ;
        }

        $connect=dbconn();

        mysql_select_db($cont_db) ; // 해당배급사를 구하고


        $FilmOpen = substr($FilmTile,0,6) ;
        $FilmCode = substr($FilmTile,6,2) ;

        $sSingoName     = get_singotable($FilmOpen,$FilmCode,$connect) ;  // 신고 테이블 이름..
        $sShowroomorder = get_showroomorder($FilmOpen,$FilmCode,$connect) ;

        $timestamp2 = mktime(0,0,0,substr($FromDate,4,2),substr($FromDate,6,2),substr($FromDate,0,4));
        $dur_time2  = (time() - $timestamp2) / 86400;

        $timestamp1 = mktime(0,0,0,substr($ToDate,4,2),substr($ToDate,6,2),substr($ToDate,0,4));
        $dur_time1  = (time() - $timestamp1) / 86400;

        $dur_day    = $dur_time2 - $dur_time1;  // 일수
?>

<html>

<link rel=stylesheet href=./LinkStyle.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>일일 보고서</title>
</head>

<body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

   <script>
         function check_submit()
         {
            return true;
         }

         //
         // 엑셀 출력
         //
         function toexel_click()
         {
             botttomaddr = 'wrk_filmsupply_Link_DnB.php?'
                         + 'FilmTile=<?=$FilmTile?>&'
                         + 'logged_UserId=<?=$logged_UserId?>&'
                         + 'LocationCode=<?=$LocationCode?>&'
                         + 'ZoneCode=<?=$ZoneCode?>&'
                         + 'FromDate=<?=$FromDate?>&'
                         + 'ToDate=<?=$ToDate?>&'
                         + 'ToExel=Yes' ;

             location.href = botttomaddr ;
         }
   </script>

   <center>
   <br><br>
   <b>일자별현황</b>
   <a href="javascript: window.print();"><img src="print.gif" width="32" height="32" border="0"></a>
   <a href=# onclick="toexel_click();"><img src="exel.gif" width="32" height="32" border="0"></a>


   <form method=post name=write action="wrk_fiulmsupply_X.php?BackAddr=wrk_silmooja.php" onsubmit="return check_submit()">


   <br>

   <!--                 -->
   <!-- 세부스코어 집계 -->
   <!--                 -->


   <?
   $qryfilmtitle = mysql_query("Select * From bas_filmtitle        ".
                               " Where Open = '".$FilmOpen."'  ".
                               "   And Code = '".$FilmCode."'  ",$connect) ;

   $filmtitle_data = mysql_fetch_array($qryfilmtitle) ;
   if  ($filmtitle_data)
   {
       // 영화제목출력
       ?>
       <center>

           <table name=score cellpadding=0 cellspacing=0 border=1 bordercolor="#FFFFFF" width=100%>
           <tr>

           <td align=left class=textare>
           개봉일:(<?=substr($FilmTile,0,2)?>/<?=substr($FilmTile,2,2)?>/<?=substr($FilmTile,4,2)?>)
           </td>

           <td align=center >
           <!-- 영화제목출력 -->
           <b><?=$filmtitle_data["Name"]?></b>
           </td>


           <?

           $Ttimestamp2 = mktime(0,0,0,substr($FilmTile,2,2),substr($FilmTile,4,2),"20".substr($FilmTile,0,2));
           $Tdur_time2  = (time() - $timestamp2) / 86400;

           $Ttimestamp1 = mktime(0,0,0,substr($WorkDate,4,2),substr($WorkDate,6,2),substr($WorkDate,0,4));
           $Tdur_time1  = (time() - $timestamp1) / 86400;

           $Tdur_day    = $Tdur_time2 - $Tdur_time1;  // 일수

           ?>

           <td align=right>
           개봉일로 부터 <?=($Tdur_day+1)?>일째..
           </td>

           </tr>
           </table>

		     </center>
       <?
   }
   ?>
  <br>

   <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor=#C0B0A0>

       <tr height=25>

       <td class=textarea bgcolor=#ffe4b5 width=50 align=center>지역</td>
       <td class=textarea bgcolor=#ffe4b5 width=130 align=center>극장명</td>
       <td class=textarea bgcolor=#ffe4b5 width=50 align=center>좌석수</td>
       <?
       for ($i=0 ; $i<=$dur_day ; $i++)
       {
       ?>
          <td class=textarea width=50 bgcolor=#ffe4b5 class=tbltitle align=center>&nbsp;<?=date("m/d",$timestamp2 + ($i * 86400)) ;?>&nbsp;</td>
       <?
       }
       ?>
       <td class=textarea width=60 bgcolor=#ffe4b5 class=tbltitle align=center>&nbsp;합계&nbsp;</td>
       <td class=textarea width=80 bgcolor=#ffe4b5 class=tbltitle align=center>&nbsp;금액&nbsp;</td>
       <td class=textarea width=60 bgcolor=#ffe4b5 class=tbltitle align=center>&nbsp;총 누계&nbsp;</td>
       <td class=textarea width=100 bgcolor=#ffe4b5 class=tbltitle align=center>&nbsp;총 금액&nbsp;</td>

       </tr>

       <?
       $AddedCont = "" ;

       $SumSeat = 0 ;

       for ($i=0 ; $i<=($dur_day+3) ; $i++)
       {
           $arrySumNumPersons[$i] = 0 ;  // 배열구성..
       }


       if  (((!$LocationCode) && (!$ZoneCode)) || ($ZoneCode=="9999")) // 전체지역
       {
           //-----------
           // 서울 출력
           //-----------
           $AddedCont = " And  Singo.Location = '100' " ;

           if  ($FilmTile != "") // 특정영화만 선택하여 보고자 할 경우..
           {
               if   ($FilmCode=='00')
               {
                    $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
               }
               else
               {
                    $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                   " And Singo.Film = '".$FilmCode."' " ;
               }
           }

           $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                    "       Singo.Theather,                          ".
                                    "       Singo.Room,                              ".
                                    "       Singo.Open,                              ".
                                    "       Singo.Film,                              ".
                                    "       Showroom.Discript                        ".
                                    "  From ".$sSingoName."   As Singo,              ".
                                    "       ".$sShowroomorder." As ShowroomOrder,    ".
                                    "       bas_showroom      As Showroom            ".
                                    " Where Singo.SingoDate  >= '".$FromDate."'      ".
                                    "   And Singo.SingoDate  <= '".$ToDate."'        ".
                                    "   And Singo.Theather   = Showroom.Theather     ".
                                    "   And Singo.Room       = Showroom.Room         ".
                                    "   And Singo.Theather   = ShowroomOrder.Theather".
                                    "   And Singo.Room       = ShowroomOrder.Room    ".
                                    $AddedCont                                        .
                                    " Group By Singo.Theather,                       ".
                                    "          Singo.Room,                           ".
                                    "          Singo.Open,                           ".
                                    "          Singo.Film,                           ".
                                    "          Showroom.Discript                     ".
                                    " Order By ShowroomOrder.Seq,                    ".
                                    "          Showroom.Discript,                    ".
                                    "          Singo.Open,                           ".
                                    "          Singo.Film,                           ".
                                    "          Singo.Theather,                       ".
                                    "          Singo.Room                            ",$connect) ;

           $affected_row = mysql_affected_rows() ;

           include "wrk_filmsupply_Link_DnB1.php";



           //-----------
           // 경기 출력
           //-----------
           $query1    = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                    " Where Zone  = '04'                   ",$connect) ;

           $AddedCont = " And " ;

           while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
           {
               if  ($AddedCont == " And ")
               {
                   $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
               else
               {
                   $AddedCont .= " Or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
           }

           if  ($AddedCont != " And ")
           {
               $AddedCont .= ")" ;
           }
           else
           {
               $AddedCont = "" ;
           }

           if  ($AddedCont != "") // 지방지역에 해당하는 자료가 있는경우..
           {
               if  ($FilmTile != "") // 특정영화만 선택하여 보고자 할 경우..
               {
                   if   ($FilmCode=='00')
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
                   }
                   else
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                       " And Singo.Film = '".$FilmCode."' " ;
                   }
               }

               $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                        "       Singo.Theather,                          ".
                                        "       Singo.Room,                              ".
                                        "       Singo.Open,                              ".
                                        "       Singo.Film,                              ".
                                        "       Showroom.Discript                        ".
                                        "  From ".$sSingoName."   As Singo,              ".
                                        "       ".$sShowroomorder." As ShowroomOrder,    ".
                                        "       bas_showroom      As Showroom            ".
                                        " Where Singo.SingoDate  >= '".$FromDate."'      ".
                                        "   And Singo.SingoDate  <= '".$ToDate."'        ".
                                        "   And Singo.Theather   = Showroom.Theather     ".
                                        "   And Singo.Room       = Showroom.Room         ".
                                        "   And Singo.Theather   = ShowroomOrder.Theather".
                                        "   And Singo.Room       = ShowroomOrder.Room    ".
                                        $AddedCont                                        .
                                        " Group By Singo.Theather,                       ".
                                        "          Singo.Room,                           ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Showroom.Discript                     ".
                                        " Order By ShowroomOrder.Seq,                    ".
                                        "          Showroom.Discript,                    ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Singo.Theather,                       ".
                                        "          Singo.Room                            ",$connect) ;

               $affected_row = mysql_affected_rows() ;

               include "wrk_filmsupply_Link_DnB1.php";
           }




           //-----------
           // 부산 출력
           //-----------
           $AddedCont = " And ( Singo.Location = '200'   " . // 부산
                        "  or   Singo.Location = '203'   " . // 통영
                        "  or   Singo.Location = '600'   " . // 울산
                        "  or   Singo.Location = '207'   " . // 김해
                        "  or   Singo.Location = '205'   " . // 진주
                        "  or   Singo.Location = '208'   " . // 거제
                        "  or   Singo.Location = '202'   " . // 마산
                        "  or   Singo.Location = '211'   " . // 사천
                        "  or   Singo.Location = '212'   " . // 거창
                        "  or   Singo.Location = '213'   " . // 양산
                        "  or   Singo.Location = '201' ) " ; // 창원

           if  ($FilmTile != "") // 특정영화만 선택하여 보고자 할 경우..
           {
               if   ($FilmCode=='00')
               {
                    $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
               }
               else
               {
                    $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                   " And Singo.Film = '".$FilmCode."' " ;
               }

           }

           $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                    "       Singo.Theather,                          ".
                                    "       Singo.Room,                              ".
                                    "       Singo.Open,                              ".
                                    "       Singo.Film,                              ".
                                    "       Showroom.Discript                        ".
                                    "  From ".$sSingoName."   As Singo,              ".
                                    "       ".$sShowroomorder." As ShowroomOrder,    ".
                                    "       bas_showroom      As Showroom            ".
                                    " Where Singo.SingoDate  >= '".$FromDate."'      ".
                                    "   And Singo.SingoDate  <= '".$ToDate."'        ".
                                    "   And Singo.Theather   = Showroom.Theather     ".
                                    "   And Singo.Room       = Showroom.Room         ".
                                    "   And Singo.Theather   = ShowroomOrder.Theather".
                                    "   And Singo.Room       = ShowroomOrder.Room    ".
                                    $AddedCont                                        .
                                    " Group By Singo.Theather,                       ".
                                    "          Singo.Room,                           ".
                                    "          Singo.Open,                           ".
                                    "          Singo.Film,                           ".
                                    "          Showroom.Discript                     ".
                                    " Order By ShowroomOrder.Seq,                    ".
                                    "          Showroom.Discript,                    ".
                                    "          Singo.Open,                           ".
                                    "          Singo.Film,                           ".
                                    "          Singo.Theather,                       ".
                                    "          Singo.Room                            ",$connect) ;


           $affected_row = mysql_affected_rows() ;

           include "wrk_filmsupply_Link_DnB1.php";


           //-----------
           // 경강 출력
           //-----------
           $query1     = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                     " Where Zone  = '10'                   ",$connect) ;

           $AddedCont = " And " ;
           while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
           {
               if  ($AddedCont == " And ")
               {
                   $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
               else
               {
                   $AddedCont .= " Or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
           }

           if  ($AddedCont != " And ")
           {
               $AddedCont .= ")" ;
           }
           else
           {
               $AddedCont = "" ;
           }


           if  ($AddedCont != "") // 경강지역에 해당하는 자료가 있는경우..
           {
               if  ($FilmTile != "") // 특정영화만 선택하여 보고자 할 경우..
               {
                   if   ($FilmCode=='00')
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
                   }
                   else
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                       " And Singo.Film = '".$FilmCode."' " ;
                   }
               }

               $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                        "       Singo.Theather,                          ".
                                        "       Singo.Room,                              ".
                                        "       Singo.Open,                              ".
                                        "       Singo.Film,                              ".
                                        "       Showroom.Discript                        ".
                                        "  From ".$sSingoName."   As Singo,              ".
                                        "       ".$sShowroomorder." As ShowroomOrder,    ".
                                        "       bas_showroom      As Showroom            ".
                                        " Where Singo.SingoDate  >= '".$FromDate."'      ".
                                        "   And Singo.SingoDate  <= '".$ToDate."'        ".
                                        "   And Singo.Theather   = Showroom.Theather     ".
                                        "   And Singo.Room       = Showroom.Room         ".
                                        "   And Singo.Theather   = ShowroomOrder.Theather".
                                        "   And Singo.Room       = ShowroomOrder.Room    ".
                                        $AddedCont                                        .
                                        " Group By Singo.Theather,                       ".
                                        "          Singo.Room,                           ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Showroom.Discript                     ".
                                        " Order By ShowroomOrder.Seq,                    ".
                                        "          Showroom.Discript,                    ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Singo.Theather,                       ".
                                        "          Singo.Room                            ",$connect) ;

               $affected_row = mysql_affected_rows() ;

               include "wrk_filmsupply_Link_DnB1.php";
           }

           //-----------
           // 충청 출력
           //-----------
           $query1    = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                    " Where Zone  = '35'                   ",$connect) ;

           $AddedCont = " And " ;
           while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
           {
               if  ($AddedCont == " And ")
               {
                   $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
               else
               {
                   $AddedCont .= " Or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
           }

           if  ($AddedCont != " And ")
           {
               $AddedCont .= ")" ;
           }
           else
           {
               $AddedCont = "" ;
           }

           if  ($AddedCont != "") // 충청지역에 해당하는 자료가 있는경우..
           {
               if  ($FilmTile != "") // 특정영화만 선택하여 보고자 할 경우..
               {
                   if   ($FilmCode=='00')
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
                   }
                   else
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                       " And Singo.Film = '".$FilmCode."' " ;
                   }
               }

               $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                        "       Singo.Theather,                          ".
                                        "       Singo.Room,                              ".
                                        "       Singo.Open,                              ".
                                        "       Singo.Film,                              ".
                                        "       Showroom.Discript                        ".
                                        "  From ".$sSingoName."   As Singo,              ".
                                        "       ".$sShowroomorder." As ShowroomOrder,    ".
                                        "       bas_showroom      As Showroom            ".
                                        " Where Singo.SingoDate  >= '".$FromDate."'      ".
                                        "   And Singo.SingoDate  <= '".$ToDate."'        ".
                                        "   And Singo.Theather   = Showroom.Theather     ".
                                        "   And Singo.Room       = Showroom.Room         ".
                                        "   And Singo.Theather   = ShowroomOrder.Theather".
                                        "   And Singo.Room       = ShowroomOrder.Room    ".
                                        $AddedCont                                        .
                                        " Group By Singo.Theather,                       ".
                                        "          Singo.Room,                           ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Showroom.Discript                     ".
                                        " Order By ShowroomOrder.Seq,                    ".
                                        "          Showroom.Discript,                    ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Singo.Theather,                       ".
                                        "          Singo.Room                            ",$connect) ;

               $affected_row = mysql_affected_rows() ;

               include "wrk_filmsupply_Link_DnB1.php";
           }

           //-----------
           // 경남 출력
           //-----------
           $query1    = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                    " Where Zone  = '20'                   ",$connect) ;

           $AddedCont = " And " ;
           while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
           {
               if  ($AddedCont == " And ")
               {
                   $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
               else
               {
                   $AddedCont .= " Or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
           }

           if  ($AddedCont != " And ")
           {
               $AddedCont .= " Or Singo.Location <> '600' " ;
               $AddedCont .= " Or Singo.Location <> '201' " ;
               $AddedCont .= " Or Singo.Location <> '207' " ;
               $AddedCont .= ")" ;
           }
           else
           {
               $AddedCont = "" ;
           }

           if  ($AddedCont != "") // 경남지역에 해당하는 자료가 있는경우..
           {
               if  ($FilmTile != "") // 특정영화만 선택하여 보고자 할 경우..
               {
                   if   ($FilmCode=='00')
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
                   }
                   else
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                       " And Singo.Film = '".$FilmCode."' " ;
                   }
               }

               $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                        "       Singo.Theather,                          ".
                                        "       Singo.Room,                              ".
                                        "       Singo.Open,                              ".
                                        "       Singo.Film,                              ".
                                        "       Showroom.Discript                        ".
                                        "  From ".$sSingoName."   As Singo,              ".
                                        "       ".$sShowroomorder." As ShowroomOrder,    ".
                                        "       bas_showroom      As Showroom            ".
                                        " Where Singo.SingoDate  >= '".$FromDate."'      ".
                                        "   And Singo.SingoDate  <= '".$ToDate."'        ".
                                        "   And Singo.Theather   = Showroom.Theather     ".
                                        "   And Singo.Room       = Showroom.Room         ".
                                        "   And Singo.Theather   = ShowroomOrder.Theather".
                                        "   And Singo.Room       = ShowroomOrder.Room    ".
                                        $AddedCont                                        .
                                        " Group By Singo.Theather,                       ".
                                        "          Singo.Room,                           ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Showroom.Discript                     ".
                                        " Order By ShowroomOrder.Seq,                    ".
                                        "          Showroom.Discript,                    ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Singo.Theather,                       ".
                                        "          Singo.Room                            ",$connect) ;

               $affected_row = mysql_affected_rows() ;

               include "wrk_filmsupply_Link_DnB1.php";
           }

           //-----------
           // 경북 출력
           //-----------
           $query1    = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                    " Where Zone  = '21'                   ",$connect) ;

           $AddedCont = " And " ;
           while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
           {
               if  ($AddedCont == " And ")
               {
                   $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
               else
               {
                   $AddedCont .= " Or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
           }

           if  ($AddedCont != " And ")
           {
               $AddedCont .= ")" ;
           }
           else
           {
               $AddedCont = "" ;
           }

           if  ($AddedCont != "") // 경북지역에 해당하는 자료가 있는경우..
           {
               if  ($FilmTile != "") // 특정영화만 선택하여 보고자 할 경우..
               {
                   if   ($FilmCode=='00')
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
                   }
                   else
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                       " And Singo.Film = '".$FilmCode."' " ;
                   }
               }

               $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                        "       Singo.Theather,                          ".
                                        "       Singo.Room,                              ".
                                        "       Singo.Open,                              ".
                                        "       Singo.Film,                              ".
                                        "       Showroom.Discript                        ".
                                        "  From ".$sSingoName."   As Singo,              ".
                                        "       ".$sShowroomorder." As ShowroomOrder,    ".
                                        "       bas_showroom      As Showroom            ".
                                        " Where Singo.SingoDate  >= '".$FromDate."'      ".
                                        "   And Singo.SingoDate  <= '".$ToDate."'        ".
                                        "   And Singo.Theather   = Showroom.Theather     ".
                                        "   And Singo.Room       = Showroom.Room         ".
                                        "   And Singo.Theather   = ShowroomOrder.Theather".
                                        "   And Singo.Room       = ShowroomOrder.Room    ".
                                        $AddedCont                                        .
                                        " Group By Singo.Theather,                       ".
                                        "          Singo.Room,                           ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Showroom.Discript                     ".
                                        " Order By ShowroomOrder.Seq,                    ".
                                        "          Showroom.Discript,                    ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Singo.Theather,                       ".
                                        "          Singo.Room                            ",$connect) ;

               $affected_row = mysql_affected_rows() ;

               include "wrk_filmsupply_Link_DnB1.php";
           }

           //-----------
           // 호남 출력
           //-----------
           $query1    = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                    " Where Zone  = '50'                   ",$connect) ;

           $AddedCont = " And " ;
           while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
           {
               if  ($AddedCont == " And ")
               {
                   $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
               else
               {
                   $AddedCont .= " Or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
           }

           if  ($AddedCont != " And ")
           {
               $AddedCont .= ")" ;
           }
           else
           {
               $AddedCont = "" ;
           }

           if  ($AddedCont != "") // 호남지역에 해당하는 자료가 있는경우..
           {
               if  ($FilmTile != "") // 특정영화만 선택하여 보고자 할 경우..
               {
                   if   ($FilmCode=='00')
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
                   }
                   else
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                       " And Singo.Film = '".$FilmCode."' " ;
                   }
               }

               $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                        "       Singo.Theather,                          ".
                                        "       Singo.Room,                              ".
                                        "       Singo.Open,                              ".
                                        "       Singo.Film,                              ".
                                        "       Showroom.Discript                        ".
                                        "  From ".$sSingoName."   As Singo,              ".
                                        "       ".$sShowroomorder." As ShowroomOrder,    ".
                                        "       bas_showroom      As Showroom            ".
                                        " Where Singo.SingoDate  >= '".$FromDate."'      ".
                                        "   And Singo.SingoDate  <= '".$ToDate."'        ".
                                        "   And Singo.Theather   = Showroom.Theather     ".
                                        "   And Singo.Room       = Showroom.Room         ".
                                        "   And Singo.Theather   = ShowroomOrder.Theather".
                                        "   And Singo.Room       = ShowroomOrder.Room    ".
                                        $AddedCont                                        .
                                        " Group By Singo.Theather,                       ".
                                        "          Singo.Room,                           ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Showroom.Discript                     ".
                                        " Order By ShowroomOrder.Seq,                    ".
                                        "          Showroom.Discript,                    ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Singo.Theather,                       ".
                                        "          Singo.Room                            ",$connect) ;

               $affected_row = mysql_affected_rows() ;

               include "wrk_filmsupply_Link_DnB1.php";
           }



           //-----------
           // 지방 출력
           //-----------
           $query1    = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                    " Where Zone  = '04'                   ",$connect) ;

           $AddedCont = " And " ;

           while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
           {
               if  ($AddedCont == " And ")
               {
                   $AddedCont .= "( Singo.Location <> '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
               else
               {
                   $AddedCont .= " And Singo.Location <> '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
           }

           if  ($AddedCont != " And ")
           {
               $AddedCont .= " and Singo.Location <> '100' "  ; // 서울
               $AddedCont .= " and Singo.Location <> '200' "  ; // 부산
               $AddedCont .= " and Singo.Location <> '203' "  ; // 통영
               $AddedCont .= " and Singo.Location <> '600' "  ; // 울산
               $AddedCont .= " and Singo.Location <> '207' "  ; // 김해
               $AddedCont .= " and Singo.Location <> '205' "  ; // 진주
			   $AddedCont .= " and Singo.Location <> '208' "  ; // 거제
			   $AddedCont .= " and Singo.Location <> '202' "  ; // 마산
			   $AddedCont .= " and Singo.Location <> '211' "  ; // 사천
			   $AddedCont .= " and Singo.Location <> '212' "  ; // 거창
			   $AddedCont .= " and Singo.Location <> '213' "  ; // 양산
			   $AddedCont .= " and Singo.Location <> '201' "  ; // 창원
               $AddedCont .= ")" ;
           }
           else
           {
               $AddedCont = "" ;
           }

           // 경기 + 서울 + 부산 을 제외한 나머지를 지방으로 한다.

           if  ($AddedCont != "") // 지방지역에 해당하는 자료가 있는경우..
           {
               if  ($FilmTile != "") // 특정영화만 선택하여 보고자 할 경우..
               {
                   if   ($FilmCode=='00')
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
                   }
                   else
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                       " And Singo.Film = '".$FilmCode."' " ;
                   }
               }

               $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                        "       Singo.Theather,                          ".
                                        "       Singo.Room,                              ".
                                        "       Singo.Open,                              ".
                                        "       Singo.Film,                              ".
                                        "       Showroom.Discript                        ".
                                        "  From ".$sSingoName."   As Singo,              ".
                                        "       ".$sShowroomorder." As ShowroomOrder,    ".
                                        "       bas_showroom      As Showroom            ".
                                        " Where Singo.SingoDate  >= '".$FromDate."'      ".
                                        "   And Singo.SingoDate  <= '".$ToDate."'        ".
                                        "   And Singo.Theather   = Showroom.Theather     ".
                                        "   And Singo.Room       = Showroom.Room         ".
                                        "   And Singo.Theather   = ShowroomOrder.Theather".
                                        "   And Singo.Room       = ShowroomOrder.Room    ".
                                        $AddedCont                                        .
                                        " Group By Singo.Theather,                       ".
                                        "          Singo.Room,                           ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Showroom.Discript                     ".
                                        " Order By ShowroomOrder.Seq,                    ".
                                        "          Showroom.Discript,                    ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Singo.Theather,                       ".
                                        "          Singo.Room                            ",$connect) ;

               $affected_row = mysql_affected_rows() ;

               include "wrk_filmsupply_Link_DnB1.php";
           }
           ?>



           <!-- 일자별총합계-지역전체일때만 나온다.-->

           <tr height=20>

           <td class=textarea bgcolor=#ffebcd align=center colspan=2>총합계</td>
           <td class=textarea bgcolor=#ffebcd align=right>&nbsp;<?=number_format($SumSeat)?>&nbsp;</td>
           <?
           for ($i=0 ; $i<(count($arrySumNumPersons)-2) ; $i++)
           {
           ?>
              <td class=textarea bgcolor=#ffebcd class=tbltitle align=right>&nbsp;<?=number_format($arrySumNumPersons[$i])?>&nbsp;</td>
           <?
           }
           ?>
           <td class=textarea bgcolor=#ffebcd class=tbltitle align=right>&nbsp;<?=number_format($arrySumNumPersons[count($arrySumNumPersons)-2])?>&nbsp;</td>
           <td class=textarea bgcolor=#ffebcd class=tbltitle align=right>&nbsp;<?=number_format($arrySumNumPersons[count($arrySumNumPersons)-1])?>&nbsp;</td>

           </tr>

           <?
       }
       else
       {
           // 특정지역만 선택적으로 보고자 할 경우
           if  (($LocationCode) && ($LocationCode!=""))
           {
               //$AddedCont = " and  Singo.Location = '".$LocationCode."' " ;

               $sQuery = "Select * From bas_location        ".
                         " Where Code = '".$LocationCode."' " ;
               $qryzone = mysql_query($sQuery,$connect) ;
               if  ($zone_data = mysql_fetch_array($qryzone))
               {
                   $zoneName = $zone_data["Name"] ;
               }

               if  ($LocationCode=="200")//  부산은 (부산+울산+김해+창원)
               {
                   $AddedCont = " And  (Singo.Location = '200'  ".
                                "    Or Singo.Location = '203'  ".
                                "    Or Singo.Location = '600'  ".
                                "    Or Singo.Location = '207'  ".
                                "    Or Singo.Location = '205'  ".
                                "    Or Singo.Location = '208'  ".
                                "    Or Singo.Location = '202'  ".
                                "    Or Singo.Location = '202'  ".
                                "    Or Singo.Location = '211'  ".
                                "    Or Singo.Location = '212'  ".
                                "    Or Singo.Location = '213'  ".
                                "    Or Singo.Location = '201') " ;
               }
               else
               {
                   $AddedCont = " And  Singo.Location = '".$LocationCode."'  ";
               }

           }

           // 특정구역만 선택적으로 보고자 할 경우
           if  (($ZoneCode) && ($ZoneCode!=""))
           {
               $query1 = mysql_query("Select * From bas_filmsupplyzoneloc  ".
                                     "Where Zone  = '".$ZoneCode."'        ",$connect) ;

               $AddedCont = " And " ;
               while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
               {
                   if  ($AddedCont == " And ")
                   {
                       $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
                   }
                   else
                   {
                       $AddedCont .= " or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
                   }
               }

               if  ($AddedCont != " And ")
               {
                   if  ($ZoneCode == '20') // 경남인경우 부산을 포함한다.
                   {
                        $AddedCont .= " or Singo.Location = '200' " ;
                   }
                   $AddedCont .= ")" ;
               }
               else
               {
                   $AddedCont = "" ;
               }
           }

           if  ($AddedCont != "") // 해당하는 자료가 있는경우..
           {
               if  ($FilmTile != "") // 특정영화만 선택하여 보고자 할 경우..
               {
                   if   ($FilmCode=='00')
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
                   }
                   else
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                       " And Singo.Film = '".$FilmCode."' " ;
                   }
               }

               $sQuery = "Select ShowroomOrder.Seq,                       ".
                         "       Singo.Theather,                          ".
                         "       Singo.Room,                              ".
                         "       Singo.Open,                              ".
                         "       Singo.Film,                              ".
                         "       Showroom.Discript                        ".
                         "  From ".$sSingoName."   As Singo,              ".
                         "       ".$sShowroomorder." As ShowroomOrder,    ".
                         "       bas_showroom      As Showroom            ".
                         " Where Singo.SingoDate  >= '".$FromDate."'      ".
                         "   And Singo.SingoDate  <= '".$ToDate."'        ".
                         "   And Singo.Theather   = Showroom.Theather     ".
                         "   And Singo.Room       = Showroom.Room         ".
                         "   And Singo.Theather   = ShowroomOrder.Theather".
                         "   And Singo.Room       = ShowroomOrder.Room    ".
                         $AddedCont                                        .
                         " Group By Singo.Theather,                       ".
                         "          Singo.Room,                           ".
                         "          Singo.Open,                           ".
                         "          Singo.Film,                           ".
                         "          Showroom.Discript                     ".
                         " Order By ShowroomOrder.Seq,                    ".
                         "          Showroom.Discript,                    ".
                         "          Singo.Open,                           ".
                         "          Singo.Film,                           ".
                         "          Singo.Theather,                       ".
                         "          Singo.Room                            " ;
//eq($sQuery);
               $qry_singo = mysql_query($sQuery,$connect) ;

               $filmtitleNameTitle = "" ;

               $affected_row = mysql_affected_rows() ;

               include "wrk_filmsupply_Link_DnB1.php";
           }
       }
       ?>

   </table>


   </center>

   <br><br>

   </form>

</body>

</html>

<?
        mysql_close($connect);
    }
?>
