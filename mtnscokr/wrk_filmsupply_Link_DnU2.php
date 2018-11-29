           <table cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">
           <tr>
                     <td class=textarea bgcolor=<?=$ColorA?> align=center><b>구분</b></td>
                     <td class=textarea bgcolor=<?=$ColorA?> align=right><?=$NBSP?></td>
                     <td class=textarea bgcolor=<?=$ColorA?> align=right><b>당일합계</b></td>
                     <td class=textarea bgcolor=<?=$ColorA?> align=right><b>당일금액</b></td>
                     <td class=textarea bgcolor=<?=$ColorA?> align=right><b>총누계</b></td>
                     <td class=textarea bgcolor=<?=$ColorA?> align=right><b>누계금액</b></td>
           </tr>
           <tr>
                     <td class=textarea bgcolor=<?=$ColorC?> align=center>서울</td>
                     <?
                     $sTemp = "sLoc1" ;
                     $AddedLoc = $$sTemp ;

                     // 스크린수 (서울)
                     $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                               "       As cntShowroom                               ".
                               "  From ".$sSingoName." As singo,                    ".
                               "       ".$sFilmTypePrv." as filmtypeprv,            ".
                               "       bas_showroom As showroom                     ".
                               " Where singo.singodate  = '".$WorkDate."'           ".
                               "   And filmtypeprv.WorkDate	= '".$WorkDate."'       ".
                               "   And singo.theather = showroom.theather           ".
                               "   And singo.room     = showroom.room               ".
                               "   And singo.theather = filmtypeprv.theather        ".
                               "   And singo.room     = filmtypeprv.room            ".
                               $FilmTypeCont."                                      ".
                               $AddedLoc."                                          ".
                               $AddedCont."                                         " ;//echo $sQuery."<BR>" ;
                     $QrySingo = mysql_query($sQuery,$connect) ;
                     if  ($ArySingo = mysql_fetch_array($QrySingo))
                     {
                         $cntRealScreen = $ArySingo["cntShowroom"] ;
                     }

                     $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                               "       As cntFinishShowroom                         ".
                               "  From ".$sSingoName." As singo,                    ".
                               "       ".$sFilmTypePrv." as filmtypeprv,            ".
                               "       bas_showroom As showroom,                    ".
                               "       bas_silmoojatheatherfinish As finish         ".
                               " Where singo.singodate  = '".$WorkDate."'           ".
                               "   And filmtypeprv.WorkDate	= '".$WorkDate."'       ".
                               "   And singo.theather = showroom.theather           ".
                               "   And singo.room     = showroom.room               ".
                               "   And singo.theather = finish.theather             ".
                               "   And singo.room     = finish.room                 ".
                               "   And singo.theather = filmtypeprv.theather        ".
                               "   And singo.room     = filmtypeprv.room            ".
                               $FilmTypeCont."                                      ".
                               $AddedCont."                                         ".
                               $AddedLoc."                                          ".
                               $FinishCont."                                        ".
                               "   And singo.Silmooja = finish.silmooja             " ; //echo $sQuery."<BR>" ;
                     $QrySingo = mysql_query($sQuery,$connect) ;
                     if  ($ArySingo = mysql_fetch_array($QrySingo))
                     {
                         $cntRealScreen = $cntRealScreen - $ArySingo["cntFinishShowroom"] ;
                     }
                     ?>
                     <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=right><?=$NBSP?><?=number_format($cntRealScreen)?><?=$NBSP?></td>
                     <?
                     $nTotNumPersons = 0 ;
                     $nTotTotAmount  = 0 ;

                     $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,      ".
                               "       Sum(TotAmount) As SumTotAmount               ".
                               "  From ".$sSingoName." As singo,                    ".
                               "       ".$sFilmTypePrv." as filmtypeprv,            ".
                               "       bas_showroom As showroom                     ".
                               " Where singo.singodate  = '".$WorkDate."'           ".
                               "   And filmtypeprv.WorkDate	= '".$WorkDate."'       ".
                               "   And singo.theather = showroom.theather           ".
                               "   And singo.room     = showroom.room               ".
                               "   And singo.theather = filmtypeprv.theather        ".
                               "   And singo.room     = filmtypeprv.room            ".
                               $FilmTypeCont."                                      ".
                               $AddedLoc."                                          ".
                               $AddedCont."                                         " ;
                     $QrySingo = mysql_query($sQuery,$connect) ;
                     if  ($ArrSingo = mysql_fetch_array($QrySingo))
                     {
                         $nTotNumPersons  = $ArrSingo["SumNumPersons"] ;
                         $nTotTotAmount   = $ArrSingo["SumTotAmount"] ;
                     }

                     ?>
                     <td class=textarea bgcolor=<?=$ColorC?> align=right><?=$NBSP?><?=number_format($nTotNumPersons)?><?=$NBSP?></td>
                     <td class=textarea bgcolor=<?=$ColorC?> align=right><?=$NBSP?><?=number_format($nTotTotAmount)?><?=$NBSP?></td>
                     <td class=textarea bgcolor=<?=$ColorC?> align=right><?=$NBSP?></td>
                     <td class=textarea bgcolor=<?=$ColorC?> align=right><?=$NBSP?></td>
           </tr>
           <tr>
                     <td class=textarea bgcolor=<?=$ColorC?> align=center>경기</td>
                     <?
                     $sTemp = "sLoc2" ;
                     $AddedLoc = $$sTemp ;

                     // 스크린수 (경기)
                     $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                               "       As cntShowroom                               ".
                               "  From ".$sSingoName." As singo,                    ".
                               "       ".$sFilmTypePrv." as filmtypeprv,            ".
                               "       bas_showroom As showroom                     ".
                               " Where singo.singodate  = '".$WorkDate."'           ".
                               "   And filmtypeprv.WorkDate	= '".$WorkDate."'       ".
                               "   And singo.theather = showroom.theather           ".
                               "   And singo.room     = showroom.room               ".
                               "   And singo.theather = filmtypeprv.theather        ".
                               "   And singo.room     = filmtypeprv.room            ".
                               $FilmTypeCont."                                      ".
                               $AddedCont."                                         ".
                               $AddedLoc."                                          " ;
                     $qrysingo2 = mysql_query($sQuery,$connect) ;
                     if  ($cntShowroom_data = mysql_fetch_array($qrysingo2))
                     {
                         $cntRealScreen = $cntShowroom_data["cntShowroom"] ;
                     }

                     $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                               "       As cntFinishShowroom                         ".
                               "  From ".$sSingoName." As singo,                    ".
                               "       ".$sFilmTypePrv." as filmtypeprv,            ".
                               "       bas_showroom As showroom,                    ".
                               "       bas_silmoojatheatherfinish As finish         ".
                               " Where singo.singodate  = '".$WorkDate."'           ".
                               "   And filmtypeprv.WorkDate	= '".$WorkDate."'       ".
                               "   And singo.theather = showroom.theather           ".
                               "   And singo.room     = showroom.room               ".
                               "   And singo.theather = finish.theather             ".
                               "   And singo.room     = finish.room                 ".
                               "   And singo.theather = filmtypeprv.theather        ".
                               "   And singo.room     = filmtypeprv.room            ".
                               $FilmTypeCont."                                      ".
                               $AddedCont."                                         ".
                               $AddedLoc."                                          ".
                               $FinishCont."                                        ".
                               "   And singo.Silmooja = finish.silmooja             " ;
                     $qrysingo2 = mysql_query($sQuery,$connect) ;
                     if  ($cntShowroom_data = mysql_fetch_array($qrysingo2))
                     {
                         $cntRealScreen = $cntRealScreen - $cntShowroom_data["cntFinishShowroom"] ;
                     }
                     ?>
                     <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=right><?=$NBSP?><?=number_format($cntRealScreen)?><?=$NBSP?></td>
                     <?
                     $nTotNumPersons = 0 ;
                     $nTotTotAmount  = 0 ;

                     $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,      ".
                               "       Sum(TotAmount) As SumTotAmount               ".
                               "  From ".$sSingoName." As singo,                    ".
                               "       ".$sFilmTypePrv." as filmtypeprv,            ".
                               "       bas_showroom As showroom                     ".
                               " Where singo.singodate  = '".$WorkDate."'           ".
                               "   And filmtypeprv.WorkDate	= '".$WorkDate."'       ".
                               "   And singo.theather = showroom.theather           ".
                               "   And singo.room     = showroom.room               ".
                               "   And singo.theather = filmtypeprv.theather        ".
                               "   And singo.room     = filmtypeprv.room            ".
                               $FilmTypeCont."                                      ".
                               $AddedCont."                                         ".
                               $AddedLoc."                                          " ;
                     $QrySingo = mysql_query($sQuery,$connect) ;
                     if  ($ArrSingo = mysql_fetch_array($QrySingo))
                     {
                         $nTotNumPersons  = $ArrSingo["SumNumPersons"] ;
                         $nTotTotAmount   = $ArrSingo["SumTotAmount"] ;
                     }
                     ?>
                     <td class=textarea bgcolor=<?=$ColorC?> align=right><?=$NBSP?><?=number_format($nTotNumPersons)?><?=$NBSP?></td>
                     <td class=textarea bgcolor=<?=$ColorC?> align=right><?=$NBSP?><?=number_format($nTotTotAmount)?><?=$NBSP?></td>
                     <td class=textarea bgcolor=<?=$ColorC?> align=right><?=$NBSP?></td>
                     <td class=textarea bgcolor=<?=$ColorC?> align=right><?=$NBSP?></td>
           </tr>
           <tr>
                     <td class=textarea bgcolor=<?=$ColorC?> align=center>부산</td>
                     <?
                     $sTemp = "sLoc3" ;
                     $AddedLoc = $$sTemp ;

                     // 스크린수 (예상 부산)
                     $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                               "       As cntShowroom                               ".
                               "  From ".$sSingoName." As singo,                    ".
                               "       ".$sFilmTypePrv." as filmtypeprv,            ".
                               "       bas_showroom As showroom                     ".
                               " Where singo.singodate  = '".$WorkDate."'           ".
                               "   And filmtypeprv.WorkDate	= '".$WorkDate."'       ".
                               "   And singo.theather = showroom.theather           ".
                               "   And singo.room     = showroom.room               ".
                               "   And singo.theather = filmtypeprv.theather        ".
                               "   And singo.room     = filmtypeprv.room            ".
                               $FilmTypeCont."                                      ".
                               $AddedCont."                                         ".
                               $AddedLoc."                                          " ;
                     $qrysingo2 = mysql_query($sQuery,$connect) ;
                     if  ($cntShowroom_data = mysql_fetch_array($qrysingo2))
                     {
                         $cntRealScreen = $cntShowroom_data["cntShowroom"] ;
                     }

                     $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                               "       As cntFinishShowroom                         ".
                               "  From ".$sSingoName." As singo,                    ".
                               "       ".$sFilmTypePrv." as filmtypeprv,            ".
                               "       bas_showroom As showroom,                    ".
                               "       bas_silmoojatheatherfinish As finish         ".
                               " Where singo.singodate  = '".$WorkDate."'           ".
                               "   And filmtypeprv.WorkDate	= '".$WorkDate."'       ".
                               "   And singo.theather = showroom.theather           ".
                               "   And singo.room     = showroom.room               ".
                               "   And singo.theather = finish.theather             ".
                               "   And singo.room     = finish.room                 ".
                               "   And singo.theather = filmtypeprv.theather        ".
                               "   And singo.room     = filmtypeprv.room            ".
                               $FilmTypeCont."                                      ".
                               $AddedCont."                                         ".
                               $AddedLoc."                                          ".
                               $FinishCont."                                        ".
                               "   And singo.Silmooja = finish.silmooja             " ;
                     $qrysingo2 = mysql_query($sQuery,$connect) ;
                     if  ($cntShowroom_data = mysql_fetch_array($qrysingo2))
                     {
                          $cntRealScreen = $cntRealScreen - $cntShowroom_data["cntFinishShowroom"] ;
                     }
                     ?>
                     <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=right><?=$NBSP?><?=number_format($cntRealScreen)?><?=$NBSP?></td>
                     <?
                     $nTotNumPersons = 0 ;
                     $nTotTotAmount  = 0 ;

                     $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,      ".
                               "       Sum(TotAmount) As SumTotAmount               ".
                               "  From ".$sSingoName." As singo,                    ".
                               "       ".$sFilmTypePrv." as filmtypeprv,            ".
                               "       bas_showroom As showroom                     ".
                               " Where singo.singodate  = '".$WorkDate."'           ".
                               "   And filmtypeprv.WorkDate	= '".$WorkDate."'       ".
                               "   And singo.theather = showroom.theather           ".
                               "   And singo.room     = showroom.room               ".
                               "   And singo.theather = filmtypeprv.theather        ".
                               "   And singo.room     = filmtypeprv.room            ".
                               $FilmTypeCont."                                      ".
                               $AddedCont."                                         ".
                               $AddedLoc."                                          " ;
                     $QrySingo = mysql_query($sQuery,$connect) ;
                     if  ($ArrSingo = mysql_fetch_array($QrySingo))
                     {
                         $nTotNumPersons  = $ArrSingo["SumNumPersons"] ;
                         $nTotTotAmount   = $ArrSingo["SumTotAmount"] ;
                     }
                     ?>
                     <td class=textarea bgcolor=<?=$ColorC?> align=right><?=$NBSP?><?=number_format($nTotNumPersons)?><?=$NBSP?></td>
                     <td class=textarea bgcolor=<?=$ColorC?> align=right><?=$NBSP?><?=number_format($nTotTotAmount)?><?=$NBSP?></td>
                     <td class=textarea bgcolor=<?=$ColorC?> align=right><?=$NBSP?></td>
                     <td class=textarea bgcolor=<?=$ColorC?> align=right><?=$NBSP?></td>
           </tr>
           <tr>
                     <td class=textarea bgcolor=<?=$ColorC?> align=center>지방</td>
                     <?
                     $sTemp = "sLoc4" ;
                     $AddedLoc = $$sTemp ;

                     // 스크린수 (지방)
                     $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                               "       As cntShowroom                               ".
                               "  From ".$sSingoName." As singo,                    ".
                               "       ".$sFilmTypePrv." as filmtypeprv,            ".
                               "       bas_showroom As showroom                     ".
                               " Where singo.singodate  = '".$WorkDate."'           ".
                               "   And filmtypeprv.WorkDate	= '".$WorkDate."'       ".
                               "   And singo.theather = showroom.theather           ".
                               "   And singo.room     = showroom.room               ".
                               "   And singo.theather = filmtypeprv.theather        ".
                               "   And singo.room     = filmtypeprv.room            ".
                               $FilmTypeCont."                                      ".
                               $AddedCont."                                         ".
                               $AddedLoc."                                          " ;
                     $qrysingo2 = mysql_query($sQuery,$connect) ;
                     if  ($cntShowroom_data = mysql_fetch_array($qrysingo2))
                     {
                         $cntRealScreen = $cntShowroom_data["cntShowroom"] ;
                     }
                     $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                               "       As cntFinishShowroom                         ".
                               "  From ".$sSingoName." As singo,                    ".
                               "       ".$sFilmTypePrv." as filmtypeprv,            ".
                               "       bas_showroom As showroom,                    ".
                               "       bas_silmoojatheatherfinish As finish         ".
                               " Where singo.singodate  = '".$WorkDate."'           ".
                               "   And filmtypeprv.WorkDate	= '".$WorkDate."'       ".
                               "   And singo.theather = showroom.theather           ".
                               "   And singo.room     = showroom.room               ".
                               "   And singo.theather = finish.theather             ".
                               "   And singo.room     = finish.room                 ".
                               "   And singo.theather = filmtypeprv.theather        ".
                               "   And singo.room     = filmtypeprv.room            ".
                               $FilmTypeCont."                                      ".
                               $AddedCont."                                         ".
                               $AddedLoc."                                          ".
                               $FinishCont."                                        ".
                               "   And singo.Silmooja = finish.silmooja             " ;
                     $qrysingo2 = mysql_query($sQuery,$connect) ;
                     if  ($cntShowroom_data = mysql_fetch_array($qrysingo2))
                     {
                         $cntRealScreen = $cntRealScreen - $cntShowroom_data["cntFinishShowroom"] ;
                     }
                     ?>
                     <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=right><?=$NBSP?><?=number_format($cntRealScreen)?><?=$NBSP?></td>
                     <?
                     $nTotNumPersons = 0 ;
                     $nTotTotAmount  = 0 ;

                     $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,      ".
                               "       Sum(TotAmount) As SumTotAmount               ".
                               "  From ".$sSingoName." As singo,                    ".
                               "       ".$sFilmTypePrv." as filmtypeprv,            ".
                               "       bas_showroom As showroom                     ".
                               " Where singo.singodate  = '".$WorkDate."'           ".
                               "   And filmtypeprv.WorkDate	= '".$WorkDate."'       ".
                               "   And singo.theather = showroom.theather           ".
                               "   And singo.room     = showroom.room               ".
                               "   And singo.theather = filmtypeprv.theather        ".
                               "   And singo.room     = filmtypeprv.room            ".
                               $FilmTypeCont."                                      ".
                               $AddedCont."                                         ".
                               $AddedLoc."                                          " ;
                     $QrySingo = mysql_query($sQuery,$connect) ;
                     if  ($ArrSingo = mysql_fetch_array($QrySingo))
                     {
                         $nTotNumPersons  = $ArrSingo["SumNumPersons"] ;
                         $nTotTotAmount   = $ArrSingo["SumTotAmount"] ;
                     }
                     ?>
                     <td class=textarea bgcolor=<?=$ColorC?> align=right><?=$NBSP?><?=number_format($nTotNumPersons)?><?=$NBSP?></td>
                     <td class=textarea bgcolor=<?=$ColorC?> align=right><?=$NBSP?><?=number_format($nTotTotAmount)?><?=$NBSP?></td>
                     <td class=textarea bgcolor=<?=$ColorC?> align=right><?=$NBSP?></td>
                     <td class=textarea bgcolor=<?=$ColorC?> align=right><?=$NBSP?></td>
           </tr>
           <tr>
                     <td class=textarea bgcolor=<?=$ColorD?> align=center>총합계</td>
                     <?
                     // 스크린수 (총합계)
                     $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                               "       As cntShowroom                               ".
                               "  From ".$sSingoName." As singo,                    ".
                               "       ".$sFilmTypePrv." as filmtypeprv,            ".
                               "       bas_showroom As showroom                     ".
                               " Where singo.singodate  = '".$WorkDate."'           ".
                               "   And filmtypeprv.WorkDate	= '".$WorkDate."'       ".
                               "   And singo.theather = showroom.theather           ".
                               "   And singo.room     = showroom.room               ".
                               "   And singo.theather = filmtypeprv.theather        ".
                               "   And singo.room     = filmtypeprv.room            ".
                               $FilmTypeCont."                                      ".
                               $AddedCont."                                         " ;
                     $qrysingo3 = mysql_query($sQuery,$connect) ;
                     if  ($cntShowroom_data = mysql_fetch_array($qrysingo3))
                     {
                         $cntRealScreen = $cntShowroom_data["cntShowroom"] ;
                     }
                     $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                               "       As cntFinishShowroom                         ".
                               "  From ".$sSingoName." As singo,                    ".
                               "       ".$sFilmTypePrv." as filmtypeprv,            ".
                               "       bas_showroom As showroom,                    ".
                               "       bas_silmoojatheatherfinish As finish         ".
                               " Where singo.singodate  = '".$WorkDate."'           ".
                               "   And filmtypeprv.WorkDate	= '".$WorkDate."'       ".
                               "   And singo.theather = showroom.theather           ".
                               "   And singo.room     = showroom.room               ".
                               "   And singo.theather = finish.theather             ".
                               "   And singo.room     = finish.room                 ".
                               "   And singo.theather = filmtypeprv.theather        ".
                               "   And singo.room     = filmtypeprv.room            ".
                               $FilmTypeCont."                                      ".
                               $AddedCont."                                         ".
                               $FinishCont."                                        ".
                               "   And singo.Silmooja = finish.silmooja             " ;
                     $qrysingo2 = mysql_query($sQuery,$connect) ;
                     if  ($cntShowroom_data = mysql_fetch_array($qrysingo2))
                     {
                         $cntRealScreen = $cntRealScreen - $cntShowroom_data["cntFinishShowroom"] ;
                     }
                     ?>
                     <td class=textarea bgcolor=<?=$ColorD?> class=tbltitle align=center><?=$NBSP?><?=number_format($cntRealScreen)?><?=$NBSP?></td>
                     <?
                     $nTotNumPersons = 0 ;
                     $nTotTotAmount  = 0 ;

                     $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,      ".
                               "       Sum(TotAmount) As SumTotAmount               ".
                               "  From ".$sSingoName." As singo,                    ".
                               "       ".$sFilmTypePrv." as filmtypeprv,            ".
                               "       bas_showroom As showroom                     ".
                               " Where singo.singodate  = '".$WorkDate."'           ".
                               "   And filmtypeprv.WorkDate	= '".$WorkDate."'       ".
                               "   And singo.theather = showroom.theather           ".
                               "   And singo.room     = showroom.room               ".
                               "   And singo.theather = filmtypeprv.theather        ".
                               "   And singo.room     = filmtypeprv.room            ".
                               $FilmTypeCont."                                      ".
                               $AddedCont."                                         " ;
                     $QrySingo = mysql_query($sQuery,$connect) ;
                     if  ($ArrSingo = mysql_fetch_array($QrySingo))
                     {
                         $nTotNumPersons  = $ArrSingo["SumNumPersons"] ;
                         $nTotTotAmount   = $ArrSingo["SumTotAmount"] ;
                     }
                     ?>
                     <td class=textarea bgcolor=<?=$ColorD?> align=right><?=$NBSP?><?=number_format($nTotNumPersons)?><?=$NBSP?></td>
                     <td class=textarea bgcolor=<?=$ColorD?> align=right><?=$NBSP?><?=number_format($nTotTotAmount)?><?=$NBSP?></td>
                     <td class=textarea bgcolor=<?=$ColorD?> align=right><?=$NBSP?></td>
                     <td class=textarea bgcolor=<?=$ColorD?> align=right><?=$NBSP?></td>
           </tr>

        </table>