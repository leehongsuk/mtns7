<?
    session_start();

    if  ($ToExel)
    {
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=excel_name.xls");
        header("Content-Description: GamZa Excel Data");
    }



    // ���������� �α��� �ߴ��� üũ�Ѵ�.
    if ((!$logged_UserId) || ($logged_UserId==""))
    {
       echo "<script language='JavaScript'>window.location = 'index.php'</script>";
    }
    else
    {

        include "config.php";

        if (!$FromDate)
        {
            $FromDate = date("Ymd",$Today) ;
        }
        if (!$ToDate)
        {
            $ToDate = date("Ymd",$Today) ;
        }

        $connect=dbconn();

        mysql_select_db($cont_db) ; // �ش��޻縦 ���ϰ�


        $FilmOpen = substr($FilmTile,0,6) ;
        $FilmCode = substr($FilmTile,6,2) ;

        $sSingoName     = get_singotable($FilmOpen,$FilmCode,$connect) ;  // �Ű� ���̺� �̸�..
        $sShowroomorder = get_showroomorder($FilmOpen,$FilmCode,$connect) ;

        $timestamp2 = mktime(0,0,0,substr($FromDate,4,2),substr($FromDate,6,2),substr($FromDate,0,4));
        $dur_time2  = (time() - $timestamp2) / 86400;

        $timestamp1 = mktime(0,0,0,substr($ToDate,4,2),substr($ToDate,6,2),substr($ToDate,0,4));
        $dur_time1  = (time() - $timestamp1) / 86400;

        $dur_day    = $dur_time2 - $dur_time1;  // �ϼ�
?>

<html>

<link rel=stylesheet href=./LinkStyle.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>���� ����</title>
</head>

<body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

   <script>
         function check_submit()
         {
            return true;
         }

         //
         // ���� ���
         //
         function toexel_click()
         {
             botttomaddr = 'wrk_filmsupply_Link_DnB.php?'
                         + 'FilmTile=<?=$FilmTile?>&'
                         + 'logged_UserId=<?=$logged_UserId?>&'
                         + 'LocationCode=<?=$LocationCode?>&'
                         + 'ZoneCode=<?=$ZoneCode?>&'
                         + 'FromDate=<?=$FromDate?>&'
                         + 'ToDate=<?=$ToDate?>&'
                         + 'ToExel=Yes' ;

             location.href = botttomaddr ;
         }
   </script>

   <center>
   <br><br>
   <b>���ں���Ȳ</b>
   <a href="javascript: window.print();"><img src="print.gif" width="32" height="32" border="0"></a>
   <a href=# onclick="toexel_click();"><img src="exel.gif" width="32" height="32" border="0"></a>


   <form method=post name=write action="wrk_fiulmsupply_X.php?BackAddr=wrk_silmooja.php" onsubmit="return check_submit()">


   <br>

   <!--                 -->
   <!-- ���ν��ھ� ���� -->
   <!--                 -->


   <?
   $qryfilmtitle = mysql_query("Select * From bas_filmtitle        ".
                               " Where Open = '".$FilmOpen."'  ".
                               "   And Code = '".$FilmCode."'  ",$connect) ;

   $filmtitle_data = mysql_fetch_array($qryfilmtitle) ;
   if  ($filmtitle_data)
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

   <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor=#C0B0A0>

       <tr height=25>

       <td class=textarea bgcolor=#ffe4b5 width=50 align=center>����</td>
       <td class=textarea bgcolor=#ffe4b5 width=130 align=center>�����</td>
       <td class=textarea bgcolor=#ffe4b5 width=50 align=center>�¼���</td>
       <?
       for ($i=0 ; $i<=$dur_day ; $i++)
       {
       ?>
          <td class=textarea width=50 bgcolor=#ffe4b5 class=tbltitle align=center>&nbsp;<?=date("m/d",$timestamp2 + ($i * 86400)) ;?>&nbsp;</td>
       <?
       }
       ?>
       <td class=textarea width=60 bgcolor=#ffe4b5 class=tbltitle align=center>&nbsp;�հ�&nbsp;</td>
       <td class=textarea width=80 bgcolor=#ffe4b5 class=tbltitle align=center>&nbsp;�ݾ�&nbsp;</td>
       <td class=textarea width=60 bgcolor=#ffe4b5 class=tbltitle align=center>&nbsp;�� ����&nbsp;</td>
       <td class=textarea width=100 bgcolor=#ffe4b5 class=tbltitle align=center>&nbsp;�� �ݾ�&nbsp;</td>

       </tr>

       <?
       $AddedCont = "" ;

       $SumSeat = 0 ;

       for ($i=0 ; $i<=($dur_day+3) ; $i++)
       {
           $arrySumNumPersons[$i] = 0 ;  // �迭����..
       }


       if  (((!$LocationCode) && (!$ZoneCode)) || ($ZoneCode=="9999")) // ��ü����
       {
           //-----------
           // ���� ���
           //-----------
           $AddedCont = " And  Singo.Location = '100' " ;

           if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
           {
               if   ($FilmCode=='00')
               {
                    $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
               }
               else
               {
                    $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                   " And Singo.Film = '".$FilmCode."' " ;
               }
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

           include "wrk_filmsupply_Link_DnB1.php";



           //-----------
           // ��� ���
           //-----------
           $query1    = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                    " Where Zone  = '04'                   ",$connect) ;

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

           if  ($AddedCont != "") // ���������� �ش��ϴ� �ڷᰡ �ִ°��..
           {
               if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
               {
                   if   ($FilmCode=='00')
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
                   }
                   else
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                       " And Singo.Film = '".$FilmCode."' " ;
                   }
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

               include "wrk_filmsupply_Link_DnB1.php";
           }




           //-----------
           // �λ� ���
           //-----------
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
               if   ($FilmCode=='00')
               {
                    $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
               }
               else
               {
                    $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                   " And Singo.Film = '".$FilmCode."' " ;
               }

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

           include "wrk_filmsupply_Link_DnB1.php";


           //-----------
           // �氭 ���
           //-----------
           $query1     = mysql_query("Select * From bas_filmsupplyzoneloc   ".
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


           if  ($AddedCont != "") // �氭������ �ش��ϴ� �ڷᰡ �ִ°��..
           {
               if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
               {
                   if   ($FilmCode=='00')
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
                   }
                   else
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                       " And Singo.Film = '".$FilmCode."' " ;
                   }
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

               include "wrk_filmsupply_Link_DnB1.php";
           }

           //-----------
           // ��û ���
           //-----------
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

           if  ($AddedCont != "") // ��û������ �ش��ϴ� �ڷᰡ �ִ°��..
           {
               if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
               {
                   if   ($FilmCode=='00')
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
                   }
                   else
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                       " And Singo.Film = '".$FilmCode."' " ;
                   }
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

               include "wrk_filmsupply_Link_DnB1.php";
           }

           //-----------
           // �泲 ���
           //-----------
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
               $AddedCont .= " Or Singo.Location <> '600' " ;
               $AddedCont .= " Or Singo.Location <> '201' " ;
               $AddedCont .= " Or Singo.Location <> '207' " ;
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
                   if   ($FilmCode=='00')
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
                   }
                   else
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                       " And Singo.Film = '".$FilmCode."' " ;
                   }
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

               include "wrk_filmsupply_Link_DnB1.php";
           }

           //-----------
           // ��� ���
           //-----------
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

           if  ($AddedCont != "") // ��������� �ش��ϴ� �ڷᰡ �ִ°��..
           {
               if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
               {
                   if   ($FilmCode=='00')
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
                   }
                   else
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                       " And Singo.Film = '".$FilmCode."' " ;
                   }
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

               include "wrk_filmsupply_Link_DnB1.php";
           }

           //-----------
           // ȣ�� ���
           //-----------
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

           if  ($AddedCont != "") // ȣ�������� �ش��ϴ� �ڷᰡ �ִ°��..
           {
               if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
               {
                   if   ($FilmCode=='00')
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
                   }
                   else
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                       " And Singo.Film = '".$FilmCode."' " ;
                   }
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

               include "wrk_filmsupply_Link_DnB1.php";
           }



           //-----------
           // ���� ���
           //-----------
           $query1    = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                    " Where Zone  = '04'                   ",$connect) ;

           $AddedCont = " And " ;

           while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
           {
               if  ($AddedCont == " And ")
               {
                   $AddedCont .= "( Singo.Location <> '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
               else
               {
                   $AddedCont .= " And Singo.Location <> '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
           }

           if  ($AddedCont != " And ")
           {
               $AddedCont .= " and Singo.Location <> '100' "  ; // ����
               $AddedCont .= " and Singo.Location <> '200' "  ; // �λ�
               $AddedCont .= " and Singo.Location <> '203' "  ; // �뿵
               $AddedCont .= " and Singo.Location <> '600' "  ; // ���
               $AddedCont .= " and Singo.Location <> '207' "  ; // ����
               $AddedCont .= " and Singo.Location <> '205' "  ; // ����
			   $AddedCont .= " and Singo.Location <> '208' "  ; // ����
			   $AddedCont .= " and Singo.Location <> '202' "  ; // ����
			   $AddedCont .= " and Singo.Location <> '211' "  ; // ��õ
			   $AddedCont .= " and Singo.Location <> '212' "  ; // ��â
			   $AddedCont .= " and Singo.Location <> '213' "  ; // ���
			   $AddedCont .= " and Singo.Location <> '201' "  ; // â��
               $AddedCont .= ")" ;
           }
           else
           {
               $AddedCont = "" ;
           }

           // ��� + ���� + �λ� �� ������ �������� �������� �Ѵ�.

           if  ($AddedCont != "") // ���������� �ش��ϴ� �ڷᰡ �ִ°��..
           {
               if  ($FilmTile != "") // Ư����ȭ�� �����Ͽ� ������ �� ���..
               {
                   if   ($FilmCode=='00')
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
                   }
                   else
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                       " And Singo.Film = '".$FilmCode."' " ;
                   }
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

               include "wrk_filmsupply_Link_DnB1.php";
           }
           ?>



           <!-- ���ں����հ�-������ü�϶��� ���´�.-->

           <tr height=20>

           <td class=textarea bgcolor=#ffebcd align=center colspan=2>���հ�</td>
           <td class=textarea bgcolor=#ffebcd align=right>&nbsp;<?=number_format($SumSeat)?>&nbsp;</td>
           <?
           for ($i=0 ; $i<(count($arrySumNumPersons)-2) ; $i++)
           {
           ?>
              <td class=textarea bgcolor=#ffebcd class=tbltitle align=right>&nbsp;<?=number_format($arrySumNumPersons[$i])?>&nbsp;</td>
           <?
           }
           ?>
           <td class=textarea bgcolor=#ffebcd class=tbltitle align=right>&nbsp;<?=number_format($arrySumNumPersons[count($arrySumNumPersons)-2])?>&nbsp;</td>
           <td class=textarea bgcolor=#ffebcd class=tbltitle align=right>&nbsp;<?=number_format($arrySumNumPersons[count($arrySumNumPersons)-1])?>&nbsp;</td>

           </tr>

           <?
       }
       else
       {
           // Ư�������� ���������� ������ �� ���
           if  (($LocationCode) && ($LocationCode!=""))
           {
               //$AddedCont = " and  Singo.Location = '".$LocationCode."' " ;

               $sQuery = "Select * From bas_location        ".
                         " Where Code = '".$LocationCode."' " ;
               $qryzone = mysql_query($sQuery,$connect) ;
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
                   if   ($FilmCode=='00')
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " ;
                   }
                   else
                   {
                        $AddedCont .=  " And Singo.Open = '".$FilmOpen."' " .
                                       " And Singo.Film = '".$FilmCode."' " ;
                   }
               }

               $sQuery = "Select ShowroomOrder.Seq,                       ".
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
                         "          Singo.Room                            " ;
//eq($sQuery);
               $qry_singo = mysql_query($sQuery,$connect) ;

               $filmtitleNameTitle = "" ;

               $affected_row = mysql_affected_rows() ;

               include "wrk_filmsupply_Link_DnB1.php";
           }
       }
       ?>

   </table>


   </center>

   <br><br>

   </form>

</body>

</html>

<?
        mysql_close($connect);
    }
?>
