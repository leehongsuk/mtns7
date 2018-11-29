<? 
    session_start();

    if ($ToExel) 
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

        mysql_select_db($cont_db) ;  // {[데이터 베이스]} : 디비선택


        //
        // 그해 그월의 몇번째 주일의 첨일자를 구한다. (토요일부터 - 가장핵심)
        //
        function Get_Week_Date( $years, $months, $week )
        {
            $oneday    = 60 * 60 * 24 ; // 하루를 초단위로 나타냄.
            
            $basedate  = mktime(0,0,0,1,1,$years) ; // 그해 1월1일을 구한다.
            $baseweek  = date("w", $basedate) ;     // 그해 1월1일의 요일을 구한다.

            $basedate  = $basedate - ($baseweek * $oneday) ; // 실제적인 기준일을 구한다.        
            
            $offset1   = date("W", mktime(0,0,0,$months,1,$years)) ; // 그해 그달 1일..

            $startdate = $basedate + (($offset1 + $week-1) * 7 * $oneday ) - $oneday ;

            return $startdate ;
        }

        $timestamp2 = Get_Week_Date( $YearDate, $MonthDate, $WeekDate ) ;

        $FromDate = date("Ymd",$timestamp2) ;
        $ToDate   = date("Ymd",$timestamp2 + 6 * (3600*24) ) ;

        $dur_day = 6 ;
?>

<link rel=stylesheet href=./LinkStyle.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>주간단위 현황</title>
</head>

<body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
  
  <script>
     //
     // 엑셀 출력
     //
     function toexel_click()
     {
           botttomaddr = 'wrk_filmsupply_Link_DnF.php?'
                       + 'FilmTile=<?=$FilmTile?>&'
                       + 'logged_UserId=<?=$logged_UserId?>&'
                       + 'LocationCode=<?=$LocationCode?>&'
                       + 'ZoneCode=<?=$ZoneCode?>&'
                       + 'YearDate=<?=$YearDate?>&'
                       + 'MonthDate=<?=$MonthDate?>&'
                       + 'WeekDate=<?=$WeekDate?>' ;

           top.frames.bottom.location.href = botttomaddr ;
     }

  </script>

  <center>
  <br><br>				 
  <b>주간단위 현황</b>
  <a href="javascript: window.print();"><img src="print.gif" width="32" height="32" border="0"></a> 
  <a href=# onclick="toexel_click();"><img src="exel.gif" width="32" height="32" border="0"></a>
  </center>

  <form method=post name=write action="wrk_fiulmsupply_X.php?BackAddr=wrk_silmooja.php" onsubmit="return check_submit()">

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
	  $qryfilmtitle = mysql_query($sQuery,$connect) ;
	  if  ($filmtitle_data = mysql_fetch_array($qryfilmtitle))
	  {
       // 영화제목출력 
       ?>
       <center>

           <table name=score cellpadding=0 cellspacing=0 border=1 bordercolor="#FFFFFF" width=100%>
           <tr>
           
           <!-- 
           <td align=left class=textare>
           개봉일:(<?=substr($FilmTile,0,2)?>/<?=substr($FilmTile,2,2)?>/<?=substr($FilmTile,4,2)?>)
           </td>
           -->
           
           <td align=center >
           <!-- 영화제목출력 -->
           <b><?=$filmtitle_data["Name"]?></b>            
           </td>           
           
           </tr>
           </table>

		     </center>

       <?
	  }
	  ?>
	  <br>


   <center>
   
   <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">
   
   <tr>
   <td class=textarea bgcolor=#ffe4b5 width=50 align=center>
   지역
   </td>

   <td class=textarea bgcolor=#ffe4b5 width=120 align=center>
   극장명
   </td>
   
   <td class=textarea bgcolor=#ffe4b5 width=50 align=center>
   좌석수
   </td>
   
   <td class=textarea bgcolor=#ffe4b5 width=50 align=center>
   요금
   </td>

   <? 
   for ($i=0 ; $i <= $dur_day ; $i++)
   {       
       $WeekDay=date("w", $timestamp2 + ($i * 86400));
       if  ($WeekDay==0)  $WeekChar = "일" ;
       if  ($WeekDay==1)  $WeekChar = "월" ;
       if  ($WeekDay==2)  $WeekChar = "화" ;
       if  ($WeekDay==3)  $WeekChar = "수" ;
       if  ($WeekDay==4)  $WeekChar = "목" ;
       if  ($WeekDay==5)  $WeekChar = "금" ;
       if  ($WeekDay==6)  $WeekChar = "토" ;
   ?>
       <td class=textarea width=50 bgcolor=#ffe4b5 class=tbltitle align=center>
       &nbsp;<?=date("m/d",$timestamp2 + ($i * 86400)) ;?>(<?=$WeekChar?>)&nbsp;
       </td>
   <?
   } 
   ?>
   <td class=textarea width=60 bgcolor=#ffe4b5 class=tbltitle align=center>
   &nbsp;합계&nbsp;
   </td>
   
   <td class=textarea width=80 bgcolor=#ffe4b5 class=tbltitle align=center>
   &nbsp;금액&nbsp;
   </td>
   
   <td class=textarea width=60 bgcolor=#ffe4b5 class=tbltitle align=center>
   &nbsp;총 누계&nbsp;
   </td>

   <td class=textarea width=100 bgcolor=#ffe4b5 class=tbltitle align=center>
   &nbsp;총 금액&nbsp;
   </td>

   </tr>

  
   <?   
   $AddedCont = "" ;
   
   $SumSeat       = 0 ;
   $AccNumPersons = 0 ;
   $AccTotAmount  = 0 ;
   
   
   for ($i=0 ; $i<=($dur_day+1) ; $i++)
   {
       $arrySumNumPersons[$i] = 0 ;
   }
   
   if  (((!$LocationCode) && (!$ZoneCode)) || ($ZoneCode=="9999"))  // 전체지역
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
       
       include "wrk_filmsupply_Link_DnE1.php" ; 
       
       
       
       //-----------
       // 경기출력
       //-----------
       $zoneName   = "경기" ;
       $qryzoneloc = mysql_query("Select Location from bas_filmsupplyzoneloc  ".
                                 " Where Zone = '04'                          ",$connect) ;

       $AddedLoc = " And " ;           

       while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
       {            
            if  ($AddedLoc == " And ")
            {
                $AddedLoc .= "( Singo.Location = '".$zoneloc_data["Location"]."' "  ;
            }
            else
            {
                $AddedLoc .= " or Singo.Location = '".$zoneloc_data["Location"]."' "  ;
            }
       }           
       if  ($AddedLoc != " And ")
       {
           $AddedLoc .= ")" ;
       }
       else
       {
           $AddedLoc = "" ;
       }              

       if  ($AddedLoc != "") // 해당하는 자료가 있는경우..
       { 
           if  ($FilmTile != "") // 특정영화만 선택하여 보고자 할 경우..
           {
               $AddedLoc .= " And Singo.Open = '".$FilmOpen."'  ".
                            " And Singo.Film = '".$FilmCode."'  " ;
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
                                    $AddedLoc                                         .
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

           include "wrk_filmsupply_Link_DnE1.php" ; 
       }
       
       
       
       
       //-----------
       // 부산 출력
       //-----------
       $zoneName  = "부산" ;
       $AddedCont = " And  Singo.Location = '200' " ;

       if  ($FilmTile != "") // 특정영화만 선택하여 보고자 할 경우..
       {
           $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                         " And Singo.Film = '".$FilmCode."'  " ;
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
       
       include "wrk_filmsupply_Link_DnE1.php" ; 
       

       //-----------
       // 경강 출력
       //-----------
       $zoneName  = "경강" ;
       $query1    = mysql_query("Select * From bas_filmsupplyzoneloc   ".
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
               $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                             " And Singo.Film = '".$FilmCode."'  " ;
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
           
           include "wrk_filmsupply_Link_DnE1.php" ; 
       }

       //-----------
       // 충청 출력
       //-----------
       $zoneName  = "충청" ;
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
               $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                             " And Singo.Film = '".$FilmCode."'  " ;
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
           
           include "wrk_filmsupply_Link_DnE1.php" ; 
       }

       //-----------
       // 경남 출력
       //-----------
       $zoneName  = "경남" ;
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
           
           include "wrk_filmsupply_Link_DnE1.php" ; 
       }

       //-----------
       // 경북 출력
       //-----------
       $zoneName  = "경북" ;
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
               $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                             " And Singo.Film = '".$FilmCode."'  " ;
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

           include "wrk_filmsupply_Link_DnE1.php" ; 
       }

       //-----------
       // 호남 출력
       //-----------
       $zoneName  = "호남" ;
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
               $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                             " And Singo.Film = '".$FilmCode."'  " ;
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

           include "wrk_filmsupply_Link_DnE1.php" ; 
       }

       
       
       
       //-----------
       // 지방출력
       //-----------
       $zoneName   = "지방" ;
       $qryzoneloc = mysql_query("Select Location from bas_filmsupplyzoneloc  ".
                                 " Where Zone = '04'                          ",$connect) ;

       $AddedLoc = " And " ;           

       while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
       {            
            if  ($AddedLoc == " And ")
            {
                $AddedLoc .= "( Singo.Location <> '".$zoneloc_data["Location"]."' "  ;
            }
            else
            {
                $AddedLoc .= " and Singo.Location <> '".$zoneloc_data["Location"]."' "  ;
            }
       }           
       if  ($AddedLoc != " And ")
       {
           $AddedLoc .= " and Singo.Location <> '100' "  ; // 서울
           $AddedLoc .= " and Singo.Location <> '200' "  ; // 부산
           $AddedLoc .= ")" ;
       }
       else
       {
           $AddedLoc = "" ;
       }              

       // 경기 + 서울 + 부산 을 제외한 나머지를 지방으로 한다.           

       if  ($AddedLoc != "") // 해당하는 자료가 있는경우..
       { 
           if  ($FilmTile != "") // 특정영화만 선택하여 보고자 할 경우..
           {
               $AddedLoc .= " And Singo.Open = '".$FilmOpen."'  ".
                            " And Singo.Film = '".$FilmCode."'  " ;
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
                                    $AddedLoc                                        .
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

           include "wrk_filmsupply_Link_DnE1.php" ; 
       }           
       
       
       
       ?>
       
       <!--일자별총합계-->
       <tr>
            <td class=textarea bgcolor=#ffebcd align=center colspan=2>총합계</td>
            <td class=textarea bgcolor=#ffebcd align=right>&nbsp;<?=number_format($SumSeat)?>&nbsp;</td>
            <td class=textarea bgcolor=#ffebcd align=right>&nbsp;</td>
            <? 
            for ($i=0 ; $i<=$dur_day ; $i++)
            {
                 $objDate = date("Ymd",$timestamp2 + ($i * 86400)) ;
                 
                 $qry_singo2 = mysql_query("Select Sum(NumPersons) As SumNumPersons    ".
                                           "  From ".$sSingoName." As Singo            ".
                                           " Where Singo.SingoDate  = '".$objDate."'   ".
                                           "   And Singo.Open       = '".$FilmOpen."'  ".
                                           "   And Singo.Film       = '".$FilmCode."'  ",$connect) ;
                 $NumPersons_data = mysql_fetch_array($qry_singo2) ;
                 if  ($NumPersons_data)
                 {
                     ?>
                     <td class=textarea bgcolor=#ffebcd align=right>&nbsp;<?=number_format($NumPersons_data["SumNumPersons"])?>&nbsp;</td>
                     <?
                 }
                 else
                 {                     
                     ?>
                     <td class=textarea bgcolor=#ffebcd align=center>-</td>
                     <?
                 }
            } 
            
            $qry_singo2 = mysql_query("Select Sum(NumPersons) As SumNumPersons     ".
                                      "  From ".$sSingoName." As Singo             ".
                                      " Where Singo.SingoDate  >= '".$FromDate."'  ".
                                      "   And Singo.SingoDate  <= '".$ToDate."'    ".
                                      "   And Singo.Open        = '".$FilmOpen."'  ".
                                      "   And Singo.Film        = '".$FilmCode."'  ",$connect) ;
            $NumPersons_data = mysql_fetch_array($qry_singo2) ;
            if  ($NumPersons_data)
            {
                ?>
                <td class=textarea bgcolor=#ffebcd align=right>&nbsp;<?=number_format($NumPersons_data["SumNumPersons"])?>&nbsp;</td>
                <?
            }
            else
            {                     
                ?>
                <td class=textarea bgcolor=#ffebcd align=center>-</td>
                <?
            }
            ?>
                <td class=textarea bgcolor=#ffebcd align=right>&nbsp;<?=number_format($AccNumPersons)?>&nbsp;</td>
                <td class=textarea bgcolor=#ffebcd align=right>&nbsp;<?=number_format($AccTotAmount)?>&nbsp;</td>
       </tr>
   <?
   }
   else
   {
       // 특정지역만 선택적으로 보고자 할 경우
       if  (($LocationCode) && ($LocationCode!=""))
       {
           $qryzone = mysql_query("Select * From bas_location        ".
                                  " Where Code = '".$LocationCode."' ",$connect) ;


           $zone_data = mysql_fetch_array($qryzone) ;
           if  ($zone_data)
           { 
               $zoneName = $zone_data["Name"] ;
           }

           $AddedCont = " and  Singo.Location = '".$LocationCode."' " ;
       }

       // 특정구역만 선택적으로 보고자 할 경우
       if  (($ZoneCode) && ($ZoneCode!=""))
       {
          $qryzone = mysql_query("Select * From bas_zone          ".
                                 " Where Code = '".$ZoneCode."'   ",$connect) ;


          $zone_data = mysql_fetch_array($qryzone) ;
          if  ($zone_data)
          { 
              $zoneName = $zone_data["Name"] ;
          }

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
               $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                             " And Singo.Film = '".$FilmCode."'  " ;
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

           $filmtitleNameTitle = "" ;

           $affected_row = mysql_affected_rows() ;

           include "wrk_filmsupply_Link_DnE1.php" ; 
       }
   }
   ?>
   

   </table>   
   </center>
   <br>
   <br>

   </form>

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
