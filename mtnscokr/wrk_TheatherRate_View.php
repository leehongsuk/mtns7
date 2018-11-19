<?
    set_time_limit(0) ; // 긴 처리작업이 있을 때만 쓴다.....

    session_start();

    $NBSP="&nbsp;" ;
?>

<!-- 일일 보고서 -->
<html>

    <link rel=stylesheet href=./LinkStyle.css type=text/css>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

    <head>
    <title>부율 입력</title>

    <style type="text/css" media="all">
        table {
         width: 350px;
         margin-bottom: 20px;
        }
        th, td {
         padding: 3px;
        }
        caption {
         text-align: left;
         color: #F60;
         padding: 10px;
         font-size: 1.2em;
         font-weight: bold;
        }

        #beauty,
        #use-th,
        #use-th-beauty{
         border-collapse: collapse;
         border: 1px solid #CCC;
        }
        #beauty {
        }
        #use-th {
        }
        #use-th-beauty {
         font-size: 0.9em;
         border: none;
        }
        #use-th-beauty td {
         border: 1px solid #CCC;
        }
        #use-th-beauty th {
         background: #366B9F url(th_bg.png) top repeat-x ;
         color: #FFF;
         height: 22px;
         border: 1px solid #A1C3E6;
        }
        #use-th-beauty th.row {
         background-color: #BDDBF9;
         background-image: none;
         height: auto;
         color: #356EAB;
         font-weight: normal;
        }
        #use-th-beauty td {
         padding-left: 5px;
        }
    </style>

</head>



<body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 >


<?
    // 정상적으로 로그인 했는지 체크한다.
    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";        // {[데이터 베이스]} : 환경설정

        $connect = dbconn() ;        // {[데이터 베이스]} : 연결

        mysql_select_db($cont_db) ;  // {[데이터 베이스]} : 디비선택

        if (!$FromDate)
        {
            $FromDate = date("Ymd",$Today) ;
        }
        if (!$ToDate)
        {
            $ToDate = date("Ymd",$Today) ;
        }

        $timestamp2 = mktime(0,0,0,substr($FromDate,4,2),substr($FromDate,6,2),substr($FromDate,0,4));
        $dur_time2  = (time() - $timestamp2) / 86400;

        $timestamp1 = mktime(0,0,0,substr($ToDate,4,2),substr($ToDate,6,2),substr($ToDate,0,4));
        $dur_time1  = (time() - $timestamp1) / 86400;

        $dur_day    = $dur_time2 - $dur_time1 + 1 ;  // 일수


        $Open = substr($FilmTile,0,6) ;
        $Film = substr($FilmTile,6,2) ;
		
		$TblTheatherRate = get_theather_rate($Open,$Film,$connect) ; //필름별 부율을 결정할 테이블을 생성 및 구한다.

        $SingoName     = get_singotable($Open,$Film,$connect) ;  // 신고 테이블 이름..
        $Showroomorder = get_showroomorder($Open,$Film,$connect) ;


        if  (($FilmTile != "") && ($FilmTile != "00000000")) // 특정영화만 선택하여 보고자 할 경우..
        {
            if  ($FilmTileFilm == '00') // 분리된영화의통합코드
            {
                $AddedCont .= " And Singo.Open = '".$Open."' " ;
            }
            else
            {
                $AddedCont .= " And Singo.Open = '".$Open."' ".
                              " And Singo.Film = '".$Film."' " ;
            }

            if   (($ZoneLoc == "9999") || ($ZoneLoc == "0")) // 전체
            {
            }
            else
            {
                 if  ($LocationCode=="100")
                 {
                     $AddedLoc .= " And Singo.Location = '".$LocationCode."' " ;
                 }
                 if  ($LocationCode=="200")
                 {

                     $AddedLoc = "\n And ( Singo.location = 200   \n".
                                 "      Or Singo.location = 600   \n".
                                 "      Or Singo.location = 207   \n".
                                 "      Or Singo.location = 201 ) \n" ;
                 }

                 if  (strlen($LocationCode) == 2)  // 구역
                 {
                     $AddedLoc = " And " ;

                     $sQuery = "Select Location                     \n".
                               "  From bas_filmsupplyzoneloc        \n".
                               " Where Zone = '".$LocationCode."'   \n" ; // Eq($sQuery) ;
                     $qryzoneloc = mysql_query($sQuery,$connect) ;
                     while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
                     {
                          if  ($AddedLoc == " And ")
                              $AddedLoc .= "( Singo.Location = '".$zoneloc_data["Location"]."' \n"  ;
                          else
                              $AddedLoc .= "    or Singo.Location = '".$zoneloc_data["Location"]."' \n"  ;
                     }
                     $AddedLoc .= ")" ;
                 }
                 $AddedCont .= " \n".$AddedLoc ;
            }
        }

        ?>
        <center>


        <br>
        <br>


        <table style='table-layout:fixed'  border="1" id="use-th-beauty">
             <tr>
                <th id="first" width=70 align=center>극장</th>
                <th width=150 align=center>극장명</th>
                <?
                for ($i=0 ; $i<$dur_day ; $i++)
                {
                   ?>
                   <th width=50 align=center>&nbsp;<B><?=date("d",$timestamp2 + ($i * 86400)) ;?></B>&nbsp;</th>
                   <?
                }
                ?>
             </tr>
        <?
        $TableOrder = $Showroomorder."_tmp" ;

        drop_table($TableOrder,$connect) ;
        create_tbleorder($TableOrder,$Showroomorder,$connect) ;


        $sQuery = "Select Singo.Theather,                          \n".
                  "       Singo.Open,                              \n".
                  "       Singo.Film,                              \n".
                  "       Theather.Discript,                       \n".
                  "       Theather.Location                        \n".
                  "  From ".$SingoName."     As Singo,             \n".
                  "       ".$TableOrder."    As TableOrder,        \n".
                  "       bas_theather       As Theather,          \n".
                  "       bas_silmooja       As Silmooja,          \n".
                  "       bas_location       As Location           \n".
                  " Where Singo.Silmooja   = Silmooja.Code         \n".
                  "   And Singo.Theather   = Theather.Code         \n".
                  "   And Singo.Location   = Location.Code         \n".
                  "   And Theather.Code    = TableOrder.Theather   \n".
                  $AddedCont."                                     \n".
                  " Group By Singo.Theather,                       \n".
                  "          Singo.Open,                           \n".
                  "          Singo.Film                            \n".
                  " Order By TableOrder.Seq                        \n" ; //Eq($sQuery) ;
        $QrySingo = mysql_query($sQuery,$connect) ;
        while  ($ArrSingo = mysql_fetch_array($QrySingo))
        {
             $singoTheather    = $ArrSingo["Theather"] ;      // 신고상영관
             $singoOpen        = $ArrSingo["Open"] ;          // 신고영화
             $singoFilm        = $ArrSingo["Film"] ;          //

             $showroomDiscript = $ArrSingo["Discript"] ;      // 신고 상영관명
             $showroomLocation = $ArrSingo["Location"] ;      // 신고 상영관지역

             $locationName     = $ArrSingo["LocationName"] ;  // 신고 상영관지역명

             ?>
             <tr>
					<th class="row" align=center><?=$singoTheather?></th>
					<td><?=$showroomDiscript?></td>
					<?
					$theather_rate_default = get_theather_rate_value_default($LocationCode,$singoTheather,$singoOpen,$singoFilm,$connect) ; // 해당극장의 해당필름의 디폴트 부율
					
					for ($i=0 ; $i<$dur_day ; $i++)
					{
						$WorkDate = date("Ymd",$timestamp2 + ($i * 86400)) ;

						//$TheatherRate  = get_theather_rate_value($WorkDate,$showroomLocation,$singoTheather,$singoOpen,$singoFilm,$connect) ;
						$TheatherRate = get_theather_rate_value_date($TblTheatherRate,$theather_rate_default,$WorkDate,$singoTheather,$singoOpen,$singoFilm,$connect) ;

						?><td width=60 align=center>&nbsp;<?=$TheatherRate?>&nbsp;</td><?
					}
					?>
             </tr>
             <?
        }
        ?>
        </table>

        <br>
        <br>


        <br>
        <br>

        </center>



        <?
        mysql_close($connect);       // {[데이터 베이스]} : 단절
    }
    else
    {
        ?>
        <!-- 로그인하지 않고 바로들어온다면 -->
        <script language="JavaScript">
            <!--
            window.top.location = '../index_net.php' ;
            //-->
        </script>
        <?
    }
?>




</body>

</html>
