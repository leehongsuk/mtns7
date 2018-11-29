
	<?
	$no = 1 ;

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

		$sQuery = "Select Theather.Discript,                   \n".
				  "       Location.Name                        \n".
				  "  From bas_theather As Theather             \n".
				  "  left join bas_location As Location        \n".
				  "    on  Theather.Location = Location.Code   \n".
				  " Where Theather.Code = '".$singoTheather."' " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
		$QryTheather = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
		if  ($AryTheather = mysql_fetch_array($QryTheather))
		{
		   $locationName     = $AryTheather["Name"] ; // 지역명 ..
		   $showroomDiscript = $AryTheather["Discript"] ; // 극장명 ..
		}

		$i = 0 ;

		$AccSumNumPersons     = 0 ;
		$AccSumTotAmount      = 0 ;
		$AccSumTotAmountBukum = 0 ;
		?>
		<table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">
		<tr height="25">
			<?
			if  ($clrToggle==true)
			{
				$Color1 = "#c0c0c0" ;
				$Color2 = "#dcdcdc" ;

				$clrToggle=false;
			}
			else
			{
				$Color1 = "#d0d0d0" ;
				$Color2 = "#ececec" ;

				$clrToggle=true;
			}
			?>
			<!-- 순위			-->
			<td class=textarea bgcolor=<?=$Color1?> width=40 class=tbltitle align=center>
			<?=$no++?>
			</td>

			<!-- 지역명 출력 -->
			<td class=textarea bgcolor=<?=$Color1?> width=50 class=tbltitle align=center>
			<?=$locationName?>
			</td>

			<!-- 상영관명 출력 -->
			<td class=textarea bgcolor=<?=$Color1?> width=120 class=tbltitle align=center>
			<?=$showroomDiscript?>
			</td>

			<?
			$theather_rate_default = get_theather_rate_value_default($singoLocation,$singoTheather,$singoOpen,$singoFilm,$connect) ; // 해당극장의 해당필름의 디폴트 부율

			for ($i=0 ; $i<=$dur_day ; $i++)
			{
				$curdate = date("Ymd",$timestamp2 + ($i * 86400)) ;

				$TheatherRate = get_theather_rate_value_date($TblTheatherRate,$theather_rate_default,$curdate,$singoTheather,$singoOpen,$singoFilm,$connect) ;

				if  ($nFilmTypeNo != 0) // All이 아닐때//.
				{
				  // 일자별로 스코어를 구한다.
				  $sQuery = "Select SingoDate,                               \n".
							"       Sum(NumPersons) As SumNumPersons,        \n".
							"       Sum(TotAmount)  As SumTotAmount          \n".
							"  From ".$sSingoName."                          \n".
							" Where SingoDate  = '".$curdate."'              \n".
							"   And Theather   = '".$singoTheather."'        \n".
							"   And Open       = '".$singoOpen."'            \n".
							"   And Film       = '".$singoFilm."'            \n".
							"   And FilmType   = '".$nFilmTypeNo."'          \n".
							" Group By SingoDate                             \n" ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
				}
				else
				{
				  // 일자별로 스코어를 구한다.
				  $sQuery = "Select SingoDate,                               \n".
							"       Sum(NumPersons) As SumNumPersons,        \n".
							"       Sum(TotAmount)  As SumTotAmount          \n".
							"  From ".$sSingoName."                          \n".
							" Where SingoDate  = '".$curdate."'              \n".
							"   And Theather   = '".$singoTheather."'        \n".
							"   And Open       = '".$singoOpen."'            \n".
							"   And Film       = '".$singoFilm."'            \n".
							" Group By SingoDate                             \n" ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
				}
				$QrySingo2 = mysql_query($sQuery,$connect) or die(ee($sQuery)) ;
				if ($ArySingo2 = mysql_fetch_array($QrySingo2))
				{
					$SumNumPersons     = $ArySingo2["SumNumPersons"] ; // 극장별 합계(스코어)
					$SumTotAmount      = $ArySingo2["SumTotAmount"] ;  // 극장별 합계(금액)

					$AccSumNumPersons     += $SumNumPersons ;
					$AccSumTotAmount      += $SumTotAmount ;
					$AccSumTotAmountBukum += $SumTotAmount * ($TheatherRate / 100.0) ;
				}
				else
				{
					$SumNumPersons      = 0 ; // 극장별 합계(스코어)
					$SumTotAmount       = 0 ;  // 극장별 합계(금액)
				}
				//number_format($arryHapGea[$i])
			  ?>
			  <td class=textarea bgcolor=<?=$Color2?> width=50 align=right>
			  &nbsp;<?=number_format($SumNumPersons)?>&nbsp;
			  </td>
			  <?
			}
			?>
			<td class=textarea width=60 bgcolor=<?=$Color2?> align=right>&nbsp;<b><?=number_format($AccSumNumPersons)?></b>&nbsp;</td>
			<td class=textarea width=120 bgcolor=<?=$Color2?> align=right>&nbsp;<b><?=number_format($AccSumTotAmount)?></b>&nbsp;</td>
			<td class=textarea width=120 bgcolor=<?=$Color2?> align=right>&nbsp;<b><?=number_format($AccSumTotAmountBukum)?></b>&nbsp;</td>
		</tr>
		</table>

	<?
	}
	?>
