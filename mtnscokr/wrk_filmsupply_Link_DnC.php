   <br>
   <?

   $sQuery = "Delete From ".$sSingoName."  ".
             " Where room = '00'           " ; //echo $sQuery ;
   mysql_query($sQuery,$connect) ;

   $sQuery = "Delete From ".$sSingoName."  ".
             " Where ShowDgree = '00'      " ; //echo $sQuery ;
   mysql_query($sQuery,$connect) ;

   ?>
   <br>
   <br>
   <?
   $TotSeat = 0 ; // (���հ�) �� �¼���

   for ($i = 1 ; $i <= 11 ; $i++)
   {
       $arrySumOfDegree[$i] = 0 ;  // ȸ���� ���ھ� �հ�
   }
   ?>

   <!---------------------------------------------------------------------------------------------------------------------->
   <!--

                                                   ���������� ������ ����
                                                                                                                         -->
   <!---------------------------------------------------------------------------------------------------------------------->


   <table cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">


      <!----------------------------------------------------------------------------------------------------------->
      <!--

                                                 Ÿ��Ʋ ���
                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <tr>
            <?
            if  ($ToExel)
            {
            ?>
                <td class=textarea bgcolor=#ffffff align=center><b>����</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>��ũ����</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>�հ�/������</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>���¼���</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>1ȸ</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>2ȸ</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>3ȸ</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>4ȸ</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>5ȸ</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>6ȸ</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>7ȸ</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>8ȸ</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>9ȸ</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>�ɾ�</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>�հ�</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>���ϱݾ�</b></td>
            <?
            }
            else
            {
            ?>
                <td class=textarea bgcolor=#ffebcd align=center><b>����</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>��ũ����</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>�հ�/������</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>���¼���</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>1ȸ</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>2ȸ</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>3ȸ</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>4ȸ</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>5ȸ</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>6ȸ</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>7ȸ</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>8ȸ</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>9ȸ</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>�ɾ�</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>�հ�</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>���ϱݾ�</b></td>
            <?
            }
            ?>
      </tr>


      <?
      $AddedCont = "" ; // �߰����� �˻�����

      $singoOpen = substr($FilmTile,0,6) ;
      $singoFilm = substr($FilmTile,6,2) ;

      $sSingoName = get_singotable($singoOpen,$singoFilm,$connect) ;  // �Ű� ���̺� �̸�..

      // Ư�� ��ȭ�� ���������� ������ �� ���
      if  (($FilmTile != "") && ($FilmTile != "00000000"))
      {
          if   ($singoFilm=='00')
          {
               $AddedCont  = "   And singo.open = '".$singoOpen."' " ;
               $FinishCont = "   And singo.open = finish.open      " ;
          }
          else
          {
               $AddedCont  = "   And singo.open = '".$singoOpen."' " .
                             "   And singo.film = '".$singoFilm."' " ;
               $FinishCont = "   And singo.open = finish.open      " .
                             "   And singo.film = finish.film      " ;
          }

          if   ($singoFilm=='00')
          {
               $modAddedCont  = "   And open = '".$singoOpen."' " ;
          }
          else
          {
               $modAddedCont  = "   And open = '".$singoOpen."' " .
                                "   And film = '".$singoFilm."' " ;
          }
      }
      ?>


      <!----------------------------------------------------------------------------------------------------------->
      <!--
                                                 ���� �հ� ���
                                                 (100)
                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?
      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // ȸ���� ���ھ�
      }

      if  ($ToExel)
      {
          $ColorC = "#ffffff" ;
      }
      else
      {
          if  ($clrCToggle==true)
          {
              $ColorC = "#dcdcdc" ;

              $clrCToggle=false ;
          }
          else
          {
              $ColorC = "#cccccc" ;

              $clrCToggle=true ;
          }
      }
      ?>
      <tr>
           <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>����</b></td>

           <?
           // ��ũ���� (����)
           $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                     "       As cntShowroom                               ".
                     "  From ".$sSingoName." As singo,                    ".
                     "       bas_showroom As showroom                     ".
                     " Where singo.singodate  = '".$WorkDate."'           ".
                     "   And singo.location = 100                         ".
                     "   And singo.theather = showroom.theather           ".
                     "   And singo.room     = showroom.room               ".
                     $AddedCont."                                         " ;
           if  ($WorkGubun == 28)
           {
               $sQuery .= " And singo.Silmooja = '777777' " ;
           }
           if  ($WorkGubun == 33)
           {
               $sQuery .= " And singo.Silmooja = '555595' " ;
           }
           if  ($WorkGubun == 34) // ���ʽ�
           {
               $sQuery .= " And showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // �Ե����׸�
           {
               $sQuery .= " And showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // �ް��ڽ�
           {
               $sQuery .= " And showroom.MultiPlex  = '3' " ;
           }
           if  ($nFilmTypeNo != "0")
           {
               $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
           }
           $QrySingo2 = mysql_query($sQuery,$connect) ;
           if  ($AryCntShowroom = mysql_fetch_array($QrySingo2))
           {
               $cntRealScreen = $AryCntShowroom["cntShowroom"] ;
           }

           $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                     "       As cntFinishShowroom                         ".
                     "  From ".$sSingoName." As singo,                    ".
                     "       bas_showroom As showroom,                    ".
                     "       bas_silmoojatheatherfinish As finish         ".
                     " Where singo.singodate  = '".$WorkDate."'           ".
                     "   And singo.location = 100                         ".
                     "   And singo.theather = showroom.theather           ".
                     "   And singo.room     = showroom.room               ".
                     "   And singo.theather = finish.theather             ".
                     "   And singo.room     = finish.room                 ".
                     $AddedCont."                                         ".
                     $FinishCont."                                        ".
                     "   And singo.Silmooja = finish.silmooja             " ;
           if  ($WorkGubun == 28)
           {
               $sQuery .= " And singo.Silmooja = '777777' " ;
           }
           if  ($WorkGubun == 33)
           {
               $sQuery .= " And singo.Silmooja = '555595' " ;
           }
           if  ($WorkGubun == 34) // ���ʽ�
           {
               $sQuery .= " And showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // �Ե����׸�
           {
               $sQuery .= " And showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // �ް��ڽ�
           {
               $sQuery .= " And showroom.MultiPlex  = '3' " ;
           }
           if  ($nFilmTypeNo != "0")
           {
               $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
           }
           $QrySingo2 = mysql_query($sQuery,$connect) ;
           if  ($AryCntShowroom = mysql_fetch_array($QrySingo2))
           {
               $cntRealScreen = $cntRealScreen - $AryCntShowroom["cntFinishShowroom"] ;
           }
           ?>

           <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center   rowspan=2>
           &nbsp;<?=number_format($cntRealScreen)?>&nbsp;&nbsp;
           </td>


           <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center>
           <b>�հ�</b>
           </td>

           <?
           // �¼��� (������ü)
           $SumSeatSeat = 0 ;
           $sQuery = "Select distinct singo.theather, singo.room,         ".
                     "                singo.showdgree, showroom.seat      ".
                     "  From ".$sSingoName." As singo,                    ".
                     "       bas_showroom As showroom                     ".
                     " Where singo.singodate  = '".$WorkDate."'           ".
                     "   And singo.location = 100                         ".
                     "   And singo.theather = showroom.theather           ".
                     "   And singo.room     = showroom.room               ".
                     $AddedCont."                                         " ;
           if  ($WorkGubun == 28)
           {
               $sQuery .= " And singo.Silmooja = '777777' " ;
           }
           if  ($WorkGubun == 33)
           {
               $sQuery .= " And singo.Silmooja = '555595' " ;
           }
           if  ($WorkGubun == 34) // ���ʽ�
           {
               $sQuery .= " And showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // �Ե����׸�
           {
               $sQuery .= " And showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // �ް��ڽ�
           {
               $sQuery .= " And showroom.MultiPlex  = '3' " ;
           }
           if  ($nFilmTypeNo != "0")
           {
               $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
           }
           $QrySingo2 = mysql_query($sQuery,$connect) ;
           while ($ArySumSeat = mysql_fetch_array($QrySingo2))
           {
                 $SumSeatSeat += $ArySumSeat["seat"] ;
           }

           $TotSeat = $TotSeat + $SumSeatSeat ; // ���¼���...
           ?>
           <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatSeat)?></td> <?


           $ModifyScore  = 0 ;
           $ModifyAmount = 0 ;


           $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore,  ".
                     "       Sum(ModifyAmount) As SumOfModifyAmount ".
                     "  From bas_modifyscore                        ".
                     " Where location   = 100                       ".
                     "   And Open       = '".$singoOpen."'          ".
                     "   And Film       = '".$singoFilm."'          ".
                     "   And ModifyDate = '".$WorkDate."'           " ;
           $qry_modifyscore  = mysql_query($sQuery,$connect) ;
           if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
           {
               $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
               $ModifyAmount = $modifyscore_data["SumOfModifyAmount"] ;
           }


           $nTotNumPersons = 0 ;
           $nTotTotAmount  = 0 ;

           if  ($WorkGubun == 28)
           {
               $AddedCont .= " And singo.Silmooja = '777777' " ;
           }
           if  ($WorkGubun == 33)
           {
               $AddedCont .= " And singo.Silmooja = '555595' " ;
           }
           if  ($WorkGubun == 34) // ���ʽ�
           {
               $AddedCont .= " And showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // �Ե����׸�
           {
               $AddedCont .= " And showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // �ް��ڽ�
           {
               $AddedCont .= " And showroom.MultiPlex  = '3' " ;
           }
           if  ($nFilmTypeNo != "0")
           {
               $AddedCont .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
           }
           $sQuery = "Select singo.showdgree As ShowDgree,                ".
                     "       Sum(singo.NumPersons) As SumNumPersons,      ".
                     "       Sum(TotAmount) As SumTotAmount               ".
                     "  From ".$sSingoName." As singo,                    ".
                     "       bas_showroom As showroom                     ".
                     " Where singo.singodate  = '".$WorkDate."'           ".
                     "   And singo.location = 100                         ".
                     "   And singo.theather = showroom.theather           ".
                     "   And singo.room     = showroom.room               ".
                     $AddedCont."                                         ".
                     " Group By singo.showdgree                           ".
                     " Order By singo.showdgree                           " ;

           $QrySingo2 = mysql_query($sQuery,$connect) ;

           $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;

           for  ($i=1; $i<=11; $i++)
           {
                $sDgree = sprintf("%02d", $i) ;  // 01,02,03,........

                if  ($i==11)
                {
                    $sDgree = "99" ; // �ɾ�
                }

                if  ($ArySumNumPersons)
                {
                    if  ($sDgree == $ArySumNumPersons["ShowDgree"])
                    {
                        if  ($sDgree == "01")
                        {
                            $dispData = number_format($ArySumNumPersons["SumNumPersons"]+$ModifyScore) ;

                            $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                            $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"]  + $ModifyAmount ;

                            $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                            $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                        }
                        else
                        {
                            $dispData = number_format($ArySumNumPersons["SumNumPersons"]) ;

                            $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] ;
                            $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"] ;

                            $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                            $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                        }

                        $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;
                    }
                    else
                    {
                        $dispData = "0" ;
                    }
                }
                else
                {
                    $dispData = "0" ;
                }

                if  ($i!=10)  // �������ͳ׼ų�
                {
                ?>
                <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispData?></td>
                <?
                }

           }
           ?>
           <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotNumPersons)?></td>
           <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotTotAmount)?></td>
      </tr>
      <!----------------------------------------------------------------------------------------------------------->
      <tr>
           <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center>
           <b>������</b>
           </td>

           <?
           // �� ������ ..
           if  ($SumSeatSeat==0)
           {
               $SumSeatRate = 0 ;
           }
           else
           {
               $SumSeatRate = $nTotNumPersons / $SumSeatSeat * 100.0 ;
           }
           ?>
           <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>

           <?
           for ($i = 1 ; $i <= 11 ; $i++)
           {
               if  ($i!=10)  // �������ͳ׼ų�
               {
                   if  ($SumSeatSeat==0)
                   {
                       $ByDegreeRate = 0 ;
                   }
                   else
                   {
                       $ByDegreeRate = $arryDegree[ $i ] / $SumSeatSeat * 100.0 ;
                   }


                   ?>
                   <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($ByDegreeRate,2)?>%</td>
                   <?
               }
           }
           ?>

           <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
           <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
      </tr>


      <!----------------------------------------------------------------------------------------------------------->
      <!--
                                                 ��� �հ� ���
                                                 (04)
                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?
      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // ȸ���� ���ھ�
      }

      $sQuery = "Select *                            ".
                "  From bas_filmsupplyzoneloc        ".
                " Where Zone = '04'                  " ;
      $qryzone = mysql_query($sQuery,$connect) ;
      if  ($zone_data = mysql_fetch_array($qryzone))
      {
          if  ($ToExel)
          {
              $ColorC = "#ffffff" ;
          }
          else
          {
              if  ($clrCToggle==true)
              {
                  $ColorC = "#dcdcdc" ;

                  $clrCToggle=false ;
              }
              else
              {
                  $ColorC = "#cccccc" ;

                  $clrCToggle=true ;
              }
          }

          $sQuery = "Select * From bas_zone  ".
                    " Where Code = '04'      " ;
          $qryzone = mysql_query($sQuery,$connect) ;
          if  ($zone_data = mysql_fetch_array($qryzone))
          {
              $zoneName = $zone_data["Name"] ;
          }

          $AddedLoc = " And " ;
          $AddedLocMD = " And " ;

          $sQuery = "Select Location                     ".
                    "  From bas_filmsupplyzoneloc        ".
                    " Where Zone = '04'                  " ;
          $qryzoneloc = mysql_query($sQuery,$connect) ;
          while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
          {
               if  ($AddedLoc == " And ")
                   $AddedLoc .= "( singo.Location = '".$zoneloc_data["Location"]."' "  ;
               else
                   $AddedLoc .= " or singo.Location = '".$zoneloc_data["Location"]."' "  ;

               if  ($AddedLocMD == " And ")
                   $AddedLocMD .= "( Location = '".$zoneloc_data["Location"]."' "  ;
               else
                   $AddedLocMD .= " or Location = '".$zoneloc_data["Location"]."' "  ;
          }
          $AddedLoc .= ")" ;
          $AddedLocMD .= ")" ;
          ?>
          <tr>
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>���</b></td>

               <?
               // ��ũ���� (���)
               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntShowroom                               ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $QrySingo2 = mysql_query($sQuery,$connect) ;
               if  ($AryCntShowroom = mysql_fetch_array($QrySingo2))
               {
                   $cntRealScreen = $AryCntShowroom["cntShowroom"] ;
               }

               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntFinishShowroom                         ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom,                    ".
                         "       bas_silmoojatheatherfinish As finish         ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         "   And singo.theather = finish.theather             ".
                         "   And singo.room     = finish.room                 ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          ".
                         $FinishCont."                                        ".
                         "   And singo.Silmooja = finish.silmooja             " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $QrySingo2 = mysql_query($sQuery,$connect) ;
               if  ($AryCntShowroom = mysql_fetch_array($QrySingo2))
               {
                   $cntRealScreen = $cntRealScreen - $AryCntShowroom["cntFinishShowroom"] ;
               }
               ?>

               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><?=number_format($cntRealScreen)?>&nbsp;&nbsp;</td>

               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><b>�հ�</b></td>

               <?
               // �¼��� (�����ü)
               $SumSeatSeat = 0 ;

               $sQuery = "Select distinct singo.theather, singo.room,         ".
                         "                singo.showdgree, showroom.seat      ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $QrySingo2 = mysql_query($sQuery,$connect) ;
               while ($ArySumSeat = mysql_fetch_array($QrySingo2))
               {
                     $SumSeatSeat += $ArySumSeat["seat"] ;
               }

               $TotSeat = $TotSeat + $SumSeatSeat ; // ���¼���...
               ?>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatSeat)?></td> <?

               $ModifyScore  = 0 ;
               $ModifyAmount = 0 ;

               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore,  ".
                         "       Sum(ModifyAmount) As SumOfModifyAmount ".
                         "  From bas_modifyscore                        ".
                         " Where Open       = '".$singoOpen."'          ".
                         "   And Film       = '".$singoFilm."'          ".
                         "   And ModifyDate = '".$WorkDate."'           ".
                         $AddedLocMD."                                  " ;
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
                   $ModifyAmount = $modifyscore_data["SumOfModifyAmount"] ;
               }


               $nTotNumPersons = 0 ;
               $nTotTotAmount  = 0 ;

               if  ($WorkGubun == 28)
               {
                   $AddedCont .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $AddedCont .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $AddedCont .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $AddedCont .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $AddedCont .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $AddedCont .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $sQuery = "Select singo.showdgree As ShowDgree,                ".
                         "       Sum(singo.NumPersons) As SumNumPersons,      ".
                         "       Sum(TotAmount) As SumTotAmount               ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          ".
                         " Group By singo.showdgree                           ".
                         " Order By singo.showdgree                           " ;

               $QrySingo2 = mysql_query($sQuery,$connect) ;

               $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;

               for  ($i=1; $i<=11; $i++)
               {
                    $sDgree = sprintf("%02d", $i) ;  // 01,02,03,........

                    if  ($i==11)
                    {
                        $sDgree = "99" ; // �ɾ�
                    }

                    if  ($ArySumNumPersons)
                    {
                        if  ($sDgree == $ArySumNumPersons["ShowDgree"])
                        {
                            if  ($sDgree == "01")
                            {
                                $dispData = number_format($ArySumNumPersons["SumNumPersons"]+$ModifyScore) ;

                                $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                                $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"]  + $ModifyAmount ;

                                $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                                $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                            }
                            else
                            {
                                $dispData = number_format($ArySumNumPersons["SumNumPersons"]) ;

                                $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] ;
                                $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"] ;

                                $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                                $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                            }

                            $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;
                        }
                        else
                        {
                            $dispData = "0" ;
                        }
                    }
                    else
                    {
                        $dispData = "0" ;
                    }

                    if  ($i!=10)  // �������ͳ׼ų�
                    {
                    ?>
                    <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispData?></td>
                    <?
                    }

               }
               ?>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotNumPersons)?></td>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotTotAmount)?></td>
          </tr>
          <!----------------------------------------------------------------------------------------------------------->
          <tr>
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center>
               <b>������</b>
               </td>

               <?
               // �� ������ ..
               if  ($SumSeatSeat==0)
               {
                   $SumSeatRate = 0 ;
               }
               else
               {
                   $SumSeatRate = $nTotNumPersons / $SumSeatSeat * 100.0 ;
               }

               ?>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>

               <?
               for ($i = 1 ; $i <= 11 ; $i++)
               {
                   if  ($i!=10)  // �������ͳ׼ų�
                   {
                       if  ($SumSeatSeat==0)
                       {
                           $ByDegreeRate = 0 ;
                       }
                       else
                       {
                           $ByDegreeRate = $arryDegree[ $i ] / $SumSeatSeat * 100.0 ;
                       }

                       ?>
                       <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($ByDegreeRate,2)?>%</td>
                       <?
                   }
               }
               ?>

               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
          </tr>
          <?
      }
      ?>


      <?


      ?>
      <!----------------------------------------------------------------------------------------------------------->
      <!--
                                                 �λ� �հ� ���
                                                 (200)
                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?
      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // ȸ���� ���ھ�
      }

      if  ($ToExel)
      {
          $ColorC = "#ffffff" ;
      }
      else
      {
          if  ($clrCToggle==true)
          {
              $ColorC = "#dcdcdc" ;

              $clrCToggle=false ;
          }
          else
          {
              $ColorC = "#cccccc" ;

              $clrCToggle=true ;
          }
      }
      ?>
      <tr>
           <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>�λ�</b></td>
           <?
           // ��ũ���� (���� �λ�)
           $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                     "       As cntShowroom                               ".
                     "  From ".$sSingoName." As singo,                    ".
                     "       bas_showroom As showroom                     ".
                     " Where singo.singodate  = '".$WorkDate."'           ".
                     "   And ( singo.location = 200                       ".
                     "    Or   singo.location = 203                       ".
                     "    Or   singo.location = 600                       ".
                     "    Or   singo.location = 207                       ".
                     "    Or   singo.location = 205                       ".
                     "    Or   singo.location = 208                       ".
                     "    Or   singo.location = 202                       ".
                     "    Or   singo.location = 211                       ".
                     "    Or   singo.location = 212                       ".
                     "    Or   singo.location = 213                       ".
                     "    Or   singo.location = 201 )                     ".
                     "   And singo.theather = showroom.theather           ".
                     "   And singo.room     = showroom.room               ".
                     $AddedCont."                                         " ;
           if  ($WorkGubun == 28)
           {
               $sQuery .= " And singo.Silmooja = '777777' " ;
           }
           if  ($WorkGubun == 33)
           {
               $sQuery .= " And singo.Silmooja = '555595' " ;
           }
           if  ($WorkGubun == 34) // ���ʽ�
           {
               $sQuery .= " And showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // �Ե����׸�
           {
               $sQuery .= " And showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // �ް��ڽ�
           {
               $sQuery .= " And showroom.MultiPlex  = '3' " ;
           }
           if  ($nFilmTypeNo != "0")
           {
               $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
           }
           $QrySingo2 = mysql_query($sQuery,$connect) ;
           if  ($AryCntShowroom = mysql_fetch_array($QrySingo2))
           {
               $cntRealScreen = $AryCntShowroom["cntShowroom"] ;
           }

           $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                     "       As cntFinishShowroom                         ".
                     "  From ".$sSingoName." As singo,                    ".
                     "       bas_showroom As showroom,                    ".
                     "       bas_silmoojatheatherfinish As finish         ".
                     " Where singo.singodate  = '".$WorkDate."'           ".
                     "   And singo.location = 200                         ".
                     "   And singo.theather = showroom.theather           ".
                     "   And singo.room     = showroom.room               ".
                     "   And singo.theather = finish.theather             ".
                     "   And singo.room     = finish.room                 ".
                     $AddedCont."                                         ".
                     $FinishCont."                                        ".
                     "   And singo.Silmooja = finish.silmooja             " ;
           if  ($WorkGubun == 28)
           {
               $sQuery .= " And singo.Silmooja = '777777' " ;
           }
           if  ($WorkGubun == 33)
           {
               $sQuery .= " And singo.Silmooja = '555595' " ;
           }
           if  ($WorkGubun == 34) // ���ʽ�
           {
               $sQuery .= " And showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // �Ե����׸�
           {
               $sQuery .= " And showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // �ް��ڽ�
           {
               $sQuery .= " And showroom.MultiPlex  = '3' " ;
           }
           if  ($nFilmTypeNo != "0")
           {
               $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
           }
           $QrySingo2 = mysql_query($sQuery,$connect) ;
           if  ($AryCntShowroom = mysql_fetch_array($QrySingo2))
           {
                $cntRealScreen = $cntRealScreen - $AryCntShowroom["cntFinishShowroom"] ;
           }
           ?>
           <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><?=number_format($cntRealScreen)?>&nbsp;&nbsp;</td>

           <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><b>�հ�</b></td>

           <?
           // �¼��� (���� �λ���ü)
           $SumSeatSeat = 0 ;

           $sQuery = "Select distinct singo.theather, singo.room,         ".
                     "                singo.showdgree, showroom.seat      ".
                     "  From ".$sSingoName." As singo,                    ".
                     "       bas_showroom As showroom                     ".
                     " Where singo.singodate  = '".$WorkDate."'           ".
                     "   And ( singo.location = 200                       ".
                     "    Or   singo.location = 203                       ".
                     "    Or   singo.location = 600                       ".
                     "    Or   singo.location = 207                       ".
                     "    Or   singo.location = 205                       ".
                     "    Or   singo.location = 208                       ".
                     "    Or   singo.location = 202                       ".
                     "    Or   singo.location = 211                       ".
                     "    Or   singo.location = 212                       ".
                     "    Or   singo.location = 213                       ".
                     "    Or   singo.location = 201 )                     ".
                     "   And singo.theather = showroom.theather           ".
                     "   And singo.room     = showroom.room               ".
                     $AddedCont."                                         " ;
           if  ($WorkGubun == 28)
           {
               $sQuery .= " And singo.Silmooja = '777777' " ;
           }
           if  ($WorkGubun == 33)
           {
               $sQuery .= " And singo.Silmooja = '555595' " ;
           }
           if  ($WorkGubun == 34) // ���ʽ�
           {
               $sQuery .= " And showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // �Ե����׸�
           {
               $sQuery .= " And showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // �ް��ڽ�
           {
               $sQuery .= " And showroom.MultiPlex  = '3' " ;
           }
           if  ($nFilmTypeNo != "0")
           {
               $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
           }
           $QrySingo2 = mysql_query($sQuery,$connect) ;
           while ($ArySumSeat = mysql_fetch_array($QrySingo2))
           {
                 $SumSeatSeat += $ArySumSeat["seat"] ;
           }

           $TotSeat = $TotSeat + $SumSeatSeat ; // ���¼���...
           ?>
           <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatSeat)?></td>

           <?
           $ModifyScore  = 0 ;
           $ModifyAmount = 0 ;

           $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore,  ".
                     "       Sum(ModifyAmount) As SumOfModifyAmount ".
                     "  From bas_modifyscore                        ".
                     " Where ( location = 200                       ".
                     "    Or   location = 203                       ".
                     "    Or   location = 600                       ".
                     "    Or   location = 207                       ".
                     "    Or   location = 201 )                     ".
                     "   And Open       = '".$singoOpen."'          ".
                     "   And Film       = '".$singoFilm."'          ".
                     "   And ModifyDate = '".$WorkDate."'           " ;
           $qry_modifyscore  = mysql_query($sQuery,$connect) ;
           if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
           {
               $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
               $ModifyAmount = $modifyscore_data["SumOfModifyAmount"] ;
           }


           $nTotNumPersons = 0 ;
           $nTotTotAmount  = 0 ;

           if  ($WorkGubun == 28)
           {
               $AddedCont .= " And singo.Silmooja = '777777' " ;
           }
           if  ($WorkGubun == 33)
           {
               $AddedCont .= " And singo.Silmooja = '555595' " ;
           }
           if  ($WorkGubun == 34) // ���ʽ�
           {
               $AddedCont .= " And showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // �Ե����׸�
           {
               $AddedCont .= " And showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // �ް��ڽ�
           {
               $AddedCont .= " And showroom.MultiPlex  = '3' " ;
           }
           if  ($nFilmTypeNo != "0")
           {
               $AddedCont .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
           }
           $sQuery = "Select singo.showdgree As ShowDgree,                ".
                     "       Sum(singo.NumPersons) As SumNumPersons,      ".
                     "       Sum(TotAmount) As SumTotAmount               ".
                     "  From ".$sSingoName." As singo,                    ".
                     "       bas_showroom As showroom                     ".
                     " Where ( singo.location = 200                       ".
                     "    Or   singo.location = 203                       ".
                     "    Or   singo.location = 600                       ".
                     "    Or   singo.location = 207                       ".
                     "    Or   singo.location = 201 )                     ".
                     "   And singo.singodate  = '".$WorkDate."'           ".
                     "   And singo.theather = showroom.theather           ".
                     "   And singo.room     = showroom.room               ".
                     $AddedCont."                                         ".
                     " Group By singo.showdgree                           ".
                     " Order By singo.showdgree                           " ;

           $QrySingo2 = mysql_query($sQuery,$connect) ;

           $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;

           for  ($i=1; $i<=11; $i++)
           {
                $sDgree = sprintf("%02d", $i) ;  // 01,02,03,........

                if  ($i==11)
                {
                    $sDgree = "99" ; // �ɾ�
                }

                if  ($ArySumNumPersons)
                {
                    if  ($sDgree == $ArySumNumPersons["ShowDgree"])
                    {
                        if  ($sDgree == "01")
                        {
                            $dispData = number_format($ArySumNumPersons["SumNumPersons"]+$ModifyScore) ;

                            $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                            $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"]  + $ModifyAmount ;

                            $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                            $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                        }
                        else
                        {
                            $dispData = number_format($ArySumNumPersons["SumNumPersons"]) ;

                            $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] ;
                            $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"] ;

                            $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                            $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                        }

                        $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;
                    }
                    else
                    {
                        $dispData = "0" ;
                    }
                }
                else
                {
                    $dispData = "0" ;
                }

                if  ($i!=10)  // �������ͳ׼ų�
                {
                ?>
                <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispData?></td>
                <?
                }

           }
           ?>
           <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotNumPersons)?></td>
           <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotTotAmount)?></td>
      </tr>
      <!----------------------------------------------------------------------------------------------------------->
      <tr>
           <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center>
           <b>������</b>
           </td>

           <?
           // �� ������ ..
           if  ($SumSeatSeat==0)
           {
               $SumSeatRate = 0 ;
           }
           else
           {
               $SumSeatRate = $nTotNumPersons / $SumSeatSeat * 100.0 ;
           }

           ?>
           <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>

           <?
           for ($i = 1 ; $i <= 11 ; $i++)
           {
               if  ($i!=10)// �������ͳ׼ų�
               {
                   if  ($SumSeatSeat==0)
                   {
                       $ByDegreeRate = 0 ;
                   }
                   else
                   {
                       $ByDegreeRate = $arryDegree[ $i ] / $SumSeatSeat * 100.0 ;
                   }

                   ?>
                   <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($ByDegreeRate,2)?>%</td>
                   <?
               }
           }
           ?>

           <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
           <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
      </tr>

      <!----------------------------------------------------------------------------------------------------------->
      <!--
                                                 �氭 �հ� ���
                                                 (10)
                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?

      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // ȸ���� ���ھ�
      }

      $sQuery = "Select * From bas_filmsupplyzoneloc  ".
                " Where Zone = '10'                   " ;
      $qryzone = mysql_query($sQuery,$connect) ;
      if  ($zone_data = mysql_fetch_array($qryzone))
      {

          if  ($ToExel)
          {
              $ColorC = "#ffffff" ;
          }
          else
          {
              if  ($clrCToggle==true)
              {
                  $ColorC = "#dcdcdc" ;

                  $clrCToggle=false ;
              }
              else
              {
                  $ColorC = "#cccccc" ;

                  $clrCToggle=true ;
              }
          }

          $sQuery = "Select * From bas_zone  ".
                    " Where Code = '10'      " ;
          $qryzone = mysql_query($sQuery,$connect) ;
          if  ($zone_data = mysql_fetch_array($qryzone))
          {
              $zoneName = $zone_data["Name"] ;
          }


          $AddedLoc = " and " ;

          $sQuery = "Select Location                            ".
                    "  From bas_filmsupplyzoneloc               ".
                    " Where Zone = '10'                         " ;
          $qryzoneloc = mysql_query($sQuery,$connect) ;
          while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
          {
               if   ($AddedLoc == " and ")
               $AddedLoc .= "( singo.Location = '".$zoneloc_data["Location"]."' "  ;
               else
               $AddedLoc .= " or singo.Location = '".$zoneloc_data["Location"]."' "  ;
          }
          $AddedLoc .= ")" ;

          ?>

          <tr>
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>�氭</b></td>
               <?
               // ��ũ���� (�氭)
               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntShowroom                               ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $QrySingo2 = mysql_query($sQuery,$connect) ;
               if  ($AryCntShowroom = mysql_fetch_array($QrySingo2))
               {
                   $cntRealScreen = $AryCntShowroom["cntShowroom"] ;
               }
               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntFinishShowroom                         ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom,                    ".
                         "       bas_silmoojatheatherfinish As finish         ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         "   And singo.theather = finish.theather             ".
                         "   And singo.room     = finish.room                 ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          ".
                         $FinishCont."                                        ".
                         "   And singo.Silmooja = finish.silmooja             " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $QrySingo2 = mysql_query($sQuery,$connect) ;
               if  ($AryCntShowroom = mysql_fetch_array($QrySingo2))
               {
                   $cntRealScreen = $cntRealScreen - $AryCntShowroom["cntFinishShowroom"] ;
               }
               ?>
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><?=number_format($cntRealScreen)?>&nbsp;&nbsp;</td>

               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><b>�հ�</b></td>

               <?

               // �¼��� (�氭��ü)
               $SumSeatSeat = 0 ;

               $sQuery = "Select distinct singo.theather, singo.room,         ".
                         "                singo.showdgree, showroom.seat      ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $QrySingo2 = mysql_query($sQuery,$connect) ;
               while ($ArySumSeat = mysql_fetch_array($QrySingo2))
               {
                     $SumSeatSeat += $ArySumSeat["seat"] ;
               }

               $TotSeat = $TotSeat + $SumSeatSeat ; // ���¼���...
               ?>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatSeat)?></td>




               <?
               $ModifyScore  = 0 ;
               $ModifyAmount = 0 ;


               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore,  ".
                         "       Sum(ModifyAmount) As SumOfModifyAmount ".
                         "  From bas_modifyscore                        ".
                         " Where Open       = '".$singoOpen."'          ".
                         "   And Film       = '".$singoFilm."'          ".
                         "   And ModifyDate = '".$WorkDate."'           ".
                         $AddedLoc."                                    " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
                   $ModifyAmount = $modifyscore_data["SumOfModifyAmount"] ;
               }


               $nTotNumPersons = 0 ;
               $nTotTotAmount  = 0 ;

               if  ($WorkGubun == 28)
               {
                   $AddedCont .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $AddedCont .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $AddedCont .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $AddedCont .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $AddedCont .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $AddedCont .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $sQuery = "Select singo.showdgree As ShowDgree,                ".
                         "       Sum(singo.NumPersons) As SumNumPersons,      ".
                         "       Sum(TotAmount) As SumTotAmount               ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          ".
                         " Group By singo.showdgree                           ".
                         " Order By singo.showdgree                           " ;

               $QrySingo2 = mysql_query($sQuery,$connect) ;

               $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;

               for  ($i=1; $i<=11; $i++)
               {
                    $sDgree = sprintf("%02d", $i) ;  // 01,02,03,........

                    if  ($i==11)
                    {
                        $sDgree = "99" ; // �ɾ�
                    }

                    if  ($ArySumNumPersons)
                    {
                        if  ($sDgree == $ArySumNumPersons["ShowDgree"])
                        {
                            if  ($sDgree == "01")
                            {
                                $dispData = number_format($ArySumNumPersons["SumNumPersons"]+$ModifyScore) ;

                                $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                                $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"]  + $ModifyAmount ;

                                $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                                $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                            }
                            else
                            {
                                $dispData = number_format($ArySumNumPersons["SumNumPersons"]) ;

                                $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] ;
                                $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"] ;

                                $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                                $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                            }

                            $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;
                        }
                        else
                        {
                            $dispData = "0" ;
                        }
                    }
                    else
                    {
                        $dispData = "0" ;
                    }

                    if  ($i!=10)  // �������ͳ׼ų�
                    {
                    ?>
                    <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispData?></td>
                    <?
                    }

               }
               ?>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotNumPersons)?></td>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotTotAmount)?></td>
          </tr>
          <!----------------------------------------------------------------------------------------------------------->
          <tr>
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center>
               <b>������</b>
               </td>

               <?
               // �� ������ ..
               if  ($SumSeatSeat==0)
               {
                   $SumSeatRate = 0 ;
               }
               else
               {
                   $SumSeatRate = $nTotNumPersons / $SumSeatSeat * 100.0 ;
               }

               ?>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>

               <?
               for ($i = 1 ; $i <= 11 ; $i++)
               {
                   if  ($i!=10)  // �������ͳ׼ų�
                   {
                       if  ($SumSeatSeat==0)
                       {
                           $ByDegreeRate = 0 ;
                       }
                       else
                       {
                           $ByDegreeRate = $arryDegree[ $i ] / $SumSeatSeat * 100.0 ;
                       }

                       ?>
                       <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($ByDegreeRate,2)?>%</td>
                       <?
                   }
               }
               ?>

               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
          </tr>
          <?
      }
      ?>


      <!----------------------------------------------------------------------------------------------------------->
      <!--
                                                 ��û �հ� ���
                                                 (35)
                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?
      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // ȸ���� ���ھ�
      }

      $sQuery = "Select * From bas_filmsupplyzoneloc  ".
                " Where Zone = '35'                   " ;
      $qryzone = mysql_query($sQuery,$connect) ;
      if  ($zone_data = mysql_fetch_array($qryzone))
      {
           if  ($ToExel)
           {
               $ColorC = "#ffffff" ;
           }
           else
           {
               if  ($clrCToggle==true)
               {
                   $ColorC = "#dcdcdc" ;

                   $clrCToggle=false ;
               }
               else
               {
                   $ColorC = "#cccccc" ;

                   $clrCToggle=true ;
               }
           }

           $sQuery = "Select * From bas_zone  ".
                     " Where Code = '35'      " ;
           $qryzone = mysql_query($sQuery,$connect) ;
           if  ($zone_data = mysql_fetch_array($qryzone))
           {
               $zoneName = $zone_data["Name"] ;
           }

           $AddedLoc = " and " ;

           $sQuery = "Select Location                             ".
                     "  From bas_filmsupplyzoneloc                ".
                     " Where Zone = '35'                          " ;
           $qryzoneloc = mysql_query($sQuery,$connect) ;
           while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
           {
                if  ($AddedLoc == " and ")
                    $AddedLoc .= "( singo.Location = '".$zoneloc_data["Location"]."' "  ;
                else
                    $AddedLoc .= " or singo.Location = '".$zoneloc_data["Location"]."' "  ;
           }
           $AddedLoc .= ")" ;
          ?>
          <tr>
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>��û</b></td>
               <?
               // ��ũ���� (��û)
               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntShowroom                               ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $QrySingo2 = mysql_query($sQuery,$connect) ;
               if  ($AryCntShowroom = mysql_fetch_array($QrySingo2))
               {
                   $cntRealScreen = $AryCntShowroom["cntShowroom"] ;
               }
               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntFinishShowroom                         ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom,                    ".
                         "       bas_silmoojatheatherfinish As finish         ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         "   And singo.theather = finish.theather             ".
                         "   And singo.room     = finish.room                 ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          ".
                         $FinishCont."                                        ".
                         "   And singo.Silmooja = finish.silmooja             " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $QrySingo2 = mysql_query($sQuery,$connect) ;
               if  ($AryCntShowroom = mysql_fetch_array($QrySingo2))
               {
                   $cntRealScreen = $cntRealScreen - $AryCntShowroom["cntFinishShowroom"] ;
               }
               ?>
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><?=number_format($cntRealScreen)?>&nbsp;&nbsp;</td>

               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><b>�հ�</b></td>

               <?

               // �¼��� (��û��ü)
               $SumSeatSeat = 0 ;

               $sQuery = "Select distinct singo.theather, singo.room,         ".
                         "                singo.showdgree, showroom.seat      ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $QrySingo2 = mysql_query($sQuery,$connect) ;
               while ($ArySumSeat = mysql_fetch_array($QrySingo2))
               {
                     $SumSeatSeat += $ArySumSeat["seat"] ;
               }

               $TotSeat = $TotSeat + $SumSeatSeat ; // ���¼���...
               ?>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatSeat)?></td>




               <?
               $ModifyScore  = 0 ;
               $ModifyAmount = 0 ;


               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore,  ".
                         "       Sum(ModifyAmount) As SumOfModifyAmount ".
                         "  From bas_modifyscore                        ".
                         " Where Open       = '".$singoOpen."'          ".
                         "   And Film       = '".$singoFilm."'          ".
                         "   And ModifyDate = '".$WorkDate."'           ".
                         $AddedLoc."                                    " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
                   $ModifyAmount = $modifyscore_data["SumOfModifyAmount"] ;
               }


               $nTotNumPersons = 0 ;
               $nTotTotAmount  = 0 ;

               if  ($WorkGubun == 28)
               {
                   $AddedCont .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $AddedCont .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $AddedCont .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $AddedCont .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $AddedCont .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $AddedCont .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $sQuery = "Select singo.showdgree As ShowDgree,                ".
                         "       Sum(singo.NumPersons) As SumNumPersons,      ".
                         "       Sum(TotAmount) As SumTotAmount               ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          ".
                         " Group By singo.showdgree                           ".
                         " Order By singo.showdgree                           " ;

               $QrySingo2 = mysql_query($sQuery,$connect) ;

               $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;

               for  ($i=1; $i<=11; $i++)
               {
                    $sDgree = sprintf("%02d", $i) ;  // 01,02,03,........

                    if  ($i==11)
                    {
                        $sDgree = "99" ; // �ɾ�
                    }

                    if  ($ArySumNumPersons)
                    {
                        if  ($sDgree == $ArySumNumPersons["ShowDgree"])
                        {
                            if  ($sDgree == "01")
                            {
                                $dispData = number_format($ArySumNumPersons["SumNumPersons"]+$ModifyScore) ;

                                $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                                $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"]  + $ModifyAmount ;

                                $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                                $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                            }
                            else
                            {
                                $dispData = number_format($ArySumNumPersons["SumNumPersons"]) ;

                                $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] ;
                                $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"] ;

                                $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                                $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                            }

                            $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;
                        }
                        else
                        {
                            $dispData = "0" ;
                        }
                    }
                    else
                    {
                        $dispData = "0" ;
                    }

                    if  ($i!=10)  // �������ͳ׼ų�
                    {
                    ?>
                    <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispData?></td>
                    <?
                    }

               }
               ?>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotNumPersons)?></td>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotTotAmount)?></td>
          </tr>
          <!----------------------------------------------------------------------------------------------------------->
          <tr>
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center>
               <b>������</b>
               </td>

               <?
               // �� ������ ..
               if  ($SumSeatSeat==0)
               {
                   $SumSeatRate = 0 ;
               }
               else
               {
                   $SumSeatRate = $nTotNumPersons / $SumSeatSeat * 100.0 ;
               }

               ?>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>

               <?
               for ($i = 1 ; $i <= 11 ; $i++)
               {
                   if  ($i!=10)  // �������ͳ׼ų�
                   {
                       if  ($SumSeatSeat==0)
                       {
                           $ByDegreeRate = 0 ;
                       }
                       else
                       {
                           $ByDegreeRate = $arryDegree[ $i ] / $SumSeatSeat * 100.0 ;
                       }

                       ?>
                       <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($ByDegreeRate,2)?>%</td>
                       <?
                   }
               }
               ?>

               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
          </tr>
          <?
      }
      ?>


      <!----------------------------------------------------------------------------------------------------------->
      <!--
                                                 �泲 �հ� ���
                                                 (20)
                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?
      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // ȸ���� ���ھ�
      }

      $sQuery = "Select * From bas_filmsupplyzoneloc  ".
                " Where Zone = '20'                   " ;
      $qryzone = mysql_query($sQuery,$connect) ;
      if  ($zone_data = mysql_fetch_array($qryzone))
      {
           if  ($ToExel)
           {
               $ColorC = "#ffffff" ;
           }
           else
           {
               if  ($clrCToggle==true)
               {
                   $ColorC = "#dcdcdc" ;

                   $clrCToggle=false ;
               }
               else
               {
                   $ColorC = "#cccccc" ;

                   $clrCToggle=true ;
               }
           }

           $sQuery = "Select * From bas_zone  ".
                     " Where Code = '20'      " ;
           $qryzone = mysql_query($sQuery,$connect) ;
           if  ($zone_data = mysql_fetch_array($qryzone))
           {
               $zoneName = $zone_data["Name"] ;
           }

           $AddedLoc = " and " ;

           $sQuery = "Select Location                             ".
                     "  From bas_filmsupplyzoneloc                ".
                     " Where Zone = '20'                          " ;
           $qryzoneloc = mysql_query($sQuery,$connect) ;
           while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
           {
                if  ($AddedLoc == " and ")
                    $AddedLoc .= "( singo.Location = '".$zoneloc_data["Location"]."' "  ;
                else
                    $AddedLoc .= " or singo.Location = '".$zoneloc_data["Location"]."' "  ;
           }
           $AddedLoc .= " or singo.Location = '200' "  ; // �泲�� �λ굵 ���Եȴ�.
           $AddedLoc .= " or singo.Location = '203' "  ; // �泲�� �뿵�� ���Եȴ�.
           $AddedLoc .= " or singo.Location = '600' "  ; // �泲�� ��굵 ���Եȴ�.
           $AddedLoc .= " or singo.Location = '207' "  ; // �泲�� ���ص� ���Եȴ�.
           $AddedLoc .= " or singo.Location = '205' "  ; // �泲�� ���ֵ� ���Եȴ�.
           $AddedLoc .= " or singo.Location = '208' "  ; // �泲�� ������ ���Եȴ�.
           $AddedLoc .= " or singo.Location = '202' "  ; // �泲�� ���굵 ���Եȴ�.
           $AddedLoc .= " or singo.Location = '211' "  ; // �泲�� ��õ�� ���Եȴ�.
           $AddedLoc .= " or singo.Location = '212' "  ; // �泲�� ��â�� ���Եȴ�.
           $AddedLoc .= " or singo.Location = '213' "  ; // �泲�� ��굵 ���Եȴ�.
           $AddedLoc .= " or singo.Location = '201' "  ; // �泲�� â���� ���Եȴ�.
           $AddedLoc .= ")" ;
           ?>
           <tr>
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>�泲</b></td>
               <?
               // ��ũ���� (�泲)
               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntShowroom                               ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $QrySingo2 = mysql_query($sQuery,$connect) ;
               if  ($AryCntShowroom = mysql_fetch_array($QrySingo2))
               {
                   $cntRealScreen = $AryCntShowroom["cntShowroom"] ;
               }
               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntFinishShowroom                         ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom,                    ".
                         "       bas_silmoojatheatherfinish As finish         ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         "   And singo.theather = finish.theather             ".
                         "   And singo.room     = finish.room                 ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          ".
                         $FinishCont."                                        ".
                         "   And singo.Silmooja = finish.silmooja             " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $QrySingo2 = mysql_query($sQuery,$connect) ;
               if  ($AryCntShowroom = mysql_fetch_array($QrySingo2))
               {
                   $cntRealScreen = $cntRealScreen - $AryCntShowroom["cntFinishShowroom"] ;
               }
               ?>
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><?=number_format($cntRealScreen)?>&nbsp;&nbsp;</td>

               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><b>�հ�</b></td>

               <?

               // �¼��� (�泲��ü)
               $SumSeatSeat = 0 ;

               $sQuery = "Select distinct singo.theather, singo.room,         ".
                         "                singo.showdgree, showroom.seat      ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $QrySingo2 = mysql_query($sQuery,$connect) ;
               while ($ArySumSeat = mysql_fetch_array($QrySingo2))
               {
                     $SumSeatSeat += $ArySumSeat["seat"] ;
               }

               $TotSeat = $TotSeat + $SumSeatSeat ; // ���¼���...
               ?>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatSeat)?></td>




               <?
               $ModifyScore  = 0 ;
               $ModifyAmount = 0 ;


               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore,  ".
                         "       Sum(ModifyAmount) As SumOfModifyAmount ".
                         "  From bas_modifyscore                        ".
                         " Where Open       = '".$singoOpen."'          ".
                         "   And Film       = '".$singoFilm."'          ".
                         "   And ModifyDate = '".$WorkDate."'           ".
                         $AddedLoc."                                    " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
                   $ModifyAmount = $modifyscore_data["SumOfModifyAmount"] ;
               }


               $nTotNumPersons = 0 ;
               $nTotTotAmount  = 0 ;

               if  ($WorkGubun == 28)
               {
                   $AddedCont .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $AddedCont .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $AddedCont .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $AddedCont .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $AddedCont .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $AddedCont .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $sQuery = "Select singo.showdgree As ShowDgree,                ".
                         "       Sum(singo.NumPersons) As SumNumPersons,      ".
                         "       Sum(TotAmount) As SumTotAmount               ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          ".
                         " Group By singo.showdgree                           ".
                         " Order By singo.showdgree                           " ;
               $QrySingo2 = mysql_query($sQuery,$connect) ;

               $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;

               for  ($i=1; $i<=11; $i++)
               {
                    $sDgree = sprintf("%02d", $i) ;  // 01,02,03,........

                    if  ($i==11)
                    {
                        $sDgree = "99" ; // �ɾ�
                    }

                    if  ($ArySumNumPersons)
                    {
                        if  ($sDgree == $ArySumNumPersons["ShowDgree"])
                        {
                            if  ($sDgree == "01")
                            {
                                $dispData = number_format($ArySumNumPersons["SumNumPersons"]+$ModifyScore) ;

                                $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                                $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"]  + $ModifyAmount ;

                                $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                                $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                            }
                            else
                            {
                                $dispData = number_format($ArySumNumPersons["SumNumPersons"]) ;

                                $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] ;
                                $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"] ;

                                $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                                $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                            }

                            $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;
                        }
                        else
                        {
                            $dispData = "0" ;
                        }
                    }
                    else
                    {
                        $dispData = "0" ;
                    }

                    if  ($i!=10)  // �������ͳ׼ų�
                    {
                    ?>
                    <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispData?></td>
                    <?
                    }

               }
               ?>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotNumPersons)?></td>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotTotAmount)?></td>
          </tr>
          <!----------------------------------------------------------------------------------------------------------->
          <tr>
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center>
               <b>������</b>
               </td>

               <?
               // �� ������ ..
               if  ($SumSeatSeat==0)
               {
                   $SumSeatRate = 0 ;
               }
               else
               {
                   $SumSeatRate = $nTotNumPersons / $SumSeatSeat * 100.0 ;
               }

               ?>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>

               <?
               for ($i = 1 ; $i <= 11 ; $i++)
               {
                   if  ($i!=10)  // �������ͳ׼ų�
                   {
                       if  ($SumSeatSeat==0)
                       {
                           $ByDegreeRate = 0 ;
                       }
                       else
                       {
                           $ByDegreeRate = $arryDegree[ $i ] / $SumSeatSeat * 100.0 ;
                       }

                       ?>
                       <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($ByDegreeRate,2)?>%</td>
                       <?
                   }
               }
               ?>

               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
          </tr>
          <?
      }
      ?>


      <!----------------------------------------------------------------------------------------------------------->
      <!--
                                                 ��� �հ� ���
                                                 (21)
                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?
      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // ȸ���� ���ھ�
      }

      $sQuery = "Select * From bas_filmsupplyzoneloc ".
                " Where Zone = '21'                  " ;
      $qryzone = mysql_query($sQuery,$connect) ;
      if  ($zone_data = mysql_fetch_array($qryzone))
      {
          if  ($ToExel)
          {
              $ColorC = "#ffffff" ;
          }
          else
          {
              if  ($clrCToggle==true)
              {
                  $ColorC = "#dcdcdc" ;

                  $clrCToggle=false ;
              }
              else
              {
                  $ColorC = "#cccccc" ;

                  $clrCToggle=true ;
              }
          }

          $sQuery = "Select * From bas_zone  ".
                    " Where Code = '21'      " ;
          $qryzone = mysql_query($sQuery,$connect) ;
          if  ($zone_data = mysql_fetch_array($qryzone))
          {
              $zoneName = $zone_data["Name"] ;
          }

          $AddedLoc = " and " ;

          $sQuery = "Select Location                     ".
                    "  From bas_filmsupplyzoneloc        ".
                    " Where Zone = '21'                  " ;
          $qryzoneloc = mysql_query($sQuery,$connect) ;
          while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
          {
               if  ($AddedLoc == " and ")
                   $AddedLoc .= "( singo.Location = '".$zoneloc_data["Location"]."' "  ;
               else
                   $AddedLoc .= " or singo.Location = '".$zoneloc_data["Location"]."' "  ;
          }
          $AddedLoc .= ")" ;
          ?>
          <tr>
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>���</b></td>
               <?
               // ��ũ���� (���)
               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntShowroom                               ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $QrySingo2 = mysql_query($sQuery,$connect) ;
               if  ($AryCntShowroom = mysql_fetch_array($QrySingo2))
               {
                   $cntRealScreen = $AryCntShowroom["cntShowroom"] ;
               }
               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntFinishShowroom                         ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom,                    ".
                         "       bas_silmoojatheatherfinish As finish         ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         "   And singo.theather = finish.theather             ".
                         "   And singo.room     = finish.room                 ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          ".
                         $FinishCont."                                        ".
                         "   And singo.Silmooja = finish.silmooja             " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $QrySingo2 = mysql_query($sQuery,$connect) ;
               if  ($AryCntShowroom = mysql_fetch_array($QrySingo2))
               {
                   $cntRealScreen = $cntRealScreen - $AryCntShowroom["cntFinishShowroom"] ;
               }
               ?>
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><?=number_format($cntRealScreen)?>&nbsp;&nbsp;</td>

               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><b>�հ�</b></td>

               <?

               // �¼��� (�����ü)
               $SumSeatSeat = 0 ;

               $sQuery = "Select distinct singo.theather, singo.room,         ".
                         "                singo.showdgree, showroom.seat      ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $QrySingo2 = mysql_query($sQuery,$connect) ;
               while ($ArySumSeat = mysql_fetch_array($QrySingo2))
               {
                     $SumSeatSeat += $ArySumSeat["seat"] ;
               }

               $TotSeat = $TotSeat + $SumSeatSeat ; // ���¼���...
               ?>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatSeat)?></td>




               <?
               $ModifyScore  = 0 ;
               $ModifyAmount = 0 ;


               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore,  ".
                         "       Sum(ModifyAmount) As SumOfModifyAmount ".
                         "  From bas_modifyscore                        ".
                         " Where Open       = '".$singoOpen."'          ".
                         "   And Film       = '".$singoFilm."'          ".
                         "   And ModifyDate = '".$WorkDate."'           ".
                         $AddedLoc."                                    " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
                   $ModifyAmount = $modifyscore_data["SumOfModifyAmount"] ;
               }


               $nTotNumPersons = 0 ;
               $nTotTotAmount  = 0 ;

               if  ($WorkGubun == 28)
               {
                   $AddedCont .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $AddedCont .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $AddedCont .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $AddedCont .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $AddedCont .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $AddedCont .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $sQuery = "Select singo.showdgree As ShowDgree,                ".
                         "       Sum(singo.NumPersons) As SumNumPersons,      ".
                         "       Sum(TotAmount) As SumTotAmount               ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          ".
                         " Group By singo.showdgree                           ".
                         " Order By singo.showdgree                           " ;

               $QrySingo2 = mysql_query($sQuery,$connect) ;

               $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;

               for  ($i=1; $i<=11; $i++)
               {
                    $sDgree = sprintf("%02d", $i) ;  // 01,02,03,........

                    if  ($i==11)
                    {
                        $sDgree = "99" ; // �ɾ�
                    }

                    if  ($ArySumNumPersons)
                    {
                        if  ($sDgree == $ArySumNumPersons["ShowDgree"])
                        {
                            if  ($sDgree == "01")
                            {
                                $dispData = number_format($ArySumNumPersons["SumNumPersons"]+$ModifyScore) ;

                                $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                                $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"]  + $ModifyAmount ;

                                $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                                $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                            }
                            else
                            {
                                $dispData = number_format($ArySumNumPersons["SumNumPersons"]) ;

                                $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] ;
                                $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"] ;

                                $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                                $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                            }

                            $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;
                        }
                        else
                        {
                            $dispData = "0" ;
                        }
                    }
                    else
                    {
                        $dispData = "0" ;
                    }

                    if  ($i!=10)  // �������ͳ׼ų�
                    {
                    ?>
                    <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispData?></td>
                    <?
                    }

               }
               ?>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotNumPersons)?></td>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotTotAmount)?></td>
          </tr>
          <!----------------------------------------------------------------------------------------------------------->
          <tr>
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center>
               <b>������</b>
               </td>

               <?
               // �� ������ ..
               if  ($SumSeatSeat==0)
               {
                   $SumSeatRate = 0 ;
               }
               else
               {
                   $SumSeatRate = $nTotNumPersons / $SumSeatSeat * 100.0 ;
               }

               ?>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>

               <?
               for ($i = 1 ; $i <= 11 ; $i++)
               {
                   if  ($i!=10)  // �������ͳ׼ų�
                   {
                       if  ($SumSeatSeat==0)
                       {
                           $ByDegreeRate = 0 ;
                       }
                       else
                       {
                           $ByDegreeRate = $arryDegree[ $i ] / $SumSeatSeat * 100.0 ;
                       }

                       ?>
                       <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($ByDegreeRate,2)?>%</td>
                       <?
                   }
               }
               ?>

               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
          </tr>
          <?
      }
      ?>


      <!----------------------------------------------------------------------------------------------------------->
      <!--
                                                 ȣ�� �հ� ���
                                                 (50)
                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?
      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // ȸ���� ���ھ�
      }

      $sQuery = "Select * From bas_filmsupplyzoneloc ".
                " Where Zone = '50'                  " ;
      $qryzone = mysql_query($sQuery,$connect) ;
      if  ($zone_data = mysql_fetch_array($qryzone))
      {
          if  ($ToExel)
          {
              $ColorC = "#ffffff" ;
          }
          else
          {
              if  ($clrCToggle==true)
              {
                  $ColorC = "#dcdcdc" ;

                  $clrCToggle=false ;
              }
              else
              {
                  $ColorC = "#cccccc" ;

                  $clrCToggle=true ;
              }
          }

          $sQuery = "Select * From bas_zone  ".
                    " Where Code = '50'      " ;
          $qryzone = mysql_query($sQuery,$connect) ;
          if  ($zone_data = mysql_fetch_array($qryzone))
          {
              $zoneName = $zone_data["Name"] ;
          }

          $AddedLoc = " and " ;

          $sQuery = "Select Location                            ".
                    "  From bas_filmsupplyzoneloc               ".
                    " Where Zone = '50'                         " ;
          $qryzoneloc = mysql_query($sQuery,$connect) ;
          while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
          {
               if  ($AddedLoc == " and ")
                   $AddedLoc .= "( singo.Location = '".$zoneloc_data["Location"]."' "  ;
               else
                   $AddedLoc .= " or singo.Location = '".$zoneloc_data["Location"]."' "  ;
          }
          $AddedLoc .= ")" ;
          ?>
          <tr>
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>ȣ��</b></td>
               <?
               // ��ũ���� (ȣ��)
               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntShowroom                               ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $QrySingo2 = mysql_query($sQuery,$connect) ;
               if  ($AryCntShowroom = mysql_fetch_array($QrySingo2))
               {
                   $cntRealScreen = $AryCntShowroom["cntShowroom"] ;
               }
               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntFinishShowroom                         ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom,                    ".
                         "       bas_silmoojatheatherfinish As finish         ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         "   And singo.theather = finish.theather             ".
                         "   And singo.room     = finish.room                 ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          ".
                         $FinishCont."                                        ".
                         "   And singo.Silmooja = finish.silmooja             " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $QrySingo2 = mysql_query($sQuery,$connect) ;
               if  ($AryCntShowroom = mysql_fetch_array($QrySingo2))
               {
                   $cntRealScreen = $cntRealScreen - $AryCntShowroom["cntFinishShowroom"] ;
               }
               ?>
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><?=number_format($cntRealScreen)?>&nbsp;&nbsp;</td>

               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><b>�հ�</b></td>

               <?

               // �¼��� (ȣ����ü)
               $SumSeatSeat = 0 ;

               $sQuery = "Select distinct singo.theather, singo.room,         ".
                         "                singo.showdgree, showroom.seat      ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $QrySingo2 = mysql_query($sQuery,$connect) ;
               while ($ArySumSeat = mysql_fetch_array($QrySingo2))
               {
                     $SumSeatSeat += $ArySumSeat["seat"] ;
               }

               $TotSeat = $TotSeat + $SumSeatSeat ; // ���¼���...
               ?>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatSeat)?></td>




               <?
               $ModifyScore  = 0 ;
               $ModifyAmount = 0 ;


               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore,  ".
                         "       Sum(ModifyAmount) As SumOfModifyAmount ".
                         "  From bas_modifyscore                        ".
                         " Where Open       = '".$singoOpen."'          ".
                         "   And Film       = '".$singoFilm."'          ".
                         "   And ModifyDate = '".$WorkDate."'           ".
                         $AddedLoc."                                    " ;
               if  ($WorkGubun == 28)
               {
                   $sQuery .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $sQuery .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $sQuery .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
                   $ModifyAmount = $modifyscore_data["SumOfModifyAmount"] ;
               }


               $nTotNumPersons = 0 ;
               $nTotTotAmount  = 0 ;

               if  ($WorkGubun == 28)
               {
                   $AddedCont .= " And singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $AddedCont .= " And singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $AddedCont .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $AddedCont .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $AddedCont .= " And showroom.MultiPlex  = '3' " ;
               }
               if  ($nFilmTypeNo != "0")
               {
                   $AddedCont .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
               }
               $sQuery = "Select singo.showdgree As ShowDgree,                ".
                         "       Sum(singo.NumPersons) As SumNumPersons,      ".
                         "       Sum(TotAmount) As SumTotAmount               ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          ".
                         " Group By singo.showdgree                           ".
                         " Order By singo.showdgree                           " ;

               $QrySingo2 = mysql_query($sQuery,$connect) ;

               $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;

               for  ($i=1; $i<=11; $i++)
               {
                    $sDgree = sprintf("%02d", $i) ;  // 01,02,03,........

                    if  ($i==11)
                    {
                        $sDgree = "99" ; // �ɾ�
                    }

                    if  ($ArySumNumPersons)
                    {
                        if  ($sDgree == $ArySumNumPersons["ShowDgree"])
                        {
                            if  ($sDgree == "01")
                            {
                                $dispData = number_format($ArySumNumPersons["SumNumPersons"]+$ModifyScore) ;

                                $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                                $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"]  + $ModifyAmount ;

                                $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                                $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                            }
                            else
                            {
                                $dispData = number_format($ArySumNumPersons["SumNumPersons"]) ;

                                $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] ;
                                $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"] ;

                                $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                                $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                            }

                            $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;
                        }
                        else
                        {
                            $dispData = "0" ;
                        }
                    }
                    else
                    {
                        $dispData = "0" ;
                    }

                    if  ($i!=10)  // �������ͳ׼ų�
                    {
                    ?>
                    <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispData?></td>
                    <?
                    }

               }
               ?>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotNumPersons)?></td>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotTotAmount)?></td>
          </tr>
          <!----------------------------------------------------------------------------------------------------------->
          <tr>
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center>
               <b>������</b>
               </td>

               <?
               // �� ������ ..
               if  ($SumSeatSeat==0)
               {
                   $SumSeatRate = 0 ;
               }
               else
               {
                   $SumSeatRate = $nTotNumPersons / $SumSeatSeat * 100.0 ;
               }

               ?>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>

               <?
               for ($i = 1 ; $i <= 11 ; $i++)
               {
                   if  ($i!=10)  // �������ͳ׼ų�
                   {
                       if  ($SumSeatSeat==0)
                       {
                           $ByDegreeRate = 0 ;
                       }
                       else
                       {
                           $ByDegreeRate = $arryDegree[ $i ] / $SumSeatSeat * 100.0 ;
                       }

                       ?>
                       <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($ByDegreeRate,2)?>%</td>
                       <?
                   }
               }
               ?>

               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
               <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
          </tr>
          <?
      }
      ?>


      <!----------------------------------------------------------------------------------------------------------->
      <!--
                                                 ���� �հ� ���  // �������ͳ׼ų�

                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?
      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // ȸ���� ���ھ�
      }

      if  ($ToExel)
      {
          $ColorC = "#ffffff" ;
      }
      else
      {
          if  ($clrCToggle==true)
          {
              $ColorC = "#dcdcdc" ;

              $clrCToggle=false ;
          }
          else
          {
              $ColorC = "#cccccc" ;

              $clrCToggle=true ;
          }
      }

      $sQuery = "Select Location From bas_filmsupplyzoneloc ".
                " Where Zone = '04'                         " ;
      $qryzoneloc = mysql_query($sQuery,$connect) ;

      $AddedLoc = " and " ;
      $AddedLocMD = " and " ;

      while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
      {
           if  ($AddedLoc == " and ")
               $AddedLoc .= "( singo.Location <> '".$zoneloc_data["Location"]."' "  ;
           else
               $AddedLoc .= " and singo.Location <> '".$zoneloc_data["Location"]."' "  ;

           if  ($AddedLocMD == " and ")
               $AddedLocMD .= "( Location <> '".$zoneloc_data["Location"]."' "  ;
           else
               $AddedLocMD .= " and Location <> '".$zoneloc_data["Location"]."' "  ;
      }
      $AddedLoc .= " and singo.Location <> '100' "  ; // ����
      $AddedLoc .= " and singo.Location <> '200' "  ; // �λ�
      $AddedLoc .= " and singo.Location <> '203' "  ; // �뿵
      $AddedLoc .= " and singo.Location <> '600' "  ; // ���
      $AddedLoc .= " and singo.Location <> '207' "  ; // ����
      $AddedLoc .= " and singo.Location <> '205' "  ; // ����
      $AddedLoc .= " and singo.Location <> '208' "  ; // ����
      $AddedLoc .= " and singo.Location <> '202' "  ; // ����
      $AddedLoc .= " and singo.Location <> '211' "  ; // ��õ
      $AddedLoc .= " and singo.Location <> '212' "  ; // ��â
      $AddedLoc .= " and singo.Location <> '213' "  ; // ���
      $AddedLoc .= " and singo.Location <> '201' "  ; // â��
      $AddedLoc .= ")" ;

      $AddedLocMD .= " and Location <> '100' "  ; // ����
      $AddedLocMD .= " and Location <> '200' "  ; // �λ�
      $AddedLocMD .= " and Location <> '203' "  ; // �뿵
      $AddedLocMD .= " and Location <> '600' "  ; // ���
      $AddedLocMD .= " and Location <> '207' "  ; // ����
      $AddedLocMD .= " and Location <> '205' "  ; // ����
      $AddedLocMD .= " and Location <> '208' "  ; // ����
      $AddedLocMD .= " and Location <> '202' "  ; // ����
      $AddedLocMD .= " and Location <> '211' "  ; // ��õ
      $AddedLocMD .= " and Location <> '212' "  ; // ��â
      $AddedLocMD .= " and Location <> '213' "  ; // ���
      $AddedLocMD .= " and Location <> '201' "  ; // â��
      $AddedLocMD .= ")" ;


      // ��� + ���� + �λ� �� ������ �������� �������� �Ѵ�.
      ?>
      <tr>
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>����</b></td>
            <?
            // ��ũ���� (����)
            $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                      "       As cntShowroom                               ".
                      "  From ".$sSingoName." As singo,                    ".
                      "       bas_showroom As showroom                     ".
                      " Where singo.singodate  = '".$WorkDate."'           ".
                      "   And singo.theather = showroom.theather           ".
                      "   And singo.room     = showroom.room               ".
                      $AddedCont."                                         ".
                      $AddedLoc."                                          " ;
            if  ($WorkGubun == 28)
            {
                $sQuery .= " And singo.Silmooja = '777777' " ;
            }
            if  ($WorkGubun == 33)
            {
                $sQuery .= " And singo.Silmooja = '555595' " ;
            }
            if  ($WorkGubun == 34) // ���ʽ�
            {
                $sQuery .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // �Ե����׸�
            {
                $sQuery .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // �ް��ڽ�
            {
                $sQuery .= " And showroom.MultiPlex  = '3' " ;
            }
            if  ($nFilmTypeNo != "0")
            {
                $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
            }
//eq($sQuery);
            $QrySingo2 = mysql_query($sQuery,$connect) ;
            if  ($AryCntShowroom = mysql_fetch_array($QrySingo2))
            {
                $cntRealScreen = $AryCntShowroom["cntShowroom"] ;
            }
            $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                      "       As cntFinishShowroom                         ".
                      "  From ".$sSingoName." As singo,                    ".
                      "       bas_showroom As showroom,                    ".
                      "       bas_silmoojatheatherfinish As finish         ".
                      " Where singo.singodate  = '".$WorkDate."'           ".
                      "   And singo.theather = showroom.theather           ".
                      "   And singo.room     = showroom.room               ".
                      "   And singo.theather = finish.theather             ".
                      "   And singo.room     = finish.room                 ".
                      $AddedCont."                                         ".
                      $AddedLoc."                                          ".
                      $FinishCont."                                        ".
                      "   And singo.Silmooja = finish.silmooja             " ;
            if  ($WorkGubun == 28)
            {
                $sQuery .= " And singo.Silmooja = '777777' " ;
            }
            if  ($WorkGubun == 33)
            {
                $sQuery .= " And singo.Silmooja = '555595' " ;
            }
            if  ($WorkGubun == 34) // ���ʽ�
            {
                $sQuery .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // �Ե����׸�
            {
                $sQuery .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // �ް��ڽ�
            {
                $sQuery .= " And showroom.MultiPlex  = '3' " ;
            }
            if  ($nFilmTypeNo != "0")
            {
                $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
            }
            $QrySingo2 = mysql_query($sQuery,$connect) ;
            if  ($AryCntShowroom = mysql_fetch_array($QrySingo2))
            {
                $cntRealScreen = $cntRealScreen - $AryCntShowroom["cntFinishShowroom"] ;
            }
            ?>
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><?=number_format($cntRealScreen)?>&nbsp;&nbsp;</td>

            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><b>�հ�</b></td>

            <?

            // �¼��� (������ü)
            $SumSeatSeat = 0 ;

            $sQuery = "Select distinct singo.theather, singo.room,         ".
                      "                singo.showdgree, showroom.seat      ".
                      "  From ".$sSingoName." As singo,                    ".
                      "       bas_showroom As showroom                     ".
                      " Where singo.singodate  = '".$WorkDate."'           ".
                      "   And singo.theather = showroom.theather           ".
                      "   And singo.room     = showroom.room               ".
                      $AddedCont."                                         ".
                      $AddedLoc."                                          " ;
            if  ($WorkGubun == 28)
            {
                $sQuery .= " And singo.Silmooja = '777777' " ;
            }
            if  ($WorkGubun == 33)
            {
                $sQuery .= " And singo.Silmooja = '555595' " ;
            }
            if  ($WorkGubun == 34) // ���ʽ�
            {
                $sQuery .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // �Ե����׸�
            {
                $sQuery .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // �ް��ڽ�
            {
                $sQuery .= " And showroom.MultiPlex  = '3' " ;
            }
            if  ($nFilmTypeNo != "0")
            {
                $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
            }
            $QrySingo2 = mysql_query($sQuery,$connect) ;
            while ($ArySumSeat = mysql_fetch_array($QrySingo2))
            {
                  $SumSeatSeat += $ArySumSeat["seat"] ;
            }

            $TotSeat = $TotSeat + $SumSeatSeat ; // ���¼���...
            ?>
            <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatSeat)?></td>




            <?
            $ModifyScore  = 0 ;
            $ModifyAmount = 0 ;

            $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore,  ".
                      "       Sum(ModifyAmount) As SumOfModifyAmount ".
                      "  From bas_modifyscore As singo,              ".
                      "       bas_showroom As showroom               ".
                      " Where singo.Open       = '".$singoOpen."'    ".
                      "   And singo.Film       = '".$singoFilm."'    ".
                      "   And singo.ModifyDate = '".$WorkDate."'     ".
                      "   And singo.theather = showroom.theather     ".
                      "   And singo.room     = showroom.room         ".
                      $AddedLoc."                                    " ;
            if  ($WorkGubun == 28)
            {
                $sQuery .= " And singo.Silmooja = '777777' " ;
            }
            if  ($WorkGubun == 33)
            {
                $sQuery .= " And singo.Silmooja = '555595' " ;
            }
            if  ($WorkGubun == 34) // ���ʽ�
            {
                $sQuery .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // �Ե����׸�
            {
                $sQuery .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // �ް��ڽ�
            {
                $sQuery .= " And showroom.MultiPlex  = '3' " ;
            }
            $qry_modifyscore  = mysql_query($sQuery,$connect) ;
            if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
            {
                $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
                $ModifyAmount = $modifyscore_data["SumOfModifyAmount"] ;
            }


            $nTotNumPersons = 0 ;
            $nTotTotAmount  = 0 ;

            if  ($WorkGubun == 28)
            {
                $AddedCont .= " And singo.Silmooja = '777777' " ;
            }
            if  ($WorkGubun == 33)
            {
                $AddedCont .= " And singo.Silmooja = '555595' " ;
            }
            if  ($WorkGubun == 34) // ���ʽ�
            {
                $AddedCont .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // �Ե����׸�
            {
                $AddedCont .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // �ް��ڽ�
            {
                $AddedCont .= " And showroom.MultiPlex  = '3' " ;
            }
            if  ($nFilmTypeNo != "0")
            {
                $AddedCont .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
            }
            $sQuery = "Select singo.showdgree As ShowDgree,                ".
                      "       Sum(singo.NumPersons) As SumNumPersons,      ".
                      "       Sum(TotAmount) As SumTotAmount               ".
                      "  From ".$sSingoName." As singo,                    ".
                      "       bas_showroom As showroom                     ".
                      " Where singo.singodate  = '".$WorkDate."'           ".
                      "   And singo.theather = showroom.theather           ".
                      "   And singo.room     = showroom.room               ".
                      $AddedCont."                                         ".
                      $AddedLoc."                                          ".
                      " Group By singo.showdgree                           ".
                      " Order By singo.showdgree                           " ;

            $QrySingo2 = mysql_query($sQuery,$connect) ;
            $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;

            for  ($i=1; $i<=11; $i++)
            {
                 $sDgree = sprintf("%02d", $i) ;  // 01,02,03,........

                 if  ($i==11)
                 {
                     $sDgree = "99" ; // �ɾ�
                 }

                 if  ($ArySumNumPersons)
                 {
                     if  ($sDgree == $ArySumNumPersons["ShowDgree"])
                     {
                         if  ($sDgree == "01")
                         {
                             $dispData = number_format($ArySumNumPersons["SumNumPersons"]+$ModifyScore) ;

                             $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                             $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"]  + $ModifyAmount ;

                             $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                             $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                         }
                         else
                         {
                             $dispData = number_format($ArySumNumPersons["SumNumPersons"]) ;

                             $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] ;
                             $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"] ;

                             $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                             $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                         }

                         $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;
                     }
                     else
                     {
                         $dispData = "0" ;
                     }
                 }
                 else
                 {
                     $dispData = "0" ;
                 }

                 if  ($i!=10)  // �������ͳ׼ų�
                 {
                 ?>
                 <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispData?></td>
                 <?
                 }

            }
            ?>
            <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotNumPersons)?></td>
            <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotTotAmount)?></td>
      </tr>
      <!----------------------------------------------------------------------------------------------------------->
      <tr>
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center>
            <b>������</b>
            </td>

            <?
            // �� ������ ..
            if  ($SumSeatSeat==0)
            {
                $SumSeatRate = 0 ;
            }
            else
            {
                $SumSeatRate = $nTotNumPersons / $SumSeatSeat * 100.0 ;
            }

            ?>
            <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>

            <?
            for ($i = 1 ; $i <= 11 ; $i++)
            {
                if  ($i!=10)  // �������ͳ׼ų�
                {
                    if  ($SumSeatSeat==0)
                    {
                        $ByDegreeRate = 0 ;
                    }
                    else
                    {
                        $ByDegreeRate = $arryDegree[ $i ] / $SumSeatSeat * 100.0 ;
                    }

                    ?>
                    <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($ByDegreeRate,2)?>%</td>
                    <?
                }
            }
            ?>

            <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
            <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
      </tr>
      <?

      ?>


      <!----------------------------------------------------------------------------------------------------------->
      <!--
                                                 �� �հ� ���

                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?
      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // ȸ���� ���ھ�
      }

      if  ($ToExel)
      {
          $ColorC = "#ffffff" ;
      }
      else
      {
          if  ($clrCToggle==true)
          {
              $ColorC = "#dcdcdc" ;

              $clrCToggle=false ;
          }
          else
          {
              $ColorC = "#cccccc" ;

              $clrCToggle=true ;
          }
      }
      ?>

      <tr>
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>���հ�</b></td>
            <?
            // ��ũ���� (���հ�)
            $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                      "       As cntShowroom                               ".
                      "  From ".$sSingoName." As singo,                    ".
                      "       bas_showroom As showroom                     ".
                      " Where singo.singodate  = '".$WorkDate."'           ".
                      "   And singo.theather = showroom.theather           ".
                      "   And singo.room     = showroom.room               ".
                      $AddedCont."                                         " ;
            if  ($WorkGubun == 28)
            {
                $sQuery .= " And singo.Silmooja = '777777' " ;
            }
            if  ($WorkGubun == 33)
            {
                $sQuery .= " And singo.Silmooja = '555595' " ;
            }
            if  ($WorkGubun == 34) // ���ʽ�
            {
                $sQuery .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // �Ե����׸�
            {
                $sQuery .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // �ް��ڽ�
            {
                $sQuery .= " And showroom.MultiPlex  = '3' " ;
            }
            if  ($nFilmTypeNo != "0")
            {
                $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
            }
            $qrysingo3 = mysql_query($sQuery,$connect) ;
            if  ($AryCntShowroom = mysql_fetch_array($qrysingo3))
            {
                $cntRealScreen = $AryCntShowroom["cntShowroom"] ;
            }
            $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                      "       As cntFinishShowroom                         ".
                      "  From ".$sSingoName." As singo,                    ".
                      "       bas_showroom As showroom,                    ".
                      "       bas_silmoojatheatherfinish As finish         ".
                      " Where singo.singodate  = '".$WorkDate."'           ".
                      "   And singo.theather = showroom.theather           ".
                      "   And singo.room     = showroom.room               ".
                      "   And singo.theather = finish.theather             ".
                      "   And singo.room     = finish.room                 ".
                      $AddedCont."                                         ".
                      $FinishCont."                                        ".
                      "   And singo.Silmooja = finish.silmooja             " ;
            if  ($WorkGubun == 28)
            {
                $sQuery .= " And singo.Silmooja = '777777' " ;
            }
            if  ($WorkGubun == 33)
            {
                $sQuery .= " And singo.Silmooja = '555595' " ;
            }
            if  ($WorkGubun == 34) // ���ʽ�
            {
                $sQuery .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // �Ե����׸�
            {
                $sQuery .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // �ް��ڽ�
            {
                $sQuery .= " And showroom.MultiPlex  = '3' " ;
            }
            if  ($nFilmTypeNo != "0")
            {
                $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
            }
            $QrySingo2 = mysql_query($sQuery,$connect) ;
            if  ($AryCntShowroom = mysql_fetch_array($QrySingo2))
            {
                $cntRealScreen = $cntRealScreen - $AryCntShowroom["cntFinishShowroom"] ;
            }
            ?>
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><?=number_format($cntRealScreen)?>&nbsp;&nbsp;</td>

            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><b>�հ�</b></td>

            <?

            // �¼��� (���հ���ü)
            $SumSeatSeat = 0 ;

            $sQuery = "Select distinct singo.theather, singo.room,         ".
                      "                singo.showdgree, showroom.seat      ".
                      "  From ".$sSingoName." As singo,                    ".
                      "       bas_showroom As showroom                     ".
                      " Where singo.singodate  = '".$WorkDate."'           ".
                      "   And singo.theather = showroom.theather           ".
                      "   And singo.room     = showroom.room               ".
                      $AddedCont."                                         " ;
            if  ($WorkGubun == 28)
            {
                $sQuery .= " And singo.Silmooja = '777777' " ;
            }
            if  ($WorkGubun == 33)
            {
                $sQuery .= " And singo.Silmooja = '555595' " ;
            }
            if  ($WorkGubun == 34) // ���ʽ�
            {
                $sQuery .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // �Ե����׸�
            {
                $sQuery .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // �ް��ڽ�
            {
                $sQuery .= " And showroom.MultiPlex  = '3' " ;
            }
            if  ($nFilmTypeNo != "0")
            {
                $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
            }
            $QrySingo2 = mysql_query($sQuery,$connect) ;
            while ($ArySumSeat = mysql_fetch_array($QrySingo2))
            {
                  $SumSeatSeat += $ArySumSeat["seat"] ;
            }

            $TotSeat = $TotSeat + $SumSeatSeat ; // ���¼���...
            ?>
            <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatSeat)?></td>




            <?
            $ModifyScore  = 0 ;
            $ModifyAmount = 0 ;


            $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore,  ".
                      "       Sum(ModifyAmount) As SumOfModifyAmount ".
                      "  From bas_modifyscore As singo,              ".
                      "       bas_showroom As showroom               ".
                      " Where singo.Open       = '".$singoOpen."'    ".
                      "   And singo.Film       = '".$singoFilm."'    ".
                      "   And singo.theather   = showroom.theather   ".
                      "   And singo.room       = showroom.room       ".
                      "   And singo.ModifyDate = '".$WorkDate."'     " ;
            if  ($WorkGubun == 28)
            {
                $sQuery .= " And singo.Silmooja = '777777' " ;
            }
            if  ($WorkGubun == 33)
            {
                $sQuery .= " And singo.Silmooja = '555595' " ;
            }
            if  ($WorkGubun == 34) // ���ʽ�
            {
                $sQuery .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // �Ե����׸�
            {
                $sQuery .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // �ް��ڽ�
            {
                $sQuery .= " And showroom.MultiPlex  = '3' " ;
            }
            $qry_modifyscore  = mysql_query($sQuery,$connect) ;
            if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
            {
                $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
                $ModifyAmount = $modifyscore_data["SumOfModifyAmount"] ;
            }


            $nTotNumPersons = 0 ;
            $nTotTotAmount  = 0 ;

            if  ($WorkGubun == 28)
            {
                $AddedCont .= " And singo.Silmooja = '777777' " ;
            }
            if  ($WorkGubun == 33)
            {
                $AddedCont .= " And singo.Silmooja = '555595' " ;
            }
            if  ($WorkGubun == 34) // ���ʽ�
            {
                $AddedCont .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // �Ե����׸�
            {
                $AddedCont .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // �ް��ڽ�
            {
                $AddedCont .= " And showroom.MultiPlex  = '3' " ;
            }
            if  ($nFilmTypeNo != "0")
            {
                $AddedCont .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
            }
            $sQuery = "Select singo.showdgree As ShowDgree,                ".
                      "       Sum(singo.NumPersons) As SumNumPersons,      ".
                      "       Sum(TotAmount) As SumTotAmount               ".
                      "  From ".$sSingoName." As singo,                    ".
                      "       bas_showroom As showroom                     ".
                      " Where singo.singodate  = '".$WorkDate."'           ".
                      "   And singo.theather = showroom.theather           ".
                      "   And singo.room     = showroom.room               ".
                      $AddedCont."                                         ".
                      " Group By singo.showdgree                           ".
                      " Order By singo.showdgree                           " ;

            $QrySingo2 = mysql_query($sQuery,$connect) ;

            $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;

            for  ($i=1; $i<=11; $i++)
            {
                 $sDgree = sprintf("%02d", $i) ;  // 01,02,03,........

                 if  ($i==11)
                 {
                     $sDgree = "99" ; // �ɾ�
                 }

                 if  ($ArySumNumPersons)
                 {
                     if  ($sDgree == $ArySumNumPersons["ShowDgree"])
                     {
                         if  ($sDgree == "01")
                         {
                             $dispData = number_format($ArySumNumPersons["SumNumPersons"]+$ModifyScore) ;

                             $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                             $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"]  + $ModifyAmount ;

                             $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                             $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                         }
                         else
                         {
                             $dispData = number_format($ArySumNumPersons["SumNumPersons"]) ;

                             $nTotNumPersons = $nTotNumPersons + $ArySumNumPersons["SumNumPersons"] ;
                             $nTotTotAmount  = $nTotTotAmount  + $ArySumNumPersons["SumTotAmount"] ;

                             $arryDegree[$i]      = $ArySumNumPersons["SumNumPersons"] + $ModifyScore ;
                             $arrySumOfDegree[$i] = $arrySumOfDegree[$i] + $arryDegree[$i] ;
                         }

                         $ArySumNumPersons = mysql_fetch_array($QrySingo2) ;
                     }
                     else
                     {
                         $dispData = "0" ;
                     }
                 }
                 else
                 {
                     $dispData = "0" ;
                 }

                 if  ($i!=10)  // �������ͳ׼ų�
                 {
                 ?>
                 <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispData?></td>
                 <?
                 }

            }
            ?>
            <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotNumPersons)?></td>
            <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($nTotTotAmount)?></td>
      </tr>
      <!----------------------------------------------------------------------------------------------------------->
      <tr>
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center>
            <b>������</b>
            </td>

            <?
            // �� ������ ..
            if  ($SumSeatSeat==0)
            {
                $SumSeatRate = 0 ;
            }
            else
            {
                $SumSeatRate = $nTotNumPersons / $SumSeatSeat * 100.0 ;
            }

            ?>
            <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>

            <?
            for ($i = 1 ; $i <= 11 ; $i++)
            {
                if  ($i!=10)  // �������ͳ׼ų�
                {
                    if  ($SumSeatSeat==0)
                    {
                        $ByDegreeRate = 0 ;
                    }
                    else
                    {
                        $ByDegreeRate = $arryDegree[ $i ] / $SumSeatSeat * 100.0 ;
                    }

                    ?>
                    <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($ByDegreeRate,2)?>%</td>
                    <?
                }
            }
            ?>

            <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
            <td class="textarea bottomsum_val" bgcolor=<?=$ColorC?> align=right><?=number_format($SumSeatRate,2)?>%</td>
      </tr>

   </table>

   <br>
   <br>
   <br>

