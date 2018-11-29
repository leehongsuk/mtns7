<?
	header("Content-Type: text/html; charset=euc-kr");

	include "config.php";

	$connect = dbconn();

	mysql_select_db($cont_db) ;

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
			$sQuery = "  UPDATE $SingoName                                                \n".
					  "     SET TotAmountGikum = ROUND(UnitPrice / $_Value) * NumPersons  \n".
					  "   WHERE Theather = '$_Code'                                       \n" ;   //echo($sQuery);
			mysql_query($sQuery,$connect) ;
		}
	}

	mysql_close($connect);
?>