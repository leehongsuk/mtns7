<?
    session_start();



    // 정상적으로 로그인 했는지 체크한다.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = 'index_com.php'</script>";
    }
    else
    {
        include "config.php";

        $connect=dbconn();

        mysql_select_db($cont_db) ;

        $ToDate = date("Ymd",$Today) ; // 무조건 오늘 (이전자료와 비교)

        $TmroDate = date("Ymd",strtotime("+1 day",strtotime(substr($WorkDate,0,4)."-".substr($WorkDate,4,2)."-".substr($WorkDate,6,2).""))) ;

        $sQuery = "Select * From bas_smsidchk       ".
                  " Where Id = '".$logged_UserId."' " ;
tmp_query($sQuery,$connect) ;
        $QrySmsIdChk = mysql_query($sQuery,$connect) ;
        if  ($ArrSmsIdChk = mysql_fetch_array($QrySmsIdChk)) // 이부장.. $logged_UserId=="7070"
        {
            echo "<script language='JavaScript'>window.location = 'mtnscokr/wrk_filmsupply_Link.php?logged_UserId=bros56&spacial_UserId=".$logged_UserId."'</script>";
        }

        // 해당실무자를 구하고
        $sQuery = "Select * From bas_silmooja    ".
                  " Where UserId = '".$UserId."' " ;
        $qry_silmooja = mysql_query($sQuery,$connect) ;
        if  ($silmooja_data = mysql_fetch_array($qry_silmooja))
        {
            $silmoojaCode         = $silmooja_data["Code"] ;      // 실무자코드
            $silmoojaName         = $silmooja_data["Name"] ;      // 실무자이름
            $silmoojaTheather     = $silmooja_data["Theather"] ;  // 의미없음
            $silmoojaRoom         = $silmooja_data["Room"] ;      // 의미없음
            $silmoojaGongji       = $silmooja_data["Gongji"] ;    // 실무자 공지사항
            $silmoojaFilmSupply   = $silmooja_data["FilmSupply"] ;// 실무자소속배급사
            $silmoojaSangTheather = $silmooja_data["SangTheather"] ;   // 상대영화 지정 극장
            $silmoojaSunTheather  = $silmooja_data["SunTheather"] ;    // 선재현황 지정 극장


            if  (!$SangTheather)
            {
                $SangTheather = $silmoojaSangTheather ;
            }
            if  (!$SunTheather)
            {
                $SunTheather = $silmoojaSunTheather ;
            }
        }

        if  ($ChkSangTheather=='Yes') // 상대영화가 선택되었을 때..
        {
            $sQuery = "Update bas_silmooja                       ".
                      "   Set SangTheather = '".$SangTheather."' ".
                      " Where Code = '".$silmoojaCode."'         " ;
            mysql_query($sQuery,$connect) ;

        }
        if  ($ChkSunTheather=='Yes') // 상대영화가 선택되었을 때..
        {
            $sQuery = "Update bas_silmooja                     ".
                      "   Set SunTheather = '".$SunTheather."' ".
                      " Where Code = '".$silmoojaCode."'       " ;
            mysql_query($sQuery,$connect) ;

        }
        if  ($GongjiDelete == "Yes")
        {
            $sQuery = "Update bas_silmooja                 ".
                      "   Set Gongji = 'none'              ".
                      " Where Code = '".$silmoojaCode."'   " ;
            mysql_query($sQuery,$connect) ;
        }
?>

<html>

<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>실무자업무</title>
</head>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

   <script>

         // 실무자가 선택한 상영관에 대한 세부정보를 입력하기 위한 화면으로 간다.
         function check_theether(sShowroom)
         {
            location.href="wrk_silmooja_2.php?WorkDate="+<?=$WorkDate?>+"&ShowroomCode="+sShowroom+"&BackAddr=wrk_silmooja.php" ;
         }

         // 상대영화 입력하기 위한 화면으로 간다.
         function check_sangtheether(sShowroom)
         {
            location.href="wrk_silmooja_11.php?WorkDate="+<?=$WorkDate?>+"&ShowroomCode="+sShowroom+"&BackAddr=wrk_silmooja.php" ;
         }

         // 선재영화 입력하기 위한 화면으로 간다.
         function check_suntheether(sShowroom)
         {
            location.href="wrk_silmooja_13.php?WorkDate="+<?=$WorkDate?>+"&SunTheather="+sShowroom+"&BackAddr=wrk_silmooja.php" ;
         }

   </script>


<? echo "<b>".$UserName . "</b>님을 환영합니다!" ; ?>
<a href="../index_com.php?actcode=logout"><b>[LogOut]</b></a>
<!-- <a href="member_modyfy.php"><b>[수정]</b></a> -->


<br><br><br>


 <center>

    <table cellpadding=0 cellspacing=0 border=0>

    <tr height=25>
        <td align=left colspan=2>

        <ol>

        <?
        $sQuery = "Select * From bas_silmoojatheatherpriv  ".
                  " Where Silmooja  = '".$silmoojaCode."'  ".
                  "   And WorkDate  = '".$WorkDate."'      " ;
//echo "<br>".$sQuery ;
        $QrySilmothetprv = mysql_query($sQuery,$connect) ;
        if  ($ArrSilmothetprv = mysql_fetch_array($QrySilmothetprv))
        {
            $sDgrName   = get_degree($ArrSilmothetprv["Open"],$ArrSilmothetprv["Film"],$connect) ;
            $sDgrpName  = get_degreepriv($ArrSilmothetprv["Open"],$ArrSilmothetprv["Film"],$connect) ;
        }
        else
        {
            $sQuery = "Select * From bas_silmoojatheather      ".
                      " Where Silmooja  = '".$silmoojaCode."'  " ;
            $QrySilmothet = mysql_query($sQuery,$connect) ;
            while ($ArrSilmothet = mysql_fetch_array($QrySilmothet))
            {
                 $sQuery = "Insert Into bas_silmoojatheatherpriv      ".  // 실무자 상영관선택 정보
                           "Values (                                  ".
                           "         '".$silmoojaCode."',             ".
                           "         '".$WorkDate."',                 ".
                           "         '".$ArrSilmothet["Theather"]."', ".
                           "         '".$ArrSilmothet["Room"]."',     ".
                           "         '".$ArrSilmothet["Open"]."',     ".
                           "         '".$ArrSilmothet["Film"]."',     ".
                           "         '".$ArrSilmothet["Name"]."',     ".
                           "         '".$ArrSilmothet["Showroom"]."', ".
                           "         '".$ArrSilmothet["Title"]."'     ".
                           "        )                                 " ;
                 mysql_query($sQuery,$connect) ;
            }
            $sDgrName   = get_degree($ArrSilmothet["Open"],$ArrSilmothet["Film"],$connect) ;
            $sDgrpName  = get_degreepriv($ArrSilmothet["Open"],$ArrSilmothet["Film"],$connect) ;
        }
        ?>

        <!-- 작업기준일자 지정 -->
        <li><b><a href="wrk_silmooja_0.php?BackAddr=wrk_silmooja.php">작업일자설정</a></b></li>
               <a href="wrk_silmooja_0.php?BackAddr=wrk_silmooja.php">(<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>)</a><br><br>


        <!-- 도착보고 -->
        <li><b><a href="wrk_silmooja_1.php?WorkDate=<?=$WorkDate?>&BackAddr=wrk_silmooja.php">도착보고</a>
        <?
        $sQuery = "Select * From bas_filmtitlesilmooja      ".
                  " where Silmooja   = '".$silmoojaCode."'  " ;
        $QryFilmSupply = mysql_query($sQuery,$connect) ;
        if  ($ArrQryFilmSupply = mysql_fetch_array($QryFilmSupply))
        {
            ?>
            &nbsp;<a href="wrk_silmooja_1_Code.php?WorkDate=<?=$WorkDate?>&BackAddr=wrk_silmooja.php">극장코드표</a>
            <?
        }
        ?>
        </b>
        </li><br>

             <?
             if  ($ToDate != $WorkDate) // 작업일자와 오늘 일자가 다른경우 ..
             {
                 // 실무자가 선택한 상영관을 나열한다. (다른날 검색)
                 $sQuery = "Select * From bas_silmoojatheatherpriv  ".
                           " Where Silmooja  = '".$silmoojaCode."'  ".
                           "   And WorkDate  = '".$WorkDate."'      " ;
                 $query1 = mysql_query($sQuery,$connect) ;
                 while ($ArrSilmothet = mysql_fetch_array($query1))
                 {
                     $sQuery = "SELECT sr.Theather
					                  ,sr.Room
									  ,sr.Location
									  ,sr.Discript
									  ,lo.Name LocName
                                  FROM bas_showroom sr
                             LEFT JOIN bas_location lo
							        ON sr.Location = lo.Code
  								 WHERE Theather = '".$ArrSilmothet["Theather"]."'
                                   AND Room     = '".$ArrSilmothet["Room"]."'        " ; //echo "<br>".$sQuery ;
                     $query2 = mysql_query($sQuery,$connect) ;
                     if  ($showroom_data = mysql_fetch_array($query2))
                     {
                         $showroomTheather  = $showroom_data["Theather"] ;
                         $showroomRoom      = $showroom_data["Room"] ;
                         $showroomDiscript  = $showroom_data["Discript"] ;
                         $locationName      = $showroom_data["LocName"] ; // 상영관 소재지역 명
						 ?>
						 <!-- 실무자가 선택한 상영관에 대한 세부 정보가 들어간다. -->
						 <!-- <a OnClick="check_theether('<?=$showroomTheather?><?=$showroomRoom?>');"> -->
						 <a href="wrk_silmooja_2.php?WorkDate=<?=$WorkDate?>&ShowroomCode=<?=$showroomTheather?><?=$showroomRoom?>&BackAddr=wrk_silmooja.php">
						 (<? echo $showroomDiscript."-".$locationName.":".$ArrSilmothet["Title"] ; ?>)<br><br> <!-- 실무자가 선택한 상영관 -->
						 </a>
						 <?
                     }
                 }
             }
             else // 오늘 일자 작업인 경우 ..
             {
                 // 실무자가 선택한 상영관을 나열한다.
                 $sQuery = "Select * From bas_silmoojatheather      ".
                           " Where Silmooja  = '".$silmoojaCode."'  " ;//echo "<br>".$sQuery ;
                 $query1 = mysql_query($sQuery,$connect) ;
                 while ($ArrSilmothet = mysql_fetch_array($query1))
                 {
					 $sQuery = "SELECT sr.Theather
					                  ,sr.Room
									  ,sr.Location
									  ,sr.Discript
									  ,lo.Name LocName
                                  FROM bas_showroom sr
                             LEFT JOIN bas_location lo
							        ON sr.Location = lo.Code
  								 WHERE Theather = '".$ArrSilmothet["Theather"]."'
                                   AND Room     = '".$ArrSilmothet["Room"]."'        " ; //echo "<br>".$sQuery ;
                     $query2 = mysql_query($sQuery,$connect) ;
                     if  ($showroom_data = mysql_fetch_array($query2))
                     {
                         $showroomTheather  = $showroom_data["Theather"] ;
                         $showroomRoom      = $showroom_data["Room"] ;
                         $showroomDiscript  = $showroom_data["Discript"] ;
                         $showroomLocation  = $showroom_data["Location"] ;
						 $locationName      = $showroom_data["LocName"] ; // 상영관 소재지역 명
						 ?>
						 <!-- 실무자가 선택한 상영관에 대한 세부 정보가 들어간다. -->
						 <!-- <a OnClick="check_theether('<?=$showroomTheather?><?=$showroomRoom?>');"> -->
						 <a href="wrk_silmooja_2.php?WorkDate=<?=$WorkDate?>&ShowroomCode=<?=$showroomTheather?><?=$showroomRoom?>&BackAddr=wrk_silmooja.php">
						 (<? echo $showroomDiscript."-".$locationName.":".$ArrSilmothet["Title"] ; ?>)<br><br> <!-- 실무자가 선택한 상영관 -->
						 </a>
						 <?
                     }
                 }
             }
             ?>

        <!-- 스코어보고 -->
        <li><b>스코어보고</b></li><br>

             <?
             if  ($ToDate != $WorkDate) // 작업일자와 오늘 일자가 다른경우 ..
             {
                 // 실무자가 선택한 상영관을 나열한다. (다른날 검색)
                 $sQuery = "Select * From bas_silmoojatheatherpriv  ".
                           " Where Silmooja  = '".$silmoojaCode."'  ".
                           "   And WorkDate  = '".$WorkDate."'      " ;
                 $query1 = mysql_query($sQuery,$connect) ;
                 while ($ArrSilmothet = mysql_fetch_array($query1))
                 {
					 $sQuery = "SELECT sr.Theather
					                  ,sr.Room
									  ,sr.Location
									  ,sr.Discript
									  ,lo.Name LocName
                                  FROM bas_showroom sr
                             LEFT JOIN bas_location lo
							        ON sr.Location = lo.Code
  								 WHERE Theather = '".$ArrSilmothet["Theather"]."'
                                   AND Room     = '".$ArrSilmothet["Room"]."'        " ; //echo "<br>".$sQuery ;
                     $query2 = mysql_query($sQuery,$connect) ;
                     if  ($showroom_data = mysql_fetch_array($query2))
                     {
                         $showroomDiscript  = $showroom_data["Discript"] ;
						 $locationName      = $showroom_data["LocName"] ;  // 상영관 소재지역 명

						 // 스코어 보고를 위해서는 반드시 영화를 선택해야 한다.
						 if  (($ArrSilmothet["Open"] != "") && ($ArrSilmothet["Film"] != ""))
						 {
							  $sDgrName   = get_degree($ArrSilmothet["Open"],$ArrSilmothet["Film"],$connect) ;
							  $sDgrpName  = get_degreepriv($ArrSilmothet["Open"],$ArrSilmothet["Film"],$connect) ;

							  $sQuery = "Select * From ".$sDgrpName."                      ".
										" Where Silmooja = '".$silmoojaCode."'             ".
										"   And WorkDate = '".$WorkDate."'                 ".
										"   And Open     = '".$ArrSilmothet["Open"]."'     ".
										"   And Film     = '".$ArrSilmothet["Film"]."'     ".
										"   And Theather = '".$ArrSilmothet["Theather"]."' ".
										"   And Room     = '".$ArrSilmothet["Room"]."'     " ;
							  $qry_degreepriv = mysql_query($sQuery,$connect) ;
							  $degreepriv_data = mysql_fetch_array($qry_degreepriv) ;

							  $sQuery = "Select * From bas_unitpricespriv                  ".
										" Where Silmooja = '".$silmoojaCode."'             ".
										"   And WorkDate = '".$WorkDate."'                 ".
										"   And Open     = '".$ArrSilmothet["Open"]."'     ".
										"   And Film     = '".$ArrSilmothet["Film"]."'     ".
										"   And Theather = '".$ArrSilmothet["Theather"]."' ".
										"   And Room     = '".$ArrSilmothet["Room"]."'     " ;
							  $qry_unitpricespriv = mysql_query($sQuery,$connect) ;
							  $unitpricespriv_data = mysql_fetch_array($qry_unitpricespriv) ;

							  if  (($degreepriv_data) && ($unitpricespriv_data))
							  {
							  ?>

							  <!-- 실무자가 선택한 상영관에 스코어 전송으로 들어간다. -->
							  <a href="wrk_silmooja_7.php?M2005=No&WorkDate=<?=$WorkDate?>&ShowRoom=<? echo $ArrSilmothet["Theather"] . $ArrSilmothet["Room"]?>&BackAddr=wrk_silmooja.php">
							  (<? echo $showroomDiscript."-".$locationName.":".$ArrSilmothet["Title"] ; ?>)   <!-- 실무자가 선택한 상영관 -->
							  </a>
							  &nbsp;
							  <a href="wrk_silmooja_7.php?M2005=Yes&WorkDate=<?=$WorkDate?>&ShowRoom=<? echo $ArrSilmothet["Theather"] . $ArrSilmothet["Room"]?>&BackAddr=wrk_silmooja.php">*</a>
							  &nbsp;
							  <a href="wrk_silmooja_7A.php?M2005=Yes&WorkDate=<?=$WorkDate?>&ShowRoom=<? echo $ArrSilmothet["Theather"] . $ArrSilmothet["Room"]?>&BackAddr=wrk_silmooja.php">*</a>

							  <br><br>

							  <?
							  }
						 }
                     }
                 }
             }
             else // 오늘 일자 작업인 경우 ..
             {
                 // 실무자가 선택한 상영관을 나열한다.
                 $sQuery = "Select * From bas_silmoojatheather     ".
                           " Where Silmooja  = '".$silmoojaCode."' " ;
//echo "<br>".$sQuery ;
                 $query1 = mysql_query($sQuery,$connect) ;
                 while ($ArrSilmothet = mysql_fetch_array($query1))
                 {
					 $sQuery = "SELECT sr.Theather
					                  ,sr.Room
									  ,sr.Location
									  ,sr.Discript
									  ,lo.Name LocName
                                  FROM bas_showroom sr
                             LEFT JOIN bas_location lo
							        ON sr.Location = lo.Code
  								 WHERE Theather = '".$ArrSilmothet["Theather"]."'
                                   AND Room     = '".$ArrSilmothet["Room"]."'        " ; //echo "<br>".$sQuery ;
                     $query2 = mysql_query($sQuery,$connect) ;
                     if  ($showroom_data = mysql_fetch_array($query2))
                     {
                         $showroomDiscript  = $showroom_data["Discript"] ;
						 $locationName      = $showroom_data["LocName"] ;  // 상영관 소재지역 명

//echo "<br>".$ArrSilmothet["Open"]. ' ' . $sDgrName;
//echo "<br>".$ArrSilmothet["Film"];

						 // 스코어 보고를 위해서는 반드시 영화를 선택해야 한다.
						 if  (($ArrSilmothet["Open"] != "") && ($ArrSilmothet["Film"] != ""))
						 {
							 $sDgrName   = get_degree($ArrSilmothet["Open"],$ArrSilmothet["Film"],$connect) ;
							 $sDgrpName  = get_degreepriv($ArrSilmothet["Open"],$ArrSilmothet["Film"],$connect) ;

							 $sQuery = "Select * From ".$sDgrName."                       ".
									   " Where Silmooja = '".$silmoojaCode."'             ".
									   "   And Open     = '".$ArrSilmothet["Open"]."'     ".
									   "   And Film     = '".$ArrSilmothet["Film"]."'     ".
									   "   And Theather = '".$ArrSilmothet["Theather"]."' ".
									   "   And Room     = '".$ArrSilmothet["Room"]."'     " ;
							 $qry_temp = mysql_query($sQuery,$connect) ;
							 if  ($temp_data  = mysql_fetch_array($qry_temp))
							 {
								 $sQuery = "Select * From ".$sDgrName."                       ".
										   " Where Silmooja = '".$silmoojaCode."'             ".
										   "   And Open     = '".$ArrSilmothet["Open"]."'     ".
										   "   And Film     = '".$ArrSilmothet["Film"]."'     ".
										   "   And Theather = '".$ArrSilmothet["Theather"]."' ".
										   "   And Room     = '".$ArrSilmothet["Room"]."'     " ;
								 $qry_degree = mysql_query($sQuery,$connect) ;
							 }
							 else
							 {
								 $sQuery = "Select * From ".$sDgrName."                       ".
										   " Where Theather = '".$ArrSilmothet["Theather"]."' ".
										   "   And Room     = '".$ArrSilmothet["Room"]."'     " ;
								 $qry_degree = mysql_query($sQuery,$connect) ;
							 }
	//echo "<br>".$sQuery ;
							 $degree_data = mysql_fetch_array($qry_degree) ;


							 $sQuery = "Select * From bas_unitprices                      ".
									   " Where Theather = '".$ArrSilmothet["Theather"]."' ".
									   "   And Room     = '".$ArrSilmothet["Room"]."'     " ;
	//echo "<br>".$sQuery ;
							 $qry_unitprices = mysql_query($sQuery,$connect) ;
							 $unitprices_data = mysql_fetch_array($qry_unitprices) ;

							 if  (($degree_data) && ($unitprices_data))
							 {
							 ?>

							 <!-- 실무자가 선택한 상영관에 스코어 전송으로 들어간다. -->
							 <a href="wrk_silmooja_7.php?M2005=No&WorkDate=<?=$WorkDate?>&ShowRoom=<? echo $ArrSilmothet["Theather"] . $ArrSilmothet["Room"]?>&BackAddr=wrk_silmooja.php">
							 (<? echo $showroomDiscript."-".$locationName.":".$ArrSilmothet["Title"] ; ?>)   <!-- 실무자가 선택한 상영관 -->
							 </a>
							 &nbsp;
							 <a href="wrk_silmooja_7.php?M2005=Yes&WorkDate=<?=$WorkDate?>&ShowRoom=<? echo $ArrSilmothet["Theather"] . $ArrSilmothet["Room"]?>&BackAddr=wrk_silmooja.php">*</a>
							 &nbsp;
							 <a href="wrk_silmooja_7A.php?M2005=Yes&WorkDate=<?=$WorkDate?>&ShowRoom=<? echo $ArrSilmothet["Theather"] . $ArrSilmothet["Room"]?>&BackAddr=wrk_silmooja.php">*</a>

							 <br><br>

							 <?
							 }
						 }
                     }
                 }
             }

        // 배급사에 지정된 사용자만 상대나 선재 현황보고를 할수 있다.
        $sQuery = "Select *                                      ".
                  "  From bas_filmtitlesilmooja       As FsTiSi, ".
                  "       bas_filmtitle               As FsTi    ".
                  " Where Silmooja    = '".$silmoojaCode."'      ".
                  "   And FsTiSi.Open = FsTi.Open                ".
                  "   And FsTiSi.Film = FsTi.Code                " ;
        $Qry_SpcUser = mysql_query($sQuery,$connect) ;
        if  ($Arr_SpcUser = mysql_fetch_array($Qry_SpcUser))
        {
            $FilmSupply = $Arr_SpcUser["FilmSupply"] ;


            ?>
            <li><b><a href='wrk_silmooja_10.php?WorkDate=<?=$WorkDate?>&BackAddr=wrk_silmooja.php'>상대영화보고</a></b></li><br>
            <?
            // 실무자가 선택한 상영관을 나열한다.
            $sQuery = "Select * From bas_silmoojasangdae       ".
                      " Where Silmooja  = '".$silmoojaCode."'  ".
                      "  Order By Showroom                     " ;
            $query1 = mysql_query($sQuery,$connect) ;
            while ($ArrSilmothet = mysql_fetch_array($query1))
            {
                $sQuery = "Select * From bas_theather                    ".
                          " Where Code = '".$ArrSilmothet["Theather"]."' " ;
                $query2 = mysql_query($sQuery,$connect) ;
                if  ($showroom_data = mysql_fetch_array($query2))
                {
                    $showroomTheather  = $showroom_data["Code"] ;
                    $showroomDiscript  = $showroom_data["Discript"] ;
                    $showroomLocation  = $showroom_data["Location"] ;

                    // 상영관의 소재지 지역을 구한다.
                    $sQuery = "Select * From bas_location            ".
                              " Where Code = '".$showroomLocation."' " ;
                    $query3 = mysql_query($sQuery,$connect) ;

                    $location_data = mysql_fetch_array($query3) ;

                    if  ($location_data)
                    {
                        $locationName = $location_data["Name"] ; // 상영관 소재지역 명
                    }
                }
                ?>
                <!-- 실무자가 선택한 상영관에 대한 세부 정보가 들어간다. -->
                <a OnClick="check_sangtheether('<?=$showroomTheather?>');">
                (<? echo $showroomDiscript."-".$locationName ; ?>)<br><br> <!-- 실무자가 선택한 상영관 -->
                </a>
                <?
            }
            ?>

            <li><b><a href='wrk_silmooja_12.php?WorkDate=<?=$WorkDate?>&BackAddr=wrk_silmooja.php'>선재현황보고</a></b></li><br>
            <?
            // 실무자가 선택한 상영관을 나열한다.
            $sQuery = "Select * From bas_silmoojasunjae        ".
                      " Where Silmooja  = '".$silmoojaCode."'  ".
                      "  Order By Showroom                     " ;
            $query1 = mysql_query($sQuery,$connect) ;
            while ($ArrSilmothet = mysql_fetch_array($query1))
            {
                $sQuery = "Select * From bas_theather                    ".
                          " Where Code = '".$ArrSilmothet["Theather"]."' " ;
                $query2 = mysql_query($sQuery,$connect) ;

                $showroom_data = mysql_fetch_array($query2) ;

                if  ($showroom_data)
                {
                    $showroomTheather  = $showroom_data["Code"] ;
                    $showroomDiscript  = $showroom_data["Discript"] ;
                    $showroomLocation  = $showroom_data["Location"] ;

                    // 상영관의 소재지 지역을 구한다.
                    $sQuery = "Select * From bas_location            ".
                              " Where Code = '".$showroomLocation."' " ;
                    $query3 = mysql_query($sQuery,$connect) ;

                    $location_data = mysql_fetch_array($query3) ;

                    if  ($location_data)
                    {
                        $locationName = $location_data["Name"] ; // 상영관 소재지역 명
                    }
                }
                ?>
                <!-- 실무자가 선택한 상영관에 대한 세부 정보가 들어간다. -->
                <a OnClick="check_suntheether('<?=$showroomTheather?>');">
                (<? echo $showroomDiscript."-".$locationName ; ?>)<br><br> <!-- 실무자가 선택한 상영관 -->
                </a>
                <?
            }

        }
        ?>
        <br>

        <li><b>
        <?
        if  (($silmoojaGongji != "none") && (trim($silmoojaGongji) != ""))
        {
            ?>
            <a href='<?=$PHP_SELF?>?WorkDate=<?=$WorkDate?>&logged_UserId=<?=$logged_UserId?>&logged_Name=<?=$logged_Name?>&GongjiDelete=Yes'>공지삭제</a>
            <?
        }
        ?>
        <a href='wrk_silmooja_gongji_list.php?WorkDate=<?=$WorkDate?>&BackAddr=wrk_silmooja.php'>공지현황</a>
        </b></li>
        <br>
        <br>
        <?

        ?>

        <li><b><a href='wrk_silmooja_siljuk.php?WorkDate=<?=$WorkDate?>&BackAddr=wrk_silmooja.php'>근무현황 성적</a></b></li><br>

    </tr>

    </table>

 </center>

   <? //echo "[".trim($silmoojaGongji)."]" ?>
   <? //if  (trim($silmoojaGongji) == "") echo "공백" ?>

   <?
   if  (($silmoojaGongji != "none") && (trim($silmoojaGongji) != ""))
   {
   ?>
      <script>
      var temp  = '<?=$silmoojaGongji?>'.split('<br>') ;
      var stemp = '' ;

      for(i=0;i<temp.length;i++)
      {
        stemp +=  temp[i]+'\n'  ;
      }
      alert(stemp) ;
      </script>
   <?
   }
   ?>

</body>

</html>

<?
        mysql_close($connect) ;
    }
?>
