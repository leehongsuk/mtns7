<?
    set_time_limit(0) ; // 긴 처리작업이 있을 때만 쓴다.....

    if ($ToExel)
    {
        header("Content-type: application/vnd.ms-excel;charset=KSC5601");
        header("Content-Disposition: attachment; filename=excel_name.xls");
        header("Content-Description: GamZa Excel Data");

        $NBSP="" ;
    }
    else
    {
        $NBSP="&nbsp;" ;
    }

    if ($ToCSV)
    {
       header( "Content-type: application/vnd.ms-excel;charset=KSC5601" );
       header( "Content-Disposition: attachment; filename=$filename" );
       header( "Content-Description: PHP4 Generated Data" );
    }

    session_start();

    function Get_HTTP_GET_VARS()
    {
        global $PAGE ;
        global $HTTP_GET_VARS ;
        global $ReturnStr ;

        $ReturnStr = "" ;

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

    $filename = date("Ymd",time()).".csv" ;

?>
<html>
<?
    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";        // {[데이터 베이스]} : 환경설정

        $connect = dbconn() ;        // {[데이터 베이스]} : 연결

        mysql_select_db($cont_db) ;  // {[데이터 베이스]} : 디비선택


        $sQuery = "Select * From bas_filmproduce             ".
                  " Where UserId  = '".$logged_UserId."'     " ;
        $QryFilmproduce = mysql_query($sQuery,$connect) ;
        if  ($ArrFilmproduce = mysql_fetch_array($QryFilmproduce))
        {
            $filmproduce = $ArrFilmproduce["Code"] ;
        }

        if ($ToCSV)
        {
            include "wrk_filmsupply_Link_DnACvs.php";
        }
        else
        {
            if  ($ActionCode=="Delete") // 한 상영관의 신고건 전체를 삭제한다.
            {
                $delTheather = substr($ShowRoom,0,4) ;
                $delRoom     = substr($ShowRoom,4,2) ;

                $sSingoName = get_singotable($Open,$Film,$connect) ;  // 신고 테이블 이름..

                $sQuery = "Delete From ".$sSingoName."            ".
                          " Where SingoDate = '".$WorkDate."'     ".
                          "   And Silmooja  = '".$silmoojaCode."' ".
                          "   And Theather  = '".$delTheather."'  ".
                          "   And Room      = '".$delRoom."'      ".
                          "   And Open      = '".$Open."'         ".
                          "   And Film      = '".$Film."'         " ;
                mysql_query($sQuery,$connect) ;
            }

            if  ($EndShowRoom!="") // 종영보고
            {
                // 실무자가 지정한 상영관을 해제한다.
                $sQuery = "Delete From bas_silmoojatheather                   ".
                          " Where Silmooja  = '".$silmoojaCode."'             ".
                          "   And Theather  = '".substr($EndShowRoom,0,4)."'  ".
                          "   And Room      = '".substr($EndShowRoom,4,2)."'  " ;
                mysql_query($sQuery,$connect) ;

                // 실무자가 지정한 상영관을 해제한다.
                $sQuery = "Delete From bas_silmoojatheatherpriv               ".
                          " Where Silmooja  = '".$silmoojaCode."'             ".
                          "   And WorkDate  = '".$WorkDate."'                 ".
                          "   And Theather  = '".substr($EndShowRoom,0,4)."'  ".
                          "   And Room      = '".substr($EndShowRoom,4,2)."'  " ;
                mysql_query($sQuery,$connect) ;

                // 종영되는 상영관의 이름을 구한다.
                $sQuery = "Select * From bas_showroom                         ".
                          " Where Theather  = '".substr($EndShowRoom,0,4)."'  ".
                          "   And Room      = '".substr($EndShowRoom,4,2)."'  " ;
                $qry_showroom = mysql_query($sQuery,$connect) ;
                if  ($showroom_data = mysql_fetch_array($qry_showroom))
                {
                    $showroomDiscript = $showroom_data["Discript"] ; // 종영되는 상영관의 이름
                }

                // 종영되는 상영관의 영화를 구한다.
                $sQuery = "Select * From bas_filmtitle                   ".
                          " Where Open = '".substr($EndFilmTitle,0,6)."' ".
                          "   And Code = '".substr($EndFilmTitle,6,2)."' " ;
                $qry_filmtitle = mysql_query($sQuery,$connect) ;
                if  ($filmtitle_data = mysql_fetch_array($qry_filmtitle))
                {
                    $filmtitleName       = $filmtitle_data["Name"] ; // 종영되는 상영관의 영화

                }
                // 실무자가 지정한 상영관종영정보를 만든다
                $sQuery = "Insert Into bas_silmoojatheatherfinish   ".
                          "Values ('".$silmoojaCode."',             ".
                          "        '".$TmroDate."',                 ".
                          "        '".substr($EndShowRoom,0,4)."',  ".
                          "        '".substr($EndShowRoom,4,2)."',  ".
                          "        '".substr($EndFilmTitle,0,6)."', ".
                          "        '".substr($EndFilmTitle,6,2)."', ".
                          "        '".$silmoojaName."',             ".
                          "        '".$showroomDiscript."',         ".
                          "        '".$filmtitleName."'             ".
                          "        )                                " ;
                mysql_query($sQuery,$connect) ;

                // 상영관내 실무자 정보를 지운다 (실제적으로는 아무의미없음)
                $sQuery = "Update bas_showroom                                ".
                          "   Set Silmooja     = NULL,                        ".
                          "       SilmoojaName = NULL                         ".
                          " Where Theather  = '".substr($EndShowRoom,0,4)."'  ".
                          "   And Room      = '".substr($EndShowRoom,4,2)."'  " ;
                mysql_query($sQuery,$connect) ;
            }
        }
?>
   <link rel=stylesheet href=./LinkStyle.css type=text/css>
   <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

   <head>
   <title>일일 보고서</title>
   </head>

   <script language='JavaScript'>
   <!--
       function Timer(sec)
       {
          if  (sec==0)  //self.location = '<?=$PHP_SELF?>' ;
          document.location.reload();

          count.innerHTML = sec;


          sec -= 1;

          window.setTimeout('Timer('+sec+')',1000);
       }

   //-->
   </script>

   <?
   $sec = 60 ;
   ?>

   <body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 >
   <!--onload='Timer (<?=$sec?>)'-->

   <script language='JavaScript'>
   <!--
         //
         //  종영처리
         //
         function endingShowroom(sSilmoojaCode,sEndShowRoom,sEndFilmTitle)
         {
            answer = confirm("정말로 종영처리하시겠읍니까?") ;
            if  (answer==true)
            {
                // WorkDate     : 작업일자
                // EndFilmTitle : 종영영화
                // EndShowRoom  : 종영상영관

                //location.href="<?=$PHP_SELF?>?silmoojaCode="+sSilmoojaCode+"&EndShowRoom="+sEndShowRoom+"&EndFilmTitle="+sEndFilmTitle+"&<?=Get_HTTP_GET_VARS()?>";

                popupaddr = "wrk_filmsupply_Link_Ending.php?"
                          + "silmoojaCode="+sSilmoojaCode+"&"
                          + "TmroDate=<?=$TmroDate?>&"
                          + "EndShowRoom="+sEndShowRoom+"&"
                          + "EndFilmTitle="+sEndFilmTitle+"&"
                          + "<?=Get_HTTP_GET_VARS()?>";

                popupoption = "status=0, "
                            + "menubar=0, "
                            + "scrollbars=yes, "
                            + "resizable=yes, "
                            + "width=300, "
                            + "height=200" ;

                window.open(popupaddr,'',popupoption) ;
            }
         }

         //
         // 스코어 수정
         //
         function edit_click(singoSilmooja,TheatherRoom,FilmTile,Location,UnitPrice)
         {
             popupaddr = "wrk_filmsupply_Link_Edt.php?"
                       + "logged_UserId=<?=$logged_UserId?>&"
                       + "WorkDate=<?=$WorkDate?>&"
                       + "FilmTitle="+FilmTile+"&"
                       + "silmooja_Code="+singoSilmooja+"&"
                       + "ShowRoom="+TheatherRoom+"&"
                       + "UnitPrice="+UnitPrice+"&"
                       + "Location="+Location+"&"
                       + "BackAddr=wrk_filmsupply_Link_Up.php" ;

             popupoption = "status=0, "
                         + "menubar=0, "
                         + "scrollbars=yes, "
                         + "resizable=yes, "
                         + "width=300, "
                         + "height=200" ;

             window.open(popupaddr,'',popupoption) ;
         }

         //
         // 보고자료 양도
         //
         function yangdo_click(singoSilmooja,TheatherRoom,FilmTile)
         {
             popupaddr = "wrk_filmsupply_Link_Chg.php?"
                       + "logged_UserId=<?=$logged_UserId?>&"
                       + "WorkDate=<?=$WorkDate?>&"
                       + "FilmTitle="+FilmTile+"&"
                       + "silmooja_Code="+singoSilmooja+"&"
                       + "ShowRoom="+TheatherRoom+"&"
                       + "BackAddr=wrk_filmsupply_Link_Up.php" ;

             popupoption = "status=0, "
                         + "menubar=0, "
                         + "scrollbars=yes, "
                         + "resizable=yes, "
                         + "width=400, "
                         + "height=500" ;

             window.open(popupaddr,'',popupoption) ;
         }

         //
         // 보고자료 수정
         //
         function modify_click(singoSilmooja,TheatherRoom,FilmTile)
         {
             popupaddr = "wrk_filmsupply_Link_UpM.php?"
                       + "logged_UserId=<?=$logged_UserId?>&"
                       + "WorkDate=<?=$WorkDate?>&"
                       + "silmooja_Code="+singoSilmooja+"&"
                       + "ShowRoom="+TheatherRoom+"&"
                       + "FilmTile="+FilmTile+"&"
                       + "BackAddr=wrk_filmsupply_Link_Up.php" ;

             popupoption = "status=0, "
                         + "menubar=0, "
                         + "scrollbars=yes, "
                         + "resizable=yes, "
                         + "width=400, "
                         + "height=400" ;

             window.open(popupaddr,'',popupoption) ;
         }

         //
         // 보고자료 삭제
         //
         function delect_click(singoSilmooja,TheatherRoom,FilmTile,sOpen,sFilm)
         {
             answer = confirm("정말로 삭제하시겠읍니까?") ;
             if  (answer==true)
             {
                 deladdr = "<?=$PHP_SELF?>?"
                         + "FilmTile="+FilmTile+"&"
                         + "ZoneCode=<?=$ZoneCode?>&"
                         + "ZoneLoc=<?=$ZoneLoc?>&"
                         + "logged_UserId=<?=$logged_UserId?>&"
                         + "WorkDate=<?=$WorkDate?>&"
                         + "ActionCode=Delete&"
                         + "silmoojaCode="+singoSilmooja+"&"
                         + "ShowRoom="+TheatherRoom+"&"
                         + "Open="+sOpen+"&"
                         + "Film="+sFilm ;
                 //location.href='<?=$PHP_SELF?>?FilmTile=<?=$FilmTile?>&ZoneCode=<?=$ZoneCode?>&ZoneLoc=<?=$ZoneLoc?>&logged_UserId=<?=$logged_UserId?>&WorkDate=<?=$WorkDate?>&ActionCode=Delete&silmoojaCode='+singoSilmooja+'&ShowRoom='+TheatherRoom+'>' ;
                 location.href = deladdr ;
             }
         }

         //
         // 보고자료 비고 등록
         //
         function bigo_click(singoSilmooja,TheatherRoom,FilmTile)
         {
             //wrk_filmsupply_Link_Bigo.php?logged_UserId=<?=$logged_UserId?>&ShowRoom=<?=$singoTheather.$singoRoom?>&FilmTitle=<?=$singoOpen.$singoFilm?>&silmooja_Code=<?=$singoSilmooja?>

             popupaddr = "wrk_filmsupply_Link_Bigo.php?"
                       + "logged_UserId=<?=$logged_UserId?>&"
                       + "FilmTitle="+FilmTile+"&"
                       + "silmoojaCode="+singoSilmooja+"&"
                       + "ShowRoom="+TheatherRoom ;

             popupoption = "status=0, "
                         + "menubar=0, "
                         + "scrollbars=yes, "
                         + "resizable=yes, "
                         + "width=350, "
                         + "height=300" ;

             window.open(popupaddr,'',popupoption) ;
         }

         //
         // 엑셀 출력
         //
         function toexel_click()
         {
             <?
             if  ($filmproduce)
             {
                 ?>
                 botttomaddr = 'wrk_filmsupply_Link_DnA.php?'
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
                 botttomaddr = 'wrk_filmsupply_Link_DnA.php?'
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
             <?
             if  ($WorkGubun==1) // 당일 회차별 현황
             {
                 ?>
                 //botttomaddr += ('&'+'nFilmType=<?=$nFilmType?>') ;
                 <?
             }
             else
             {
                 ?>
                 //botttomaddr += ('&'+'nFilmType=') ;
                 <?
             }

             ?>

             botttomaddr += ('&'+'nFilmTypeNo=<?=$nFilmTypeNo?>') ;


             //alert(botttomaddr) ;
             top.frames.bottom.location.href = botttomaddr ;
         }

         //
         //  csv 출력
         //
         function tocsv_click()
         {
             <?
             if  ($filmproduce)
             {
             ?>
             botttomaddr = 'wrk_filmsupply_Link_DnA.php?'
                         + 'logged_UserId=<?=$logged_UserId?>&'
                         + 'FilmTile=<?=$FilmTile?>&'
                         + 'WorkDate=<?=$WorkDate?>&'
                         + 'WorkGubun=<?=$WorkGubun?>&'
                         + 'LocationCode=<?=$LocationCode?>&'
                         + 'ZoneCode=<?=$ZoneCode?>&'
                         + 'filmproduce=<?=$filmproduce?>&'
                         + 'ToCSV=Yes' ;
             <?
             }
             else
             {
             ?>
             botttomaddr = 'wrk_filmsupply_Link_DnA.php?'
                         + 'logged_UserId=<?=$logged_UserId?>&'
                         + 'FilmTile=<?=$FilmTile?>&'
                         + 'WorkDate=<?=$WorkDate?>&'
                         + 'WorkGubun=<?=$WorkGubun?>&'
                         + 'LocationCode=<?=$LocationCode?>&'
                         + 'ZoneCode=<?=$ZoneCode?>&'
                         + 'ToCSV=Yes' ;
             <?
             }
             ?>
             //alert(botttomaddr) ;

             top.frames.bottom.location.href = botttomaddr ;
         }
   //-->
   </script>

<center>

  <br><br>
  <b>당일회차별현황</b>
  <?
  $sQuery = "Select * From bas_smsidchk        ".
            " Where Id = '".$spacial_UserId."' " ;
  $QrySmsIdChk = mysql_query($sQuery,$connect) ;
  if  ($ArrSmsIdChk = mysql_fetch_array($QrySmsIdChk)) // 이부장..
  {
      $TimJang   = true ;
      $TimJangNo = $ArrSmsIdChk["ChkNo"] ; // 팀장번호
  }
  else
  {
      $TimJang   = false ;
      $TimJangNo = "0" ; // 팀장번호
  }

  if  ((!$ToExel) && ($TimJang==false)) // 엑셀출력
  {
      ?>
      <a href="javascript: window.print();"><img src="print.gif" width="32" height="32" border="0"></a>
      <a href=# onclick="toexel_click();"><img src="exel.gif" width="32" height="32" border="0"></a>
      <!-- <a href=# onclick="tocsv_click();"><img src="csv.jpg" width="32" height="32" border="0"></a> -->
      <?
  }
  ?>

  <!--                 -->
  <!-- 세부스코어 집계 -->
  <!--                 -->

  <br>
  <span id='count'></span>
  <br>


   <?
   //echo "구역=".$ZoneCode. " - 지역=".$LocationCode. " - 영화=".$FilmTile ;

   $FilmOpen = substr($FilmTile,0,6) ;
   $FilmCode = substr($FilmTile,6,2) ;

   $sSingoName = get_singotable($FilmOpen,$FilmCode,$connect) ;  // 신고 테이블 이름..
   $sAccName   = get_acctable($FilmOpen,$FilmCode,$connect) ;    // accumulate 이름..
   $sDgrName   = get_degree($FilmOpen,$FilmCode,$connect) ;
   $sDgrpName  = get_degreepriv($FilmOpen,$FilmCode,$connect) ;
   $sFilmType    =  get_FilmType($FilmOpen,$FilmCode,$connect) ;
   $sFilmTypePrv =  get_FilmTypePrv($FilmOpen,$FilmCode,$connect) ;

   if   ((!$FilmTile) && (!$ZoneCode) && (!$LocationCode)) //
   {
       //echo "없음" ;
   }
   else
   {

       // 엑셀출력
       // 엑셀출력
       // 엑셀출력
       // 엑셀출력
       // 엑셀출력
       // 엑셀출력
       // 엑셀출력
       // 엑셀출력
       // 엑셀출력
       // 엑셀출력
       // 엑셀출력

       if ($ToExel)  // 엑셀출력
       {
          include "wrk_filmsupply_Link_DnZ.php";
       }
       // 엑셀끝
       // 엑셀끝
       // 엑셀끝
       // 엑셀끝
       // 엑셀끝
       // 엑셀끝
       // 엑셀끝
       // 엑셀끝
       // 엑셀끝
       // 엑셀끝
       // $ToExel


       if  ($ZoneCode=="9999") // "전체"
       {
           $filmtitleNameTitle = "" ; // 두번이상 반복되면 영화명을 지우기 위해 ..

           if  ($TimJang==false) // 이부장..
           {
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
                       $OrderCont = " Order By Singo.RoomOrder,                      ".
                                    "          Singo.Theather,                       ".
                                    "          Singo.Room                            " ;
                  }
                  else
                  {
                       $AddedCont .= " And Singo.Open = '".$FilmOpen."' ".
                                     " And Singo.Film = '".$FilmCode."' " ;
                       $OrderCont = " Order By Singo.RoomOrder,                      ".
                                     "         Showroom.Discript,                    ".
                                    "          Singo.Theather,                       ".
                                    "          Singo.Room                            " ;
                  }
              }
              if  ($WorkGubun == 28)
              {
                  $AddedCont .= " And Singo.Silmooja = '777777' " ;
              }
              if  ($WorkGubun == 33)
              {
                  $AddedCont .= " And Singo.Silmooja = '555595' " ;
              }
              if  ($WorkGubun == 34) // 씨너스
              {
                  $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
              }
              if  ($WorkGubun == 37) // 롯데씨네마
              {
                  $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
              }
              if  ($WorkGubun == 39) // 메가박스
              {
                  $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
              }
              if  ($WorkGubun == 56) // 기타
              {
                  $AddedCont .= " And (Showroom.MultiPlex <> '4' and Showroom.MultiPlex <> '5' and Showroom.MultiPlex <> '3' and Showroom.MultiPlex <> '2') " ;
              }

              $sQuery = "Select Singo.RoomOrder,                         ".
                        "       Singo.Theather,                          ".
                        "       Singo.Room,                              ".
                        "       Singo.Open,                              ".
                        "       Singo.Film,                              ".
                        "       Singo.FilmType,                              ".
                        "       Singo.Silmooja,                          ".
                        "       Showroom.Discript,                       ".
                        "       Showroom.Location,                       ".
                        "       Showroom.MultiPlex,                      ".
                        "       Location.Name As LocationName,           ".
                        "       Showroom.Seat As ShowRoomSeat,           ".
                        "       FilmTitle.Name As FilmTitleName,         ".
                        "       FilmTitle.ExcelTitle As ExcelTitle,      ".
                        "       Silmooja.Name	As SilmoojaName,           ".
                        "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                        "       Count(distinct ShowDgree) As CntDgree    ".
                        "  From ".$sSingoName."   As Singo,              ".
                        "       bas_showroom      As Showroom,           ".
                        "       bas_filmtitle     As FilmTitle,          ".
                        "       bas_silmooja      As Silmooja,           ".
                        "       bas_location      As Location            ".
                        " Where Singo.Singodate  = '".$WorkDate."'       ".
                        "   And Singo.Silmooja   = Silmooja.Code         ".
                        "   And Singo.Theather   = Showroom.Theather     ".
                        "   And Singo.Room       = Showroom.Room         ".
                        "   And Singo.Location   = Location.Code         ".
                        "   And Singo.Open       = FilmTitle.Open        ".
                        "   And Singo.Film       = FilmTitle.Code	       ".
                        $AddedCont                                        .
                        " Group By Singo.Theather,                       ".
                        "          Singo.Room,                           ".
                        "          Singo.Open,                           ".
                        "          Singo.Film,                           ".
                        "          Singo.FilmType,                           ".
                        "          Singo.Silmooja ,                      ".
                        "          Showroom.Discript                     ".
                        $OrderCont                                        ;
              $QrySingo = mysql_query($sQuery,$connect) ;

              include "wrk_filmsupply_Link_DnA1.php";



              //-----------
              // 경기출력
              //-----------
              $zoneName  = "경기" ;

              $AddedLoc = " And " ;

              $sQuery = "select Location from bas_filmsupplyzoneloc  ".
                        " Where Zone = '04'                          " ;
              $qryzoneloc = mysql_query($sQuery,$connect) ;
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
                       $AddedCont = " And Singo.Open = '".$FilmOpen."' " ;
                       $OrderCont = " Order By Showroom.Discript,                    ".
                                    "          Singo.Theather,                       ".
                                    "          Singo.Room                            " ;
                  }
                  else
                  {
                       $AddedCont = " And Singo.Open = '".$FilmOpen."' ".
                                    " And Singo.Film = '".$FilmCode."' " ;
                       $OrderCont = " Order By Singo.RoomOrder,                      ".
                                    "          Showroom.Discript,                    ".
                                    "          Singo.Theather,                       ".
                                    "          Singo.Room                            " ;
                  }
              }
              if  ($WorkGubun == 28)
              {
                  $AddedCont .= " And Singo.Silmooja = '777777' " ;
              }
              if  ($WorkGubun == 33)
              {
                  $AddedCont .= " And Singo.Silmooja = '555595' " ;
              }
              if  ($WorkGubun == 34) // 씨너스
              {
                  $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
              }
              if  ($WorkGubun == 37) // 롯데씨네마
              {
                  $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
              }
              if  ($WorkGubun == 39) // 메가박스
              {
                  $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
              }
              if  ($WorkGubun == 56) // 기타
              {
                  $AddedCont .= " And (Showroom.MultiPlex <> '4' and Showroom.MultiPlex <> '5' and Showroom.MultiPlex <> '3' and Showroom.MultiPlex <> '2') " ;
              }

              $sQuery = "Select Singo.RoomOrder,                         ".
                        "       Singo.Theather,                          ".
                        "       Singo.Room,                              ".
                        "       Singo.Open,                              ".
                        "       Singo.Film,                              ".
                        "       Singo.FilmType,                              ".
                        "       Singo.Silmooja,                          ".
                        "       Showroom.Discript,                       ".
                        "       Showroom.Location,                       ".
                        "       Showroom.MultiPlex,                      ".
                        "       Location.Name As LocationName,           ".
                        "       Showroom.Seat As ShowRoomSeat,           ".
                        "       FilmTitle.Name As FilmTitleName,         ".
                        "       FilmTitle.ExcelTitle As ExcelTitle,      ".
                        "       Silmooja.Name	As SilmoojaName,           ".
                        "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                        "       Count(distinct ShowDgree) As CntDgree    ".
                        "  From ".$sSingoName."   As Singo,              ".
                        "       bas_showroom      As Showroom,           ".
                        "       bas_filmtitle     As FilmTitle,          ".
                        "       bas_silmooja      As Silmooja,           ".
                        "       bas_location      As Location            ".
                        " Where Singo.Singodate  = '".$WorkDate."'       ".
                        "   And Singo.Silmooja   = Silmooja.Code         ".
                        "   And Singo.Theather   = Showroom.Theather     ".
                        "   And Singo.Room       = Showroom.Room         ".
                        "   And Singo.Location   = Location.Code         ".
                        "   And Singo.Open       = FilmTitle.Open        ".
                        "   And Singo.Film       = FilmTitle.Code	       ".
                        $AddedLoc                                         .
                        $AddedCont                                        .
                        " Group By Singo.Theather,                       ".
                        "          Singo.Room,                           ".
                        "          Singo.Open,                           ".
                        "          Singo.Film,                           ".
                        "          Singo.FilmType,                           ".
                        "          Singo.Silmooja ,                      ".
                        "          Showroom.Discript                     ".
                        $OrderCont                                        ;
              $QrySingo = mysql_query($sQuery,$connect) ;

              include "wrk_filmsupply_Link_DnA1.php";

           }



           //-----------
           // 부산 출력
           //-----------

           $zoneName  = "부산" ;

           $AddedCont = " And ( Singo.Location = '200'   " . // 부산
                        "  or   Singo.Location = '203'   " . // 통영
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
                    $AddedCont .= " And Singo.Open = '".$FilmOpen."' ".
                                  " And Singo.Film = '".$FilmCode."' " ;
                    $OrderCont = " Order By Singo.RoomOrder,                      ".
                                 "          Showroom.Discript,                    ".
                                 "          Singo.Theather,                       ".
                                 "          Singo.Room                            " ;
               }
           }
           if  ($WorkGubun == 28)
           {
               $AddedCont .= " And Singo.Silmooja = '777777' " ;
           }
           if  ($WorkGubun == 33)
           {
               $AddedCont .= " And Singo.Silmooja = '555595' " ;
           }
           if  ($WorkGubun == 34) // 씨너스
           {
               $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // 롯데씨네마
           {
               $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // 메가박스
           {
               $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
           }
           if  ($WorkGubun == 56) // 기타
           {
               $AddedCont .= " And (Showroom.MultiPlex <> '4' and Showroom.MultiPlex <> '5' and Showroom.MultiPlex <> '3' and Showroom.MultiPlex <> '2') " ;
           }

           $sQuery = "Select Singo.RoomOrder,                         ".
                     "       Singo.Theather,                          ".
                     "       Singo.Room,                              ".
                     "       Singo.Open,                              ".
                     "       Singo.Film,                              ".
                     "       Singo.FilmType,                              ".
                     "       Singo.Silmooja,                          ".
                     "       Showroom.Discript,                       ".
                     "       Showroom.Location,                       ".
                     "       Showroom.MultiPlex,                      ".
                     "       Location.Name As LocationName,           ".
                     "       Showroom.Seat As ShowRoomSeat,           ".
                     "       FilmTitle.Name As FilmTitleName,         ".
                     "       FilmTitle.ExcelTitle As ExcelTitle,      ".
                     "       Silmooja.Name	As SilmoojaName,           ".
                     "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                     "       Count(distinct ShowDgree) As CntDgree    ".
                     "  From ".$sSingoName."   As Singo,              ".
                     "       bas_showroom      As Showroom,           ".
                     "       bas_filmtitle     As FilmTitle,          ".
                     "       bas_silmooja      As Silmooja,           ".
                     "       bas_location      As Location            ".
                     " Where Singo.Singodate  = '".$WorkDate."'       ".
                     "   And Singo.Silmooja   = Silmooja.Code         ".
                     "   And Singo.Theather   = Showroom.Theather     ".
                     "   And Singo.Room       = Showroom.Room         ".
                     "   And Singo.Location   = Location.Code         ".
                     "   And Singo.Open       = FilmTitle.Open        ".
                     "   And Singo.Film       = FilmTitle.Code	       ".
                     $AddedCont                                        .
                     " Group By Singo.Theather,                       ".
                     "          Singo.Room,                           ".
                     "          Singo.Open,                           ".
                     "          Singo.Film,                           ".
                     "          Singo.FilmType,                       ".
                     "          Singo.Silmooja ,                      ".
                     "          Showroom.Discript                     ".
                     $OrderCont                                        ;
           $QrySingo = mysql_query($sQuery,$connect) ;

           include "wrk_filmsupply_Link_DnA1.php";




           //-----------
           // 경강 출력
           //-----------
           $zoneName  = "경강" ;

           $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                     " Where Zone  = '10'                   " ;
           $query1 = mysql_query($sQuery,$connect) ;

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
                        $AddedCont .= " And Singo.Open = '".$FilmOpen."' ".
                                      " And Singo.Film = '".$FilmCode."' " ;
                        $OrderCont = " Order By Singo.RoomOrder,                      ".
                                     "          Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                   }
               }

               if  ($WorkGubun == 28)
               {
                   $AddedCont .= " And Singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $AddedCont .= " And Singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // 씨너스
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
               }
               if  ($WorkGubun == 56) // 기타
               {
                   $AddedCont .= " And (Showroom.MultiPlex <> '4' and Showroom.MultiPlex <> '5' and Showroom.MultiPlex <> '3' and Showroom.MultiPlex <> '2') " ;
               }

               $sQuery = "Select Singo.RoomOrder,                         ".
                         "       Singo.Theather,                          ".
                         "       Singo.Room,                              ".
                         "       Singo.Open,                              ".
                         "       Singo.Film,                              ".
                         "       Singo.FilmType,                              ".
                         "       Singo.Silmooja,                          ".
                         "       Showroom.Discript,                       ".
                         "       Showroom.Location,                       ".
                         "       Showroom.MultiPlex,                      ".
                         "       Location.Name As LocationName,           ".
                         "       Showroom.Seat As ShowRoomSeat,           ".
                         "       FilmTitle.Name As FilmTitleName,         ".
                         "       FilmTitle.ExcelTitle As ExcelTitle,      ".
                         "       Silmooja.Name	As SilmoojaName,           ".
                         "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                         "       Count(distinct ShowDgree) As CntDgree    ".
                         "  From ".$sSingoName."   As Singo,              ".
                         "       bas_showroom      As Showroom,           ".
                         "       bas_filmtitle     As FilmTitle,          ".
                         "       bas_silmooja      As Silmooja,           ".
                         "       bas_location      As Location            ".
                         " Where Singo.Singodate  = '".$WorkDate."'       ".
                         "   And Singo.Silmooja   = Silmooja.Code         ".
                         "   And Singo.Theather   = Showroom.Theather     ".
                         "   And Singo.Room       = Showroom.Room         ".
                         "   And Singo.Location   = Location.Code         ".
                         "   And Singo.Open       = FilmTitle.Open        ".
                         "   And Singo.Film       = FilmTitle.Code	       ".
                         $AddedCont                                        .
                         " Group By Singo.Theather,                       ".
                         "          Singo.Room,                           ".
                         "          Singo.Open,                           ".
                         "          Singo.Film,                           ".
                         "          Singo.FilmType,                           ".
                         "          Singo.Silmooja ,                      ".
                         "          Showroom.Discript                     ".
                         $OrderCont                                        ;
               $QrySingo = mysql_query($sQuery,$connect) ;

               include "wrk_filmsupply_Link_DnA1.php";
           }

           //-----------
           // 충청 출력
           //-----------
           $zoneName  = "충청" ;

           $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                     " Where Zone  = '35'                   " ;
           $query1 = mysql_query($sQuery,$connect) ;

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
                        $AddedCont .= " And Singo.Open = '".$FilmOpen."' ".
                                      " And Singo.Film = '".$FilmCode."' " ;
                        $OrderCont = " Order By Singo.RoomOrder,                      ".
                                     "          Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                   }
               }

               if  ($WorkGubun == 28)
               {
                   $AddedCont .= " And Singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $AddedCont .= " And Singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // 씨너스
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
               }
               if  ($WorkGubun == 56) // 기타
               {
                   $AddedCont .= " And (Showroom.MultiPlex <> '4' and Showroom.MultiPlex <> '5' and Showroom.MultiPlex <> '3' and Showroom.MultiPlex <> '2') " ;
               }

               $sQuery = "Select Singo.RoomOrder,                         ".
                         "       Singo.Theather,                          ".
                         "       Singo.Room,                              ".
                         "       Singo.Open,                              ".
                         "       Singo.Film,                              ".
                         "       Singo.FilmType,                              ".
                         "       Singo.Silmooja,                          ".
                         "       Showroom.Discript,                       ".
                         "       Showroom.Location,                       ".
                         "       Showroom.MultiPlex,                      ".
                         "       Location.Name As LocationName,           ".
                         "       Showroom.Seat As ShowRoomSeat,           ".
                         "       FilmTitle.Name As FilmTitleName,         ".
                         "       FilmTitle.ExcelTitle As ExcelTitle,      ".
                         "       Silmooja.Name	As SilmoojaName,           ".
                         "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                         "       Count(distinct ShowDgree) As CntDgree    ".
                         "  From ".$sSingoName."   As Singo,              ".
                         "       bas_showroom      As Showroom,           ".
                         "       bas_filmtitle     As FilmTitle,          ".
                         "       bas_silmooja      As Silmooja,           ".
                         "       bas_location      As Location            ".
                         " Where Singo.Singodate  = '".$WorkDate."'       ".
                         "   And Singo.Silmooja   = Silmooja.Code         ".
                         "   And Singo.Theather   = Showroom.Theather     ".
                         "   And Singo.Room       = Showroom.Room         ".
                         "   And Singo.Location   = Location.Code         ".
                         "   And Singo.Open       = FilmTitle.Open        ".
                         "   And Singo.Film       = FilmTitle.Code	       ".
                         $AddedCont                                        .
                         " Group By Singo.Theather,                       ".
                         "          Singo.Room,                           ".
                         "          Singo.Open,                           ".
                         "          Singo.Film,                           ".
                         "          Singo.FilmType,                           ".
                         "          Singo.Silmooja ,                      ".
                         "          Showroom.Discript                     ".
                         $OrderCont                                        ;
               $QrySingo = mysql_query($sQuery,$connect) ;


               include "wrk_filmsupply_Link_DnA1.php";
           }
           //-----------
           // 경남 출력
           //-----------
           $zoneName  = "경남" ;

           $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                     " Where Zone  = '20'                   " ;
           $query1 = mysql_query($sQuery,$connect) ;

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
                        $AddedCont .= " And Singo.Open = '".$FilmOpen."' ".
                                      " And Singo.Film = '".$FilmCode."' " ;
                        $OrderCont = " Order By Singo.RoomOrder,                      ".
                                     "          Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                   }
               }

               if  ($WorkGubun == 28)
               {
                   $AddedCont .= " And Singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $AddedCont .= " And Singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // 씨너스
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
               }
               if  ($WorkGubun == 56) // 기타
               {
                   $AddedCont .= " And (Showroom.MultiPlex <> '4' and Showroom.MultiPlex <> '5' and Showroom.MultiPlex <> '3' and Showroom.MultiPlex <> '2') " ;
               }

               $sQuery = "Select Singo.RoomOrder,                         ".
                         "       Singo.Theather,                          ".
                         "       Singo.Room,                              ".
                         "       Singo.Open,                              ".
                         "       Singo.Film,                              ".
                         "       Singo.FilmType,                              ".
                         "       Singo.Silmooja,                          ".
                         "       Showroom.Discript,                       ".
                         "       Showroom.Location,                       ".
                         "       Showroom.MultiPlex,                      ".
                         "       Location.Name As LocationName,           ".
                         "       Showroom.Seat As ShowRoomSeat,           ".
                         "       FilmTitle.Name As FilmTitleName,         ".
                         "       FilmTitle.ExcelTitle As ExcelTitle,      ".
                         "       Silmooja.Name	As SilmoojaName,           ".
                         "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                         "       Count(distinct ShowDgree) As CntDgree    ".
                         "  From ".$sSingoName."   As Singo,              ".
                         "       bas_showroom      As Showroom,           ".
                         "       bas_filmtitle     As FilmTitle,          ".
                         "       bas_silmooja      As Silmooja,           ".
                         "       bas_location      As Location            ".
                         " Where Singo.Singodate  = '".$WorkDate."'       ".
                         "   And Singo.Silmooja   = Silmooja.Code         ".
                         "   And Singo.Theather   = Showroom.Theather     ".
                         "   And Singo.Room       = Showroom.Room         ".
                         "   And Singo.Location   = Location.Code         ".
                         "   And Singo.Open       = FilmTitle.Open        ".
                         "   And Singo.Film       = FilmTitle.Code	       ".
                         $AddedCont                                        .
                         " Group By Singo.Theather,                       ".
                         "          Singo.Room,                           ".
                         "          Singo.Open,                           ".
                         "          Singo.Film,                           ".
                         "          Singo.FilmType,                           ".
                         "          Singo.Silmooja ,                      ".
                         "          Showroom.Discript                     ".
                         $OrderCont                                        ;
               $QrySingo = mysql_query($sQuery,$connect) ;

               include "wrk_filmsupply_Link_DnA1.php";
           }
           //-----------
           // 경북 출력
           //-----------
           $zoneName  = "경북" ;

           $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                     " Where Zone  = '21'                   " ;
           $query1 = mysql_query($sQuery,$connect) ;

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
                        $AddedCont .= " And Singo.Open = '".$FilmOpen."' ".
                                      " And Singo.Film = '".$FilmCode."' " ;
                        $OrderCont = " Order By Singo.RoomOrder,                      ".
                                     "          Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                   }
               }

               if  ($WorkGubun == 28)
               {
                   $AddedCont .= " And Singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $AddedCont .= " And Singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // 씨너스
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
               }
               if  ($WorkGubun == 56) // 기타
               {
                   $AddedCont .= " And (Showroom.MultiPlex <> '4' and Showroom.MultiPlex <> '5' and Showroom.MultiPlex <> '3' and Showroom.MultiPlex <> '2') " ;
               }

               $sQuery = "Select Singo.RoomOrder,                         ".
                         "       Singo.Theather,                          ".
                         "       Singo.Room,                              ".
                         "       Singo.Open,                              ".
                         "       Singo.Film,                              ".
                         "       Singo.FilmType,                              ".
                         "       Singo.Silmooja,                          ".
                         "       Showroom.Discript,                       ".
                         "       Showroom.Location,                       ".
                         "       Showroom.MultiPlex,                      ".
                         "       Location.Name As LocationName,           ".
                         "       Showroom.Seat As ShowRoomSeat,           ".
                         "       FilmTitle.Name As FilmTitleName,         ".
                         "       FilmTitle.ExcelTitle As ExcelTitle,      ".
                         "       Silmooja.Name	As SilmoojaName,           ".
                         "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                         "       Count(distinct ShowDgree) As CntDgree    ".
                         "  From ".$sSingoName."   As Singo,              ".
                         "       bas_showroom      As Showroom,           ".
                         "       bas_filmtitle     As FilmTitle,          ".
                         "       bas_silmooja      As Silmooja,           ".
                         "       bas_location      As Location            ".
                         " Where Singo.Singodate  = '".$WorkDate."'       ".
                         "   And Singo.Silmooja   = Silmooja.Code         ".
                         "   And Singo.Theather   = Showroom.Theather     ".
                         "   And Singo.Room       = Showroom.Room         ".
                         "   And Singo.Location   = Location.Code         ".
                         "   And Singo.Open       = FilmTitle.Open        ".
                         "   And Singo.Film       = FilmTitle.Code	       ".
                         $AddedCont                                        .
                         " Group By Singo.Theather,                       ".
                         "          Singo.Room,                           ".
                         "          Singo.Open,                           ".
                         "          Singo.Film,                           ".
                         "          Singo.FilmType,                           ".
                         "          Singo.Silmooja ,                      ".
                         "          Showroom.Discript                     ".
                         $OrderCont                                        ;
               $QrySingo = mysql_query($sQuery,$connect) ;

               include "wrk_filmsupply_Link_DnA1.php";
           }
           //-----------
           // 호남 출력
           //-----------
           $zoneName  = "호남" ;

           $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                     " Where Zone  = '50'                   " ;
           $query1 = mysql_query($sQuery,$connect) ;

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
                        $AddedCont .= " And Singo.Open = '".$FilmOpen."' ".
                                      " And Singo.Film = '".$FilmCode."' " ;
                        $OrderCont = " Order By Singo.RoomOrder,                      ".
                                     "          Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                   }
               }

               if  ($WorkGubun == 28)
               {
                   $AddedCont .= " And Singo.Silmooja = '777777' " ;
               }
               if  ($WorkGubun == 33)
               {
                   $AddedCont .= " And Singo.Silmooja = '555595' " ;
               }
               if  ($WorkGubun == 34) // 씨너스
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // 롯데씨네마
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // 메가박스
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
               }
               if  ($WorkGubun == 56) // 기타
               {
                   $AddedCont .= " And (Showroom.MultiPlex <> '4' and Showroom.MultiPlex <> '5' and Showroom.MultiPlex <> '3' and Showroom.MultiPlex <> '2') " ;
               }

               $sQuery = "Select Singo.RoomOrder,                         ".
                         "       Singo.Theather,                          ".
                         "       Singo.Room,                              ".
                         "       Singo.Open,                              ".
                         "       Singo.Film,                              ".
                         "       Singo.FilmType,                              ".
                         "       Singo.Silmooja,                          ".
                         "       Showroom.Discript,                       ".
                         "       Showroom.Location,                       ".
                         "       Showroom.MultiPlex,                      ".
                         "       Location.Name As LocationName,           ".
                         "       Showroom.Seat As ShowRoomSeat,           ".
                         "       FilmTitle.Name As FilmTitleName,         ".
                         "       FilmTitle.ExcelTitle As ExcelTitle,      ".
                         "       Silmooja.Name	As SilmoojaName,           ".
                         "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                         "       Count(distinct ShowDgree) As CntDgree    ".
                         "  From ".$sSingoName."   As Singo,              ".
                         "       bas_showroom      As Showroom,           ".
                         "       bas_filmtitle     As FilmTitle,          ".
                         "       bas_silmooja      As Silmooja,           ".
                         "       bas_location      As Location            ".
                         " Where Singo.Singodate  = '".$WorkDate."'       ".
                         "   And Singo.Silmooja   = Silmooja.Code         ".
                         "   And Singo.Theather   = Showroom.Theather     ".
                         "   And Singo.Room       = Showroom.Room         ".
                         "   And Singo.Location   = Location.Code         ".
                         "   And Singo.Open       = FilmTitle.Open        ".
                         "   And Singo.Film       = FilmTitle.Code	       ".
                         $AddedCont                                        .
                         " Group By Singo.Theather,                       ".
                         "          Singo.Room,                           ".
                         "          Singo.Open,                           ".
                         "          Singo.Film,                           ".
                         "          Singo.FilmType,                           ".
                         "          Singo.Silmooja ,                      ".
                         "          Showroom.Discript                     ".
                         $OrderCont                                         ;
               $QrySingo = mysql_query($sQuery,$connect) ;

               include "wrk_filmsupply_Link_DnA1.php";
           }



           //-----------
           // 지방출력
           //-----------
           $zoneName  = "지방" ;

           $AddedLoc = " And " ;

           $sQuery = "select Location from bas_filmsupplyzoneloc ".
                     " Where Zone = '04'                         " ;
           $qryzoneloc = mysql_query($sQuery,$connect) ;
           while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
           {
                if  ($AddedLoc == " And ")
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

           // 경기 + 서울 + 부산 + 울산 + 창원 + 김해 + 진주 + 거제 + 창원 를 제외한 나머지를 지방으로 한다.

           if  (($FilmTile != "") && ($FilmTile != "00000000")) // 특정영화만 선택하여 보고자 할 경우..
           {
               if   ($FilmCode == '00') // 분리된영화의통합코드
               {
                    $AddedCont .= " And Singo.Open = '".$FilmOpen."' " ;
                    $OrderCont = " Order By Singo.RoomOrder,        ".
                                 "          Showroom.Discript,      ".
                                 "          Singo.Theather,         ".
                                 "          Singo.Room              " ;
               }
               else
               {
                    $AddedCont .= " And Singo.Open = '".$FilmOpen."' ".
                                  " And Singo.Film = '".$FilmCode."' " ;
                    $OrderCont = " Order By Singo.RoomOrder,        ".
                                 "          Showroom.Discript,      ".
                                 "          Singo.Theather,         ".
                                 "          Singo.Room              " ;
               }
           }

           if  ($WorkGubun == 28)
           {
               $AddedCont .= " And Singo.Silmooja = '777777' " ;
           }
           if  ($WorkGubun == 33)
           {
               $AddedCont .= " And Singo.Silmooja = '555595' " ;
           }
           if  ($WorkGubun == 34) // 씨너스
           {
               $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // 롯데씨네마
           {
               $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // 메가박스
           {
               $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
           }
           if  ($WorkGubun == 56) // 기타
           {
               $AddedCont .= " And (Showroom.MultiPlex <> '4' and Showroom.MultiPlex <> '5' and Showroom.MultiPlex <> '3' and Showroom.MultiPlex <> '2') " ;
           }

           $sQuery = "Select Singo.RoomOrder,                         ".
                     "       Singo.Theather,                          ".
                     "       Singo.Room,                              ".
                     "       Singo.Open,                              ".
                     "       Singo.Film,                              ".
                     "       Singo.FilmType,                              ".
                     "       Singo.Silmooja,                          ".
                     "       Showroom.Discript,                       ".
                     "       Showroom.Location,                       ".
                     "       Showroom.MultiPlex,                      ".
                     "       Location.Name As LocationName,           ".
                     "       Showroom.Seat As ShowRoomSeat,           ".
                     "       FilmTitle.Name As FilmTitleName,         ".
                     "       FilmTitle.ExcelTitle As ExcelTitle,      ".
                     "       Silmooja.Name	As SilmoojaName,           ".
                     "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                     "       Count(distinct ShowDgree) As CntDgree    ".
                     "  From ".$sSingoName."   As Singo,              ".
                     "       bas_showroom      As Showroom,           ".
                     "       bas_filmtitle     As FilmTitle,          ".
                     "       bas_silmooja      As Silmooja,           ".
                     "       bas_location      As Location            ".
                     " Where Singo.Singodate  = '".$WorkDate."'       ".
                     "   And Singo.Silmooja   = Silmooja.Code         ".
                     "   And Singo.Theather   = Showroom.Theather     ".
                     "   And Singo.Room       = Showroom.Room         ".
                     "   And Singo.Location   = Location.Code         ".
                     "   And Singo.Open       = FilmTitle.Open        ".
                     "   And Singo.Film       = FilmTitle.Code	       ".
                     $AddedLoc                                         .
                     $AddedCont                                        .
                     " Group By Singo.Theather,                       ".
                     "          Singo.Room,                           ".
                     "          Singo.Open,                           ".
                     "          Singo.Film,                           ".
                     "          Singo.FilmType,                           ".
                     "          Singo.Silmooja ,                      ".
                     "          Showroom.Discript                     ".
                     $OrderCont                                        ;
           $QrySingo = mysql_query($sQuery,$connect) ;

           include "wrk_filmsupply_Link_DnA1.php";

       }

       //if   ($ZoneCode!="0000") // 전체가 아닌 지역별로..
       else
       {
           $AddedCont = "" ; // 추가적인 검색조건

           // 특정지역만 선택적으로 보고자 할 경우
           if  (($LocationCode) && ($LocationCode!=""))
           {
               $sQuery = "Select * From bas_location        ".
                         " Where Code = '".$LocationCode."' " ;
               $qryzone = mysql_query($sQuery,$connect) ;
               if  ($zone_data = mysql_fetch_array($qryzone))
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
               $sQuery = "Select * From bas_zone          ".
                         " Where Code = '".$ZoneCode."'   " ;
               $qryzone = mysql_query($sQuery,$connect) ;
               if  ($zone_data = mysql_fetch_array($qryzone))
               {
                   $zoneName = $zone_data["Name"] ;
               }

               $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                         " Where Zone  = '".$ZoneCode."'        " ;
               $query1 = mysql_query($sQuery,$connect) ;

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

           if  ($WorkGubun == 28)
           {
               $AddedCont .= " And Singo.Silmooja = '777777' " ;
           }
           if  ($WorkGubun == 33)
           {
               $AddedCont .= " And Singo.Silmooja = '555595' " ;
           }
           if  ($WorkGubun == 34) // 씨너스
           {
               $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // 롯데씨네마
           {
               $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // 메가박스
           {
               $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
           }
           if  ($WorkGubun == 56) // 기타
           {
               $AddedCont .= " And (Showroom.MultiPlex <> '4' and Showroom.MultiPlex <> '5' and Showroom.MultiPlex <> '3' and Showroom.MultiPlex <> '2') " ;
           }

           if  ($AddedCont != "") // 해당하는 자료가 있는경우..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // 특정영화만 선택하여 보고자 할 경우..
               {
                   if   ($FilmCode == '00') // 분리된영화의통합코드
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmOpen."' " ;
                        $OrderCont = " Order By Singo.RoomOrder,    ".
                                     "          Showroom.Discript,  ".
                                     "          Singo.Theather,     ".
                                     "          Singo.Room          " ;
                   }
                   else
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmOpen."' " .
                                      " And Singo.Film = '".$FilmCode."' " ;
                        $OrderCont = " Order By Singo.RoomOrder,    ".
                                     "          Showroom.Discript,  ".
                                     "          Singo.Theather,     ".
                                     "          Singo.Room          " ;
                   }
               }

               $sQuery = "Select Singo.RoomOrder,                         \n".
                         "       Singo.Theather,                          \n".
                         "       Singo.Room,                              \n".
                         "       Singo.Open,                              \n".
                         "       Singo.Film,                              \n".
                         "       Singo.FilmType,                          \n".
                         "       Singo.Silmooja,                          \n".
                         "       Showroom.Discript,                       \n".
                         "       Showroom.Location,                       \n".
                         "       Showroom.MultiPlex,                      \n".
                         "       Location.Name As LocationName,           \n".
                         "       Showroom.Seat As ShowRoomSeat,           \n".
                         "       FilmTitle.Name As FilmTitleName,         \n".
                         "       FilmTitle.ExcelTitle As ExcelTitle,      \n".
                         "       Silmooja.Name	As SilmoojaName,          \n".
                         "       Sum(Singo.NumPersons) As SumNumPersons,  \n".
                         "       Count(distinct ShowDgree) As CntDgree    \n".
                         "  From ".$sSingoName."   As Singo,              \n".
                         "       bas_showroom      As Showroom,           \n".
                         "       bas_filmtitle     As FilmTitle,          \n".
                         "       bas_silmooja      As Silmooja,           \n".
                         "       bas_location      As Location            \n".
                         " Where Singo.Singodate  = '".$WorkDate."'       \n".
                         "   And Singo.Silmooja   = Silmooja.Code         \n".
                         "   And Singo.Theather   = Showroom.Theather     \n".
                         "   And Singo.Room       = Showroom.Room         \n".
                         "   And Singo.Location   = Location.Code         \n".
                         "   And Singo.Open       = FilmTitle.Open        \n".
                         "   And Singo.Film       = FilmTitle.Code	      \n".
                         $AddedCont                                     ."\n".
                         "  Group By Singo.Theather,                      \n".
                         "          Singo.Room,                           \n".
                         "          Singo.Open,                           \n".
                         "          Singo.Film,                           \n".
                         "          Singo.FilmType,                       \n".
                         "          Singo.Silmooja ,                      \n".
                         "          Showroom.Discript                     \n".
                         $OrderCont                                     ."\n" ;
//eq($sQuery);
               $QrySingo = mysql_query($sQuery,$connect) ;
               $filmtitleNameTitle = "" ; // 두번이상 반복되면 영화명을 지우기 위해 ..

               include "wrk_filmsupply_Link_DnA1.php";
           }
       }

       if  ($TimJang==false)
       {
           include "wrk_filmsupply_Link_DnC.php";
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
