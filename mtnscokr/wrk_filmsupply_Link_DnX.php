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
		include "config.php";        // {[������ ���̽�]} : ȯ�漳��

        $connect = dbconn() ;        // {[������ ���̽�]} : ����

        mysql_select_db($cont_db,$connect) ;  // {[������ ���̽�]} : �����


        ////////////////////////////////
        $bEq       = 0 ;
        $bTmpQuery = 0 ;
        trace_init($connect) ;
        /////////////////////////////////////


        $timestamp1 = mktime(0,0,0,substr($ToDate,4,2),substr($ToDate,6,2),substr($ToDate,0,4));
        $timestamp2 = mktime(0,0,0,substr($FromDate,4,2),substr($FromDate,6,2),substr($FromDate,0,4));

		$dur_day    =  ($timestamp1 - $timestamp2) / 86400;  // �ϼ�
?>
<link rel=stylesheet href=./LinkStyle.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
    <title>�������� ����</title>

    <script>
    //
    // ���� ���
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

        // Ư�������� ���������� ������ �� ���
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

		<b>�������� ����</b>
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
		<!-- ���ν��ھ� ���� -->
		<!--                 -->

		<?
		$FilmOpen = substr($FilmTile,0,6) ;
		$FilmCode = substr($FilmTile,6,2) ;

		$TblTheatherRate = get_theather_rate($FilmOpen,$FilmCode,$connect) ; //�ʸ��� ������ ������ ���̺��� ���� �� ���Ѵ�.

		//echo    $FilmOpen.":".$FilmCode;
		$sSingoName     = get_singotable($FilmOpen,$FilmCode,$connect) ;  // �Ű� ���̺� �̸�..
		$sShowroomorder = get_showroomorder($FilmOpen,$FilmCode,$connect) ;

		$sQuery = "Select * From bas_filmtitle    \n".
				  " Where Open = '".$FilmOpen."'  \n".
				  "   And Code = '".$FilmCode."'  \n" ;
		$qryfilmtitle = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
		if  ($filmtitle_data = mysql_fetch_array($qryfilmtitle))
		{
			// ��ȭ�������
			?>
			<center>

			   <table name=score cellpadding=0 cellspacing=0 border=1 bordercolor="#FFFFFF" width=100%>
			   <tr>

				   <td align=left class=textare>
				   ������:(<?=substr($FilmTile,0,2)?>/<?=substr($FilmTile,2,2)?>/<?=substr($FilmTile,4,2)?>)
				   </td>

				   <!-- ��ȭ������� -->
				   <td align=center ><b><?=$filmtitle_data["Name"]?></b></td>

				   <?
				   $Ttimestamp2 = mktime(0,0,0,substr($FilmTile,2,2),substr($FilmTile,4,2),"20".substr($FilmTile,0,2));
				   $Tdur_time2  = (time() - $timestamp2) / 86400;

				   $Ttimestamp1 = mktime(0,0,0,substr($WorkDate,4,2),substr($WorkDate,6,2),substr($WorkDate,0,4));
				   $Tdur_time1  = (time() - $timestamp1) / 86400;

				   $Tdur_day    = $Tdur_time2 - $Tdur_time1;  // �ϼ�
				   ?>

				   <td align=right>
				   �����Ϸ� ���� <?=($Tdur_day+1)?>��°..
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
			����
			</td>

			<td class=textarea bgcolor=#ffe4b5 width=50 align=center>
			����
			</td>

			<td class=textarea bgcolor=#ffe4b5 width=120 align=center>
			�����
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
			&nbsp;�հ�&nbsp;
			</td>

			<td class=textarea width=120 bgcolor=#ffe4b5 class=tbltitle align=center>
			&nbsp;�ݾ�&nbsp;
			</td>

			<td class=textarea width=120 bgcolor=#ffe4b5 class=tbltitle align=center>
			&nbsp;�α�&nbsp;
			</td>
		</tr>
		</table>

		<?
		if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
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
    else // �α������� �ʰ� �ٷε��´ٸ�..
    {
        ?>

        <!-- �α������� �ʰ� �ٷε��´ٸ� -->
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
