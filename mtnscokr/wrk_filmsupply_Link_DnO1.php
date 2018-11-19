   <?
   $cntLocData = 0 ;

   for ($i=0 ; $i<6 ; $i++)
   {
       $Sums[$i]  = 0 ; //
   }

   while ($singo_data = mysql_fetch_array($qry_singo))
   {
       $singoTheather = $singo_data["Theather"] ;

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

       $sQuery = "Select Theather.Discript,
                         Theather.JikYong,
                         Location.Code,
                         Location.Name
                    From bas_theather As Theather
                    Left Join bas_location As Location
                      On Theather.Location = Location.Code
                   Where Theather.Code = '".$singoTheather."'
                 " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
       $qryTheather = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
       if  ($arrTheather = mysql_fetch_array($qryTheather))
       {
           $showroomDiscript = $arrTheather["Discript"] ;
           $JikYong          = $arrTheather["JikYong"] ;
           $locationCode     = $arrTheather["Code"] ;
           $locationName     = $arrTheather["Name"] ; // 지역명 ..

           $TheatherRate  = get_theather_rate_value($WorkDate,$locationCode,$singoTheather,$singoOpen,$singoFilm,$connect) ;
           /*
           if  ($locationCode == "100")
           {
               if  ($JikYong == "N")
               {
                   if  (($singoTheather=="2373") || ($singoTheather=="2372") || ($singoTheather=="2426"))
                   {
                       $sRate = "50%" ;
                       $dRate = 0.5 ;
                   }
                   else
                   {
                       $sRate = "60%" ;
                       $dRate = 0.6 ;
                   }
               }
               else
               {
                   $sRate = "55%" ;
                   $dRate = 0.55 ;  // 직영일때
               }
           }
           else
           {
               if  ($JikYong == "N")
               {
                   $sRate = "50%" ;
                   $dRate = 0.5 ;
               }
               else
               {
                   $sRate = "45%" ;
                   $dRate = 0.45 ; // 직영일때
               }
           } */
           $Rate = $TheatherRate / 100.0 ; ////////// 부율
           $sRate = $TheatherRate . "%" ;

       }

       $sQuery = "Select Sum(NumPersons) As SumNumPersons,
                         Sum(TotAmount)  As SumTotAmount,
                         Sum(TotAmountGikum)  As SumTotAmountGikum
                    From $sSingoName
                   Where SingoDate >= '$FromDate'
                     And SingoDate <= '$ToDate'
                     And Open      = '$FilmOpen'
                     And Film      = '$FilmCode'
                     And Theather  = '$singoTheather'
                 " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
       $qrySum = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
       if  ($arrSum = mysql_fetch_array($qrySum))
       {
            $arrSumNumPersons         = $arrSum["SumNumPersons"] ;
            $arrSumTotAmount          = $arrSum["SumTotAmount"] ;
            $arrSumTotAmountGikum     = $arrSum["SumTotAmountGikum"] ;
            $arrSumTotAmountGikumVAT  = $arrSumTotAmountGikum * 0.1 ;
            $arrSumTotAmountGikumGong = $arrSumTotAmountGikum -$arrSumTotAmountGikumVAT ;
            $arrSumTotAmountGikumAck  = $arrSumTotAmountGikum * $dRate ;

            $Sums[0] += $arrSumNumPersons ;
            $Sums[1] += $arrSumTotAmount ;
            $Sums[2] += $arrSumTotAmountGikum ;
            $Sums[3] += $arrSumTotAmountGikumGong ;
            $Sums[4] += $arrSumTotAmountGikumVAT ;
            $Sums[5] += $arrSumTotAmountGikumAck ;
       }

       ?>

       <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">

       <?
       for ($i=0 ; $i<=($dur_day+5) ; $i++)
       {
           $arryHapGea[$i]  = 0 ; // 극장별 합계를 극장이 바뀔때 마다 초기화한다.
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
       <tr height=20>

               <!-- 지역명 출력 -->
               <td class=textarea bgcolor=<?=$Color1?> width=50 class=tbltitle align=center>
               <?=$locationName?>
               </td>

               <!-- 상영관명 출력 -->
               <td class=textarea bgcolor=<?=$Color1?> class=tbltitle width=120 align=center>
               <?=$showroomDiscript?>
               </td>

               <td class=textarea width=60 bgcolor=<?=$Color1?> align=center>
               <!-- 종영일 -->&nbsp;
               </td>

               <td class=textarea width=50 bgcolor=<?=$Color1?> align=center>
               <!-- 부율 --><?=$sRate?>
               </td>

               <td class=textarea width=60 bgcolor=<?=$Color1?> class=tbltitle align=right>
               <!-- 인원 --><?=number_format($arrSumNumPersons)?>&nbsp;
               </td>

               <td class=textarea width=90 bgcolor=<?=$Color1?> class=tbltitle align=right>
               <!-- 금액(입장료) --><?=number_format($arrSumTotAmount)?>&nbsp;
               </td>

               <td class=textarea width=90 bgcolor=<?=$Color1?> class=tbltitle align=right>
               <!-- 기금제외금액 --><?=number_format($arrSumTotAmountGikum)?>&nbsp;
               </td>

               <td class=textarea width=100 bgcolor=<?=$Color1?> class=tbltitle align=right>
               <!-- 공급가액 --><?=number_format($arrSumTotAmountGikumGong)?>&nbsp;
               </td>

               <td class=textarea width=70 bgcolor=<?=$Color1?> class=tbltitle align=right>
               <!-- 부가세 --><?=number_format($arrSumTotAmountGikumVAT)?>&nbsp;
               </td>

               <td class=textarea width=100 bgcolor=<?=$Color1?> class=tbltitle align=right>
               <!-- 영화사 입금액 --><?=number_format($arrSumTotAmountGikumAck)?>&nbsp;
               </td>

       </tr>
       </table>

       <?
       $cntLocData ++ ;
   }
   ?>




   <?
   // --------------------------------------------
   // 소계
   // --------------------------------------------

   if  ($cntLocData > 0) // 한번이라도 데이터가 나온경우
   {
       for ($i=0 ; $i<6 ; $i++)
       {
           $TotSums[$i] += $Sums[$i] ;
       }
   ?>
       <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">
       <tr height=20>
           <td class=textarea bgcolor=#b0c4de align=center colspan=2 width=170>
           소계
           </td>

           <td class=textarea width=60 bgcolor=#b0c4de align=center>
           <!-- 종영일 -->&nbsp;
           </td>

           <td class=textarea width=50 bgcolor=#b0c4de align=center>
           <!-- 부율 -->&nbsp;
           </td>

           <td class=textarea width=60 bgcolor=#b0c4de class=tbltitle align=right>
           <!-- 인원 --><?=number_format($Sums[0])?>&nbsp;
           </td>

           <td class=textarea width=90 bgcolor=#b0c4de class=tbltitle align=right>
           <!-- 금액(입장료) --><?=number_format($Sums[1])?>&nbsp;
           </td>

           <td class=textarea width=90 bgcolor=#b0c4de class=tbltitle align=right>
           <!-- 기금제외금액 --><?=number_format($Sums[2])?>&nbsp;
           </td>

           <td class=textarea width=100 bgcolor=#b0c4de class=tbltitle align=right>
           <!-- 공급가액 --><?=number_format($Sums[3])?>&nbsp;
           </td>

           <td class=textarea width=70 bgcolor=#b0c4de class=tbltitle align=right>
           <!-- 부가세 --><?=number_format($Sums[4])?>&nbsp;
           </td>

           <td class=textarea width=100 bgcolor=#b0c4de class=tbltitle align=right>
           <!-- 영화사 입금액 --><?=number_format($Sums[5])?>&nbsp;
           </td>
       </tr>
       </table>
   <?
   }
   ?>
