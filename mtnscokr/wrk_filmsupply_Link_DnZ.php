    <?
    if   ($FilmCode == '00') // 분리된영화의통합코드
    {
         $FilmCond = "    singo.Open = '".$FilmOpen."'  " ;
    }
    else
    {
         $FilmCond = "    singo.Open = '".$FilmOpen."'  ".
                     "And singo.Film = '".$FilmCode."'  " ;
    }

    if ($ToExel)
    {
        $ColorA =  '#ffffff' ;
        $ColorB =  '#ffffff' ;
        $ColorC =  '#ffffff' ;
        $ColorD =  '#ffffff' ;
    }
    else
    {
        $ColorA =  '#ffebcd' ;
        $ColorB =  '#dcdcec' ;
        $ColorC =  '#dcdcdc' ;
        $ColorD =  '#c0c0c0' ;
    }


    // 누계금액 부터 구한다. (신고일자, 배급사) // 영화 가 빠져있음 확인요

    $SumTotAmount = 0 ;

    if  ($sSingoName != "")
    {
        $sQuery = "Select Sum(singo.TotAmount) As SumTotAmount  ".
                  "  From ".$sSingoName." As singo              ".
                  " Where singo.SingoDate  <= '".$WorkDate."'   ".
                  "   And ".$FilmCond."                         " ;
        $qrysingo3 = mysql_query($sQuery,$connect) ;
        if  ($SumTotAmount_data = mysql_fetch_array($qrysingo3))
        {
            $SumTotAmount = $SumTotAmount_data["SumTotAmount"] ;
        }
    }

    ?>
    <table cellpadding=0 cellspacing=0 border=1 bordercolor='#C0B0A0'>

       <!----------------------------------------------------------------------------------------------------------->
       <!--
                                         타이틀 찍기

                                                                                                                  -->
       <!----------------------------------------------------------------------------------------------------------->
       <tr height=25>
             <td class=textarea bgcolor=<?=$ColorA?> align=center>구분</td>
             <td class=textarea bgcolor=<?=$ColorA?> align=center>&nbsp;당일합계&nbsp;</td>
             <td class=textarea bgcolor=<?=$ColorA?> align=center>&nbsp;전일합계&nbsp;</td>
             <td class=textarea bgcolor=<?=$ColorA?> align=center>&nbsp;총누계&nbsp;</td>
             <td class=textarea bgcolor=<?=$ColorA?> align=center>&nbsp;당일금액&nbsp;</td>
             <?
             if  ($WorkGubun == 29)
             {
             ?>
             <td class="textarea topsum_col_2" bgcolor=<?=$ColorA?> align=center>기금적용<?if  (!$ToExel) { ?><br><? } else { echo "\n" ; }?>당일금액</td>
             <td class="textarea topsum_col_3"  bgcolor=<?=$ColorA?> align=center>누계금액</td>
             <?
             }
             else
             {
             ?>
             <td class="textarea topsum_col_1"  bgcolor=<?=$ColorA?> align=center>누계금액</td>
             <td class="textarea topsum_col_2"  bgcolor=<?=$ColorA?> align=center>기금적용<?if  (!$ToExel) { ?><br><? } else { echo "\n" ; }?>당일금액</td>
             <td class="textarea topsum_col_3"  bgcolor=<?=$ColorA?> align=center>기금적용<?if  (!$ToExel) { ?><br><? } else { echo "\n" ; } ?>누계금액</td>
             <?
             }
             ?>
       </tr>


       <!----------------------------------------------------------------------------------------------------------->
       <!--
                                         서울 합계 찍기
                                         (100)
                                                                                                                  -->
       <!----------------------------------------------------------------------------------------------------------->
       <tr height=20>
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center>서울</td>

            <?
            // 당일합계

            $dispItm1 = "-" ;
            if  ($sSingoName != "")
            {
                $sQuery = "Select Sum(NumPersons) As SumNumPersons,         ".
                          "       Sum(TotAmount) As SumTotAmount,           ".
                          "       Sum(TotAmountGikum) As SumTotAmountGikum  ".
                          "  From ".$sSingoName."   As singo,               ".
                          "       bas_showroom      As showroom             ".
                          " Where singo.SingoDate  = '".$WorkDate."'        ".
                          "   And ".$FilmCond."                             ".
                          "   And singo.theather = showroom.theather        ".
                          "   And singo.room     = showroom.room            ".
                          "   And singo.Location   = '100'                  " ; // 여기서 일괄 변경..
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
                if  ($WorkGubun == 56) // 기타
                {
                    $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                }
                if  ($nFilmTypeNo != "0")
                {
                    $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                }
//eq($sQuery);
                $qrysingo2 = mysql_query($sQuery,$connect) ;     // eq($sQuery );
                if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                {
                    $WorkDateNumPersons = $NumPersons_data["SumNumPersons"] ;
                    $WorkDateTotAmount  = $NumPersons_data["SumTotAmount"] ;
                    $WorkDateTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                    $dispItm1 = number_format($WorkDateNumPersons) ;
                }
            }
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm1?></td>

            <?
            // 전일합계
            $dispItm2 = "-" ;
            if  ($sSingoName != "")
            {
                $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons    ".
                          "  From ".$sSingoName."   As singo,               ".
                          "       bas_showroom      As showroom             ".
                          " Where singo.SingoDate  = '".$AgoDate."'         ".
                          "   And ".$FilmCond."                             ".
                          "   And singo.theather = showroom.theather        ".
                          "   And singo.room     = showroom.room            ".
                          "   And singo.Location   = '100'                  " ;
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
                if  ($WorkGubun == 56) // 기타
                {
                    $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                }
                if  ($nFilmTypeNo != "0")
                {
                    $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                }
                $qrysingo2 = mysql_query($sQuery,$connect) ;
                if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                {
                    $AgoDateNumPersons = $NumPersons_data["SumNumPersons"] ;

                    $dispItm2 = number_format($AgoDateNumPersons) ;
                }
            }
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm2?></td>

            <?
            // 총누계
            $dispItm3 = "-" ;
            if  ($sSingoName != "")
            {
                $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,        ".
                          "       Sum(singo.TotAmount) As SumTotAmount,          ".
                          "       Sum(singo.TotAmountGikum) As SumTotAmountGikum ".
                          "  From ".$sSingoName."   As singo,                    ".
                          "       bas_showroom      As showroom                  ".
                          " Where singo.SingoDate <= '".$WorkDate."'             ".
                          "   And ".$FilmCond."                                  ".
                          "   And singo.theather = showroom.theather             ".
                          "   And singo.room     = showroom.room                 ".
                          "   And singo.Location   = '100'                       " ;
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
                if  ($WorkGubun == 56) // 기타
                {
                    $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                }
                if  ($nFilmTypeNo != "0")
                {
                    $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                }
                $qrysingo2 = mysql_query($sQuery,$connect) ;
                if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                {
                    $TotNumPersons = $NumPersons_data["SumNumPersons"] ;
                    $TotTotAmount  = $NumPersons_data["SumTotAmount"] ;
                    $TotTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                    $dispItm3 =  number_format($TotNumPersons) ;
                }
            }
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm3?></td>

            <?
            $dispItm4  = number_format($WorkDateTotAmount) ;
            $dispItm43 = number_format($WorkDateTotAmountGikum) ;
            $dispItm5  = number_format($TotTotAmount) ;
            $dispItm53 = number_format($TotTotAmountGikum) ;
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm4?></td>
            <?
            if  ($WorkGubun == 29)
            {
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm43?></td>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm5?></td>
            <?
            }
            else
            {
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm5?></td>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm43?></td>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm53?></td>
            <?
            }
            ?>
       </tr>

       <!----------------------------------------------------------------------------------------------------------->
       <!--

                                         경기 합계 찍기
                                         (04)
                                                                                                                  -->
       <!----------------------------------------------------------------------------------------------------------->
       <?


       $zoneName = "지역명없음" ; // 지역코드로 지역명을 찾는다..
       $sQuery = "Select * From bas_zone  ".
                 " Where Code = '04'      " ;
       $qryzone = mysql_query($sQuery,$connect) ;
       if  ($zone_data = mysql_fetch_array($qryzone))
       {
           $zoneName = $zone_data["Name"] ;
       }

       $AddedLoc = "" ;
       $sQuery = "Select * From bas_filmsupplyzoneloc  ".
                 " Where Zone = '04'                   " ;
       $qryzone = mysql_query($sQuery,$connect) ;
       if  ($zone_data = mysql_fetch_array($qryzone))
       {
            $AddedLoc = " And " ;

            $sQuery = "Select Location From bas_filmsupplyzoneloc  ".
                      " Where Zone = '04'                          " ;
            $qryzoneloc = mysql_query($sQuery,$connect) ;
            while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
            {
                 if  ($AddedLoc == " And ")
                     $AddedLoc .= "( singo.Location = '".$zoneloc_data["Location"]."' "  ;
                 else
                     $AddedLoc .= " or singo.Location = '".$zoneloc_data["Location"]."' "  ;
            }
            $AddedLoc .= ")" ;
       }
       ?>
       <tr height=20>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> class=tbltitle align=center><?=$zoneName?></td>

            <?
            // 당일합계
            $dispItm1 = "-" ;
            if  ($sSingoName != "")
            {
                $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,        ".
                          "       Sum(singo.TotAmount) As SumTotAmount,          ".
                          "       Sum(singo.TotAmountGikum) As SumTotAmountGikum ".
                          "  From ".$sSingoName."   As singo,                    ".
                          "       bas_showroom      As showroom                  ".
                          " Where singo.SingoDate  = '".$WorkDate."'             ".
                          "   And ".$FilmCond."                                  ".
                          "   And singo.theather = showroom.theather             ".
                          "   And singo.room     = showroom.room                 ".
                          $AddedLoc."                                            " ;
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
                if  ($WorkGubun == 56) // 기타
                {
                    $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                }
                if  ($nFilmTypeNo != "0")
                {
                    $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                }
//eq($sQuery);
                $qrysingo2 = mysql_query($sQuery,$connect) ;
                if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                {
                    $WorkDateNumPersons = $NumPersons_data["SumNumPersons"] ;
                    $WorkDateTotAmount  = $NumPersons_data["SumTotAmount"] ;
                    $WorkDateTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                    $dispItm1 = number_format($WorkDateNumPersons) ;
                }
            }
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm1?></td>

            <?
            // 전일합계
            $dispItm2 = "-" ;
            if  ($sSingoName != "")
            {
                $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons   ".
                          "  From ".$sSingoName."   As singo,              ".
                          "       bas_showroom      As showroom            ".
                          " Where singo.SingoDate  = '".$AgoDate."'        ".
                          "   And ".$FilmCond."                            ".
                          "   And singo.theather = showroom.theather       ".
                          "   And singo.room     = showroom.room           ".
                          $AddedLoc."                                      " ;
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
                if  ($WorkGubun == 56) // 기타
                {
                    $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                }
                if  ($nFilmTypeNo != "0")
                {
                    $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                }
                $qrysingo2 = mysql_query($sQuery,$connect) ;
                if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                {
                    $AgoDateNumPersons = $NumPersons_data["SumNumPersons"] ;

                    $dispItm2 = number_format($AgoDateNumPersons) ;
                }
            }
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm2?></td>

            <?
            // 총누계
            $dispItm3 = "-" ;
            if  ($sSingoName != "")
            {
                $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,        ".
                          "       Sum(singo.TotAmount) As SumTotAmount,          ".
                          "       Sum(singo.TotAmountGikum) As SumTotAmountGikum ".
                          "  From ".$sSingoName."   As singo,                    ".
                          "       bas_showroom      As showroom                  ".
                          " Where singo.SingoDate  <= '".$WorkDate."'            ".
                          "   And ".$FilmCond."                                  ".
                          "   And singo.theather = showroom.theather             ".
                          "   And singo.room     = showroom.room                 ".
                          $AddedLoc."                                            " ;
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
                if  ($WorkGubun == 56) // 기타
                {
                    $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                }
                if  ($nFilmTypeNo != "0")
                {
                    $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                }
//eq($sQuery);
                $qrysingo2 = mysql_query($sQuery,$connect) ;
                if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                {
                    $TotNumPersons = $NumPersons_data["SumNumPersons"] ;
                    $TotTotAmount  = $NumPersons_data["SumTotAmount"] ;
                    $TotTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                    $dispItm3 =  number_format($TotNumPersons) ;
                }
            }
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm3?></td>


            <?
            $dispItm4  = number_format($WorkDateTotAmount) ;
            $dispItm43 = number_format($WorkDateTotAmountGikum) ;
            $dispItm5  = number_format($TotTotAmount) ;
            $dispItm53 = number_format($TotTotAmountGikum) ;
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm4?></td>
            <?
            if  ($WorkGubun == 29)
            {
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm43?></td>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm5?></td>
            <?
            }
            else
            {
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm5?></td>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm43?></td>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm53?></td>
            <?
            }
            ?>
       </tr>


       <!----------------------------------------------------------------------------------------------------------->
       <!--
                                         부산 합계 찍기
                                         (200)
                                                                                                                  -->
       <!----------------------------------------------------------------------------------------------------------->

       <tr height=20>
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center>부산</td>

            <?
            // 당일합계
            $dispItm1 = "-" ;
            if  ($sSingoName != "")
            {
                $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,        ".
                          "       Sum(singo.TotAmount) As SumTotAmount,          ".
                          "       Sum(singo.TotAmountGikum) As SumTotAmountGikum ".
                          "  From ".$sSingoName."   As singo,                    ".
                          "       bas_showroom      As showroom                  ".
                          " Where singo.SingoDate  = '".$WorkDate."'             ".
                          "   And ".$FilmCond."                                  ".
                          "   And singo.theather = showroom.theather             ".
                          "   And singo.room     = showroom.room                 ".
                          "   And (singo.Location  = '200'                       ". // 부산
                          "    Or  singo.Location  = '203'                       ". // 통영
                          "    Or  singo.Location  = '600'                       ". // 울산
                          "    Or  singo.Location  = '207'                       ". // 김해
                          "    Or  singo.Location  = '205'                       ". // 진주
                          "    Or  singo.Location  = '208'                       ". // 거제
                          "    Or  singo.Location  = '202'                       ". // 마산
                          "    Or  singo.Location  = '211'                       ". // 사천
                          "    Or  singo.Location  = '212'                       ". // 거창
                          "    Or  singo.Location  = '213'                       ". // 양산
                          "    Or  singo.Location  = '201')                      " ;// 창원
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
                if  ($WorkGubun == 56) // 기타
                {
                    $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                }
                if  ($nFilmTypeNo != "0")
                {
                    $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                }
                $qrysingo2 = mysql_query($sQuery,$connect) ;
                if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                {
                    $WorkDateNumPersons = $NumPersons_data["SumNumPersons"] ;
                    $WorkDateTotAmount  = $NumPersons_data["SumTotAmount"] ;
                    $WorkDateTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                    $dispItm1 = number_format($WorkDateNumPersons) ;
                }
            }
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm1?></td>

            <?
            // 전일합계
            $dispItm2 = "-" ;
            if  ($sSingoName != "")
            {
                $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons   ".
                          "  From ".$sSingoName."   As singo,              ".
                          "       bas_showroom      As showroom            ".
                          " Where singo.SingoDate  = '".$AgoDate."'        ".
                          "   And ".$FilmCond."                            ".
                          "   And singo.theather = showroom.theather       ".
                          "   And singo.room     = showroom.room           ".
                          "   And (singo.Location  = '200'                 ". // 부산
                          "    Or  singo.Location  = '203'                 ". // 통영
                          "    Or  singo.Location  = '600'                 ". // 울산
                          "    Or  singo.Location  = '207'                 ". // 김해
                          "    Or  singo.Location  = '205'                 ". // 진주
                          "    Or  singo.Location  = '208'                 ". // 거제
                          "    Or  singo.Location  = '202'                 ". // 마산
                          "    Or  singo.Location  = '211'                 ". // 사천
                          "    Or  singo.Location  = '212'                 ". // 거창
                          "    Or  singo.Location  = '213'                 ". // 양산
                          "    Or  singo.Location  = '201')                " ;// 창원
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
                if  ($WorkGubun == 56) // 기타
                {
                    $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                }
                if  ($nFilmTypeNo != "0")
                {
                    $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                }
                $qrysingo2 = mysql_query($sQuery,$connect) ;
                if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                {
                    $AgoDateNumPersons = $NumPersons_data["SumNumPersons"] ;

                    $dispItm2 = number_format($AgoDateNumPersons) ;
                }
            }
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm2?></td>

            <?
            // 총누계
            $dispItm3 = "-" ;
            if  ($sSingoName != "")
            {
                $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,        ".
                          "       Sum(singo.TotAmount) As SumTotAmount,          ".
                          "       Sum(singo.TotAmountGikum) As SumTotAmountGikum ".
                          "  From ".$sSingoName."   As singo,                    ".
                          "       bas_showroom      As showroom                  ".
                          " Where singo.SingoDate <= '".$WorkDate."'             ".
                          "   And ".$FilmCond."                                  ".
                          "   And singo.theather = showroom.theather             ".
                          "   And singo.room     = showroom.room                 ".
                          "   And (singo.Location  = '200'                       ". // 부산
                          "    Or  singo.Location  = '203'                       ". // 통영
                          "    Or  singo.Location  = '600'                       ". // 울산
                          "    Or  singo.Location  = '207'                       ". // 김해
                          "    Or  singo.Location  = '205'                       ". // 진주
                          "    Or  singo.Location  = '208'                       ". // 거제
                          "    Or  singo.Location  = '202'                       ". // 마산
                          "    Or  singo.Location  = '211'                       ". // 사천
                          "    Or  singo.Location  = '212'                       ". // 거창
                          "    Or  singo.Location  = '213'                       ". // 양산
                          "    Or  singo.Location  = '201')                      " ; // 창원
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
                if  ($WorkGubun == 56) // 기타
                {
                    $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                }
                if  ($nFilmTypeNo != "0")
                {
                    $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                }
                $qrysingo2 = mysql_query($sQuery,$connect) ;
                if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                {
                    $TotNumPersons = $NumPersons_data["SumNumPersons"] ;
                    $TotTotAmount  = $NumPersons_data["SumTotAmount"] ;
                    $TotTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                    $dispItm3 =  number_format($TotNumPersons) ;
                }
            }
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm3?></td>



            <?
            $dispItm4  = number_format($WorkDateTotAmount) ;
            $dispItm43 = number_format($WorkDateTotAmountGikum) ;
            $dispItm5  = number_format($TotTotAmount) ;
            $dispItm53 = number_format($TotTotAmountGikum) ;
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm4?></td>
            <?
            if  ($WorkGubun == 29)
            {
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm43?></td>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm5?></td>
            <?
            }
            else
            {
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm5?></td>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm43?></td>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm53?></td>
            <?
            }
            ?>
       </tr>

       <!----------------------------------------------------------------------------------------------------------->
       <!--
                                         경강 합계 찍기
                                         (10)
                                                                                                                  -->
       <!----------------------------------------------------------------------------------------------------------->
       <?
       $AddedLoc = "" ;

       $sQuery = "Select * From bas_filmsupplyzoneloc  ".
                 " Where Zone = '10'                   " ;
       $qryzone = mysql_query($sQuery,$connect) ;
       if  ($zone_data = mysql_fetch_array($qryzone))
       {
           $zoneName = "지역명없음" ; // 지역코드로 지역명을 찾는다..

           $sQuery = "Select * From bas_zone  ".
                     " Where Code = '10'      " ;
           $qryzone = mysql_query($sQuery,$connect) ;
           if  ($zone_data = mysql_fetch_array($qryzone))
           {
               $zoneName = $zone_data["Name"] ;
           }

           $AddedLoc = " And " ;

           $sQuery = "Select Location From bas_filmsupplyzoneloc  ".
                     " Where Zone = '10'                          " ;
           $qryzoneloc = mysql_query($sQuery,$connect) ;
           while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
           {
                if  ($AddedLoc == " And ")
                    $AddedLoc .= "( Location = '".$zoneloc_data["Location"]."' "  ;
                else
                    $AddedLoc .= " or Location = '".$zoneloc_data["Location"]."' "  ;
           }
           $AddedLoc .= ")" ;
           ?>

           <tr height=20>
                <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><?=$zoneName?></td>

                <?
                // 당일합계
                $dispItm1 = "-" ;
                if  ($sSingoName != "")
                {
                    $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,        ".
                              "       Sum(singo.TotAmount) As SumTotAmount,          ".
                              "       Sum(singo.TotAmountGikum) As SumTotAmountGikum ".
                              "  From ".$sSingoName."   As singo,                    ".
                              "       bas_showroom      As showroom                  ".
                              " Where singo.SingoDate  = '".$WorkDate."'             ".
                              "   And ".$FilmCond."                                  ".
                              "   And singo.theather = showroom.theather             ".
                              "   And singo.room     = showroom.room                 ".
                              $AddedLoc."                                            " ;
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
                    if  ($WorkGubun == 56) // 기타
                    {
                        $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                    }
                    if  ($nFilmTypeNo != "0")
                    {
                        $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                    }
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                    {
                        $WorkDateNumPersons = $NumPersons_data["SumNumPersons"] ;
                        $WorkDateTotAmount  = $NumPersons_data["SumTotAmount"] ;
                        $WorkDateTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                        $dispItm1 = number_format($WorkDateNumPersons) ;
                    }
                }
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm1?></td>

                <?
                // 전일합계
                $dispItm2 = "-" ;
                if  ($sSingoName != "")
                {
                    $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                              "  From ".$sSingoName."                    ".
                              " Where SingoDate  = '".$AgoDate."'        ".
                              "   And ".$FilmCond."                      ".
                              $AddedLoc."                                " ;
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                    {
                        $AgoDateNumPersons = $NumPersons_data["SumNumPersons"] ;

                        $dispItm2 = number_format($AgoDateNumPersons) ;
                    }
                }
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm2?></td>

                <?
                // 총누계
                $dispItm3 = "-" ;
                if  ($sSingoName != "")
                {
                    $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,        ".
                              "       Sum(singo.TotAmount) As SumTotAmount,          ".
                              "       Sum(singo.TotAmountGikum) As SumTotAmountGikum ".
                              "  From ".$sSingoName."   As singo,                    ".
                              "       bas_showroom      As showroom                  ".
                              " Where singo.SingoDate  <= '".$WorkDate."'            ".
                              "   And ".$FilmCond."                                  ".
                              "   And singo.theather = showroom.theather             ".
                              "   And singo.room     = showroom.room                 ".
                              $AddedLoc."                                            " ;
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
                    if  ($WorkGubun == 56) // 기타
                    {
                        $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                    }
                    if  ($nFilmTypeNo != "0")
                    {
                        $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                    }
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                    {
                        $TotNumPersons = $NumPersons_data["SumNumPersons"] ;
                        $TotTotAmount  = $NumPersons_data["SumTotAmount"] ;
                        $TotTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                        $dispItm3 =  number_format($TotNumPersons) ;
                    }
                }
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm3?></td>

                <?
                $dispItm4  = number_format($WorkDateTotAmount) ;
                $dispItm43 = number_format($WorkDateTotAmountGikum) ;
                $dispItm5  = number_format($TotTotAmount) ;
                $dispItm53 = number_format($TotTotAmountGikum) ;
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm4?></td>
                <?
                if  ($WorkGubun == 29)
                {
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm43?></td>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm5?></td>
                <?
                }
                else
                {
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm5?></td>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm43?></td>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm53?></td>
                <?
                }
                ?>
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
       $sQuery = "Select * From bas_filmsupplyzoneloc  ".
                 " Where Zone = '35'                   " ;
       $qryzone = mysql_query($sQuery,$connect) ;
       if  ($zone_data = mysql_fetch_array($qryzone))
       {
            $sQuery = "Select * From bas_zone  ".
                      " Where Code = '35'      " ;
            $qryzone = mysql_query($sQuery,$connect) ;
            if  ($zone_data = mysql_fetch_array($qryzone))
            {
                $zoneName = $zone_data["Name"] ;
            }

            $AddedLoc = " And " ;

            $sQuery = "Select Location From bas_filmsupplyzoneloc ".
                      " Where Zone = '35'                         " ;
            $qryzoneloc = mysql_query($sQuery,$connect) ;
            while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
            {
                 if  ($AddedLoc == " And ")
                     $AddedLoc .= "( Location = '".$zoneloc_data["Location"]."' "  ;
                 else
                     $AddedLoc .= " or Location = '".$zoneloc_data["Location"]."' "  ;
            }
            $AddedLoc .= ")" ;
            ?>
            <tr height=20>
                <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><?=$zoneName?></td>

                <?
                // 당일합계
                $dispItm1 = "-" ;
                if  ($sSingoName != "")
                {
                    $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,        ".
                              "       Sum(singo.TotAmount) As SumTotAmount,          ".
                              "       Sum(singo.TotAmountGikum) As SumTotAmountGikum ".
                              "  From ".$sSingoName."   As singo,                    ".
                              "       bas_showroom      As showroom                  ".
                              " Where singo.SingoDate  = '".$WorkDate."'             ".
                              "   And ".$FilmCond."                                  ".
                              "   And singo.theather = showroom.theather             ".
                              "   And singo.room     = showroom.room                 ".
                              $AddedLoc."                                            " ;
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
                    if  ($WorkGubun == 56) // 기타
                    {
                        $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                    }
                    if  ($nFilmTypeNo != "0")
                    {
                        $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                    }
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                    {
                        $WorkDateNumPersons = $NumPersons_data["SumNumPersons"] ;
                        $WorkDateTotAmount  = $NumPersons_data["SumTotAmount"] ;
                        $WorkDateTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                        $dispItm1 = number_format($WorkDateNumPersons) ;
                    }
                }
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm1?></td>
                <?

                // 전일합계
                $dispItm2 = "-" ;
                if  ($sSingoName != "")
                {
                    $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons   ".
                              "  From ".$sSingoName."   As singo,              ".
                              "       bas_showroom      As showroom            ".
                              " Where singo.SingoDate  = '".$AgoDate."'        ".
                              "   And ".$FilmCond."                            ".
                              "   And singo.theather = showroom.theather       ".
                              "   And singo.room     = showroom.room           ".
                              $AddedLoc."                                      " ;
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
                    if  ($WorkGubun == 56) // 기타
                    {
                        $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                    }
                    if  ($nFilmTypeNo != "0")
                    {
                        $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                    }
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                    {
                        $AgoDateNumPersons = $NumPersons_data["SumNumPersons"] ;

                        $dispItm2 = number_format($AgoDateNumPersons) ;
                    }
                }
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm2?></td>
                <?
                // 전일대비
                if   ($AgoDateNumPersons>0)
                {
                     $sValue = round((($WorkDateNumPersons - $AgoDateNumPersons)* 100.0) / $AgoDateNumPersons ,2) ;
                     $dispAgoRate = number_format($sValue,2) ;
                }
                else
                {
                     $dispAgoRate = "0.00" ;
                }
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispAgoRate?>%&nbsp;</td>
                <?

                // 총누계
                $dispItm3 = "-" ;
                if  ($sSingoName != "")
                {
                    $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,        ".
                              "       Sum(singo.TotAmount) As SumTotAmount,          ".
                              "       Sum(singo.TotAmountGikum) As SumTotAmountGikum ".
                              "  From ".$sSingoName."   As singo,                    ".
                              "       bas_showroom      As showroom                  ".
                              " Where singo.SingoDate  <= '".$WorkDate."'            ".
                              "   And ".$FilmCond."                                  ".
                              "   And singo.theather = showroom.theather             ".
                              "   And singo.room     = showroom.room                 ".
                              $AddedLoc."                                            " ;
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
                    if  ($WorkGubun == 56) // 기타
                    {
                        $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                    }
                    if  ($nFilmTypeNo != "0")
                    {
                        $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                    }
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                    {
                        $TotNumPersons = $NumPersons_data["SumNumPersons"] ;
                        $TotTotAmount  = $NumPersons_data["SumTotAmount"] ;
                        $TotTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                        $dispItm3 =  number_format($TotNumPersons) ;
                    }

                }
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm3?></td>


                <?
                $dispItm4  = number_format($WorkDateTotAmount) ;
                $dispItm43 = number_format($WorkDateTotAmountGikum) ;
                $dispItm5  = number_format($TotTotAmount) ;
                $dispItm53 = number_format($TotTotAmountGikum) ;
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm4?></td>
                <?
                if  ($WorkGubun == 29)
                {
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm43?></td>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm5?></td>
                <?
                }
                else
                {
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm5?></td>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm43?></td>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm53?></td>
                <?
                }
                ?>
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
       $sQuery = "Select * From bas_filmsupplyzoneloc  ".
                 " Where Zone = '20'                   " ;
       $qryzone = mysql_query($sQuery,$connect) ;
       if  ($zone_data = mysql_fetch_array($qryzone))
       {
           $sQuery = "Select * From bas_zone  ".
                     " Where Code = '20'      " ;
           $qryzone = mysql_query($sQuery,$connect) ;
           if  ($zone_data = mysql_fetch_array($qryzone))
           {
               $zoneName = $zone_data["Name"] ;
           }

           $AddedLoc = " And " ;

           $sQuery = "Select Location From bas_filmsupplyzoneloc  ".
                     " Where Zone = '20'                          " ;
           $qryzoneloc = mysql_query($sQuery,$connect) ;
           while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
           {
                if  ($AddedLoc == " And ")
                    $AddedLoc .= "( Location = '".$zoneloc_data["Location"]."' "  ;
                else
                    $AddedLoc .= " or Location = '".$zoneloc_data["Location"]."' "  ;
           }
           $AddedLoc .= " or Location = '200' "  ; // 경남에 부산도 포함
           $AddedLoc .= " or Location = '203' "  ; // 경남에 통영도 포함
           $AddedLoc .= " or Location = '600' "  ; // 경남에 울산도 포함
           $AddedLoc .= " or Location = '207' "  ; // 경남에 김해도 포함
           $AddedLoc .= " or Location = '205' "  ; // 경남에 진주도 포함
           $AddedLoc .= " or Location = '208' "  ; // 경남에 거제도 포함
           $AddedLoc .= " or Location = '202' "  ; // 경남에 마산도 포함
           $AddedLoc .= " or Location = '211' "  ; // 경남에 사천도 포함
           $AddedLoc .= " or Location = '212' "  ; // 경남에 거창도 포함
           $AddedLoc .= " or Location = '213' "  ; // 경남에 양산도 포함
           $AddedLoc .= " or Location = '201' "  ; // 경남에 창원도 포함
           $AddedLoc .= ")" ;
           ?>

           <tr height=20>
                <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><?=$zoneName?></td>

                <?
                // 당일합계
                $dispItm1 = "-" ;
                if  ($sSingoName != "")
                {
                    $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,        ".
                              "       Sum(singo.TotAmount) As SumTotAmount,          ".
                              "       Sum(singo.TotAmountGikum) As SumTotAmountGikum ".
                              "  From ".$sSingoName."   As singo,                    ".
                              "       bas_showroom      As showroom                  ".
                              " Where singo.SingoDate  = '".$WorkDate."'             ".
                              "   And ".$FilmCond."                                  ".
                              "   And singo.theather = showroom.theather             ".
                              "   And singo.room     = showroom.room                 ".
                              $AddedLoc."                                            " ;
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
                    if  ($WorkGubun == 56) // 기타
                    {
                        $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                    }
                    if  ($nFilmTypeNo != "0")
                    {
                        $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                    }
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                    {
                        $WorkDateNumPersons = $NumPersons_data["SumNumPersons"] ;
                        $WorkDateTotAmount  = $NumPersons_data["SumTotAmount"] ;
                        $WorkDateTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                        $dispItm1 = number_format($WorkDateNumPersons) ;
                    }
                }
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm1?></td>
                <?

                // 전일합계
                $dispItm2 = "-" ;
                if  ($sSingoName != "")
                {
                    $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons   ".
                              "  From ".$sSingoName."   As singo,              ".
                              "       bas_showroom      As showroom            ".
                              " Where singo.SingoDate  = '".$AgoDate."'        ".
                              "   And ".$FilmCond."                            ".
                              "   And singo.theather = showroom.theather       ".
                              "   And singo.room     = showroom.room           ".
                              $AddedLoc."                                      " ;
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
                    if  ($WorkGubun == 56) // 기타
                    {
                        $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                    }
                    if  ($nFilmTypeNo != "0")
                    {
                        $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                    }
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                    {
                        $AgoDateNumPersons = $NumPersons_data["SumNumPersons"] ;

                        $dispItm2 = number_format($AgoDateNumPersons) ;
                    }
                }
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm2?></td>
                <?

                // 총누계
                $dispItm3 = "-" ;
                if  ($sSingoName != "")
                {
                    $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,        ".
                              "       Sum(singo.TotAmount) As SumTotAmount,          ".
                              "       Sum(singo.TotAmountGikum) As SumTotAmountGikum ".
                              "  From ".$sSingoName."   As singo,                    ".
                              "       bas_showroom      As showroom                  ".
                              " Where singo.SingoDate  <= '".$WorkDate."'            ".
                              "   And ".$FilmCond."                                  ".
                              "   And singo.theather = showroom.theather             ".
                              "   And singo.room     = showroom.room                 ".
                              $AddedLoc."                                            " ;
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
                    if  ($WorkGubun == 56) // 기타
                    {
                        $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                    }
                    if  ($nFilmTypeNo != "0")
                    {
                        $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                    }
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                    {
                        $TotNumPersons = $NumPersons_data["SumNumPersons"] ;
                        $TotTotAmount  = $NumPersons_data["SumTotAmount"] ;
                        $TotTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                        $dispItm3 =  number_format($TotNumPersons) ;
                    }
                }
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm3?></td>

                <?
                $dispItm4  = number_format($WorkDateTotAmount) ;
                $dispItm43 = number_format($WorkDateTotAmountGikum) ;
                $dispItm5  = number_format($TotTotAmount) ;
                $dispItm53 = number_format($TotTotAmountGikum) ;
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm4?></td>
                <?
                if  ($WorkGubun == 29)
                {
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm43?></td>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm5?></td>
                <?
                }
                else
                {
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm5?></td>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm43?></td>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm53?></td>
                <?
                }
                ?>
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
       $zoneName = "지역명없음" ; // 지역코드로 지역명을 찾는다..

       $sQuery = "Select * From bas_filmsupplyzoneloc  ".
                 " Where Zone = '21'                   " ;
       $qryzone = mysql_query($sQuery,$connect) ;
       if  ($zone_data = mysql_fetch_array($qryzone))
       {
           $sQuery = "Select * From bas_zone  ".
                     " Where Code = '21'      " ;
           $qryzone = mysql_query($sQuery,$connect) ;
           if  ($zone_data = mysql_fetch_array($qryzone))
           {
               $zoneName = $zone_data["Name"] ;
           }

           $AddedLoc = " And " ;

           $sQuery = "Select  Location From bas_filmsupplyzoneloc ".
                     " Where Zone = '21'                          " ;
           $qryzoneloc = mysql_query($sQuery,$connect) ;
           while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
           {
                if  ($AddedLoc == " And ")
                    $AddedLoc .= "( Location = '".$zoneloc_data["Location"]."' "  ;
                else
                    $AddedLoc .= " or Location = '".$zoneloc_data["Location"]."' "  ;
           }
           $AddedLoc .= ")" ;
           ?>

           <tr height=20>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> class=tbltitle align=center><?=$zoneName?></td>

                <?
                // 당일합계
                $dispItm1 = "-" ;
                if  ($sSingoName != "")
                {
                    $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,        ".
                              "       Sum(singo.TotAmount) As SumTotAmount,          ".
                              "       Sum(singo.TotAmountGikum) As SumTotAmountGikum ".
                              "  From ".$sSingoName."   As singo,                    ".
                              "       bas_showroom      As showroom                  ".
                              " Where singo.SingoDate  = '".$WorkDate."'             ".
                              "   And ".$FilmCond."                                  ".
                              "   And singo.theather = showroom.theather             ".
                              "   And singo.room     = showroom.room                 ".
                              $AddedLoc."                                            " ;
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
                    if  ($WorkGubun == 56) // 기타
                    {
                        $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                    }
                    if  ($nFilmTypeNo != "0")
                    {
                        $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                    }
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                    {
                        $WorkDateNumPersons = $NumPersons_data["SumNumPersons"] ;
                        $WorkDateTotAmount  = $NumPersons_data["SumTotAmount"] ;
                        $WorkDateTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                        $dispItm1 = number_format($WorkDateNumPersons) ;
                    }
                }
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm1?></td>
                <?

                // 전일합계
                $dispItm2 = "-" ;
                if  ($sSingoName != "")
                {
                    $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons   ".
                              "  From ".$sSingoName."   As singo,              ".
                              "       bas_showroom      As showroom            ".
                              " Where singo.SingoDate  = '".$AgoDate."'        ".
                              "   And ".$FilmCond."                            ".
                              "   And singo.theather = showroom.theather       ".
                              "   And singo.room     = showroom.room           ".
                              $AddedLoc."                                      " ;
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
                    if  ($WorkGubun == 56) // 기타
                    {
                        $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                    }
                    if  ($nFilmTypeNo != "0")
                    {
                        $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                    }
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                    {
                        $AgoDateNumPersons = $NumPersons_data["SumNumPersons"] ;

                        $dispItm2 = number_format($AgoDateNumPersons) ;
                    }
                }
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm2?></td>

                <?
                // 전일대비
                if   ($AgoDateNumPersons>0)
                {
                     $sValue = round((($WorkDateNumPersons - $AgoDateNumPersons)* 100.0) / $AgoDateNumPersons ,2) ;
                     $dispAgoRate = number_format($sValue,2) ;
                }
                else
                {
                     $dispAgoRate = "0.00" ;
                }
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispAgoRate?>%&nbsp;</td>
                <?

                // 총누계
                $dispItm3 = "-" ;
                if  ($sSingoName != "")
                {
                    $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,        ".
                              "       Sum(singo.TotAmount) As SumTotAmount,          ".
                              "       Sum(singo.TotAmountGikum) As SumTotAmountGikum ".
                              "  From ".$sSingoName."   As singo,                    ".
                              "       bas_showroom      As showroom                  ".
                              " Where singo.SingoDate  <= '".$WorkDate."'            ".
                              "   And ".$FilmCond."                                  ".
                              "   And singo.theather = showroom.theather             ".
                              "   And singo.room     = showroom.room                 ".
                              $AddedLoc."                                            " ;
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
                    if  ($WorkGubun == 56) // 기타
                    {
                        $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                    }
                    if  ($nFilmTypeNo != "0")
                    {
                        $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                    }
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                    {
                        $TotNumPersons = $NumPersons_data["SumNumPersons"] ;
                        $TotTotAmount  = $NumPersons_data["SumTotAmount"] ;
                        $TotTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                        $dispItm3 =  number_format($TotNumPersons) ;
                    }
                }
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm3?></td>

                <?
                $dispItm4  = number_format($WorkDateTotAmount) ;
                $dispItm43 = number_format($WorkDateTotAmountGikum) ;
                $dispItm5  = number_format($TotTotAmount) ;
                $dispItm53 = number_format($TotTotAmountGikum) ;
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm4?></td>
                <?
                if  ($WorkGubun == 29)
                {
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm43?></td>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm5?></td>
                <?
                }
                else
                {
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm5?></td>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm43?></td>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm53?></td>
                <?
                }
                ?>

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
       $sQuery = "Select * From bas_filmsupplyzoneloc  ".
                 " Where Zone = '50'                   " ;
       $qryzone = mysql_query($sQuery,$connect) ;
       if  ($zone_data = mysql_fetch_array($qryzone))
       {
           $sQuery = "Select * From bas_zone   ".
                     " Where Code = '50'       " ;
           $qryzone = mysql_query($sQuery,$connect) ;
           if  ($zone_data = mysql_fetch_array($qryzone))
           {
               $zoneName = $zone_data["Name"] ;
           }

           $AddedLoc = " And " ;

           $sQuery = "Select  Location From bas_filmsupplyzoneloc ".
                     " Where Zone = '50'                          " ;
           $qryzoneloc = mysql_query($sQuery,$connect) ;
           while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
           {
                if  ($AddedLoc == " And ")
                    $AddedLoc .= "( Location = '".$zoneloc_data["Location"]."' "  ;
                else
                    $AddedLoc .= " or Location = '".$zoneloc_data["Location"]."' "  ;
           }
           $AddedLoc .= ")" ;
           ?>

           <tr height=20>
                <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><?=$zoneName?></td>

                <?
                // 당일합계
                $dispItm1 = "-" ;
                if  ($sSingoName != "")
                {
                    $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,        ".
                              "       Sum(singo.TotAmount) As SumTotAmount,          ".
                              "       Sum(singo.TotAmountGikum) As SumTotAmountGikum ".
                              "  From ".$sSingoName."   As singo,                    ".
                              "       bas_showroom      As showroom                  ".
                              " Where singo.SingoDate  = '".$WorkDate."'             ".
                              "   And ".$FilmCond."                                  ".
                              "   And singo.theather = showroom.theather             ".
                              "   And singo.room     = showroom.room                 ".
                              $AddedLoc."                                            " ;
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
                    if  ($WorkGubun == 56) // 기타
                    {
                        $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                    }
                    if  ($nFilmTypeNo != "0")
                    {
                        $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                    }
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                    {
                        $WorkDateNumPersons = $NumPersons_data["SumNumPersons"] ;
                        $WorkDateTotAmount  = $NumPersons_data["SumTotAmount"] ;
                        $WorkDateTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                        $dispItm1 = number_format($WorkDateNumPersons) ;
                    }
                }
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm1?></td>

                <?
                // 전일합계
                $dispItm2 = "-" ;
                if  ($sSingoName != "")
                {
                    $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons   ".
                              "  From ".$sSingoName."   As singo,              ".
                              "       bas_showroom      As showroom            ".
                              " Where singo.SingoDate  = '".$AgoDate."'        ".
                              "   And ".$FilmCond."                            ".
                              "   And singo.theather = showroom.theather       ".
                              "   And singo.room     = showroom.room           ".
                              $AddedLoc."                                      " ;
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
                    if  ($WorkGubun == 56) // 기타
                    {
                        $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                    }
                    if  ($nFilmTypeNo != "0")
                    {
                        $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                    }
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                    {
                        $AgoDateNumPersons = $NumPersons_data["SumNumPersons"] ;

                        $dispItm2 = number_format($AgoDateNumPersons) ;
                    }
                }
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm2?></td>
                <?

                // 총누계
                $dispItm3 = "-" ;
                if  ($sSingoName != "")
                {
                    $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,        ".
                              "       Sum(singo.TotAmount) As SumTotAmount,          ".
                              "       Sum(singo.TotAmountGikum) As SumTotAmountGikum ".
                              "  From ".$sSingoName."   As singo,                    ".
                              "       bas_showroom      As showroom                  ".
                              " Where singo.SingoDate  <= '".$WorkDate."'            ".
                              "   And ".$FilmCond."                                  ".
                              "   And singo.theather = showroom.theather             ".
                              "   And singo.room     = showroom.room                 ".
                              $AddedLoc."                                            " ;
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
                    if  ($WorkGubun == 56) // 기타
                    {
                        $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                    }
                    if  ($nFilmTypeNo != "0")
                    {
                        $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                    }
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                    {
                        $TotNumPersons = $NumPersons_data["SumNumPersons"] ;
                        $TotTotAmount  = $NumPersons_data["SumTotAmount"] ;
                        $TotTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                        $dispItm3 =  number_format($TotNumPersons) ;
                    }
                }
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm3?></td>

                <?
                $dispItm4  = number_format($WorkDateTotAmount) ;
                $dispItm43 = number_format($WorkDateTotAmountGikum) ;
                $dispItm5  = number_format($TotTotAmount) ;
                $dispItm53 = number_format($TotTotAmountGikum) ;
                ?>
                <td class=textarea bgcolor=<?=$ColorC?> align=right><?=$dispItm4?></td>
                <?
                if  ($WorkGubun == 29)
                {
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm43?></td>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm5?></td>
                <?
                }
                else
                {
                ?>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm5?></td>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm43?></td>
                <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm53?></td>
                <?
                }
                ?>
           </tr>
           <?
       }
       ?>

       <!----------------------------------------------------------------------------------------------------------->
       <!--
                                         지방 합계 찍기
                                         (예상인터네셔널 )
                                                                                                                  -->
       <!----------------------------------------------------------------------------------------------------------->
       <?


       // 경기
       $AddedLoc = " And " ;

       $sQuery = "Select Location From bas_filmsupplyzoneloc ".
                 " Where Zone = '04'                         " ;
       $qryzoneloc = mysql_query($sQuery,$connect) ;
       while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
       {
            if  ($AddedLoc == " And ")
                $AddedLoc .= "( singo.Location <> '".$zoneloc_data["Location"]."' "  ;
            else
                $AddedLoc .= " and singo.Location <> '".$zoneloc_data["Location"]."' "  ;
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

       // 경기 + 서울 + 부산 을 제외한 나머지를 지방으로 한다.
       ?>

       <tr height=20>
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center>지방</td>

            <?
            // 당일합계
            $dispItm1 = "-" ;
            if  ($sSingoName != "")
            {
                $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,        ".
                          "       Sum(singo.TotAmount) As SumTotAmount,          ".
                          "       Sum(singo.TotAmountGikum) As SumTotAmountGikum ".
                          "  From ".$sSingoName."   As singo,                    ".
                          "       bas_showroom      As showroom                  ".
                          " Where singo.SingoDate  = '".$WorkDate."'             ".
                          "   And ".$FilmCond."                                  ".
                          "   And singo.theather = showroom.theather             ".
                          "   And singo.room     = showroom.room                 ".
                          $AddedLoc."                                            " ;
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
                if  ($WorkGubun == 56) // 기타
                {
                    $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                }
                if  ($nFilmTypeNo != "0")
                {
                    $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                }
                $qrysingo2 = mysql_query($sQuery,$connect) ;
                if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                {
                    $WorkDateNumPersons = $NumPersons_data["SumNumPersons"] ;
                    $WorkDateTotAmount  = $NumPersons_data["SumTotAmount"] ;
                    $WorkDateTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                    $dispItm1 = number_format($WorkDateNumPersons) ;
                }
            }
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm1?></td>

            <?
            // 전일합계
            $dispItm2 = "-" ;
            if  ($sSingoName != "")
            {
                $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons   ".
                          "  From ".$sSingoName."   As singo,              ".
                          "       bas_showroom      As showroom            ".
                          " Where singo.SingoDate  = '".$AgoDate."'        ".
                          "   And ".$FilmCond."                            ".
                          "   And singo.theather = showroom.theather       ".
                          "   And singo.room     = showroom.room           ".
                          $AddedLoc."                                      " ;
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
                if  ($WorkGubun == 56) // 기타
                {
                    $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                }
                if  ($nFilmTypeNo != "0")
                {
                    $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                }
                $qrysingo2 = mysql_query($sQuery,$connect) ;
                if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                {
                    $AgoDateNumPersons = $NumPersons_data["SumNumPersons"] ;

                    $dispItm2 = number_format($AgoDateNumPersons) ;
                }
            }
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm2?></td>

            <?
            // 총누계
            $dispItm3 = "-" ;
            if  ($sSingoName != "")
            {
                $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,        ".
                          "       Sum(singo.TotAmount) As SumTotAmount,          ".
                          "       Sum(singo.TotAmountGikum) As SumTotAmountGikum ".
                          "  From ".$sSingoName."   As singo,                    ".
                          "       bas_showroom      As showroom                  ".
                          " Where singo.SingoDate  <= '".$WorkDate."'            ".
                          "   And ".$FilmCond."                                  ".
                          "   And singo.theather = showroom.theather             ".
                          "   And singo.room     = showroom.room                 ".
                          $AddedLoc."                                            " ;
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
                if  ($WorkGubun == 56) // 기타
                {
                    $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                }
                if  ($nFilmTypeNo != "0")
                {
                    $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                }
                $qrysingo2 = mysql_query($sQuery,$connect) ;
                if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                {
                    $TotNumPersons = $NumPersons_data["SumNumPersons"] ;
                    $TotTotAmount  = $NumPersons_data["SumTotAmount"] ;
                    $TotTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                    $dispItm3 =  number_format($TotNumPersons) ;
                }
            }
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm3?></td>

            <?
            $dispItm4  = number_format($WorkDateTotAmount) ;
            $dispItm43 = number_format($WorkDateTotAmountGikum) ;
            $dispItm5  = number_format($TotTotAmount) ;
            $dispItm53 = number_format($TotTotAmountGikum) ;
            ?>
            <td class=textarea bgcolor=<?=$ColorC?> align=right><?=$dispItm4?></td>
            <?
            if  ($WorkGubun == 29)
            {
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm43?></td>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm5?></td>
            <?
            }
            else
            {
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm5?></td>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm43?></td>
            <td class="textarea topsum_val" bgcolor=<?=$ColorC?> align=right><?=$dispItm53?></td>
            <?
            }
            ?>

       </tr>



       <!----------------------------------------------------------------------------------------------------------->
       <!--
                                         총합계 찍기

                                                                                                                  -->
       <!----------------------------------------------------------------------------------------------------------->
       <tr height=20>
            <td class=textarea bgcolor=<?=$ColorD?> class=tbltitle align=center>총 합계</td>

            <?
            // 당일합계
            $dispItm1 = "-" ;
            if  ($sSingoName != "")
            {
                $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,        ".
                          "       Sum(singo.TotAmount) As SumTotAmount,          ".
                          "       Sum(singo.TotAmountGikum) As SumTotAmountGikum ".
                          "  From ".$sSingoName."   As singo,                    ".
                          "       bas_showroom      As showroom                  ".
                          " Where singo.SingoDate  = '".$WorkDate."'             ".
                          "   And singo.theather = showroom.theather             ".
                          "   And singo.room     = showroom.room                 ".
                          "   And ".$FilmCond."                                  " ;
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
                if  ($WorkGubun == 56) // 기타
                {
                    $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                }
                if  ($nFilmTypeNo != "0")
                {
                    $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                }
                $qrysingo2 = mysql_query($sQuery,$connect) ;
                if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                {
                    $WorkDateNumPersons = $NumPersons_data["SumNumPersons"] ;
                    $WorkDateTotAmount  = $NumPersons_data["SumTotAmount"] ;
                    $WorkDateTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                    $dispItm1 = number_format($WorkDateNumPersons) ;
                }
            }
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorD?> align=right><?=$dispItm1?></td>

            <?

            // 전일합계
            $dispItm2 = "-" ;
            if  ($sSingoName != "")
            {
                $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons   ".
                          "  From ".$sSingoName."   As singo,              ".
                          "       bas_showroom      As showroom            ".
                          " Where singo.SingoDate  = '".$AgoDate."'        ".
                          "   And singo.theather = showroom.theather       ".
                          "   And singo.room     = showroom.room           ".
                          "   And ".$FilmCond."                            " ;
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
                if  ($WorkGubun == 56) // 기타
                {
                    $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                }
                if  ($nFilmTypeNo != "0")
                {
                    $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                }
                $qrysingo2 = mysql_query($sQuery,$connect) ;
                if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                {
                    $AgoDateNumPersons = $NumPersons_data["SumNumPersons"] ;

                    $dispItm2 = number_format($AgoDateNumPersons) ;
                }
            }
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorD?> align=right><?=$dispItm2?></td>

            <?
            // 총누계
            $dispItm3 = "-" ;
            if  ($sSingoName != "")
            {
                $sQuery = "Select Sum(singo.NumPersons) As SumNumPersons,        ".
                          "       Sum(singo.TotAmount) As SumTotAmount,          ".
                          "       Sum(singo.TotAmountGikum) As SumTotAmountGikum ".
                          "  From ".$sSingoName."   As singo,                    ".
                          "       bas_showroom      As showroom                  ".
                          " Where singo.SingoDate <= '".$WorkDate."'             ".
                          "   And singo.theather = showroom.theather             ".
                          "   And singo.room     = showroom.room                 ".
                          "   And ".$FilmCond."                                  " ;
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
                if  ($WorkGubun == 56) // 기타
                {
                    $sQuery .= " And (showroom.MultiPlex <> '4' and showroom.MultiPlex <> '5' and showroom.MultiPlex <> '3' and showroom.MultiPlex <> '2') " ;
                }
                if  ($nFilmTypeNo != "0")
                {
                    $sQuery .= " And singo.FilmType  = '".$nFilmTypeNo."' " ;
                }
                $qrysingo2 = mysql_query($sQuery,$connect) ;
                if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
                {
                    $TotNumPersons = $NumPersons_data["SumNumPersons"] ;
                    $TotTotAmount  = $NumPersons_data["SumTotAmount"] ;
                    $TotTotAmountGikum  = $NumPersons_data["SumTotAmountGikum"] ;

                    $dispItm3 =  number_format($TotNumPersons) ;
                }
            }
            ?>
            <td class=textarea bgcolor=<?=$ColorD?> align=right><?=$dispItm3?></td>


            <?
            $dispItm4  = number_format($WorkDateTotAmount) ;
            $dispItm43 = number_format($WorkDateTotAmountGikum) ;
            $dispItm5  = number_format($TotTotAmount) ;
            $dispItm53 = number_format($TotTotAmountGikum) ;
            ?>
            <td class=textarea bgcolor=<?=$ColorD?> align=right><?=$dispItm4?></td>
            <?
            if  ($WorkGubun == 29)
            {
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorD?> align=right><?=$dispItm43?></td>
            <td class="textarea topsum_val" bgcolor=<?=$ColorD?> align=right><?=$dispItm5?></td>
            <?
            }
            else
            {
            ?>
            <td class="textarea topsum_val" bgcolor=<?=$ColorD?> align=right><?=$dispItm5?></td>
            <td class="textarea topsum_val" bgcolor=<?=$ColorD?> align=right><?=$dispItm43?></td>
            <td class="textarea topsum_val" bgcolor=<?=$ColorD?> align=right><?=$dispItm53?></td>
            <?
            }
            ?>
       </tr>
    </table>
