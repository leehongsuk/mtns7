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

        mysql_select_db($cont_db) ;  // {[������ ���̽�]} : �����


        $timestamp2 = mktime(0,0,0,substr($FromDate,4,2),substr($FromDate,6,2),substr($FromDate,0,4));
        $dur_time2  = (time() - $timestamp2) / 86400;

        $timestamp1 = mktime(0,0,0,substr($ToDate,4,2),substr($ToDate,6,2),substr($ToDate,0,4));
        $dur_time1  = (time() - $timestamp1) / 86400;

        $dur_day    = $dur_time2 - $dur_time1;  // �ϼ�
?>
<link rel=stylesheet href=./LinkStyle.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
    <title>�ݾ׺� ����</title>
</head>

<body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

    <script>
    //
    // ���� ���
    //
    function toexel_click()
    {
        botttomaddr = 'wrk_filmsupply_Link_DnSumAmt.php?'
                    + 'FilmTile=<?=$FilmTile?>&'
                    + 'logged_UserId=<?=$logged_UserId?>&'
                    + 'FromDate=<?=$FromDate?>&'
                    + 'ToDate=<?=$ToDate?>&'
                    + 'ToExel=Yes' ;

        top.frames.bottom.location.href = botttomaddr ;
    }
    </script>

    <center>
    <br><br>
    <b>�ݾ׺� ����</b>
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

    $sSingoName     = get_singotable($FilmOpen,$FilmCode,$connect) ;  // �Ű� ���̺� �̸�..
    $sShowroomorder = get_showroomorder($FilmOpen,$FilmCode,$connect) ;

	$sQuery = "Select * From bas_filmtitle    ".
              " Where Open = '".$FilmOpen."'  ".
              "   And Code = '".$FilmCode."'  " ;
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

            <td align=center >
            <!-- ��ȭ������� -->
            <b><?=$filmtitle_data["Name"]?></b>
            </td>


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
        <td class=textarea bgcolor=#ffe4b5 width=70 align=center>����</td>

        <td class=textarea bgcolor=#ffe4b5 width=70 align=center>�����</td>

        <td class=textarea bgcolor=#ffe4b5 width=70 align=center>������</td>

        <td class=textarea bgcolor=#ffe4b5 width=80 align=center>&nbsp;�ݾ�&nbsp;</td>
    </tr>


    <?
    $TotSumNumPersons = 0 ;
    $TotSumPrice = 0 ;

    if  ((!$LocationCode) && (!$ZoneCode))  // ��ü����
    {
        if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
        {
            $sFilmTileCont = " And Singo.Open = '".$FilmOpen."'  \n".
                             " And Singo.Film = '".$FilmCode."'  \n" ;
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


        //
        //  �� ������ŭ 9�� ����.............
        //
        for ($j=1; $j<=9; $j++)
        {
            $sLocCondX = "sLoc".$j ;
            $zoneName = $sLocName[$j] ;

            if  ($$sLocCondX != "") // �ش������� �ڷᰡ �ִ� ��� ����
            {
                $sCondition = $sFilmTileCont . $$sLocCondX  ;

                $sQuery = "Select COUNT(DISTINCT(Singo.UnitPrice)) CntUnitPrice \n".
                          "  From ".$sSingoName."   As Singo                    \n".
                          " Where Singo.SingoDate  >= '".$FromDate."'           \n".
                          "   And Singo.SingoDate  <= '".$ToDate."'             \n".
                          $sCondition                                              ; // eq($sQuery) ;
                $QrySingo = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
                if ($singo_data = mysql_fetch_array($QrySingo))
                {
                    $CntUnitPrice   = $singo_data["CntUnitPrice"] ; //
                }

                $sQuery = "Select Singo.UnitPrice,                            \n".
                          "       Sum(Singo.NumPersons)  SumNumPersons        \n".
                          "  From ".$sSingoName."   As Singo                  \n".
                          " Where Singo.SingoDate  >= '".$FromDate."'         \n".
                          "   And Singo.SingoDate  <= '".$ToDate."'           \n".
                          $sCondition                                            .
                          " Group By Singo.UnitPrice                          \n".
                          " Order By Singo.UnitPrice desc                     \n" ;  //eq($sQuery) ;
                $QrySingo = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

                $affected_row = (mysql_affected_rows() + 1) ;

                include "wrk_filmsupply_Link_DnSumAmt1.php";
            }
        }

        $zoneName = "��ü";
        $TotSumNumPersons = 0 ;
        $TotSumPrice = 0 ;
        //��ü
        $sQuery = "Select COUNT(DISTINCT(Singo.UnitPrice)) CntUnitPrice \n".
                  "  From ".$sSingoName."   As Singo                    \n".
                  " Where Singo.SingoDate  >= '".$FromDate."'           \n".
                  "   And Singo.SingoDate  <= '".$ToDate."'             \n".
                  $sFilmTileCont                                              ;  //eq($sQuery) ;
        $QrySingo = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
        if ($singo_data = mysql_fetch_array($QrySingo))
        {
            $CntUnitPrice   = $singo_data["CntUnitPrice"] ; //
        }

        $sQuery = "Select Singo.UnitPrice,                            \n".
                  "       Sum(Singo.NumPersons)  SumNumPersons        \n".
                  "  From ".$sSingoName."   As Singo                  \n".
                  " Where Singo.SingoDate  >= '".$FromDate."'         \n".
                  "   And Singo.SingoDate  <= '".$ToDate."'           \n".
                  $sFilmTileCont                                         .
                  " Group By Singo.UnitPrice                          \n".
                  " Order By Singo.UnitPrice desc                     \n" ;  //eq($sQuery) ;
        $QrySingo = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

        $affected_row = (mysql_affected_rows() + 1) ;

        include "wrk_filmsupply_Link_DnSumAmt1.php";
    }
    else
    {
        // Ư�������� ���������� ������ �� ���
        if  (($LocationCode) && ($LocationCode!=""))
        {
            $sQuery = "Select * From bas_location        ".
                      " Where Code = '".$LocationCode."' " ;
            $qryzone = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
            if  ($zone_data = mysql_fetch_array($qryzone))
            {
                $zoneName = $zone_data["Name"] ;
            }

            if  ($LocationCode=="200")//  �λ��� (�λ�+���+����+â��)
            {
                $AddedCont = " And  (Singo.Location = '200'  \n".
                             "    Or Singo.Location = '600'  \n".
                             "    Or Singo.Location = '207'  \n".
                             "    Or Singo.Location = '205'  \n".
                             "    Or Singo.Location = '208'  \n".
                             "    Or Singo.Location = '202'  \n".
                             "    Or Singo.Location = '211'  \n".
                             "    Or Singo.Location = '212'  \n".
                             "    Or Singo.Location = '213'  \n".
                             "    Or Singo.Location = '201') \n" ;
            }
            else
            {
                $AddedCont = " And  Singo.Location = '".$LocationCode."'  \n";
            }
        }

        // Ư�������� ���������� ������ �� ���
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
                   $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' \n" ;
               }
               else
               {
                   $AddedCont .= " or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' \n" ;
               }
           }

           if  ($AddedCont != " And ")
           {
               if  ($ZoneCode == '20') // �泲�ΰ�� �λ��� �����Ѵ�.
               {
                    $AddedCont .= " or Singo.Location = '200' \n" ;
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
                $AddedCont .= " And Singo.Open = '".$FilmOpen."'  \n".
                              " And Singo.Film = '".$FilmCode."'  \n" ;
            }

            $sQuery = "Select COUNT(DISTINCT(Singo.UnitPrice)) CntUnitPrice \n".
                      "  From ".$sSingoName."   As Singo                    \n".
                      " Where Singo.SingoDate  >= '".$FromDate."'           \n".
                      "   And Singo.SingoDate  <= '".$ToDate."'             \n".
                      $AddedCont                                              ; // eq($sQuery) ;
            $QrySingo = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
            if ($singo_data = mysql_fetch_array($QrySingo))
            {
                $CntUnitPrice   = $singo_data["CntUnitPrice"] ; //
            }

            $sQuery = "Select Singo.UnitPrice,                                \n".
                          "       Sum(Singo.NumPersons)  SumNumPersons        \n".
                          "  From ".$sSingoName."   As Singo                  \n".
                          " Where Singo.SingoDate  >= '".$FromDate."'         \n".
                          "   And Singo.SingoDate  <= '".$ToDate."'           \n".
                          $AddedCont                                           .
                          " Group By Singo.UnitPrice                          \n".
                          " Order By Singo.UnitPrice desc                     \n" ; // eq($sQuery) ;
            $QrySingo = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;

            $filmtitleNameTitle = "" ;

            $affected_row = (mysql_affected_rows() + 1) ;

            include "wrk_filmsupply_Link_DnSumAmt1.php";
        }
    }
    ?>
        <!--�հ�-->
        <tr>
            <td class=textarea bgcolor=#ffebcd align=center colspan=2>�հ�</td>

            <td class=textarea bgcolor=#ffebcd align=right><?=number_format($TotSumNumPersons)?>&nbsp;</td>

            <td class=textarea bgcolor=#ffebcd align=right><?=number_format($TotSumPrice)?>&nbsp;</td>
        </tr>
    </table>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    </center>
    </form>

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
