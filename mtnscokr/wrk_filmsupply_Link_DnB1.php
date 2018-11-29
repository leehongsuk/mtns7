   <?
   $SubSumSeat = 0 ;

   $cntLocData = 0 ;

   for ($i=0 ; $i<=($dur_day+4) ; $i++)
   {
       $arrySubSumNumPersons[$i] = 0 ;
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
       
       $sSingoName = get_singotable($singoOpen,$singoFilm,$connect) ;  // ½Å°í Å×ÀÌºí ÀÌ¸§..

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

           // »ó¿µ°üÀÇ ¼ÒÀçÁö Áö¿ªÀ» ±¸ÇÑ´Ù. ($locationName)
           $query1 = mysql_query("Select * From bas_location            ".
                                 " Where Code = '".$showroomLocation."' ",$connect) ;

           $location_data = mysql_fetch_array($query1) ;

           if  ($location_data)
           {
               $locationName = $location_data["Name"] ; // Áö¿ª¸í ..
           }
       }

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


      
       <!--             -->
       <!-- ³»¿ë Âï±â -->
       <!--             -->
       <tr height=20>
            <td class=textarea bgcolor=#c0c0c0 width=70 class=tbltitle align=center>               
                <?=$locationName?> <!-- Áö¿ª -->
            </td>

            <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=center>
                <?=$showroomDiscript?> <!-- ±ØÀå¸í  -->
            </td>

            <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=right>
                <?=$showroomSeat?>&nbsp; <!-- ÁÂ¼®¼ö -->
            </td>

            <?
            $SubSumSeat += $showroomSeat ;
            $SumSeat    += $showroomSeat ;
            ?>

            <?
            //
            //  ÇÕ°è Âï±â
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

                        $arrySubSumNumPersons[$i] += $SumNumPersons ;
                        $arrySumNumPersons[$i]    += $SumNumPersons ;
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
            //  ±ØÀåº° ÃÑ ÇÕ°è Âï±â
            //            
            $qry_singo2 = mysql_query("Select Sum(NumPersons) As SumNumPersons,     ".
                                      "       Sum(TotAmount)  As SumTotAmount       ".
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

                $arrySubSumNumPersons[$dur_day+1] += $SumNumPersons ;
                $arrySumNumPersons[$dur_day+1]    += $SumNumPersons ;

                $arrySubSumNumPersons[$dur_day+2] += $SumTotAmount ;
                $arrySumNumPersons[$dur_day+2]    += $SumTotAmount ;

                ?>
                <td class=textarea bgcolor=<?=$Color1?> align=right>
                &nbsp;<?=number_format($SumNumPersons)?>&nbsp;
                </td>
                <td class=textarea bgcolor=<?=$Color1?> align=right>
                &nbsp;<?=number_format($SumTotAmount)?>&nbsp;
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

            //
            //  ±ØÀåº° ÃÑ ´©°è Âï±â
            //            
            $qry_singo2 = mysql_query("Select Sum(NumPersons) As SumNumPersons  ".
                                      "  From ".$sSingoName."                   ".
                                      " Where SingoDate  <= '".$ToDate."'       ".
                                      "   And Theather   = '".$singoTheather."' ".
                                      "   And Room       = '".$singoRoom."'     ".
                                      "   And ".$CondOpenFilm."                 ",$connect) ;
            $NumPersons_data = mysql_fetch_array($qry_singo2) ;
            if  ($NumPersons_data)
            {
                $arrySubSumNumPersons[$dur_day+3] += $NumPersons_data["SumNumPersons"] ;
                $arrySumNumPersons[$dur_day+3]    += $NumPersons_data["SumNumPersons"] ;

                ?>
                <td class=textarea bgcolor=<?=$Color1?> align=right>
                &nbsp;<?=number_format($NumPersons_data["SumNumPersons"])?>&nbsp;
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

            //
            //  ±ØÀåº° ÃÑ ±Ý¾× Âï±â
            //            
            $qry_singo2 = mysql_query("Select Sum(TotAmount) As SumTotAmount     ".
                                      "  From ".$sSingoName."                    ".
                                      " Where SingoDate  <= '".$ToDate."'        ".
                                      "   And Theather   = '".$singoTheather."'  ".
                                      "   And Room       = '".$singoRoom."'      ".
                                      "   And ".$CondOpenFilm."                  ",$connect) ;
            $NumPersons_data = mysql_fetch_array($qry_singo2) ;
            if  ($NumPersons_data)
            {
                $arrySubSumNumPersons[$dur_day+4] += $NumPersons_data["SumTotAmount"] ;
                $arrySumNumPersons[$dur_day+4]    += $NumPersons_data["SumTotAmount"] ;

                ?>
                <td class=textarea bgcolor=<?=$Color1?> align=right>
                &nbsp;<?=number_format($NumPersons_data["SumTotAmount"])?>&nbsp;
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

   <?
   //
   //  ¼Ò°è Ãâ·Â
   //

   if  ($cntLocData>0)
   {
   ?> 
      <tr height=20>
      
      <td class=textarea bgcolor=#b0c4de align=center colspan=2>
      ¼Ò°è
      </td>

      <td class=textarea bgcolor=#b0c4de align=right>
      &nbsp;<?=number_format($SubSumSeat)?>&nbsp;
      </td>

      <? 
      for ($i=0 ; $i<(count($arrySubSumNumPersons)-2) ; $i++)
      {       
      ?>
          <td class=textarea bgcolor=#b0c4de class=tbltitle align=right>
          &nbsp;<?=number_format($arrySubSumNumPersons[$i])?>&nbsp;
          </td>
      <?
      } 

      ?>
      <td class=textarea bgcolor=#b0c4de class=tbltitle align=right>
      &nbsp;<?=number_format($arrySubSumNumPersons[count($arrySubSumNumPersons)-2])?>&nbsp;
      </td>

      <td class=textarea bgcolor=#b0c4de class=tbltitle align=right>
      &nbsp;<?=number_format($arrySubSumNumPersons[count($arrySubSumNumPersons)-1])?>&nbsp;
      </td>

      </tr>
   <?
   } 
   ?>

