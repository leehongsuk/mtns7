    <?
    if   ($FilmCode == '00') // �и��ȿ�ȭ�������ڵ�
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


    // ����ݾ� ���� ���Ѵ�. (�Ű�����, ��޻�) // ��ȭ �� �������� Ȯ�ο�

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
                                         Ÿ��Ʋ ���

                                                                                                                  -->
       <!----------------------------------------------------------------------------------------------------------->
       <tr height=25>
             <td class=textarea bgcolor=<?=$ColorA?> align=center>����</td>
             <td class=textarea bgcolor=<?=$ColorA?> align=center>&nbsp;�����հ�&nbsp;</td>
             <td class=textarea bgcolor=<?=$ColorA?> align=center>&nbsp;�����հ�&nbsp;</td>
             <td class=textarea bgcolor=<?=$ColorA?> align=center>&nbsp;�Ѵ���&nbsp;</td>
             <td class=textarea bgcolor=<?=$ColorA?> align=center>&nbsp;���ϱݾ�&nbsp;</td>
             <?
             if  ($WorkGubun == 29)
             {
             ?>
             <td class="textarea topsum_col_2" bgcolor=<?=$ColorA?> align=center>�������<?if  (!$ToExel) { ?><br><? } else { echo "\n" ; }?>���ϱݾ�</td>
             <td class="textarea topsum_col_3"  bgcolor=<?=$ColorA?> align=center>����ݾ�</td>
             <?
             }
             else
             {
             ?>
             <td class="textarea topsum_col_1"  bgcolor=<?=$ColorA?> align=center>����ݾ�</td>
             <td class="textarea topsum_col_2"  bgcolor=<?=$ColorA?> align=center>�������<?if  (!$ToExel) { ?><br><? } else { echo "\n" ; }?>���ϱݾ�</td>
             <td class="textarea topsum_col_3"  bgcolor=<?=$ColorA?> align=center>�������<?if  (!$ToExel) { ?><br><? } else { echo "\n" ; } ?>����ݾ�</td>
             <?
             }
             ?>
       </tr>


       <!----------------------------------------------------------------------------------------------------------->
       <!--
                                         ���� �հ� ���
                                         (100)
                                                                                                                  -->
       <!----------------------------------------------------------------------------------------------------------->
       <tr height=20>
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center>����</td>

            <?
            // �����հ�

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
                          "   And singo.Location   = '100'                  " ; // ���⼭ �ϰ� ����..
                if  ($WorkGubun == 28)
                {
                    $sQuery .= " And singo.Silmooja = '777777' " ;
                }
                if  ($WorkGubun == 33)
                {
                    $sQuery .= " And singo.Silmooja = '555595' " ;
                }
                if  ($WorkGubun == 34) // ���ʽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '4' " ;
                }
                if  ($WorkGubun == 37) // �Ե����׸�
                {
                    $sQuery .= " And showroom.MultiPlex  = '5' " ;
                }
                if  ($WorkGubun == 39) // �ް��ڽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '3' " ;
                }
                if  ($WorkGubun == 56) // ��Ÿ
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
            // �����հ�
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
                if  ($WorkGubun == 34) // ���ʽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '4' " ;
                }
                if  ($WorkGubun == 37) // �Ե����׸�
                {
                    $sQuery .= " And showroom.MultiPlex  = '5' " ;
                }
                if  ($WorkGubun == 39) // �ް��ڽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '3' " ;
                }
                if  ($WorkGubun == 56) // ��Ÿ
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
            // �Ѵ���
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
                if  ($WorkGubun == 34) // ���ʽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '4' " ;
                }
                if  ($WorkGubun == 37) // �Ե����׸�
                {
                    $sQuery .= " And showroom.MultiPlex  = '5' " ;
                }
                if  ($WorkGubun == 39) // �ް��ڽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '3' " ;
                }
                if  ($WorkGubun == 56) // ��Ÿ
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

                                         ��� �հ� ���
                                         (04)
                                                                                                                  -->
       <!----------------------------------------------------------------------------------------------------------->
       <?


       $zoneName = "���������" ; // �����ڵ�� �������� ã�´�..
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
            // �����հ�
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
                if  ($WorkGubun == 34) // ���ʽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '4' " ;
                }
                if  ($WorkGubun == 37) // �Ե����׸�
                {
                    $sQuery .= " And showroom.MultiPlex  = '5' " ;
                }
                if  ($WorkGubun == 39) // �ް��ڽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '3' " ;
                }
                if  ($WorkGubun == 56) // ��Ÿ
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
            // �����հ�
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
                if  ($WorkGubun == 34) // ���ʽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '4' " ;
                }
                if  ($WorkGubun == 37) // �Ե����׸�
                {
                    $sQuery .= " And showroom.MultiPlex  = '5' " ;
                }
                if  ($WorkGubun == 39) // �ް��ڽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '3' " ;
                }
                if  ($WorkGubun == 56) // ��Ÿ
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
            // �Ѵ���
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
                if  ($WorkGubun == 34) // ���ʽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '4' " ;
                }
                if  ($WorkGubun == 37) // �Ե����׸�
                {
                    $sQuery .= " And showroom.MultiPlex  = '5' " ;
                }
                if  ($WorkGubun == 39) // �ް��ڽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '3' " ;
                }
                if  ($WorkGubun == 56) // ��Ÿ
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
                                         �λ� �հ� ���
                                         (200)
                                                                                                                  -->
       <!----------------------------------------------------------------------------------------------------------->

       <tr height=20>
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center>�λ�</td>

            <?
            // �����հ�
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
                          "   And (singo.Location  = '200'                       ". // �λ�
                          "    Or  singo.Location  = '203'                       ". // �뿵
                          "    Or  singo.Location  = '600'                       ". // ���
                          "    Or  singo.Location  = '207'                       ". // ����
                          "    Or  singo.Location  = '205'                       ". // ����
                          "    Or  singo.Location  = '208'                       ". // ����
                          "    Or  singo.Location  = '202'                       ". // ����
                          "    Or  singo.Location  = '211'                       ". // ��õ
                          "    Or  singo.Location  = '212'                       ". // ��â
                          "    Or  singo.Location  = '213'                       ". // ���
                          "    Or  singo.Location  = '201')                      " ;// â��
                if  ($WorkGubun == 28)
                {
                    $sQuery .= " And singo.Silmooja = '777777' " ;
                }
                if  ($WorkGubun == 33)
                {
                    $sQuery .= " And singo.Silmooja = '555595' " ;
                }
                if  ($WorkGubun == 34) // ���ʽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '4' " ;
                }
                if  ($WorkGubun == 37) // �Ե����׸�
                {
                    $sQuery .= " And showroom.MultiPlex  = '5' " ;
                }
                if  ($WorkGubun == 39) // �ް��ڽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '3' " ;
                }
                if  ($WorkGubun == 56) // ��Ÿ
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
            // �����հ�
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
                          "   And (singo.Location  = '200'                 ". // �λ�
                          "    Or  singo.Location  = '203'                 ". // �뿵
                          "    Or  singo.Location  = '600'                 ". // ���
                          "    Or  singo.Location  = '207'                 ". // ����
                          "    Or  singo.Location  = '205'                 ". // ����
                          "    Or  singo.Location  = '208'                 ". // ����
                          "    Or  singo.Location  = '202'                 ". // ����
                          "    Or  singo.Location  = '211'                 ". // ��õ
                          "    Or  singo.Location  = '212'                 ". // ��â
                          "    Or  singo.Location  = '213'                 ". // ���
                          "    Or  singo.Location  = '201')                " ;// â��
                if  ($WorkGubun == 28)
                {
                    $sQuery .= " And singo.Silmooja = '777777' " ;
                }
                if  ($WorkGubun == 33)
                {
                    $sQuery .= " And singo.Silmooja = '555595' " ;
                }
                if  ($WorkGubun == 34) // ���ʽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '4' " ;
                }
                if  ($WorkGubun == 37) // �Ե����׸�
                {
                    $sQuery .= " And showroom.MultiPlex  = '5' " ;
                }
                if  ($WorkGubun == 39) // �ް��ڽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '3' " ;
                }
                if  ($WorkGubun == 56) // ��Ÿ
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
            // �Ѵ���
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
                          "   And (singo.Location  = '200'                       ". // �λ�
                          "    Or  singo.Location  = '203'                       ". // �뿵
                          "    Or  singo.Location  = '600'                       ". // ���
                          "    Or  singo.Location  = '207'                       ". // ����
                          "    Or  singo.Location  = '205'                       ". // ����
                          "    Or  singo.Location  = '208'                       ". // ����
                          "    Or  singo.Location  = '202'                       ". // ����
                          "    Or  singo.Location  = '211'                       ". // ��õ
                          "    Or  singo.Location  = '212'                       ". // ��â
                          "    Or  singo.Location  = '213'                       ". // ���
                          "    Or  singo.Location  = '201')                      " ; // â��
                if  ($WorkGubun == 28)
                {
                    $sQuery .= " And singo.Silmooja = '777777' " ;
                }
                if  ($WorkGubun == 33)
                {
                    $sQuery .= " And singo.Silmooja = '555595' " ;
                }
                if  ($WorkGubun == 34) // ���ʽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '4' " ;
                }
                if  ($WorkGubun == 37) // �Ե����׸�
                {
                    $sQuery .= " And showroom.MultiPlex  = '5' " ;
                }
                if  ($WorkGubun == 39) // �ް��ڽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '3' " ;
                }
                if  ($WorkGubun == 56) // ��Ÿ
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
                                         �氭 �հ� ���
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
           $zoneName = "���������" ; // �����ڵ�� �������� ã�´�..

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
                // �����հ�
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
                    if  ($WorkGubun == 34) // ���ʽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '4' " ;
                    }
                    if  ($WorkGubun == 37) // �Ե����׸�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '5' " ;
                    }
                    if  ($WorkGubun == 39) // �ް��ڽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '3' " ;
                    }
                    if  ($WorkGubun == 56) // ��Ÿ
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
                // �����հ�
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
                // �Ѵ���
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
                    if  ($WorkGubun == 34) // ���ʽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '4' " ;
                    }
                    if  ($WorkGubun == 37) // �Ե����׸�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '5' " ;
                    }
                    if  ($WorkGubun == 39) // �ް��ڽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '3' " ;
                    }
                    if  ($WorkGubun == 56) // ��Ÿ
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
                                         ��û �հ� ���
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
                // �����հ�
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
                    if  ($WorkGubun == 34) // ���ʽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '4' " ;
                    }
                    if  ($WorkGubun == 37) // �Ե����׸�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '5' " ;
                    }
                    if  ($WorkGubun == 39) // �ް��ڽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '3' " ;
                    }
                    if  ($WorkGubun == 56) // ��Ÿ
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

                // �����հ�
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
                    if  ($WorkGubun == 34) // ���ʽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '4' " ;
                    }
                    if  ($WorkGubun == 37) // �Ե����׸�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '5' " ;
                    }
                    if  ($WorkGubun == 39) // �ް��ڽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '3' " ;
                    }
                    if  ($WorkGubun == 56) // ��Ÿ
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
                // ���ϴ��
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

                // �Ѵ���
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
                    if  ($WorkGubun == 34) // ���ʽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '4' " ;
                    }
                    if  ($WorkGubun == 37) // �Ե����׸�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '5' " ;
                    }
                    if  ($WorkGubun == 39) // �ް��ڽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '3' " ;
                    }
                    if  ($WorkGubun == 56) // ��Ÿ
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
                                         �泲 �հ� ���
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
           $AddedLoc .= " or Location = '200' "  ; // �泲�� �λ굵 ����
           $AddedLoc .= " or Location = '203' "  ; // �泲�� �뿵�� ����
           $AddedLoc .= " or Location = '600' "  ; // �泲�� ��굵 ����
           $AddedLoc .= " or Location = '207' "  ; // �泲�� ���ص� ����
           $AddedLoc .= " or Location = '205' "  ; // �泲�� ���ֵ� ����
           $AddedLoc .= " or Location = '208' "  ; // �泲�� ������ ����
           $AddedLoc .= " or Location = '202' "  ; // �泲�� ���굵 ����
           $AddedLoc .= " or Location = '211' "  ; // �泲�� ��õ�� ����
           $AddedLoc .= " or Location = '212' "  ; // �泲�� ��â�� ����
           $AddedLoc .= " or Location = '213' "  ; // �泲�� ��굵 ����
           $AddedLoc .= " or Location = '201' "  ; // �泲�� â���� ����
           $AddedLoc .= ")" ;
           ?>

           <tr height=20>
                <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><?=$zoneName?></td>

                <?
                // �����հ�
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
                    if  ($WorkGubun == 34) // ���ʽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '4' " ;
                    }
                    if  ($WorkGubun == 37) // �Ե����׸�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '5' " ;
                    }
                    if  ($WorkGubun == 39) // �ް��ڽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '3' " ;
                    }
                    if  ($WorkGubun == 56) // ��Ÿ
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

                // �����հ�
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
                    if  ($WorkGubun == 34) // ���ʽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '4' " ;
                    }
                    if  ($WorkGubun == 37) // �Ե����׸�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '5' " ;
                    }
                    if  ($WorkGubun == 39) // �ް��ڽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '3' " ;
                    }
                    if  ($WorkGubun == 56) // ��Ÿ
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

                // �Ѵ���
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
                    if  ($WorkGubun == 34) // ���ʽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '4' " ;
                    }
                    if  ($WorkGubun == 37) // �Ե����׸�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '5' " ;
                    }
                    if  ($WorkGubun == 39) // �ް��ڽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '3' " ;
                    }
                    if  ($WorkGubun == 56) // ��Ÿ
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
                                         ��� �հ� ���
                                         (21)
                                                                                                                  -->
       <!----------------------------------------------------------------------------------------------------------->
       <?
       $zoneName = "���������" ; // �����ڵ�� �������� ã�´�..

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
                // �����հ�
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
                    if  ($WorkGubun == 34) // ���ʽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '4' " ;
                    }
                    if  ($WorkGubun == 37) // �Ե����׸�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '5' " ;
                    }
                    if  ($WorkGubun == 39) // �ް��ڽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '3' " ;
                    }
                    if  ($WorkGubun == 56) // ��Ÿ
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

                // �����հ�
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
                    if  ($WorkGubun == 34) // ���ʽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '4' " ;
                    }
                    if  ($WorkGubun == 37) // �Ե����׸�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '5' " ;
                    }
                    if  ($WorkGubun == 39) // �ް��ڽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '3' " ;
                    }
                    if  ($WorkGubun == 56) // ��Ÿ
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
                // ���ϴ��
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

                // �Ѵ���
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
                    if  ($WorkGubun == 34) // ���ʽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '4' " ;
                    }
                    if  ($WorkGubun == 37) // �Ե����׸�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '5' " ;
                    }
                    if  ($WorkGubun == 39) // �ް��ڽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '3' " ;
                    }
                    if  ($WorkGubun == 56) // ��Ÿ
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
                                         ȣ�� �հ� ���
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
                // �����հ�
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
                    if  ($WorkGubun == 34) // ���ʽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '4' " ;
                    }
                    if  ($WorkGubun == 37) // �Ե����׸�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '5' " ;
                    }
                    if  ($WorkGubun == 39) // �ް��ڽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '3' " ;
                    }
                    if  ($WorkGubun == 56) // ��Ÿ
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
                // �����հ�
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
                    if  ($WorkGubun == 34) // ���ʽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '4' " ;
                    }
                    if  ($WorkGubun == 37) // �Ե����׸�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '5' " ;
                    }
                    if  ($WorkGubun == 39) // �ް��ڽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '3' " ;
                    }
                    if  ($WorkGubun == 56) // ��Ÿ
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

                // �Ѵ���
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
                    if  ($WorkGubun == 34) // ���ʽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '4' " ;
                    }
                    if  ($WorkGubun == 37) // �Ե����׸�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '5' " ;
                    }
                    if  ($WorkGubun == 39) // �ް��ڽ�
                    {
                        $sQuery .= " And showroom.MultiPlex  = '3' " ;
                    }
                    if  ($WorkGubun == 56) // ��Ÿ
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
                                         ���� �հ� ���
                                         (�������ͳ׼ų� )
                                                                                                                  -->
       <!----------------------------------------------------------------------------------------------------------->
       <?


       // ���
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
       $AddedLoc .= " and singo.Location <> '100' "  ; // ����
       $AddedLoc .= " and singo.Location <> '200' "  ; // �λ�
       $AddedLoc .= " and singo.Location <> '203' "  ; // �뿵
       $AddedLoc .= " and singo.Location <> '600' "  ; // ���
       $AddedLoc .= " and singo.Location <> '207' "  ; // ����
       $AddedLoc .= " and singo.Location <> '205' "  ; // ����
       $AddedLoc .= " and singo.Location <> '208' "  ; // ����
       $AddedLoc .= " and singo.Location <> '202' "  ; // ����
       $AddedLoc .= " and singo.Location <> '211' "  ; // ��õ
       $AddedLoc .= " and singo.Location <> '212' "  ; // ��â
       $AddedLoc .= " and singo.Location <> '213' "  ; // ���
       $AddedLoc .= " and singo.Location <> '201' "  ; // â��
       $AddedLoc .= ")" ;

       // ��� + ���� + �λ� �� ������ �������� �������� �Ѵ�.
       ?>

       <tr height=20>
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center>����</td>

            <?
            // �����հ�
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
                if  ($WorkGubun == 34) // ���ʽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '4' " ;
                }
                if  ($WorkGubun == 37) // �Ե����׸�
                {
                    $sQuery .= " And showroom.MultiPlex  = '5' " ;
                }
                if  ($WorkGubun == 39) // �ް��ڽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '3' " ;
                }
                if  ($WorkGubun == 56) // ��Ÿ
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
            // �����հ�
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
                if  ($WorkGubun == 34) // ���ʽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '4' " ;
                }
                if  ($WorkGubun == 37) // �Ե����׸�
                {
                    $sQuery .= " And showroom.MultiPlex  = '5' " ;
                }
                if  ($WorkGubun == 39) // �ް��ڽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '3' " ;
                }
                if  ($WorkGubun == 56) // ��Ÿ
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
            // �Ѵ���
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
                if  ($WorkGubun == 34) // ���ʽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '4' " ;
                }
                if  ($WorkGubun == 37) // �Ե����׸�
                {
                    $sQuery .= " And showroom.MultiPlex  = '5' " ;
                }
                if  ($WorkGubun == 39) // �ް��ڽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '3' " ;
                }
                if  ($WorkGubun == 56) // ��Ÿ
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
                                         ���հ� ���

                                                                                                                  -->
       <!----------------------------------------------------------------------------------------------------------->
       <tr height=20>
            <td class=textarea bgcolor=<?=$ColorD?> class=tbltitle align=center>�� �հ�</td>

            <?
            // �����հ�
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
                if  ($WorkGubun == 34) // ���ʽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '4' " ;
                }
                if  ($WorkGubun == 37) // �Ե����׸�
                {
                    $sQuery .= " And showroom.MultiPlex  = '5' " ;
                }
                if  ($WorkGubun == 39) // �ް��ڽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '3' " ;
                }
                if  ($WorkGubun == 56) // ��Ÿ
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

            // �����հ�
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
                if  ($WorkGubun == 34) // ���ʽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '4' " ;
                }
                if  ($WorkGubun == 37) // �Ե����׸�
                {
                    $sQuery .= " And showroom.MultiPlex  = '5' " ;
                }
                if  ($WorkGubun == 39) // �ް��ڽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '3' " ;
                }
                if  ($WorkGubun == 56) // ��Ÿ
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
            // �Ѵ���
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
                if  ($WorkGubun == 34) // ���ʽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '4' " ;
                }
                if  ($WorkGubun == 37) // �Ե����׸�
                {
                    $sQuery .= " And showroom.MultiPlex  = '5' " ;
                }
                if  ($WorkGubun == 39) // �ް��ڽ�
                {
                    $sQuery .= " And showroom.MultiPlex  = '3' " ;
                }
                if  ($WorkGubun == 56) // ��Ÿ
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
