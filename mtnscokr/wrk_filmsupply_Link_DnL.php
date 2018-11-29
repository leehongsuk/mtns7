<?
  session_start();
?>
<?
  set_time_limit(0) ; // 긴 처리작업이 있을 때만 쓴다.....


  function Get_HTTP_GET_VARS()
  {
      global $PAGE ;
      global $HTTP_GET_VARS ;
      global $ReturnStr ;

      if  ( $HTTP_GET_VARS )
      {
          foreach( $HTTP_GET_VARS AS $key => $val )
          {
                 if  (($key != "silmoojaCode")
                      and ($key != "EndShowRoom")
                      and ($key != "EndFilmTitle"))
                 {
                      $PAGE[$key] = $val;
                      $ReturnStr .= $key.'='.$val."&";
                 }
          }

          return substr($ReturnStr,0,strlen($ReturnStr)-1) ;
      }
      else
      {
          return "" ;
      }
  }

?>


<html>
<?
    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";        // {[데이터 베이스]} : 환경설정

        $connect = dbconn() ;        // {[데이터 베이스]} : 연결

        mysql_select_db($cont_db) ;  // {[데이터 베이스]} : 디비선택


        $FilmOpen = substr($FilmTile,0,6) ;
        $FilmCode = substr($FilmTile,6,2) ;

        $Theather = substr($ShowRoom,0,4) ;
        $Room     = substr($ShowRoom,4,2) ;

        $sSingoName     = get_singotable($FilmOpen,$FilmCode,$connect) ;  // 신고 테이블 이름..
        $sShowroomorder = get_showroomorder($FilmOpen,$FilmCode,$connect) ;


        if  ($ActionCode=="Delete") // 한 상영관의 신고건 전체를 삭제한다.
        {
            mysql_query("Delete From ".$sSingoName."             ".
                        " Where SingoDate = '".$WorkDate."'      ".
                        "   And Silmooja  = '".$silmoojaCode."'  ".
                        "   And Theather  = '".$Theather."'      ".
                        "   And Room      = '".$Room."'          ",$connect) ;
        }



        if  ($EndShowRoom!="") // 종영보고
        {
            // 실무자가 지정한 상영관을 해제한다.
            mysql_query("Delete From bas_silmoojatheather                   ".
                        " Where Silmooja  = '".$silmoojaCode."'             ".
                        "   And Theather  = '".substr($EndShowRoom,0,4)."'  ".
                        "   And Room      = '".substr($EndShowRoom,4,2)."'  ",$connect) ;

            // 실무자가 지정한 상영관을 해제한다.
            mysql_query("Delete From bas_silmoojatheatherpriv               ".
                        " Where Silmooja  = '".$silmoojaCode."'             ".
                        "   And WorkDate  = '".$WorkDate."'                 ".
                        "   And Theather  = '".substr($EndShowRoom,0,4)."'  ".
                        "   And Room      = '".substr($EndShowRoom,4,2)."'  ",$connect) ;

            // 종영되는 상영관의 이름을 구한다.
            $qry_showroom = mysql_query("Select * From bas_showroom                         ".
                                        " Where Theather  = '".substr($EndShowRoom,0,4)."'  ".
                                        "   And Room      = '".substr($EndShowRoom,4,2)."'  ",$connect) ;
            if  ($showroom_data = mysql_fetch_array($qry_showroom))
            {
                $showroomDiscript = $showroom_data["Discript"] ; // 종영되는 상영관의 이름
            }

            // 종영되는 상영관의 영화를 구한다.
            $qry_filmtitle = mysql_query("Select * From bas_filmtitle                   ".
                                         " Where Open = '".substr($EndFilmTitle,0,6)."' ".
                                         "   And Code = '".substr($EndFilmTitle,6,2)."' ",$connect) ;
            if  ($filmtitle_data = mysql_fetch_array($qry_filmtitle))
            {
                $filmtitleName = $filmtitle_data["Name"] ; // 종영되는 상영관의 영화
            }
            // 실무자가 지정한 상영관종영정보를 만든다
            mysql_query("Insert Into bas_silmoojatheatherfinish   ".
                        "Values ('".$silmoojaCode."',             ".
                        "        '".$TmroDate."',                 ".
                        "        '".substr($EndShowRoom,0,4)."',  ".
                        "        '".substr($EndShowRoom,4,2)."',  ".
                        "        '".substr($EndFilmTitle,0,6)."', ".
                        "        '".substr($EndFilmTitle,6,2)."', ".
                        "        '".$silmoojaName."',             ".
                        "        '".$showroomDiscript."',         ".
                        "        '".$filmtitleName."'             ".
                        "        )                                ",$connect) ;

            // 상영관내 실무자 정보를 지운다 (실제적으로는 아무의미없음)
            mysql_query("Update bas_showroom                                ".
                        "   Set Silmooja     = NULL,                        ".
                        "       SilmoojaName = NULL                         ".
                        " Where Theather  = '".substr($EndShowRoom,0,4)."'  ".
                        "   And Room      = '".substr($EndShowRoom,4,2)."'  ",$connect) ;
        }
?>
<link rel=stylesheet href=./LinkStyle.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>SMS현황</title>
</head>

<script language='JavaScript'>

function Timer(sec)
{
   if  (sec==0)  //self.location = '<?=$PHP_SELF?>' ;
   document.location.reload();

   count.innerHTML = sec;

   sec -= 1;

   window.setTimeout('Timer('+sec+')',1000);

}

</script>

<?
$sec = 60 ;
?>

<body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 >
<!--onload='Timer(<?=$sec?>)'-->


   <script>


         //
         // 엑셀 출력
         //
         function toexel_click()
         {
             <?
             if  ($filmproduce)
             {
             ?>
             botttomaddr = 'wrk_filmsupply_Link_DnL.php?'
                         + 'logged_UserId=<?=$logged_UserId?>&'
                         + 'FilmTile=<?=$FilmTile?>&'
                         + 'WorkDate=<?=$WorkDate?>&'
                         + 'WorkGubun=<?=$WorkGubun?>&'
                         + 'LocationCode=<?=$LocationCode?>&'
                         + 'ZoneCode=<?=$ZoneCode?>&'
                         + 'filmproduce=<?=$filmproduce?>&'
                         + 'ToExel=Yes' ;
             <?
             }
             else
             {
             ?>
             botttomaddr = 'wrk_filmsupply_Link_DnL.php?'
                         + 'logged_UserId=<?=$logged_UserId?>&'
                         + 'FilmTile=<?=$FilmTile?>&'
                         + 'WorkDate=<?=$WorkDate?>&'
                         + 'WorkGubun=<?=$WorkGubun?>&'
                         + 'LocationCode=<?=$LocationCode?>&'
                         + 'ZoneCode=<?=$ZoneCode?>&'
                         + 'ToExel=Yes' ;
             <?
             }
             ?>
             //alert(botttomaddr) ;

             top.frames.bottom.location.href = botttomaddr ;
         }

   </script>


<center>

  <br><br>
  <b>SMS현황</b>

  <!--                 -->
  <!-- 세부스코어 집계 -->
  <!--                 -->

  <br>
  <span id='count'></span>
  <br>


   <?
   //echo "구역=".$ZoneCode. " - 지역=".$LocationCode. " - 영화=".$FilmTile ;

   if   ((!$FilmTile) && (!$ZoneCode) && (!$LocationCode)) //
   {
       //echo "없음" ;
   }
   else
   {
       if   ($FilmCode == '00') // 분리된영화의통합코드
       {
            $FilmCond = " Open = '".$FilmOpen."' " ;
       }
       else
       {
            $FilmCond = "    Open = '".$FilmOpen."' ".
                        "And Film = '".$FilmCode."' " ;
       }

       $silmoojaCode = "111111" ;

       if   ($ZoneCode=="9999") // "전체"
       {
           $filmtitleNameTitle = "" ; // 두번이상 반복되면 영화명을 지우기 위해 ..

           //-----------
           // 서울 출력
           //-----------
           $zoneName  = "서울" ;
           $AddedCont = " And  Singo.Location = '100' " ;

           if  (($FilmTile != "") && ($FilmTile != "00000000")) // 특정영화만 선택하여 보고자 할 경우..
           {
               if   ($FilmCode == '00') // 분리된영화의통합코드
               {
                    $AddedCont .= " And Singo.Open = '".$FilmOpen."' " ;
                    $OrderCont = " Order By Showroom.Discript,                    ".
                                 "          Singo.Theather,                       ".
                                 "          Singo.Room                            " ;
               }
               else
               {
                    $AddedCont .= " And Singo.Open = '".$FilmOpen."' " .
                                  " And Singo.Film = '".$FilmCode."' " ;
                    $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                 "          Showroom.Discript,                    ".
                                 "          Singo.Theather,                       ".
                                 "          Singo.Room                            " ;
               }
           }

           $qry_CntLocat = mysql_query("SELECT Count( DISTINCT Singo.Theather,       ".
                                       "                       Singo.Room,           ".
                                       "                       Singo.Open,           ".
                                       "                       Singo.Film,           ".
                                       "                       Singo.Phoneno )       ".
                                       "              AS CntLocat                    ".
                                       "  FROM ".$sSingoName." AS Singo              ".
                                       " WHERE Singo.Singodate = '".$WorkDate."'     ".
                                       "   AND Singo.Silmooja = '".$silmoojaCode."'  ".
                                       $AddedCont                                     ,$connect) ;

           if  ($CntLocat_data = mysql_fetch_array($qry_CntLocat))
           {
               $CntLocat = $CntLocat_data["CntLocat"] ;
               $FirstLoc = true ;
           }



           $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                    "       Singo.Theather,                          ".
                                    "       Singo.Room,                              ".
                                    "       Singo.Open,                              ".
                                    "       Singo.Film,                              ".
                                    "       Showroom.Discript,                       ".
                                    "       Showroom.Location,                       ".
                                    "       FilmTitle.Name As FilmTitleName,         ".
                                    "       Count(distinct ShowDgree) As CntDgree    ".
                                    "  From ".$sSingoName."   As Singo,              ".
                                    "       bas_showroom      As Showroom,           ".
                                    "       ".$sShowroomorder." As ShowroomOrder,    ".
                                    "       bas_filmtitle     As FilmTitle           ".
                                    " Where Singo.Singodate  = '".$WorkDate."'       ".
                                    "   And Singo.Theather   = Showroom.Theather     ".
                                    "   And Singo.Room       = Showroom.Room         ".
                                    "   And Singo.Theather   = ShowroomOrder.Theather".
                                    "   And Singo.Room       = ShowroomOrder.Room    ".
                                    "   And Singo.Open       = FilmTitle.Open        ".
                                    "   And Singo.Film       = FilmTitle.Code	       ".
                                    "   And Singo.Silmooja   = '".$silmoojaCode."'   ".
                                    $AddedCont                                        .
                                    " Group By Singo.Theather,                       ".
                                    "          Singo.Room,                           ".
                                    "          Singo.Open,                           ".
                                    "          Singo.Film,                           ".
                                    "          Showroom.Discript                     ".
                                    $OrderCont                                        ,$connect) ;
           //$filmtitleNameTitle = "" ; // 두번이상 반복되면 영화명을 지우기 위해 ..

           include "wrk_filmsupply_Link_DnL1.php";




           //-----------
           // 경기출력
           //-----------
           $zoneName  = "경기" ;
           $AddedCont = "" ;

           $qryzoneloc = mysql_query("select Location from bas_filmsupplyzoneloc ".
                                     " Where Zone = '04'                         ",$connect) ;

           $AddedLoc = " And " ;


           while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
           {
                if  ($AddedLoc == " And ")
                    $AddedLoc .= "( Singo.Location = '".$zoneloc_data["Location"]."' "  ;
                else
                    $AddedLoc .= " or Singo.Location = '".$zoneloc_data["Location"]."' "  ;
           }
           $AddedLoc .= ")" ;

           // 경기


           if  (($FilmTile != "") && ($FilmTile != "00000000")) // 특정영화만 선택하여 보고자 할 경우..
           {
               if   ($FilmCode == '00') // 분리된영화의통합코드
               {
                    $AddedCont .= " And Singo.Open = '".$FilmOpen."' " ;
                    $OrderCont = " Order By Showroom.Discript,                    ".
                                 "          Singo.Theather,                       ".
                                 "          Singo.Room                            " ;
               }
               else
               {
                    $AddedCont .= " And Singo.Open = '".$FilmOpen."' " .
                                  " And Singo.Film = '".$FilmCode."' " ;
                    $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                 "          Showroom.Discript,                    ".
                                 "          Singo.Theather,                       ".
                                 "          Singo.Room                            " ;
               }
           }

           $qry_CntLocat = mysql_query("SELECT Count( DISTINCT Singo.Theather,       ".
                                       "                       Singo.Room,           ".
                                       "                       Singo.Open,           ".
                                       "                       Singo.Film,           ".
                                       "                       Singo.Phoneno )       ".
                                       "              AS CntLocat                    ".
                                       "  FROM ".$sSingoName." AS Singo              ".
                                       " WHERE Singo.Singodate = '".$WorkDate."'     ".
                                       "   AND Singo.Silmooja = '".$silmoojaCode."'  ".
                                       $AddedCont                                     .
                                       $AddedLoc                                      ,$connect) ;

           if  ($CntLocat_data = mysql_fetch_array($qry_CntLocat))
           {
               $CntLocat = $CntLocat_data["CntLocat"] ;
               $FirstLoc = true ;
           }

           $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                    "       Singo.Theather,                          ".
                                    "       Singo.Room,                              ".
                                    "       Singo.Open,                              ".
                                    "       Singo.Film,                              ".
                                    "       Showroom.Discript,                       ".
                                    "       Showroom.Location,                       ".
                                    "       FilmTitle.Name As FilmTitleName,         ".
                                    "       Count(distinct ShowDgree) As CntDgree    ".
                                    "  From ".$sSingoName."   As Singo,              ".
                                    "       bas_showroom      As Showroom,           ".
                                    "       ".$sShowroomorder." As ShowroomOrder,    ".
                                    "       bas_filmtitle     As FilmTitle           ".
                                    " Where Singo.Singodate  = '".$WorkDate."'       ".
                                    "   And Singo.Theather   = Showroom.Theather     ".
                                    "   And Singo.Room       = Showroom.Room         ".
                                    "   And Singo.Theather   = ShowroomOrder.Theather".
                                    "   And Singo.Room       = ShowroomOrder.Room    ".
                                    "   And Singo.Open       = FilmTitle.Open        ".
                                    "   And Singo.Film       = FilmTitle.Code	       ".
                                    "   And Singo.Silmooja   = '".$silmoojaCode."'   ".
                                    $AddedCont                                        .
                                    $AddedLoc                                         .
                                    " Group By Singo.Theather,                       ".
                                    "          Singo.Room,                           ".
                                    "          Singo.Open,                           ".
                                    "          Singo.Film,                           ".
                                    "          Showroom.Discript                     ".
                                    $OrderCont                                        ,$connect) ;
           //$filmtitleNameTitle = "" ; // 두번이상 반복되면 영화명을 지우기 위해 ..

           include "wrk_filmsupply_Link_DnL1.php";






           //-----------
           // 부산 출력
           //-----------

           $zoneName  = "부산" ;
           $AddedCont = " And ( Singo.Location = '200'   " . // 부산
                        "  or   Singo.Location = '203'   " . // 툥영
                        "  or   Singo.Location = '600'   " . // 울산
                        "  or   Singo.Location = '207'   " . // 김해
                        "  or   Singo.Location = '205'   " . // 진주
                        "  or   Singo.Location = '208'   " . // 거제
                        "  or   Singo.Location = '202'   " . // 마산
                        "  or   Singo.Location = '211'   " . // 사천
                        "  or   Singo.Location = '212'   " . // 거창
                        "  or   Singo.Location = '213'   " . // 양산
                        "  or   Singo.Location = '201' ) " ; // 창원


           if  (($FilmTile != "") && ($FilmTile != "00000000")) // 특정영화만 선택하여 보고자 할 경우..
           {
               if   ($FilmCode == '00') // 분리된영화의통합코드
               {
                    $AddedCont .= " And Singo.Open = '".$FilmOpen."' " ;
                    $OrderCont = " Order By Showroom.Discript,                    ".
                                 "          Singo.Theather,                       ".
                                 "          Singo.Room                            " ;
               }
               else
               {
                    $AddedCont .= " And Singo.Open = '".$FilmOpen."' " .
                                  " And Singo.Film = '".$FilmCode."' " ;
                    $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                 "          Showroom.Discript,                    ".
                                 "          Singo.Theather,                       ".
                                 "          Singo.Room                            " ;
               }
           }

           $qry_CntLocat = mysql_query("SELECT Count( DISTINCT Singo.Theather,       ".
                                       "                       Singo.Room,           ".
                                       "                       Singo.Open,           ".
                                       "                       Singo.Film,           ".
                                       "                       Singo.Phoneno )       ".
                                       "              AS CntLocat                    ".
                                       "  FROM ".$sSingoName." AS Singo              ".
                                       " WHERE Singo.Singodate = '".$WorkDate."'     ".
                                       "   AND Singo.Silmooja = '".$silmoojaCode."'  ".
                                       $AddedCont                                     ,$connect) ;

           if  ($CntLocat_data = mysql_fetch_array($qry_CntLocat))
           {
               $CntLocat = $CntLocat_data["CntLocat"] ;
               $FirstLoc = true ;
           }



           $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                    "       Singo.Theather,                          ".
                                    "       Singo.Room,                              ".
                                    "       Singo.Open,                              ".
                                    "       Singo.Film,                              ".
                                    "       Showroom.Discript,                       ".
                                    "       Showroom.Location,                       ".
                                    "       FilmTitle.Name As FilmTitleName,         ".
                                    "       Count(distinct ShowDgree) As CntDgree    ".
                                    "  From ".$sSingoName."   As Singo,              ".
                                    "       bas_showroom      As Showroom,           ".
                                    "       ".$sShowroomorder." As ShowroomOrder,    ".
                                    "       bas_filmtitle     As FilmTitle           ".
                                    " Where Singo.Singodate  = '".$WorkDate."'       ".
                                    "   And Singo.Theather   = Showroom.Theather     ".
                                    "   And Singo.Room       = Showroom.Room         ".
                                    "   And Singo.Theather   = ShowroomOrder.Theather".
                                    "   And Singo.Room       = ShowroomOrder.Room    ".
                                    "   And Singo.Open       = FilmTitle.Open        ".
                                    "   And Singo.Film       = FilmTitle.Code	       ".
                                    "   And Singo.Silmooja   = '".$silmoojaCode."'   ".
                                    $AddedCont                                        .
                                    " Group By Singo.Theather,                       ".
                                    "          Singo.Room,                           ".
                                    "          Singo.Open,                           ".
                                    "          Singo.Film,                           ".
                                    "          Showroom.Discript                     ".
                                    $OrderCont                                        ,$connect) ;
           //$filmtitleNameTitle = "" ; // 두번이상 반복되면 영화명을 지우기 위해 ..

           include "wrk_filmsupply_Link_DnL1.php";




           //-----------
           // 경강 출력
           //-----------
           $zoneName  = "경강" ;
           $query1 = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                 " Where Zone  = '10'                   ",$connect) ;

           $AddedCont = " And " ;
           while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
           {
               if  ($AddedCont == " And ")
               {
                   $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
               else
               {
                   $AddedCont .= " Or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
           }

           if  ($AddedCont != " And ")
           {
               $AddedCont .= ")" ;
           }
           else
           {
               $AddedCont = "" ;
           }

           if  ($AddedCont != "") // 경강지역에 해당하는 자료가 있는경우..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // 특정영화만 선택하여 보고자 할 경우..
               {
                   if   ($FilmCode == '00') // 분리된영화의통합코드
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmOpen."' " ;
                        $OrderCont = " Order By Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                   }
                   else
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmOpen."' " .
                                      " And Singo.Film = '".$FilmCode."' " ;
                        $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                     "          Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                   }
               }

               $qry_CntLocat = mysql_query("SELECT Count( DISTINCT Singo.Theather,       ".
                                           "                       Singo.Room,           ".
                                           "                       Singo.Open,           ".
                                           "                       Singo.Film,           ".
                                           "                       Singo.Phoneno )       ".
                                           "              AS CntLocat                    ".
                                           "  FROM ".$sSingoName." AS Singo              ".
                                           " WHERE Singo.Singodate = '".$WorkDate."'     ".
                                           "   AND Singo.Silmooja = '".$silmoojaCode."'  ".
                                           $AddedCont                                     ,$connect) ;

               if  ($CntLocat_data = mysql_fetch_array($qry_CntLocat))
               {
                   $CntLocat = $CntLocat_data["CntLocat"] ;
                   $FirstLoc = true ;
               }



               $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                        "       Singo.Theather,                          ".
                                        "       Singo.Room,                              ".
                                        "       Singo.Open,                              ".
                                        "       Singo.Film,                              ".
                                        "       Showroom.Discript,                       ".
                                        "       Showroom.Location,                       ".
                                        "       FilmTitle.Name As FilmTitleName,         ".
                                        "       Count(distinct ShowDgree) As CntDgree    ".
                                        "  From ".$sSingoName."   As Singo,              ".
                                        "       bas_showroom      As Showroom,           ".
                                        "       ".$sShowroomorder." As ShowroomOrder,    ".
                                        "       bas_filmtitle     As FilmTitle           ".
                                        " Where Singo.Singodate  = '".$WorkDate."'       ".
                                        "   And Singo.Theather   = Showroom.Theather     ".
                                        "   And Singo.Room       = Showroom.Room         ".
                                        "   And Singo.Theather   = ShowroomOrder.Theather".
                                        "   And Singo.Room       = ShowroomOrder.Room    ".
                                        "   And Singo.Open       = FilmTitle.Open        ".
                                        "   And Singo.Film       = FilmTitle.Code	       ".
                                        "   And Singo.Silmooja   = '".$silmoojaCode."'   ".
                                        $AddedCont                                        .
                                        " Group By Singo.Theather,                       ".
                                        "          Singo.Room,                           ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Showroom.Discript                     ".
                                        $OrderCont                                        ,$connect) ;
               //$filmtitleNameTitle = "" ; // 두번이상 반복되면 영화명을 지우기 위해 ..

               include "wrk_filmsupply_Link_DnL1.php";
           }

           //-----------
           // 충청 출력
           //-----------
           $zoneName  = "충청" ;
           $query1 = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                 " Where Zone  = '35'                   ",$connect) ;

           $AddedCont = " And " ;
           while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
           {
               if  ($AddedCont == " And ")
               {
                   $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
               else
               {
                   $AddedCont .= " Or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
           }

           if  ($AddedCont != " And ")
           {
               $AddedCont .= ")" ;
           }
           else
           {
               $AddedCont = "" ;
           }

           if  ($AddedCont != "") // 충청지역에 해당하는 자료가 있는경우..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // 특정영화만 선택하여 보고자 할 경우..
               {
                   if   ($FilmCode == '00') // 분리된영화의통합코드
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmOpen."' " ;
                        $OrderCont = " Order By Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                   }
                   else
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmOpen."' " .
                                      " And Singo.Film = '".$FilmCode."' " ;
                        $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                     "          Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                   }
               }

               $qry_CntLocat = mysql_query("SELECT Count( DISTINCT Singo.Theather,       ".
                                           "                       Singo.Room,           ".
                                           "                       Singo.Open,           ".
                                           "                       Singo.Film,           ".
                                           "                       Singo.Phoneno )       ".
                                           "              AS CntLocat                    ".
                                           "  FROM ".$sSingoName." AS Singo              ".
                                           " WHERE Singo.Singodate = '".$WorkDate."'     ".
                                           "   AND Singo.Silmooja = '".$silmoojaCode."'  ".
                                           $AddedCont                                     ,$connect) ;

               if  ($CntLocat_data = mysql_fetch_array($qry_CntLocat))
               {
                   $CntLocat = $CntLocat_data["CntLocat"] ;
                   $FirstLoc = true ;
               }



               $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                        "       Singo.Theather,                          ".
                                        "       Singo.Room,                              ".
                                        "       Singo.Open,                              ".
                                        "       Singo.Film,                              ".
                                        "       Showroom.Discript,                       ".
                                        "       Showroom.Location,                       ".
                                        "       FilmTitle.Name As FilmTitleName,         ".
                                        "       Count(distinct ShowDgree) As CntDgree    ".
                                        "  From ".$sSingoName."   As Singo,              ".
                                        "       bas_showroom      As Showroom,           ".
                                        "       ".$sShowroomorder." As ShowroomOrder,    ".
                                        "       bas_filmtitle     As FilmTitle           ".
                                        " Where Singo.Singodate  = '".$WorkDate."'       ".
                                        "   And Singo.Theather   = Showroom.Theather     ".
                                        "   And Singo.Room       = Showroom.Room         ".
                                        "   And Singo.Theather   = ShowroomOrder.Theather".
                                        "   And Singo.Room       = ShowroomOrder.Room    ".
                                        "   And Singo.Open       = FilmTitle.Open        ".
                                        "   And Singo.Film       = FilmTitle.Code	       ".
                                        "   And Singo.Silmooja   = '".$silmoojaCode."'   ".
                                        $AddedCont                                        .
                                        " Group By Singo.Theather,                       ".
                                        "          Singo.Room,                           ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Showroom.Discript                     ".
                                        $OrderCont                                        ,$connect) ;
               //$filmtitleNameTitle = "" ; // 두번이상 반복되면 영화명을 지우기 위해 ..

               include "wrk_filmsupply_Link_DnL1.php";
           }
           //-----------
           // 경남 출력
           //-----------
           $zoneName  = "경남" ;
           $query1 = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                 " Where Zone  = '20'                   ",$connect) ;

           $AddedCont = " And " ;
           while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
           {
               if  ($AddedCont == " And ")
               {
                   $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
               else
               {
                   $AddedCont .= " Or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
           }

           if  ($AddedCont != " And ")
           {
               $AddedCont .= " Or Singo.Location <> '600' " ;
               $AddedCont .= " Or Singo.Location <> '201' " ;
               $AddedCont .= " Or Singo.Location <> '207' " ;
               $AddedCont .= ")" ;
           }
           else
           {
               $AddedCont = "" ;
           }

           if  ($AddedCont != "") // 경남지역에 해당하는 자료가 있는경우..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // 특정영화만 선택하여 보고자 할 경우..
               {
                   if   ($FilmCode == '00') // 분리된영화의통합코드
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmOpen."' " ;
                        $OrderCont = " Order By Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                   }
                   else
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmOpen."' " .
                                      " And Singo.Film = '".$FilmCode."' " ;
                        $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                     "          Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                   }
               }

               $qry_CntLocat = mysql_query("SELECT Count( DISTINCT Singo.Theather,       ".
                                           "                       Singo.Room,           ".
                                           "                       Singo.Open,           ".
                                           "                       Singo.Film,           ".
                                           "                       Singo.Phoneno )       ".
                                           "              AS CntLocat                    ".
                                           "  FROM ".$sSingoName." AS Singo              ".
                                           " WHERE Singo.Singodate = '".$WorkDate."'     ".
                                           "   AND Singo.Silmooja = '".$silmoojaCode."'  ".
                                           $AddedCont                                     ,$connect) ;

               if  ($CntLocat_data = mysql_fetch_array($qry_CntLocat))
               {
                   $CntLocat = $CntLocat_data["CntLocat"] ;
                   $FirstLoc = true ;
               }



               $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                        "       Singo.Theather,                          ".
                                        "       Singo.Room,                              ".
                                        "       Singo.Open,                              ".
                                        "       Singo.Film,                              ".
                                        "       Showroom.Discript,                       ".
                                        "       Showroom.Location,                       ".
                                        "       FilmTitle.Name As FilmTitleName,         ".
                                        "       Count(distinct ShowDgree) As CntDgree    ".
                                        "  From ".$sSingoName."   As Singo,              ".
                                        "       bas_showroom      As Showroom,           ".
                                        "       ".$sShowroomorder." As ShowroomOrder,    ".
                                        "       bas_filmtitle     As FilmTitle           ".
                                        " Where Singo.Singodate  = '".$WorkDate."'       ".
                                        "   And Singo.Theather   = Showroom.Theather     ".
                                        "   And Singo.Room       = Showroom.Room         ".
                                        "   And Singo.Theather   = ShowroomOrder.Theather".
                                        "   And Singo.Room       = ShowroomOrder.Room    ".
                                        "   And Singo.Open       = FilmTitle.Open        ".
                                        "   And Singo.Film       = FilmTitle.Code	       ".
                                        "   And Singo.Silmooja   = '".$silmoojaCode."'   ".
                                        $AddedCont                                        .
                                        " Group By Singo.Theather,                       ".
                                        "          Singo.Room,                           ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Showroom.Discript                     ".
                                        $OrderCont                                        ,$connect) ;
               //$filmtitleNameTitle = "" ; // 두번이상 반복되면 영화명을 지우기 위해 ..

               include "wrk_filmsupply_Link_DnL1.php";
           }
           //-----------
           // 경북 출력
           //-----------
           $zoneName  = "경북" ;
           $query1 = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                 " Where Zone  = '21'                   ",$connect) ;

           $AddedCont = " And " ;
           while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
           {
               if  ($AddedCont == " And ")
               {
                   $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
               else
               {
                   $AddedCont .= " Or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
           }

           if  ($AddedCont != " And ")
           {
               $AddedCont .= ")" ;
           }
           else
           {
               $AddedCont = "" ;
           }

           if  ($AddedCont != "") // 경강지역에 해당하는 자료가 있는경우..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // 특정영화만 선택하여 보고자 할 경우..
               {
                   if   ($FilmCode == '00') // 분리된영화의통합코드
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmOpen."' " ;
                        $OrderCont = " Order By Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                   }
                   else
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmOpen."' " .
                                      " And Singo.Film = '".$FilmCode."' " ;
                        $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                     "          Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                   }
               }

               $qry_CntLocat = mysql_query("SELECT Count( DISTINCT Singo.Theather,       ".
                                           "                       Singo.Room,           ".
                                           "                       Singo.Open,           ".
                                           "                       Singo.Film,           ".
                                           "                       Singo.Phoneno )       ".
                                           "              AS CntLocat                    ".
                                           "  FROM ".$sSingoName." AS Singo              ".
                                           " WHERE Singo.Singodate = '".$WorkDate."'     ".
                                           "   AND Singo.Silmooja = '".$silmoojaCode."'  ".
                                           $AddedCont                                     ,$connect) ;

               if  ($CntLocat_data = mysql_fetch_array($qry_CntLocat))
               {
                   $CntLocat = $CntLocat_data["CntLocat"] ;
                   $FirstLoc = true ;
               }



               $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                        "       Singo.Theather,                          ".
                                        "       Singo.Room,                              ".
                                        "       Singo.Open,                              ".
                                        "       Singo.Film,                              ".
                                        "       Showroom.Discript,                       ".
                                        "       Showroom.Location,                       ".
                                        "       FilmTitle.Name As FilmTitleName,         ".
                                        "       Count(distinct ShowDgree) As CntDgree    ".
                                        "  From ".$sSingoName."   As Singo,              ".
                                        "       bas_showroom      As Showroom,           ".
                                        "       ".$sShowroomorder." As ShowroomOrder,    ".
                                        "       bas_filmtitle     As FilmTitle           ".
                                        " Where Singo.Singodate  = '".$WorkDate."'       ".
                                        "   And Singo.Theather   = Showroom.Theather     ".
                                        "   And Singo.Room       = Showroom.Room         ".
                                        "   And Singo.Theather   = ShowroomOrder.Theather".
                                        "   And Singo.Room       = ShowroomOrder.Room    ".
                                        "   And Singo.Open       = FilmTitle.Open        ".
                                        "   And Singo.Film       = FilmTitle.Code	       ".
                                        "   And Singo.Silmooja   = '".$silmoojaCode."'   ".
                                        $AddedCont                                        .
                                        " Group By Singo.Theather,                       ".
                                        "          Singo.Room,                           ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Showroom.Discript                     ".
                                        $OrderCont                                        ,$connect) ;
               //$filmtitleNameTitle = "" ; // 두번이상 반복되면 영화명을 지우기 위해 ..

               include "wrk_filmsupply_Link_DnL1.php";
           }
           //-----------
           // 호남 출력
           //-----------
           $zoneName  = "호남" ;
           $query1 = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                 " Where Zone  = '50'                   ",$connect) ;

           $AddedCont = " And " ;
           while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
           {
               if  ($AddedCont == " And ")
               {
                   $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
               else
               {
                   $AddedCont .= " Or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
           }

           if  ($AddedCont != " And ")
           {
               $AddedCont .= ")" ;
           }
           else
           {
               $AddedCont = "" ;
           }

           if  ($AddedCont != "") // 경강지역에 해당하는 자료가 있는경우..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // 특정영화만 선택하여 보고자 할 경우..
               {
                   if   ($FilmCode == '00') // 분리된영화의통합코드
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmOpen."' " ;
                        $OrderCont = " Order By Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                   }
                   else
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmOpen."' " .
                                      " And Singo.Film = '".$FilmCode."' " ;
                        $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                     "          Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                   }
               }

               $qry_CntLocat = mysql_query("SELECT Count( DISTINCT Singo.Theather,       ".
                                           "                       Singo.Room,           ".
                                           "                       Singo.Open,           ".
                                           "                       Singo.Film,           ".
                                           "                       Singo.Phoneno )       ".
                                           "              AS CntLocat                    ".
                                           "  FROM ".$sSingoName." AS Singo              ".
                                           " WHERE Singo.Singodate = '".$WorkDate."'     ".
                                           "   AND Singo.Silmooja = '".$silmoojaCode."'  ".
                                           $AddedCont                                     ,$connect) ;

               if  ($CntLocat_data = mysql_fetch_array($qry_CntLocat))
               {
                   $CntLocat = $CntLocat_data["CntLocat"] ;
                   $FirstLoc = true ;
               }



               $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                        "       Singo.Theather,                          ".
                                        "       Singo.Room,                              ".
                                        "       Singo.Open,                              ".
                                        "       Singo.Film,                              ".
                                        "       Showroom.Discript,                       ".
                                        "       Showroom.Location,                       ".
                                        "       FilmTitle.Name As FilmTitleName,         ".
                                        "       Count(distinct ShowDgree) As CntDgree    ".
                                        "  From ".$sSingoName."   As Singo,              ".
                                        "       bas_showroom      As Showroom,           ".
                                        "       ".$sShowroomorder." As ShowroomOrder,    ".
                                        "       bas_filmtitle     As FilmTitle           ".
                                        " Where Singo.Singodate  = '".$WorkDate."'       ".
                                        "   And Singo.Theather   = Showroom.Theather     ".
                                        "   And Singo.Room       = Showroom.Room         ".
                                        "   And Singo.Theather   = ShowroomOrder.Theather".
                                        "   And Singo.Room       = ShowroomOrder.Room    ".
                                        "   And Singo.Open       = FilmTitle.Open        ".
                                        "   And Singo.Film       = FilmTitle.Code	       ".
                                        "   And Singo.Silmooja   = '".$silmoojaCode."'   ".
                                        $AddedCont                                        .
                                        " Group By Singo.Theather,                       ".
                                        "          Singo.Room,                           ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Showroom.Discript                     ".
                                        $OrderCont                                        ,$connect) ;
               //$filmtitleNameTitle = "" ; // 두번이상 반복되면 영화명을 지우기 위해 ..

               include "wrk_filmsupply_Link_DnL1.php";
           }




           //-----------
           // 지방출력
           //-----------
           $zoneName  = "지방" ;
           $qryzoneloc = mysql_query("select Location from bas_filmsupplyzoneloc ".
                                     " Where Zone = '04'                         ",$connect) ;

           $AddedLoc = " and " ;

           while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
           {
                if  ($AddedLoc == " and ")
                    $AddedLoc .= "( Singo.Location <> '".$zoneloc_data["Location"]."' "  ;
                else
                    $AddedLoc .= " and Singo.Location <> '".$zoneloc_data["Location"]."' "  ;
           }
           $AddedLoc .= " and Singo.Location <> '100' "  ; // 서울
           $AddedLoc .= " and Singo.Location <> '200' "  ; // 부산
           $AddedLoc .= " and Singo.Location <> '203' "  ; // 통영
           $AddedLoc .= " and Singo.Location <> '600' "  ; // 울산
           $AddedLoc .= " and Singo.Location <> '207' "  ; // 김해
           $AddedLoc .= " and Singo.Location <> '205' "  ; // 진주
           $AddedLoc .= " and Singo.Location <> '208' "  ; // 거제
           $AddedLoc .= " and Singo.Location <> '202' "  ; // 마산
           $AddedLoc .= " and Singo.Location <> '211' "  ; // 사천
           $AddedLoc .= " and Singo.Location <> '212' "  ; // 거창
           $AddedLoc .= " and Singo.Location <> '213' "  ; // 양산
           $AddedLoc .= " and Singo.Location <> '201' "  ; // 창원
           $AddedLoc .= ")" ;

           // 경기 + 서울 + 부산 + 울산 + 창원 + 김해 를 제외한 나머지를 지방으로 한다.

           if  (($FilmTile != "") && ($FilmTile != "00000000")) // 특정영화만 선택하여 보고자 할 경우..
           {
               if   ($FilmCode == '00') // 분리된영화의통합코드
               {
                    $AddedCont .= " And Singo.Open = '".$FilmOpen."' " ;
                    $OrderCont = " Order By Showroom.Discript,                    ".
                                 "          Singo.Theather,                       ".
                                 "          Singo.Room                            " ;
               }
               else
               {
                    $AddedCont .= " And Singo.Open = '".$FilmOpen."' " .
                                  " And Singo.Film = '".$FilmCode."' " ;
                    $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                 "          Showroom.Discript,                    ".
                                 "          Singo.Theather,                       ".
                                 "          Singo.Room                            " ;
               }
           }

           $qry_CntLocat = mysql_query("SELECT Count( DISTINCT Singo.Theather,       ".
                                       "                       Singo.Room,           ".
                                       "                       Singo.Open,           ".
                                       "                       Singo.Film,           ".
                                       "                       Singo.Phoneno )       ".
                                       "              AS CntLocat                    ".
                                       "  FROM ".$sSingoName." AS Singo              ".
                                       " WHERE Singo.Singodate = '".$WorkDate."'     ".
                                       "   AND Singo.Silmooja = '".$silmoojaCode."'  ".
                                       $AddedCont                                     .
                                       $AddedLoc                                      ,$connect) ;

           if  ($CntLocat_data = mysql_fetch_array($qry_CntLocat))
           {
               $CntLocat = $CntLocat_data["CntLocat"] ;
               $FirstLoc = true ;
           }

           $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                    "       Singo.Theather,                          ".
                                    "       Singo.Room,                              ".
                                    "       Singo.Open,                              ".
                                    "       Singo.Film,                              ".
                                    "       Showroom.Discript,                       ".
                                    "       Showroom.Location,                       ".
                                    "       FilmTitle.Name As FilmTitleName,         ".
                                    "       Count(distinct ShowDgree) As CntDgree    ".
                                    "  From ".$sSingoName."   As Singo,              ".
                                    "       bas_showroom      As Showroom,           ".
                                    "       ".$sShowroomorder." As ShowroomOrder,    ".
                                    "       bas_filmtitle     As FilmTitle           ".
                                    " Where Singo.Singodate  = '".$WorkDate."'       ".
                                    "   And Singo.Theather   = Showroom.Theather     ".
                                    "   And Singo.Room       = Showroom.Room         ".
                                    "   And Singo.Theather   = ShowroomOrder.Theather".
                                    "   And Singo.Room       = ShowroomOrder.Room    ".
                                    "   And Singo.Open       = FilmTitle.Open        ".
                                    "   And Singo.Film       = FilmTitle.Code	       ".
                                    "   And Singo.Silmooja   = '".$silmoojaCode."'   ".
                                    $AddedCont                                        .
                                    $AddedLoc                                         .
                                    " Group By Singo.Theather,                       ".
                                    "          Singo.Room,                           ".
                                    "          Singo.Open,                           ".
                                    "          Singo.Film,                           ".
                                    "          Showroom.Discript                     ".
                                    $OrderCont                                        ,$connect) ;
           //$filmtitleNameTitle = "" ; // 두번이상 반복되면 영화명을 지우기 위해 ..

           include "wrk_filmsupply_Link_DnL1.php";

           ?>
           </table>
           <?
       }







       //if   ($ZoneCode!="0000") // 전체가 아닌 지역별로..
       else
       {
           $AddedCont = "" ; // 추가적인 검색조건

           // 특정지역만 선택적으로 보고자 할 경우
           if  (($LocationCode) && ($LocationCode!=""))
           {
               $qryzone = mysql_query("Select * From bas_location        ".
                                      " Where Code = '".$LocationCode."' ",$connect) ;


               $zone_data = mysql_fetch_array($qryzone) ;
               if  ($zone_data)
               {
                   $zoneName = $zone_data["Name"] ;
               }

               if  ($LocationCode=="200")//  부산은 (부산+울산+김해+창원)
               {
                   $AddedCont = " And  (Singo.Location = '200'  ".
                                "    Or Singo.Location = '203'  ".
                                "    Or Singo.Location = '600'  ".
                                "    Or Singo.Location = '207'  ".
                                "    Or Singo.Location = '205'  ".
                                "    Or Singo.Location = '208'  ".
                                "    Or Singo.Location = '202'  ".
                                "    Or Singo.Location = '211'  ".
                                "    Or Singo.Location = '212'  ".
                                "    Or Singo.Location = '213'  ".
                                "    Or Singo.Location = '201') " ;
               }
               else
               {
                   $AddedCont = " And  Singo.Location = '".$LocationCode."'  ";
               }
           }

           // 특정구역만 선택적으로 보고자 할 경우
           if  (($ZoneCode) && ($ZoneCode!=""))
           {
               $qryzone = mysql_query("Select * From bas_zone          ".
                                      " Where Code = '".$ZoneCode."'   ",$connect) ;


               $zone_data = mysql_fetch_array($qryzone) ;
               if  ($zone_data)
               {
                   $zoneName = $zone_data["Name"] ;
               }

               $query1 = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                     " Where Zone  = '".$ZoneCode."'        ",$connect) ;

               $AddedCont = " And " ;
               while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
               {
                   if  ($AddedCont == " And ")
                   {
                       $AddedCont .= "( Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
                   }
                   else
                   {
                       $AddedCont .= " Or Singo.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
                   }
               }

               if  ($AddedCont != " And ")
               {
                   if  ($ZoneCode == '20') // 경남인경우 부산을 포함한다.
                   {
                        $AddedCont .= " Or Singo.Location = '200' " ;

                        $AddedCont .= " Or Singo.Location <> '203' ".  // 통영
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
               else
               {
                   $AddedCont = "" ;
               }

               //$zoneName = $filmsupplyzoneloc_data["Name"]
           }

           if  ($AddedCont != "") // 해당하는 자료가 있는경우..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // 특정영화만 선택하여 보고자 할 경우..
               {
                   if   ($FilmCode == '00') // 분리된영화의통합코드
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmOpen."' " ;
                        $OrderCont = " Order By Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                   }
                   else
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmOpen."' " .
                                      " And Singo.Film = '".$FilmCode."' " ;
                        $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                     "          Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                   }
               }


               $strQuery = "Select ShowroomOrder.Seq,                       ".
                           "       Singo.Theather,                          ".
                           "       Singo.Room,                              ".
                           "       Singo.Open,                              ".
                           "       Singo.Film,                              ".
                           "       Showroom.Discript,                       ".
                           "       Showroom.Location,                       ".
                           "       FilmTitle.Name As FilmTitleName,         ".
                           "       Count(distinct ShowDgree) As CntDgree    ".
                           "  From ".$sSingoName."   As Singo,              ".
                           "       bas_showroom      As Showroom,           ".
                           "       ".$sShowroomorder." As ShowroomOrder,    ".
                           "       bas_filmtitle     As FilmTitle           ".
                           " Where Singo.Singodate  = '".$WorkDate."'       ".
                           "   And Singo.Theather   = Showroom.Theather     ".
                           "   And Singo.Room       = Showroom.Room         ".
                           "   And Singo.Theather   = ShowroomOrder.Theather".
                           "   And Singo.Room       = ShowroomOrder.Room    ".
                           "   And Singo.Open       = FilmTitle.Open        ".
                           "   And Singo.Film       = FilmTitle.Code	       ".
                           "   And Singo.Silmooja   = '".$silmoojaCode."'   ".
                           $AddedCont                                        .
                           " Group By Singo.Theather,                       ".
                           "          Singo.Room,                           ".
                           "          Singo.Open,                           ".
                           "          Singo.Film,                           ".
                           "          Showroom.Discript                     ".
                           $OrderCont                                        ;
               $qry_singo = mysql_query($strQuery,$connect) ;
               $filmtitleNameTitle = "" ; // 두번이상 반복되면 영화명을 지우기 위해 ..

               include "wrk_filmsupply_Link_DnL1.php";
           }
           ?>
           </table>
           <?
       }

   }
   ?>

   <br>
   <br>

</center>

</body>

        <?
        mysql_close($connect);
    }
    else // 로그인하지 않고 바로들어온다면..
    {
        ?>

        <!-- 로그인하지 않고 바로들어온다면 -->
        <body>
            <script language="JavaScript">
                <!--
                window.top.location = '../index_cokr.php' ;
                //-->
            </script>
        </body>

        <?
    }
    ?>
</html>
