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
   $TotSeat = 0 ; // (총합계) 총 좌석수

   for ($i = 1 ; $i <= 11 ; $i++)
   {
       $arrySumOfDegree[$i] = 0 ;  // 회차별 스코어 합계
   }
   ?>

   <!---------------------------------------------------------------------------------------------------------------------->
   <!--

                                                   지역구역별 점유율 집계
                                                                                                                         -->
   <!---------------------------------------------------------------------------------------------------------------------->


   <table cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">


      <!----------------------------------------------------------------------------------------------------------->
      <!--

                                                 타이틀 찍기
                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <tr>
            <?
            if  ($ToExel)
            {
            ?>
                <td class=textarea bgcolor=#ffffff align=center><b>지역</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>스크린수</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>합계/점유율</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>총좌석수</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>1회</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>2회</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>3회</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>4회</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>5회</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>6회</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>7회</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>8회</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>9회</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>심야</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>합계</b></td>
                <td class=textarea bgcolor=#ffffff align=center><b>당일금액</b></td>
            <?
            }
            else
            {
            ?>
                <td class=textarea bgcolor=#ffebcd align=center><b>지역</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>스크린수</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>합계/점유율</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>총좌석수</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>1회</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>2회</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>3회</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>4회</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>5회</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>6회</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>7회</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>8회</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>9회</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>심야</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>합계</b></td>
                <td class=textarea bgcolor=#ffebcd align=center><b>당일금액</b></td>
            <?
            }
            ?>
      </tr>


      <?
      $AddedCont = "" ; // 추가적인 검색조건

      $singoOpen = substr($FilmTile,0,6) ;
      $singoFilm = substr($FilmTile,6,2) ;

      $sSingoName = get_singotable($singoOpen,$singoFilm,$connect) ;  // 신고 테이블 이름..

      // 특정 영화만 선택적으로 보고자 할 경우
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
                                                 서울 합계 찍기
                                                 (100)
                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?
      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // 회차별 스코어
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
           <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>서울</b></td>

           <?
           // 스크린수 (서울)
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
           if  ($WorkGubun == 34) // 씨너스
           {
               $sQuery .= " And showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // 롯데씨네마
           {
               $sQuery .= " And showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // 메가박스
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
           if  ($WorkGubun == 34) // 씨너스
           {
               $sQuery .= " And showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // 롯데씨네마
           {
               $sQuery .= " And showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // 메가박스
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
           <b>합계</b>
           </td>

           <?
           // 좌석수 (서울전체)
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
           if  ($WorkGubun == 34) // 씨너스
           {
               $sQuery .= " And showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // 롯데씨네마
           {
               $sQuery .= " And showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // 메가박스
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

           $TotSeat = $TotSeat + $SumSeatSeat ; // 총좌석수...
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
           if  ($WorkGubun == 34) // 씨너스
           {
               $AddedCont .= " And showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // 롯데씨네마
           {
               $AddedCont .= " And showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // 메가박스
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
                    $sDgree = "99" ; // 심야
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

                if  ($i!=10)  // 예상인터네셔널
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
           <b>점유율</b>
           </td>

           <?
           // 총 점유율 ..
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
               if  ($i!=10)  // 예상인터네셔널
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
                                                 경기 합계 찍기
                                                 (04)
                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?
      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // 회차별 스코어
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
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>경기</b></td>

               <?
               // 스크린수 (경기)
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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

               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><b>합계</b></td>

               <?
               // 좌석수 (경기전체)
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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

               $TotSeat = $TotSeat + $SumSeatSeat ; // 총좌석수...
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $AddedCont .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $AddedCont .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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
                        $sDgree = "99" ; // 심야
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

                    if  ($i!=10)  // 예상인터네셔널
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
               <b>점유율</b>
               </td>

               <?
               // 총 점유율 ..
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
                   if  ($i!=10)  // 예상인터네셔널
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
                                                 부산 합계 찍기
                                                 (200)
                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?
      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // 회차별 스코어
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
           <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>부산</b></td>
           <?
           // 스크린수 (예상 부산)
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
           if  ($WorkGubun == 34) // 씨너스
           {
               $sQuery .= " And showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // 롯데씨네마
           {
               $sQuery .= " And showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // 메가박스
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
           if  ($WorkGubun == 34) // 씨너스
           {
               $sQuery .= " And showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // 롯데씨네마
           {
               $sQuery .= " And showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // 메가박스
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

           <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><b>합계</b></td>

           <?
           // 좌석수 (예상 부산전체)
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
           if  ($WorkGubun == 34) // 씨너스
           {
               $sQuery .= " And showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // 롯데씨네마
           {
               $sQuery .= " And showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // 메가박스
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

           $TotSeat = $TotSeat + $SumSeatSeat ; // 총좌석수...
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
           if  ($WorkGubun == 34) // 씨너스
           {
               $AddedCont .= " And showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // 롯데씨네마
           {
               $AddedCont .= " And showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // 메가박스
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
                    $sDgree = "99" ; // 심야
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

                if  ($i!=10)  // 예상인터네셔널
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
           <b>점유율</b>
           </td>

           <?
           // 총 점유율 ..
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
               if  ($i!=10)// 예상인터네셔널
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
                                                 경강 합계 찍기
                                                 (10)
                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?

      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // 회차별 스코어
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
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>경강</b></td>
               <?
               // 스크린수 (경강)
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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

               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><b>합계</b></td>

               <?

               // 좌석수 (경강전체)
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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

               $TotSeat = $TotSeat + $SumSeatSeat ; // 총좌석수...
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $AddedCont .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $AddedCont .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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
                        $sDgree = "99" ; // 심야
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

                    if  ($i!=10)  // 예상인터네셔널
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
               <b>점유율</b>
               </td>

               <?
               // 총 점유율 ..
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
                   if  ($i!=10)  // 예상인터네셔널
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
                                                 충청 합계 찍기
                                                 (35)
                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?
      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // 회차별 스코어
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
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>충청</b></td>
               <?
               // 스크린수 (충청)
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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

               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><b>합계</b></td>

               <?

               // 좌석수 (충청전체)
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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

               $TotSeat = $TotSeat + $SumSeatSeat ; // 총좌석수...
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $AddedCont .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $AddedCont .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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
                        $sDgree = "99" ; // 심야
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

                    if  ($i!=10)  // 예상인터네셔널
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
               <b>점유율</b>
               </td>

               <?
               // 총 점유율 ..
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
                   if  ($i!=10)  // 예상인터네셔널
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
                                                 경남 합계 찍기
                                                 (20)
                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?
      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // 회차별 스코어
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
           $AddedLoc .= " or singo.Location = '200' "  ; // 경남에 부산도 포함된다.
           $AddedLoc .= " or singo.Location = '203' "  ; // 경남에 통영도 포함된다.
           $AddedLoc .= " or singo.Location = '600' "  ; // 경남에 울산도 포함된다.
           $AddedLoc .= " or singo.Location = '207' "  ; // 경남에 김해도 포함된다.
           $AddedLoc .= " or singo.Location = '205' "  ; // 경남에 진주도 포함된다.
           $AddedLoc .= " or singo.Location = '208' "  ; // 경남에 거제도 포함된다.
           $AddedLoc .= " or singo.Location = '202' "  ; // 경남에 마산도 포함된다.
           $AddedLoc .= " or singo.Location = '211' "  ; // 경남에 사천도 포함된다.
           $AddedLoc .= " or singo.Location = '212' "  ; // 경남에 거창도 포함된다.
           $AddedLoc .= " or singo.Location = '213' "  ; // 경남에 양산도 포함된다.
           $AddedLoc .= " or singo.Location = '201' "  ; // 경남에 창원도 포함된다.
           $AddedLoc .= ")" ;
           ?>
           <tr>
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>경남</b></td>
               <?
               // 스크린수 (경남)
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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

               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><b>합계</b></td>

               <?

               // 좌석수 (경남전체)
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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

               $TotSeat = $TotSeat + $SumSeatSeat ; // 총좌석수...
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $AddedCont .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $AddedCont .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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
                        $sDgree = "99" ; // 심야
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

                    if  ($i!=10)  // 예상인터네셔널
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
               <b>점유율</b>
               </td>

               <?
               // 총 점유율 ..
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
                   if  ($i!=10)  // 예상인터네셔널
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
                                                 경북 합계 찍기
                                                 (21)
                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?
      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // 회차별 스코어
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
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>경북</b></td>
               <?
               // 스크린수 (경북)
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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

               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><b>합계</b></td>

               <?

               // 좌석수 (경북전체)
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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

               $TotSeat = $TotSeat + $SumSeatSeat ; // 총좌석수...
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $AddedCont .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $AddedCont .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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
                        $sDgree = "99" ; // 심야
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

                    if  ($i!=10)  // 예상인터네셔널
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
               <b>점유율</b>
               </td>

               <?
               // 총 점유율 ..
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
                   if  ($i!=10)  // 예상인터네셔널
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
                                                 호남 합계 찍기
                                                 (50)
                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?
      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // 회차별 스코어
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
               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>호남</b></td>
               <?
               // 스크린수 (호남)
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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

               <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><b>합계</b></td>

               <?

               // 좌석수 (호남전체)
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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

               $TotSeat = $TotSeat + $SumSeatSeat ; // 총좌석수...
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $sQuery .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $sQuery .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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
               if  ($WorkGubun == 34) // 씨너스
               {
                   $AddedCont .= " And showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $AddedCont .= " And showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
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
                        $sDgree = "99" ; // 심야
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

                    if  ($i!=10)  // 예상인터네셔널
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
               <b>점유율</b>
               </td>

               <?
               // 총 점유율 ..
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
                   if  ($i!=10)  // 예상인터네셔널
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
                                                 지방 합계 찍기  // 예상인터네셔널

                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?
      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // 회차별 스코어
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
      $AddedLoc .= " and singo.Location <> '100' "  ; // 서울
      $AddedLoc .= " and singo.Location <> '200' "  ; // 부산
      $AddedLoc .= " and singo.Location <> '203' "  ; // 통영
      $AddedLoc .= " and singo.Location <> '600' "  ; // 울산
      $AddedLoc .= " and singo.Location <> '207' "  ; // 김해
      $AddedLoc .= " and singo.Location <> '205' "  ; // 진주
      $AddedLoc .= " and singo.Location <> '208' "  ; // 거제
      $AddedLoc .= " and singo.Location <> '202' "  ; // 마산
      $AddedLoc .= " and singo.Location <> '211' "  ; // 사천
      $AddedLoc .= " and singo.Location <> '212' "  ; // 거창
      $AddedLoc .= " and singo.Location <> '213' "  ; // 양산
      $AddedLoc .= " and singo.Location <> '201' "  ; // 창원
      $AddedLoc .= ")" ;

      $AddedLocMD .= " and Location <> '100' "  ; // 서울
      $AddedLocMD .= " and Location <> '200' "  ; // 부산
      $AddedLocMD .= " and Location <> '203' "  ; // 통영
      $AddedLocMD .= " and Location <> '600' "  ; // 울산
      $AddedLocMD .= " and Location <> '207' "  ; // 김해
      $AddedLocMD .= " and Location <> '205' "  ; // 진주
      $AddedLocMD .= " and Location <> '208' "  ; // 거제
      $AddedLocMD .= " and Location <> '202' "  ; // 마산
      $AddedLocMD .= " and Location <> '211' "  ; // 사천
      $AddedLocMD .= " and Location <> '212' "  ; // 거창
      $AddedLocMD .= " and Location <> '213' "  ; // 양산
      $AddedLocMD .= " and Location <> '201' "  ; // 창원
      $AddedLocMD .= ")" ;


      // 경기 + 서울 + 부산 을 제외한 나머지를 지방으로 한다.
      ?>
      <tr>
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>지방</b></td>
            <?
            // 스크린수 (지방)
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
            if  ($WorkGubun == 34) // 씨너스
            {
                $sQuery .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // 롯데씨네마
            {
                $sQuery .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // 메가박스
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
            if  ($WorkGubun == 34) // 씨너스
            {
                $sQuery .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // 롯데씨네마
            {
                $sQuery .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // 메가박스
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

            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><b>합계</b></td>

            <?

            // 좌석수 (지방전체)
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
            if  ($WorkGubun == 34) // 씨너스
            {
                $sQuery .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // 롯데씨네마
            {
                $sQuery .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // 메가박스
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

            $TotSeat = $TotSeat + $SumSeatSeat ; // 총좌석수...
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
            if  ($WorkGubun == 34) // 씨너스
            {
                $sQuery .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // 롯데씨네마
            {
                $sQuery .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // 메가박스
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
            if  ($WorkGubun == 34) // 씨너스
            {
                $AddedCont .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // 롯데씨네마
            {
                $AddedCont .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // 메가박스
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
                     $sDgree = "99" ; // 심야
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

                 if  ($i!=10)  // 예상인터네셔널
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
            <b>점유율</b>
            </td>

            <?
            // 총 점유율 ..
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
                if  ($i!=10)  // 예상인터네셔널
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
                                                 총 합계 찍기

                                                                                                                 -->
      <!----------------------------------------------------------------------------------------------------------->
      <?
      for ($i = 1 ; $i <= 11 ; $i++)
      {
          $arryDegree[$i] = 0 ;  // 회차별 스코어
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
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center rowspan=2><b>총합계</b></td>
            <?
            // 스크린수 (총합계)
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
            if  ($WorkGubun == 34) // 씨너스
            {
                $sQuery .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // 롯데씨네마
            {
                $sQuery .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // 메가박스
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
            if  ($WorkGubun == 34) // 씨너스
            {
                $sQuery .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // 롯데씨네마
            {
                $sQuery .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // 메가박스
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

            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><b>합계</b></td>

            <?

            // 좌석수 (총합계전체)
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
            if  ($WorkGubun == 34) // 씨너스
            {
                $sQuery .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // 롯데씨네마
            {
                $sQuery .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // 메가박스
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

            $TotSeat = $TotSeat + $SumSeatSeat ; // 총좌석수...
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
            if  ($WorkGubun == 34) // 씨너스
            {
                $sQuery .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // 롯데씨네마
            {
                $sQuery .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // 메가박스
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
            if  ($WorkGubun == 34) // 씨너스
            {
                $AddedCont .= " And showroom.MultiPlex  = '4' " ;
            }
            if  ($WorkGubun == 37) // 롯데씨네마
            {
                $AddedCont .= " And showroom.MultiPlex  = '5' " ;
            }
            if  ($WorkGubun == 39) // 메가박스
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
                     $sDgree = "99" ; // 심야
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

                 if  ($i!=10)  // 예상인터네셔널
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
            <b>점유율</b>
            </td>

            <?
            // 총 점유율 ..
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
                if  ($i!=10)  // 예상인터네셔널
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

