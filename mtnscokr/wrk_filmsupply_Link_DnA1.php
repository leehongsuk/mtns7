   <?
   $CountRooms = 0 ;


   while ($ArySingo = mysql_fetch_array($QrySingo))
   {
		$CountRooms++;

        for ($i = 1 ; $i <= 12 ; $i++)
        {
            $arrySumOfDegree[$i] = 0 ;  // ȸ���� ���ھ� �հ�
        }
        $singoSilmooja     = $ArySingo["Silmooja"] ;      // �Ű�ǹ���
        $singoTheather     = $ArySingo["Theather"] ;      // �Ű�󿵰�
        $singoRoom         = $ArySingo["Room"] ;          //
        $singoOpen         = $ArySingo["Open"] ;          // �Ű�ȭ
        $singoFilm         = $ArySingo["Film"] ;          //
        $singoFilmType     = $ArySingo["FilmType"] ;      //
        $silmoojaName      = $ArySingo["SilmoojaName"] ;  // �Ű� �ǹ��ڸ�
        $showroomDiscript  = $ArySingo["Discript"] ;      // �Ű� �󿵰���
        $showroomLocation  = $ArySingo["Location"] ;      // �Ű� �󿵰�����
        $showroomMultiPlex = $ArySingo["MultiPlex"] ;     //
        $locationName      = $ArySingo["LocationName"] ;  // �Ű� �󿵰�������
        $showroomSeat      = $ArySingo["ShowRoomSeat"] ;  // �Ű� �󿵰��¼�
        $SumNumPersons     = $ArySingo["SumNumPersons"] ; // �� ���ھ�
        $showroomCntDgree  = $ArySingo["CntDgree"] ;      // ��ȸ����
        $filmExcelTitle    = $ArySingo["ExcelTitle"] ;    //

		$GikumRate = 1.03 ;
		$sQuery = "SELECT IF( '".$WorkDate."' >=  '20160523', GikumRate,  1.03 ) GikumRate ".
                  "  FROM bas_theather                                                     ".
                  " WHERE code = ".$singoTheather."                                        " ; //echo $sQuery ;
		$qry_GikumRate = mysql_query($sQuery,$connect) ;
		if  ($AryGikumRate = mysql_fetch_array($qry_GikumRate))
		{
			$GikumRate	= $AryGikumRate["GikumRate"]; // = 1.03
		}

        $sQuery = "Select Count(distinct UnitPrice) CntUnitPrice".
                  " From ".$sSingoName."                        ".
                  " Where SingoDate  <= '".$WorkDate."'         ".
                  "   And Theather   = '".$singoTheather."'     ".
                  "   And Open       = '".$singoOpen."'         ".
                  "   And Film       = '".$singoFilm."'         ".
                  " Order By UnitPrice desc                     " ;
        $qry_CntUnitPrice = mysql_query($sQuery,$connect) ;
        if  ($AryCntUnitPrice = mysql_fetch_array($qry_CntUnitPrice))
        {
            $cntUnitPrice = $AryCntUnitPrice["CntUnitPrice"] ;
        }

        // �������θ� �˻��Ѵ�.
        $sQuery = "Select * From bas_silmoojatheatherfinish  ".
                  " Where Silmooja = '".$singoSilmooja."'    ".
                  "   And WorkDate <= '".$WorkDate."'        ".
                  "   And Theather = '".$singoTheather."'    ".
                  "   And Room     = '".$singoRoom."'        ".
                  "   And Open     = '".$singoOpen."'        ".
                  "   And Film     = '".$singoFilm."'        " ;
        $qry_silmoojatheatherfinish = mysql_query($sQuery,$connect) ;
        if  ($ArySilmoojatheatherfinish = mysql_fetch_array($qry_silmoojatheatherfinish))
        {
            $isFinished = true ;                                   // ������ �Ǿ���

            $TempDate = $ArySilmoojatheatherfinish["WorkDate"] ; // ��������

            // �Ϸ� ������ ���Ѵ�.
            $FinishDate = date("Ymd",strtotime("-1 day",strtotime(substr($TempDate,0,4)."-".substr($TempDate,4,2)."-".substr($TempDate,6,2).""))) ;
        }
        else
        {
            $isFinished = false ;  // �������� �ʾ���
            $FinishDate = "" ;     //
        }


        // ��ȭ ������ ���ϵ� ��ȭ�� �ٲ�� �������� �����ϰ�
        // �ι��̻� �ݺ��Ǹ� ��ȭ���� �����.
        if  ($filmtitleNameTitle != $ArySingo["FilmTitleName"])
        {
            $filmtitleName      = $ArySingo["FilmTitleName"] ;
            $filmtitleNameTitle = $ArySingo["FilmTitleName"] ;
        }
        else
        {
            $filmtitleName = "" ;
        }

        mysql_free_result($qry_silmoojatheatherfinish) ;


       // ��ȭ���� ��� (��ȭ�Ǵ� ��������,)..
       if   (($filmtitleName!="") and ($FilmCode<>'00'))
       {
       ?>
           <?
           $WorkTime = mktime(0,0,0,substr($WorkDate,4,2),substr($WorkDate,6,2),substr($WorkDate,0,4));
           $OpenTime = mktime(0,0,0,substr($singoOpen,2,2),substr($singoOpen,4,2),"20".substr($singoOpen,0,2));
           $dur_day  = ($WorkTime - $OpenTime) / 86400  ;

           if  ($WorkGubun != 29)
           {
               ?>
               <table name=score cellpadding=0 cellspacing=0 border=1 bordercolor="#FFFFFF" width=100%>
               <tr>

                   <td align=left class=textare>
                   ������:<?=substr($singoOpen,0,2)."/".substr($singoOpen,2,2)."/".substr($singoOpen,4,2)?>
                   </td>

                   <td align=center colspan=19>

                       <!-- ��ȭ������� -->
                       <b><?=$filmtitleName?></b>

                       <?
                       if ($ToExel) // ����
                       {
                           ?>
                           <BR><?=$filmExcelTitle?>
                           <?
                       }
                       ?>

                   </td>

                   <td align=right class=textare>
                       �����Ϸ� ���� <?=($dur_day+1)?>��°..<br>
                       ��ȸ��:<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>  <br>
                       �ð�:<?=date("h:i:s") ?>
                   </td>

               </tr>
               </table>
               <?
           }
           else
           {
               ?>
               <table name=score cellpadding=0 cellspacing=0 border=0 width=100%>
               <tr>
                   <td align=center colspan=21>
                       <!-- ��ȭ������� -->
                       <b><?=$filmtitleName?></b>

                       <?
                       if ($ToExel)  // ����
                       {
                           ?>
                           <BR><?=$filmExcelTitle?>
                           <?
                       }
                       ?>
                   </td>
               </tr>
               <tr>

               <td align=left class=textare width=320>
                   <?=($dur_day+1)?>����
                   ������:<?=substr($singoOpen,0,2)."/".substr($singoOpen,2,2)."/".substr($singoOpen,4,2)?>
                   ����:<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>
               </td>

               <td align=center colspan=19>

               </td>

               <td align=right class=textare width=320>
                  &nbsp;
               </td>
               </tr>
               </table>
               <?
           }
           ?>
           <br>
       <?
       }

       // �� ���¼��� = ȸ���� * �󿵰� �ڸ���
       $showroomTotDgree = $showroomCntDgree * $showroomSeat ;

       if  ($showroomTotDgree==0)
       {
           $rateSeat = "[0%]" ;
       }
       else
       {
           if  ($SumNumPersons > 0)
           {
               // ������ = ( �� ���ھ� / �� ���¼��� ) * 100 [%]  $SumNumPersons
               $rateSeat = "[".round(($SumNumPersons / $showroomTotDgree)*100.0)."%]" ;
           }
           else
           {
               $rateSeat = "[0%]" ;
           }
       }

       if  ($oldsingoTheather != $singoTheather)
       {
           $clrToggle = !$clrToggle ;

           $oldsingoTheather = $singoTheather ;
       }

       if  ($ToExel)   // ����
       {
           $Color1 = "#ffffff" ;
           $Color2 = "#ffffff" ;
           $Color3 = "#ffffff" ;
           $Color4 = "#ffffff" ;
       }
       else
       {
           if  ($clrToggle==true)
           {
               $Color1 = "#b0c4de" ;
               $Color2 = "#efebcd" ;
               $Color3 = "#dcdcdc" ;
               $Color4 = "#c0c0c0" ;
           }
           else
           {
               $Color1 = "#c0d4ee" ;
               $Color2 = "#fffbdd" ;
               $Color3 = "#ececec" ;
               $Color4 = "#d0d0d0" ;
           }
       }


       $ExitTheather = false ; // �ϴ� ��翵ȭ�� ��Ÿ����..

       if  ($TimJang == true)  // �����̰�..
       {
           $sQuery = "Select Count(*) As cntSmschk           ".
                     "  From wrk_smschk                      ".
                     " Where Open     = '".$singoOpen."'     ".
                     "   And Film     = '".$singoFilm."'     ".
                     "   And Theather = '".$singoTheather."' ".
                     "   And Chk".$TimJangNo." = 'Y'         " ;
           $QrySmschk = mysql_query($sQuery,$connect) ;
           if  ($ArySmschk = mysql_fetch_array($QrySmschk) )
           {
               if  ($ArySmschk["cntSmschk"]==0) // ��ȭ�� ���õ��� �ʾҴٸ�.
               {
                   $ExitTheather = true ;  // ��ȭ�� ������
               }
           }
           else
           {
               $ExitTheather = true ;  // ��ȭ�� ������
           }
       }

       if  ($ExitTheather == false)
       {
           // 35mm�� 35, ������ ����� 2 ������ ������� 3 ���̸ƽ� ����� 29 ���̸ƽ� ������� 39 -
           /*
           if  ($nFilmTypeNo != "-1")
           {
               $FilmType = 0 ;

               $sQuery = "Select Type From ".$sFilmTypePrv."      ".
                         " Where WorkDate <= '".$WorkDate."'      ".
                         "   And Open     = '".$FilmOpen."'       ".
                         "   And Code     = '".$FilmCode."'       ".
                         "   And Theather = '".$singoTheather."'  ".
                         "   And Room     = '".$singoRoom."'      ".
                         " Order By WorkDate desc                 ".
                         " Limit 0 , 1                            "; //if  ($singoTheather =="2605") echo $sQuery."<BR>" ;
//echo $sQuery."<BR>" ;
               $QryFilmType = mysql_query($sQuery,$connect) ;
               if  ($ArrFilmType = mysql_fetch_array($QryFilmType))
               {
                    $FilmType = $ArrFilmType["Type"] ;
               }

               if   ($FilmType == 0)
               {
                    $sQuery = "Select Type From ".$sFilmType."         ".
                              " Where Open     = '".$FilmOpen."'       ".
                              "   And Code     = '".$FilmCode."'       ".
                              "   And Theather = '".$singoTheather."'  ".
                              "   And Room     = '".$singoRoom."'      "; //echo $sQuery."<BR>" ;
                    $QryFilmType = mysql_query($sQuery,$connect) ;
                    if  ($ArrFilmType = mysql_fetch_array($QryFilmType))
                    {
                        $FilmType = $ArrFilmType["Type"] ;
                    }
                    else
                    {
                        $sQuery = "Insert Into ".$sFilmType."    ".
                                  " Value                        ".
                                  "(                             ".
                                  "   '".$FilmOpen."',           ".
                                  "   '".$FilmCode."',           ".
                                  "   '".$singoTheather."',      ".
                                  "   '".$singoRoom."',          ".
                                  "   '35'                       ".
                                  ")                             " ; //echo $sQuery."<BR>" ;
                        mysql_query($sQuery,$connect) ;

                        $sQuery = "Insert Into ".$sFilmTypePrv." ".
                                  " Value                        ".
                                  "(                             ".
                                  "   '".$WorkDate."',           ".
                                  "   '".$FilmOpen."',           ".
                                  "   '".$FilmCode."',           ".
                                  "   '".$singoTheather."',      ".
                                  "   '".$singoRoom."',          ".
                                  "   '35'                       ".
                                  ")                             " ;
                        mysql_query($sQuery,$connect) ;//echo $sQuery."<BR>" ;

                        $FilmType = 35 ;
                    }
               }

               if   ($FilmType != 0) $EchoFilmType = "<BR>".$FilmType  ;
               else                  $EchoFilmType = "" ;
           }
           else
           {
               $EchoFilmType = "" ;
           }
           */
           if   (($nFilmTypeNo == $singoFilmType) || ($nFilmTypeNo == "") || ($nFilmTypeNo == "0"))
           {
           ?>

           <table style='table-layout:fixed' name=score cellpadding=0 cellspacing=0 border=1 bordercolor=#C0B0A0>

           <tr>
                 <!--             -->
                 <!-- Ÿ��Ʋ ��� -->
                 <!--             -->

                 <!-- ���� �� ���� -->
                 <td class=textarea width=40 bgcolor=<?=$Color1?> align=center rowspan=<?=$cntUnitPrice+2?>>

                     <!-- ���� -->
                     <span style="background-color:white;">
                     <?// =$CountRooms?>
                     <font color=#boc4de><B><?=$zoneName?></B></font>
                     </span>

                     <br>

                     <!-- ���� -->
                     <B><?=$locationName?></B>

                     <?
                     if  ($ToExel)  // ����
                     {
                         ?>
                         <br><?=$singoRoom?>
                         <br><?=$singoTheather?>
                         <?
                     }

                     if  ((!$filmproduce) && ($TimJang==false)) // �̺��� // �Ϲݹ�޻� �������� ��� �ö��� �ѷ��ش�.
                     {
                         ?>
                         <a href="#" OnClick="endingShowroom('<?=$singoSilmooja?>','<?=$singoTheather.$singoRoom?>','<?=$FilmTile?>')">����<br>ó��</a>
                         <?
                     }

                     //echo $EchoFilmType ;
                     echo "<BR>".$singoFilmType ;
                     ?>
                 </td>

                 <!-- �󿵰����� �󿵰���,�¼���,������ -->
                 <td class=textarea width=120 bgcolor=<?=$Color2?> align=center rowspan=<?=$cntUnitPrice+2?>>


                     <B><?=$showroomDiscript?></B><br> <!-- �󿵰��� -->
                                                       <!--[�󿵰��ڵ�(<?=$singoTheather?>,<?=$singoRoom?>)]      -->
                                                       <!-- ��ȭ�ڵ�(<?=substr($FilmTile,0,6)?>,<?=substr($FilmTile,6,2)?>) -->
                     <?
                     if  (!$filmproduce) // �Ϲݹ�޻� �������� ��� �ö��� �ѷ��ش�.
                     {
                     ?>
                         <!-- �¼��� -->
                         <!-- ������ -->
                         <!-- �ǹ����̸� [�ǹ����ڵ�(<?=$singoSilmooja?>)] -->
                         <!-- �ǹ��ھ絵 (wrk_filmsupply_Link_Chg.php) -->
                         <!-- ���ھ���� (wrk_filmsupply_Link_UpM.php) -->

                         <B><?=$showroomSeat?>��</B><br>


                         <?
                         if  ($TimJang==false) // �̺���
                         {
                         ?>
                         <B><?=$rateSeat?></B><br>
                         <?=$silmoojaName?>

                         <a href="#" onclick="yangdo_click('<?=$singoSilmooja?>','<?=$singoTheather.$singoRoom?>','<?=$FilmTile?>')">[�絵]</a>
                         <br>

                         <a href="#" onclick="modify_click('<?=$singoSilmooja?>','<?=$singoTheather.$singoRoom?>','<?=$FilmTile?>')">[����]</a>
                         <a href="#" onclick="delect_click('<?=$singoSilmooja?>','<?=$singoTheather.$singoRoom?>','<?=$FilmTile?>','<?=$singoOpen?>','<?=$singoFilm?>');">[����]</a>

                         <?
                         }

                         /*
                         $sQuery = "Select * From chk_extension_day          ". /////////////////////////////////////////////////////////
                                   " Where Theather   = '".$singoTheather."' ".
                                   "   And ObjectDay  = '".$WorkDate."'      ".
                                   "   And Open       = '".$singoOpen."'     ".
                                   "   And Film       = '".$singoFilm."'     " ;
                         $QryExtension = mysql_query($sQuery,$connect) ;
                         if  ($ArrExtension  = mysql_fetch_array($QryExtension))
                         {
                             echo "<BR>" ;
                             if  ($ArrExtension["Gubun"]=="Er") echo "<font color='red'>����ġ</font>" ;
                             if  ($ArrExtension["Gubun"]=="Ok") echo "<font color='red'>��ġ</font>" ;
                             if  ($ArrExtension["Damdangja"] != "")
                             {
                                 echo  " [".$ArrExtension["Damdangja"]."]" ;
                             }
                         }
                         */
                         ?>
                     <?
                     /********************
                     $sQuery = "Select Phoneno From ".$sSingoName."      ".
                               " Where SingoDate  = '".$WorkDate."'      ".
                               "   And Theather   = '".$singoTheather."' ".
                               "   And Room       = '".$singoRoom."'     ".
                               "   And Open       = '".$singoOpen."'     ".
                               "   And Film       = '".$singoFilm."'     " ;
                     $QryPhone = mysql_query($sQuery,$connect) ;
                     if  ($ArrPhone  = mysql_fetch_array($QryPhone))
                     {
                         if  ($ArrPhone["Phoneno"]!="")
                         {
                         echo  "<BR>hp:" .$ArrPhone["Phoneno"]  ;
                         }
                         else
                         {
                         echo  "<BR>" ;
                         }
                     }
                     *****************/
                     echo "<BR><B>".$singoTheather."</B>" ;
                     //echo "<BR><B>(".$showroomMultiPlex.")</B>" ;

                     }
                     else // ��ȭ�� �������� ������ ��
                     {
                     ?>
                         <B><?=$showroomSeat?>��</B><br> <!-- �¼��� -->
                         <B><?=$rateSeat?></B><br>       <!-- ������ -->
                     <?
                     }


                     ?>


                     <br>
                     <!-- �󿵰� ���� ������ ��Ÿ����. -->


                     <!-- ��ȭ�ڵ�(<?=substr($FilmTile,0,6)?>,<?=substr($FilmTile,6,2)?>) -->
                     <?
                     $MovPrn = false ;
                     ?>

                 </td>


                 <?
                 for ($i = 1 ; $i <= 11 ; $i++)
                 {
                     //
                     // �ǹ��ڰ� "111111", "222222" �϶�..
                     //    ������ ȸ�������� �����.
                     //
                     if   ($singoSilmooja == "777777")
                     {
                          // ����ȸ�� ���翩��Ȯ�� ..
                          $sQuery = "Select * From ".$sDgrpName."           ".
                                    " Where Silmooja = '".$singoSilmooja."' ".
                                    "   And WorkDate = '".$WorkDate."'      ".
                                    "   And Open     = '".$singoOpen."'     ".
                                    "   And Film     = '".$singoFilm."'     ".
                                    "   And Theather = '".$singoTheather."' ".
                                    "   And Room     = '".$singoRoom."'     " ;
                          $qry_degreepriv = mysql_query($sQuery,$connect) ;
                          $degreepriv_data  = mysql_fetch_array($qry_degreepriv) ;
                          if  (!$degreepriv_data) // ���� ȸ�� ������ ���ٸ�..
                          {
                              $sQuery = "Insert Into ".$sDgrpName."  ".
                                        "Values                      ".
                                        "(                           ".
                                        "    '".$singoSilmooja."',   ".
                                        "    '".$WorkDate."',        ".
                                        "    '".$singoOpen."',       ".
                                        "    '".$singoFilm."',       ".
                                        "    '".$singoTheather."',   ".
                                        "    '".$singoRoom."',       ".
                                        "    '01',                   ".
                                        "    '10000',                ".
                                        "    '".$degreeDiscript."'   ".
                                        ")                           " ;
                              mysql_query($sQuery,$connect) ;
                          }
                     }
                     if   (($singoSilmooja == "111111") || ($singoSilmooja == "222222"))
                     {
                          // ����ȸ�� ���翩��Ȯ�� ..
                          $sQuery = "Select * From ".$sDgrpName."           ".
                                    " Where Silmooja = '".$singoSilmooja."' ".
                                    "   And WorkDate = '".$WorkDate."'      ".
                                    "   And Open     = '".$singoOpen."'     ".
                                    "   And Film     = '".$singoFilm."'     ".
                                    "   And Theather = '".$singoTheather."' ".
                                    "   And Room     = '".$singoRoom."'     " ;
                          $qry_degreepriv = mysql_query($sQuery,$connect) ;
                          $degreepriv_data  = mysql_fetch_array($qry_degreepriv) ;
                          if  (!$degreepriv_data) // ���� ȸ�� ������ ���ٸ�..
                          {
                              $sQuery = "Select * From ".$sDgrName."                ".
                                        " Where Silmooja = '".$singoSilmooja."'  ".
                                        "   And Open     = '".$singoOpen."'      ".
                                        "   And Film     = '".$singoFilm."'      ".
                                        "   And Theather = '".$singoTheather."'  ".
                                        "   And Room     = '".$singoRoom."'      " ;
                              $qry_degree = mysql_query($sQuery,$connect) ;

                              while ($degree_data  = mysql_fetch_array($qry_degree))
                              {
                                  // ������ ȸ�������� �����.

                                  $Degree         = $degree_data["Degree"] ;   // ������ ȸ��.
                                  $degreeTime     = $degree_data["Time"] ;     // ������ �ð�.
                                  $degreeDiscript = $degree_data["Discript"] ; // ������ �󿵰��̸�.

                                  $sQuery = "Insert Into ".$sDgrpName."  ".
                                            "Values                      ".
                                            "(                           ".
                                            "    '".$singoSilmooja."',   ".
                                            "    '".$WorkDate."',        ".
                                            "    '".$singoOpen."',       ".
                                            "    '".$singoFilm."',       ".
                                            "    '".$singoTheather."',   ".
                                            "    '".$singoRoom."',       ".
                                            "    '".$Degree."',          ".
                                            "    '".$degreeTime."',      ".
                                            "    '".$degreeDiscript."'   ".
                                            ")                           " ;
                                  mysql_query($sQuery,$connect) ;
                              }
                          }
                     }
                 }
                 ?>


                 <!-- ��� -->
                 <?
                 if  ($WorkGubun != 29)
                 {
                     ?>
                     <td class=textarea width=40 bgcolor=<?=$Color3?> align=center>
                     ���
                     </td>
                     <?
                 }
                 ?>


                 <?
                 if  (($WorkGubun == 27) || ($WorkGubun == 29))
                 {
                     ?>
                     <td class=textarea width=40 bgcolor=<?=$Color3?> align=center>
                     ���<BR>���
                     </td>
                     <?
                 }
                 ?>

                 <?
                 // �ֱ�ȸ�� ���翩��Ȯ�� ���ڸ� ����..
                 $sQuery = "Select Workdate From ".$sDgrpName."    ".
                           " Where Open     = '".$singoOpen."'     ".
                           "   And Film     = '".$singoFilm."'     ".
                           "   And Theather = '".$singoTheather."' ".
                           "   And Room     = '".$singoRoom."'     ".
                           "   And Degree   <> ''                  ".
                           "   And Degree   <> '00'                ".
                           "   And Workdate <= '".$WorkDate."'     ".
                           " Order By WorkDate Desc                ".
                           " LIMIT 0 , 1                           " ;
                 $qry_degreepriv = mysql_query($sQuery,$connect) ;
                 if  ($degreepriv_data  = mysql_fetch_array($qry_degreepriv)) // ���� ȸ�� ����
                 {
                     $LastWorkDate = $degreepriv_data["Workdate"] ; // �ֱ�ȸ�� ���� ..
                 }
                 else
                 {
                     $LastWorkDate = "" ;
                 }

                 $LastDegree = 10 ;

                 $sQuery = "Select ShowDgree, SingoTime                 ".
                           "  From ".$sSingoName."                      ".
                           " Where SingoDate  = '".$WorkDate."'         ".
                           "   And Silmooja   = '".$singoSilmooja."'    ".
                           "   And Theather   = '".$singoTheather."'    ".
                           "   And Room       = '".$singoRoom."'        ".
                           "   And Open       = '".$singoOpen."'        ".
                           "   And Film       = '".$singoFilm."'        ".
                           " Group By ShowDgree                         ".
                           " Order By ShowDgree                         " ;
                 $QrySingoTime = mysql_query($sQuery,$connect) ;
                 $ArrSingoTime = mysql_fetch_array($QrySingoTime) ;

                 for ($i = 1 ; $i <= $LastDegree ; $i++)
                 {
                     $strdegree = sprintf("%2d",$i) ;

                     $SingoTime =  "__:__" ;
                     /*
                     if  ($ArrSingoTime["ShowDgree"] == $strdegree)
                     {
                         $SingoTime = substr($ArrSingoTime["SingoTime"],8,2).":".substr($ArrSingoTime["SingoTime"],10,2) ;
                         $ArrSingoTime = mysql_fetch_array($QrySingoTime) ;
                     }
                     if  (($i == $LastDegree) && ($ArrSingoTime["ShowDgree"] == "99"))
                     {
                         $SingoTime = substr($ArrSingoTime["SingoTime"],8,2).":".substr($ArrSingoTime["SingoTime"],10,2) ;
                         $ArrSingoTime = mysql_fetch_array($QrySingoTime) ;
                     }
                     */

                     if  ($i<$LastDegree) // 1ȸ ���� 10ȸ ����..
                     {
                         $CntDgree = $count_data["CntDgree"] ;

                         $sQuery = "Select Time From ".$sDgrpName."            ".
                                   " Where Silmooja = '".$singoSilmooja."'     ".
                                   "   And WorkDate = '".$LastWorkDate."'      ".
                                   "   And Open     = '".$singoOpen."'         ".
                                   "   And Film     = '".$singoFilm."'         ".
                                   "   And Theather = '".$singoTheather."'     ".
                                   "   And Room     = '".$singoRoom."'         ".
                                   "   And Degree   = '".sprintf("%02d",$i)."' " ;
                         $qry_degreepriv = mysql_query($sQuery,$connect) ;
                         if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
                         {
                             $Hour = substr($degreepriv_data["Time"],0,2) ;
                             $Mint = substr($degreepriv_data["Time"],2,2) ;
                             ?>
                             <td class=textarea width=39 bgcolor=<?=$Color3?> align=center>
                             <?=$strdegree?>ȸ<br><?=$Hour?>:<?=$Mint?><?
                             if  (!$filmproduce) // �Ϲݹ�޻� �������� ��� �ö��� �ѷ��ش�.
                             {
                                     ?><!-- <br><FONT COLOR="red"><?=$SingoTime?></FONT> --><?
                             }
                             ?></td>
                             <?
                         }
                         else
                         {
                             ?>
                             <td class=textarea width=39 bgcolor=<?=$Color3?> align=center>
                             <font color="silver"><?=$strdegree?>ȸ<br>__:__<?
                             if  (!$filmproduce) // �Ϲݹ�޻� �������� ��� �ö��� �ѷ��ش�.
                             {
                                     ?><!-- <br>__:__ --><?
                             }
                             ?>
                             </td></font>
                             <?
                         }
                         $agree = true ;
                     }
                     else // �ɾ�
                     {
                         $sQuery = "Select Time From ".$sDgrpName."            ".
                                   " Where Silmooja = '".$singoSilmooja."'     ".
                                   "   And WorkDate = '".$LastWorkDate."'      ".
                                   "   And Open     = '".$singoOpen."'         ".
                                   "   And Film     = '".$singoFilm."'         ".
                                   "   And Theather = '".$singoTheather."'     ".
                                   "   And Room     = '".$singoRoom."'         ".
                                   "   And Degree   = '99'                     " ;
                         $qry_degreepriv = mysql_query($sQuery,$connect) ;
                         if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
                         {
                             $Hour = substr($degreepriv_data["Time"],0,2) ;
                             $Mint = substr($degreepriv_data["Time"],2,2) ;
                             ?>
                             <td class=textarea width=39 bgcolor=<?=$Color3?> align=center>
                             �ɾ�<br><?=$Hour?>:<?=$Mint?><?
                             if  (!$filmproduce) // �Ϲݹ�޻� �������� ��� �ö��� �ѷ��ش�.
                             {
                                 ?><!-- <br><FONT COLOR="red"><?=$SingoTime?></FONT> --><?
                             }
                             ?>
                             </td>
                             <?
                         }
                         else
                         {
                             ?>
                             <td class=textarea width=39 bgcolor=<?=$Color3?> align=center>
                             <font color="silver">�ɾ�<br>__:__<?
                             if  (!$filmproduce) // �Ϲݹ�޻� �������� ��� �ö��� �ѷ��ش�.
                             {
                                     ?><!-- <br>__:__ --><?
                             }
                             ?></font>
                             </td>

                             <?
                         }

                         $agree = true ;
                     }
                 }

                 //mysql_free_result($QrySingo1) ; ?????
                 ?>

                 <!-- ���� �հ� -->
                 <td class=textarea width=50 bgcolor=<?=$Color3?> align=center>
                 <?=$NBSP?>����<?=$NBSP?><br><?=$NBSP?>�հ�<?=$NBSP?>
                 </td>

                 <!-- ���� �հ� -->
                 <td class=textarea width=60 bgcolor=<?=$Color3?> align=center>
                 <?=$NBSP?>����<?=$NBSP?><br><?=$NBSP?>�հ�<?=$NBSP?>
                 </td>

                 <!-- ���� -->
                 <td class=textarea width=60 bgcolor=<?=$Color3?> align=center>
                 <?=$NBSP?>����<?=$NBSP?>
                 </td>

                 <?
                 if  ($TimJang==false)
                 {
                 ?>
                     <!-- ���� �ݾ� -->
                     <td class=textarea width=70 bgcolor=<?=$Color3?> align=center>
                     <?=$NBSP?>����<?=$NBSP?><br><?=$NBSP?>�ݾ�<?=$NBSP?>
                     </td>



                     <?
                     $AccWidth = 100 ;
                     ?>


                     <?
                     if  ($WorkGubun == 29)
                     {
                         ?>
                         <!-- ���� �ݾ� -->
                         <td class=textarea width=70 bgcolor=<?=$Color3?> align=center>
                         <?=$NBSP?>�������<?=$NBSP?><br><?=$NBSP?>���� �ݾ�<?=$NBSP?>
                         </td>

                         <!-- ���� �ݾ� -->
                         <td class=textarea width=<?=$AccWidth?> bgcolor=<?=$Color3?> align=center>
                         <?=$NBSP?>����<?=$NBSP?><br><?=$NBSP?>�ݾ�<?=$NBSP?>
                         </td>
                         <?
                     }
                     else
                     {
                         ?>
                         <!-- ���� �ݾ� -->
                         <td class=textarea width=<?=$AccWidth?> bgcolor=<?=$Color3?> align=center>
                         <?=$NBSP?>����<?=$NBSP?><br><?=$NBSP?>�ݾ�<?=$NBSP?>
                         </td>

                         <!-- ���� �ݾ� -->
                         <td class=textarea width=70 bgcolor=<?=$Color3?> align=center>
                         <?=$NBSP?>�������<?=$NBSP?><br><?=$NBSP?>���� �ݾ�<?=$NBSP?>
                         </td>

                         <!-- ���� �ݾ� -->
                         <td class=textarea width=<?=$AccWidth?> bgcolor=<?=$Color3?> align=center>
                          <?=$NBSP?>�������<?=$NBSP?><br><?=$NBSP?>���� �ݾ�<?=$NBSP?>
                         </td>

                         <!-- ��� -->
                         <td class=textarea width=100 bgcolor=<?=$Color3?> align=center>
                         <?=$NBSP?>���<?=$NBSP?><br>
                         <a href='#' onclick="bigo_click('<?=$singoSilmooja?>','<?=$singoTheather.$singoRoom?>','<?=$FilmTile?>')">
                         [���]
                         </a>
                         <?
                     }
                     ?>
                 <?
                 }
                 ?>
                 </td>

                 <?
                    $DataRow = 1 ; // ��� �÷��� ��� ���� ù��° ���� �ÿ���´�.
                 ?>
           </tr>

           <?
           //$SumOf01Degree = 0 ; // 01 ȸ�� �հ�
           //$SumOf02Degree = 0 ; // 02 ȸ�� �հ�
           //$SumOf03Degree = 0 ; // 03 ȸ�� �հ�
           //$SumOf04Degree = 0 ; // 04 ȸ�� �հ�
           //$SumOf05Degree = 0 ; // 05 ȸ�� �հ�
           //$SumOf06Degree = 0 ; // 06 ȸ�� �հ�
           //$SumOf07Degree = 0 ; // 07 ȸ�� �հ�
           //$SumOf08Degree = 0 ; // 08 ȸ�� �հ�
           //$SumOf09Degree = 0 ; // 09 ȸ�� �հ�
           //$SumOf10Degree = 0 ; // 10 ȸ�� �հ�
           //$SumOf11Degree = 0 ; // 11 ȸ�� �հ�

           $SumOf99Degree = 0 ; // �ɾ� ȸ�� �հ�
           $SumOfPsToday  = 0 ; // ���� �հ� �հ�
           $SumOfPsAgoDay = 0 ; // ���� �հ� �հ�
           $SumOfPsAcc    = 0 ; // ���� �հ�
           $SumOfAmToday  = 0 ; // ���ϱݾ� �հ�
           $SumOfAmToday3 = 0 ; // ���ϱݾ� �հ�
           $SumOfAmAcc    = 0 ; // ����ݾ� �հ�
           $SumOfAmAcc3   = 0 ; // ����ݾ� �հ�

           $isFinishBlock = false ;

           // ������ ��ݴ뿪�� ���Ѵ�. - �� ��ݺ��� ����Ÿ�� ��´�.
           $sQuery = "Select distinct UnitPrice                ".
                     "  From ".$sSingoName."                   ".
                     " Where SingoDate  <= '".$WorkDate."'     ".
                     "   And Theather   = '".$singoTheather."' ".
                     "   And Open       = '".$singoOpen."'     ".
                     "   And Film       = '".$singoFilm."'     ".
                     " Order By UnitPrice desc                 " ;
           $QrySingo3 = mysql_query($sQuery,$connect) ;
           while  ($UnitPrice_data = mysql_fetch_array($QrySingo3))
           {
                $SumOfToday = 0 ;

                $UnitPrice  = $UnitPrice_data["UnitPrice"] ;  // ��ݺ� ���ھ�..
                ?>



           <!--             -->
           <!-- ����Ÿ ��� -->
           <!--             -->
           <tr>
                <?
                // ��� (0=������) �� ��´�.
                if  ($UnitPrice > 0)
                {
                    if  ($WorkGubun != 29)
                    {
                        //if ($ToExel) { $sValue = $UnitPrice ; }
                        /*else */      { $sValue = "$NBSP".number_format($UnitPrice)."$NBSP" ; }
                        ?>
                        <td class=textarea bgcolor=<?=$Color3?> align=right>
                        <?=$sValue?>
                        </td>
                        <?
                    }
                    if  (($WorkGubun == 27) || ($WorkGubun == 29))
                    {
                        //if ($ToExel) { $sValue = round($UnitPrice / $GikumRate) ; }
                        /*else*/       { $sValue = "$NBSP".number_format(round($UnitPrice / $GikumRate))."$NBSP" ; }
                        ?>
                        <td class=textarea bgcolor=<?=$Color3?> align=right>
                        <?=$sValue?>
                        </td>
                        <?
                    }
                }
                else
                {
                    if  ($WorkGubun != 29)
                    {
                    ?>
                    <td class=textarea bgcolor=<?=$Color3?> align=center>
                    ������
                    </td>
                    <?
                    }
                    if  (($WorkGubun == 27) || ($WorkGubun == 29))
                    {
                    ?>
                    <td class=textarea bgcolor=<?=$Color3?> align=center>
                    ������
                    </td>
                    <?
                    }
                }



                if  ($isFinished == true)
                {
                    if  ($isFinishBlock == false)
                    {
                        ?>
                        <td class=textarea bgcolor=<?=$Color3?> colspan=11 rowspan=<?=($cntUnitPrice+1)?> align=center valign=middle>
                        ����(<?=substr($FinishDate,2,2)?>/<?=substr($FinishDate,4,2)?>/<?=substr($FinishDate,6,2)?>)ó����
                        </td>

                        <?
                    }
                    $isFinishBlock = true ;
                }
                else
                {

                    $sQuery = "Select ShowDgree, NumPersons                ".
                              "  From ".$sSingoName."                      ".
                              " Where SingoDate  = '".$WorkDate."'         ".
                              "   And Silmooja   = '".$singoSilmooja."'    ".
                              "   And Theather   = '".$singoTheather."'    ".
                              "   And Room       = '".$singoRoom."'        ".
                              "   And Open       = '".$singoOpen."'        ".
                              "   And Film       = '".$singoFilm."'        ".
                              "   And UnitPrice  = '".$UnitPrice."'        ".
                              " Group By ShowDgree                         ".
                              " Order By ShowDgree                         " ;
                    $QrySingo2 = mysql_query($sQuery,$connect) ;

                    $agree = true ;

                    for ($i = 1 ; $i <= $LastDegree ; $i++)
                    {

                        if  ($agree==true) // ��ġ�ϴ� �ڷᰡ ���� ��� �� �� ���ڵ徿 �д´�.
                        {
                            $NumPersons_data = mysql_fetch_array($QrySingo2) ;
                        }

                        if  ($i<$LastDegree) // 1ȸ ���� 10ȸ ����..
                        {
                            if  ($NumPersons_data["ShowDgree"] == sprintf("%02d",$i))
                            {
                                $SumOfDegree = "SumOf".sprintf("%02d",$i)."Degree" ;

                                $arrySumOfDegree[$i] += ($NumPersons_data["NumPersons"]) ; // ȸ���� ���ھ� �հ�

                                $SumOfToday   += $NumPersons_data["NumPersons"] ;
                                $$SumOfDegree += $NumPersons_data["NumPersons"] ;
                                ?>

                                <td class=textarea bgcolor=<?=$Color3?> align=right>
                                <?=$NBSP?><?=$NumPersons_data["NumPersons"]?><?=$NBSP?>
                                </td>

                                <?

                                $agree = true ;
                            }
                            else
                            {
                                ?>
                                <td class=textarea bgcolor=<?=$Color3?> align=right><?=$NBSP?></td>
                                <?

                                $agree = false ;
                            }
                        }
                        else // �ɾ�
                        {
                            if  ($NumPersons_data["ShowDgree"] == "99")
                            {
                                $arrySumOfDegree[$i] += $NumPersons_data["NumPersons"] ; // ȸ���� ���ھ� �հ�

                                $SumOfToday    += $NumPersons_data["NumPersons"] ;
                                $SumOf99Degree += $NumPersons_data["NumPersons"] ;

                                ?>

                                <td class=textarea bgcolor=<?=$Color3?> align=right>
                                <?=$NBSP?><?=$NumPersons_data["NumPersons"]?><?=$NBSP?>
                                </td>

                                <?

                                $agree = true ;
                            }
                            else
                            {
                                ?>
                                <td class=textarea bgcolor=<?=$Color3?> align=center><?=$NBSP?></td>
                                <?

                                $agree = false ;
                            }
                        }
                    }
                }




                // �����հ� ���
                $SumOfPsToday += $SumOfToday ; // ���� �հ� �հ�

                //if ($ToExel) { $sValue = $SumOfToday; }
                /*else*/       { $sValue = "$NBSP".number_format($SumOfToday)."$NBSP"; }
                ?>
                <td class=textarea bgcolor=<?=$Color3?> align=right>
                <?=$sValue?>
                </td>
                <?


                $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore   ".
                          "  From bas_modifyscore                        ".
                          " Where Theather   = '".$singoTheather."'      ".
                          //"   And Room       = '".$singoRoom."'        ".
                          "   And Open       = '".$singoOpen."'          ".
                          "   And Film       = '".$singoFilm."'          ".
                          "   And UnitPrice  = '".$UnitPrice."'          ".
                          "   And ModifyDate = '".$AgoDate."'            " ;
                $qry_Agomodifyscore  = mysql_query($sQuery,$connect) ;
                if  ($Agomodifyscore_data = mysql_fetch_array($qry_Agomodifyscore))
                {
                    $AgoModifyScore = $Agomodifyscore_data["SumOfModifyScore"] ;
                }
                else
                {
                    $AgoModifyScore = 0 ;
                }
                // �����հ�
                $sQuery = "Select Sum(NumPersons) As SumNumPersons    ".
                          "  From ".$sSingoName."                     ".
                          " Where SingoDate  = '".$AgoDate."'         ".
                          "   And Theather   = '".$singoTheather."'   ".
                          "   And Room       = '".$singoRoom."'       ".
                          "   And Open       = '".$singoOpen."'       ".
                          "   And Film       = '".$singoFilm."'       ".
                          "   And UnitPrice  = '".$UnitPrice."'       " ;
                $QrySingo2 = mysql_query($sQuery,$connect) ;
                if  ($NumPersons_data = mysql_fetch_array($QrySingo2))
                {
                    $SumOfPsAgoDay += ($NumPersons_data["SumNumPersons"]+$AgoModifyScore) ; // ���� �հ� �հ�

                    // �����հ� ���
                    //if ($ToExel) { $sValue = $NumPersons_data["SumNumPersons"]+$AgoModifyScore ; }
                    /*else*/       { $sValue = "$NBSP".number_format($NumPersons_data["SumNumPersons"]+$AgoModifyScore)."$NBSP" ; }
                    ?>
                    <td class=textarea bgcolor=<?=$Color3?> align=right>
                    <?=$sValue?>
                    </td>
                    <?
                }
                else
                {
                    // �����հ� ���
                    ?>
                    <td class=textarea bgcolor=<?=$Color3?> align=center><?=$NBSP?></td>
                    <?
                }

                $SumOfAmToday += $SumOfToday * $UnitPrice  ; // ���ϱݾ� �հ�
                if  ($WorkDate<"20070701")
                {
                     $SumOfAmToday3 += $SumOfToday  * $UnitPrice  ; // ���ϱݾ� �հ�
                }
                else
                {
                    $SumOfAmToday3 += $SumOfToday  * round($UnitPrice / $GikumRate)  ; // ���ϱݾ� �հ�
                }

                $FilmOpen = substr($FilmTile,0,6) ;
                $FilmCode = substr($FilmTile,6,2) ;

                if  ($FilmCode=='00')
                {
                    $CondOpenFilm = " And Open = '".$FilmOpen."' " ;
                }
                else
                {
                    $CondOpenFilm = " And Open = '".$FilmOpen."' ".
                                    " And Film = '".$FilmCode."' " ;
                }

                ////////////////////////////////////////////////////////////////////////////

                $CondFilmType = " And FilmType = '".$singoFilmType."' " ;   //echo $CondFilmType  ;
                $CondFilmTypeNo = " And FilmType = '".$nFilmTypeNo."' " ;   //echo $CondFilmTypeNo  ;


                // ���� ĳ�� ���� �˻�..
                $sQuery = "Select Accu, TotAccu, AcMoney, TotAcMoney  \n".
                          "  From ".$sAccName."                       \n". // $sAccName
                          " Where WorkDate   = '".$WorkDate."'        \n".
                          "   And Theather   = '".$singoTheather."'   \n".
                          "   And Open       = '".$singoOpen."'       \n".
                          "   And Film       = '".$singoFilm."'       \n".
                          $CondFilmTypeNo."                           \n".  // 0 �̸� �ʸ� ���� ���� ����� ����
                          "   And UnitPrice  = '".$UnitPrice."'       \n" ;  //if  ($singoTheather=="1137")	  eq($sQuery);
                $QryAccumulate = mysql_query($sQuery,$connect) ;
                if  ($AryAccumulate = mysql_fetch_array($QryAccumulate))
                {
                    $AccuScore = $AryAccumulate["Accu"] ;
                    $AccuMoney = $AryAccumulate["AcMoney"]  ;
                }
                else // ������
                {
                    // ���ϴ��� - ���ݺ�
                    if ($nFilmTypeNo == "0")
                    {
                        $sQuery = "Select Sum(NumPersons) As SumNumPersons,  \n".
                                  "       Sum(TotAmount)  As SumTotAmount    \n".
                                  "  From ".$sSingoName."                    \n".
                                  " Where SingoDate  <= '".$WorkDate."'      \n".
                                  "   And Theather   = '".$singoTheather."'  \n".
                                  $CondOpenFilm."                            \n". // �ʸ� ���о��� �ش��ʸ� ��ü
                                  "   And UnitPrice  = '".$UnitPrice."'      \n" ;
                    }
                    else
                    {
                        $sQuery = "Select Sum(NumPersons) As SumNumPersons,  \n".
                                  "       Sum(TotAmount)  As SumTotAmount    \n".
                                  "  From ".$sSingoName."                    \n".
                                  " Where SingoDate  <= '".$WorkDate."'      \n".
                                  "   And Theather   = '".$singoTheather."'  \n".
                                  $CondOpenFilm."                            \n".   //
                                  $CondFilmType."                            \n".   // �ش��ʸ�Ÿ�Ը�..
                                  "   And UnitPrice  = '".$UnitPrice."'      \n" ;
                    }
                    $QrySumSingo = mysql_query($sQuery,$connect) ;     //if  ($singoTheather=="1137") eq($sQuery);
                    if  ($ArySumSingo = mysql_fetch_array($QrySumSingo))
                    {
                        $AccuScore = $ArySumSingo["SumNumPersons"] ;
                        $AccuMoney = $ArySumSingo["SumTotAmount"] ;

                        $sQuery = "Insert Into ".$sAccName."           \n".  // $sAccName
                                  "Values                              \n".
                                  "(                                   \n".
                                  "    '".$WorkDate."',                \n".
                                  "    '".$singoSilmooja."',           \n".
                                  "    '".$singoTheather."',           \n".
                                  "    '".$singoOpen."',               \n".
                                  "    '".$singoFilm."',               \n".
                                  "    '".$nFilmTypeNo."',             \n".  // 0 �̸� �ʸ� ���� ���� ����� ����
                                  "    '".$UnitPrice."',               \n".
                                  "    '".$AccuScore."',               \n".
                                  "    '0',                            \n".
                                  "    '".$AccuMoney."',               \n".
                                  "    '0',                            \n".
                                  "    '".$showroomLocation."',        \n".
                                  "    '".$SumOfToday."',              \n".
                                  "    '".$SumOfToday*$UnitPrice."'    \n".
                                  ")                                   \n" ;  //if  ($singoTheather=="1137") eq($sQuery);
                        mysql_query($sQuery,$connect) ;

                    }
                }

                //if ($ToExel) { $sValue = $AccuScore ; }
                /*else*/       { $sValue = "$NBSP".number_format($AccuScore)."$NBSP" ; }
                ?>
                <td class=textarea bgcolor=<?=$Color3?> align=right>
                <?=$sValue?>
                </td>




                <?
                $SumOfPsAcc += $AccuScore ;    // ���轺�ھ� �հ�

                if  ($TimJang==false)
                {
                    //if ($ToExel) { $sValue = ($SumOfToday)*$UnitPrice ; }
                    /*else*/       { $sValue = "$NBSP".number_format(($SumOfToday)*$UnitPrice)."$NBSP" ; }
                    ?>
                    <td class=textarea bgcolor=<?=$Color3?> align=right>
                    <?=$sValue?>
                    </td>
                    <?
                }

                if  ($WorkGubun != 29)
                {
                    if  ($TimJang==false)
                    {
                        // ����ݾ�
                        //if ($ToExel) { $sValue = $AccuMoney ; }
                        /*else*/       { $sValue = "$NBSP".number_format($AccuMoney)."$NBSP" ; }
                        ?>
                        <td class=textarea bgcolor=<?=$Color3?> align=right>
                        <?=$sValue?>
                        </td>
                        <?

                        $SumOfAmAcc += $AccuMoney ; // ����ݾ� �հ�
                    }
                    ?>
					<!-- ������� ���� �ݾ�  -->
                    <td class=textarea bgcolor=<?=$Color3?> align=right>
                        <?
                        if  ($WorkDate<"20070701")
                        {
                            //if ($ToExel) { $sValue = ($SumOfToday)*$UnitPrice ; }
                            /*else*/       { $sValue = "$NBSP".number_format(($SumOfToday)*$UnitPrice)."$NBSP" ; }
                        }
                        else
                        {
                            //if ($ToExel) { $sValue = ($SumOfToday)*round($UnitPrice/$GikumRate) ; }
                            /*else*/       { $sValue = "$NBSP".number_format(($SumOfToday)*round($UnitPrice / $GikumRate))."$NBSP" ; }
                        }
                        ?>
                        <?=$sValue?>
                    </td>
                    <?


                    $SumNumPersons7Ago = 0 ;
                    /*
                    $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                              "  From ".$sSingoName."                    ".
                              " Where SingoDate  < '20070701'            ".
                              "   And Theather   = '".$singoTheather."'  ".
                              $CondOpenFilm."                            ".
                              "   And UnitPrice  = '".$UnitPrice."'      " ;
                    $Qry7Ago = mysql_query($sQuery,$connect) ;
                    if  ($Arr7Ago = mysql_fetch_array($Qry7Ago))
                    {
                        $SumNumPersons7Ago = $Arr7Ago["SumNumPersons"] * $UnitPrice ;
                    }

                    $SumNumPersons7Aft = 0 ;
                    $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                              "  From ".$sSingoName."                    ".
                              " Where SingoDate  <= '".$WorkDate."'        ".
                              "   And SingoDate  >= '20070701'           ".
                              "   And Theather   = '".$singoTheather."'  ".
                              $CondOpenFilm."                            ".
                              "   And UnitPrice  = '".$UnitPrice."'      " ;
                    $Qry7Aft = mysql_query($sQuery,$connect) ;
                    if  ($Arr7Aft = mysql_fetch_array($Qry7Aft))
                    {
                        $SumNumPersons7Aft = $Arr7Aft["SumNumPersons"] * round($UnitPrice / $GikumRate) ;
                    }
                    */
                    $SumNumPersons7Aft = $AccuScore * round($UnitPrice / $GikumRate) ;


                    $SumOfAmAcc3 += ($SumNumPersons7Ago+$SumNumPersons7Aft) ;

                    //if ($ToExel) { $sValue = $SumNumPersons7Ago+$SumNumPersons7Aft ; }
                    /*else*/       { $sValue = "$NBSP".number_format($SumNumPersons7Ago+$SumNumPersons7Aft)."$NBSP" ; }
                    ?>
					<!-- ������� ���� �ݾ�  -->
                    <td class=textarea bgcolor=<?=$Color3?> align=right>
                    <?=$sValue?>
                    </td>


                    <?
                    if  ($TimJang==false)
                    {
                        // ��� �÷�
                        if  ($DataRow==1) // ù��° ����Ÿ�� �������� �ѹ� �ÿ��׸���.
                        {
                            $sQuery = "Select Count(*) As CntMagam              ".
                                      "  From wrk_magam                         ".
                                      " Where WorkDate = '".$WorkDate."'        ".
                                      "   And Theather = '".$singoTheather."'   ".
                                      "   And Room     = '".$singoRoom."'       ".
                                      "   And Open     = '".$singoOpen."'       ".
                                      "   And Film     = '".$singoFilm."'       " ;
                            $QryMagam = mysql_query($sQuery,$connect) ;
                            if  ($ArrMagam = mysql_fetch_array($QryMagam))
                            {
                                if  ($ArrMagam["CntMagam"] > 0)
                                {
                                    $IsMagam = true ;
                                }
                                else
                                {
                                    $IsMagam = false ;
                                }
                                $sQuery = "Select WorkTime From wrk_magam           ".
                                          " Where WorkDate = '".$WorkDate."'        ".
                                          "   And Theather = '".$singoTheather."'   ".
                                          "   And Room     = '".$singoRoom."'       ".
                                          "   And Open     = '".$singoOpen."'       ".
                                          "   And Film     = '".$singoFilm."'       " ;
                                $QryMagam = mysql_query($sQuery,$connect) ;
                                if  ($ArrMagam = mysql_fetch_array($QryMagam))
                                {
                                    $MagamWorkTime = substr($ArrMagam["WorkTime"],0,2).":".substr($ArrMagam["WorkTime"],2,2) ;
                                }
                            }

                            $sQuery = "Select Bigo From wrk_showroombigo          ".
                                      " Where Theather   = '".$singoTheather."'   ".
                                      "   And Room       = '".$singoRoom."'       ".
                                      "   And Open       = '".$singoOpen."'       ".
                                      "   And Film       = '".$singoFilm."'       " ;
                            $QrySingo2 = mysql_query($sQuery,$connect) ;
                            if  ($ShowroomBigo_data = mysql_fetch_array($QrySingo2))
                            {
                                ?>
                                <td class=textarea bgcolor=<?=$Color3?> rowspan=<?=($cntUnitPrice+1)?> align=left>
                                <div id=bigo<?=$singoTheather.$singoRoom.$singoOpen.$singoFilm?>>
                                <?=str_replace("\r","",str_replace("\n","<br>",$ShowroomBigo_data["Bigo"]))?>
                                </div>
                                <?
                                if  ($IsMagam==true)
                                {
                                    ?><!-- <FONT COLOR="#FF0000"><B>����</B><br><?=$MagamWorkTime?></FONT> --><?
                                }
                                ?>
                                </td>
                                <?
                            }
                            else
                            {
                                ?>
                                <td class=textarea bgcolor=<?=$Color3?> rowspan=<?=($cntUnitPrice+1)?> align=center>
                                <div id=bigo<?=$singoTheather.$singoRoom.$singoOpen.$singoFilm?>><?=$NBSP?></div>
                                <?
                                if  ($IsMagam==true)
                                {
                                    ?><!-- <FONT COLOR="#FF0000"><B>����</B><br><?=$MagamWorkTime?></FONT> --><?
                                }
                                ?>
                                </td>
                                <?
                            }
                        }
                    }
                    $DataRow++;
                }
                else
                {
                    ?>
                    <td class=textarea bgcolor=<?=$Color3?> align=right>
                        <?
                        if  ($WorkDate<"20070701")
                        {
                            //if ($ToExel) { $sValue = ($SumOfToday)*$UnitPrice ; }
                            /*else*/       { $sValue = "$NBSP".number_format(($SumOfToday)*$UnitPrice)."$NBSP" ; }
                        }
                        else
                        {

                            //if ($ToExel) { $sValue = ($SumOfToday)*round($UnitPrice / $GikumRate) ; }
                            /*else*/       { $sValue = "$NBSP".number_format(($SumOfToday)*round($UnitPrice / $GikumRate))."$NBSP" ; }
                        }
                        ?>
                        <?=$sValue?>
                    </td>
                    <?
                    if  ($TimJang==false)
                    {
                        // ����ݾ�
                        //if ($ToExel) { $sValue = $AccuMoney ; }
                        /*else*/       { $sValue = "$NBSP".number_format($AccuMoney)."$NBSP" ; }
                        ?>
                        <td class=textarea bgcolor=<?=$Color3?> align=right>
                        <?=$sValue?>
                        </td>
                        <?

                        $SumOfAmAcc += $AccuMoney ; // ����ݾ� �հ�
                    }


                }
                ?>

           </tr>

           <?
           }

           mysql_free_result($QrySingo3) ;
           ?>


           <!--             -->
           <!--  �� �� ��� -->
           <!--             -->
           <tr>
                <!-- �հ� -->
                <?
                if  ($WorkGubun != 29)
                {
                    ?>
                    <td class=textarea bgcolor=<?=$Color4?> class=tblsum align=center>�հ�</td>
                    <?
                }

                if  (($WorkGubun == 27) || ($WorkGubun == 29))
                {
                    ?>
                    <td class=textarea bgcolor=<?=$Color4?> class=tblsum align=center>�հ�</td>
                    <?
                }


                if  ($isFinished == false)
                {
                    // ȸ���� ���ھ� �հ�
                    for ($i = 1 ; $i <= $LastDegree ; $i++)
                    {
                       //if ($ToExel) { $sValue = $arrySumOfDegree[$i] ; }
                       /*else*/       { $sValue = "$NBSP".number_format($arrySumOfDegree[$i])."$NBSP" ; }
                       ?>
                       <td class=textarea bgcolor=<?=$Color4?> align=right>
                       <?=$sValue?>
                       </td>
                       <?
                    }
                }
                ?>

                <!-- ���� �հ� �հ� -->
                <?
                //if ($ToExel) { $sValue = $SumOfPsToday ; }
                /*else*/       { $sValue = "$NBSP".number_format($SumOfPsToday)."$NBSP" ; }
                ?>
                <td class=textarea bgcolor=<?=$Color4?> align=right>
                <?=$sValue?>
                </td>

                <!-- ���� �հ� �հ� -->
                <?
                //if ($ToExel) { $sValue = $SumOfPsAgoDay ; }
                /*else*/       { $sValue = "$NBSP".number_format($SumOfPsAgoDay)."$NBSP" ; }
                ?>
                <td class=textarea bgcolor=<?=$Color4?> align=right>
                <?=$sValue?>
                </td>

                <!-- ���� �հ� -->
                <?
                //if ($ToExel) { $sValue = $SumOfPsAcc ; }
                /*else*/       { $sValue = "$NBSP".number_format($SumOfPsAcc)."$NBSP" ; }
                ?>
                <td class=textarea bgcolor=<?=$Color4?> align=right>
                <?=$sValue?>
                </td>


                <?
                if  ($TimJang==false)
                {
                    //if ($ToExel) { $sValue = $SumOfAmToday ; }
                    /*else*/       { $sValue = "$NBSP".number_format($SumOfAmToday)."$NBSP" ; }
                    ?>
                    <!-- ���ϱݾ� �հ� -->
                    <td class=textarea bgcolor=<?=$Color4?> align=right>
                    <?=$sValue?>
                    </td>
                    <?
                }

                if  ($WorkGubun != 29)
                {
                    if  ($TimJang==false)
                    {
                        ?>
                        <!-- ����ݾ� �հ� -->
                        <?
                        //if ($ToExel) { $sValue = $SumOfAmAcc ; }
                        /*else*/       { $sValue = "$NBSP".number_format($SumOfAmAcc)."$NBSP" ; }
                        ?>
                        <td class=textarea bgcolor=<?=$Color4?> align=right>
                        <?=$sValue?>
                        </td>

                        <!-- ���ϱݾ� �հ� -->
                        <?
                        //if ($ToExel) { $sValue = $SumOfAmToday3 ; }
                        /*else*/       { $sValue = "$NBSP".number_format($SumOfAmToday3)."$NBSP" ; }
                        ?>
                        <td class=textarea bgcolor=<?=$Color4?> align=right>
                        <?=$sValue?>
                        </td>

                        <!-- ����ݾ� �հ� -->
                        <?
                        //if ($ToExel) { $sValue = $SumOfAmAcc3 ; }
                        /*else*/       { $sValue = "$NBSP".number_format($SumOfAmAcc3)."$NBSP" ; }
                        ?>
                        <td class=textarea bgcolor=<?=$Color4?> align=right>
                        <?=$sValue?>
                        </td>
                        <?
                    }
                }
                else
                {
                    if  ($TimJang==false)
                    {
                        ?>
                        <!-- ���ϱݾ� �հ� -->
                        <?
                        //if ($ToExel) { $sValue = $SumOfAmToday3 ; }
                        /*else*/       { $sValue = "$NBSP".number_format($SumOfAmToday3)."$NBSP" ; }
                        ?>
                        <td class=textarea bgcolor=<?=$Color4?> align=right>
                        <?=$sValue?>
                        </td>

                        <!-- ����ݾ� �հ� -->
                        <?
                        //if ($ToExel) { $sValue = $SumOfAmAcc ; }
                        /*else*/       { $sValue = "$NBSP".number_format($SumOfAmAcc)."$NBSP" ; }
                        ?>
                        <td class=textarea bgcolor=<?=$Color4?> align=right>
                        <?=$sValue?>
                        </td>

                        <?
                    }
                }
                ?>
           </tr>
           </table>
           <?
           } ///////////////////
       }
   }
   ?>

