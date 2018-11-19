   
   <?   
   for ($i = 1 ; $i <= 15 ; $i++)
   {
       $arryTotSumOfDegree[$i] = 0 ;  // 회차별 스코어 합계
   }

   $PutAct = False ;

   while ($singo_data = mysql_fetch_array($qry_singo))
   {
        $PutAct = True ; // 한건의 자료라도 있다면...  합계를 찍는다..
        
        for ($i = 1 ; $i <= 12 ; $i++)
        {
            $arrySumOfDegree[$i] = 0 ;  // 회차별 스코어 합계
        }

        $singoSilmooja    = $singo_data["Silmooja"] ;      // 신고실무자
        $singoTheather    = $singo_data["Theather"] ;      // 신고상영관
        $singoRoom        = $singo_data["Room"] ;          //
        $singoOpen        = $singo_data["Open"] ;          // 신고영화
        $singoFilm        = $singo_data["Film"] ;          //
        $silmoojaName     = $singo_data["SilmoojaName"] ;  // 신고 실무자명  
        $showroomDiscript = $singo_data["Discript"] ;      // 신고 상영관명  
        $showroomLocation = $singo_data["Location"] ;      // 신고 상영관지역
        $locationName     = $singo_data["LocationName"] ;  // 신고 상영관지역명
        $showroomSeat     = $singo_data["ShowRoomSeat"] ;  // 신고 상영관좌석            
        $SumNumPersons    = $singo_data["SumNumPersons"] ; // 총 스코어
        $showroomCntDgree = $singo_data["CntDgree"] ;      // 상영회차수
        $cntRoom          = $singo_data["cntRoom"] ;       //

        
        // 상영관 행 늘림수를 구한다..
        $nTotRow = 0 ;
        
        $sQuery = "Select distinct(Room) From ".$SingoName." ".
                  " Where SingoDate = '".$WorkDate."'        ".
                  "   And Theather  = '".$singoTheather."'   " ;
        $qry_Rooms = mysql_query($sQuery,$connect) ;
        while ($Rooms_data = mysql_fetch_array($qry_Rooms))
        {              
             $RoomNo = $Rooms_data["Room"] ;

             $sQuery = "Select count(distinct(UnitPrice)) As CntUP  ".
                       "  From ".$SingoName."                       ".
                       " Where SingoDate  = '".$WorkDate."'         ".
                       "   And Room       = '".$RoomNo."'           ".
                       "   And Theather   = '".$singoTheather."'    " ;
             $qry_CntUP = mysql_query($sQuery,$connect) ;
             if  ($CntUP_data = mysql_fetch_array($qry_CntUP))
             {
                 $nTotRow = $nTotRow + $CntUP_data["CntUP"] ;
                 $nTotRow = $nTotRow + 1 ; /////////////////////////////////////////
             }             
        }
        if  ($nTotRow == 0)
        {
            $nTotRow = 1 ;
        }



        // 영화 제목을 구하되 영화가 바뀌는 순간에만 저장하고
        // 두번이상 반복되면 영화명을 지운다.
        if  ($filmtitleNameTitle != $singo_data["FilmTitleName"])
        {
            $filmtitleName      = $singo_data["FilmTitleName"] ;
            $filmtitleNameTitle = $singo_data["FilmTitleName"] ;
        }
        else
        {
            $filmtitleName = "" ;
        }



        if  ($oldsingoTheather != $singoTheather)
        {
            $clrToggle = !$clrToggle ;

            $oldsingoTheather = $singoTheather ;
        }

        if  ($ToExel)// && ($filmsupplyCode=="20003")  // 예상인터네셔널 
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
                $Color1 = "#ffccff" ;
                $Color2 = "#f1ebed" ;
                $Color3 = "#ffffff" ;
                $Color4 = "#66ccfe" ;
            }
            else
            {
                $Color1 = "#ffccff" ;
                $Color2 = "#f1ebed" ;
                $Color3 = "#ffffff" ;
                $Color4 = "#66ccfe" ;
            }
        }


        // 영화제목 출력 (변화되는 시점에만,)..
        if  ($PutTitle==True)
        {               
            ?>
            <table name=score cellpadding=0 cellspacing=0 border=1 bordercolor="#FFFFFF" width=100%>
            <tr>
            
            <td align=left class=textare>
            <?         
            $WorkTime = mktime(0,0,0,substr($WorkDate,4,2),substr($WorkDate,6,2),substr($WorkDate,0,4));
            $OpenTime = mktime(0,0,0,substr($singoOpen,2,2),substr($singoOpen,4,2),"20".substr($singoOpen,0,2));
            $dur_day  = ($WorkTime - $OpenTime) / 86400  ;
            ?>           
            <font size=2>
            <B>상영작:<?=$filmtitleName?></B>
            기준일자:<?=substr($WorkDate,2,2)."년 ".substr($WorkDate,4,2)."월 ".substr($WorkDate,6,2)."일 "?>
            (개봉 <?=substr($singoOpen,0,2)."년 ".substr($singoOpen,2,2)."월 ".substr($singoOpen,4,2)."일 "?>)
            (상영 <?=($dur_day+1)?>일째)
            </font>
            </td>
            
            </tr>
            </table>            


            <br>        
            <table style='table-layout:fixed' name=score cellpadding=0 cellspacing=0 border=1 bordercolor=<?=$Color4?> style="border-collapse:collapse">
            <tr height=50>
                  <!--             -->
                  <!-- 타이틀 찍기 -->
                  <!--             -->
                  <td class=textarea width=260 colspan=2 bgcolor=<?=$Color4?> align=center>
                  <b>SCREEN</b>
                  </td>

                  <td class=textarea width=55 bgcolor=<?=$Color4?> align=center>
                  <b>요금</b>
                  </td>

                  <td class=textarea width=55 bgcolor=<?=$Color4?> align=center>
                  <b>1회</b>
                  </td>

                  <td class=textarea width=45 bgcolor=<?=$Color4?> align=center>
                  <b>2회</b>
                  </td>

                  <td class=textarea width=50 bgcolor=<?=$Color4?> align=center>
                  <b>3회</b>
                  </td>

                  <td class=textarea width=50 bgcolor=<?=$Color4?> align=center>
                  <b>4회</b>
                  </td>

                  <td class=textarea width=50 bgcolor=<?=$Color4?> align=center>
                  <b>5회</b>
                  </td>

                  <td class=textarea width=50 bgcolor=<?=$Color4?> align=center>
                  <b>6회</b>
                  </td>

                  <td class=textarea width=45 bgcolor=<?=$Color4?> align=center>
                  <b>7회</b>
                  </td>

                  <td class=textarea width=45 bgcolor=<?=$Color4?> align=center>
                  <b>8회</b>
                  </td>

                  <td class=textarea width=45 bgcolor=<?=$Color4?> align=center>
                  <b>9회</b>
                  </td>

                  <td class=textarea width=45 bgcolor=<?=$Color4?> align=center>
                  <b>10회</b>
                  </td>

                  <td class=textarea width=45 bgcolor=<?=$Color4?> align=center>
                  <b>심야</b>
                  </td>

                  <!-- 당일 합계 -->
                  <td class=textarea width=60 bgcolor=<?=$Color4?> align=center>
                  <b>&nbsp;당일&nbsp;<br>&nbsp;합계&nbsp;</b>
                  </td>
                  
                  <!-- 전일 합계 -->
                  <td class=textarea width=60 bgcolor=<?=$Color4?> align=center>
                  <b>&nbsp;전일&nbsp;<br>&nbsp;합계&nbsp;</b>
                  </td>
                  
                  <!-- 누계 -->
                  <td class=textarea width=70 bgcolor=<?=$Color4?> align=center>
                  <b>&nbsp;누계&nbsp;</b>
                  </td>                  
            </tr>
            <?
            $PutTitle=False ;
        }          
        
        $SumOfPsToday  = 0 ; // 당일 합계 합계

        $isFinishBlock = false ;

        // 오늘의 관대역을 구한다.
        $sQuery = "Select distinct Room, UnitPrice            ".
                  "  From ".$SingoName."                      ".
                  " Where SingoDate  = '".$WorkDate."'        ".
                  "   And Theather   = '".$singoTheather."'   ".
                  "   And Open       = '".$singoOpen."'       ".
                  "   And Film       = '".$singoFilm."'       ".
                  " Order By Room, UnitPrice Desc             " ;
        $qry_singo3 = mysql_query($sQuery,$connect) ;
        $bFirstLeftTitle = True ;
        
        while  ($UnitPrice_data = mysql_fetch_array($qry_singo3))
        {
             $SumOfToday = 0 ;

             $singoRoom  = $UnitPrice_data["Room"] ; 
             $singoPrice = $UnitPrice_data["UnitPrice"] ; 

             $sQuery = "Select Seat  From bas_seat                        ".
                       " Where Theather = '".$singoTheather."'            ".
                       "   And Room     = '".sprintf("%d",$singoRoom)."'  " ;
             $qry_showroom = mysql_query($sQuery,$connect) ;

             if  ($showroom_data = mysql_fetch_array($qry_showroom))
             {
                  $showroomSeat = $showroom_data["Seat"] ;
             }
             else
             {
                  $showroomSeat = 0 ;
             }
             ?>

             
             
             <!--             -->
             <!-- 데이타 찍기 -->
             <!--             -->
             <tr> 
                  <?
                  if  ($bFirstLeftTitle == True) 
                  {                           
                      ?>             
                      <!-- 상영관정보 상영관명,좌석수,점유율 -->
                      <td class=textarea bgcolor=<?=$Color2?> valign=center rowspan=<?=$nTotRow + 1?>>
                          <font color=#f444e1><B>&nbsp;<?=$showroomDiscript?></B></font> <!-- 상영관명(<?=$singoTheather?>) -->
                          <?
                          if  ($logged_UserId == "been")
                           {
                              echo "<br>".$singoTheather;
                           }
                          ?>
                      </td>
                      <?
                      

                      $singoOldRoom   = "" ;
                  } 

                  if  ($singoOldRoom !=  $singoRoom) // 상영관 관번호 ..
                  {
                      $bFirstLeftRoom = True ;
                      $singoOldRoom   = $singoRoom ;
                  }
                  else
                  {
                      $bFirstLeftRoom = False ;
                  }

                  if  ($bFirstLeftRoom == True) 
                  {
                      $CntUP = 1 ;
                      $sQuery = "Select count(distinct(UnitPrice)) As CntUP   ".
                                "  From ".$SingoName."                        ".
                                " Where SingoDate = '".$WorkDate."'           ".
                                "   And Theather = '".$singoTheather."'       ".
                                "   And Room     = '".$singoRoom."'           " ;
                      $qry_CntUP = mysql_query($sQuery,$connect) ;
                      if  ($CntUP_data = mysql_fetch_array($qry_CntUP))
                      {
                          $CntUP = $CntUP_data["CntUP"] ;
                          $CntUP = $CntUP + 1 ;
                      }                      
                      ?>
                      
                      <td class=textarea bgcolor=<?=$Color3?> rowspan=<?=$CntUP?> class=tblsum align=center>
                      <?
                      if  ($logged_UserId == "been")
                      {
                      ?>
                          <?=number_format($singoRoom)?>관 <B><?=$showroomSeat?>석</B> <br><br>
                          <a href="#" onclick="delect_click('<?=$singoSilmooja?>','<?=$singoTheather.$singoRoom?>','<?=$FilmTile?>');">[삭제]</a>
                          <?=$silmoojaName?>
                          <?
                          if  ($singoSilmooja=="666666") 
                          {
                              ?>
                              <a href="#" onclick="modify_click('<?=$singoSilmooja?>','<?=$singoTheather.$singoRoom?>','<?=$FilmTile?>');">[수정]</a>
                              <?
                          }
                          ?>
                      <?
                      }
                      else
                      {
                      ?>                 
                          <?=number_format($singoRoom)?>관 <br>
                          <B><?=$showroomSeat?>석</B> 
                      <?
                      }
                      ?>
                      </td>

                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      
                      <?                      
                      $bFirstLeftRoom = False ;                      
                  }
                  ?>

                  <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center>
                  <B><?=number_format($singoPrice)?></B>
                  </td>

                  <?
                  $sQuery = "Select * From bas_theatherfinish        ".
                            " Where Theather = '".$singoTheather."'  ".
                            "   And Open     = '".$singoOpen."'      ".
                            "   And Film     = '".$singoFilm."'      ".
                            "   And WorkDate < '".$WorkDate."'       " ;
                  $qry_Theatherfinish  = mysql_query($sQuery,$connect) ; 
             
                  if  ($Theatherfinish_data = mysql_fetch_array($qry_Theatherfinish))
                  {
                      $isFinished = true ;

                      $FinishDate = $Theatherfinish_data["WorkDate"] ;
                  }
                  else
                  {
                      $isFinished = false ;
                  }

                  if  ($isFinished == true)
                  {                 
                      if  ($isFinishBlock == false)
                      {                      
                          $cntUnitPriceP1 = $CntUP+1 ;

                          ?>
                          <td class=textarea bgcolor=<?=$Color3?> colspan=11 rowspan=<?=$cntUnitPriceP1?> align=center valign=middle>
                          <font color=red><B>종영(<?=substr($FinishDate,2,2)?>/<?=substr($FinishDate,4,2)?>/<?=substr($FinishDate,6,2)?>)처리됨</B></font>
                          </td>

                          <?
                      }
                      $isFinishBlock = true ;
                  }
                  else
                  {     
                      $sQuery = "Select ShowDgree,                           ".
                                "       SUM(NumPersons) As SumOfNumPersons   ".
                                "  From ".$SingoName."                       ".
                                " Where SingoDate  = '".$WorkDate."'         ".
                                "   And Silmooja   = '".$singoSilmooja."'    ".
                                "   And Theather   = '".$singoTheather."'    ".
                                "   And Room       = '".$singoRoom."'        ".
                                "   And Open       = '".$singoOpen."'        ".
                                "   And Film       = '".$singoFilm."'        ".
                                "   And UnitPrice  = '".$singoPrice."'       ".
                                " Group By ShowDgree                         ".
                                " Order By ShowDgree                         " ;
                      $qry_singo2 = mysql_query($sQuery,$connect) ;
                     
                      $agree = true ;
                      for ($i = 1 ; $i <= 11 ; $i++)
                      {
                          if  ($agree==true) // 일치하는 자료가 있을 경우 만 한 레코드씩 읽는다.
                          {
                              $NumPersons_data = mysql_fetch_array($qry_singo2) ;
                          }

                          if  ($i<11) // 1회 부터 10회 까지..
                          {
                              if  ($NumPersons_data["ShowDgree"] == sprintf("%02d",$i)) 
                              {
                                  $SumOfDegree = "SumOf".sprintf("%02d",$i)."Degree" ;

                                  $arrySumOfDegree[$i] += $NumPersons_data["SumOfNumPersons"] ; // 회차별 스코어 합계
                                  
                                  $SumOfToday   += $NumPersons_data["SumOfNumPersons"] ;
                                  $$SumOfDegree += $NumPersons_data["SumOfNumPersons"] ;
                                  ?>

                                  <td class=textarea bgcolor=<?=$Color3?> align=right>
                                  <?=$NBSP?><?=$NumPersons_data["SumOfNumPersons"]?><?=$NBSP?>                             
                                  </td>
                                  
                                  <?
                                  
                                  $agree = true ;
                              }
                              else
                              {
                                  ?>
                                  <td class=textarea bgcolor=<?=$Color3?> align=center>&nbsp;</td>
                                  <?

                                  $agree = false ;
                              }
                          }
                          else // 심야
                          {
                              if  ($NumPersons_data["ShowDgree"] == "99") 
                              {
                                  $arrySumOfDegree[$i] += $NumPersons_data["SumOfNumPersons"] ; // 회차별 스코어 합계
                                  
                                  $SumOfToday    += $NumPersons_data["SumOfNumPersons"] ;
                                  ?>
                                  
                                  <td class=textarea bgcolor=<?=$Color3?> align=right>
                                  <?=$NBSP?><?=$NumPersons_data["SumOfNumPersons"]?><?=$NBSP?>
                                  </td>

                                  <?
                                  
                                  $agree = true ;
                              }
                              else
                              {
                                  ?>
                                  <td class=textarea bgcolor=<?=$Color3?> align=center>&nbsp;</td>
                                  <?
                                  
                                  $agree = false ;
                              }
                          }
                      }
                  }
                                                 
                  // 당일합계 출력
                  $SumOfPsToday += $SumOfToday ; // 당일 합계 합계

                  ?>
                  <td class=textarea bgcolor=<?=$Color3?> align=right>
                  <b><?=$NBSP?><?=number_format($SumOfToday)?><?=$NBSP?></b>
                  </td>
                  <?

                  $AgoModifyScore = 0 ;
                  
                  // 전일합계
                  if   ($singoRoom=='0')
                  {
                      $CondRoom = "" ;
                  }
                  else
                  {
                      $CondRoom = " And Room = '".$singoRoom."' " ;
                  }

                  $sQuery = "Select Sum(NumPersons) As SumNumPersons  ".
                            "  From ".$SingoName."                    ".
                            " Where SingoDate  = '".$AgoDate."'       ".
                            "   And Theather   = '".$singoTheather."' ".
                            $CondRoom                                  .
                            "   And Open       = '".$singoOpen."'     ".
                            "   And Film       = '".$singoFilm."'     " ;
                  $qry_singo2 = mysql_query($sQuery,$connect) ;

                  $NumPersons_data = mysql_fetch_array($qry_singo2) ;
                  if  ($NumPersons_data)
                  {
                      //$SumOfPsAgoDay += ($NumPersons_data["SumNumPersons"]+$AgoModifyScore) ; // 전일 합계 합계

                      // 전일합계 출력
                      ?>
                      <td class=textarea bgcolor=<?=$Color3?> align=right>
                      <b><?=$NBSP?><?=number_format($NumPersons_data["SumNumPersons"]+$AgoModifyScore)?><?=$NBSP?></b>
                      </td>
                      <?
                  }
                  else
                  {
                      // 전일합계 출력
                      ?>
                      <td class=textarea bgcolor=<?=$Color3?> align=center>&nbsp;</td>
                      <?
                  }


                  $FilmTileOpen = substr($FilmTile,0,6) ;
                  $FilmTileFilm = substr($FilmTile,6,2) ;

                  if   ($FilmTileFilm=='00')
                  {
                       $CondOpenFilm = " And Open = '".$FilmTileOpen."' " ;
                  }
                  else
                  {
                       $CondOpenFilm = " And Open = '".$FilmTileOpen."' " .
                                       " And Film = '".$FilmTileFilm."' " ;
                  }

               
                  // 당일누계
                  $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                            "  From ".$SingoName."                     ".
                            " Where SingoDate  <= '".$WorkDate."'      ".
                            "   And Theather   = '".$singoTheather."'  ".
                            $CondRoom                                   .
                            "   And Open       = '".$singoOpen."'      ".
                            "   And Film       = '".$singoFilm."'      ".
                            "   And UnitPrice  = '".$singoPrice."'     " ;
                  $qry_singo2 = mysql_query($sQuery,$connect) ;
                  $NumPersons_data = mysql_fetch_array($qry_singo2) ;
                  if  ($NumPersons_data)
                  {
                      ?>
                      <td class=textarea bgcolor=<?=$Color3?> align=right>
                      <b><?=$NBSP?><font color="#0000ff"><?=number_format($NumPersons_data["SumNumPersons"])?></font><?=$NBSP?></b>
                      </td>                      
                      <?
                  }
                  else
                  {
                      ?>
                      <td class=textarea bgcolor=<?=$Color3?> align=center>&nbsp;</td>
                      <?
                  }
                  ?>
             </tr>
             
             <?              
              $bFirstLeftTitle = False ;        
        }
        ?>


        
        <!--             -->
        <!--  합 계 찍기 -->
        <!--             -->
        <tr height=22>          
             <!-- 합계 -->
             <td class=textarea bgcolor=<?=$Color2?> class=tblsum align=center>
             합계
             </td>

             <td class=textarea bgcolor=<?=$Color2?> class=tblsum align=center>             
             </td>

             <?
             if  ($isFinished == false)
             {
                 // 회차별 스코어 합계
                 for ($i = 1 ; $i <= 11 ; $i++)
                 {
                    ?>
                    <td class=textarea bgcolor=<?=$Color2?> align=right>
                    <?=$NBSP?><?=number_format($arrySumOfDegree[$i])?><?=$NBSP?>
                    </td>
                    <?
                    $arryTotSumOfDegree[$i] += $arrySumOfDegree[$i] ;
                 }
             }
             ?>

             <!-- 당일 합계 합계 -->
             <td class=textarea bgcolor=<?=$Color2?> align=right>
             <b><?=$NBSP?><?=number_format($SumOfPsToday)?><?=$NBSP?></b>
             <?
             $arryTotSumOfDegree[12] += $SumOfPsToday ;
             ?>
             </td>              
             
             <!-- 전일 합계 합계 -->
             <?
             // 전일합계
             $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                       "  From ".$SingoName."                     ".
                       " Where SingoDate  = '".$AgoDate."'        ".
                       "   And Theather   = '".$singoTheather."'  ".
                       "   And Open       = '".$singoOpen."'      ".
                       "   And Film       = '".$singoFilm."'      " ;
             $qry_singo2 = mysql_query($sQuery,$connect) ;
             $NumPersons_data = mysql_fetch_array($qry_singo2) ;
             if  ($NumPersons_data)
             {
                 ?>
                 <td class=textarea bgcolor=<?=$Color2?> align=right>
                 <b><?=$NBSP?><?=number_format($NumPersons_data["SumNumPersons"])?><?=$NBSP?></b>
                 <?
                 $arryTotSumOfDegree[13] += $NumPersons_data["SumNumPersons"] ;
                 ?>
                 </td>        
                 <?
             }
             ?>
             
             <?
             // 당일누계
             $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                       "  From ".$SingoName."                     ".
                       " Where SingoDate  <= '".$WorkDate."'      ".
                       "   And Theather   = '".$singoTheather."'  ".
                       "   And Open       = '".$singoOpen."'      ".
                       "   And Film       = '".$singoFilm."'      " ;
             $qry_singo2 = mysql_query($sQuery,$connect) ;
             $NumPersons_data = mysql_fetch_array($qry_singo2) ;
             if  ($NumPersons_data)
             {
                 ?>                                                                      
                 <td class=textarea bgcolor=<?=$Color2?> align=right>
                 <b><?=$NBSP?><font color="#0000ff"><?=number_format($NumPersons_data["SumNumPersons"])?><?=$NBSP?></font></b>
                 </td>                      
                 <?
                 $arryTotSumOfDegree[14] += $NumPersons_data["SumNumPersons"] ;
             }
             ?>
        </tr>
   <?
   } 
   
   if   ($PutAct==True)
   {
   ?>
        <tr height=22>
              <!--             -->
              <!--  합 계 찍기 -->
              <!--             -->

              <td class=textarea colspan=2 bgcolor=<?=$Color1?> align=center>
              <b>합계</b>
              </td>

              <td class=textarea bgcolor=<?=$Color1?> class=tblsum align=center>
              </td>

              <?
              // 회차별 스코어 합계
              for ($i = 1 ; $i <= 12 ; $i++)
              {
                 ?>
                 <td class=textarea bgcolor=<?=$Color1?> align=right>
                 <b><?=$NBSP?><?=number_format($arryTotSumOfDegree[$i])?><?=$NBSP?></b>
                 </td>
                 <?
                  $arryTotTotSumOfDegree[$i] += $arryTotSumOfDegree[$i] ;
              }

              
              ?>

              <?
              $qry_singo2 = mysql_query("Select Sum(NumPersons) As SumNumPersons  ".
                                        "  From ".$SingoName." As Singo           ".
                                        " Where Singo.SingoDate  = '".$AgoDate."' ".
                                        $AddedCont                                ,$connect) ;
              $NumPersons_data = mysql_fetch_array($qry_singo2) ;
              if  ($NumPersons_data)
              {
                  ?>
                  <td class=textarea bgcolor=<?=$Color1?> align=right>
                  <b><?=number_format($NumPersons_data["SumNumPersons"])?><?=$NBSP?></b>
                  </td>
                  <?
                  $arryTotTotSumOfDegree[13] += $NumPersons_data["SumNumPersons"] ;
              }
              ?>

              <?
              $qry_singo2 = mysql_query("Select Sum(NumPersons) As SumNumPersons    ".
                                        "  From ".$SingoName." As Singo             ".
                                        " Where Singo.SingoDate  <= '".$WorkDate."' ".
                                        $AddedCont                                   ,$connect) ;
              $NumPersons_data = mysql_fetch_array($qry_singo2) ;
              if  ($NumPersons_data)
              {
                  ?>
                  <td class=textarea bgcolor=<?=$Color1?> align=right>
                  <b><font color="#0000ff"><?=number_format($NumPersons_data["SumNumPersons"])?></font><?=$NBSP?></b>
                  </td>
                  <?
                  $arryTotTotSumOfDegree[14] += $NumPersons_data["SumNumPersons"] ;
              }
              ?>
        </tr>
   <?
   }      
   ?>

   <?
   if   ($PutTail==True)
   { 
        if   ($PutTotal==True)
        {
        ?>
        <tr  height=25>
              <!--             -->
              <!--  합 계 찍기 -->
              <!--             -->

              <td class=textarea colspan=2 bgcolor=<?=$Color2?> align=center>              
              <b>총 합계</b>
              </td>

              <td class=textarea bgcolor=<?=$Color1?> class=tblsum align=center>
              </td>

              <?
              // 회차별 스코어 합계
              for ($i = 1 ; $i <= 13 ; $i++)
              {
                 ?>
                 <td class=textarea bgcolor=<?=$Color2?> align=right>
                 <b><?=$NBSP?><?=number_format($arryTotTotSumOfDegree[$i])?><?=$NBSP?></b>
                 </td>               
                 <?
              }
              ?>
              
              <?
              if  ($ZoneCode=="9")
              {
                   $AddedCont = "" ;
              }
              else
              {
                   $AddedCont = " And  Singo.Location = '".$LocationCode."' " ;
              }

              if   ($FilmTileFilm == '00') // 분리된영화의통합코드
              {
                   $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' " ;
              }
              else
              {
                   $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' ".
                                 " And Singo.Film = '".$FilmTileFilm."' " ;
              }
              $sQuery = "Select Sum(NumPersons) As SumNumPersons    ".
                        "  From ".$SingoName." As Singo             ".
                        " Where Singo.SingoDate  <= '".$WorkDate."' ".
                        $AddedCont                                   ;
              $qry_singo2 = mysql_query($sQuery,$connect) ;
              $NumPersons_data = mysql_fetch_array($qry_singo2) ;
              if  ($NumPersons_data)
              {
              //$arryTotTotSumOfDegree[14]
              ?>
              <td class=textarea bgcolor=<?=$Color2?> align=right>
              <b><?=$NBSP?><font color="#0000ff"><?=number_format($NumPersons_data["SumNumPersons"])?></font><?=$NBSP?></b>
              </td>               
              <?
              }
              ?>
        </tr>
        <?
        } 
        ?>
      </table>
      <br>
   <?
   }
   ?>