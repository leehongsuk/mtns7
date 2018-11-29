<?
	@set_time_limit(0);
	session_cache_expire(3600);
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
    <title>금액별 회계 현황</title>

    <script>
    //
    // 엑셀 출력
    //
    function toexel_click()
    {
        botttomaddr = 'wrk_filmsupply_Link_DnP.php?'
                      + 'WorkGubun=<?=$WorkGubun?>&'
                      + 'FilmTile=<?=$FilmTile?>&'
                      + 'logged_UserId=<?=$logged_UserId?>&'
                      + 'FromDate=<?=$FromDate?>&'
                      + 'ToDate=<?=$ToDate?>&'
                      + 'nFilmTypeNo=<?=$nFilmTypeNo?>&'
                      + 'ToExel=Yes' ;
        <?
        if  (($LocationCode) && ($LocationCode!=""))
        {
            ?>botttomaddr += '&LocationCode=<?=$LocationCode?>';<?
        }

        // 특정구역만 선택적으로 보고자 할 경우
        if  (($ZoneCode) && ($ZoneCode!=""))
        {
            ?>botttomaddr += '&ZoneCode=<?=ZoneCode?>';<?
        }
        ?>

        // alert(botttomaddr);
        top.frames.bottom.location.href = botttomaddr ;
    }
    </script>
</head>

<body bgcolor="#fafafa" topmargin="0" leftmargin="0" marginwidth="0" marginheight="0">

	<center>
	<br><br>

	<b>금액별 회계 현황</b>

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

    $TblTheatherRate = get_theather_rate($FilmOpen,$FilmCode,$connect) ; //필름별 부율을 결정할 테이블을 생성 및 구한다.

	//echo    $FilmOpen.":".$FilmCode;
	$sSingoName     = get_singotable($FilmOpen,$FilmCode,$connect) ;  // 신고 테이블 이름..
	$sShowroomorder = get_showroomorder($FilmOpen,$FilmCode,$connect) ;

	$sQuery = "Select * From bas_filmtitle    \n".
              " Where Open = '".$FilmOpen."'  \n".
              "   And Code = '".$FilmCode."'  \n" ;
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

               <!-- 영화제목출력 -->
               <td align=center ><b><?=$filmtitle_data["Name"]?></b></td>

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
	for ($i=0 ; $i<=($dur_day+6) ; $i++)
	{
		$arryTotHapGea[$i] = 0 ; // 총합계를 구하기 위한 배열..
	}

	$AddedCont = "" ;

	//$AccRate = 0 ;
	//$AccNumPersons = 0 ;
	//$AccTotAmount = 0 ;

	$nNumChongGea = 0 ; // 극장갯수(총합계)

	for ($i=0 ; $i<=($dur_day+2) ; $i++)
	{
		$arrySumNumPersons[$i] = 0 ;
	}
	?>

	<table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">

	<tr height=25>
		<td class=textarea bgcolor=#ffe4b5 width=50 align=center>
		지역
		</td>

		<td class=textarea bgcolor=#ffe4b5 width=120 align=center>
		극장명
		</td>

		<!--
		<td class=textarea bgcolor=#ffe4b5 width=50 align=center>
		스크린
		</td>

		<td class=textarea bgcolor=#ffe4b5 width=50 align=center>
		좌석
		</td>	-->

		<td class=textarea bgcolor=#ffe4b5 width=30 align=center>
		코드
		</td>

		<td class=textarea bgcolor=#ffe4b5 width=50 align=center>
		요금
		</td>

		<?
		for ($i=0 ; $i<=$dur_day ; $i++)
		{
			?>
		   <td class=textarea width=50 bgcolor=#ffe4b5 class=tbltitle align=center>
		   &nbsp;<?=date("m/d",$timestamp2 + ($i * 86400)) ;?>&nbsp;
			</td>
			<?
		}
		?>

		<td class=textarea width=60 bgcolor=#ffe4b5 class=tbltitle align=center>
		&nbsp;합계&nbsp;
		</td>

		<td class=textarea width=90 bgcolor=#ffe4b5 class=tbltitle align=center>
		&nbsp;금액&nbsp;
		</td>

		<td class=textarea width=90 bgcolor=#ffe4b5 class=tbltitle align=center>
		&nbsp;기금적용 금액&nbsp;
		</td>

		<td class=textarea width=100 bgcolor=#ffe4b5 class=tbltitle align=center>
		&nbsp;부금
		</td>

		<td class=textarea width=70 bgcolor=#ffe4b5 class=tbltitle align=center>
		&nbsp;총 누계&nbsp;
		</td>

		<td class=textarea width=100 bgcolor=#ffe4b5 class=tbltitle align=center>
		&nbsp;총 금액&nbsp;
		</td>

	</tr>
	</table>

	<?
	if  ((!$LocationCode) && (!$ZoneCode))  // 전체지역
	{
       if  ($FilmTile != "") // 특정영화만 선택하여 보고자 할 경우..
       {
           $sFilmTileCont = " And Singo.Open = '".$FilmOpen."'  \n".
                            " And Singo.Film = '".$FilmCode."'  \n" ;
       }

       if  ($WorkGubun == 38) // 롯데씨네마
       {
           $sFilmTileCont .= " And Showroom.MultiPlex  = '5' \n" ;
       }
       if  ($WorkGubun == 40) // 메가박스
       {
           $sFilmTileCont .= " And Showroom.MultiPlex  = '3' \n" ;
       }
       if  ($WorkGubun == 51) // CGV
       {
           $sFilmTileCont .= " And Showroom.MultiPlex  = '2' \n" ;
       }
       if  ($WorkGubun == 52) // 기타
       {
           $sFilmTileCont .= " And (Showroom.MultiPlex <> '5' and Showroom.MultiPlex <> '3' and Showroom.MultiPlex <> '2') \n" ;
       }

       $sLocName = array("", "서울", "경기", "부산", "경강", "충청", "경남", "경북", "호남", "지방");

       // 서울
       $sLoc1 = "   And Singo.Location = 100  \n" ;

       // 경기
       $sLoc2 = " And " ;

       $sQuery = "Select Location              ".
                 "  From bas_filmsupplyzoneloc ".
                 " Where Zone = '04'           " ;
       $qryzoneloc = mysql_query($sQuery,$connect) ;
       while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
       {
            if  ($sLoc2 == " And ")
                $sLoc2 .= "( Singo.Location = '".$zoneloc_data["Location"]."' \n"  ;
            else
                $sLoc2 .= " or Singo.Location = '".$zoneloc_data["Location"]."' \n"  ;
       }
       if  ($sLoc2 == " And ") $sLoc2 = "" ;
       else                    $sLoc2 .= ")" ;

       // 부산
       $sLoc3 = " And ( Singo.Location = '200'   \n" . // 부산
                "  or   Singo.Location = '203'   \n" . // 통영
                "  or   Singo.Location = '600'   \n" . // 울산
                "  or   Singo.Location = '207'   \n" . // 김해
                "  or   Singo.Location = '205'   \n" . // 진주
                "  or   Singo.Location = '208'   \n" . // 거제
                "  or   Singo.Location = '202'   \n" . // 마산
                "  or   Singo.Location = '211'   \n" . // 사천
                "  or   Singo.Location = '212'   \n" . // 거창
                "  or   Singo.Location = '213'   \n" . // 양산
                "  or   Singo.Location = '201' ) \n" ; // 창원

       // 경강
       $sLoc4 = " And " ;

       $sQuery = "Select Location              ".
                 "  From bas_filmsupplyzoneloc ".
                 " Where Zone = '10'           " ;
       $qryzoneloc = mysql_query($sQuery,$connect) ;
       while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
       {
            if  ($sLoc4 == " And ")
                $sLoc4 .= "( Singo.Location = '".$zoneloc_data["Location"]."' \n"  ;
            else
                $sLoc4 .= " or Singo.Location = '".$zoneloc_data["Location"]."' \n"  ;
       }
       if  ($sLoc4 == " And ") $sLoc4 = "" ;
       else                    $sLoc4 .= ")" ;

       // 충청
       $sLoc5 = " And " ;

       $sQuery = "Select Location              ".
                 "  From bas_filmsupplyzoneloc ".
                 " Where Zone = '35'           " ;
       $qryzoneloc = mysql_query($sQuery,$connect) ;
       while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
       {
            if  ($sLoc5 == " And ")
                $sLoc5 .= "( Singo.Location = '".$zoneloc_data["Location"]."' \n"  ;
            else
                $sLoc5 .= " or Singo.Location = '".$zoneloc_data["Location"]."' \n"  ;
       }
       if  ($sLoc5 == " And ") $sLoc5 = "" ;
       else                    $sLoc5 .= ")" ;

       // 경남
       $sLoc6 = " And " ;

       $sQuery = "Select Location              ".
                 "  From bas_filmsupplyzoneloc ".
                 " Where Zone = '20'           " ;
       $qryzoneloc = mysql_query($sQuery,$connect) ;
       while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
       {
            if  ($sLoc6 == " And ")
                $sLoc6 .= "( Singo.Location = '".$zoneloc_data["Location"]."' \n"  ;
            else
                $sLoc6 .= " or Singo.Location = '".$zoneloc_data["Location"]."' \n"  ;
       }
       if  ($sLoc6 == " And ") $sLoc6 = "" ;
       else                    $sLoc6 .= ")" ;

       // 경북
       $sLoc7 = " And " ;

       $sQuery = "Select Location              ".
                 "  From bas_filmsupplyzoneloc ".
                 " Where Zone = '21'           " ;
       $qryzoneloc = mysql_query($sQuery,$connect) ;
       while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
       {
            if  ($sLoc7 == " And ")
                $sLoc7 .= "( Singo.Location = '".$zoneloc_data["Location"]."' \n"  ;
            else
                $sLoc7 .= " or Singo.Location = '".$zoneloc_data["Location"]."' \n"  ;
       }
       if  ($sLoc7 == " And ") $sLoc7 = "" ;
       else                    $sLoc7 .= ")" ;

       // 호남
       $sLoc8 = " And " ;

       $sQuery = "Select Location              ".
                 "  From bas_filmsupplyzoneloc ".
                 " Where Zone = '50'           " ;
       $qryzoneloc = mysql_query($sQuery,$connect) ;
       while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
       {
            if  ($sLoc8 == " And ")
                $sLoc8 .= "( Singo.Location = '".$zoneloc_data["Location"]."' \n"  ;
            else
                $sLoc8 .= " or Singo.Location = '".$zoneloc_data["Location"]."' \n"  ;
       }
       if  ($sLoc8 == " And ") $sLoc8 = "" ;
       else                    $sLoc8 .= ")" ;

       //  지방
       $sLoc9 = " And " ;

       $sQuery = "Select Location              ".
                 "  From bas_filmsupplyzoneloc ".
                 " Where Zone = '04'           " ;
       $qryzoneloc = mysql_query($sQuery,$connect) ;
       while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
       {
            if  ($sLoc9 == " And ")
                $sLoc9 .= "( Singo.Location <> '".$zoneloc_data["Location"]."' \n"  ;
            else
                $sLoc9 .= " and Singo.Location <> '".$zoneloc_data["Location"]."' \n"  ;
       }
       if  ($sLoc9 == " And ") $sLoc9 = "(" ;
       $sLoc9 .= " and Singo.Location <> '100' "  ; // 서울
       $sLoc9 .= " and Singo.Location <> '200' "  ; // 부산
       $sLoc9 .= " and Singo.Location <> '203' "  ; // 통영
       $sLoc9 .= " and Singo.Location <> '600' "  ; // 울산
       $sLoc9 .= " and Singo.Location <> '207' "  ; // 김해
       $sLoc9 .= " and Singo.Location <> '205' "  ; // 진주
       $sLoc9 .= " and Singo.Location <> '208' "  ; // 거제
       $sLoc9 .= " and Singo.Location <> '202' "  ; // 마산
       $sLoc9 .= " and Singo.Location <> '211' "  ; // 사천
       $sLoc9 .= " and Singo.Location <> '212' "  ; // 거창
       $sLoc9 .= " and Singo.Location <> '213' "  ; // 양산
       $sLoc9 .= " and Singo.Location <> '201' "  ; // 창원
       $sLoc9 .= ")" ;

       for ($j=1; $j<=9; $j++)
       {
          $sLocCondX = "sLoc".$j ;
          $sLocNameX = $sLocName[$j] ;
          //if  ($$sLocCondX != "") echo $sLocNameX.":[". $$sLocCondX ."]<BR>" ;

          if  ($$sLocCondX != "") // 해당지역에 자료가 있는 경우 에만
          {
              if  ($nFilmTypeNo != 0) // All이 아닐때//.
              {
                  $sCondition = $sFilmTileCont . $$sLocCondX . " And Singo.FilmType = '".$nFilmTypeNo."'  \n" ;

                  $sQuery = "Select distinct                                    \n".
                            "       ShowroomOrder.Seq,                          \n".
                            "       Singo.Location,                             \n".
                            "       Singo.Theather,                             \n".
                            "       Singo.Open,                                 \n".
                            "       Singo.Film,                                 \n".
                            "       Singo.FilmType                              \n".
                            "  From ".$sSingoName."     As Singo,               \n".
                            "       ".$sShowroomorder." As ShowroomOrder,       \n".
                            "       bas_showroom        As Showroom             \n".
                            " Where Singo.SingoDate  >= '".$FromDate."'         \n".
                            "   And Singo.SingoDate  <= '".$ToDate."'           \n".
                            "   And Singo.Theather   = ShowroomOrder.Theather   \n".
                            "   And Singo.Theather   = Showroom.Theather        \n".
                            $sCondition."                                       \n".
                            " Group By Singo.Theather,                          \n".
                            "          Singo.Open,                              \n".
                            "          Singo.Film,                              \n".
                            "          Singo.FilmType                           \n".
                            " Order By ShowroomOrder.Seq,                       \n".
                            "          Singo.Open,                              \n".
                            "          Singo.Film,                              \n".
                            "          Singo.Theather                           \n" ; //eq($sQuery) ;
              }
              else
              {
                  $sCondition = $sFilmTileCont . $$sLocCondX ;

                  $sQuery = "Select distinct                                    \n".
                            "       ShowroomOrder.Seq,                          \n".
                            "       Singo.Location,                             \n".
                            "       Singo.Theather,                             \n".
                            "       Singo.Open,                                 \n".
                            "       Singo.Film                                  \n".
                            "  From ".$sSingoName."     As Singo,               \n".
                            "       ".$sShowroomorder." As ShowroomOrder,       \n".
                            "       bas_showroom        As Showroom             \n".
                            " Where Singo.SingoDate  >= '".$FromDate."'         \n".
                            "   And Singo.SingoDate  <= '".$ToDate."'           \n".
                            "   And Singo.Theather   = ShowroomOrder.Theather   \n".
                            "   And Singo.Theather   = Showroom.Theather        \n".
                            $sCondition."                                       \n".
                            " Group By Singo.Theather,                          \n".
                            "          Singo.Open,                              \n".
                            "          Singo.Film                               \n".
                            " Order By ShowroomOrder.Seq,                       \n".
                            "          Singo.Open,                              \n".
                            "          Singo.Film,                              \n".
                            "          Singo.Theather                           \n" ; //eq($sQuery) ;
              }
              //eq($sQuery) ;

              $QrySingo = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

              $affected_row = (mysql_affected_rows() + 1) ;

              include "wrk_filmsupply_Link_DnP1.php";
          }
       }
	}
	else
	{
       // 특정지역만 선택적으로 보고자 할 경우
       if  (($LocationCode) && ($LocationCode!=""))
       {
           $sQuery = "Select * From bas_location         \n".
                     " Where Code = '".$LocationCode."'  \n" ;
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
           $sQuery = "Select * From bas_zone          \n".
                     " Where Code = '".$ZoneCode."'   \n" ;
           $qryzone = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
           if  ($zone_data = mysql_fetch_array($qryzone))
           {
               $zoneName = $zone_data["Name"] ;
           }

           $sQuery = "Select * From bas_location         \n".
                     " Where Code = '".$LocationCode."'  \n" ;
           $qryzone = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
           if  ($zone_data = mysql_fetch_array($qryzone))
           {
               $zoneName = $zone_data["Name"] ;
           }

           $sQuery = "Select * From bas_zone          \n".
                     " Where Code = '".$ZoneCode."'   \n" ;
           $qryzone = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
           if  ($zone_data = mysql_fetch_array($qryzone))
           {
               $zoneName = $zone_data["Name"] ;
           }

           $sQuery = "Select * From bas_filmsupplyzoneloc  \n".
                     " Where Zone  = '".$ZoneCode."'       \n" ;
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
			if  ($WorkGubun == 38) // 롯데씨네마
			{
               $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
			}
			if  ($WorkGubun == 40) // 메가박스
			{
               $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
			}
			if  ($WorkGubun == 51) // CGV
			{
               $AddedCont .= " And Showroom.MultiPlex  = '2' " ;
			}
			if  ($WorkGubun == 52) // 기타
			{
               $AddedCont .= " And ((Showroom.MultiPlex <> '5') and (Showroom.MultiPlex <> '3')  and (Showroom.MultiPlex <> '2') )" ;
			}

			if  (($WorkGubun == 38) || ($WorkGubun == 40) || ($WorkGubun == 51) || ($WorkGubun == 52)) // 롯데씨네마,  메가박스, CGV, 기타
			{
				if  ($nFilmTypeNo != 0) // All이 아닐때//.
				{
				   $AddedCont .= " And Singo.FilmType = '".$nFilmTypeNo."' \n" ;

				   $sQuery = "Select distinct                                    \n".
							 "       ShowroomOrder.Seq,                          \n".
							 "       Singo.Location,                             \n".
							 "       Singo.Theather,                             \n".
							 "       Singo.Open,                                 \n".
							 "       Singo.Film,                                 \n".
							 "       Singo.FilmType                              \n".
							 "  From ".$sSingoName."   As Singo,                 \n".
							 "       ".$sShowroomorder." As ShowroomOrder,       \n".
							 "       bas_showroom        As Showroom             \n".
							 " Where Singo.SingoDate  >= '".$FromDate."'         \n".
							 "   And Singo.SingoDate  <= '".$ToDate."'           \n".
							 "   And Singo.Theather   = ShowroomOrder.Theather   \n".
							 "   And Singo.Theather   = Showroom.Theather        \n".
							 $AddedCont."                                        \n".
							 " Group By Singo.Theather,                          \n".
							 "          Singo.Open,                              \n".
							 "          Singo.Film,                              \n".
							 "          Singo.FilmType                           \n".
							 " Order By ShowroomOrder.Seq,                       \n".
							 "          Singo.Open,                              \n".
							 "          Singo.Film,                              \n".
							 "          Singo.Theather                           \n" ;
				}
				else
				{
				   $sQuery = "Select distinct                                    \n".
							 "       ShowroomOrder.Seq,                          \n".
							 "       Singo.Location,                             \n".
							 "       Singo.Theather,                             \n".
							 "       Singo.Open,                                 \n".
							 "       Singo.Film                                  \n".
							 "  From ".$sSingoName."   As Singo,                 \n".
							 "       ".$sShowroomorder." As ShowroomOrder,       \n".
							 "       bas_showroom        As Showroom             \n".
							 " Where Singo.SingoDate  >= '".$FromDate."'         \n".
							 "   And Singo.SingoDate  <= '".$ToDate."'           \n".
							 "   And Singo.Theather   = ShowroomOrder.Theather   \n".
							 "   And Singo.Theather   = Showroom.Theather        \n".
							 $AddedCont."                                        \n".
							 " Group By Singo.Theather,                          \n".
							 "          Singo.Open,                              \n".
							 "          Singo.Film                               \n".
							 " Order By ShowroomOrder.Seq,                       \n".
							 "          Singo.Open,                              \n".
							 "          Singo.Film,                              \n".
							 "          Singo.Theather                           \n" ;
				}
			}
			else // 롯데씨네마, 메가박스, CGV, 기타  가 아닌 일반
			{
				if  ($nFilmTypeNo != 0) // All이 아닐때//.
				{
				   $AddedCont .= " And Singo.FilmType = '".$nFilmTypeNo."' \n" ;

				   $sQuery = "Select distinct                                    \n".
							 "       ShowroomOrder.Seq,                          \n".
							 "       Singo.Location,                             \n".
							 "       Singo.Theather,                             \n".
							 "       Singo.Open,                                 \n".
							 "       Singo.Film,                                 \n".
							 "       Singo.FilmType                              \n".
							 "  From ".$sSingoName."   As Singo,                 \n".
							 "       ".$sShowroomorder." As ShowroomOrder        \n".
							 " Where Singo.SingoDate  >= '".$FromDate."'         \n".
							 "   And Singo.SingoDate  <= '".$ToDate."'           \n".
							 "   And Singo.Theather   = ShowroomOrder.Theather   \n".
							 $AddedCont."                                        \n".
							 " Group By Singo.Theather,                          \n".
							 "          Singo.Open,                              \n".
							 "          Singo.Film,                              \n".
							 "          Singo.FilmType                           \n".
							 " Order By ShowroomOrder.Seq,                       \n".
							 "          Singo.Open,                              \n".
							 "          Singo.Film,                              \n".
							 "          Singo.Theather                           \n" ;
				}
				else
				{
				   $sQuery = "Select distinct                                    \n".
							 "       ShowroomOrder.Seq,                          \n".
							 "       Singo.Location,                             \n".
							 "       Singo.Theather,                             \n".
							 "       Singo.Open,                                 \n".
							 "       Singo.Film                                  \n".
							 "  From ".$sSingoName."   As Singo,                 \n".
							 "       ".$sShowroomorder." As ShowroomOrder        \n".
							 " Where Singo.SingoDate  >= '".$FromDate."'         \n".
							 "   And Singo.SingoDate  <= '".$ToDate."'           \n".
							 "   And Singo.Theather   = ShowroomOrder.Theather   \n".
							 $AddedCont."                                        \n".
							 " Group By Singo.Theather,                          \n".
							 "          Singo.Open,                              \n".
							 "          Singo.Film                               \n".
							 " Order By ShowroomOrder.Seq,                       \n".
							 "          Singo.Open,                              \n".
							 "          Singo.Film,                              \n".
							 "          Singo.Theather                           \n" ;
				}
			}
		    //eq($sQuery) ;
			$QrySingo = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

			$filmtitleNameTitle = "" ;

			$affected_row = (mysql_affected_rows() + 1) ;

			include "wrk_filmsupply_Link_DnP1.php";
        }
	}
	?>

	<!--총합계-->
   <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">
   <tr>
		<td class=textarea bgcolor=#ffebcd width=170 align=center colspan=2>
		총합계(<?=$nNumChongGea?>)
		</td>

		<td class=textarea bgcolor=#ffebcd width=30 align=right>
		&nbsp;
		</td>

		<td class=textarea bgcolor=#ffebcd width=50 align=right>
		&nbsp;
		</td>

		<?
		for ($i=0 ; $i<=$dur_day ; $i++)
		{
			?>
			<td class=textarea bgcolor=#ffebcd width=50 align=right>&nbsp;<B><?=number_format($arryTotHapGea[$i])?></B>&nbsp;</td>
			<?
		}
		?>
		<td class=textarea bgcolor=#ffebcd width=60 align=right>&nbsp;<b><?=number_format($arryTotHapGea[$dur_day+1])?></b>&nbsp;</td>
		<td class=textarea bgcolor=#ffebcd width=90 align=right>&nbsp;<b><?=number_format($arryTotHapGea[$dur_day+2])?></b>&nbsp;</td>
		<td class=textarea bgcolor=#ffebcd width=90 align=right>&nbsp;<b><?=number_format($arryTotHapGea[$dur_day+3])?></b>&nbsp;</td>
		<td class=textarea bgcolor=#ffebcd width=100 align=right>&nbsp;<b><?=number_format($arryTotHapGea[$dur_day+4])?></b>&nbsp;</td>
		<td class=textarea bgcolor=#ffebcd width=70 align=right>&nbsp;<b><?=number_format($arryTotHapGea[$dur_day+5])?></b>&nbsp;</td>
		<td class=textarea bgcolor=#ffebcd width=100 align=right>&nbsp;<b><?=number_format($arryTotHapGea[$dur_day+6])?></b>&nbsp;</td>
   </tr>
   </table>

	<br>
	<br>

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
