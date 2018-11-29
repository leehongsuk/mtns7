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


        $timestamp1 = mktime(0,0,0,substr($ToDate,4,2),substr($ToDate,6,2),substr($ToDate,0,4));
        $timestamp2 = mktime(0,0,0,substr($FromDate,4,2),substr($FromDate,6,2),substr($FromDate,0,4));

		$dur_day    =  ($timestamp1 - $timestamp2) / 86400;  // 일수
?>
<link rel=stylesheet href=./LinkStyle.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
    <title>전국극장 순위</title>

    <script>
    //
    // 엑셀 출력
    //
    function toexel_click()
    {
        botttomaddr = 'wrk_filmsupply_Link_DnX.php?'
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

		<b>전국극장 순위</b>
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

		<br><br>

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
		<table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">

		<tr height=25>
			<td class=textarea bgcolor=#ffe4b5 width=40 align=center>
			순위
			</td>

			<td class=textarea bgcolor=#ffe4b5 width=50 align=center>
			지역
			</td>

			<td class=textarea bgcolor=#ffe4b5 width=120 align=center>
			극장명
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

			<td class=textarea width=120 bgcolor=#ffe4b5 class=tbltitle align=center>
			&nbsp;금액&nbsp;
			</td>

			<td class=textarea width=120 bgcolor=#ffe4b5 class=tbltitle align=center>
			&nbsp;부금&nbsp;
			</td>
		</tr>
		</table>

		<?
		if  ($FilmTile != "") // 특정영화만 선택하여 보고자 할 경우..
		{
		   $sFilmTileCont = " And Singo.Open = '".$FilmOpen."'  \n".
							" And Singo.Film = '".$FilmCode."'  \n" ;
		}

		$sQuery = "Select   Singo.Location,                             \n".
					"       Singo.Theather,                             \n".
					"       Singo.Open,                                 \n".
					"       Singo.Film,                                 \n".
					"       Sum( NumPersons ) SumNumPersons             \n".
					"  From ".$sSingoName."     As Singo                \n".
					" Where Singo.SingoDate  >= '".$FromDate."'         \n".
					"   And Singo.SingoDate  <= '".$ToDate."'           \n".
					$sCondition."                                       \n".
					" Group By Singo.Theather,                          \n".
					"          Singo.Open,                              \n".
					"          Singo.Film                               \n".
					" Order By SumNumPersons DESC                       \n" ; //eq($sQuery) ;
		$QrySingo = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

		$affected_row = (mysql_affected_rows() + 1) ;

		include "wrk_filmsupply_Link_DnX1.php";
		?>


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
