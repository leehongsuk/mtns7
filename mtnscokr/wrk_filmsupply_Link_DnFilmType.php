<?
    set_time_limit(0) ; // 긴 처리작업이 있을 때만 쓴다.....

    session_start();
?>
<html>
<?
    $FilmOpen = substr($FilmTile,0,6) ;
    $FilmCode = substr($FilmTile,6,2) ;

    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";        // {[데이터 베이스]} : 환경설정

        $connect = dbconn() ;        // {[데이터 베이스]} : 연결

        mysql_select_db($cont_db) ;  // {[데이터 베이스]} : 디비선택

        $tblFilmType    =  get_FilmType($FilmOpen,$FilmCode,$connect) ;
        $tblFilmTypePrv =  get_FilmTypePrv($FilmOpen,$FilmCode,$connect) ;
?>
   <link rel=stylesheet href=./LinkStyle.css type=text/css>

   <meta http-equiv="Content-Type" content="text/html; charset=euc-kr">

   <head>
        <script type="text/javascript" src="./js/jquery-1.3.2.js"></script> <!-- http://visualjquery.com/ -->
        <script type="text/javascript" src="./js/jquery.form.js"></script>  <!-- http://www.malsup.com/jquery/form/ -->

        <title></title>

        <script language="javascript">
        <!--
             $(document).ready(function()
             {
                 $("span#output").css("color","red") ;

                 $(".FilmeType").click(function()
                 {
                     var sValue = $(this).val();
                     var index = $(this).index(this);

                     var sTheather = $(this).eq(index).attr( "Theather" )
                     var sRoom = $(this).eq(index).attr( "Room" )

                     // alert(sTheather+"/"+sRoom+":"+sValue);

                     var options = {
                          WorkDate     : <?=$WorkDate?>,
                          FilmOpen     : <?=$FilmOpen?>,
                          FilmCode     : <?=$FilmCode?>,
                          txtTheather  : sTheather,
                          txtRoom      : sRoom,
                          txtValue     : sValue
                     } ;

                     $.post("./wrk_filmsupply_Link_DnFilmTypeSet.php", options, function(data)
                     {
                         $('span#output').html(data) ;
                     });

                     //clear() ;



                 });

                 $("#btnMagaBox").click(function()
                 {
                     $('span#output').html("") ;

                     clear() ;
                 });

                 function clear()
                 {
                     FormUpload.file.select(); // value를 강제 select 하자!
                     document.execCommand('Delete'); // 날려버리자!!
                 }
            });

        //-->
        </script>

   </head>


   <body bgcolor=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 >

<center>

   <br><br>

   <b>상영관별 필름종류지정(<span id="output"></span>)</b>



   <BR><BR>
   <?
   //echo "구역=".$ZoneCode. " - 지역=".$LocationCode. " - 영화=".$FilmTile ;

   $FilmOpen = substr($FilmTile,0,6) ;
   $FilmCode = substr($FilmTile,6,2) ;

   $sSingoName    = get_singotable($FilmOpen,$FilmCode,$connect) ;  // 신고 테이블 이름..
   $Showroomorder = get_showroomorder($FilmOpen,$FilmCode,$connect) ;
   $sAccName      = get_acctable($FilmOpen,$FilmCode,$connect) ;    // accumulate 이름..
   $sDgrName      = get_degree($FilmOpen,$FilmCode,$connect) ;
   $sDgrpName     = get_degreepriv($FilmOpen,$FilmCode,$connect) ;


   $TableOrder = $Showroomorder."_tmp" ;

   drop_table($TableOrder,$connect) ;
   create_tbleorder($TableOrder,$Showroomorder,$connect) ;

   if   ((!$FilmTile) && (!$ZoneCode) && (!$LocationCode)) //
   {
       //echo "없음" ;
   }
   else
   {
       if  ($ZoneCode=="9999") // "전체"
       {

           //-----------
           // 서울 출력
           //-----------
           $zoneName  = "서울" ; //echo "<BR>".$zoneName."<BR>";

           $AddedCont = " And Singo.Location = '100'       ".
                        " And Singo.Open = '".$FilmOpen."' ".
                        " And Singo.Film = '".$FilmCode."' " ;

           $sQuery = "Select TableOrder.seq,                          ".
                     "       Singo.Theather,                          ".
                     "       Theather.Discript,                       ".
                     "       Singo.Open,                              ".
                     "       Singo.Film                               ".
                     "  From ".$sSingoName." As Singo,                ".
                     "       ".$TableOrder." As TableOrder,           ".
                     "       bas_theather    As Theather              ".
                     " Where Singo.Singodate  = '".$WorkDate."'       ".
                     "   And Singo.theather   = TableOrder.theather   ".
                     "   And Singo.theather   = Theather.Code         ".
                     $AddedCont                                        .
                     " Group By TableOrder.seq,                       ".
                     "          Singo.Theather                        ".
                     " Order By TableOrder.seq,                       ".
                     "          Singo.Theather                        "  ;  //eq($sQuery);
           $QrySingo = mysql_query($sQuery,$connect) ;

           include "wrk_filmsupply_Link_DnFilmType1.php";

           //-----------
           // 경기출력
           //-----------
           $zoneName  = "경기" ; //echo "<BR>".$zoneName."<BR>";

           $AddedCont = " And " ;

           $sQuery = "select Location from bas_filmsupplyzoneloc  ".
                     " Where Zone = '04'                          " ;
           $QryZoneloc = mysql_query($sQuery,$connect) ;
           while ($zoneloc_data = mysql_fetch_array($QryZoneloc))
           {
                if  ($AddedCont == " And ")
                    $AddedCont .= "( Singo.Location = '".$zoneloc_data["Location"]."' "  ;
                else
                    $AddedCont .= " or Singo.Location = '".$zoneloc_data["Location"]."' "  ;
           }
           $AddedCont .= ")" ;

           // 경기

           $AddedCont .= " And Singo.Open = '".$FilmOpen."' ".
                        " And Singo.Film = '".$FilmCode."' " ;

           $sQuery = "Select TableOrder.seq,                          ".
                     "       Singo.Theather,                          ".
                     "       Theather.Discript,                       ".
                     "       Singo.Open,                              ".
                     "       Singo.Film                               ".
                     "  From ".$sSingoName." As Singo,                ".
                     "       ".$TableOrder." As TableOrder,           ".
                     "       bas_theather    As Theather              ".
                     " Where Singo.Singodate  = '".$WorkDate."'       ".
                     "   And Singo.theather   = TableOrder.theather   ".
                     "   And Singo.theather   = Theather.Code         ".
                     $AddedCont                                        .
                     " Group By TableOrder.seq,                       ".
                     "          Singo.Theather                        ".
                     " Order By TableOrder.seq,                       ".
                     "          Singo.Theather                        "  ; // eq($sQuery);
           $QrySingo = mysql_query($sQuery,$connect) ;

           include "wrk_filmsupply_Link_DnFilmType1.php";

           //-----------
           // 부산 출력
           //-----------

           $zoneName  = "부산" ; //echo "<BR>".$zoneName."<BR>";

           $AddedCont = " And ( Singo.Location = '200'   " . // 부산
                        "  or   Singo.Location = '600'   " . // 울산
                        "  or   Singo.Location = '207'   " . // 김해
                        "  or   Singo.Location = '205'   " . // 진주
                        "  or   Singo.Location = '208'   " . // 거제
                        "  or   Singo.Location = '202'   " . // 마산
                        "  or   Singo.Location = '211'   " . // 사천
                        "  or   Singo.Location = '212'   " . // 거창
                        "  or   Singo.Location = '213'   " . // 양산
                        "  or   Singo.Location = '201' ) " ; // 창원

           $AddedCont .= " And Singo.Open = '".$FilmOpen."' ".
                        " And Singo.Film = '".$FilmCode."' " ;

           $sQuery = "Select TableOrder.seq,                          ".
                     "       Singo.Theather,                          ".
                     "       Theather.Discript,                       ".
                     "       Singo.Open,                              ".
                     "       Singo.Film                               ".
                     "  From ".$sSingoName." As Singo,                ".
                     "       ".$TableOrder." As TableOrder,           ".
                     "       bas_theather    As Theather              ".
                     " Where Singo.Singodate  = '".$WorkDate."'       ".
                     "   And Singo.theather   = TableOrder.theather   ".
                     "   And Singo.theather   = Theather.Code         ".
                     $AddedCont                                        .
                     " Group By TableOrder.seq,                       ".
                     "          Singo.Theather                        ".
                     " Order By TableOrder.seq,                       ".
                     "          Singo.Theather                        "  ; // eq($sQuery);
           $QrySingo = mysql_query($sQuery,$connect) ;

           include "wrk_filmsupply_Link_DnFilmType1.php";


           //-----------
           // 경강 출력
           //-----------
           $zoneName  = "경강" ; //echo "<BR>".$zoneName."<BR>";

           $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                     " Where Zone  = '10'                   " ;
           $QryFilmsupplyzoneloc = mysql_query($sQuery,$connect) ;

           $AddedCont = " And " ;
           while ($AryFilmsupplyzoneloc = mysql_fetch_array($QryFilmsupplyzoneloc))
           {
               if  ($AddedCont == " And ")
               {
                   $AddedCont .= "( Singo.Location = '".$AryFilmsupplyzoneloc["Location"]."' " ;
               }
               else
               {
                   $AddedCont .= " Or Singo.Location = '".$AryFilmsupplyzoneloc["Location"]."' " ;
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
               $AddedCont .= " And Singo.Open = '".$FilmOpen."' ".
                             " And Singo.Film = '".$FilmCode."' " ;

               $sQuery = "Select TableOrder.seq,                          ".
                         "       Singo.Theather,                          ".
                         "       Theather.Discript,                       ".
                         "       Singo.Open,                              ".
                         "       Singo.Film                               ".
                         "  From ".$sSingoName." As Singo,                ".
                         "       ".$TableOrder." As TableOrder,           ".
                         "       bas_theather    As Theather              ".
                         " Where Singo.Singodate  = '".$WorkDate."'       ".
                         "   And Singo.theather   = TableOrder.theather   ".
                         "   And Singo.theather   = Theather.Code         ".
                         $AddedCont                                        .
                         " Group By TableOrder.seq,                       ".
                         "          Singo.Theather                        ".
                         " Order By TableOrder.seq,                       ".
                         "          Singo.Theather                        "  ; // eq($sQuery);
               $QrySingo = mysql_query($sQuery,$connect) ;

               include "wrk_filmsupply_Link_DnFilmType1.php";
           }

           //-----------
           // 충청 출력
           //-----------
           $zoneName  = "충청" ; //echo "<BR>".$zoneName."<BR>";

           $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                     " Where Zone  = '35'                   " ;
           $QryFilmsupplyzoneloc = mysql_query($sQuery,$connect) ;

           $AddedCont = " And " ;
           while ($AryFilmsupplyzoneloc = mysql_fetch_array($QryFilmsupplyzoneloc))
           {
               if  ($AddedCont == " And ")
               {
                   $AddedCont .= "( Singo.Location = '".$AryFilmsupplyzoneloc["Location"]."' " ;
               }
               else
               {
                   $AddedCont .= " Or Singo.Location = '".$AryFilmsupplyzoneloc["Location"]."' " ;
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
               $AddedCont .= " And Singo.Open = '".$FilmOpen."' ".
                             " And Singo.Film = '".$FilmCode."' " ;

               $sQuery = "Select TableOrder.seq,                          ".
                         "       Singo.Theather,                          ".
                         "       Theather.Discript,                       ".
                         "       Singo.Open,                              ".
                         "       Singo.Film                               ".
                         "  From ".$sSingoName." As Singo,                ".
                         "       ".$TableOrder." As TableOrder,           ".
                         "       bas_theather    As Theather              ".
                         " Where Singo.Singodate  = '".$WorkDate."'       ".
                         "   And Singo.theather   = TableOrder.theather   ".
                         "   And Singo.theather   = Theather.Code         ".
                         $AddedCont                                        .
                         " Group By TableOrder.seq,                       ".
                         "          Singo.Theather                        ".
                         " Order By TableOrder.seq,                       ".
                         "          Singo.Theather                        "  ; // eq($sQuery);
               $QrySingo = mysql_query($sQuery,$connect) ;

               include "wrk_filmsupply_Link_DnFilmType1.php";
           }

           //-----------
           // 경남 출력
           //-----------
           $zoneName  = "경남" ; //echo "<BR>".$zoneName."<BR>";

           $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                     " Where Zone  = '20'                   " ;
           $QryFilmsupplyzoneloc = mysql_query($sQuery,$connect) ;

           $AddedCont = " And " ;
           while ($AryFilmsupplyzoneloc = mysql_fetch_array($QryFilmsupplyzoneloc))
           {
               if  ($AddedCont == " And ")
               {
                   $AddedCont .= "( Singo.Location = '".$AryFilmsupplyzoneloc["Location"]."' " ;
               }
               else
               {
                   $AddedCont .= " Or Singo.Location = '".$AryFilmsupplyzoneloc["Location"]."' " ;
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
               $AddedCont .= " And Singo.Open = '".$FilmOpen."' ".
                             " And Singo.Film = '".$FilmCode."' " ;

               $sQuery = "Select TableOrder.seq,                          ".
                         "       Singo.Theather,                          ".
                         "       Theather.Discript,                       ".
                         "       Singo.Open,                              ".
                         "       Singo.Film                               ".
                         "  From ".$sSingoName." As Singo,                ".
                         "       ".$TableOrder." As TableOrder,           ".
                         "       bas_theather    As Theather              ".
                         " Where Singo.Singodate  = '".$WorkDate."'       ".
                         "   And Singo.theather   = TableOrder.theather   ".
                         "   And Singo.theather   = Theather.Code         ".
                         $AddedCont                                        .
                         " Group By TableOrder.seq,                       ".
                         "          Singo.Theather                        ".
                         " Order By TableOrder.seq,                       ".
                         "          Singo.Theather                        "  ; // eq($sQuery);
               $QrySingo = mysql_query($sQuery,$connect) ;

               include "wrk_filmsupply_Link_DnFilmType1.php";
           }
           //-----------
           // 경북 출력
           //-----------
           $zoneName  = "경북" ; //echo "<BR>".$zoneName."<BR>";

           $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                     " Where Zone  = '21'                   " ;
           $QryFilmsupplyzoneloc = mysql_query($sQuery,$connect) ;

           $AddedCont = " And " ;
           while ($AryFilmsupplyzoneloc = mysql_fetch_array($QryFilmsupplyzoneloc))
           {
               if  ($AddedCont == " And ")
               {
                   $AddedCont .= "( Singo.Location = '".$AryFilmsupplyzoneloc["Location"]."' " ;
               }
               else
               {
                   $AddedCont .= " Or Singo.Location = '".$AryFilmsupplyzoneloc["Location"]."' " ;
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
               $AddedCont .= " And Singo.Open = '".$FilmOpen."' ".
                             " And Singo.Film = '".$FilmCode."' " ;

               $sQuery = "Select TableOrder.seq,                          ".
                         "       Singo.Theather,                          ".
                         "       Theather.Discript,                       ".
                         "       Singo.Open,                              ".
                         "       Singo.Film                               ".
                         "  From ".$sSingoName." As Singo,                ".
                         "       ".$TableOrder." As TableOrder,           ".
                         "       bas_theather    As Theather              ".
                         " Where Singo.Singodate  = '".$WorkDate."'       ".
                         "   And Singo.theather   = TableOrder.theather   ".
                         "   And Singo.theather   = Theather.Code         ".
                         $AddedCont                                        .
                         " Group By TableOrder.seq,                       ".
                         "          Singo.Theather                        ".
                         " Order By TableOrder.seq,                       ".
                         "          Singo.Theather                        "  ; // eq($sQuery);
               $QrySingo = mysql_query($sQuery,$connect) ;

               include "wrk_filmsupply_Link_DnFilmType1.php";
           }
           //-----------
           // 호남 출력
           //-----------
           $zoneName  = "호남" ; //echo "<BR>".$zoneName."<BR>";

           $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                     " Where Zone  = '50'                   " ;
           $QryFilmsupplyzoneloc = mysql_query($sQuery,$connect) ;

           $AddedCont = " And " ;
           while ($AryFilmsupplyzoneloc = mysql_fetch_array($QryFilmsupplyzoneloc))
           {
               if  ($AddedCont == " And ")
               {
                   $AddedCont .= "( Singo.Location = '".$AryFilmsupplyzoneloc["Location"]."' " ;
               }
               else
               {
                   $AddedCont .= " Or Singo.Location = '".$AryFilmsupplyzoneloc["Location"]."' " ;
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
              $AddedCont .= " And Singo.Open = '".$FilmOpen."' ".
                            " And Singo.Film = '".$FilmCode."' " ;

              $sQuery = "Select TableOrder.seq,                          ".
                        "       Singo.Theather,                          ".
                        "       Theather.Discript,                       ".
                        "       Singo.Open,                              ".
                        "       Singo.Film                               ".
                        "  From ".$sSingoName." As Singo,                ".
                        "       ".$TableOrder." As TableOrder,           ".
                        "       bas_theather    As Theather              ".
                        " Where Singo.Singodate  = '".$WorkDate."'       ".
                        "   And Singo.theather   = TableOrder.theather   ".
                        "   And Singo.theather   = Theather.Code         ".
                        $AddedCont                                        .
                        " Group By TableOrder.seq,                       ".
                        "          Singo.Theather                        ".
                        " Order By TableOrder.seq,                       ".
                        "          Singo.Theather                        "  ; // eq($sQuery);
              $QrySingo = mysql_query($sQuery,$connect) ;

              include "wrk_filmsupply_Link_DnFilmType1.php";
           }

           //-----------
           // 지방출력
           //-----------
           $zoneName  = "지방" ; //echo "<BR>".$zoneName."<BR>";

           $AddedCont = " And " ;

           $sQuery = "select Location from bas_filmsupplyzoneloc ".
                     " Where Zone = '04'                         " ;
           $QryZoneloc = mysql_query($sQuery,$connect) ;
           while ($zoneloc_data = mysql_fetch_array($QryZoneloc))
           {
                if  ($AddedCont == " And ")
                    $AddedCont .= "( Singo.Location <> '".$zoneloc_data["Location"]."' "  ;
                else
                    $AddedCont .= " and Singo.Location <> '".$zoneloc_data["Location"]."' "  ;
           }
           $AddedCont .= " and Singo.Location <> '100' "  ; // 서울
           $AddedCont .= " and Singo.Location <> '200' "  ; // 부산
           $AddedCont .= " and Singo.Location <> '600' "  ; // 울산
           $AddedCont .= " and Singo.Location <> '207' "  ; // 김해
           $AddedCont .= " and Singo.Location <> '205' "  ; // 진주
           $AddedCont .= " and Singo.Location <> '208' "  ; // 거제
           $AddedCont .= " and Singo.Location <> '202' "  ; // 마산
           $AddedCont .= " and Singo.Location <> '211' "  ; // 사천
           $AddedCont .= " and Singo.Location <> '212' "  ; // 거창
           $AddedCont .= " and Singo.Location <> '213' "  ; // 양산
           $AddedCont .= " and Singo.Location <> '201' "  ; // 창원
           $AddedCont .= ")" ;

           // 경기 + 서울 + 부산 + 울산 + 창원 + 김해 를 제외한 나머지를 지방으로 한다.

           $AddedCont .= " And Singo.Open = '".$FilmOpen."' ".
                         " And Singo.Film = '".$FilmCode."' " ;

           $sQuery = "Select TableOrder.seq,                          ".
                     "       Singo.Theather,                          ".
                     "       Theather.Discript,                       ".
                     "       Singo.Open,                              ".
                     "       Singo.Film                               ".
                     "  From ".$sSingoName." As Singo,                ".
                     "       ".$TableOrder." As TableOrder,           ".
                     "       bas_theather    As Theather              ".
                     " Where Singo.Singodate  = '".$WorkDate."'       ".
                     "   And Singo.theather   = TableOrder.theather   ".
                     "   And Singo.theather   = Theather.Code         ".
                     $AddedCont                                        .
                     " Group By TableOrder.seq,                       ".
                     "          Singo.Theather                        ".
                     " Order By TableOrder.seq,                       ".
                     "          Singo.Theather                        "  ;  //eq($sQuery);
           $QrySingo = mysql_query($sQuery,$connect) ;

           include "wrk_filmsupply_Link_DnFilmType1.php";
       }



       //if   ($ZoneCode!="0000") // 전체가 아닌 지역별로..
       else
       {
           $AddedCont = "" ; // 추가적인 검색조건

           // 특정지역만 선택적으로 보고자 할 경우
           if  (($LocationCode) && ($LocationCode!=""))
           {
               $sQuery = "Select * From bas_location
                           Where Code = '".$LocationCode."' " ; //eq($sQuery);
               $qryzone = mysql_query($sQuery,$connect) ;
               if  ($zone_data = mysql_fetch_array($qryzone))
               {
                   $zoneName = $zone_data["Name"] ;
               }

               if  ($LocationCode=="200")//  부산은 (부산+울산+김해+창원+진주+거제)
               {
                   $AddedCont = " And  (Singo.Location = '200'  ".
                                "    Or Singo.Location = '600'  ".
                                "    Or Singo.Location = '207'  ".
                                "    Or Singo.Location = '205'  ".
                                "    Or Singo.Location = '208'  ".
                                "    Or Singo.Location = '202'  ".
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
               while ($AryFilmsupplyzoneloc = mysql_fetch_array($query1))
               {
                   if  ($AddedCont == " And ")
                   {
                       $AddedCont .= "( Singo.Location = '".$AryFilmsupplyzoneloc["Location"]."' " ;
                   }
                   else
                   {
                       $AddedCont .= " Or Singo.Location = '".$AryFilmsupplyzoneloc["Location"]."' " ;
                   }
               }

               if  ($AddedCont != " And ")
               {
                   if  ($ZoneCode == '20') // 경남인경우 부산을 포함한다.
                   {
                        $AddedCont .= " Or Singo.Location = '200' " ;

                        $AddedCont .= " Or Singo.Location <> '600' ".  // 울산
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

               //$zoneName = $AryFilmsupplyzoneloc["Name"]
           }

           $AddedCont .= " And Singo.Open = '".$FilmOpen."' ".
                         " And Singo.Film = '".$FilmCode."' " ;

           $sQuery = "Select TableOrder.seq,                          ".
                     "       Singo.Theather,                          ".
                     "       Theather.Discript,                       ".
                     "       Singo.Open,                              ".
                     "       Singo.Film                               ".
                     "  From ".$sSingoName." As Singo,                ".
                     "       ".$TableOrder." As TableOrder,           ".
                     "       bas_theather    As Theather              ".
                     " Where Singo.Singodate  = '".$WorkDate."'       ".
                     "   And Singo.theather   = TableOrder.theather   ".
                     "   And Singo.theather   = Theather.Code         ".
                     $AddedCont                                        .
                     " Group By TableOrder.seq,                       ".
                     "          Singo.Theather                        ".
                     " Order By TableOrder.seq,                       ".
                     "          Singo.Theather                        "  ; // eq($sQuery);
           $QrySingo = mysql_query($sQuery,$connect) ;

           include "wrk_filmsupply_Link_DnFilmType1.php";
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
