<?
	$FirstZone  = 0 ;

	$cntLocData = 0 ;

	for ($i=0 ; $i<=($dur_day+6) ; $i++)
	{
       $arrySoGea[$i] = 0 ; // 지역이 끝날때 마다 초기화 한다.
	}
	$nNumSoGea = 0 ; // 극장갯수(소계)

	while ($ArySingo = mysql_fetch_array($QrySingo))
	{
		$singoLocation = $ArySingo["Location"] ;
		$singoTheather = $ArySingo["Theather"] ;
		$singoRoom     = $ArySingo["Room"] ;

		if  ($FilmTile != "")
		{
			$singoOpen = substr($FilmTile,0,6) ;
			$singoFilm = substr($FilmTile,6,2) ;
		}
		else
		{
			$singoOpen = $ArySingo["Open"] ;
			$singoFilm = $ArySingo["Film"] ;
		}

		$sQuery = "Select Theather.Discript,                   ".
                  "       Theather.JikYong,                    ".
                  "       Location.Name                        ".
                  "  From bas_theather As Theather             ".
                  "  left join bas_location As Location        ".
                  "    on  Theather.Location = Location.Code   ".
                  " Where Theather.Code = '".$singoTheather."' " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
        $QryTheather = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
        if  ($AryTheather = mysql_fetch_array($QryTheather))
        {
           $showroomDiscript = $AryTheather["Discript"] ;
           $locationName     = $AryTheather["Name"] ; // 지역명 ..
           $JikYong          = $AryTheather["JikYong"] ;
        }
		?>

		<table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">

		<?
		// --------------------------------------------
		// 가격별 스코어
		// --------------------------------------------

		$FirstPrice = true ;

		for ($i=0 ; $i<=($dur_day+6) ; $i++)
		{
			$arryHapGea[$i]  = 0 ; // 극장별 합계를 극장이 바뀔때 마다 초기화한다.
		}

		$sQuery = "Select UnitPrice,                            ".
                  "       Sum(NumPersons) As AccSumNumPersons,  ".
                  "       Sum(TotAmount) As AccSumNumTotAmount  ".
                  "  From ".$sSingoName."                       ".
                  " Where Theather   = '".$singoTheather."'     ".
                  "   And Open       = '".$singoOpen."'         ".
                  "   And Film       = '".$singoFilm."'         ".
                  " Group By UnitPrice                          ".
                  " Order By UnitPrice desc                     " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
		$QrySingo1 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
		$affected_rows = (mysql_affected_rows()+1) ;

		if  ($affected_rows > 0) // 도착보고가 온전히 된거
		{
			while ($ArySingo1 = mysql_fetch_array($QrySingo1))
			{
				$UnitPrice          = $ArySingo1["UnitPrice"] ;          // 요금대역
				$AccSumNumPersons   = $ArySingo1["AccSumNumPersons"] ;   // 극장별 합계(스코어)
				$AccSumNumTotAmount = $ArySingo1["AccSumNumTotAmount"] ; // 극장별 합계(금액)

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
				if  ($FirstPrice == true)
				{
					$AccNumTotAmount = 0 ;
					$nNumSoGea ++ ;
					?>

					<!-- 지역명 출력 -->
					<td class=textarea bgcolor=<?=$Color1?> rowspan=<?=$affected_rows?> width=50 class=tbltitle align=center>
					<?=$locationName?>
					</td>

					<!-- 상영관명 출력 -->
					<td class=textarea bgcolor=<?=$Color1?> rowspan=<?=$affected_rows?> class=tbltitle width=120 align=center>
					<?=$showroomDiscript?>
					</td>

					<?
					$FirstPrice = false ;
				}
				?>

              <!-- 요금(코드) 출력 -->
              <?
				$sQuery = "Select * From bas_pricescode    ".
                          " Where prices = ".$UnitPrice."  " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
				$QryPricecode = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
				if   ($AryPricecode = mysql_fetch_array($QryPricecode))
				{
					$pricecodecode = $AryPricecode["pcode"]  ;
				}
				else
				{
					$pricecodecode = "&nbsp;" ;
				}
				?>
              <td class=textarea bgcolor=<?=$Color1?> width=30 align=center><?=$pricecodecode?>&nbsp;</td>

              <!-- 요금(타이틀) 출력 -->
              <td class=textarea bgcolor=<?=$Color1?> width=50 align=right><?=number_format($UnitPrice)?>&nbsp;</td>


              <?
				$HapNumPersons         = 0 ;
				$HapTotAmount          = 0 ;
				$HapTotAmountGikum     = 0 ;
				$HapTotAmountGikumRate = 0 ;

				if  ($nFilmTypeNo != 0) // All이 아닐때//.
				{
                  // 일자별로 스코어를 구한다.
                  $sQuery = "Select SingoDate,                               ".
                            "       Sum(NumPersons) As SumNumPersons,        ".
                            "       Sum(TotAmount)  As SumTotAmount,         ".
                            "       Sum(TotAmountGikum) AS SumTotAmountGikum ".
                            "  From ".$sSingoName."                          ".
                            " Where SingoDate  >= '".$FromDate."'            ".
                            "   And SingoDate  <= '".$ToDate."'              ".
                            "   And Theather   = '".$singoTheather."'        ".
                            "   And Open       = '".$singoOpen."'            ".
                            "   And Film       = '".$singoFilm."'            ".
                            "   And FilmType   = '".$nFilmTypeNo."'          ".
                            "   And UnitPrice  = '".$UnitPrice."'            ".
                            " Group By SingoDate                             " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;

				}
				else
				{
                  // 일자별로 스코어를 구한다.
                  $sQuery = "Select SingoDate,                               ".
                            "       Sum(NumPersons) As SumNumPersons,        ".
                            "       Sum(TotAmount)  As SumTotAmount,         ".
                            "       Sum(TotAmountGikum) AS SumTotAmountGikum ".
                            "  From ".$sSingoName."                          ".
                            " Where SingoDate  >= '".$FromDate."'            ".
                            "   And SingoDate  <= '".$ToDate."'              ".
                            "   And Theather   = '".$singoTheather."'        ".
                            "   And Open       = '".$singoOpen."'            ".
                            "   And Film       = '".$singoFilm."'            ".

                            "   And UnitPrice  = '".$UnitPrice."'            ".
                            " Group By SingoDate                             " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
							//if  (($singoTheather=='2604') && ($UnitPrice==6500))			eq($sQuery);
				}

				$QrySingo2 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
				$ArySingo2 = mysql_fetch_array($QrySingo2) ;

				$theather_rate_default = get_theather_rate_value_default($singoLocation,$singoTheather,$singoOpen,$singoFilm,$connect) ; // 해당극장의 해당필름의 디폴트 부율

				for ($i=0 ; $i<=$dur_day ; $i++)
				{
					$objDate = date("Ymd",$timestamp2 + ($i * 86400)) ;

					$TheatherRate = get_theather_rate_value_date($TblTheatherRate,$theather_rate_default,$objDate,$singoTheather,$singoOpen,$singoFilm,$connect) ;

					if  ($ArySingo2)
					{
                       if  ($objDate == $ArySingo2["SingoDate"])
                       {
                           $HapNumPersons         += $ArySingo2["SumNumPersons"] ;
                           $HapTotAmount          += $ArySingo2["SumTotAmount"] ;
                           $HapTotAmountGikum     += $ArySingo2["SumTotAmountGikum"] ;
						   $HapTotAmountGikumRate += $ArySingo2["SumTotAmountGikum"] * ($TheatherRate / 100.0) ; // 부율

                           $arryHapGea[$i]        += $ArySingo2["SumNumPersons"] ;
                           $arrySoGea[$i]         += $ArySingo2["SumNumPersons"] ;

						   ?>
                        <td class=textarea bgcolor=<?=$Color2?> width=50 align=right>
                        &nbsp;<?=number_format($ArySingo2["SumNumPersons"])?>&nbsp;
                        </td>
                        <?
                           $ArySingo2 = mysql_fetch_array($QrySingo2) ;
                       }
                       else
                       {
                           ?>
                        <td class=textarea bgcolor=<?=$Color2?> width=50 align=right>
                        &nbsp;&nbsp;
                        </td>
                        <?
                       }
					}
					else
					{
                       ?>
                    <td class=textarea bgcolor=<?=$Color2?> width=50  align=right>
                    &nbsp;&nbsp;
                    </td>
                    <?
					}
				}


				//
				//  극장별 총 합계 찍기
				//

				/*
				$TheatherRate  = get_theather_rate_value($objDate,$singoLocation,$singoTheather,$singoOpen,$singoFilm,$connect) ;

				if  ($singoLocation == "100")
				{
                  if  ($JikYong == "N")
                  {
                      if  (($singoTheather=="2373") || ($singoTheather=="2372") || ($singoTheather=="2426"))
                      {
                          $Rate = 0.5 ;
                      }
                      else
                      {
                          $Rate = 0.6 ;
                      }
                  }
                  else
                  {
                      $Rate = 0.55 ;  // 직영일때
                  }
				}
				else
				{
                  if  ($JikYong == "N")
                  {
                      $Rate = 0.5 ;
                  }
                  else
                  {
                      $Rate = 0.45 ; // 직영일때
                  }
				}

				$Rate = $TheatherRate / 100.0 ; ////////// 부율

				$arrySoGea[$dur_day+4] += $HapTotAmountGikum * $Rate ;  // 부금
				*/

				$arrySoGea[$dur_day+1] += $HapNumPersons ;         // 소계 스코어
				$arrySoGea[$dur_day+2] += $HapTotAmount ;          // 소계 금액합계
				$arrySoGea[$dur_day+3] += $HapTotAmountGikum ;     // 소계 금액합계
				$arrySoGea[$dur_day+4] += $HapTotAmountGikumRate ; // 부금
				$arrySoGea[$dur_day+5] += $AccSumNumPersons ;  	   // 극장별 합계(스코어)
				$arrySoGea[$dur_day+6] += $AccSumNumTotAmount;     // 총계 금액합계

				$arryHapGea[$dur_day+1] += $HapNumPersons ;   	    // 총계 스코어
				$arryHapGea[$dur_day+2] += $HapTotAmount ;  	    // 총계 금액합계
				$arryHapGea[$dur_day+3] += $HapTotAmountGikum ;     // 총계 금액합계
				$arryHapGea[$dur_day+4] += $HapTotAmountGikumRate ; // 소계 부금합계
				$arryHapGea[$dur_day+5] += $AccSumNumPersons;  	    // 극장별 합계(스코어)
				$arryHapGea[$dur_day+6] += $AccSumNumTotAmount;     // 총계 금액합계

				?>

              <!-- 스코어 합계 -->
              <td class=textarea bgcolor=<?=$Color2?> width=60 align=right>&nbsp;<?=number_format($HapNumPersons)?>&nbsp;</td>

              <!-- 스코어 총금액 -->
              <td class=textarea bgcolor=<?=$Color2?> width=90 align=right>&nbsp;<?=number_format($HapTotAmount)?>&nbsp;</td>

              <!-- 기금적용금액 누계 찍기 -->
              <td class=textarea bgcolor=<?=$Color2?> width=90 align=right>&nbsp;<?=number_format($HapTotAmountGikum)?>&nbsp;</td>

              <!-- 극장별 부금 찍기 -->
              <td class=textarea bgcolor=<?=$Color1?> width=100 align=right>&nbsp;</td>

              <!-- 극장별 누계 찍기 -->
              <td class=textarea bgcolor=<?=$Color1?> width=70 align=right>&nbsp;<?=number_format($AccSumNumPersons)?>&nbsp;</td>

              <!-- 극장별 금액 찍기 -->
              <td class=textarea bgcolor=<?=$Color1?> width=100 align=right>&nbsp;<?=number_format($AccSumNumTotAmount)?>&nbsp;</td>

              </tr>
				<?
			}
			?>

			<td class=textarea bgcolor=<?=$Color1?> align=right>&nbsp;</td>
			<td class=textarea bgcolor=<?=$Color1?> align=right>합계</td>
			<?
			for ($i=0 ; $i<=$dur_day ; $i++)
			{
				?>
              <td class=textarea bgcolor=<?=$Color2?> align=right>
              &nbsp;<b><?=number_format($arryHapGea[$i])?></b>&nbsp;
              </td>
              <?
			}
			?>
			<td class=textarea bgcolor=<?=$Color2?> align=right>&nbsp;<b><?=number_format($arryHapGea[$dur_day+1])?></b>&nbsp;</td>
			<td class=textarea bgcolor=<?=$Color2?> align=right>&nbsp;<b><?=number_format($arryHapGea[$dur_day+2])?></b>&nbsp;</td>
			<td class=textarea bgcolor=<?=$Color2?> align=right>&nbsp;<b><?=number_format($arryHapGea[$dur_day+3])?></b>&nbsp;</td>
			<td class=textarea bgcolor=<?=$Color1?> align=right>&nbsp;<b><?=number_format($arryHapGea[$dur_day+4])?></b>&nbsp;</td>
			<td class=textarea bgcolor=<?=$Color1?> align=right>&nbsp;<b><?=number_format($arryHapGea[$dur_day+5])?></b>&nbsp;</td>
			<td class=textarea bgcolor=<?=$Color1?> align=right>&nbsp;<b><?=number_format($arryHapGea[$dur_day+6])?></b>&nbsp;</td>
			<?
		}
		else
		{   // 도착보고가 제대로 되지않은 경우..
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
				<!-- 지역명 출력 -->
				<td class=textarea bgcolor=<?=$Color1?> width=70 class=tbltitle align=center>
				<?=$locationName?>
				</td>

				<!-- 상영관명 출력 -->
				<td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=center>
				<?=$showroomDiscript?>
				</td>

				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>&nbsp;</td>
				<td></td>
				<td></td>
          </tr>
			<?
		}
		?>

		</table>

		<?
		$cntLocData ++ ;
		$FirstZone ++  ;
    }
?>


<?  // --------------------------------------------
    // 소계
    // --------------------------------------------

    if  ($cntLocData > 0) // 한번이라도 데이터가 나온경우
    {
    ?>
       <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">
       <tr>
           <td class=textarea bgcolor=#b0c4de align=center colspan=2 width=170>
           소계(<?=$nNumSoGea?>)<? $nNumChongGea += $nNumSoGea ; ?>
           </td>

			<td class=textarea bgcolor=#b0c4de width=30 align=right>
           &nbsp;
           </td>

           <td class=textarea bgcolor=#b0c4de width=50 align=right>
           &nbsp;
           </td>

           <?
			for ($i=0 ; $i<=$dur_day ; $i++)
			{
				?>
              <td class=textarea bgcolor=#b0c4de width=50 align=right>
              &nbsp;<b><?=number_format($arrySoGea[$i])?></b>&nbsp;
              </td>
              <?

				$arryTotHapGea[$i] += $arrySoGea[$i] ; // 총합계를 구하기(i)..
			}

			$arryTotHapGea[$dur_day+1] += $arrySoGea[$dur_day+1] ; // 총합계를 구하기(합계)
			$arryTotHapGea[$dur_day+2] += $arrySoGea[$dur_day+2] ; // 총합계를 구하기(금액)
			$arryTotHapGea[$dur_day+3] += $arrySoGea[$dur_day+3] ; // 총합계를 구하기(기금적용 금액)
			$arryTotHapGea[$dur_day+4] += $arrySoGea[$dur_day+4] ; // 총합계를 구하기(부금)
			$arryTotHapGea[$dur_day+5] += $arrySoGea[$dur_day+5] ; // 총합계를 구하기(총 누계)
			$arryTotHapGea[$dur_day+6] += $arrySoGea[$dur_day+6] ; // 총합계를 구하기(총 금액)
			?>

           <td class=textarea bgcolor=#b0c4de width=60 align=right> <!--합계-->
           &nbsp;<b><?=number_format($arrySoGea[$dur_day+1])?></b>&nbsp;
           </td>
           <td class=textarea bgcolor=#b0c4de width=90 align=right> <!--금액-->
           &nbsp;<b><?=number_format($arrySoGea[$dur_day+2])?></b>&nbsp;
           </td>
           <td class=textarea bgcolor=#b0c4de width=90 align=right> <!--기금적용 금액-->
           &nbsp;<b><?=number_format($arrySoGea[$dur_day+3])?></b>&nbsp;
           </td>
           <td class=textarea bgcolor=#b0c4de width=100 align=right> <!--부금-->
           &nbsp;<b><?=number_format($arrySoGea[$dur_day+4])?></b>&nbsp;
           </td>
           <td class=textarea bgcolor=#b0c4de width=70 align=right> <!--총 누계-->
           &nbsp;<b><?=number_format($arrySoGea[$dur_day+5])?></b>&nbsp;
           </td>
           <td class=textarea bgcolor=#b0c4de width=100 align=right> <!--총 금액-->
           &nbsp;<b><?=number_format($arrySoGea[$dur_day+6])?></b>&nbsp;
           </td>

       </tr>
       </table>
	<?
	}
?>
