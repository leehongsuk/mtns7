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
        include "config.php";        // {[������ ���̽�]} : ȯ�漳��

        $connect = dbconn() ;        // {[������ ���̽�]} : ����

        mysql_select_db($cont_db,$connect) ;  // {[������ ���̽�]} : �����

        ////////////////////////////////
        $bEq       = 0 ;
        $bTmpQuery = 0 ;
        trace_init($connect) ;
        /////////////////////////////////////

        $timestamp2 = mktime(0,0,0,substr($FromDate,4,2),substr($FromDate,6,2),substr($FromDate,0,4));
        $dur_time2  = (time() - $timestamp2) / 86400;

        $timestamp1 = mktime(0,0,0,substr($ToDate,4,2),substr($ToDate,6,2),substr($ToDate,0,4));
        $dur_time1  = (time() - $timestamp1) / 86400;
        $dur_day    = $dur_time2 - $dur_time1;  // �ϼ�
?>
<link rel=stylesheet href=./LinkStyle.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
    <title>�ݾ׺� ȸ�� ��Ȳ</title>

    <script>
    //
    // ���� ���
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

	<b>�ݾ׺� ȸ�� ��Ȳ</b>

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

	<?
	for ($i=0 ; $i<=($dur_day+6) ; $i++)
	{
		$arryTotHapGea[$i] = 0 ; // ���հ踦 ���ϱ� ���� �迭..
	}

	$AddedCont = "" ;

	//$AccRate = 0 ;
	//$AccNumPersons = 0 ;
	//$AccTotAmount = 0 ;

	$nNumChongGea = 0 ; // ���尹��(���հ�)

	for ($i=0 ; $i<=($dur_day+2) ; $i++)
	{
		$arrySumNumPersons[$i] = 0 ;
	}
	?>

	<table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">

	<tr height=25>
		<td class=textarea bgcolor=#ffe4b5 width=50 align=center>
		����
		</td>

		<td class=textarea bgcolor=#ffe4b5 width=120 align=center>
		�����
		</td>

		<!--
		<td class=textarea bgcolor=#ffe4b5 width=50 align=center>
		��ũ��
		</td>

		<td class=textarea bgcolor=#ffe4b5 width=50 align=center>
		�¼�
		</td>	-->

		<td class=textarea bgcolor=#ffe4b5 width=30 align=center>
		�ڵ�
		</td>

		<td class=textarea bgcolor=#ffe4b5 width=50 align=center>
		���
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

		<td class=textarea width=90 bgcolor=#ffe4b5 class=tbltitle align=center>
		&nbsp;�ݾ�&nbsp;
		</td>

		<td class=textarea width=90 bgcolor=#ffe4b5 class=tbltitle align=center>
		&nbsp;������� �ݾ�&nbsp;
		</td>

		<td class=textarea width=100 bgcolor=#ffe4b5 class=tbltitle align=center>
		&nbsp;�α�
		</td>

		<td class=textarea width=70 bgcolor=#ffe4b5 class=tbltitle align=center>
		&nbsp;�� ����&nbsp;
		</td>

		<td class=textarea width=100 bgcolor=#ffe4b5 class=tbltitle align=center>
		&nbsp;�� �ݾ�&nbsp;
		</td>

	</tr>
	</table>

	<?
	if  ((!$LocationCode) && (!$ZoneCode))  // ��ü����
	{
       if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
       {
           $sFilmTileCont = " And Singo.Open = '".$FilmOpen."'  \n".
                            " And Singo.Film = '".$FilmCode."'  \n" ;
       }

       if  ($WorkGubun == 38) // �Ե����׸�
       {
           $sFilmTileCont .= " And Showroom.MultiPlex  = '5' \n" ;
       }
       if  ($WorkGubun == 40) // �ް��ڽ�
       {
           $sFilmTileCont .= " And Showroom.MultiPlex  = '3' \n" ;
       }
       if  ($WorkGubun == 51) // CGV
       {
           $sFilmTileCont .= " And Showroom.MultiPlex  = '2' \n" ;
       }
       if  ($WorkGubun == 52) // ��Ÿ
       {
           $sFilmTileCont .= " And (Showroom.MultiPlex <> '5' and Showroom.MultiPlex <> '3' and Showroom.MultiPlex <> '2') \n" ;
       }

       $sLocName = array("", "����", "���", "�λ�", "�氭", "��û", "�泲", "���", "ȣ��", "����");

       // ����
       $sLoc1 = "   And Singo.Location = 100  \n" ;

       // ���
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

       // �λ�
       $sLoc3 = " And ( Singo.Location = '200'   \n" . // �λ�
                "  or   Singo.Location = '203'   \n" . // �뿵
                "  or   Singo.Location = '600'   \n" . // ���
                "  or   Singo.Location = '207'   \n" . // ����
                "  or   Singo.Location = '205'   \n" . // ����
                "  or   Singo.Location = '208'   \n" . // ����
                "  or   Singo.Location = '202'   \n" . // ����
                "  or   Singo.Location = '211'   \n" . // ��õ
                "  or   Singo.Location = '212'   \n" . // ��â
                "  or   Singo.Location = '213'   \n" . // ���
                "  or   Singo.Location = '201' ) \n" ; // â��

       // �氭
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

       // ��û
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

       // �泲
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

       // ���
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

       // ȣ��
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

       //  ����
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
       $sLoc9 .= " and Singo.Location <> '100' "  ; // ����
       $sLoc9 .= " and Singo.Location <> '200' "  ; // �λ�
       $sLoc9 .= " and Singo.Location <> '203' "  ; // �뿵
       $sLoc9 .= " and Singo.Location <> '600' "  ; // ���
       $sLoc9 .= " and Singo.Location <> '207' "  ; // ����
       $sLoc9 .= " and Singo.Location <> '205' "  ; // ����
       $sLoc9 .= " and Singo.Location <> '208' "  ; // ����
       $sLoc9 .= " and Singo.Location <> '202' "  ; // ����
       $sLoc9 .= " and Singo.Location <> '211' "  ; // ��õ
       $sLoc9 .= " and Singo.Location <> '212' "  ; // ��â
       $sLoc9 .= " and Singo.Location <> '213' "  ; // ���
       $sLoc9 .= " and Singo.Location <> '201' "  ; // â��
       $sLoc9 .= ")" ;

       for ($j=1; $j<=9; $j++)
       {
          $sLocCondX = "sLoc".$j ;
          $sLocNameX = $sLocName[$j] ;
          //if  ($$sLocCondX != "") echo $sLocNameX.":[". $$sLocCondX ."]<BR>" ;

          if  ($$sLocCondX != "") // �ش������� �ڷᰡ �ִ� ��� ����
          {
              if  ($nFilmTypeNo != 0) // All�� �ƴҶ�//.
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
       // Ư�������� ���������� ������ �� ���
       if  (($LocationCode) && ($LocationCode!=""))
       {
           $sQuery = "Select * From bas_location         \n".
                     " Where Code = '".$LocationCode."'  \n" ;
           $qryzone = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
           if  ($zone_data = mysql_fetch_array($qryzone))
           {
               $zoneName = $zone_data["Name"] ;
           }

           if  ($LocationCode=="200")//  �λ��� (�λ�+���+����+â��)
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

       // Ư�������� ���������� ������ �� ���
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
               if  ($ZoneCode == '20') // �泲�ΰ�� �λ��� �����Ѵ�.
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

        if  ($AddedCont != "") // �ش��ϴ� �ڷᰡ �ִ°��..
        {
			if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
			{
               $AddedCont .= " And Singo.Open = '".$FilmOpen."'  ".
                             " And Singo.Film = '".$FilmCode."'  " ;
			}
			if  ($WorkGubun == 38) // �Ե����׸�
			{
               $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
			}
			if  ($WorkGubun == 40) // �ް��ڽ�
			{
               $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
			}
			if  ($WorkGubun == 51) // CGV
			{
               $AddedCont .= " And Showroom.MultiPlex  = '2' " ;
			}
			if  ($WorkGubun == 52) // ��Ÿ
			{
               $AddedCont .= " And ((Showroom.MultiPlex <> '5') and (Showroom.MultiPlex <> '3')  and (Showroom.MultiPlex <> '2') )" ;
			}

			if  (($WorkGubun == 38) || ($WorkGubun == 40) || ($WorkGubun == 51) || ($WorkGubun == 52)) // �Ե����׸�,  �ް��ڽ�, CGV, ��Ÿ
			{
				if  ($nFilmTypeNo != 0) // All�� �ƴҶ�//.
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
			else // �Ե����׸�, �ް��ڽ�, CGV, ��Ÿ  �� �ƴ� �Ϲ�
			{
				if  ($nFilmTypeNo != 0) // All�� �ƴҶ�//.
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

	<!--���հ�-->
   <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">
   <tr>
		<td class=textarea bgcolor=#ffebcd width=170 align=center colspan=2>
		���հ�(<?=$nNumChongGea?>)
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
