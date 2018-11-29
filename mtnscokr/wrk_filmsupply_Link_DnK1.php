   <?
   $SubSumSeat = 0 ;

   $cntLocData = 0 ;

   for ($i=0 ; $i<=($dur_day+1) ; $i++)
   {
       $arrySubSumNumPersons[$i] = 0 ;
   }   

   $oldTheather = "" ;
   
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
       
       if   ($singoFilm=='00')
       {
            $CondOpenFilm = " Open = '".$singoOpen."' " ;
       }
       else
       {
            $CondOpenFilm = "     Open = '".$singoOpen."' " .
                            " And Film = '".$singoFilm."' " ;
       }

       $singoSilmooja = $singo_data["Silmooja"] ;

       $qrysilmooja = mysql_query("Select * From bas_silmooja         ".
                                  " Where Code = '".$singoSilmooja."' ",$connect) ;

       $silmooja_data = mysql_fetch_array($qrysilmooja) ;
       if  ($silmooja_data)
       {
           $silmoojaName = $silmooja_data["Name"] ;
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

       if  ($oldsingoTheather != $singoTheather)
       {
           $clrToggle = !$clrToggle ;

           $oldsingoTheather = $singoTheather ;
       }

     
       if  ($oldTheather != "")
       {
           if  ($oldTheather != $singoTheather)
           {
               ?>
               <tr height=20>
                   <td class=textarea bgcolor=#c0c0c0 class=tbltitle align=center>               
                   &nbsp;
                   </td>
                   <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=center>               
                   &nbsp;
                   </td>
                   <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=right>               
                   &nbsp;
                   </td>
                   <?
                   for ($i=0 ; $i<=$dur_day+1 ; $i++)
                   {
                   ?>
                   <td class=textarea bgcolor=<?=$Color2?> class=tbltitle align=right>               
                   <?=number_format($arryTheatherSumNumPersons[$i])?>&nbsp;
                   </td>
                   <?
                   }
                   ?>
               </tr>
               <?
               for ($i=0 ; $i<=($dur_day+1) ; $i++)
               {
                   $arryTheatherSumNumPersons[$i] = 0 ;
               }
           }
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







       $oldTheather  = $singoTheather ;
       ?>   


      
       <!--             -->
       <!-- 내용 찍기 -->
       <!--             -->
       <tr height=20>
            <td class=textarea bgcolor=#c0c0c0 width=70 class=tbltitle align=center>               
                <?=$locationName?> <!-- 지역 -->
            </td>

            <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=center>
                <?=$showroomDiscript?> <!-- 극장명  -->
            </td>

            <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=right>
                <?=$showroomSeat?>&nbsp; <!-- 좌석수 -->
            </td>

            <?
            $SubSumSeat += $showroomSeat ;
            $SumSeat    += $showroomSeat ;


            //
            //  합계 찍기
            //  
            $qry_singo2 = mysql_query("Select SingoDate, Sum(NumPersons) As SumNumPersons  ".
                                      "  From ".$sSingoName."                              ".
                                      " Where SingoDate  >= '".$FromDate."'                ".
                                      "   And SingoDate  <= '".$ToDate."'                  ".
                                      "   And Theather   = '".$singoTheather."'            ".
                                      "   And Room       = '".$singoRoom."'                ".
                                      "   And ".$CondOpenFilm."                            ".
                                      " Group By SingoDate                                 ",$connect) ;   
            $NumPersons_data = mysql_fetch_array($qry_singo2) ;
            
            for ($i=0 ; $i<=$dur_day ; $i++)
            {
                $objDate = date("Ymd",$timestamp2 + ($i * 86400)) ;               

                if  ($NumPersons_data)
                {
                    if  ($objDate == $NumPersons_data["SingoDate"]) 
                    {
                        $SumNumPersons = $NumPersons_data["SumNumPersons"] ;

                        $arryTheatherSumNumPersons[$i] += $SumNumPersons ;
                        $arrySubSumNumPersons[$i]      += $SumNumPersons ;
                        $arrySumNumPersons[$i]         += $SumNumPersons ;
                        ?>
                        <td class=textarea bgcolor=<?=$Color2?> align=right>
                        &nbsp;<?=number_format($SumNumPersons)?>&nbsp;
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
            $qry_singo2 = mysql_query("Select Sum(NumPersons) As SumNumPersons      ".
                                      "  From ".$sSingoName."                       ".
                                      " Where SingoDate  >= '".$FromDate."'         ".
                                      "   And SingoDate  <= '".$ToDate."'           ".
                                      "   And Theather   = '".$singoTheather."'     ".
                                      "   And Room       = '".$singoRoom."'         ".
                                      "   And ".$CondOpenFilm."                     ",$connect) ;
            $NumPersons_data = mysql_fetch_array($qry_singo2) ;
            if  ($NumPersons_data)
            {
                $SumNumPersons = $NumPersons_data["SumNumPersons"] ;
                $SumTotAmount  = $NumPersons_data["SumTotAmount"] ;

                $arryTheatherSumNumPersons[$dur_day+1] += $SumNumPersons ;
                $arrySubSumNumPersons[$dur_day+1] += $SumNumPersons ;
                $arrySumNumPersons[$dur_day+1]    += $SumNumPersons ;

                ?>
                <td class=textarea bgcolor=<?=$Color1?> align=right>
                &nbsp;<?=number_format($SumNumPersons)?>&nbsp;
                </td>
                <?
            }
            else
            {
                ?>
                <td class=textarea bgcolor=<?=$Color1?> align=center>
                -
                </td>
                <?
            }
            

            ?>

       </tr>  

       
   <? 
       $cntLocData ++ ;
   }
   ?>

   <tr height=20>
       <td class=textarea bgcolor=#c0c0c0 class=tbltitle align=center>               
       &nbsp;
       </td>
       <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=center>               
       &nbsp;
       </td>
       <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=right>               
       &nbsp;
       </td>
       <?
       for ($i=0 ; $i<=$dur_day+1 ; $i++)
       {
       ?>
       <td class=textarea bgcolor=<?=$Color2?> class=tbltitle align=right>               
       <?=number_format($arryTheatherSumNumPersons[$i])?>&nbsp;
       </td>
       <?
       }
       ?>
   </tr>
   <?
   //
   //  소계 출력
   //

   if  ($cntLocData>0)
   {
   ?> 
      <tr height=20>
      
      <td class=textarea bgcolor=#b0c4de align=center colspan=2>
      소계
      </td>

      <td class=textarea bgcolor=#b0c4de align=right>
      &nbsp;<?=number_format($SubSumSeat)?>&nbsp;
      </td>

      <? 
      for ($i=0 ; $i<(count($arrySubSumNumPersons)) ; $i++)
      {       
      ?>
          <td class=textarea bgcolor=#b0c4de class=tbltitle align=right>
          &nbsp;<?=number_format($arrySubSumNumPersons[$i])?>&nbsp;
          </td>
      <?
      } 

      ?>
      </tr>
   <?
   } 
   ?>

