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
<title>�׷캰 ��Ȳ</title>
</head>

<body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

  <script>
     //
     // ���� ���
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
  <b>�׷캰 ��Ȳ<? if ($FromDate) {echo "(�Ⱓ��)"; } else {echo "(�Ϻ�)"; } ?></b>
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
               <!-- ��ȭ������� -->
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
           <td align=center bgcolor=<?=$ColorA?>>����</td>
           <td align=center bgcolor=<?=$ColorA?>>����</td>
           <td align=center bgcolor=<?=$ColorA?>>���</td>
           <td align=center bgcolor=<?=$ColorA?>>�λ�</td>
           <td align=center bgcolor=<?=$ColorA?>>����</td>
           <td align=center bgcolor=<?=$ColorA?>>��ü</td>
           <td align=center bgcolor=<?=$ColorA?>>������</td>
       </tr>
   <?


   $sSingoName = get_singotable($FilmOpen,$FilmCode,$connect) ;  // �Ű� ���̺� �̸�..

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
   $ArrNames = array ("CGV","�ް��ڽ�","�Ե�","�����ӽ�","�óʽ�","�Ϲ�") ;

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
        // ���� ���
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
        // ������
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
        // �λ� ���
        //-----------
        $AddedLoc = " And ( Singo.Location = '200'   " . // �λ�
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
        // �������
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
