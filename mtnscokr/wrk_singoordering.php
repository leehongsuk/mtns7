<?
    set_time_limit(0) ; // �� ó���۾��� ���� ���� ����.....
?>

<html>
<?
    include "config.php";

    $connect=dbconn();

    mysql_select_db($cont_db) ;


    $FilmOpen = substr($FilmTile,0,6) ;
    $FilmCode = substr($FilmTile,6,2) ;

    $sShowroomorder = get_showroomorder($FilmOpen,$FilmCode,$connect) ;  //

    if   ((($fromNo) && ($toNo)) && ($fromNo != $toNo))
    {


         $sQuery = "Update ".$sShowroomorder."   ".
                   "   Set SeqNo1 = -1           ".
                   " Where SeqNo1 = ".$fromNo."  " ;
         mysql_query($sQuery,$connect) ;

         if  ($fromNo < $toNo)
         {
             for ($i = $fromNo + 1 ; $i <= $toNo ; $i++ )
             {
                 $sQuery = "Update ".$sShowroomorder."   ".
                           "   Set SeqNo1 = ".($i-1)."   ".
                           " Where SeqNo1 = ".$i."       " ;
                 mysql_query($sQuery,$connect) ;

             }
             $sQuery = "Update ".$sShowroomorder."  ".
                       "   Set SeqNo1 = ".$toNo."   ".
                       " Where SeqNo1 = -1          " ;
             mysql_query($sQuery,$connect) ;
         }

         if  ($fromNo > $toNo)
         {
             for ($i = $fromNo - 1 ; $i >= $toNo ; $i--)
             {
                 $sQuery = "Update ".$sShowroomorder."   ".
                           "   Set SeqNo1 = ".($i+1)."   ".
                           " Where SeqNo1 = ".$i."       " ;
                 mysql_query($sQuery,$connect) ;
             }
             $sQuery = "Update ".$sShowroomorder."  ".
                       "   Set SeqNo1 = ".$toNo."   ".
                       " Where SeqNo1 = -1          " ;
             mysql_query($sQuery,$connect) ;
         }
    }

    if   ($applySingo) // �Ű��� ����
    {
         $sQuery = "Select * From ".$sShowroomorder." ".
                   " Order By SeqNo1, SeqNo2          ";
         $QryRoomorder = mysql_query($sQuery,$connect) ;
         while  ($ArrRoomorder = mysql_fetch_array($QryRoomorder))
         {
              $SeqNo1   = $ArrRoomorder["SeqNo1"] ;
              $SeqNo2   = $ArrRoomorder["SeqNo2"] ;
              $Theather = $ArrRoomorder["Theather"] ;
              $Room     = $ArrRoomorder["Room"] ;

              $sQuery = "Update ".$sShowroomorder."                    ".
                        "   Set Seq = ".(($SeqNo1*100)+($SeqNo2*1))."  ".
                        " Where SeqNo1   = ".$SeqNo1."                 ".
                        "   And SeqNo2   = ".$SeqNo2."                 ".
                        "   And Theather = ".$Theather."               ".
                        "   And Room     = ".$Room."                   " ;
              mysql_query($sQuery,$connect) ;
         }



         $FilmOpen = substr($FilmTile,0,6) ;
         $FilmCode = substr($FilmTile,6,2) ;

         $sSingoName = get_singotable($FilmOpen,$FilmCode,$connect) ;  // �Ű� ���̺� �̸�..

         $sQuery = "Select * From ".$sSingoName." " ;
         $QrySingo = mysql_query($sQuery,$connect) ;
         while  ($ArrSingo = mysql_fetch_array($QrySingo))
         {
              $SingoDate  = $ArrSingo["SingoDate"] ;
              $Silmooja   = $ArrSingo["Silmooja"] ;
              $Location   = $ArrSingo["Location"] ;
              $Theather   = $ArrSingo["Theather"] ;
              $Room       = $ArrSingo["Room"] ;
              $Open       = $ArrSingo["Open"] ;
              $Film       = $ArrSingo["Film"] ;
              $ShowDgree  = $ArrSingo["ShowDgree"] ;
              $UnitPrice  = $ArrSingo["UnitPrice"] ;

              $sQuery = "Select * From ".$sShowroomorder."   ".
                        " Where Theather   = '".$Theather."' ".
                        "   And Room       = '".$Room."'     " ;
              $QryShowroomorder = mysql_query($sQuery,$connect) ;
              if  ($ArrShowroomorder = mysql_fetch_array($QryShowroomorder))
              {
                  $Seq = $ArrShowroomorder["Seq"] ;
              }
              else
              {
                  $Seq = -1 ;
              }

              // RoomOrder �ʵ带 �����Ѵ�...
              $sQuery = "Update ".$sSingoName."                 ".
                        "   Set RoomOrder  = '".$Seq."'         ".
                        " Where SingoDate  = '".$SingoDate."'   ".
                        "   And Silmooja   = '".$Silmooja."'    ".
                        "   And Location   = '".$Location."'    ".
                        "   And Theather   = '".$Theather."'    ".
                        "   And Room       = '".$Room."'        ".
                        "   And Open       = '".$Open."'        ".
                        "   And Film       = '".$Film."'        ".
                        "   And ShowDgree  = '".$ShowDgree."'   ".
                        "   And UnitPrice  = '".$UnitPrice."'   " ;
              mysql_query($sQuery,$connect) ;
         }
    }
?>

<link rel=stylesheet href=./LinkStyle.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>������� ����</title>
</head>
<body bgcolor=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

   <SCRIPT LANGUAGE="JavaScript">
   <!--
         function clickApply()
         {
              location.href = "<?=$PHP_SELF?>?FilmTile=<?=$FilmTile?>&applySingo=Yes" ;
         }

   //-->
   </SCRIPT>

   <BR><BR><BR>
   <center><B>������� ����</B></center>

   <center>

             <?
                 /***************
                 $Seq1 = 0 ;
                 $OldTheather = "" ;

                 $sQuery = "Select * From ".$sShowroomorder." ".
                           " Order By Seq                   ";
                 $QryRoomorder = mysql_query($sQuery,$connect) ;
                 while  ($ArrRoomorder = mysql_fetch_array($QryRoomorder))
                 {
                      $Seq      = $ArrRoomorder["Seq"] ;
                      $Theather = $ArrRoomorder["Theather"] ;
                      $Room     = $ArrRoomorder["Room"] ;

                      if  ($OldTheather != $Theather)
                      {
                          $Seq1 ++ ;
                      }

                      $nTmp = ($Seq * 100 ) + ($Room+0) ;

                      $sQuery = "Update ".$sShowroomorder."       ".
                                "   Set SeqNo1   = ".$Seq1.",     ".
                                "       SeqNo2   = ".$Room."      ".
                                " Where Seq      = ".$Seq."       ".
                                "   And Theather = ".$Theather."  ".
                                "   And Room     = ".$Room."      " ;
eq($sQuery."       ".$Room) ;
                      mysql_query($sQuery,$connect) ;

                      $OldTheather = $Theather ;
                 }
                 ***************/


                 $ColorA =  '#ffebcd' ;
                 $ColorB =  '#dcdcec' ;
                 $ColorC =  '#dcdcdc' ;
                 $ColorD =  '#c0c0c0' ;
                 ?>

                 <BR>
                 <BR>

                 <TABLE cellpadding=0 cellspacing=0 border=1 bordercolor='#C0B0A0'>
                 <TR>
                      <TD width=50  bgcolor=<?=$ColorA?> align=center>����</TD>
                      <TD width=50  bgcolor=<?=$ColorA?> align=center>����</TD>
                      <TD width=50  bgcolor=<?=$ColorA?> align=center>����</TD>
                      <TD width=200 bgcolor=<?=$ColorA?> align=center>����</TD>
                      <TD width=40  bgcolor=<?=$ColorA?> align=center colspan=2>&nbsp;</TD>
                 </TR>

                 <?
                 if  ((!$LocCode) && (!$ZoneCode))
                 {
                     $sQuery = "Select * From ".$sShowroomorder." ".
                               " Order By SeqNo1, SeqNo2          ";
                     $AddedCont = "" ;
                 }
                 else
                 {
                     if  ($LocCode != "") // Ư������(3)�� ���������� ������ �� ���
                     {
                         $sQuery = "Select * From bas_location   ".
                                   " Where Code = '".$LocCode."' " ;
                         $QryLocation = mysql_query($sQuery,$connect) ;
                         if  ($ArrLocation = mysql_fetch_array($QryLocation))
                         {
                             $LocationName = $ArrLocation["Name"] ; // �����̸�
                         }

                         if  ($LocCode=="200") // ������ �λ��� (�λ�+���+����+â��)
                         {
                             $AddedCont = "   (Location = '200'  ".
                                          " Or Location = '201'  ".
                                          " Or Location = '202'  ".
                                          " Or Location = '203'  ".
                                          " Or Location = '204'  ".
                                          " Or Location = '205'  ".
                                          " Or Location = '207'  ".
                                          " Or Location = '208'  ".
                                          " Or Location = '209'  ".
                                          " Or Location = '210'  ".
                                          " Or Location = '211'  ".
                                          " Or Location = '212'  ".
                                          " Or Location = '213'  ".
                                          " Or Location = '600') " ;
                         }
                         else
                         {
                             $AddedCont = " Location = '".$LocCode."' " ;
                         }
                     }


                     if  ($ZoneCode != "") // Ư������(2)�� ���������� ������ �� ���
                     {
                         $sQuery = "Select * From bas_zone          ".
                                   " Where Code = '".$ZoneCode."'   " ;

                         $QryZone = mysql_query($sQuery,$connect) ;
                         if  ($ArrZone = mysql_fetch_array($QryZone))
                         {
                             $ZoneName = $ArrZone["Name"] ; // �����̸�
                         }


                         $AddedCont = "" ;

                         $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                                   " Where Zone  = '".$ZoneCode."'        " ;    //  eq($sQuery);
                         $QryZoneloc = mysql_query($sQuery,$connect) ;
                         while ($ArrZoneloc = mysql_fetch_array($QryZoneloc))
                         {
                             if  ($AddedCont == "")
                             {
                                 $AddedCont .= "( Location = '".$ArrZoneloc["Location"]."' " ;
                             }
                             else
                             {
                                 $AddedCont .= " Or Location = '".$ArrZoneloc["Location"]."' " ;
                             }
                         }

                         if  ($AddedCont != "")
                         {
                             if  ($ZoneCode == '20') // �泲�ΰ�� �λ��� �����Ѵ�.
                             {
                                  $AddedCont .= " Or Location  = '200' ".
                                                " Or Location <> '201' ".  // â��
                                                " Or Location <> '202' ".  // ����
                                                " Or Location <> '203' ".  // �뿵
                                                " Or Location <> '204' ".  // ����
                                                " Or Location <> '205' ".  // ����
                                                " Or Location <> '207' ".  // ����
                                                " Or Location <> '208' ".  // ����
                                                " Or Location <> '209' ".  // �о�
                                                " Or Location <> '210' ".  // ��
                                                " Or Location <> '211' ".  // ��õ
                                                " Or Location <> '212' ".  // ��â
                                                " Or Location <> '213' ".  // ���
                                                " Or Location <> '600' ";  // ���
                             }
                             $AddedCont .= ")" ;
                         }
                     }

                     $OldTheather = "" ;
                     $sQuery = "Select * From ".$sShowroomorder." ".
                               " Where ".$AddedCont."             ".
                               "  Order By SeqNo1, SeqNo2         ";   // eq($sQuery);
                 }
                 $QryRoomorder = mysql_query($sQuery,$connect) ;
                 while  ($ArrRoomorder = mysql_fetch_array($QryRoomorder))
                 {
                     $Seq      = $ArrRoomorder["Seq"] ;
                     $SeqNo1   = $ArrRoomorder["SeqNo1"] ;
                     $SeqNo2   = $ArrRoomorder["SeqNo2"] ;
                     $Location = $ArrRoomorder["Location"] ;
                     $Discript = $ArrRoomorder["Discript"] ;
                     $Theather = $ArrRoomorder["Theather"] ;

                     $sQuery = "Select * From bas_location    ".
                               " Where Code = '".$Location."' " ;
                     $QryLocation = mysql_query($sQuery,$connect) ;
                     if  ($ArrLocation = mysql_fetch_array($QryLocation))
                     {
                         $LocationName = $ArrLocation["Name"] ; // �����̸�
                     }

                     if  ($Color==$ColorC) { $Color= $ColorD ; }
                     else                  { $Color= $ColorC ; }
                     ?>
                     <TR>
                          <?
                          if  ($OldTheather != $Theather )
                          {
                              $rowspan = 1 ;
                              if  ($ColorT==$ColorC) { $ColorT= $ColorD ; }
                              else                   { $ColorT= $ColorC ; }
                              ?>
                              <TD id="h<?=$Theather?>" bgcolor=<?=$ColorT?> align=center rowspan=1><?=$SeqNo1?></TD>
                              <?
                          }
                          else
                          {
                              $rowspan++ ;
                              ?>
                              <script language="JavaScript">
                                  document.all.h<?=$Theather?>.rowSpan = <?=$rowspan?> ;
                              </script>
                              <?
                          }



                          ?>
						  <!-- ȭ���� ������ ������ �ߺ��̹Ƿ� �̸� Ǯ� ��Ƴ���.
						  <TD><?=$Theather?></TD>
						  -->

                          <TD bgcolor=<?=$Color?> align=center><?=$SeqNo2?></TD>
                          <TD bgcolor=<?=$Color?> align=center><?=$LocationName?></TD>
                          <TD bgcolor=<?=$Color?>><?=$Discript?></TD>
                          <?
                          if  ($OldTheather != $Theather )
                          {
                              ?>
                              <script language="JavaScript">
                              <!--
                              function clickMov(fromNo,toNo)
                              {
                                  edit = toNo ;

                                  if ((edit !="") && (edit.search(/\D/) != -1))
                                  {
                                      alert("���ڸ� �Է½ÿ�!") ;
                                      return false ;
                                  }
                                  else
                                  {
                                      if  ((fromNo<0) || (toNo<0))
                                      {
                                          alert("�̵��Ұ�!") ;
                                      }
                                      else
                                      {
                                          <?
                                          if  ((!$LocCode) && (!$ZoneCode))
                                          {
                                               ?>
                                               location.href = "<?=$PHP_SELF?>?FilmTile=<?=$FilmTile?>&fromNo="+fromNo+"&toNo="+toNo ;
                                               <?
                                          }
                                          else
                                          {
                                              if  ($LocCode)
                                              {
                                                  ?>
                                                  location.href = "<?=$PHP_SELF?>?FilmTile=<?=$FilmTile?>&LocCode=<?=$LocCode?>&fromNo="+fromNo+"&toNo="+toNo ;
                                                  <?
                                              }

                                              if  ($ZoneCode)
                                              {
                                                  ?>
                                                  location.href = "<?=$PHP_SELF?>?FilmTile=<?=$FilmTile?>&ZoneCode=<?=$ZoneCode?>&fromNo="+fromNo+"&toNo="+toNo ;
                                                  <?
                                              }
                                          }
                                          ?>
                                      }
                                  }
                              }
                              //-->
                              </script>

                              <?
                              $UpSeqNo1 = -1 ;

                              if  ($AddedCont=="")
                              {
                                  $sQuery = "Select * From ".$sShowroomorder." ".
                                            " Where SeqNo1 < ".$SeqNo1."       ".
                                            " Order By SeqNo1 Desc             ".
                                            " Limit 0 , 1                      " ;
                              }
                              else
                              {
                                  $sQuery = "Select * From ".$sShowroomorder." ".
                                            " Where SeqNo1 < ".$SeqNo1."       ".
                                            "   And ".$AddedCont."             ".
                                            " Order By SeqNo1 Desc             ".
                                            " Limit 0 , 1                      " ;
                              }
                              $QryShowroomorder = mysql_query($sQuery,$connect) ;
                              if  ($ArrShowroomorder = mysql_fetch_array($QryShowroomorder))
                              {
                                  $UpSeqNo1 = $ArrShowroomorder["SeqNo1"] ;
                              }

                              $DnSeqNo1 = -1 ;

                              if  ($AddedCont=="")
                              {
                                  $sQuery = "Select * From ".$sShowroomorder." ".
                                            " Where SeqNo1 > ".$SeqNo1."       ".
                                            " Order By SeqNo1 Asc              ".
                                            " Limit 0 , 1                      " ;
                              }
                              else
                              {
                                  $sQuery = "Select * From ".$sShowroomorder." ".
                                            " Where SeqNo1 > ".$SeqNo1."       ".
                                            "   And ".$AddedCont."             ".
                                            " Order By SeqNo1 Asc              ".
                                            " Limit 0 , 1                      " ;
                              }
                              $QryShowroomorder = mysql_query($sQuery,$connect) ;
                              if  ($ArrShowroomorder = mysql_fetch_array($QryShowroomorder))
                              {
                                  $DnSeqNo1 = $ArrShowroomorder["SeqNo1"] ;
                              }
                              ?>
                              <TD id="t<?=$Theather?>" bgcolor=<?=$ColorT?> align=center colspan=2>
                              <INPUT TYPE="text" name="tx<?=$Theather?>" size=5>
                              <INPUT TYPE="button" id="bu<?=$Theather?>" value="�̵�" onclick="clickMov(<?=$SeqNo1?>,tx<?=$Theather?>.value);">
                              <BR>
                              </TD>
                              <?
                          }
                          else
                          {
                              ?>
                              <script language="JavaScript">
                                  document.all.t<?=$Theather?>.rowSpan = <?=$rowspan?> ;
                              </script>
                              <?
                          }

                          $OldTheather = $Theather ;
                          ?>
                     </TR>
                     <?
                 }
                 ?>

                 </TABLE>

                 <BR>
                 <BR>
                 <INPUT TYPE="button" value="�Ű����� ����" onclick="clickApply();">
                 <BR>
                 <BR>
   </center>

</body>

</html>
<?
    mysql_close($connect);
?>

