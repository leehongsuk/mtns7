<?
  session_start();

  if  ($ToExel)
  {
      header("Content-type: application/vnd.ms-excel");
      header("Content-Disposition: attachment; filename=excel_name.xls");
      header("Content-Description: GamZa Excel Data");
  }
?>

<html>

<?
    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";        // {[데이터 베이스]} : 환경설정

        $connect = dbconn() ;        // {[데이터 베이스]} : 연결

        mysql_select_db($cont_db,$connect) ;  // {[데이터 베이스]} : 디비선택


        ////////////////////////////////
        $bEq       = 0 ;
        $bTmpQuery = 0 ;
        trace_init($connect) ;
        /////////////////////////////////////


        $timestamp2 = mktime(0,0,0,substr($FromDate,4,2),substr($FromDate,6,2),substr($FromDate,0,4));
        $dur_time2  = (time() - $timestamp2) / 86400;

        $timestamp1 = mktime(0,0,0,substr($ToDate,4,2),substr($ToDate,6,2),substr($ToDate,0,4));
        $dur_time1  = (time() - $timestamp1) / 86400;

        $dur_day    = $dur_time2 - $dur_time1;  // 일수
?>
<link rel=stylesheet href=./LinkStyle.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>극장별 부금정산</title>
</head>

<body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

  <script>
     //
     // 엑셀 출력
     //
     function toexel_click()
     {
          botttomaddr = 'wrk_filmsupply_Link_DnO.php?'
                      + 'FilmTile=<?=$FilmTile?>&'
                      + 'logged_UserId=<?=$logged_UserId?>&'
                      + 'LocationCode=<?=$LocationCode?>&'
                      + 'FromDate=<?=$FromDate?>&'
                      + 'ToDate=<?=$ToDate?>&'
                      + 'ToExel=Yes' ;

          top.frames.bottom.location.href = botttomaddr ;
     }

  </script>

  <center>
  <br><br>
  <b>극장별 부금정산</b>
  <?
  if  (!$ToExel)
  {
  ?>
  <a href="javascript: window.print();"><img src="print.gif" width="32" height="32" border="0"></a>
  <a href=# onclick="toexel_click();"><img src="exel.gif" width="32" height="32" border="0"></a>
  <?
  }
  ?>


  <form method=post name=write action="wrk_fiulmsupply_X.php?BackAddr=wrk_silmooja.php" onsubmit="return check_submit()">

  <br>
  <br>

   <!--                 -->
   <!-- 세부스코어 집계 -->
   <!--                 -->


	  <?
   $FilmOpen = substr($FilmTile,0,6) ;
   $FilmCode = substr($FilmTile,6,2) ;

   $sSingoName     = get_singotable($FilmOpen,$FilmCode,$connect) ;  // 신고 테이블 이름..
   $sShowroomorder = get_showroomorder($FilmOpen,$FilmCode,$connect) ;

	  $sQuery = "Select * From bas_filmtitle    ".
             " Where Open = '".$FilmOpen."'  ".
             "   And Code = '".$FilmCode."'  " ;
   $qryfilmtitle = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
	  if  ($filmtitle_data = mysql_fetch_array($qryfilmtitle))
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
   <?
   for ($i=0 ; $i<6 ; $i++)
   {
       $TotSums[$i]  = 0 ; //
   }
   ?>

   <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">
   <tr height=25>
       <td class=textarea width=50 bgcolor=#ffe4b5 align=center>
       지역
       </td>

       <td class=textarea width=120 bgcolor=#ffe4b5 align=center>
       극장명
       </td>

       <td class=textarea width=60 bgcolor=#ffe4b5 align=center>
       종영일
       </td>

       <td class=textarea width=50 bgcolor=#ffe4b5 align=center>
       부율
       </td>

       <td class=textarea width=60 bgcolor=#ffe4b5 class=tbltitle align=center>
       인원
       </td>

       <td class=textarea width=90 bgcolor=#ffe4b5 class=tbltitle align=center>
       금액(입장료)
       </td>

       <td class=textarea width=90 bgcolor=#ffe4b5 class=tbltitle align=center>
       기금제외금액
       </td>

       <td class=textarea width=100 bgcolor=#ffe4b5 class=tbltitle align=center>
       공급가액
       </td>

       <td class=textarea width=70 bgcolor=#ffe4b5 class=tbltitle align=center>
       부가세
       </td>

       <td class=textarea width=100 bgcolor=#ffe4b5 class=tbltitle align=center>
       영화사 입금액
       </td>
   </tr>

   </table>

   <?
   $AddedCont = "" ;

   $SumSeat = 0 ;
   $AccRate = 0 ;
   $AccNumPersons = 0 ;
   $AccTotAmount = 0 ;

   for ($i=0 ; $i<=($dur_day+2) ; $i++)
   {
       $arrySumNumPersons[$i] = 0 ;
   }

   if  ((!$LocationCode) && (!$ZoneCode))  // 전체지역
   {
       //-----------
       // 서울 출력
       //-----------
       $zoneName  = "서울" ;
       $AddedCont = " And  Singo.Location = '100' " ;

       if  ($FilmTile != "") // 특정영화만 선택하여 보고자 할 경우..
       {
           $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                         " And Singo.Film = '".$FilmCode."'  " ;
       }
       $sQuery = "Select distinct                                    ".
                 "       ShowroomOrder.Seq,                          ".
                 "       Singo.Theather,                             ".
                 "       Singo.Open,                                 ".
                 "       Singo.Film                                  ".
                 "  From ".$sSingoName."   As Singo,                 ".
                 "       ".$sShowroomorder." As ShowroomOrder        ".
                 " Where Singo.SingoDate  >= '".$FromDate."'         ".
                 "   And Singo.SingoDate  <= '".$ToDate."'           ".
                 "   And Singo.Theather   = ShowroomOrder.Theather   ".
                 $AddedCont                                           .
                 " Group By Singo.Theather,                          ".
                 "          Singo.Open,                              ".
                 "          Singo.Film                               ".
                 " Order By ShowroomOrder.Seq,                       ".
                 "          Singo.Open,                              ".
                 "          Singo.Film,                              ".
                 "          Singo.Theather                           " ;
       $qry_singo = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

       $affected_row = (mysql_affected_rows() + 1) ;

       include "wrk_filmsupply_Link_DnO1.php";



       //-----------
       // 경기출력
       //-----------
       $zoneName   = "경기" ;
       $sQuery = "Select Location from bas_filmsupplyzoneloc ".
                 " Where Zone = '04'                         " ;
       $qryzoneloc = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

       $AddedLoc = " And " ;

       while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
       {
            if  ($AddedLoc == " And ")
                $AddedLoc .= "( Singo.Location = '".$zoneloc_data["Location"]."' "  ;
            else
                $AddedLoc .= " or Singo.Location = '".$zoneloc_data["Location"]."' "  ;
       }
       $AddedLoc .= ")" ;

       if  ($AddedLoc != "") // 해당하는 자료가 있는경우..
       {
           if  ($FilmTile != "") // 특정영화만 선택하여 보고자 할 경우..
           {
               $AddedLoc .= " And Singo.Open = '".$FilmOpen."'  ".
                            " And Singo.Film = '".$FilmCode."'  " ;
           }
           $sQuery = "Select distinct                                    ".
                     "       ShowroomOrder.Seq,                          ".
                     "       Singo.Theather,                             ".
                     "       Singo.Open,                                 ".
                     "       Singo.Film                                  ".
                     "  From ".$sSingoName."   As Singo,                 ".
                     "       ".$sShowroomorder." As ShowroomOrder        ".
                     " Where Singo.SingoDate  >= '".$FromDate."'         ".
                     "   And Singo.SingoDate  <= '".$ToDate."'           ".
                     "   And Singo.Theather   = ShowroomOrder.Theather   ".
                     $AddedLoc                                            .
                     " Group By Singo.Theather,                          ".
                     "          Singo.Open,                              ".
                     "          Singo.Film                               ".
                     " Order By ShowroomOrder.Seq,                       ".
                     "          Singo.Open,                              ".
                     "          Singo.Film,                              ".
                     "          Singo.Theather                           " ;
           $qry_singo = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

           $affected_row = (mysql_affected_rows() + 1) ;

           include "wrk_filmsupply_Link_DnO1.php";
       }




       //-----------
       // 부산 출력
       //-----------
       $zoneName  = "부산" ;
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
           $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                         " And Singo.Film = '".$FilmCode."'  " ;
       }
       $sQuery = "Select distinct                                    ".
                 "       ShowroomOrder.Seq,                          ".
                 "       Singo.Theather,                             ".
                 "       Singo.Open,                                 ".
                 "       Singo.Film                                  ".
                 "  From ".$sSingoName."   As Singo,                 ".
                 "       ".$sShowroomorder." As ShowroomOrder        ".
                 " Where Singo.SingoDate  >= '".$FromDate."'         ".
                 "   And Singo.SingoDate  <= '".$ToDate."'           ".
                 "   And Singo.Theather   = ShowroomOrder.Theather   ".
                 $AddedCont                                           .
                 " Group By Singo.Theather,                          ".
                 "          Singo.Open,                              ".
                 "          Singo.Film                               ".
                 " Order By ShowroomOrder.Seq,                       ".
                 "          Singo.Open,                              ".
                 "          Singo.Film,                              ".
                 "          Singo.Theather                           " ;
       $qry_singo = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;


       $affected_row = (mysql_affected_rows() + 1) ;

       include "wrk_filmsupply_Link_DnO1.php";


       //-----------
       // 경강 출력
       //-----------
       $zoneName  = "경강" ;
       $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                 " Where Zone  = '10'                   " ;
       $query1    = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

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
               $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                             " And Singo.Film = '".$FilmCode."'  " ;
           }

           $sQuery = "Select distinct                                    ".
                     "       ShowroomOrder.Seq,                          ".
                     "       Singo.Theather,                             ".
                     "       Singo.Open,                                 ".
                     "       Singo.Film                                  ".
                     "  From ".$sSingoName."   As Singo,                 ".
                     "       ".$sShowroomorder." As ShowroomOrder        ".
                     " Where Singo.SingoDate  >= '".$FromDate."'         ".
                     "   And Singo.SingoDate  <= '".$ToDate."'           ".
                     "   And Singo.Theather   = ShowroomOrder.Theather   ".
                     $AddedCont                                           .
                     " Group By Singo.Theather,                          ".
                     "          Singo.Open,                              ".
                     "          Singo.Film                               ".
                     " Order By ShowroomOrder.Seq,                       ".
                     "          Singo.Open,                              ".
                     "          Singo.Film,                              ".
                     "          Singo.Theather                           " ;
           $qry_singo = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

           $affected_row = (mysql_affected_rows() + 1) ;

           include "wrk_filmsupply_Link_DnO1.php";
       }
       //-----------
       // 충청 출력
       //-----------
       $zoneName  = "충청" ;
       $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                 " Where Zone  = '35'                   " ;
       $query1    = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

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
               $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                             " And Singo.Film = '".$FilmCode."'  " ;
           }

           $sQuery = "Select distinct                                    ".
                     "       ShowroomOrder.Seq,                          ".
                     "       Singo.Theather,                             ".
                     "       Singo.Open,                                 ".
                     "       Singo.Film                                  ".
                     "  From ".$sSingoName."   As Singo,                 ".
                     "       ".$sShowroomorder." As ShowroomOrder        ".
                     " Where Singo.SingoDate  >= '".$FromDate."'         ".
                     "   And Singo.SingoDate  <= '".$ToDate."'           ".
                     "   And Singo.Theather   = ShowroomOrder.Theather   ".
                     $AddedCont                                           .
                     " Group By Singo.Theather,                          ".
                     "          Singo.Open,                              ".
                     "          Singo.Film                               ".
                     " Order By ShowroomOrder.Seq,                       ".
                     "          Singo.Open,                              ".
                     "          Singo.Film,                              ".
                     "          Singo.Theather                           " ;
           $qry_singo = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

           $affected_row = (mysql_affected_rows() + 1) ;

           include "wrk_filmsupply_Link_DnO1.php";
       }
       //-----------
       // 경남 출력
       //-----------
       $zoneName  = "경남" ;
       $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                 " Where Zone  = '20'                   " ;
       $query1    = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

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
           //$AddedCont .= " Or Singo.Location = '200' " ;
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
               $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                             " And Singo.Film = '".$FilmCode."'  " ;
           }

           $sQuery = "Select distinct                                    ".
                     "       ShowroomOrder.Seq,                          ".
                     "       Singo.Theather,                             ".
                     "       Singo.Open,                                 ".
                     "       Singo.Film                                  ".
                     "  From ".$sSingoName."   As Singo,                 ".
                     "       ".$sShowroomorder." As ShowroomOrder        ".
                     " Where Singo.SingoDate  >= '".$FromDate."'         ".
                     "   And Singo.SingoDate  <= '".$ToDate."'           ".
                     "   And Singo.Theather   = ShowroomOrder.Theather   ".
                     $AddedCont                                           .
                     " Group By Singo.Theather,                          ".
                     "          Singo.Open,                              ".
                     "          Singo.Film                               ".
                     " Order By ShowroomOrder.Seq,                       ".
                     "          Singo.Open,                              ".
                     "          Singo.Film,                              ".
                     "          Singo.Theather                           " ;
           $qry_singo = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

           $affected_row = (mysql_affected_rows() + 1) ;

           include "wrk_filmsupply_Link_DnO1.php";
       }
       //-----------
       // 경북 출력
       //-----------
       $zoneName  = "경북" ;
       $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                 " Where Zone  = '21'                   " ;
       $query1    = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

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
               $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                             " And Singo.Film = '".$FilmCode."'  " ;
           }

           $sQuery = "Select distinct                                    ".
                     "       ShowroomOrder.Seq,                          ".
                     "       Singo.Theather,                             ".
                     "       Singo.Open,                                 ".
                     "       Singo.Film                                  ".
                     "  From ".$sSingoName."   As Singo,                 ".
                     "       ".$sShowroomorder." As ShowroomOrder        ".
                     " Where Singo.SingoDate  >= '".$FromDate."'         ".
                     "   And Singo.SingoDate  <= '".$ToDate."'           ".
                     "   And Singo.Theather   = ShowroomOrder.Theather   ".
                     $AddedCont                                           .
                     " Group By Singo.Theather,                          ".
                     "          Singo.Open,                              ".
                     "          Singo.Film                               ".
                     " Order By ShowroomOrder.Seq,                       ".
                     "          Singo.Open,                              ".
                     "          Singo.Film,                              ".
                     "          Singo.Theather                           " ;
           $qry_singo = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

           $affected_row = (mysql_affected_rows() + 1) ;

           include "wrk_filmsupply_Link_DnO1.php";
       }

       //-----------
       // 호남 출력
       //-----------
       $zoneName  = "호남" ;
       $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                 " Where Zone  = '50'                   " ;
       $query1    = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

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
               $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                             " And Singo.Film = '".$FilmCode."'  " ;
           }

           $sQuery = "Select distinct                                    ".
                     "       ShowroomOrder.Seq,                          ".
                     "       Singo.Theather,                             ".
                     "       Singo.Open,                                 ".
                     "       Singo.Film                                  ".
                     "  From ".$sSingoName."   As Singo,                 ".
                     "       ".$sShowroomorder." As ShowroomOrder        ".
                     " Where Singo.SingoDate  >= '".$FromDate."'         ".
                     "   And Singo.SingoDate  <= '".$ToDate."'           ".
                     "   And Singo.Theather   = ShowroomOrder.Theather   ".
                     $AddedCont                                           .
                     " Group By Singo.Theather,                          ".
                     "          Singo.Open,                              ".
                     "          Singo.Film                               ".
                     " Order By ShowroomOrder.Seq,                       ".
                     "          Singo.Open,                              ".
                     "          Singo.Film,                              ".
                     "          Singo.Theather                           " ;
           $qry_singo = mysql_query($sQuery,$connect)  or die(ee($sQuery)) ;

           $affected_row = (mysql_affected_rows() + 1) ;

           include "wrk_filmsupply_Link_DnO1.php";
       }




       //-----------
       // 지방출력
       //-----------
       $zoneName   = "지방" ;
       $sQuery = "Select Location from bas_filmsupplyzoneloc  ".
                 " Where Zone = '04'                          " ;
       $qryzoneloc = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

       $AddedLoc = " And " ;

       while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
       {
            if  ($AddedLoc == " And ")
                $AddedLoc .= "( Singo.Location <> '".$zoneloc_data["Location"]."' "  ;
            else
                $AddedLoc .= " and Singo.Location <> '".$zoneloc_data["Location"]."' "  ;
       }
       $AddedLoc .= " and Singo.Location <> '100' "  ; // 서울
       $AddedLoc .= " and Singo.Location <> '200' "  ; // 부산
       $AddedLoc .= " and Singo.Location <> '203' "  ; // 통영
       $AddedLoc .= " and Singo.Location <> '600' "  ; // 울산
       $AddedLoc .= " and Singo.Location <> '207' "  ; // 김해
       $AddedLoc .= " and Singo.Location <> '205' "  ; // 진주
       $AddedLoc .= " and Singo.Location <> '208' "  ; // 거제
       $AddedLoc .= " and Singo.Location <> '202' "  ; // 마산
       $AddedLoc .= " and Singo.Location <> '211' "  ; // 사천
       $AddedLoc .= " and Singo.Location <> '212' "  ; // 거창
       $AddedLoc .= " and Singo.Location <> '213' "  ; // 양산
       $AddedLoc .= " and Singo.Location <> '201' "  ; // 창원
       $AddedLoc .= ")" ;

       // 경기 + 서울 + 부산 을 제외한 나머지를 지방으로 한다.
       if  ($AddedLoc != "") // 해당하는 자료가 있는경우..
       {
           if  ($FilmTile != "") // 특정영화만 선택하여 보고자 할 경우..
           {
               $AddedLoc .= " And Singo.Open = '".$FilmOpen."'  ".
                            " And Singo.Film = '".$FilmCode."'  " ;
           }

           $sQuery = "Select distinct                                    ".
                     "       ShowroomOrder.Seq,                          ".
                     "       Singo.Theather,                             ".
                     "       Singo.Open,                                 ".
                     "       Singo.Film                                  ".
                     "  From ".$sSingoName."   As Singo,                 ".
                     "       ".$sShowroomorder." As ShowroomOrder        ".
                     " Where Singo.SingoDate  >= '".$FromDate."'         ".
                     "   And Singo.SingoDate  <= '".$ToDate."'           ".
                     "   And Singo.Theather   = ShowroomOrder.Theather   ".
                     $AddedLoc                                            .
                     " Group By Singo.Theather,                          ".
                     "          Singo.Open,                              ".
                     "          Singo.Film                               ".
                     " Order By ShowroomOrder.Seq,                       ".
                     "          Singo.Open,                              ".
                     "          Singo.Film,                              ".
                     "          Singo.Theather                           " ;
           $qry_singo = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

           $affected_row = (mysql_affected_rows() + 1) ;

           include "wrk_filmsupply_Link_DnO1.php";
       }



       ?>

       <!--총합계-->
       <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">
       <tr height=20>
            <td class=textarea bgcolor=#ffebcd width=170 align=center colspan=2>
            총합계
            </td>

            <td class=textarea width=60 bgcolor=#ffebcd align=center>
            <!-- 종영일 -->&nbsp;
            </td>

            <td class=textarea width=50 bgcolor=#ffebcd align=center>
            <!-- 부율 -->&nbsp;
            </td>

            <td class=textarea width=60 bgcolor=#ffebcd class=tbltitle align=right>
            <!-- 인원 --><?=number_format($TotSums[0])?>&nbsp;
            </td>

            <td class=textarea width=90 bgcolor=#ffebcd class=tbltitle align=right>
            <!-- 금액(입장료) --><?=number_format($TotSums[1])?>&nbsp;
            </td>

            <td class=textarea width=90 bgcolor=#ffebcd class=tbltitle align=right>
            <!-- 기금제외금액 --><?=number_format($TotSums[2])?>&nbsp;
            </td>

            <td class=textarea width=100 bgcolor=#ffebcd class=tbltitle align=right>
            <!-- 공급가액 --><?=number_format($TotSums[3])?>&nbsp;
            </td>

            <td class=textarea width=70 bgcolor=#ffebcd class=tbltitle align=right>
            <!-- 부가세 --><?=number_format($TotSums[4])?>&nbsp;
            </td>

            <td class=textarea width=100 bgcolor=#ffebcd class=tbltitle align=right>
            <!-- 영화사 입금액 --><?=number_format($TotSums[5])?>&nbsp;
            </td>

       </tr>
       </table>
   <?
   }

   else
   {
       // 특정지역만 선택적으로 보고자 할 경우
       if  (($LocationCode) && ($LocationCode!=""))
       {
           $sQuery = "Select * From bas_location        ".
                     " Where Code = '".$LocationCode."' " ;
           $qryzone = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
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
           $sQuery = "Select * From bas_zone          ".
                     " Where Code = '".$ZoneCode."'   " ;
           $qryzone = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
           if  ($zone_data = mysql_fetch_array($qryzone))
           {
               $zoneName = $zone_data["Name"] ;
           }

           $sQuery = "Select * From bas_location        ".
                     " Where Code = '".$LocationCode."' " ;
           $qryzone = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
           if  ($zone_data = mysql_fetch_array($qryzone))
           {
               $zoneName = $zone_data["Name"] ;
           }

           $sQuery = "Select * From bas_zone          ".
                     " Where Code = '".$ZoneCode."'   " ;
           $qryzone = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
           if  ($zone_data = mysql_fetch_array($qryzone))
           {
               $zoneName = $zone_data["Name"] ;
           }

           $sQuery = "Select * From bas_filmsupplyzoneloc  ".
                     " Where Zone  = '".$ZoneCode."'       " ;
           $query1 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

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
               $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                             " And Singo.Film = '".$FilmCode."'  " ;
           }

           $sQuery = "Select distinct                                    ".
                     "       ShowroomOrder.Seq,                          ".
                     "       Singo.Theather,                             ".
                     "       Singo.Open,                                 ".
                     "       Singo.Film                                  ".
                     "  From ".$sSingoName."   As Singo,                 ".
                     "       ".$sShowroomorder." As ShowroomOrder        ".
                     " Where Singo.SingoDate  >= '".$FromDate."'         ".
                     "   And Singo.SingoDate  <= '".$ToDate."'           ".
                     "   And Singo.Theather   = ShowroomOrder.Theather   ".
                     $AddedCont                                           .
                     " Group By Singo.Theather,                          ".
                     "          Singo.Open,                              ".
                     "          Singo.Film                               ".
                     " Order By ShowroomOrder.Seq,                       ".
                     "          Singo.Open,                              ".
                     "          Singo.Film,                              ".
                     "          Singo.Theather                           " ;
           $qry_singo = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

           $filmtitleNameTitle = "" ;

           $affected_row = (mysql_affected_rows() + 1) ;

           include "wrk_filmsupply_Link_DnO1.php";
       }
   }
   ?>

   </form>

   </center>

</body>

        <?
        mysql_close($connect);
    }
    else // 로그인하지 않고 바로들어온다면..
    {
        ?>

        <!-- 로그인하지 않고 바로들어온다면 -->
        <body>
            <script language="JavaScript">
                <!--
                window.top.location = '../index_cokr.php' ;
                //-->
            </script>
        </body>

        <?
    }
    ?>
</html>
