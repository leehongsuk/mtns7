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


        $timestamp2 = mktime(0,0,0,substr($FromDate,4,2),substr($FromDate,6,2),substr($FromDate,0,4));
        $dur_time2  = (time() - $timestamp2) / 86400;

        $timestamp1 = mktime(0,0,0,substr($ToDate,4,2),substr($ToDate,6,2),substr($ToDate,0,4));
        $dur_time1  = (time() - $timestamp1) / 86400;

        $dur_day    = $dur_time2 - $dur_time1;  // �ϼ�
?>
<link rel=stylesheet href=./LinkStyle.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>������ ȸ��üũ����</title>
</head>

<body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

    <script>
    //
    // ���� ���
    //
    function toexel_click()
    {
        botttomaddr = 'wrk_filmsupply_Link_DnDigi.php?'
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
        top.frames.bottom.location.href = botttomaddr ;
    }
    </script>

    <center>
    <br><br>
    <b>������ ȸ��üũ����</b>
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
    if  ($ToExel)   // ����
    {
        $TColor = "#ffffff" ;
    }
    else
    {
        $TColor = "#ffe4b5" ;
    }

    $arrMultiCode = array( 2, 5, 6, 3) ; // 2: cgv, 5: �Ե�, 6: �����ӽ�, 3: �ް��ڽ�
    $arrMultiName = array( "CGV", "�Ե� �ó׸�", "�����ӽ�", "�ް��ڽ�") ;
    ?>

    <!-- ���� ���̺� ���� -->
    <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">
    <tr height=25>
        <td class=textarea bgcolor=<?=$TColor?> width=120 align=center>
        &nbsp;
        </td>
        <?
        for ($i=0 ; $i<=$dur_day ; $i++)
        {
            ?>
            <td class=textarea width=50 bgcolor=<?=$TColor?> class=tbltitle align=center>
            &nbsp;<?=date("m/d",$timestamp2 + ($i * 86400)) ;?>&nbsp;
            </td>
            <?
        }
        ?>
        <td class=textarea width=60 bgcolor=<?=$TColor?> class=tbltitle align=center>
        &nbsp;�հ�&nbsp;
        </td>
    </tr>
    <?
    for ($i=0 ; $i<=($dur_day+1) ; $i++)
    {
        $arrySumToatal[$i] = 0 ;  // ����ȸ�纰 �հ�
    }

    for ($k=0 ; $k<4 ; $k++) // 2: cgv, 5: �Ե�, 6: �����ӽ�, 3: �ް��ڽ�
    {
        ?>
        <tr height=25>

            <td class=textarea bgcolor=<?=$TColor?> width=120 align=center>
            <?=$arrMultiName[$k]?>
            </td>

            <?
            $TotalCntRooms = 0 ;
            for ($i=0 ; $i<=$dur_day ; $i++)
            {
                $objDate = date("Ymd",$timestamp2 + ($i * 86400)) ;

                $sQuery = " SELECT sum(CntDegreeScore) SumCntDegreeScore  \n".
                          "   FROM wrk_digital_account da,                \n".
                          "        bas_showroom sr                        \n".
                          "  WHERE da.theather = sr.Theather              \n".
                          "    AND da.room = sr.room                      \n".
                          "    AND sr.multiplex = '$arrMultiCode[$k]'     \n".
                          "    AND da.digdate   = '$objDate'              \n" ; //eq($sQuery) ;
                $QrySumCntDegreeScore = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
                if ($ArySumCntDegreeScore = mysql_fetch_array($QrySumCntDegreeScore))
                {
                    $SumCntDegreeScore =  $ArySumCntDegreeScore["SumCntDegreeScore"] ;

                    $TotalCntRooms     += $SumCntDegreeScore ;
                    $arrySumToatal[$i] += $SumCntDegreeScore ;
                }
                else
                {
                    $CntRooms =  0 ;
                }
                ?>
                <td class=textarea width=50 bgcolor=<?=$TColor?> class=tbltitle align=right>
                &nbsp;<b><?=number_format($SumCntDegreeScore)?></b>&nbsp;
                </td>
                <?

            }
            ?>

            <td class=textarea width=60 bgcolor=<?=$TColor?> class=tbltitle align=right>
            &nbsp;<b><?=number_format($TotalCntRooms)?></b>&nbsp;
            </td>
            <?
            $arrySumToatal[$i] += $TotalCntRooms ;
            ?>
        </tr>
        <?
    }
    ?>
    <tr height=25>

        <td class=textarea bgcolor=<?=$TColor?> width=120 align=center>
        �հ�
        </td>

        <?
        for ($i=0 ; $i<=$dur_day ; $i++)
        {
            ?>
            <td class=textarea bgcolor=<?=$TColor?> width=50 align=right>&nbsp;<B><?=number_format($arrySumToatal[$i])?></B>&nbsp;</td>
            </td>
            <?
        }
        ?>

        <td class=textarea width=60 bgcolor=<?=$TColor?> class=tbltitle align=right>
        &nbsp;<B><?=number_format($arrySumToatal[$i])?></B>&nbsp;
        </td>

    </tr>
    </table>
    <!-- ���� ���̺� �� -->





    <!-- ���� ���̺� ���� -->
    <?
    for ($k=0 ; $k<4 ; $k++)
    {
        ?>
        <br>
        <br>
        <br>

        <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">
        <tr height=25>

            <td class=textarea bgcolor=<?=$TColor?> width=50 align=center>
            ��ȣ
            </td>

            <td class=textarea bgcolor=<?=$TColor?> width=120 align=center>
            <?=$arrMultiName[$k]?>
            </td>

            <td class=textarea bgcolor=<?=$TColor?> width=40 align=center>
            ��
            </td>

            <?
            for ($i=0 ; $i<=$dur_day ; $i++)
            {
               ?>
               <td class=textarea width=50 bgcolor=<?=$TColor?> class=tbltitle align=center>
               &nbsp;<?=date("m/d",$timestamp2 + ($i * 86400)) ;?>&nbsp;
               </td>
               <?
            }
            ?>

            <td class=textarea width=60 bgcolor=<?=$TColor?> class=tbltitle align=center>
            &nbsp;�հ�&nbsp;
            </td>

        </tr>
        <?
        for ($i=0 ; $i<=($dur_day+1) ; $i++)
        {
            $arrySumToatal[$i] = 0 ;  // ����ȸ�纰 �հ�
        }

        $OldThaether = "" ;

        $nNo = 0 ;                                             
        $sQuery = "SELECT da.TheatherName,                          \n".
                  "       da.Theather,                              \n".
                  "       count(DISTINCT da.room ) CntRoom          \n".
                  "  FROM wrk_digital_account da,                   \n".
                  "       bas_showroom sr                           \n".
                  " WHERE da.Theather = sr.Theather                 \n".
                  "   AND da.Room = sr.room                         \n".
                  "   AND da.Open = '$FilmOpen'                     \n".
                  "   AND da.Film = '$FilmCode'                     \n".
                  "   AND sr.multiplex = $arrMultiCode[$k]          \n".
                  "   AND DigDate >='$FromDate'                     \n".
                  "   AND DigDate <='$ToDate'                       \n".
                  " GROUP BY da.TheatherName, da.Theather           \n" ; // eq($sQuery) ;
        $QryDigiTheathers = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
        while ($AryDigiTheathers = mysql_fetch_array($QryDigiTheathers))
        {
            $Thaether = $AryDigiTheathers["Theather"] ; // ����..
            $TheatherName = $AryDigiTheathers["TheatherName"] ; // �����..
            $CntRoom = $AryDigiTheathers["CntRoom"] ; 

            $clrToggle = !$clrToggle ;

            if  ($ToExel)   // ����
            {
                $Color = "#ffffff" ;
            }
            else
            {
                if  ($clrToggle==true)  $Color = "#c0c0c0" ;
                else                    $Color = "#d0d0d0" ;
            }


            $sQuery = "SELECT da.Theather,                              \n".
                      "       da.TheatherName,                          \n".
                      "       da.Room                                   \n".
                      "  FROM wrk_digital_account da,                   \n".
                      "       bas_showroom sr                           \n".
                      " WHERE da.Theather = sr.Theather                 \n".
                      "   AND da.Room = sr.room                         \n".
                      "   AND da.Theather = '$Thaether'                 \n".
                      "   AND da.Open = '$FilmOpen'                     \n".
                      "   AND da.Film = '$FilmCode'                     \n".
                      "   AND sr.multiplex = $arrMultiCode[$k]          \n".
                      "   AND DigDate >='$FromDate'                     \n".
                      "   AND DigDate <='$ToDate'                       \n".
                      " GROUP BY da.Theather, da.TheatherName, da.room  \n" ; //  eq($sQuery) ;
            $QryDigiTheatherRoom = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
            while ($AryDigiTheatherRoom = mysql_fetch_array($QryDigiTheatherRoom))
            {
                $Room = $AryDigiTheatherRoom["Room"] ; // ����..
                ?>
                <tr>
                    <?
                    if  ($OldThaether != $Thaether)
                    {
                        ?>
                        <td class=textarea bgcolor=<?=$Color?> width=50 align=center rowspan=<?=$CntRoom?>>
                        <?= ++ $nNo ?>
                        </td>

                        <td class=textarea bgcolor=<?=$Color?> width=120 align=left rowspan=<?=$CntRoom?>>
                        <?=$TheatherName?>
                        </td>
                        <?
                        $OldThaether = $Thaether ;
                    }
                    ?>

                    <td class=textarea bgcolor=<?=$Color?> width=40 align=center>
                    <?=$Room?>
                    </td>
                    <?
                    $TotalCntRooms = 0;

                    for ($j=0 ; $j<=$dur_day ; $j++)
                    {
                        $objDate = date("Ymd",$timestamp2 + ($j * 86400)) ;

                        $sQuery = "SELECT sum(CntDegreeScore) SumCntDegreeScore \n".
                                  "   FROM wrk_digital_account                  \n".
                                  "  WHERE Theather = $Thaether                 \n".
                                  "    AND Room     = $Room                     \n".
                                  "    AND digdate  = '$objDate'                \n" ; ///eq($sQuery) ;
                        $QrySumCntDegreeScore = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
                        if ($ArySumCntDegreeScore = mysql_fetch_array($QrySumCntDegreeScore))
                        {
                            $SumCntDegreeScore =  $ArySumCntDegreeScore["SumCntDegreeScore"] ;

                            $TotalCntRooms     += $SumCntDegreeScore ;
                            $arrySumToatal[$j] += $SumCntDegreeScore ;
                        }
                        else
                        {
                            $CntRooms =  0 ;
                        }
                        ?>
                        <td class=textarea bgcolor=<?=$Color?> width=50 align=right>&nbsp;<B><?=number_format($SumCntDegreeScore)?></B>&nbsp;</td>
                        <?
                    }                
                    $arrySumToatal[$j] += $TotalCntRooms ;
                    ?>
                    <td class=textarea width=60 bgcolor=<?=$Color?> class=tbltitle align=right>
                    &nbsp;<B><?=number_format($TotalCntRooms)?></B>&nbsp;
                    </td>
                    <?
                ?>
                </tr>
                <?
            }
        }
        ?>


        <tr height=25>

            <td class=textarea bgcolor=<?=$TColor?> width=50 align=center>
            &nbsp;
            </td>

            <td class=textarea bgcolor=<?=$TColor?> width=120 align=center>
            �հ�
            </td>

            <td class=textarea bgcolor=<?=$TColor?> width=40 align=center>
            &nbsp;
            </td>
            <?
            for ($i=0 ; $i<=$dur_day ; $i++)
            {
                ?>
                <td class=textarea bgcolor=<?=$TColor?> width=50 align=right>&nbsp;<B><?=number_format($arrySumToatal[$i])?></B>&nbsp;</td>
                </td>
                <?
            }
            ?>

            <td class=textarea width=60 bgcolor=<?=$TColor?> class=tbltitle align=right>
            &nbsp;<B><?=number_format($arrySumToatal[$i])?></B>&nbsp;
            </td>

        </tr>
        </table>
        <?
    }
    ?>
    <!-- ���� ���̺� �� -->

    <br><br>

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
