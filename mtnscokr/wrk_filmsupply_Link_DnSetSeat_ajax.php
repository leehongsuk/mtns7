<?
    $Theather  = $_POST["_Theather"] ;
	$Gubun     = $_POST["_Gubun"] ;
    $Room      = $_POST["_Room"] ;
	$Seat      = $_POST["_Seat"] ;
    $RoomSeat  = $_POST["_RoomSeat"] ;

	include "config.php";

	$connect=dbconn();

	mysql_select_db($cont_db) ;

	
	if  ($Gubun == "1")
	{
		$sQuery = "UPDATE bas_showroom 
					  SET Seat = '$Seat'
					WHERE Theather = '$Theather'
					  AND Room     = '$Room'
				 "; echo "UPDATE"; //echo $sQuery;
		mysql_query($sQuery,$connect) ;
	}

	if  ($Gubun == "2")
	{
		$arrRoomSeat = split(",",$RoomSeat);
		foreach ($arrRoomSeat as $item) 
		{ 
			$arrItem = split(":",$item);
			//$arrItem[0] // room
			//$arrItem[1] // Seat 

			$sQuery = "UPDATE bas_showroom 
						  SET Seat = '$arrItem[1]'
						WHERE Theather = '$Theather'
						  AND Room     = '$arrItem[0]'
					 ";  //echo $sQuery;
			mysql_query($sQuery,$connect) ;
		}
		echo "UPDATE";
	}


    mysql_close($connect) ;      // {[데이터 베이스]} : 단절
?>