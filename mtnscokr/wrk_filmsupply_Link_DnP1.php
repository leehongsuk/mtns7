<?
	$FirstZone  = 0 ;

	$cntLocData = 0 ;

	for ($i=0 ; $i<=($dur_day+6) ; $i++)
	{
       $arrySoGea[$i] = 0 ; // ������ ������ ���� �ʱ�ȭ �Ѵ�.
	}
	$nNumSoGea = 0 ; // ���尹��(�Ұ�)

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
           $locationName     = $AryTheather["Name"] ; // ������ ..
           $JikYong          = $AryTheather["JikYong"] ;
        }
		?>

		<table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">

		<?
		// --------------------------------------------
		// ���ݺ� ���ھ�
		// --------------------------------------------

		$FirstPrice = true ;

		for ($i=0 ; $i<=($dur_day+6) ; $i++)
		{
			$arryHapGea[$i]  = 0 ; // ���庰 �հ踦 ������ �ٲ� ���� �ʱ�ȭ�Ѵ�.
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

		if  ($affected_rows > 0) // �������� ������ �Ȱ�
		{
			while ($ArySingo1 = mysql_fetch_array($QrySingo1))
			{
				$UnitPrice          = $ArySingo1["UnitPrice"] ;          // ��ݴ뿪
				$AccSumNumPersons   = $ArySingo1["AccSumNumPersons"] ;   // ���庰 �հ�(���ھ�)
				$AccSumNumTotAmount = $ArySingo1["AccSumNumTotAmount"] ; // ���庰 �հ�(�ݾ�)

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

					<!-- ������ ��� -->
					<td class=textarea bgcolor=<?=$Color1?> rowspan=<?=$affected_rows?> width=50 class=tbltitle align=center>
					<?=$locationName?>
					</td>

					<!-- �󿵰��� ��� -->
					<td class=textarea bgcolor=<?=$Color1?> rowspan=<?=$affected_rows?> class=tbltitle width=120 align=center>
					<?=$showroomDiscript?>
					</td>

					<?
					$FirstPrice = false ;
				}
				?>

              <!-- ���(�ڵ�) ��� -->
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

              <!-- ���(Ÿ��Ʋ) ��� -->
              <td class=textarea bgcolor=<?=$Color1?> width=50 align=right><?=number_format($UnitPrice)?>&nbsp;</td>


              <?
				$HapNumPersons         = 0 ;
				$HapTotAmount          = 0 ;
				$HapTotAmountGikum     = 0 ;
				$HapTotAmountGikumRate = 0 ;

				if  ($nFilmTypeNo != 0) // All�� �ƴҶ�//.
				{
                  // ���ں��� ���ھ ���Ѵ�.
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
                  // ���ں��� ���ھ ���Ѵ�.
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

				$theather_rate_default = get_theather_rate_value_default($singoLocation,$singoTheather,$singoOpen,$singoFilm,$connect) ; // �ش������ �ش��ʸ��� ����Ʈ ����

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
						   $HapTotAmountGikumRate += $ArySingo2["SumTotAmountGikum"] * ($TheatherRate / 100.0) ; // ����

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
				//  ���庰 �� �հ� ���
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
                      $Rate = 0.55 ;  // �����϶�
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
                      $Rate = 0.45 ; // �����϶�
                  }
				}

				$Rate = $TheatherRate / 100.0 ; ////////// ����

				$arrySoGea[$dur_day+4] += $HapTotAmountGikum * $Rate ;  // �α�
				*/

				$arrySoGea[$dur_day+1] += $HapNumPersons ;         // �Ұ� ���ھ�
				$arrySoGea[$dur_day+2] += $HapTotAmount ;          // �Ұ� �ݾ��հ�
				$arrySoGea[$dur_day+3] += $HapTotAmountGikum ;     // �Ұ� �ݾ��հ�
				$arrySoGea[$dur_day+4] += $HapTotAmountGikumRate ; // �α�
				$arrySoGea[$dur_day+5] += $AccSumNumPersons ;  	   // ���庰 �հ�(���ھ�)
				$arrySoGea[$dur_day+6] += $AccSumNumTotAmount;     // �Ѱ� �ݾ��հ�

				$arryHapGea[$dur_day+1] += $HapNumPersons ;   	    // �Ѱ� ���ھ�
				$arryHapGea[$dur_day+2] += $HapTotAmount ;  	    // �Ѱ� �ݾ��հ�
				$arryHapGea[$dur_day+3] += $HapTotAmountGikum ;     // �Ѱ� �ݾ��հ�
				$arryHapGea[$dur_day+4] += $HapTotAmountGikumRate ; // �Ұ� �α��հ�
				$arryHapGea[$dur_day+5] += $AccSumNumPersons;  	    // ���庰 �հ�(���ھ�)
				$arryHapGea[$dur_day+6] += $AccSumNumTotAmount;     // �Ѱ� �ݾ��հ�

				?>

              <!-- ���ھ� �հ� -->
              <td class=textarea bgcolor=<?=$Color2?> width=60 align=right>&nbsp;<?=number_format($HapNumPersons)?>&nbsp;</td>

              <!-- ���ھ� �ѱݾ� -->
              <td class=textarea bgcolor=<?=$Color2?> width=90 align=right>&nbsp;<?=number_format($HapTotAmount)?>&nbsp;</td>

              <!-- �������ݾ� ���� ��� -->
              <td class=textarea bgcolor=<?=$Color2?> width=90 align=right>&nbsp;<?=number_format($HapTotAmountGikum)?>&nbsp;</td>

              <!-- ���庰 �α� ��� -->
              <td class=textarea bgcolor=<?=$Color1?> width=100 align=right>&nbsp;</td>

              <!-- ���庰 ���� ��� -->
              <td class=textarea bgcolor=<?=$Color1?> width=70 align=right>&nbsp;<?=number_format($AccSumNumPersons)?>&nbsp;</td>

              <!-- ���庰 �ݾ� ��� -->
              <td class=textarea bgcolor=<?=$Color1?> width=100 align=right>&nbsp;<?=number_format($AccSumNumTotAmount)?>&nbsp;</td>

              </tr>
				<?
			}
			?>

			<td class=textarea bgcolor=<?=$Color1?> align=right>&nbsp;</td>
			<td class=textarea bgcolor=<?=$Color1?> align=right>�հ�</td>
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
		{   // �������� ����� �������� ���..
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
    // �Ұ�
    // --------------------------------------------

    if  ($cntLocData > 0) // �ѹ��̶� �����Ͱ� ���°��
    {
    ?>
       <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 bordercolor="#C0B0A0">
       <tr>
           <td class=textarea bgcolor=#b0c4de align=center colspan=2 width=170>
           �Ұ�(<?=$nNumSoGea?>)<? $nNumChongGea += $nNumSoGea ; ?>
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

				$arryTotHapGea[$i] += $arrySoGea[$i] ; // ���հ踦 ���ϱ�(i)..
			}

			$arryTotHapGea[$dur_day+1] += $arrySoGea[$dur_day+1] ; // ���հ踦 ���ϱ�(�հ�)
			$arryTotHapGea[$dur_day+2] += $arrySoGea[$dur_day+2] ; // ���հ踦 ���ϱ�(�ݾ�)
			$arryTotHapGea[$dur_day+3] += $arrySoGea[$dur_day+3] ; // ���հ踦 ���ϱ�(������� �ݾ�)
			$arryTotHapGea[$dur_day+4] += $arrySoGea[$dur_day+4] ; // ���հ踦 ���ϱ�(�α�)
			$arryTotHapGea[$dur_day+5] += $arrySoGea[$dur_day+5] ; // ���հ踦 ���ϱ�(�� ����)
			$arryTotHapGea[$dur_day+6] += $arrySoGea[$dur_day+6] ; // ���հ踦 ���ϱ�(�� �ݾ�)
			?>

           <td class=textarea bgcolor=#b0c4de width=60 align=right> <!--�հ�-->
           &nbsp;<b><?=number_format($arrySoGea[$dur_day+1])?></b>&nbsp;
           </td>
           <td class=textarea bgcolor=#b0c4de width=90 align=right> <!--�ݾ�-->
           &nbsp;<b><?=number_format($arrySoGea[$dur_day+2])?></b>&nbsp;
           </td>
           <td class=textarea bgcolor=#b0c4de width=90 align=right> <!--������� �ݾ�-->
           &nbsp;<b><?=number_format($arrySoGea[$dur_day+3])?></b>&nbsp;
           </td>
           <td class=textarea bgcolor=#b0c4de width=100 align=right> <!--�α�-->
           &nbsp;<b><?=number_format($arrySoGea[$dur_day+4])?></b>&nbsp;
           </td>
           <td class=textarea bgcolor=#b0c4de width=70 align=right> <!--�� ����-->
           &nbsp;<b><?=number_format($arrySoGea[$dur_day+5])?></b>&nbsp;
           </td>
           <td class=textarea bgcolor=#b0c4de width=100 align=right> <!--�� �ݾ�-->
           &nbsp;<b><?=number_format($arrySoGea[$dur_day+6])?></b>&nbsp;
           </td>

       </tr>
       </table>
	<?
	}
?>
