<?
  session_start();

  if  ($ToExel)
  {
      header("Content-type: application/vnd.ms-excel");
      header("Content-Disposition: attachment; filename=excel_name.xls");
      header("Content-Description: GamZa Excel Data");
  }

  if ($ToExel)
  {
      $ColorA =  '#ffffff' ;
      $ColorB =  '#ffffff' ;
      $ColorC =  '#ffffff' ;
      $ColorD =  '#ffffff' ;
  }
  else
  {
      $ColorA =  '#ffebcd' ;
      $ColorB =  '#dcdcec' ;
      $ColorC =  '#dcdcdc' ;
      $ColorD =  '#c0c0c0' ;
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
<title>그룹별 현황</title>
</head>

<body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

  <script>
     //
     // 엑셀 출력
     //
     function toexel_click()
     {
          <?
          if ($FromDate)
          {
          ?>
          botttomaddr = 'wrk_filmsupply_Link_DnT.php?'
                      + 'FilmTile=<?=$FilmTile?>&'
                      + 'logged_UserId=<?=$logged_UserId?>&'
                      + 'FromDate=<?=$FromDate?>&'
                      + 'ToDate=<?=$ToDate?>&'
                      + 'ToExel=Yes' ;
          <?
          }
          else
          {
          ?>
          botttomaddr = 'wrk_filmsupply_Link_DnT.php?'
                      + 'FilmTile=<?=$FilmTile?>&'
                      + 'logged_UserId=<?=$logged_UserId?>&'
                      + 'WorkDate=<?=$WorkDate?>&'
                      + 'ToExel=Yes' ;
          <?
          }
          ?>
          top.frames.bottom.location.href = botttomaddr ;
     }

  </script>

  <center>
  <br><br>
  <b>그룹별 현황<? if ($FromDate) {echo "(기간별)"; } else {echo "(일별)"; } ?></b>
  <?
  if  (!$ToExel)
  {
  ?>
  <a href="javascript: window.print();"><img src="print.gif" width="32" height="32" border="0"></a>
  <a href=# onclick="toexel_click();"><img src="exel.gif" width="32" height="32" border="0"></a>
  <?
  }
  ?>


  <br>
  <br>

   <?
   $FilmOpen = substr($FilmTile,0,6) ;
   $FilmCode = substr($FilmTile,6,2) ;
   ?>
   <center>
               <table name=score cellpadding=0 cellspacing=0 border=1 bordercolor="#FFFFFF" width=100%>
               <tr>


               <td align=center colspan=19>
               <?

               $filmtitleName = get_titlename($FilmOpen,$FilmCode,$connect) ;
               ?>
               <!-- 영화제목출력 -->
               <b><?=$filmtitleName?></b>

               <?
               if ($FromDate)
               {
                   echo substr($FromDate,0,4)."-".substr($FromDate,4,2)."-".substr($FromDate,6,2) ;
                   echo " ~ " ;
                   echo substr($ToDate,0,4)."-".substr($ToDate,4,2)."-".substr($ToDate,6,2) ;
               }
               else
               {
                   echo substr($WorkDate,0,4)."-".substr($WorkDate,4,2)."-".substr($WorkDate,6,2) ;
               }


               if ($ToExel)
               {
               ?>
                   <BR><?=$filmExcelTitle?>
               <?
               }
               ?>
               </td>


               </tr>
               </table>

   <table name=score cellpadding=0 cellspacing=0  border=1 bordercolor='#C0B0A0' width=500>
       <tr>
           <td align=center bgcolor=<?=$ColorA?>>구분</td>
           <td align=center bgcolor=<?=$ColorA?>>서울</td>
           <td align=center bgcolor=<?=$ColorA?>>경기</td>
           <td align=center bgcolor=<?=$ColorA?>>부산</td>
           <td align=center bgcolor=<?=$ColorA?>>지방</td>
           <td align=center bgcolor=<?=$ColorA?>>전체</td>
           <td align=center bgcolor=<?=$ColorA?>>점유율</td>
       </tr>
   <?


   $sSingoName = get_singotable($FilmOpen,$FilmCode,$connect) ;  // 신고 테이블 이름..

   if ($FromDate)
   {
       $TermCont = " Singo.Singodate >= '".$FromDate."' and Singo.Singodate <= '".$ToDate."' " ;
   }
   else
   {
       $TermCont = " Singo.Singodate  = '".$WorkDate."' " ;
   }


   $TermCont .= " and Singo.Open = '".$FilmOpen."' And Singo.Film = '".$FilmCode."' " ;

   $ArrMultiPlex = array ("2","3","5","6","4","1") ;
   $ArrNames = array ("CGV","메가박스","롯데","프리머스","시너스","일반") ;

   //foreach( $ArrMultiPlex as $key => $value)
   //{  echo "$key : $value <BR>";  }


   /*
   $sQuery = "Select Showroom.MultiPlex,                      ".
             "       Sum(Singo.NumPersons) As SumNumPersons   ".
             "  From ".$sSingoName."   As Singo,              ".
             "       bas_showroom      As Showroom            ".
             " Where ".$TermCont."                            ".
             "   And Showroom.MultiPlex <> ''                 ".
             " Group By Showroom.MultiPlex                    " ;
   $QrySaingo = mysql_query($sQuery,$connect) ;
   while ($ArrSaingo = mysql_fetch_array($QrySaingo))
   */

   $sQuery = "Select Sum(Singo.NumPersons) As SumNumPersons           ".
             "  From ".$sSingoName."   As Singo,                      ".
             "       bas_showroom      As Showroom                    ".
             " Where ".$TermCont."                                    ".
             "   And Showroom.Theather  =  Singo.Theather             ".
             "   And Showroom.Room      =  Singo.Room                 " ;
   $QrySaingo = mysql_query($sQuery,$connect) ;
   if ($ArrSaingo = mysql_fetch_array($QrySaingo))
   {
       $TotalNumPersons = $ArrSaingo["SumNumPersons"] ;
   }

   $i = 0 ;
   foreach( $ArrMultiPlex as $key => $singoMultiPlex)
   {
        $sQuery = "Select Sum(Singo.NumPersons) As SumNumPersons           ".
                  "  From ".$sSingoName."   As Singo,                      ".
                  "       bas_showroom      As Showroom                    ".
                  " Where ".$TermCont."                                    ".
                  "   And Showroom.Theather  =  Singo.Theather             ".
                  "   And Showroom.Room      =  Singo.Room                 ".
                  "   And Showroom.MultiPlex = '".$singoMultiPlex."'     " ;
        $QrySaingo = mysql_query($sQuery,$connect) ;
        if ($ArrSaingo = mysql_fetch_array($QrySaingo))
        {
            $singoSumNumPersons = number_format($ArrSaingo["SumNumPersons"]) ;
            $SumNumPersons = $ArrSaingo["SumNumPersons"] ;
        }



        //-----------
        // 서울 출력
        //-----------
        $AddedLoc = " And  Singo.Location = '100'  " ;

        $sQuery = "Select Sum(Singo.NumPersons) As SumNumPersons     ".
                  "  From ".$sSingoName."   As Singo,                ".
                  "       bas_showroom      As Showroom              ".
                  " Where ".$TermCont."                              ".
                  "   And Showroom.Theather  =  Singo.Theather       ".
                  "   And Showroom.Room      =  Singo.Room           ".
                  $AddedLoc.
                  "   And Showroom.MultiPlex = '".$singoMultiPlex."' " ;
//if  ($ArrNames[$i]=="CGV") eq( $sQuery ) ;
        $QrySingo = mysql_query($sQuery,$connect) ;
        if  ($ArrSingo = mysql_fetch_array($QrySingo))
        {
            $SumNumPersonsA = number_format($ArrSingo["SumNumPersons"]) ;
        }
        else
        {
            $SumNumPersonsA = "&nbsp;" ;
        }

        //-----------
        // 경기출력
        //-----------
        $AddedLoc = " And " ;

        $sQuery = "select Location from bas_filmsupplyzoneloc  ".
                  " Where Zone = '04'                          " ;
        $qryzoneloc = mysql_query($sQuery,$connect) ;
        while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
        {
             if  ($AddedLoc == " And ")
                 $AddedLoc .= "( Singo.Location = '".$zoneloc_data["Location"]."' "  ;
             else
                 $AddedLoc .= " or Singo.Location = '".$zoneloc_data["Location"]."' "  ;
        }
        $AddedLoc .= ")" ;


        $sQuery = "Select Sum(Singo.NumPersons) As SumNumPersons     ".
                  "  From ".$sSingoName."   As Singo,                ".
                  "       bas_showroom      As Showroom              ".
                  " Where ".$TermCont."                              ".
                  "   And Showroom.Theather  =  Singo.Theather       ".
                  "   And Showroom.Room      =  Singo.Room           ".
                  $AddedLoc.
                  "   And Showroom.MultiPlex = '".$singoMultiPlex."' " ;
//eq($sQuery);
        $QrySingo = mysql_query($sQuery,$connect) ;
        if  ($ArrSingo = mysql_fetch_array($QrySingo))
        {
            $SumNumPersonsB = number_format($ArrSingo["SumNumPersons"]) ;
        }
        else
        {
            $SumNumPersonsB = "&nbsp;" ;
        }

        //-----------
        // 부산 출력
        //-----------
        $AddedLoc = " And ( Singo.Location = '200'   " . // 부산
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

        $sQuery = "Select Sum(Singo.NumPersons) As SumNumPersons     ".
                  "  From ".$sSingoName."   As Singo,                ".
                  "       bas_showroom      As Showroom              ".
                  " Where ".$TermCont."                              ".
                  "   And Showroom.Theather  =  Singo.Theather             ".
                  "   And Showroom.Room      =  Singo.Room                 ".
                  $AddedLoc.
                  "   And Showroom.MultiPlex = '".$singoMultiPlex."' " ;
        $QrySingo = mysql_query($sQuery,$connect) ;
        if  ($ArrSingo = mysql_fetch_array($QrySingo))
        {
            $SumNumPersonsC = number_format($ArrSingo["SumNumPersons"]) ;
        }
        else
        {
            $SumNumPersonsC = "&nbsp;" ;
        }


        //-----------
        // 지방출력
        //-----------
        $AddedLoc = " And " ;

        $sQuery = "select Location from bas_filmsupplyzoneloc ".
                  " Where Zone = '04'                         " ;
        $qryzoneloc = mysql_query($sQuery,$connect) ;
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

        $sQuery = "Select Sum(Singo.NumPersons) As SumNumPersons     ".
                  "  From ".$sSingoName."   As Singo,                ".
                  "       bas_showroom      As Showroom              ".
                  " Where ".$TermCont."                              ".
                  "   And Showroom.Theather  =  Singo.Theather       ".
                  "   And Showroom.Room      =  Singo.Room           ".
                  $AddedLoc.
                  "   And Showroom.MultiPlex = '".$singoMultiPlex."' " ;
        $QrySingo = mysql_query($sQuery,$connect) ;
        if  ($ArrSingo = mysql_fetch_array($QrySingo))
        {
            $SumNumPersonsD = number_format($ArrSingo["SumNumPersons"]) ;
        }
        else
        {
            $SumNumPersonsD = "&nbsp;" ;
        }

        if ($singoMultiPlex==null) $singoMultiPlex="&nbsp;" ;
        ?>
        <tr>
            <td align=center bgcolor=<?=$ColorC?>><?=$ArrNames[$i]?>&nbsp;</td>
            <td align=right  bgcolor=<?=$ColorC?>><?=$SumNumPersonsA?>&nbsp;</td>
            <td align=right  bgcolor=<?=$ColorC?>><?=$SumNumPersonsB?>&nbsp;</td>
            <td align=right  bgcolor=<?=$ColorC?>><?=$SumNumPersonsC?>&nbsp;</td>
            <td align=right  bgcolor=<?=$ColorC?>><?=$SumNumPersonsD?>&nbsp;</td>
            <td align=right  bgcolor=<?=$ColorC?>><?=$singoSumNumPersons?>&nbsp;</td>
            <td align=right  bgcolor=<?=$ColorC?>><?=round(($SumNumPersons/$TotalNumPersons)*100.,2)?>%&nbsp;</td>
        </tr>
        <?

        $i ++ ;
   }
   ?>
   </table>

   <br>
   <br>
   <br>


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
