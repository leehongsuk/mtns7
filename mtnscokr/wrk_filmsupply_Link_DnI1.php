   <?
   $SubSumScreen = 0 ;
   $SubSumSeat   = 0 ;

   $FirstZone  = 0 ;   
   
   $cntLocData = 0 ;

   for ($i=0 ; $i<=($dur_day+4) ; $i++)
   {
       $arrySoGea[$i]  = 0 ; // ������ ������ ���� �ʱ�ȭ �Ѵ�.
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
                                                 
       $qrysilmooja = mysql_query("Select * From bas_theather         ".
                                  " Where Code = '".$singoTheather."' ",$connect) ;

       $showroom_data = mysql_fetch_array($qrysilmooja) ;
       if  ($showroom_data)
       {
           $showroomDiscript = $showroom_data["Discript"] ;
           $showroomLocation = $showroom_data["Location"] ;

           // �󿵰��� ������ ������ ���Ѵ�. ($locationName)
           $query1 = mysql_query("Select * From bas_location            ".
                                 " Where Code = '".$showroomLocation."' ",$connect) ;

           $location_data = mysql_fetch_array($query1) ;

           if  ($location_data)
           {
               $locationName = $location_data["Name"] ; // ������ ..
           }
       }
                                                 
       $qrysilmooja = mysql_query("Select Sum(Seat) As SumOfSeat          ".
                                  "  From bas_showroom                    ".
                                  " Where Theather = '".$singoTheather."' ",$connect) ;

       $showroom_data = mysql_fetch_array($qrysilmooja) ;
       if  ($showroom_data)
       {
           $showroomSeat     = $showroom_data["SumOfSeat"] ;
       }
      ?>       
      
      
      <?
      // --------------------------------------------
      // ���ݺ� ���ھ� 
      // --------------------------------------------

      $nNumRoom = 0 ; // ��ũ����
      
      $qry_singo1 = mysql_query("Select Sum(NumPersons) As AccSumNumPersons   ".
                                "  From ".$sSingoName."                       ".
                                " Where SingoDate  <= '".$ToDate."'           ".
                                "   And Theather   = '".$singoTheather."'     ".
                                "   And Open       = '".$singoOpen."'         ".
                                "   And Film       = '".$singoFilm."'         ",$connect) ;

      $affected_rows = (mysql_affected_rows()) ;

      if  ($affected_rows>0) // �������� ������ �Ȱ�
      {
          for ($i=0 ; $i<=($dur_day+4) ; $i++)
          {
              $arryHapGea[$i]  = 0 ; // ���庰 �հ踦 ������ �ٲ� ���� �ʱ�ȭ�Ѵ�.
          }

          while ($singo1_data = mysql_fetch_array($qry_singo1))      
          {
              $UnitPrice          = $singo1_data["UnitPrice"] ;          // ��ݴ뿪
              $AccSumNumPersons   = $singo1_data["AccSumNumPersons"] ;   // ���庰 �հ�(���ھ�)
              
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
              $AccNumTotAmount = 0 ;
              ?>	
              
              <!-- ������ ��� -->
              <td class=textarea bgcolor=<?=$Color1?> width=70 class=tbltitle align=center>               
              <?=$locationName?> 
              </td>

              <!-- �󿵰��� ��� -->
              <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=center>
              <?=$showroomDiscript?> 
              </td>

              
              <?      
              $SumSeat  += $showroomSeat ;

              $qry_room = mysql_query("Select distinct                                    ".
                                      "       Singo.Theather,                             ".
                                      "       Singo.Room,                                 ".
                                      "       Singo.Open,                                 ".
                                      "       Singo.Film,                                 ".
                                      "       Showroom.Seat                               ".
                                      "From ".$sSingoName." As Singo,                     ".
                                      "     bas_showroom As Showroom                      ".
                                      " Where Singo.SingoDate  = '".$ToDate."'            ".
                                      "   And Singo.Theather   = '".$singoTheather."'     ".
                                      "   And Singo.Theather   = Showroom.Theather        ".
                                      "   And Singo.Room       = Showroom.Room            ".
                                      "   And Singo.Open       = '".$singoOpen ."'        ".
                                      "   And Singo.Film       = '".$singoFilm."'         ".
                                      $AddedCont                                           .
                                      "Group By Singo.Theather,                           ".
                                      "         Singo.Open,                               ".
                                      "         Singo.Film,                               ".
                                      "         Showroom.Seat                             ".
                                      "Order By Singo.Theather,                           ".
                                      "         Singo.Room,                               ".
                                      "         Singo.Open,                               ".
                                      "         Singo.Film,                               ".
                                      "         Showroom.Discript                         ",$connect) ;   
              
              $roomSeat = 0 ;
              
              //$nNumRoom = mysql_num_rows($qry_room) ;
              while ($room_data = mysql_fetch_array($qry_room))
              {
                  $roomSeat = $roomSeat + $room_data["Seat"] ;
                  $nNumRoom = $nNumRoom + 1 ;
              }        
              $SubSumScreen += $nNumRoom ;
              $SubSumSeat   += $roomSeat ;

              $TotSumScreen += $nNumRoom ;
              $TotSumSeat   += $roomSeat ;
              ?>

              <!-- �󿵰� ��ũ���� ��� -->
              <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=center>
              <?=$nNumRoom?>
              </td> 
              
              <!-- �󿵰� �¼��� ��� -->
              <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=center>
              <?=number_format($roomSeat)?>
              </td> 
         
              <!-- �󿵰� ������ ��� -->
              <?
              $qry_singo2 = mysql_query("Select SingoDate                          ".
                                        "  From ".$sSingoName."                    ".
                                        " Where Theather   = '".$singoTheather."'  ".
                                        "   And Open       = '".$singoOpen."'      ".
                                        "   And Film       = '".$singoFilm."'      ".
                                        " Order By Singodate                       ".
                                        " Limit 1 , 1                              ",$connect) ;   
              $NumPersons_data = mysql_fetch_array($qry_singo2) ;
              ?>
              <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=center>
              &nbsp;<?=$NumPersons_data["SingoDate"]?>&nbsp;
              </td> 
         
              
              <!-- �󿵰� ������ ��� -->
              <?
              $qry_singo2 = mysql_query("Select WorkDate                          ".
                                        "  From bas_silmoojatheatherfinish         ".
                                        " Where Theather   = '".$singoTheather."'  ".
                                        "   And Open       = '".$singoOpen."'      ".
                                        "   And Film       = '".$singoFilm."'      ".
                                        " Order By WorkDate Desc                   ".
                                        " Limit 1 , 1                              ",$connect) ;   
              $NumPersons_data = mysql_fetch_array($qry_singo2) ;
              if   ($NumPersons_data)
              {
              ?>
              <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=center>
              &nbsp;<?=$NumPersons_data["WorkDate"]?>&nbsp;
              </td> 
              <?
              }
              else
              {
              ?>
              <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=center>
              &nbsp;
              </td> 
              <?
              }
              ?>
              <?
               
              $qry_singo2 = mysql_query("Select Sum(NumPersons) As SumNumPersons   ".
                                        "  From ".$sSingoName."                    ".
                                        " Where SingoDate  >= '".$FromDate."'      ".
                                        "   And SingoDate  <= '".$ToDate."'        ".
                                        "   And Theather   = '".$singoTheather."'  ".
                                        "   And Open       = '".$singoOpen."'      ".
                                        "   And Film       = '".$singoFilm."'      ",$connect) ;   
              $NumPersons_data = mysql_fetch_array($qry_singo2) ;
              ?>
              <!-- ���ھ� �հ� -->
              <td class=textarea bgcolor=<?=$Color2?> align=right>
              &nbsp;<?=number_format($NumPersons_data["SumNumPersons"])?>&nbsp;
              </td>
              
         

              <?
              $HapNumPersons = 0 ;
              $HapTotAmount  = 0 ;

              // ���ں��� ���ھ ���Ѵ�.
              $qry_singo2 = mysql_query("Select SingoDate,                         ".
                                        "       Sum(NumPersons) As SumNumPersons,  ".
                                        "       Sum(TotAmount)  As SumTotAmount    ".
                                        "  From ".$sSingoName."                    ".
                                        " Where SingoDate  >= '".$FromDate."'      ".
                                        "   And SingoDate  <= '".$ToDate."'        ".
                                        "   And Theather   = '".$singoTheather."'  ".
                                        "   And Open       = '".$singoOpen."'      ".
                                        "   And Film       = '".$singoFilm."'      ".
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
              //  ���庰 �� �հ� ���
              //
              $arrySoGea[$dur_day+1] += $HapNumPersons ; // �Ұ� ���ھ�
              $arrySoGea[$dur_day+2] += $HapTotAmount ;  // �Ұ� �ݾ��հ�

              $arryHapGea[$dur_day+1] += $HapNumPersons ; // �Ѱ� ���ھ�            
              $arryHapGea[$dur_day+2] += $HapTotAmount ;  // �Ѱ� �ݾ��հ�
              
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
                  
             <!-- �󿵰� ��ũ���� ��� -->
             <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=center>
             <?=$nNumRoom?>
             </td> 
              
             <!-- �󿵰� �¼��� ��� -->
             <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=center>
             <?=$showroomSeat?>&nbsp; 
             </td>       

             <td></td>
             <td></td>
             <td></td>

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
           �Ұ�
           </td>

           <td class=textarea bgcolor=#b0c4de align=center>
           &nbsp;<b><?=number_format($SubSumScreen)?></b>&nbsp;
           </td>

           <td class=textarea bgcolor=#b0c4de align=center>
           &nbsp;<b><?=number_format($SubSumSeat)?></b>&nbsp;
           </td>

           <td class=textarea bgcolor=#b0c4de align=center>
           &nbsp;<b></b>&nbsp;
           </td>

           <td class=textarea bgcolor=#b0c4de align=center>
           &nbsp;<b></b>&nbsp;
           </td>

               <td class=textarea bgcolor=#b0c4de align=right>
               &nbsp;<b><?=number_format($arrySoGea[$dur_day+1])?></b>&nbsp;
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
   