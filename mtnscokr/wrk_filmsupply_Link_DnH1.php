   <?
   $SubSumScreen = 0 ;
   $SubSumSeat   = 0 ;

   $FirstZone  = 0 ;

   $cntLocData = 0 ;

   for ($i=0 ; $i<=($dur_day+4) ; $i++)
   {
       $arrySoGea[$i]    = 0 ; // ������ ������ ���� �ʱ�ȭ �Ѵ�.
   }
   $nNumSoGea = 0 ;

   while ($singo_data = mysql_fetch_array($QrySingo))
   {
       for ($i=0 ; $i<=($dur_day+2) ; $i++)
       {
           $arryTheater[$i]  = 0 ; //
       }

       $singoTheather = $singo_data["Theather"] ;
       $singoRoom     = $singo_data["Room"] ;

       if  ($FilmTile != "")
       {
           $singoOpen = substr($FilmTile,0,6) ;
           $singoFilm = substr($FilmTile,6,2) ;
       }
       else
       {
           $singoOpen = $singo_data["Open"] ;
           $singoFilm = $singo_data["Film"] ;
       }

       $sQuery = "Select Theather.Discript, Location.Name     ".
                 "  From bas_theather As Theather             ".
                 "  left join bas_location As Location        ".
                 "    on  Theather.Location = Location.Code   ".
                 " Where Theather.Code = '".$singoTheather."' " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
       $qryTheather = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
       if  ($arrTheather = mysql_fetch_array($qryTheather))
       {
           $showroomDiscript = $arrTheather["Discript"] ;
           $locationName     = $arrTheather["Name"] ; // ������ ..
       }

       /**
       $sQuery = "Select * From bas_theather         ".
                 " Where Code = '".$singoTheather."' " ;
       $qrysilmooja = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
       if  ($showroom_data = mysql_fetch_array($qrysilmooja))
       {
           $showroomDiscript = $showroom_data["Discript"] ;
           $showroomLocation = $showroom_data["Location"] ;

           // �󿵰��� ������ ������ ���Ѵ�. ($locationName)
           $sQuery = "Select * From bas_location            ".
                     " Where Code = '".$showroomLocation."' " ;
           $query1 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
           if  ($location_data = mysql_fetch_array($query1))
           {
               $locationName = $location_data["Name"] ; // ������ ..
           }
       }
       **/
       /***
       $sQuery = "Select Sum(Seat) As SumOfSeat          ".
                 "  From bas_showroom                    ".
                 " Where Theather = '".$singoTheather."' " ;
       $qrysilmooja = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
       if  ($showroom_data = mysql_fetch_array($qrysilmooja))
       {
           $showroomSeat     = $showroom_data["SumOfSeat"] ;
       }
       ****/
      ?>


      <?
      // --------------------------------------------
      // ���ݺ� ���ھ�
      // --------------------------------------------

      $FirstPrice = true ;

      if  ($nFilmTypeNo != 0) // All�� �ƴҶ�//.
      {
          $sQuery = "Select UnitPrice,                            ".
                    "       Sum(NumPersons) As AccSumNumPersons   ".
                    "  From ".$sSingoName."                       ".
                    " Where SingoDate  <= '".$ToDate."'           ".
                    "   And Theather   = '".$singoTheather."'     ".
                    "   And Open       = '".$singoOpen."'         ".
                    "   And Film       = '".$singoFilm."'         ".
                    "   And FilmType   = '".$nFilmTypeNo."'       ".
                    " Group By UnitPrice                          ".
                    " Order By UnitPrice desc                     " ;
      }
      else
      {
          $sQuery = "Select UnitPrice,                            ".
                    "       Sum(NumPersons) As AccSumNumPersons   ".
                    "  From ".$sSingoName."                       ".
                    " Where SingoDate  <= '".$ToDate."'           ".
                    "   And Theather   = '".$singoTheather."'     ".
                    "   And Open       = '".$singoOpen."'         ".
                    "   And Film       = '".$singoFilm."'         ".
                    " Group By UnitPrice                          ".
                    " Order By UnitPrice desc                     " ;
      }
      $QrySingo1 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
      $affected_rows = (mysql_affected_rows()+1) ;
      //$affected_rows = (mysql_affected_rows()) ;

      //if  ($affected_rows>1) // �������� ������ �Ȱ�
      if  ($affected_rows>0) // �������� ������ �Ȱ�
      {
          for ($i=0 ; $i<=($dur_day+4) ; $i++)
          {
              $arryHapGea[$i]  = 0 ; // ���庰 �հ踦 ������ �ٲ� ���� �ʱ�ȭ�Ѵ�.
          }

          while ($singo1_data = mysql_fetch_array($QrySingo1))
          {
              $UnitPrice          = $singo1_data["UnitPrice"] ;          // ��ݴ뿪
              $AccSumNumPersons   = $singo1_data["AccSumNumPersons"] ;   // ���庰 �հ�(���ھ�)
              //$AccSumNumTotAmount = $singo1_data["AccSumNumTotAmount"] ; // ���庰 �հ�(�ݾ�)

              if  ($oldsingoTheather != $singoTheather)
              {
                  $clrToggle = !$clrToggle ;

                  $oldsingoTheather = $singoTheather ;
              }

              if  ($clrToggle==true)
              {
                  $Color1 = "#c0c0c0" ;
                  $Color2 = "#dcdcdc" ;
              }
              else
              {
                  $Color1 = "#d0d0d0" ;
                  $Color2 = "#ececec" ;
              }
              ?>


              <tr>

              <?
              if  ($FirstPrice == true)
              {
                  $AccNumTotAmount = 0 ;
                  $nNumSoGea ++ ;
              ?>
                  <!-- ������ ��� -->
                  <td class=textarea bgcolor=<?=$Color1?> rowspan=<?=$affected_rows?> width=70 class=tbltitle align=center>
                  <?=$locationName?>
                  </td>

                  <!-- �󿵰��� ��� -->
                  <td class=textarea bgcolor=<?=$Color1?> rowspan=<?=$affected_rows?> class=tbltitle align=center>
                  <?=$showroomDiscript?>
                  </td>


                  <!-- �󿵰� �¼��� ��� -->
                  <!--
                  <td class=textarea bgcolor=<?=$Color1?> rowspan=<?=$affected_rows?> class=tbltitle align=center>
                  <?=$nNumRoom?>
                  </td>

                  <td class=textarea bgcolor=<?=$Color1?> rowspan=<?=$affected_rows?> class=tbltitle align=center>
                  <?=$roomSeat?>
                  </td>
                  -->
                  <?
                  $FirstPrice = false ;
              }
              ?>

              <!-- ���(�ڵ�) ��� -->
              <?
              $sQuery = "Select * from bas_pricescode   ".
                        " where prices = ".$UnitPrice." " ;
              $qry_pricecode = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
              if   ($pricecode_data = mysql_fetch_array($qry_pricecode))
              {
                   $pricecodecode = $pricecode_data["pcode"]  ;
              }
              else
              {
                   $pricecodecode = "&nbsp;" ;
              }
              ?>
              <td class=textarea bgcolor=<?=$Color1?> align=right>
              <center><?=$pricecodecode?>&nbsp;</center>
              </td>

              <!-- ���(Ÿ��Ʋ) ��� -->
              <td class=textarea bgcolor=<?=$Color1?> align=right>
              <?=number_format($UnitPrice)?>&nbsp;
              </td>



              <?
              $HapNumPersons = 0 ;
              $HapTotAmount  = 0 ;

              // ���ں��� ���ھ ���Ѵ�.
              if  ($nFilmTypeNo != 0) // All�� �ƴҶ�//.
              {
                  $sQuery = "Select SingoDate,                         ".
                            "       Count(*) As CntData,               ".
                            "       Sum(NumPersons) As SumNumPersons,  ".
                            "       Sum(TotAmount)  As SumTotAmount    ".
                            "  From ".$sSingoName."                    ".
                            " Where SingoDate  >= '".$FromDate."'      ".
                            "   And SingoDate  <= '".$ToDate."'        ".
                            "   And Theather   = '".$singoTheather."'  ".
                            "   And Open       = '".$singoOpen."'      ".
                            "   And Film       = '".$singoFilm."'      ".
                            "   And FilmType   = '".$nFilmTypeNo."'    ".
                            "   And UnitPrice  = '".$UnitPrice."'      ".
                            " Group By SingoDate                       " ;
              }
              else
              {
                  $sQuery = "Select SingoDate,                         ".
                            "       Count(*) As CntData,               ".
                            "       Sum(NumPersons) As SumNumPersons,  ".
                            "       Sum(TotAmount)  As SumTotAmount    ".
                            "  From ".$sSingoName."                    ".
                            " Where SingoDate  >= '".$FromDate."'      ".
                            "   And SingoDate  <= '".$ToDate."'        ".
                            "   And Theather   = '".$singoTheather."'  ".
                            "   And Open       = '".$singoOpen."'      ".
                            "   And Film       = '".$singoFilm."'      ".
                            "   And UnitPrice  = '".$UnitPrice."'      ".
                            " Group By SingoDate                       " ;
              }
              $QrySingo2 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
              $NumPersons_data = mysql_fetch_array($QrySingo2) ;

              for ($i=0 ; $i<=$dur_day ; $i++)
              {
                   $objDate = date("Ymd",$timestamp2 + ($i * 86400)) ;

                   if  ($NumPersons_data)
                   {
                       if  ($objDate == $NumPersons_data["SingoDate"])
                       {
                           $HapNumPersons  += $NumPersons_data["SumNumPersons"] ;
                           $HapTotAmount   += $NumPersons_data["SumTotAmount"] ;

                           $arryHapGea[$i] += $NumPersons_data["SumNumPersons"] ;
                           $arrySoGea[$i]  += $NumPersons_data["SumNumPersons"] ;

                           $arryTheater[$i] += $NumPersons_data["SumNumPersons"] ;

                           ?>
                           <td class=textarea bgcolor=<?=$Color2?> align=right>
                           &nbsp;<?=number_format($NumPersons_data["SumNumPersons"])?>&nbsp;
                           </td>
                           <?
                           $NumPersons_data = mysql_fetch_array($QrySingo2) ;
                       }
                       else
                       {
                           ?>
                           <td class=textarea bgcolor=<?=$Color2?> align=right>
                           &nbsp;&nbsp;
                           </td>
                           <?
                       }
                   }
                   else
                   {
                       ?>
                       <td class=textarea bgcolor=<?=$Color2?> align=right>
                       &nbsp;&nbsp;
                       </td>
                       <?
                   }
              }


              //
              //  ���庰 �� �հ� ���
              //
              $arrySoGea[$dur_day+1] += $HapNumPersons ; // �Ұ� ���ھ�
              $arrySoGea[$dur_day+2] += $HapTotAmount ;  // �Ұ� �ݾ��հ�

              $arryHapGea[$dur_day+1] += $HapNumPersons ; // �Ѱ� ���ھ�
              $arryHapGea[$dur_day+2] += $HapTotAmount ;  // �Ѱ� �ݾ��հ�

              $arryTheater[$dur_day+1] += $HapNumPersons ; // �Ѱ� ���ھ�
              $arryTheater[$dur_day+2] += $AccSumNumPersons ;  // ���庰 �հ�(���ھ�)

              ?>

              <!-- ���ھ� �հ� -->
              <td class=textarea bgcolor=<?=$Color2?> align=right>
              &nbsp;<?=number_format($HapNumPersons)?>&nbsp;
              </td>



              <!-- ���ھ� �ѱݾ� -->
              <!--
              <td class=textarea bgcolor=<?=$Color2?> align=right>
              &nbsp;<?=number_format($HapTotAmount)?>&nbsp;
              </td>
              -->
              <?

              //
              //  ���庰 ���� ���
              //
              $AccNumPersons += $AccSumNumPersons ;
              $AccTotAmount  += $AccSumNumTotAmount ;

              $arryHapGea[$dur_day+3]  += $AccSumNumPersons ;
              $arrySoGea[$dur_day+3]   += $AccSumNumPersons ;
              ?>
              <td class=textarea bgcolor=<?=$Color1?> align=right>
              &nbsp;<?=number_format($AccSumNumPersons)?>&nbsp;
              </td>

              <!--
              <?

              //
              //  ���庰 �ݾ� ���
              //
              $AccNumTotAmount += $AccSumNumTotAmount ;

              $arryHapGea[$dur_day+4] += $AccSumNumTotAmount ;
              $arrySoGea[$dur_day+4]  += $AccSumNumTotAmount ;
              ?>
              <td class=textarea bgcolor=<?=$Color1?> align=right>
              &nbsp;<?=number_format($AccSumNumTotAmount)?>&nbsp;
              </td>
              -->
              </tr>

          <?
          }
          ?>
          <td class=textarea bgcolor=<?=$Color1?> align=right>&nbsp;</td>
          <td class=textarea bgcolor=<?=$Color1?> align=right>�հ�</td>
          <?
          for ($i=0 ; $i<=$dur_day ; $i++)
          {
          ?>
          <td class=textarea bgcolor=<?=$Color2?> align=right>
          &nbsp;<b><?=number_format($arryTheater[$i])?></b>&nbsp;
          </td>
          <?
          }
          ?>
          <td class=textarea bgcolor=<?=$Color2?> align=right>
          &nbsp;<b><?=number_format($arryTheater[$dur_day+1])?></b>&nbsp;
          </td>
          <td class=textarea bgcolor=<?=$Color1?> align=right>
          &nbsp;<b><?=number_format($arryTheater[$dur_day+2])?></b>&nbsp;
          </td>
      <?
      }
      else
      {
           if  ($clrToggle==true)
           {
               $Color1 = "#c0c0c0" ;
               $Color2 = "#dcdcdc" ;
           }
           else
           {
               $Color1 = "#d0d0d0" ;
               $Color2 = "#ececec" ;
           }
      ?>
          <tr>
             <!-- ������ ��� -->
             <td class=textarea bgcolor=<?=$Color1?> width=70 class=tbltitle align=center>
             <?=$locationName?>
             </td>

             <!-- �󿵰��� ��� -->
             <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=center>
             <?=$showroomDiscript?>
             </td>

             <?
             //$SubSumSeat += $showroomSeat ;
             //$SumSeat    += $showroomSeat ;
             ?>

             <!-- �󿵰� �¼��� ��� -->
             <!--
             <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=center>
             <?=$showroomSeat?>&nbsp;
             </td>
             -->
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
          </tr>
      <?
      }
      ?>

      <!--
      <tr>
      <td class=textarea bgcolor=<?=$Color1?> align=center>
      <b>�հ�</b>
      </td>

      <?
      // �󿵰��� �Ұ��� ���Ѵ�.
      for ($i=0 ; $i<=($dur_day+1) ; $i++)
      {
      ?>
          <td class=textarea bgcolor=<?=$Color1?> align=right>
          &nbsp;<b><?=number_format($arryHapGea[$i])?></b>&nbsp;
          </td>
      <?
      }
      ?>
      <td class=textarea bgcolor=<?=$Color1?> align=right>
      &nbsp;<b><?=number_format($arryHapGea[$dur_day+3])?></b>&nbsp;
      </td>
      </tr>
      -->


      <?

      $cntLocData ++ ;

      $FirstZone ++  ;
   }
   ?>




   <?
   // --------------------------------------------
   // �Ұ�
   // --------------------------------------------

   if  ($cntLocData > 0) // �ѹ��̶� �����Ͱ� ���°��
   {
   ?>
       <tr>
           <td class=textarea bgcolor=#b0c4de align=center colspan=2>
           �Ұ�(<?=$nNumSoGea?>)<? $nNumChongGea += $nNumSoGea ; ?>
           </td>

           <!--
           <td class=textarea bgcolor=#b0c4de align=right>
           &nbsp;<b><?=number_format($SubSumScreen)?></b>&nbsp;
           </td>

           <td class=textarea bgcolor=#b0c4de align=right>
           &nbsp;<b><?=number_format($SubSumSeat)?></b>&nbsp;
           </td>
            -->

           <td class=textarea bgcolor=#b0c4de align=right>
           &nbsp;
           </td>

           <td class=textarea bgcolor=#b0c4de align=right>
           &nbsp;
           </td>

           <?
           for ($i=0 ; $i<=($dur_day+1) ; $i++)
           {
               ?>
               <td class=textarea bgcolor=#b0c4de align=right>
               &nbsp;<b><?=number_format($arrySoGea[$i])?></b>&nbsp;
               </td>
               <?
           }

           ?>
               <td class=textarea bgcolor=#b0c4de align=right>
               &nbsp;<b><?=number_format($arrySoGea[$dur_day+3])?></b>&nbsp;
               </td>
       </tr>
   <?
   }
   ?>
