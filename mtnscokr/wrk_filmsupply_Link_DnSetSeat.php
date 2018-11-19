<?
    session_start();

    // 정상적으로 로그인 했는지 체크한다.
    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";

        $connect=dbconn();

        mysql_select_db($cont_db) ;


		$sQuery = "	   SELECT Open
							 ,Code
						 FROM bas_filmtitle
						WHERE Open <>999999
					 ORDER BY Open DESC , code DESC
						LIMIT 0 , 1
					" ; //echo $sQuery;
		$QryFilmtitle = mysql_query($sQuery,$connect) ;
		if ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
		{
			$SHRmOrdrTbl =  "bas_srorder_".$ArrFilmtitle["Open"]."_".$ArrFilmtitle["Code"];
		}

		if   (strlen($ZoneCode) == 4) // 전체
        {
			$sQuery = "SELECT SR.Theather
			                 ,SR.Room
							 ,SR.Discript
							 ,LOC .Name
							 ,SR.Seat
 						 FROM bas_showroom SR
 				   INNER JOIN bas_location LOC
				           ON LOC.Code = SR.Location
 				   INNER JOIN ".$SHRmOrdrTbl."  SO
                           ON SR.Theather  =  SO.Theather
                          AND SR.Room      =  SO.Room
                     ORDER BY SO.Seq
					  " ; //echo $sQuery;
		}
		if   (strlen($ZoneCode) == 3) // 지역
        {
			if  ($ZoneCode=="200")
			{
				$sQuery = "Select SR.Theather
								 ,SR.Room
								 ,SR.Discript
								 ,LOC .Name
								 ,SR.Seat
							 From bas_showroom SR
					   INNER JOIN bas_location LOC
							   ON LOC.Code = SR.Location
					   INNER JOIN ".$SHRmOrdrTbl."  SO
							   ON SR.Theather  =  SO.Theather
							  AND SR.Room      =  SO.Room
							WHERE SR.Location = '200'
							   OR SR.Location = '203'
							   OR SR.Location = '202'
							   OR SR.Location = '600'
							   OR SR.Location = '207'
							   OR SR.Location = '201'
						 ORDER BY SO.Seq
						  " ;
			}
			else
			{
				$sQuery = "Select SR.Theather
								 ,SR.Room
								 ,SR.Discript
								 ,LOC .Name
								 ,SR.Seat
							 From bas_showroom SR
					   INNER JOIN bas_location LOC
							   ON LOC.Code = SR.Location
					   INNER JOIN ".$SHRmOrdrTbl."  SO
							   ON SR.Theather  =  SO.Theather
							  AND SR.Room      =  SO.Room
							WHERE SR.Location = '".$ZoneCode."'
						 ORDER BY SO.Seq
						  " ;
			}
			//echo $sQuery;
		}
		if   (strlen($ZoneCode) == 2)  // 구역
		{
			$sQuery = "Select SR.Theather
			                 ,SR.Room
							 ,SR.Discript
							 ,LOC .Name
							 ,SR.Seat
                         From bas_showroom SR
 				   INNER JOIN bas_location LOC
				           ON LOC.Code = SR.Location
 				   INNER JOIN ".$SHRmOrdrTbl."  SO
                           ON SR.Theather  =  SO.Theather
                          AND SR.Room      =  SO.Room
                        WHERE SR.Location in ( Select Location
                                                 From bas_filmsupplyzoneloc
                                                Where Zone = '".$ZoneCode."'
						                     )
                     ORDER BY SO.Seq
					  " ; //echo $sQuery;
		}
    }
?>
<html>
	<link rel=stylesheet href=./LinkStyle.css type=text/css>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

	<head>
	<title>극장좌석수 지정</title>

	<script type="text/javascript" src="./js/jquery-1.8.3.js"></script>

	<SCRIPT LANGUAGE="JavaScript">
	<!--

	//
	//   숫자만 입력 받도록 제한한다.
	//

	function score_check(edit)
	{

		if ((edit !="") && (edit.search(/\D/) != -1))
		{
			alert("숫자만 입력시오!") ;

			write.score.value = "";

			edit = edit.replace(/\D/g, "")

			write.score.focus() ;
			write.score.select();

			return false ;
		}
		else
		{
			return true ;
		}
	}


	function clickSet(theather,room)
	{
		var seat = $('#tx'+theather+room).val() ;

		if   (score_check(seat) == true)
		{
			var options = {
                    _Theather:  theather,
					_Gubun: '1',
                    _Room:      room,
					_Seat:      seat
                } ;
			$.post("wrk_filmsupply_Link_DnSetSeat_ajax.php", options, function(data)
			{
				if (data=="UPDATE")
				{
					alert("좌석이 수정되었습니다.");
				}
			});
		}
		else
		{
			$('#tx'+theather+room).focus();
			$('#tx'+theather+room).select();
		}

	    //alert(document.getElementById(a).value);
	    //alert(a);
	}

	function clickSetTeather(theather)
	{
		var data  = "" ;

		$("[id^=tx"+theather+"]").each(function()
		{
			if ( data != "") data += "," ;

			room = $(this).attr("room") ;
			val =  $(this).val() ;

			data += room+":"+val ;
		});

		var options = {
				_Theather:  theather,
                _Gubun: '2',
				_RoomSeat:   data
			} ;
		$.post("wrk_filmsupply_Link_DnSetSeat_ajax.php", options, function(data)
		{
			if (data=="UPDATE")
			{
				alert("좌석이 수정되었습니다.");
			}
		});
	}

	//-->
	</SCRIPT>

</head>
<body bgcolor=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

   <BR><BR><BR>
   <center><B>극장좌석수 지정</B></center>

   <center>
			<?
            $ColorA =  '#ffebcd' ;
            $ColorB =  '#dcdcec' ;
            $ColorC =  '#dcdcdc' ;
            $ColorD =  '#c0c0c0' ;
            ?>
            <BR><BR>

            <TABLE cellpadding=0 cellspacing=0 border=1 bordercolor='#C0B0A0'>
            <TR>
				<TD width=100  bgcolor=<?=$ColorA?> align=center>극장/관 코드</TD>
				<TD width=500  bgcolor=<?=$ColorA?> align=center>극장</TD>
                <TD width=100  bgcolor=<?=$ColorA?> align=center>지역</TD>
                <TD width=90  bgcolor=<?=$ColorA?> align=center>좌석수</TD>
				<TD width=70  bgcolor=<?=$ColorA?> align=center>&nbsp;</TD>
            </TR>
			<?
			$oldTheather = "" ;

			$QryShowroom = mysql_query($sQuery,$connect) ;
			while ($ArrShowroom = mysql_fetch_array($QryShowroom))
			{
				$Theather = $ArrShowroom["Theather"] ;

				if  ($oldTheather != $ArrShowroom["Theather"])
				{
					$sQuery = "Select count(Room) CntRoom
								 From bas_showroom SR
								WHERE Theather = $Theather
							  " ; //echo $sQuery;
                    $QryCntRoom = mysql_query($sQuery,$connect) ;
           			if ($ArrCntRoom = mysql_fetch_array($QryCntRoom))
					{
						$CntRoom = $ArrCntRoom["CntRoom"];
					}
				}
				?>
				<TR>
					<TD bgcolor=<?=$ColorC?> align=center><?=$ArrShowroom["Theather"]?> / <?=$ArrShowroom["Room"]?></TD>
					<TD bgcolor=<?=$ColorC?> align=center><?=$ArrShowroom["Discript"]?></TD>
					<TD bgcolor=<?=$ColorC?> align=center><?=$ArrShowroom["Name"]?></TD>
					<TD bgcolor=<?=$ColorC?> align=center>
						<INPUT TYPE="text" style="text-align:right;" room="<?=$ArrShowroom["Room"]?>" id="tx<?=$ArrShowroom["Theather"].$ArrShowroom["Room"]?>" size=5 value="<?=$ArrShowroom["Seat"]?>">
						<!-- INPUT TYPE="button" id="bu<?=$ArrShowroom["Theather"]?>"  value="저장" onclick="clickSet('<?=$ArrShowroom["Theather"]?>','<?=$ArrShowroom["Room"]?>');" -->
					</TD>
					<?
					if  ($oldTheather != $ArrShowroom["Theather"])
					{
						?>
						<TD bgcolor=<?=$ColorC?> align=center rowspan="<?=$CntRoom?>">
						<INPUT TYPE="button" id="bu<?=$ArrShowroom["Theather"]?>"  value="저장" onclick="clickSetTeather('<?=$ArrShowroom["Theather"]?>');">
						</TD>
						<?
					}
					?>
				</TR>
				<?
				$oldTheather = $ArrShowroom["Theather"];
			}
			?>
			</TABLE>

            <BR><BR>

   </center>

</body>

</html>
<?
    mysql_close($connect);
?>

