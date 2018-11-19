   <?
   $SubSumSeat = 0 ;
   $FirstZone  = 0 ;   
   
   $cntLocData = 0 ;

   for ($i=0 ; $i<=($dur_day+4) ; $i++)
   {
       $arrySoGea[$i]  = 0 ; // 지역이 끝날때 마다 초기화 한다.
   }

   while ($singo_data = mysql_fetch_array($qry_singo))
   {         
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
       $singoSilmooja = $singo_data["Silmooja"] ;

       
       $qrysilmooja = mysql_query("Select * From bas_silmooja         ".
                                  " Where Code = '".$singoSilmooja."' ",$connect) ;

       $silmooja_data = mysql_fetch_array($qrysilmooja) ;
       if  ($silmooja_data)
       {
           $silmoojaName     = $silmooja_data["Name"] ;
       }

       $qrysilmooja = mysql_query("Select * From bas_showroom             ".
                                  " Where Theather = '".$singoTheather."' ".
                                  "   And Room     = '".$singoRoom."'     ",$connect) ;

       $showroom_data = mysql_fetch_array($qrysilmooja) ;
       if  ($showroom_data)
       {
           $showroomDiscript = $showroom_data["Discript"] ;
           $showroomSeat     = $showroom_data["Seat"] ;
           $showroomLocation = $showroom_data["Location"] ;

           // 상영관의 소재지 지역을 구한다. ($locationName)
           $query1 = mysql_query("Select * From bas_location            ".
                                 " Where Code = '".$showroomLocation."' ",$connect) ;

           $location_data = mysql_fetch_array($query1) ;

           if  ($location_data)
           {
               $locationName = $location_data["Name"] ; // 지역명 ..
           }
       }
      ?>       
      
      
      <?
      $ExitTheather = false ;

      $sQuery = "Select * From bas_smsidchk        ".
                " Where Id = '".$spacial_UserId."' " ;
      $QrySmsIdChk = mysql_query($sQuery,$connect) ;         
      if  ($ArrSmsIdChk = mysql_fetch_array($QrySmsIdChk)) // 이부장.. 
      {  
          $TimJang = true ;
      }

      if  ($TimJang == true)
      {
          $sQuery = "Select Count(*) As cntSmschk           ".
                    "  From wrk_smschk                      ".
                    " Where Open     = '".$singoOpen."'     ".
                    "   And Film     = '".$singoFilm."'     ".
                    "   And Theather = '".$singoTheather."' " ;
          $qry_Smschk = mysql_query($sQuery,$connect) ;
          if  ($Smschk_data = mysql_fetch_array($qry_Smschk) )
          {
              if  ($Smschk_data["cntSmschk"]==0)
              {
                  $ExitTheather = true ;
              }
          }
      }

      if  ($ExitTheather == false)
      {
      
      // --------------------------------------------
      // 가격별 스코어 
      // --------------------------------------------

      $FirstPrice = true ;            
      
      $qry_singo1 = mysql_query("Select UnitPrice,                            ".
                                "       Sum(NumPersons) As AccSumNumPersons,  ".
                                "       Sum(TotAmount)  As AccSumNumTotAmount ".
                                "  From ".$sSingoName."                       ".
                                " Where SingoDate  <= '".$ToDate."'           ".
                                "   And Theather   = '".$singoTheather."'     ".
                                "   And Room       = '".$singoRoom."'         ".
                                "   And Open       = '".$singoOpen."'         ".
                                "   And Film       = '".$singoFilm."'         ".
                                " Group By UnitPrice                          ".
                                " Order By UnitPrice desc                     ",$connect) ;
      $affected_rows = (mysql_affected_rows()+1) ;
      
      if  ($affected_rows>1) // 도착보고가 온전히 된거
      {
          for ($i=0 ; $i<=($dur_day+4) ; $i++)
          {
              $arryHapGea[$i]  = 0 ; // 극장별 합계를 극장이 바뀔때 마다 초기화한다.
          }

          while ($singo1_data = mysql_fetch_array($qry_singo1))      
          {
              $UnitPrice          = $singo1_data["UnitPrice"] ;          // 요금대역
              $AccSumNumPersons   = $singo1_data["AccSumNumPersons"] ;   // 극장별 합계(스코어)
              $AccSumNumTotAmount = $singo1_data["AccSumNumTotAmount"] ; // 극장별 합계(금액)
              
              if  ($oldsingoTheather != $singoTheather)
              {
                  $clrToggle = !$clrToggle ;

                  $oldsingoTheather = $singoTheather ;
              }

              if  ($ToExel)
              {
                  $Color1 = "#ffffff" ;
                  $Color2 = "#ffffff" ;
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
              }
              ?>

              
              <tr>
              
              <?
              if  ($FirstPrice == true)
              {
                  $AccNumTotAmount = 0 ;
              ?>	
                  <!-- 지역명 출력 -->
                  <td class=textarea bgcolor=<?=$Color1?> rowspan=<?=$affected_rows?> width=70 class=tbltitle align=center>               
                  <?=$locationName?> 
                  </td>

                  <!-- 상영관명 출력 -->
                  <td class=textarea bgcolor=<?=$Color1?> rowspan=<?=$affected_rows?> class=tbltitle align=center>
                  <?=$showroomDiscript?> 
                  </td>

                  <?
                  $SubSumSeat += $showroomSeat ;
                  $SumSeat    += $showroomSeat ;
                  ?>
                  
                  <!-- 상영관 좌석수 출력 -->
                  <td class=textarea bgcolor=<?=$Color1?> rowspan=<?=$affected_rows?> class=tbltitle align=center>
                  <?=$showroomSeat?>&nbsp; 
                  </td> 

                  <?  
                  $FirstPrice = false ;
              }
              ?>

              <!-- 가격 출력 -->
              <td class=textarea bgcolor=<?=$Color1?> align=right>
              <?=number_format($UnitPrice)?>&nbsp;
              </td>

              

              <?
              $HapNumPersons = 0 ;
              $HapTotAmount = 0 ;

              $qry_singo2 = mysql_query("Select SingoDate,                         ".
                                        "       Sum(NumPersons) As SumNumPersons,  ".
                                        "       Sum(TotAmount)  As SumTotAmount    ".
                                        "  From ".$sSingoName."                    ".
                                        " Where SingoDate  >= '".$FromDate."'      ".
                                        "   And SingoDate  <= '".$ToDate."'        ".
                                        "   And Theather   = '".$singoTheather."'  ".
                                        "   And Room       = '".$singoRoom."'      ".
                                        "   And Open       = '".$singoOpen."'      ".
                                        "   And Film       = '".$singoFilm."'      ".
                                        "   And UnitPrice  = '".$UnitPrice."'      ".
                                        " Group By SingoDate                       ",$connect) ;   
              $NumPersons_data = mysql_fetch_array($qry_singo2) ;

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

                           ?>                       
                           <td class=textarea bgcolor=<?=$Color2?> align=right>
                           &nbsp;<?=number_format($NumPersons_data["SumNumPersons"])?>&nbsp;
                           </td>
                           <?
                           $NumPersons_data = mysql_fetch_array($qry_singo2) ;
                       }
                       else 
                       {
                           ?>
                           <td class=textarea bgcolor=<?=$Color2?> align=right>
                           &nbsp;0&nbsp;
                           </td>
                           <?
                       }                   
                   }
                   else
                   {
                       ?>
                       <td class=textarea bgcolor=<?=$Color2?> align=right>
                       &nbsp;0&nbsp;
                       </td>
                       <?
                   }
              }
              
              
              //
              //  극장별 총 합계 찍기
              //
              $arryHapGea[$dur_day+1] += $HapNumPersons ;
              $arrySoGea[$dur_day+1]  += $HapNumPersons ;
              
              $arryHapGea[$dur_day+2] += $HapTotAmount ;
              $arrySoGea[$dur_day+2]  += $HapTotAmount ;
              
              ?>

              <td class=textarea bgcolor=<?=$Color2?> align=right>
              &nbsp;<?=number_format($HapNumPersons)?>&nbsp;
              </td>

              <?
              if  ($spacial_UserId != "7070") 
              {
              ?>
              <td class=textarea bgcolor=<?=$Color2?> align=right>
              &nbsp;<?=number_format($HapTotAmount)?>&nbsp;
              </td>
              <?
              }
              ?>

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


              <?
              
              //
              //  극장별 금액 찍기
              //
              $AccNumTotAmount += $AccSumNumTotAmount ;
                                               
              $arryHapGea[$dur_day+4] += $AccSumNumTotAmount ;
              $arrySoGea[$dur_day+4]  += $AccSumNumTotAmount ;

              if  ($spacial_UserId != "7070") 
              {
              ?>
              <td class=textarea bgcolor=<?=$Color1?> align=right>
              &nbsp;<?=number_format($AccSumNumTotAmount)?>&nbsp;
              </td>
              <?
              }
              ?>
              </tr>

          <?    
          }
          ?>


          <tr>
      <?
      }
      else
      {
           if  ($ToExel)
           {
               $Color1 = "#ffffff" ;
               $Color2 = "#ffffff" ;
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
             $SubSumSeat += $showroomSeat ;
             $SumSeat    += $showroomSeat ;
             ?>
                  
             <!-- 상영관 좌석수 출력 -->
             <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=center>
             <?=$showroomSeat?>&nbsp; 
             </td>       
             <?
      }      
      ?>
      
             <td class=textarea bgcolor=<?=$Color1?> align=center>
             <b>합계</b>
             </td>
         
             <?
             // 상영관별 소개를 구한다.
             for ($i=0 ; $i<=($dur_day+4) ; $i++)
             {   
                 if  (($spacial_UserId != "7070") || (($i!=($dur_day+4) && ($i!=($dur_day+2))))) 
                 {
             ?>
                 
                 <td class=textarea bgcolor=<?=$Color1?> align=right>
                 &nbsp;<b><?=number_format($arryHapGea[$i])?></b>&nbsp;
                 </td>
             <?  
                 }
             }          
             ?>

      </tr>

      <?
      $cntLocData ++ ;

      $FirstZone ++  ;
      }
   }


   
   
   
   
   // --------------------------------------------
   // 소계
   // --------------------------------------------
   if  ($spacial_UserId != "7070") 
   {
   if  ($cntLocData > 0) // 한번이라도 데이터가 나온경우
   {
   ?> 
       <tr>
           <td class=textarea bgcolor=#b0c4de align=center colspan=2>
           소계
           </td>
           
           <td class=textarea bgcolor=#b0c4de align=right>
           &nbsp;<b><?=number_format($SubSumSeat)?></b>&nbsp;
           </td>

           <td class=textarea bgcolor=#b0c4de align=right>
           &nbsp;
           </td>

           <? 
           for ($i=0 ; $i<=($dur_day+4) ; $i++)
           {
               ?>
               <td class=textarea bgcolor=#b0c4de align=right>
               &nbsp;<b><?=number_format($arrySoGea[$i])?></b>&nbsp;
               </td>
               <?
           } 
           ?>
       </tr>
   <?
   }
   }
   ?>
   