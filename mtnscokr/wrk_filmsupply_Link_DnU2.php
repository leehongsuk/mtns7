           <table cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">
           <tr>
                     <td class=textarea bgcolor=<?=$ColorA?> align=center><b>����</b></td>
                     <td class=textarea bgcolor=<?=$ColorA?> align=right><?=$NBSP?></td>
                     <td class=textarea bgcolor=<?=$ColorA?> align=right><b>�����հ�</b></td>
                     <td class=textarea bgcolor=<?=$ColorA?> align=right><b>���ϱݾ�</b></td>
                     <td class=textarea bgcolor=<?=$ColorA?> align=right><b>�Ѵ���</b></td>
                     <td class=textarea bgcolor=<?=$ColorA?> align=right><b>����ݾ�</b></td>
           </tr>
           <tr>
                     <td class=textarea bgcolor=<?=$ColorC?> align=center>����</td>
                     <?
                     $sTemp = "sLoc1" ;
                     $AddedLoc = $$sTemp ;

                     // ��ũ���� (����)
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
                     <td class=textarea bgcolor=<?=$ColorC?> align=center>���</td>
                     <?
                     $sTemp = "sLoc2" ;
                     $AddedLoc = $$sTemp ;

                     // ��ũ���� (���)
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
                     <td class=textarea bgcolor=<?=$ColorC?> align=center>�λ�</td>
                     <?
                     $sTemp = "sLoc3" ;
                     $AddedLoc = $$sTemp ;

                     // ��ũ���� (���� �λ�)
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
                     <td class=textarea bgcolor=<?=$ColorC?> align=center>����</td>
                     <?
                     $sTemp = "sLoc4" ;
                     $AddedLoc = $$sTemp ;

                     // ��ũ���� (����)
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
                     <td class=textarea bgcolor=<?=$ColorD?> align=center>���հ�</td>
                     <?
                     // ��ũ���� (���հ�)
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