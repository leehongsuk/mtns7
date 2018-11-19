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


  <script language="JavaScript">
      <!--
      function ChgTheatherRate()
      {
           frmMain.Changing.value = "Yes" ;
           return true ;
      }
      //-->
  </script>

</head>



<body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 >


<?
    // 정상적으로 로그인 했는지 체크한다.
    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";        // {[데이터 베이스]} : 환경설정

        $connect = dbconn() ;        // {[데이터 베이스]} : 연결

        mysql_select_db($cont_db) ;  // {[데이터 베이스]} : 디비선택

        $Open = substr($FilmTile,0,6) ;
        $Film = substr($FilmTile,6,2) ;

		$TblTheatherRate = get_theather_rate($Open,$Film,$connect) ; //필름별 부율을 결정할 테이블을 생성 및 구한다.

        $SingoName     = get_singotable($Open,$Film,$connect) ;  // 신고 테이블 이름..
        $Showroomorder = get_showroomorder($Open,$Film,$connect) ;
        $TblTheatherRate  = get_theather_rate($Open,$Film,$connect) ;

        if  (($LocCode) || ($ZoneCode))
        {
            if  ($LocCode != "") // 특정지역(3)만 선택적으로 보고자 할 경우
            {
                $sQuery = "Select * From bas_location   ".
                          " Where Code = '".$LocCode."' " ;
                $QryLocation = mysql_query($sQuery,$connect) ;
                if  ($ArrLocation = mysql_fetch_array($QryLocation))
                {
                    $LocationName = $ArrLocation["Name"] ; // 지역이름
                }

                if  ($LocCode=="200") // 예상의 부산은 (부산+울산+김해+창원)
                {
                    $AddedCont = " And (Singo.Location = '200'  ".
                                 " Or Singo.Location = '202'  ".
                                 " Or Singo.Location = '600'  ".
                                 " Or Singo.Location = '207'  ".
                                 " Or Singo.Location = '205'  ".
                                 " Or Singo.Location = '208'  ".
                                 " Or Singo.Location = '202'  ".
                                 " Or Singo.Location = '211'  ".
                                 " Or Singo.Location = '212'  ".
                                 " Or Singo.Location = '213'  ".
                                 " Or Singo.Location = '201') " ;
                }
                else
                {
                    $AddedCont = " And Singo.Location = '".$LocCode."' " ;
                }
            }


            if  ($ZoneCode != "") // 특정구역(2)만 선택적으로 보고자 할 경우
            {
                $sQuery = "Select * From bas_zone          ".
                          " Where Code = '".$ZoneCode."'   " ;  //Eq($sQuery) ;
                $QryZone = mysql_query($sQuery,$connect) ;
                if  ($ArrZone = mysql_fetch_array($QryZone))
                {
                    $ZoneName = $ArrZone["Name"] ; // 구역이름
                }


                $AddedCont = "" ;

                $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                          " Where Zone  = '".$ZoneCode."'        " ;
                $QryZoneloc = mysql_query($sQuery,$connect) ;
                while ($ArrZoneloc = mysql_fetch_array($QryZoneloc))
                {
                    if  ($AddedCont == "")
                    {
                        $AddedCont .= " And  ( Singo.Location = '".$ArrZoneloc["Location"]."' " ;
                    }
                    else
                    {
                        $AddedCont .= " Or Singo.Location = '".$ArrZoneloc["Location"]."' " ;
                    }
                }

                if  ($AddedCont != "")
                {
                    if  ($ZoneCode == '20') // 경남인경우 부산을 포함한다.
                    {
                         $AddedCont .= " Or Singo.Location  = '200' ".
                                       " Or Singo.Location <> '600' ".  // 울산
                                       " Or Singo.Location <> '207' ".  // 김해
                                       " Or Singo.Location <> '205' ".  // 진주
                                       " Or Singo.Location <> '208' ".  // 거제
                                       " Or Singo.Location <> '202' ".  // 마산
                                       " Or Singo.Location <> '211' ".  // 사천
                                       " Or Singo.Location <> '212' ".  // 거창
                                       " Or Singo.Location <> '213' ".  // 양산
                                       " Or Singo.Location <> '201' " ; // 창원
                    }
                    $AddedCont .= ")" ;
                }
            }
        }

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
        }

        ?>
        <center>


        <br>
        <br>
        <form method=post action="" name="frmMain">

        <table border="1" id="use-th-beauty">
             <tr>
                <th id="first" width=70 align=center>극장</th>
                <th width=200 align=center>극장명</th>
                <th width=70 align=center>부율</th>
             </tr>
        <?



        $TableOrder = $Showroomorder."_tmp" ;

        drop_table($TableOrder,$connect) ;
		create_tbleorder($TableOrder,$Showroomorder,$connect) ;

        $sQuery = "Select Singo.Theather,                          ".
                  "       Singo.Open,                              ".
                  "       Singo.Film,                              ".
                  "       Theather.Discript,                       ".
                  "       Theather.Location                        ".
                  "  From ".$SingoName."     As Singo,             ".
                  "       ".$TableOrder."    As TableOrder,       ".
                  "       bas_theather       As Theather,          ".
                  "       bas_silmooja       As Silmooja,          ".
                  "       bas_location       As Location           ".
                  " Where Singo.Silmooja   = Silmooja.Code         ".
                  "   And Singo.Theather   = Theather.Code         ".
                  "   And Singo.Location   = Location.Code         ".
                  "   And Theather.Code    = TableOrder.Theather   ".
                  $AddedCont."                                     ".
                  " Group By Singo.Theather,                       ".
                  "          Singo.Open,                           ".
                  "          Singo.Film                            ".
                  " Order By TableOrder.Seq                       " ; // Eq($sQuery) ;
        $QrySingo = mysql_query($sQuery,$connect);
        while  ($ArrSingo = mysql_fetch_array($QrySingo))
        {
             $singoTheather    = $ArrSingo["Theather"] ;      // 신고상영관
             $singoOpen        = $ArrSingo["Open"] ;          // 신고영화
             $singoFilm        = $ArrSingo["Film"] ;          //

             $showroomDiscript = $ArrSingo["Discript"] ;      // 신고 상영관명
             $showroomLocation = $ArrSingo["Location"] ;      // 신고 상영관지역

             $locationName     = $ArrSingo["LocationName"] ;  // 신고 상영관지역명


             //$TheatherRate  = get_theather_rate_value($WorkDate,$showroomLocation,$singoTheather,$singoOpen,$singoFilm,$connect) ;

			 $theather_rate_default = get_theather_rate_value_default($showroomLocation,$singoTheather,$singoOpen,$singoFilm,$connect) ; // 해당극장의 해당필름의 디폴트 부율
			 $TheatherRate = get_theather_rate_value_date($TblTheatherRate,$theather_rate_default,$WorkDate,$singoTheather,$singoOpen,$singoFilm,$connect) ;


             $RateValue = "T".$singoTheather ;
             if  ($$RateValue)
             {
                 $TheatherRate = $$RateValue ;    // 새로 입력된 부율

                 $sQuery = "Update ".$TblTheatherRate."             ".
                           "   Set Rate     = '".$TheatherRate."'   ".
                           " Where WorkDate = '".$WorkDate."'       ".
                           "   And Theather = '".$singoTheather."'  ".
                           "   And Open     = '".$singoOpen."'      ".
                           "   And Film     = '".$singoFilm."'      " ;
                 mysql_query($sQuery,$connect);
             }


             $sQuery = "Update bas_theather_rate                ".
                       "   Set Rate = '".$TheatherRate."'       ".
                       " Where Theather = '".$singoTheather."'  ".
                       "   And Open     = '".$singoOpen."'      ".
                       "   And Film     = '".$singoFilm."'      " ;
             $QryTheatherRate = mysql_query($sQuery,$connect);
             ?>
             <tr>
                <th class="row" align=center><?=$singoTheather?></th>
                <td><?=$showroomDiscript?></td>
                <td align=center><input size=5 type="text" name=T<?=$singoTheather?> value="<?=$TheatherRate?>"  style="text-align:right;"></td>
             </tr>
             <?
        }
        ?>
        </table>

        <br>
        <br>

        <input type="hidden" name="Changing" value="">
        <input type="hidden" name="WorkDate" value="<?=$WorkDate?>">
        <input type="submit" value="확인"  onclick="return ChgTheatherRate()">



        </form>

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
