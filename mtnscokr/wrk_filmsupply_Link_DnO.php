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
<title>���庰 �α�����</title>
</head>

<body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

  <script>
     //
     // ���� ���
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
  <b>���庰 �α�����</b>
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
   <?
   for ($i=0 ; $i<6 ; $i++)
   {
       $TotSums[$i]  = 0 ; //
   }
   ?>

   <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">
   <tr height=25>
       <td class=textarea width=50 bgcolor=#ffe4b5 align=center>
       ����
       </td>

       <td class=textarea width=120 bgcolor=#ffe4b5 align=center>
       �����
       </td>

       <td class=textarea width=60 bgcolor=#ffe4b5 align=center>
       ������
       </td>

       <td class=textarea width=50 bgcolor=#ffe4b5 align=center>
       ����
       </td>

       <td class=textarea width=60 bgcolor=#ffe4b5 class=tbltitle align=center>
       �ο�
       </td>

       <td class=textarea width=90 bgcolor=#ffe4b5 class=tbltitle align=center>
       �ݾ�(�����)
       </td>

       <td class=textarea width=90 bgcolor=#ffe4b5 class=tbltitle align=center>
       ������ܱݾ�
       </td>

       <td class=textarea width=100 bgcolor=#ffe4b5 class=tbltitle align=center>
       ���ް���
       </td>

       <td class=textarea width=70 bgcolor=#ffe4b5 class=tbltitle align=center>
       �ΰ���
       </td>

       <td class=textarea width=100 bgcolor=#ffe4b5 class=tbltitle align=center>
       ��ȭ�� �Աݾ�
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

   if  ((!$LocationCode) && (!$ZoneCode))  // ��ü����
   {
       //-----------
       // ���� ���
       //-----------
       $zoneName  = "����" ;
       $AddedCont = " And  Singo.Location = '100' " ;

       if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
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
       // ������
       //-----------
       $zoneName   = "���" ;
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

       if  ($AddedLoc != "") // �ش��ϴ� �ڷᰡ �ִ°��..
       {
           if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
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
       // �λ� ���
       //-----------
       $zoneName  = "�λ�" ;
       $AddedCont = " And ( Singo.Location = '200'   " . // �λ�
                    "  or   Singo.Location = '203'   " . // �뿵
                    "  or   Singo.Location = '600'   " . // ���
                    "  or   Singo.Location = '207'   " . // ����
                    "  or   Singo.Location = '205'   " . // ����
                    "  or   Singo.Location = '208'   " . // ����
                    "  or   Singo.Location = '202'   " . // ����
                    "  or   Singo.Location = '211'   " . // ��õ
                    "  or   Singo.Location = '212'   " . // ��â
                    "  or   Singo.Location = '213'   " . // ���
                    "  or   Singo.Location = '201' ) " ; // â��


       if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
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
       // �氭 ���
       //-----------
       $zoneName  = "�氭" ;
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

       if  ($AddedCont != "") // �氭������ �ش��ϴ� �ڷᰡ �ִ°��..
       {
           if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
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
       // ��û ���
       //-----------
       $zoneName  = "��û" ;
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

       if  ($AddedCont != "") // ��û������ �ش��ϴ� �ڷᰡ �ִ°��..
       {
           if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
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
       // �泲 ���
       //-----------
       $zoneName  = "�泲" ;
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

       if  ($AddedCont != "") // �泲������ �ش��ϴ� �ڷᰡ �ִ°��..
       {
           if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
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
       // ��� ���
       //-----------
       $zoneName  = "���" ;
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

       if  ($AddedCont != "") // �氭������ �ش��ϴ� �ڷᰡ �ִ°��..
       {
           if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
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
       // ȣ�� ���
       //-----------
       $zoneName  = "ȣ��" ;
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

       if  ($AddedCont != "") // ȣ�������� �ش��ϴ� �ڷᰡ �ִ°��..
       {
           if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
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
       // �������
       //-----------
       $zoneName   = "����" ;
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
       $AddedLoc .= " and Singo.Location <> '100' "  ; // ����
       $AddedLoc .= " and Singo.Location <> '200' "  ; // �λ�
       $AddedLoc .= " and Singo.Location <> '203' "  ; // �뿵
       $AddedLoc .= " and Singo.Location <> '600' "  ; // ���
       $AddedLoc .= " and Singo.Location <> '207' "  ; // ����
       $AddedLoc .= " and Singo.Location <> '205' "  ; // ����
       $AddedLoc .= " and Singo.Location <> '208' "  ; // ����
       $AddedLoc .= " and Singo.Location <> '202' "  ; // ����
       $AddedLoc .= " and Singo.Location <> '211' "  ; // ��õ
       $AddedLoc .= " and Singo.Location <> '212' "  ; // ��â
       $AddedLoc .= " and Singo.Location <> '213' "  ; // ���
       $AddedLoc .= " and Singo.Location <> '201' "  ; // â��
       $AddedLoc .= ")" ;

       // ��� + ���� + �λ� �� ������ �������� �������� �Ѵ�.
       if  ($AddedLoc != "") // �ش��ϴ� �ڷᰡ �ִ°��..
       {
           if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
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

       <!--���հ�-->
       <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">
       <tr height=20>
            <td class=textarea bgcolor=#ffebcd width=170 align=center colspan=2>
            ���հ�
            </td>

            <td class=textarea width=60 bgcolor=#ffebcd align=center>
            <!-- ������ -->&nbsp;
            </td>

            <td class=textarea width=50 bgcolor=#ffebcd align=center>
            <!-- ���� -->&nbsp;
            </td>

            <td class=textarea width=60 bgcolor=#ffebcd class=tbltitle align=right>
            <!-- �ο� --><?=number_format($TotSums[0])?>&nbsp;
            </td>

            <td class=textarea width=90 bgcolor=#ffebcd class=tbltitle align=right>
            <!-- �ݾ�(�����) --><?=number_format($TotSums[1])?>&nbsp;
            </td>

            <td class=textarea width=90 bgcolor=#ffebcd class=tbltitle align=right>
            <!-- ������ܱݾ� --><?=number_format($TotSums[2])?>&nbsp;
            </td>

            <td class=textarea width=100 bgcolor=#ffebcd class=tbltitle align=right>
            <!-- ���ް��� --><?=number_format($TotSums[3])?>&nbsp;
            </td>

            <td class=textarea width=70 bgcolor=#ffebcd class=tbltitle align=right>
            <!-- �ΰ��� --><?=number_format($TotSums[4])?>&nbsp;
            </td>

            <td class=textarea width=100 bgcolor=#ffebcd class=tbltitle align=right>
            <!-- ��ȭ�� �Աݾ� --><?=number_format($TotSums[5])?>&nbsp;
            </td>

       </tr>
       </table>
   <?
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
