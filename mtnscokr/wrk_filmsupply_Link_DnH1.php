   <?
   $SubSumScreen = 0 ;
   $SubSumSeat   = 0 ;

   $FirstZone  = 0 ;

   $cntLocData = 0 ;

   for ($i=0 ; $i<=($dur_day+4) ; $i++)
   {
       $arrySoGea[$i]    = 0 ; // 지역이 끝날때 마다 초기화 한다.
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
           $locationName     = $arrTheather["Name"] ; // 지역명 ..
       }

       /**
       $sQuery = "Select * From bas_theather         ".
                 " Where Code = '".$singoTheather."' " ;
       $qrysilmooja = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
       if  ($showroom_data = mysql_fetch_array($qrysilmooja))
       {
           $showroomDiscript = $showroom_data["Discript"] ;
           $showroomLocation = $showroom_data["Location"] ;

           // 상영관의 소재지 지역을 구한다. ($locationName)
           $sQuery = "Select * From bas_location            ".
                     " Where Code = '".$showroomLocation."' " ;
           $query1 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
           if  ($location_data = mysql_fetch_array($query1))
           {
               $locationName = $location_data["Name"] ; // 지역명 ..
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
      // 가격별 스코어
      // --------------------------------------------

      $FirstPrice = true ;

      if  ($nFilmTypeNo != 0) // All이 아닐때//.
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

      //if  ($affected_rows>1) // 도착보고가 온전히 된거
      if  ($affected_rows>0) // 도착보고가 온전히 된거
      {
          for ($i=0 ; $i<=($dur_day+4) ; $i++)
          {
              $arryHapGea[$i]  = 0 ; // 극장별 합계를 극장이 바뀔때 마다 초기화한다.
          }

          while ($singo1_data = mysql_fetch_array($QrySingo1))
          {
              $UnitPrice          = $singo1_data["UnitPrice"] ;          // 요금대역
              $AccSumNumPersons   = $singo1_data["AccSumNumPersons"] ;   // 극장별 합계(스코어)
              //$AccSumNumTotAmount = $singo1_data["AccSumNumTotAmount"] ; // 극장별 합계(금액)

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
                  <!-- 지역명 출력 -->
                  <td class=textarea bgcolor=<?=$Color1?> rowspan=<?=$affected_rows?> width=70 class=tbltitle align=center>
                  <?=$locationName?>
                  </td>

                  <!-- 상영관명 출력 -->
                  <td class=textarea bgcolor=<?=$Color1?> rowspan=<?=$affected_rows?> class=tbltitle align=center>
                  <?=$showroomDiscript?>
                  </td>


                  <!-- 상영관 좌석수 출력 -->
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

              <!-- 요금(코드) 출력 -->
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

              <!-- 요금(타이틀) 출력 -->
              <td class=textarea bgcolor=<?=$Color1?> align=right>
              <?=number_format($UnitPrice)?>&nbsp;
              </td>



              <?
              $HapNumPersons = 0 ;
              $HapTotAmount  = 0 ;

              // 일자별로 스코어를 구한다.
              if  ($nFilmTypeNo != 0) // All이 아닐때//.
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
              //  극장별 총 합계 찍기
              //
              $arrySoGea[$dur_day+1] += $HapNumPersons ; // 소계 스코어
              $arrySoGea[$dur_day+2] += $HapTotAmount ;  // 소계 금액합계

              $arryHapGea[$dur_day+1] += $HapNumPersons ; // 총계 스코어
              $arryHapGea[$dur_day+2] += $HapTotAmount ;  // 총계 금액합계

              $arryTheater[$dur_day+1] += $HapNumPersons ; // 총계 스코어
              $arryTheater[$dur_day+2] += $AccSumNumPersons ;  // 극장별 합계(스코어)

              ?>

              <!-- 스코어 합계 -->
              <td class=textarea bgcolor=<?=$Color2?> align=right>
              &nbsp;<?=number_format($HapNumPersons)?>&nbsp;
              </td>



              <!-- 스코어 총금액 -->
              <!--
              <td class=textarea bgcolor=<?=$Color2?> align=right>
              &nbsp;<?=number_format($HapTotAmount)?>&nbsp;
              </td>
              -->
              <?

              //
              //  극장별 누계 찍기
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
              //  극장별 금액 찍기
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
          <td class=textarea bgcolor=<?=$Color1?> align=right>합계</td>
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
             <!-- 지역명 출력 -->
             <td class=textarea bgcolor=<?=$Color1?> width=70 class=tbltitle align=center>
             <?=$locationName?>
             </td>

             <!-- 상영관명 출력 -->
             <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=center>
             <?=$showroomDiscript?>
             </td>

             <?
             //$SubSumSeat += $showroomSeat ;
             //$SumSeat    += $showroomSeat ;
             ?>

             <!-- 상영관 좌석수 출력 -->
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
      <b>합계</b>
      </td>

      <?
      // 상영관별 소개를 구한다.
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
   // 소계
   // --------------------------------------------

   if  ($cntLocData > 0) // 한번이라도 데이터가 나온경우
   {
   ?>
       <tr>
           <td class=textarea bgcolor=#b0c4de align=center colspan=2>
           소계(<?=$nNumSoGea?>)<? $nNumChongGea += $nNumSoGea ; ?>
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
