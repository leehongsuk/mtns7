<?
	header("Content-Type: text/html; charset=euc-kr");

	include "config.php";

	$connect = dbconn();

	mysql_select_db($cont_db) ;

	// 신고일자기준 기금적용을 위한 시작일자
	$_SingoDate = substr($_DateSart,0,4) . substr($_DateSart,5,2) . substr($_DateSart,8,2) ;
	//echo($_SingoDate);

	$sQuery = "Update bas_theather        \n".
			  "   Set GikumRate = $_Value \n".
			  " Where Code = '$_Code'     \n";   //eq($sQuery);
	mysql_query($sQuery,$connect) ;

    $sQuery = " SELECT Open             \n".
              "       ,SingoName        \n".
              "   FROM bas_filmtitle    \n".
              "  WHERE Finish =  'N'    \n";   //eq($sQuery);
	$QryFilmtitle = mysql_query($sQuery,$connect) ;
	while ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
	{
        $Open       = $ArrFilmtitle["Open"] ;
        $SingoName  = $ArrFilmtitle["SingoName"] ;

		if  ($Open > "160523")
		{
			// 여기서 기금 적용...
			$sQuery = "  UPDATE $SingoName                                                \n".
					  "     SET TotAmountGikum = ROUND(UnitPrice / $_Value) * NumPersons  \n".
					  "   WHERE Theather   = '$_Code'                                     \n".
					  "     AND SingoDate >= '$_SingoDate'                                \n" ;   //echo($sQuery);
			mysql_query($sQuery,$connect) ;
		}
	}

	mysql_close($connect);
?>