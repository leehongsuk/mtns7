<?
   set_time_limit(0) ; // 긴 처리작업이 있을 때만 쓴다.....

   $WorkDate  = $_POST["WorkDate"] ;
   $FilmTitle = $_POST["FilmTitle"] ;

   $Now       = strtotime('now');
   $sCurrDay  = date("Ymd",$Now) ;    // 현재일...
   $sCurrTime = date("YmdHis",$Now) ; // 현재시간...

   $sAgo1Hour = date("YmdHis",strtotime('-60 minutes',$Now)) ;  // 60분전 ..
   $sAgo2Hour = date("YmdHis",strtotime('-120 minutes',$Now)) ; // 120분전 ..



   include "config.php" ;

   // DB접속
   $connect = dbconn() ;

   mysql_select_db($cont_db) ;

   $FilmOpen = substr($FilmTitle,0,6) ;  // 영화코드
   $FilmCode = substr($FilmTitle,6,2) ;  //

   $sSingoName = get_singotable($FilmOpen,$FilmCode,$connect) ;  // 신고 테이블 이름..

   if  ($Action=="getFilmList")
   {
   }

   //
   // 서버시간...
   //
   if  ($Action=="getServerTime")
   {
       $Now = time() ;

       $sTime = date("Y",$Now)."년 ".date("n",$Now)."월 ".date("d",$Now)."일 " ;

       if   (date("H",$Now) == date("h",$Now)) { $sTime .= "오전 " ; }
       else                                    { $sTime .= "오후 " ; }

       $sTime .= date("h",$Now)."시 ".date("i",$Now)."분" ;

       //echo mb_convert_encoding($sTime,"UTF-8","EUC-KR") ;
       print($sTime) ;
   }

   if  ($Action=="getTotalInfo")
   {
       $L1 = getLocationIInfo($connect,$WorkDate,$FilmTitle,"L1") ;
       $L2 = getLocationIInfo($connect,$WorkDate,$FilmTitle,"L2") ;
       $L3 = getLocationIInfo($connect,$WorkDate,$FilmTitle,"L3") ;
       $L4 = getLocationIInfo($connect,$WorkDate,$FilmTitle,"L4") ;
       $L5 = getLocationIInfo($connect,$WorkDate,$FilmTitle,"L5") ;

       $Return  = "<?xml version=1.0 encoding=euc-kr?>" ;
       $Return .= "<Totals>";

       $Return .= "<Total1>".$L1."</Total1>" ; // 서울
       $Return .= "<Total1>".$L2."</Total1>" ; // 경기
       $Return .= "<Total1>".$L3."</Total1>" ; // 부산
       $Return .= "<Total1>".$L4."</Total1>" ; // 지방
       $Return .= "<Total1>".$L5."</Total1>" ; // 전체

       $Return .= "<Total2>".$L1."</Total2>" ; // 서울
       $Return .= "<Total2>".$L2."</Total2>" ; // 경기
       $Return .= "<Total2>".$L3."</Total2>" ; // 부산
       $Return .= "<Total2>".$L4."</Total2>" ; // 지방

       $Return .= "<Total3><Day>Start</Day><Value>0</Value></Total3>" ;
       $Return .= getLineData1($connect,$WorkDate,$FilmTitle) ;

       $Return .= "<Total4><Day>Start</Day><Value>0</Value></Total4>" ;
       $Return .= getLineData2($connect,$WorkDate,$FilmTitle) ;

       $Return .= getColumData1($connect,$WorkDate,$FilmTitle) ;

       $Return .= "<Total6>" ;
       $Return .= "<Day>Start</Day>" ;
       $Return .= "<Value1>0</Value1>" ;
       $Return .= "<Value2>0</Value2>" ;
       $Return .= "<Value3>0</Value3>" ;
       $Return .= "<Value4>0</Value4>" ;
       $Return .= "</Total6>" ;
       $Return .= getLineData3($connect,$WorkDate,$FilmTitle) ;

       $Return .= getColumData2($connect,$WorkDate,$FilmTitle) ;

       $Return .= getColumData3($connect,$WorkDate,$FilmTitle) ;

       $Return .= getBarChart1($connect,$WorkDate) ;

       $Return .= "</Totals>";

       print($Return) ;
   }

   //
   // 그리드,파이그래프 ( 스크린수, 당일합계, 전일합계, 전일대비, 총누계 )
   //
   function getLocationIInfo($connect,$WorkDate,$FilmTitle,$item)
   {
       $Now       = strtotime('now');
       $sCurrTime = date("YmdHis",$Now) ; // 현재시간...

       $AgoDate   = date("Ymd",strtotime("-1 day",strtotime(substr($WorkDate,0,4)."-".substr($WorkDate,4,2)."-".substr($WorkDate,6,2).""))) ;
       $sAgo10Sec = date("YmdHis",strtotime('-20 second',$Now)) ;   // 10초전 ..

       $FilmTileOpen = substr($FilmTitle,0,6) ;
       $FilmTileFilm = substr($FilmTitle,6,2) ;

       if   ($FilmTileFilm == '00') // 분리된영화의통합코드
       {
            $FilmCond = " Open = '".$FilmTileOpen."' " ;
       }
       else
       {
            $FilmCond = "    Open = '".$FilmTileOpen."' ".
                        "And Film = '".$FilmTileFilm."' " ;
       }

       $sResult = "";

       if  ($item=="L1") {  $sResult  = "<Section>서울</Section>" ;  }
       if  ($item=="L2") {  $sResult  = "<Section>경기</Section>" ;  }
       if  ($item=="L3") {  $sResult  = "<Section>부산</Section>" ;  }
       if  ($item=="L4") {  $sResult  = "<Section>지방</Section>" ;  }
       if  ($item=="L5") {  $sResult  = "<Section>전체</Section>" ;  }


       // tmp_globaltotal 캐쉬..
       $sQuery = "Select * From tmp_globaltotal            ".
                 " Where WorkDate    = '".$WorkDate."'     ".
                 "   And FilmCode    = '".$FilmTitle."'    ".
                 "   And Location    = '".$item."'         ".
                 "   And UpdateTime >= '".$sAgo10Sec."'    " ;
       $QRY_GT = mysql_query($sQuery,$connect) ;
       if  ( $OBJ_GT = mysql_fetch_object( $QRY_GT ) ) // 만일에 캐쉬가 존재한다면..
       {
           $sResult .=  ("<Screen>"      .$OBJ_GT->Value1."</Screen>") ;   // 스크린수
           $sResult .=  ("<Score>"       .$OBJ_GT->Value2."</Score>") ;    // 당일합계
           $sResult .=  ("<AgoScore>"    .$OBJ_GT->Value3."</AgoScore>") ; // 전일합계
           $sResult .=  ("<TotalAcc>"    .$OBJ_GT->Value4."</TotalAcc>") ;     // 총누계
           $sResult .=  ("<TotalAmount>" .$OBJ_GT->Value5."</TotalAmount>") ;  // 총누적금액
       }
       else
       {
           if   ($singoFilm=='00')
           {
                $AddedCont  = "   And singo.open = '".$FilmTileOpen."' " ;
                $FinishCont = "   And singo.open = finish.open         " ;
           }
           else
           {
                $AddedCont  = "   And singo.open = '".$FilmTileOpen."' " .
                              "   And singo.film = '".$FilmTileFilm."' " ;
                $FinishCont = "   And singo.open = finish.open         " .
                              "   And singo.film = finish.film         " ;
           }
           //
           // 서울 ----------------------------------------------------------------------------------
           //
           if  ($item=="L1")
           {

               // 스크린수 (서울)
               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntShowroom                               ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.location = 100                         ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         " ;
echo $sQuery ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($cntShowroom_data = mysql_fetch_array($qrysingo2))
               {
                   $cntRealScreen = $cntShowroom_data["cntShowroom"] ;
               }

               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntFinishShowroom                         ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom,                    ".
                         "       bas_silmoojatheatherfinish As finish         ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.location = 100                         ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         "   And singo.theather = finish.theather             ".
                         "   And singo.room     = finish.room                 ".
                         $AddedCont."                                         ".
                         $FinishCont."                                        ".
                         "   And singo.silmooja = finish.silmooja             " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($cntShowroom_data = mysql_fetch_array($qrysingo2))
               {
                   $cntRealScreen = $cntRealScreen - $cntShowroom_data["cntFinishShowroom"] ;
               }

               // 당일합계
               $ModifyScore  = 0 ; // 수정스코어(당일)
               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore     ".
                         "  From bas_modifyscore                          ".
                         " Where ".$FilmCond."                            ".
                         "   And Location   = '100'                       ".
                         "   And ModifyDate = '".$WorkDate."'             " ;

               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
               }


               $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                         "  From ".$sSingoName."                    ".
                         " Where SingoDate  = '".$WorkDate."'       ".
                         "   And ".$FilmCond."                      ".
                         "   And Location   = '100'                 " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
               {
                   $WorkDateNumPersons = $NumPersons_data["SumNumPersons"] + $ModifyScore ;
               }

               // 전일합계
               $ModifyScore  = 0 ; // 수정스코어(전일)



               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore     ".
                         "  From bas_modifyscore                          ".
                         " Where ".$FilmCond."                            ".
                         "   And Location   = '100'                       ".
                         "   And ModifyDate = '".$AgoDate."'              " ;
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
               }


               $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                         "  From ".$sSingoName."                    ".
                         " Where SingoDate  = '".$AgoDate."'        ".
                         "   And ".$FilmCond."                      ".
                         "   And Location   = '100'                 " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
               {
                   $AgoDateNumPersons = $NumPersons_data["SumNumPersons"] + $ModifyScore ;
               }

               // 총누계
               $ModifyScore  = 0 ; // 수정스코어(누계)


               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore,    ".
                         "       Sum(ModifyAmount) As SumOfModifyAmount   ".
                         "  From bas_modifyscore                          ".
                         " Where ".$FilmCond."                            ".
                         "   And ModifyDate <= '".$WorkDate."'            ".
                         "   And Location   = '100'                       " ;
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
                   $ModifyAmount = $modifyscore_data["SumOfModifyAmount"] ;
               }


               $sQuery = "Select Sum(NumPersons) As SumNumPersons,  ".
                         "       Sum(TotAmount) As SumTotAmount     ".
                         "  From ".$sSingoName."                    ".
                         " Where SingoDate <= '".$WorkDate."'       ".
                         "   And ".$FilmCond."                      ".
                         "   And Location   = '100'                 " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
               {
                   $TotNumPersons = $NumPersons_data["SumNumPersons"] + $ModifyScore ;
                   $TotTotAmount  = $NumPersons_data["SumTotAmount"]  + $ModifyAmount ;
               }
           }

           //
           // 경기 ----------------------------------------------------------------------------------
           //
           if  ($item=="L2")
           {
               $sQuery = "Select * From bas_zone  ".
                         " Where Code = '04'      " ;
               $qryzone = mysql_query($sQuery,$connect) ;
               if  ($zone_data = mysql_fetch_array($qryzone))
               {
                   $zoneName = $zone_data["Name"] ;
               }

               $AddedLoc = " And " ;
               $AddedLocMD = " And " ;

               $sQuery = "Select Location                     ".
                         "  From bas_filmsupplyzoneloc        ".
                         " Where Zone = '04'                  " ;
               $qryzoneloc = mysql_query($sQuery,$connect) ;
               while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
               {
                    if  ($AddedLoc == " And ")
                        $AddedLoc .= "( singo.Location = '".$zoneloc_data["Location"]."' "  ;
                    else
                        $AddedLoc .= " or singo.Location = '".$zoneloc_data["Location"]."' "  ;

                    if  ($AddedLocMD == " And ")
                        $AddedLocMD .= "( Location = '".$zoneloc_data["Location"]."' "  ;
                    else
                        $AddedLocMD .= " or Location = '".$zoneloc_data["Location"]."' "  ;
               }
               $AddedLoc .= ")" ;
               $AddedLocMD .= ")" ;

               // 스크린수 (경기)
               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntShowroom                               ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($cntShowroom_data = mysql_fetch_array($qrysingo2))
               {
                   $cntRealScreen = $cntShowroom_data["cntShowroom"] ;
               }

               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntFinishShowroom                         ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom,                    ".
                         "       bas_silmoojatheatherfinish As finish         ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         "   And singo.theather = finish.theather             ".
                         "   And singo.room     = finish.room                 ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          ".
                         $FinishCont."                                        ".
                         "   And singo.silmooja = finish.silmooja             " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($cntShowroom_data = mysql_fetch_array($qrysingo2))
               {
                   $cntRealScreen = $cntRealScreen - $cntShowroom_data["cntFinishShowroom"] ;
               }

               // 당일합계
               $ModifyScore  = 0 ; // 수정스코어(당일)


               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore   ".
                         "  From bas_modifyscore                        ".
                         " Where ".$FilmCond."                          ".
                         "   And ModifyDate = '".$WorkDate."'           ".
                         $AddedLocMD."                                  " ;
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
               }


               $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                         "  From ".$sSingoName."                    ".
                         " Where SingoDate  = '".$WorkDate."'       ".
                         "   And ".$FilmCond."                      ".
                         $AddedLocMD."                              " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
               {
                   $WorkDateNumPersons = $NumPersons_data["SumNumPersons"] + $ModifyScore ;
               }

               // 전일합계
               $ModifyScore  = 0 ; // 수정스코어(전일)



               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore   ".
                         "  From bas_modifyscore                        ".
                         " Where ".$FilmCond."                          ".
                         "   And ModifyDate = '".$AgoDate."'            ".
                         $AddedLocMD."                                    " ;
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
               }

               $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                         "  From ".$sSingoName."                    ".
                         " Where SingoDate  = '".$AgoDate."'        ".
                         "   And ".$FilmCond."                      ".
                         $AddedLocMD."                              " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
               {
                   $AgoDateNumPersons = $NumPersons_data["SumNumPersons"] + $ModifyScore ;
               }

               // 총누계
               $ModifyScore  = 0 ; // 수정스코어(누계)



               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore,  ".
                         "       Sum(ModifyAmount) As SumOfModifyAmount ".
                         "  From bas_modifyscore                        ".
                         " Where ".$FilmCond."                          ".
                         "   And ModifyDate <= '".$WorkDate."'          ".
                         $AddedLocMD."                                    " ;
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
                   $ModifyAmount = $modifyscore_data["SumOfModifyAmount"] ;
               }


               $sQuery = "Select Sum(NumPersons) As SumNumPersons,  ".
                         "       Sum(TotAmount) As SumTotAmount     ".
                         "  From ".$sSingoName."                    ".
                         " Where SingoDate  <= '".$WorkDate."'      ".
                         "   And ".$FilmCond."                      ".
                         $AddedLocMD."                              " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
               {
                   $TotNumPersons = $NumPersons_data["SumNumPersons"] + $ModifyScore ;
                   $TotTotAmount  = $NumPersons_data["SumTotAmount"]  + $ModifyAmount ;
               }
           }

           //
           // 부산 ----------------------------------------------------------------------------------
           //
           if  ($item=="L3")
           {


               // 스크린수 (예상 부산)
               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntShowroom                               ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And ( singo.location = 200                       ".
                         "    Or   singo.location = 201                       ".
                         "    Or   singo.location = 202                       ".
                         "    Or   singo.location = 203                       ".
                         "    Or   singo.location = 204                       ".
                         "    Or   singo.location = 205                       ".
                         "    Or   singo.location = 207                       ".
                         "    Or   singo.location = 208                       ".
                         "    Or   singo.location = 209                       ".
                         "    Or   singo.location = 210                       ".
                         "    Or   singo.location = 211                       ".
                         "    Or   singo.location = 212                       ".
                         "    Or   singo.location = 213                       ".
                         "    Or   singo.location = 600  )                    ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($cntShowroom_data = mysql_fetch_array($qrysingo2))
               {
                   $cntRealScreen = $cntShowroom_data["cntShowroom"] ;
               }

               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntFinishShowroom                         ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom,                    ".
                         "       bas_silmoojatheatherfinish As finish         ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.location = 200                         ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         "   And singo.theather = finish.theather             ".
                         "   And singo.room     = finish.room                 ".
                         $AddedCont."                                         ".
                         $FinishCont."                                        ".
                         "   And singo.silmooja = finish.silmooja             " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($cntShowroom_data = mysql_fetch_array($qrysingo2))
               {
                    $cntRealScreen = $cntRealScreen - $cntShowroom_data["cntFinishShowroom"] ;
               }

               // 당일합계
               $ModifyScore  = 0 ; // 수정스코어(당일)


               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore   ".
                         "  From bas_modifyscore                        ".
                         " Where ".$FilmCond."                          ".
                         "   And ModifyDate = '".$WorkDate."'           ".
                         "   And (Location  = '200'                     ". // 부산
                         "    Or  Location  = '201'                     ". // 창원
                         "    Or  Location  = '202'                     ". // 마산
                         "    Or  Location  = '203'                     ". // 통영
                         "    Or  Location  = '204'                     ". // 진해
                         "    Or  Location  = '205'                     ". // 진주
                         "    Or  Location  = '207'                     ". // 김해
                         "    Or  Location  = '208'                     ". // 거제
                         "    Or  Location  = '209'                     ". // 밀양
                         "    Or  Location  = '210'                     ". // 고성
                         "    Or  Location  = '211'                     ". // 사천
                         "    Or  Location  = '212'                     ". // 거창
                         "    Or  Location  = '213'                     ". // 양산
                         "    Or  Location  = '600')                    " ;// 울산
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
               }


               $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                         "  From ".$sSingoName."                    ".
                         " Where SingoDate  = '".$WorkDate."'       ".
                         "   And ".$FilmCond."                      ".
                         "   And (Location  = '200'                 ". // 부산
                         "    Or  Location  = '201'                 ". // 창원
                         "    Or  Location  = '202'                 ". // 마산
                         "    Or  Location  = '203'                 ". // 통영
                         "    Or  Location  = '204'                 ". // 진해
                         "    Or  Location  = '205'                 ". // 진주
                         "    Or  Location  = '207'                 ". // 김해
                         "    Or  Location  = '208'                 ". // 거제
                         "    Or  Location  = '209'                 ". // 밀양
                         "    Or  Location  = '210'                 ". // 고성
                         "    Or  Location  = '211'                 ". // 사천
                         "    Or  Location  = '212'                 ". // 거창
                         "    Or  Location  = '213'                 ". // 양산
                         "    Or  Location  = '600')                " ;// 울산
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
               {
                   $WorkDateNumPersons = $NumPersons_data["SumNumPersons"] + $ModifyScore ;
               }

               // 전일합계
               $ModifyScore  = 0 ; // 수정스코어(전일)

               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore   ".
                         "  From bas_modifyscore                        ".
                         " Where ".$FilmCond."                          ".
                         "   And ModifyDate = '".$AgoDate."'            ".
                         "   And (Location  = '200'                     ". // 부산
                         "    Or  Location  = '201'                     ". // 창원
                         "    Or  Location  = '202'                     ". // 마산
                         "    Or  Location  = '203'                     ". // 통영
                         "    Or  Location  = '204'                     ". // 진해
                         "    Or  Location  = '205'                     ". // 진주
                         "    Or  Location  = '207'                     ". // 김해
                         "    Or  Location  = '208'                     ". // 거제
                         "    Or  Location  = '209'                     ". // 밀양
						 "    Or  Location  = '210'                     ". // 고성
                         "    Or  Location  = '211'                     ". // 사천
                         "    Or  Location  = '212'                     ". // 거창
                         "    Or  Location  = '213'                     ". // 양산
                         "    Or  Location  = '600')                    " ;// 울산
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
               }


               $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                         "  From ".$sSingoName."                    ".
                         " Where SingoDate  = '".$AgoDate."'        ".
                         "   And ".$FilmCond."                      ".
                         "   And (Location  = '200'                 ". // 부산
                         "    Or  Location  = '600'                 ". // 울산
                         "    Or  Location  = '207'                 ". // 김해
                         "    Or  Location  = '205'                 ". // 진주
                         "    Or  Location  = '208'                 ". // 거제
                         "    Or  Location  = '202'                 ". // 마산
						 "    Or  Location  = '211'                 ". // 사천
                         "    Or  Location  = '212'                 ". // 거창
                         "    Or  Location  = '213'                 ". // 양산
                         "    Or  Location  = '201')                " ;// 창원
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
               {
                   $AgoDateNumPersons = $NumPersons_data["SumNumPersons"] + $ModifyScore ;
               }

               // 총누계
               $ModifyScore  = 0 ; // 수정스코어(누계)


               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore,  ".
                         "       Sum(ModifyAmount) As SumOfModifyAmount ".
                         "  From bas_modifyscore                        ".
                         " Where ".$FilmCond."                          ".
                         "   And ModifyDate <= '".$WorkDate."'          ".
                         "   And (Location  = '200'                     ". // 부산
                         "    Or  Location  = '600'                     ". // 울산
                         "    Or  Location  = '207'                     ". // 김해
                         "    Or  Location  = '205'                     ". // 진주
                         "    Or  Location  = '208'                     ". // 거제
                         "    Or  Location  = '202'                     ". // 마산
						 "    Or  Location  = '211'                     ". // 사천
                         "    Or  Location  = '212'                     ". // 거창
                         "    Or  Location  = '213'                     ". // 양산
                         "    Or  Location  = '201')                    " ;// 창원
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               if  ($modifyscore_data = mysql_fetch_array($qry_modifyscore))
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
                   $ModifyAmount = $modifyscore_data["SumOfModifyAmount"] ;
               }


               $sQuery = "Select Sum(NumPersons) As SumNumPersons,  ".
                         "       Sum(TotAmount) As SumTotAmount     ".
                         "  From ".$sSingoName."                    ".
                         " Where SingoDate <= '".$WorkDate."'       ".
                         "   And ".$FilmCond."                      ".
                         "   And (Location  = '200'                 ". // 부산
                         "    Or  Location  = '600'                 ". // 울산
                         "    Or  Location  = '207'                 ". // 김해
                         "    Or  Location  = '205'                 ". // 진주
                         "    Or  Location  = '208'                 ". // 거제
                         "    Or  Location  = '202'                 ". // 마산
						 "    Or  Location  = '211'                 ". // 사천
                         "    Or  Location  = '212'                 ". // 거창
                         "    Or  Location  = '213'                 ". // 양산
                         "    Or  Location  = '201')                " ; // 창원
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
               {
                   $TotNumPersons = $NumPersons_data["SumNumPersons"] + $ModifyScore ;
                   $TotTotAmount  = $NumPersons_data["SumTotAmount"]  + $ModifyAmount ;
               }

           }

           //
           // 지방 ----------------------------------------------------------------------------------
           //
           if  ($item=="L4")
           {

               $sQuery = "Select Location From bas_filmsupplyzoneloc ".
                         " Where Zone = '04'                         " ;
               $qryzoneloc = mysql_query($sQuery,$connect) ;

               $AddedLoc = " and " ;
               $AddedLocMD = " and " ;

               while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
               {
                    if  ($AddedLoc == " and ")
                        $AddedLoc .= "( singo.Location <> '".$zoneloc_data["Location"]."' "  ;
                    else
                        $AddedLoc .= " and singo.Location <> '".$zoneloc_data["Location"]."' "  ;

                    if  ($AddedLocMD == " and ")
                        $AddedLocMD .= "( Location <> '".$zoneloc_data["Location"]."' "  ;
                    else
                        $AddedLocMD .= " and Location <> '".$zoneloc_data["Location"]."' "  ;
               }
               $AddedLoc .= " and singo.Location <> '100' "  ; // 서울
               $AddedLoc .= " and singo.Location <> '200' "  ; // 부산
               $AddedLoc .= " and singo.Location <> '600' "  ; // 울산
               $AddedLoc .= " and singo.Location <> '207' "  ; // 김해
               $AddedLoc .= " and singo.Location <> '205' "  ; // 진주
               $AddedLoc .= " and singo.Location <> '208' "  ; // 거제
               $AddedLoc .= " and singo.Location <> '202' "  ; // 마산
               $AddedLoc .= " and singo.Location <> '211' "  ; // 사천
               $AddedLoc .= " and singo.Location <> '212' "  ; // 거창
               $AddedLoc .= " and singo.Location <> '213' "  ; // 양산
               $AddedLoc .= " and singo.Location <> '201' "  ; // 창원
               $AddedLoc .= ")" ;

               $AddedLocMD .= " and Location <> '100' "  ; // 서울
               $AddedLocMD .= " and Location <> '200' "  ; // 부산
               $AddedLocMD .= " and Location <> '600' "  ; // 울산
               $AddedLocMD .= " and Location <> '207' "  ; // 김해
               $AddedLocMD .= " and Location <> '205' "  ; // 진주
               $AddedLocMD .= " and Location <> '208' "  ; // 거제
               $AddedLocMD .= " and Location <> '202' "  ; // 마산
               $AddedLocMD .= " and Location <> '211' "  ; // 사천
               $AddedLocMD .= " and Location <> '212' "  ; // 거창
               $AddedLocMD .= " and Location <> '213' "  ; // 양산
               $AddedLocMD .= " and Location <> '201' "  ; // 창원
               $AddedLocMD .= ")" ;

               // 스크린수 (지방)
               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntShowroom                               ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($cntShowroom_data = mysql_fetch_array($qrysingo2))
               {
                   $cntRealScreen = $cntShowroom_data["cntShowroom"] ;
               }
               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntFinishShowroom                         ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom,                    ".
                         "       bas_silmoojatheatherfinish As finish         ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         "   And singo.theather = finish.theather             ".
                         "   And singo.room     = finish.room                 ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          ".
                         $FinishCont."                                        ".
                         "   And singo.silmooja = finish.silmooja             " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($cntShowroom_data = mysql_fetch_array($qrysingo2))
               {
                   $cntRealScreen = $cntRealScreen - $cntShowroom_data["cntFinishShowroom"] ;
               }

               // 당일합계
               $ModifyScore  = 0 ; // 수정스코어(당일)


               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore   ".
                         "  From bas_modifyscore                        ".
                         " Where ".$FilmCond."                          ".
                         "   And ModifyDate = '".$WorkDate."'           ".
                         $AddedLocMD."                                    " ;
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               $modifyscore_data = mysql_fetch_array($qry_modifyscore) ;
               if  ($modifyscore_data)
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
               }


               $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                         "  From ".$sSingoName."                    ".
                         " Where SingoDate  = '".$WorkDate."'       ".
                         "   And ".$FilmCond."                      ".
                         $AddedLocMD."                              " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
               {
                   $WorkDateNumPersons = $NumPersons_data["SumNumPersons"] + $ModifyScore ;
               }

               // 전일합계
               $ModifyScore  = 0 ; // 수정스코어(전일)


               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore   ".
                         "  From bas_modifyscore                        ".
                         " Where ".$FilmCond."                          ".
                         "   And ModifyDate = '".$AgoDate."'            ".
                         $AddedLocMD."                                    " ;
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               $modifyscore_data = mysql_fetch_array($qry_modifyscore) ;
               if  ($modifyscore_data)
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
               }


               $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                         "  From ".$sSingoName."                    ".
                         " Where SingoDate  = '".$AgoDate."'        ".
                         "   And ".$FilmCond."                      ".
                         $AddedLocMD."                              " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
               {
                   $AgoDateNumPersons = $NumPersons_data["SumNumPersons"] + $ModifyScore ;
               }

               // 총누계
               $ModifyScore  = 0 ; // 수정스코어(누계)


               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore,  ".
                         "       Sum(ModifyAmount) As SumOfModifyAmount ".
                         "  From bas_modifyscore                        ".
                         " Where ".$FilmCond."                          ".
                         "   And ModifyDate <= '".$WorkDate."'          ".
                         $AddedLocMD."                                  " ;
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               $modifyscore_data = mysql_fetch_array($qry_modifyscore) ;
               if  ($modifyscore_data)
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
                   $ModifyAmount = $modifyscore_data["SumOfModifyAmount"] ;
               }


               $sQuery = "Select Sum(NumPersons) As SumNumPersons,  ".
                         "       Sum(TotAmount) As SumTotAmount     ".
                         "  From ".$sSingoName."                    ".
                         " Where SingoDate  <= '".$WorkDate."'      ".
                         "   And ".$FilmCond."                      ".
                         $AddedLocMD."                              " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
               {
                   $TotNumPersons = $NumPersons_data["SumNumPersons"] + $ModifyScore ;
                   $TotTotAmount  = $NumPersons_data["SumTotAmount"]  + $ModifyAmount ;
               }

           }

           //
           // 전체 ----------------------------------------------------------------------------------
           //
           if  ($item=="L5")
           {

               $AddedLoc = " and " ;
               $sQuery = "Select Location                     ".
                         "  From bas_filmsupplyzoneloc        ".
                         " Where (  Zone = '21'               ".
                         "       Or Zone = '04'               ".
                         "       Or Zone = '99' )             " ;
               $qryzoneloc = mysql_query($sQuery,$connect) ;
               while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
               {
                    if  ($AddedLoc == " and ")
                        $AddedLoc .= " ( singo.Location = '".$zoneloc_data["Location"]."' "  ;
                    else
                        $AddedLoc .= " or singo.Location = '".$zoneloc_data["Location"]."' "  ;
               }
               $AddedLoc .= " or singo.Location = '100' "  ; // 서울
               $AddedLoc .= " or singo.Location = '200' "  ; // 부산
               $AddedLoc .= " or singo.Location = '600' "  ; // 울산
               $AddedLoc .= " or singo.Location = '207' "  ; // 김해
               $AddedLoc .= " or singo.Location = '205' "  ; // 진주
               $AddedLoc .= " or singo.Location = '208' "  ; // 거제
               $AddedLoc .= " or singo.Location = '202' "  ; // 마산
			   $AddedLoc .= " or singo.Location = '211' "  ; // 사천
			   $AddedLoc .= " or singo.Location = '212' "  ; // 거창
			   $AddedLoc .= " or singo.Location = '213' "  ; // 양산
               $AddedLoc .= " or singo.Location = '201' "  ; // 창원
               $AddedLoc .= ")" ;


               // 스크린수 (총합계)
               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntShowroom                               ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom                     ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          " ;
               $qrysingo3 = mysql_query($sQuery,$connect) ;
               if  ($cntShowroom_data = mysql_fetch_array($qrysingo3))
               {
                   $cntRealScreen = $cntShowroom_data["cntShowroom"] ;
               }
               $sQuery = "Select count(distinct singo.theather, singo.room)   ".
                         "       As cntFinishShowroom                         ".
                         "  From ".$sSingoName." As singo,                    ".
                         "       bas_showroom As showroom,                    ".
                         "       bas_silmoojatheatherfinish As finish         ".
                         " Where singo.singodate  = '".$WorkDate."'           ".
                         "   And singo.theather = showroom.theather           ".
                         "   And singo.room     = showroom.room               ".
                         "   And singo.theather = finish.theather             ".
                         "   And singo.room     = finish.room                 ".
                         $AddedCont."                                         ".
                         $AddedLoc."                                          ".
                         $FinishCont."                                        ".
                         "   And singo.silmooja = finish.silmooja             " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($cntShowroom_data = mysql_fetch_array($qrysingo2))
               {
                   $cntRealScreen = $cntRealScreen - $cntShowroom_data["cntFinishShowroom"] ;
               }

               // 당일합계
               $ModifyScore  = 0 ; // 수정스코어(당일)


               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore   ".
                         "  From bas_modifyscore                        ".
                         " Where ".$FilmCond."                          ".
                         "   And ModifyDate = '".$WorkDate."'           " ;
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               $modifyscore_data = mysql_fetch_array($qry_modifyscore) ;
               if  ($modifyscore_data)
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
               }


               $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                         "  From ".$sSingoName."                    ".
                         " Where SingoDate  = '".$WorkDate."'       ".
                         "   And ".$FilmCond."                      " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
               {
                   $WorkDateNumPersons = $NumPersons_data["SumNumPersons"] + $ModifyScore ;
               }

               // 전일합계
               $ModifyScore  = 0 ; // 수정스코어(전일)


               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore   ".
                         "  From bas_modifyscore                        ".
                         " Where ".$FilmCond."                          ".
                         "   And ModifyDate = '".$AgoDate."'            " ;
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               $modifyscore_data = mysql_fetch_array($qry_modifyscore) ;
               if  ($modifyscore_data)
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
               }


               $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                         "  From ".$sSingoName."                    ".
                         " Where SingoDate  = '".$AgoDate."'        ".
                         "   And ".$FilmCond."                      " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
               {
                   $AgoDateNumPersons = $NumPersons_data["SumNumPersons"] + $ModifyScore ;
               }

               // 총누계
               $ModifyScore  = 0 ; // 수정스코어(누계)


               $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore,  ".
                         "       Sum(ModifyAmount) As SumOfModifyAmount ".
                         "  From bas_modifyscore                        ".
                         " Where ".$FilmCond."                          ".
                         "   And ModifyDate <= '".$WorkDate."'          " ;
               $qry_modifyscore  = mysql_query($sQuery,$connect) ;
               $modifyscore_data = mysql_fetch_array($qry_modifyscore) ;
               if  ($modifyscore_data)
               {
                   $ModifyScore  = $modifyscore_data["SumOfModifyScore"] ;
                   $ModifyAmount = $modifyscore_data["SumOfModifyAmount"] ;
               }


               $sQuery = "Select Sum(NumPersons) As SumNumPersons,  ".
                         "       Sum(TotAmount) As SumTotAmount     ".
                         "  From ".$sSingoName."                    ".
                         " Where SingoDate <= '".$WorkDate."'       ".
                         "   And ".$FilmCond."                      " ;
               $qrysingo2 = mysql_query($sQuery,$connect) ;
               if  ($NumPersons_data = mysql_fetch_array($qrysingo2))
               {
                   $TotNumPersons = $NumPersons_data["SumNumPersons"] + $ModifyScore ;
                   $TotTotAmount  = $NumPersons_data["SumTotAmount"]  + $ModifyAmount ;
               }
           }

           $sResult .= ("<Screen>"      .$cntRealScreen      ."</Screen>") ;   // 스크린수
           $sResult .= ("<Score>"       .$WorkDateNumPersons ."</Score>") ;    // 당일합계
           $sResult .= ("<AgoScore>"    .$AgoDateNumPersons  ."</AgoScore>") ; // 전일합계
           $sResult .= ("<TotalAcc>"    .$TotNumPersons      ."</TotalAcc>") ;     // 총누계
           $sResult .= ("<TotalAmount>" .$TotTotAmount       ."</TotalAmount>") ;  // 총금액


           // 찌꺼기 캐쉬를 다 지운다..
           $sQuery = "Delete From tmp_globaltotal              ".
                     " Where WorkDate    = '".$WorkDate."'     ".
                     "   And FilmCode    = '".$FilmTitle."'    ".
                     "   And Location    = '".$item."'         " ;
           mysql_query($sQuery,$connect) ;

           // 캐쉬를 구성한다.
           $sQuery = "Insert Into tmp_globaltotal              ".
                     "Values                                   ".
                     "(                                        ".
                     "   '".$WorkDate."',                      ".
                     "   '".$FilmTitle."',                     ".
                     "   '".$item."',                          ".
                     "   '".$sCurrTime."',                     ".

                     "   '".$cntRealScreen."',                 ".
                     "   '".$WorkDateNumPersons."',            ".
                     "   '".$AgoDateNumPersons."',             ".
                     "   '".$TotNumPersons."',                 ".
                     "   '".$TotTotAmount."'                   ".
                     ")                                        " ;
           mysql_query($sQuery,$connect) ;
       }

       return $sResult ;
   }



   //
   // 일별누계
   //
   function getLineData1($connect,$WorkDate,$FilmTitle)
   {
       $Now       = strtotime('now');

       $sCurrDay  = date("Ymd",$Now) ; // 현재일...
       $sCurrTime = date("YmdHis",$Now) ; // 현재시간...
       $sAgo10Sec = date("YmdHis",strtotime('-20 second',$Now)) ;   // 10초전 ..
       $sAgo1Hour = date("YmdHis",strtotime('-60 minutes',$Now)) ;  // 60분전 ..
       $sAgo2Hour = date("YmdHis",strtotime('-120 minutes',$Now)) ; // 120분전 ..


       $FilmTileOpen = substr($FilmTitle,0,6) ;
       $FilmTileFilm = substr($FilmTitle,6,2) ;

       if   ($FilmTileFilm == '00') // 분리된영화의통합코드
       {
            $FilmCond = " Open = '".$FilmTileOpen."' " ;
       }
       else
       {
            $FilmCond = "    Open = '".$FilmTileOpen."' ".
                        "And Film = '".$FilmTileFilm."' " ;
       }

       $sResult = "";

       $WorkDateNumPersonsAcc = 0 ;


       $sQuery = "Select Max(SingoDate) As MaxWorkDate        ".
                 "  From ".$sSingoName."                      ".
                 " Where Open       = '".$FilmTileOpen."'     ".
                 "   And Film       = '".$FilmTileFilm."'     " ;
       $QRY_Singo = mysql_query($sQuery,$connect) ; // 최근일자를 구한다. Z
       if  ($OBJ_Singo = mysql_fetch_object($QRY_Singo))
       {
           $MinWorkDate = "20".$FilmTileOpen ;
           $MaxWorkDate = $OBJ_Singo->MaxWorkDate ;

           $CuserDate = $MinWorkDate ;

           $sResult = "" ;

           while (($CuserDate<=$MaxWorkDate) && ($CuserDate<=$WorkDate))
           {
                $sResult .= "<Total3>"."<Day>" ;
                if  ($CurMonth != substr($CuserDate,4,2))
                {
                    $CurMonth = substr($CuserDate,4,2) ;

                    $sResult .= $CurMonth."/" ;
                }

                $sResult .= substr($CuserDate,6,2) ;
                $sResult .= "</Day>" ;

                // 캐쉬에 데이터가 있는지 확인하고
                // 있으면 1분이전에 내용이면 그대로 가지고 오고
                // 아니면 일단지우고 ..
                // 신고자료에 해당일에 합계를 구한다..
                // 그런다음 캐쉬에 저장한다.


                if  (($CuserDate == $WorkDate) && ($CuserDate == $sCurrDay))
                {
                   $sQuery = "Select * From tmp_daylytotal0            ".
                             " Where WorkDate    = '".$CuserDate."'    ".
                             "   And FilmCode    = '".$FilmTitle."'    ".
                             "   And UpdateTime >= '".$sAgo10Sec."'    " ;
                }
                else
                {
                    $sQuery = "Select * From tmp_daylytotal0            ".
                              " Where WorkDate    = '".$CuserDate."'    ".
                              "   And FilmCode    = '".$FilmTitle."'    ".
                              "   And UpdateTime >= '".$sAgo1Hour."'    " ;
                }
                $QRY_GT = mysql_query($sQuery,$connect) ;
                if  ( $OBJ_GT = mysql_fetch_object( $QRY_GT ) ) // 만일에 캐쉬가 존재한다면..
                {
                    $WorkDateNumPersonsAcc += $OBJ_GT->Value0 ;

                    $sResult .= "<Value>". $WorkDateNumPersonsAcc ."</Value>"."</Total3>" ;
                }
                else
                {
                    $WorkDateNumPersons = 0 ;

                    $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                              "  From ".$sSingoName."                    ".
                              " Where SingoDate  = '".$CuserDate."'      ".
                              "   And ".$FilmCond."                      " ;
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ( $NumPersons_data = mysql_fetch_array($qrysingo2) )
                    {
                        $WorkDateNumPersons = $NumPersons_data["SumNumPersons"] ;
                    }

                    $WorkDateNumPersonsAcc += $WorkDateNumPersons ;

                    $sResult .= "<Value>". $WorkDateNumPersonsAcc ."</Value>"."</Total3>" ;

                    // 찌꺼기 캐쉬를 다 지운다..
                    $sQuery = "Delete From tmp_daylytotal0              ".
                              " Where WorkDate    = '".$CuserDate."'    ".
                              "   And FilmCode    = '".$FilmTitle."'    " ;
                    mysql_query($sQuery,$connect) ;

                    // 캐쉬를 구성한다.
                    $sQuery = "Insert Into tmp_daylytotal0      ".
                              "Values                           ".
                              "(                                ".
                              "   '".$CuserDate."',             ".
                              "   '".$FilmTitle."',             ".
                              "   '".$sCurrTime."',             ".
                              "   '".$WorkDateNumPersons."'     ".
                              ")                                " ;
                    mysql_query($sQuery,$connect) ;
                }

                $CuserDate = date("Ymd",strtotime("+1 day",strtotime(substr($CuserDate,0,4)."-".substr($CuserDate,4,2)."-".substr($CuserDate,6,2).""))) ;
           }
           return $sResult ;
       }
       else
       {
           return "" ;
       }
   }

   //
   // 일별집계
   //
   function getLineData2($connect,$WorkDate,$FilmTitle)
   {
       $Now       = strtotime('now');

       $sCurrDay  = date("Ymd",$Now) ; // 현재일...
       $sCurrTime = date("YmdHis",$Now) ; // 현재시간...
       $sAgo10Sec = date("YmdHis",strtotime('-20 second',$Now)) ;   // 10초전 ..
       $sAgo1Hour = date("YmdHis",strtotime('-60 minutes',$Now)) ;  // 60분전 ..
       $sAgo2Hour = date("YmdHis",strtotime('-120 minutes',$Now)) ; // 120분전 ..


       $FilmTileOpen = substr($FilmTitle,0,6) ;
       $FilmTileFilm = substr($FilmTitle,6,2) ;

       if   ($FilmTileFilm == '00') // 분리된영화의통합코드
       {
            $FilmCond = " Open = '".$FilmTileOpen."' " ;
       }
       else
       {
            $FilmCond = "    Open = '".$FilmTileOpen."' ".
                        "And Film = '".$FilmTileFilm."' " ;
       }

       $sResult = "";

       $WorkDateNumPersonsAcc = 0 ;


       $sQuery = "Select Max(SingoDate) As MaxWorkDate        ".
                 "  From ".$sSingoName."                      ".
                 " Where Open       = '".$FilmTileOpen."'     ".
                 "   And Film       = '".$FilmTileFilm."'     " ;
       $QRY_Singo = mysql_query($sQuery,$connect) ; // 최근일자를 구한다. Z
       if  ($OBJ_Singo = mysql_fetch_object($QRY_Singo))
       {
           $MinWorkDate = "20".$FilmTileOpen ;
           $MaxWorkDate = $OBJ_Singo->MaxWorkDate ;

           $CuserDate = $MinWorkDate ;

           $sResult = "" ;

           while (($CuserDate<=$MaxWorkDate) && ($CuserDate<=$WorkDate))
           {

                $sResult .= "<Total4>"."<Day>" ;
                if  ($CurMonth != substr($CuserDate,4,2))
                {
                    $CurMonth = substr($CuserDate,4,2) ;

                    $sResult .= $CurMonth."/" ;
                }

                $sResult .= substr($CuserDate,6,2) ;
                $sResult .= "</Day>" ;

                // 캐쉬에 데이터가 있는지 확인하고
                // 있으면 1분이전에 내용이면 그대로 가지고 오고
                // 아니면 일단지우고 ..
                // 신고자료에 해당일에 합계를 구한다..
                // 그런다음 캐쉬에 저장한다.


                if  (($CuserDate == $WorkDate) && ($CuserDate == $sCurrDay))
                {
                   $sQuery = "Select * From tmp_daylytotal0            ".
                             " Where WorkDate    = '".$CuserDate."'    ".
                             "   And FilmCode    = '".$FilmTitle."'    ".
                             "   And UpdateTime >= '".$sAgo10Sec."'    " ;
                }
                else
                {
                    $sQuery = "Select * From tmp_daylytotal0            ".
                              " Where WorkDate    = '".$CuserDate."'    ".
                              "   And FilmCode    = '".$FilmTitle."'    ".
                              "   And UpdateTime >= '".$sAgo1Hour."'    " ;
                }

                $QRY_GT = mysql_query($sQuery,$connect) ;
                if  ( $OBJ_GT = mysql_fetch_object( $QRY_GT ) ) // 만일에 캐쉬가 존재한다면..
                {
                    $WorkDateNumPersons =  $OBJ_GT->Value0 ; // 그대로 간다..

                    $sResult .= "<Value>". $WorkDateNumPersons ."</Value>"."</Total4>" ;
                }
                else
                {
                    $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                              "  From ".$sSingoName."                    ".
                              " Where SingoDate  = '".$CuserDate."'      ".
                              "   And ".$FilmCond."                      " ;
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ( $NumPersons_data = mysql_fetch_array($qrysingo2) )
                    {
                        $WorkDateNumPersons = $NumPersons_data["SumNumPersons"] ;
                    }

                    $sResult .= "<Value>". $WorkDateNumPersons ."</Value>"."</Total4>" ;

                    // 찌꺼기 캐쉬를 다 지운다..
                    $sQuery = "Delete From tmp_daylytotal0              ".
                              " Where WorkDate    = '".$CuserDate."'    ".
                              "   And FilmCode    = '".$FilmTitle."'    " ;
                    mysql_query($sQuery,$connect) ;

                    // 캐쉬를 구성한다.
                    $sQuery = "Insert Into tmp_daylytotal0      ".
                              "Values                           ".
                              "(                                ".
                              "   '".$CuserDate."',             ".
                              "   '".$FilmTitle."',             ".
                              "   '".$sCurrTime."',             ".
                              "   '".$WorkDateNumPersons."'     ".
                              ")                                " ;
                    mysql_query($sQuery,$connect) ;
                }

                $CuserDate = date("Ymd",strtotime("+1 day",strtotime(substr($CuserDate,0,4)."-".substr($CuserDate,4,2)."-".substr($CuserDate,6,2).""))) ;
           }
           return $sResult ;
       }
       else
       {
           return "" ;
       }
   }

   //
   // 일별증감
   //
   function getColumData1($connect,$WorkDate,$FilmTitle)
   {
       $Now       = strtotime('now');

       $sCurrDay  = date("Ymd",$Now) ; // 현재일...
       $sCurrTime = date("YmdHis",$Now) ; // 현재시간...
       $sAgo10Sec = date("YmdHis",strtotime('-20 second',$Now)) ;   // 10초전 ..
       $sAgo1Hour = date("YmdHis",strtotime('-60 minutes',$Now)) ;  // 60분전 ..
       $sAgo2Hour = date("YmdHis",strtotime('-120 minutes',$Now)) ; // 120분전 ..


       $FilmTileOpen = substr($FilmTitle,0,6) ;
       $FilmTileFilm = substr($FilmTitle,6,2) ;

       if   ($FilmTileFilm == '00') // 분리된영화의통합코드
       {
            $FilmCond = " Open = '".$FilmTileOpen."' " ;
       }
       else
       {
            $FilmCond = "    Open = '".$FilmTileOpen."' ".
                        "And Film = '".$FilmTileFilm."' " ;
       }

       $sResult = "";
       $BeforeValue = 0 ;



       $sQuery = "Select Max(SingoDate) As MaxWorkDate        ".
                 "  From ".$sSingoName."                      ".
                 " Where Open       = '".$FilmTileOpen."'     ".
                 "   And Film       = '".$FilmTileFilm."'     " ;
       $QRY_Singo = mysql_query($sQuery,$connect) ;
       if  ($OBJ_Singo = mysql_fetch_object($QRY_Singo))
       {
           $MinWorkDate = "20".$FilmTileOpen ;
           $MaxWorkDate = $OBJ_Singo->MaxWorkDate ;

           $CuserDate = $MinWorkDate ;

           $sResult = "" ;

           while (($CuserDate<=$MaxWorkDate) && ($CuserDate<=$WorkDate))
           {

                $sResult .= "<Total5>"."<Day>" ;
                if  ($CurMonth != substr($CuserDate,4,2))
                {
                    $CurMonth = substr($CuserDate,4,2) ;

                    $sResult .= $CurMonth."/" ;
                }

                $sResult .= substr($CuserDate,6,2) ;
                $sResult .= "</Day>" ;

                // 캐쉬에 데이터가 있는지 확인하고
                // 있으면 1분이전에 내용이면 그대로 가지고 오고
                // 아니면 일단지우고 ..
                // 신고자료에 해당일에 합계를 구한다..
                // 그런다음 캐쉬에 저장한다.


                if  (($CuserDate == $WorkDate) && ($CuserDate == $sCurrDay))
                {
                   $sQuery = "Select * From tmp_daylytotal0            ".
                             " Where WorkDate    = '".$CuserDate."'    ".
                             "   And FilmCode    = '".$FilmTitle."'    ".
                             "   And UpdateTime >= '".$sAgo10Sec."'    " ;
                }
                else
                {
                    $sQuery = "Select * From tmp_daylytotal0            ".
                              " Where WorkDate    = '".$CuserDate."'    ".
                              "   And FilmCode    = '".$FilmTitle."'    ".
                              "   And UpdateTime >= '".$sAgo1Hour."'    " ;
                }
                $QRY_GT = mysql_query($sQuery,$connect) ;
                if  ( $OBJ_GT = mysql_fetch_object( $QRY_GT ) ) // 만일에 캐쉬가 존재한다면..
                {
                    $WorkDateNumPersons =  $OBJ_GT->Value0 ; // 그대로 간다..

                    $Gap = $WorkDateNumPersons - $BeforeValue ;

                    if  ($Gap==0)
                    {
                        $sResult .= "<Value1>". "0"  ."</Value1>" ;
                        $sResult .= "<Value2>". "0"  ."</Value2>" ;
                    }
                    else
                    {
                        if  ($Gap>0)
                        {
                            $sResult .= "<Value1>". $Gap ."</Value1>" ;
                            $sResult .= "<Value2>". "0"  ."</Value2>" ;
                        }
                        if  ($Gap<0)
                        {
                            $sResult .= "<Value1>". "0"  ."</Value1>" ;
                            $sResult .= "<Value2>". $Gap ."</Value2>" ;
                        }
                    }
                    $sResult .= "</Total5>" ;


                    $BeforeValue = $WorkDateNumPersons ;
                }
                else
                {
                    $WorkDateNumPersons = 0 ;

                    $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                              "  From ".$sSingoName."                    ".
                              " Where SingoDate  = '".$CuserDate."'      ".
                              "   And ".$FilmCond."                      " ;
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if ( $NumPersons_data = mysql_fetch_array($qrysingo2) )
                    {
                       $WorkDateNumPersons = $NumPersons_data["SumNumPersons"] ;
                    }

                    $Gap = $WorkDateNumPersons - $BeforeValue ;

                    if  ($Gap==0)
                    {
                        $sResult .= "<Value1>". "0"  ."</Value1>" ;
                        $sResult .= "<Value2>". "0"  ."</Value2>" ;
                    }
                    else
                    {
                        if  ($Gap>0)
                        {
                            $sResult .= "<Value1>". $Gap ."</Value1>" ;
                            $sResult .= "<Value2>". "0"  ."</Value2>" ;
                        }
                        if  ($Gap<0)
                        {
                            $sResult .= "<Value1>". "0"  ."</Value1>" ;
                            $sResult .= "<Value2>". $Gap ."</Value2>" ;
                        }
                    }
                    $sResult .= "</Total5>" ;

                    $BeforeValue = $WorkDateNumPersons ;


                    // 찌꺼기 캐쉬를 다 지운다..
                    $sQuery = "Delete From tmp_daylytotal0              ".
                              " Where WorkDate    = '".$CuserDate."'    ".
                              "   And FilmCode    = '".$FilmTitle."'    " ;
                    mysql_query($sQuery,$connect) ;

                    // 캐쉬를 구성한다.
                    $sQuery = "Insert Into tmp_daylytotal0      ".
                              "Values                           ".
                              "(                                ".
                              "   '".$CuserDate."',             ".
                              "   '".$FilmTitle."',             ".
                              "   '".$sCurrTime."',             ".
                              "   '".$WorkDateNumPersons."'     ".
                              ")                                " ;
                    mysql_query($sQuery,$connect) ;
                }

                $CuserDate = date("Ymd",strtotime("+1 day",strtotime(substr($CuserDate,0,4)."-".substr($CuserDate,4,2)."-".substr($CuserDate,6,2).""))) ;
           }
           return $sResult ;
       }
       else
       {
           return "" ;
       }
   }

   //
   // 지역일별집계
   //
   function getLineData3($connect,$WorkDate,$FilmTitle)
   {
       $Now       = strtotime('now');

       $sCurrDay  = date("Ymd",$Now) ; // 현재일...
       $sCurrTime = date("YmdHis",$Now) ; // 현재시간...
       $sAgo10Sec = date("YmdHis",strtotime('-20 second',$Now)) ;   // 10초전 ..
       $sAgo1Hour = date("YmdHis",strtotime('-60 minutes',$Now)) ;  // 60분전 ..
       $sAgo2Hour = date("YmdHis",strtotime('-120 minutes',$Now)) ; // 120분전 ..


       $FilmTileOpen = substr($FilmTitle,0,6) ;
       $FilmTileFilm = substr($FilmTitle,6,2) ;

       if   ($FilmTileFilm == '00') // 분리된영화의통합코드
       {
            $FilmCond = " Open = '".$FilmTileOpen."' " ;
       }
       else
       {
            $FilmCond = "    Open = '".$FilmTileOpen."' ".
                        "And Film = '".$FilmTileFilm."' " ;
       }

       $sResult = "";




       $sQuery = "Select Max(SingoDate) As MaxWorkDate        ".
                 "  From ".$sSingoName."                      ".
                 " Where Open       = '".$FilmTileOpen."'     ".
                 "   And Film       = '".$FilmTileFilm."'     " ;
       $QRY_Singo = mysql_query($sQuery,$connect) ;
       if  ($OBJ_Singo = mysql_fetch_object($QRY_Singo))
       {
           $MinWorkDate = "20".$FilmTileOpen ;
           $MaxWorkDate = $OBJ_Singo->MaxWorkDate ;

           $CuserDate = $MinWorkDate ;

           $sResult = "" ;

           while (($CuserDate<=$MaxWorkDate) && ($CuserDate<=$WorkDate))
           {

                $sResult .= "<Total6>"."<Day>" ;
                if  ($CurMonth != substr($CuserDate,4,2))
                {
                    $CurMonth = substr($CuserDate,4,2) ;

                    $sResult .= $CurMonth."/" ;
                }

                $sResult .= substr($CuserDate,6,2) ;
                $sResult .= "</Day>" ;

                // 캐쉬에 데이터가 있는지 확인하고
                // 있으면 1분이전에 내용이면 그대로 가지고 오고
                // 아니면 일단지우고 ..
                // 신고자료에 해당일에 합계를 구한다..
                // 그런다음 캐쉬에 저장한다.


                if  (($CuserDate == $WorkDate) && ($CuserDate == $sCurrDay))
                {
                   $sQuery = "Select * From tmp_daylytotal4          ".
                             " Where WorkDate    = '".$CuserDate."'  ".
                             "   And FilmCode    = '".$FilmTitle."'  ".
                             "   And UpdateTime >= '".$sAgo10Sec."'  " ;
                }
                else
                {
                    $sQuery = "Select * From tmp_daylytotal4         ".
                              " Where WorkDate    = '".$CuserDate."' ".
                              "   And FilmCode    = '".$FilmTitle."' ".
                              "   And UpdateTime >= '".$sAgo1Hour."' " ;
                }
                $QRY_GT = mysql_query($sQuery,$connect) ;
                if  ( $OBJ_GT = mysql_fetch_object( $QRY_GT ) ) // 만일에 캐쉬가 존재한다면..
                {
                    $WorkDateNumPersons1 =  $OBJ_GT->Value1 ; // 그대로 간다..
                    $WorkDateNumPersons2 =  $OBJ_GT->Value2 ; //
                    $WorkDateNumPersons3 =  $OBJ_GT->Value3 ; //
                    $WorkDateNumPersons4 =  $OBJ_GT->Value4 ; //

                    $sResult .= "<Value1>". $WorkDateNumPersons1."</Value1>" ;
                    $sResult .= "<Value2>". $WorkDateNumPersons2."</Value2>" ;
                    $sResult .= "<Value3>". $WorkDateNumPersons3."</Value3>" ;
                    $sResult .= "<Value4>". $WorkDateNumPersons4."</Value4>"."</Total6>" ;
                }
                else
                {
                    $WorkDateNumPersons1 = 0 ;
                    $WorkDateNumPersons2 = 0 ;
                    $WorkDateNumPersons3 = 0 ;
                    $WorkDateNumPersons4 = 0 ;

                    // 서울
                    $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                              "  From ".$sSingoName."                    ".
                              " Where SingoDate  = '".$CuserDate."'      ".
                              "   And ".$FilmCond."                      ".
                              "   And Location   = '100'                 " ;
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ( $NumPersons_data = mysql_fetch_array($qrysingo2) )
                    {
                        $WorkDateNumPersons1 =  $NumPersons_data["SumNumPersons"] ;
                    }


                    // 경기
                    $AddedLocMD = " And " ;

                    $sQuery = "Select Location                     ".
                              "  From bas_filmsupplyzoneloc        ".
                              " Where Zone = '04'                  " ;
                    $qryzoneloc = mysql_query($sQuery,$connect) ;
                    while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
                    {
                         if  ($AddedLocMD == " And ")
                             $AddedLocMD .= "( Location = '".$zoneloc_data["Location"]."' "  ;
                         else
                             $AddedLocMD .= " or Location = '".$zoneloc_data["Location"]."' "  ;
                    }
                    $AddedLocMD .= ")" ;

                    $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                              "  From ".$sSingoName."                    ".
                              " Where SingoDate  = '".$CuserDate."'      ".
                              "   And ".$FilmCond."                      ".
                              $AddedLocMD."                              " ;
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ( $NumPersons_data = mysql_fetch_array($qrysingo2) )
                    {
                        $WorkDateNumPersons2 =  $NumPersons_data["SumNumPersons"] ;
                    }

                    // 부산
                    $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                              "  From ".$sSingoName."                    ".
                              " Where SingoDate  = '".$CuserDate."'      ".
                              "   And ".$FilmCond."                      ".
                              "   And (Location  = '200'                 ". // 부산
                              "    Or  Location  = '600'                 ". // 울산
                              "    Or  Location  = '207'                 ". // 김해
                              "    Or  Location  = '205'                 ". // 진주
                              "    Or  Location  = '208'                 ". // 거제
                              "    Or  Location  = '202'                 ". // 마산
                              "    Or  Location  = '211'                 ". // 사천
                              "    Or  Location  = '212'                 ". // 거창
                              "    Or  Location  = '213'                 ". // 양산
                              "    Or  Location  = '201')                " ;// 창원
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ( $NumPersons_data = mysql_fetch_array($qrysingo2) )
                    {
                        $WorkDateNumPersons3 =  $NumPersons_data["SumNumPersons"] ;
                    }

                    // 지방

                    $AddedLocMD = " and " ;

                    $sQuery = "Select Location From bas_filmsupplyzoneloc ".
                              " Where Zone = '04'                         " ;
                    $qryzoneloc = mysql_query($sQuery,$connect) ;
                    while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
                    {
                         if  ($AddedLocMD == " and ")
                             $AddedLocMD .= "( Location <> '".$zoneloc_data["Location"]."' "  ;
                         else
                             $AddedLocMD .= " and Location <> '".$zoneloc_data["Location"]."' "  ;
                    }
                    $AddedLocMD .= " and Location <> '100' "  ; // 서울
                    $AddedLocMD .= " and Location <> '200' "  ; // 부산
                    $AddedLocMD .= " and Location <> '600' "  ; // 울산
                    $AddedLocMD .= " and Location <> '207' "  ; // 김해
                    $AddedLocMD .= " and Location <> '205' "  ; // 진주
                    $AddedLocMD .= " and Location <> '208' "  ; // 거제
                    $AddedLocMD .= " and Location <> '202' "  ; // 마산
                    $AddedLocMD .= " and Location <> '211' "  ; // 사천
                    $AddedLocMD .= " and Location <> '212' "  ; // 거창
                    $AddedLocMD .= " and Location <> '213' "  ; // 양산
                    $AddedLocMD .= " and Location <> '201' "  ; // 창원
                    $AddedLocMD .= ")" ;

                    $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                              "  From ".$sSingoName."                    ".
                              " Where SingoDate  = '".$CuserDate."'      ".
                              "   And ".$FilmCond."                      ".
                              $AddedLocMD."                              " ;
                    $qrysingo2 = mysql_query($sQuery,$connect) ;
                    if  ( $NumPersons_data = mysql_fetch_array($qrysingo2) )
                    {
                        $WorkDateNumPersons4 =  $NumPersons_data["SumNumPersons"] ;
                    }


                    $sResult .= "<Value1>". $WorkDateNumPersons1 ."</Value1>" ;
                    $sResult .= "<Value2>". $WorkDateNumPersons2 ."</Value2>" ;
                    $sResult .= "<Value3>". $WorkDateNumPersons3 ."</Value3>" ;
                    $sResult .= "<Value4>". $WorkDateNumPersons4 ."</Value4>"."</Total6>" ;

                    // 찌꺼기 캐쉬를 다 지운다..
                    $sQuery = "Delete From tmp_daylytotal4              ".
                              " Where WorkDate    = '".$CuserDate."'     ".
                              "   And FilmCode    = '".$FilmTitle."'    " ;
                    mysql_query($sQuery,$connect) ;

                    // 캐쉬를 구성한다.
                    $sQuery = "Insert Into tmp_daylytotal4       ".
                              "Values                           ".
                              "(                                ".
                              "   '".$CuserDate."',              ".
                              "   '".$FilmTitle."',             ".
                              "   '".$sCurrTime."',             ".
                              "   '".$WorkDateNumPersons1."',   ".
                              "   '".$WorkDateNumPersons2."',   ".
                              "   '".$WorkDateNumPersons3."',   ".
                              "   '".$WorkDateNumPersons4."'    ".
                              ")                                " ;
                    mysql_query($sQuery,$connect) ;
                }

                $CuserDate = date("Ymd",strtotime("+1 day",strtotime(substr($CuserDate,0,4)."-".substr($CuserDate,4,2)."-".substr($CuserDate,6,2).""))) ;
           }
           return $sResult ;
       }
       else
       {
           return "" ;
       }
   }

   //
   // 시간별집계
   //
   function getColumData2($connect,$WorkDate,$FilmTitle)
   {
       $AgoDate = date("Ymd",strtotime("-1 day",strtotime(substr($WorkDate,0,4)."-".substr($WorkDate,4,2)."-".substr($WorkDate,6,2).""))) ;

       $FilmTileOpen = substr($FilmTitle,0,6) ;
       $FilmTileFilm = substr($FilmTitle,6,2) ;

       $sResult = "" ;

       $sQuery = "Select distinct(WorkTime)           ".
                 "  From tmp_timelytotal0             ".
                 " Where (WorkDate = '".$WorkDate."'  ".
                 "    Or  WorkDate = '".$AgoDate."')  ".
                 "   And FilmCode = '".$FilmTitle."'  ".
                 " Order By WorkTime                  " ;
       $QRY_WorkTimes = mysql_query($sQuery,$connect) ;
       while ($OBJ_WorkTimes = mysql_fetch_object($QRY_WorkTimes))
       {
            $WorkTime = $OBJ_WorkTimes->WorkTime ;

            $sQuery = "Select Value0 From tmp_timelytotal0".
                      " Where WorkDate = '".$WorkDate."'  ".
                      "   And WorkTime = '".$WorkTime."'  ".
                      "   And FilmCode = '".$FilmTitle."' " ;
            $QRY_Timelytotal = mysql_query($sQuery,$connect) ;
            if ($OBJ_Timelytotal = mysql_fetch_object($QRY_Timelytotal))
            {
                $WorkDateVale = $OBJ_Timelytotal->Value0 ;
            }
            else
            {
                $WorkDateVale = 0 ;
            }
            $Gap1 = $WorkDateVale - $Before1 ;
            $Before1 = $WorkDateVale ;

            $sQuery = "Select Value0 From tmp_timelytotal0".
                      " Where WorkDate = '".$AgoDate."'   ".
                      "   And WorkTime = '".$WorkTime."'  ".
                      "   And FilmCode = '".$FilmTitle."' " ;
            $QRY_Timelytotal = mysql_query($sQuery,$connect) ;
            if ($OBJ_Timelytotal = mysql_fetch_object($QRY_Timelytotal))
            {
                $AgoDateVale = $OBJ_Timelytotal->Value0 ;
            }
            else
            {
                $AgoDateVale = 0 ;
            }
            $Gap2 = $AgoDateVale - $Before2 ;
            $Before2 = $AgoDateVale ;

            if  ($Gap1<0) { $Gap1 = 0 ; }
            if  ($Gap2<0) { $Gap2 = 0 ; }

            if  ($WorkTime>24) $sWorkTime = $WorkTime - 24 ;
            else               $sWorkTime = $WorkTime  ;
            $sResult .= "<Total7>" ;
            $sResult .= "<Time>". $sWorkTime ."</Time>" ;
            $sResult .= "<Value1>". $Gap1 ."</Value1>" ;
            $sResult .= "<Value2>". $Gap2 ."</Value2>" ;
            $sResult .= "</Total7>" ;
       }

       return $sResult ;
   }

   //
   // 전주대비
   //
   function getColumData3($connect,$WorkDate,$FilmTitle)
   {
       $Now       = strtotime('now');
       $sCurrTime = date("YmdHis",$Now) ; // 현재시간...

       $FilmTileOpen = substr($FilmTitle,0,6) ;
       $FilmTileFilm = substr($FilmTitle,6,2) ;

       $sAgo10Sec = date("YmdHis",strtotime('-20 second',$Now)) ;   // 10초전 ..

       // 전주
       $AgoDate = date("Ymd",strtotime("-7 day",strtotime(substr($WorkDate,0,4)."-".substr($WorkDate,4,2)."-".substr($WorkDate,6,2).""))) ;

       $strWorkDate = substr($WorkDate,0,4)."-".substr($WorkDate,4,2)."-".substr($WorkDate,6,2);
       $strAgoDate  = substr($AgoDate,0,4)."-".substr($AgoDate,4,2)."-".substr($AgoDate,6,2);

       $FilmTileOpen = substr($FilmTitle,0,6) ;
       $FilmTileFilm = substr($FilmTitle,6,2) ;

       $sResult = "<Total8>" ;
       $sResult .= "<Time>". $strAgoDate.":". $strWorkDate."</Time>" ;

       // tmp_globaltotal 캐쉬..
       $sQuery = "Select * From tmp_alltotal               ".
                 " Where FilmCode    = '".$FilmTitle."'    ".
                 "   And UpdateTime >= '".$sAgo10Sec."'    " ;
       $QRY_GT = mysql_query($sQuery,$connect) ;
       if  ( $OBJ_GT = mysql_fetch_object( $QRY_GT ) ) // 만일에 캐쉬가 존재한다면..
       {
           $sResult .=  ("<Value1>".$OBJ_GT->Value1."</Value1>") ; //
           $sResult .=  ("<Value2>".$OBJ_GT->Value2."</Value2>") ; //
       }
       else
       {
           $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                     "  From ".$sSingoName."                    ".
                     " Where SingoDate = '".$AgoDate."'         ".
                     "   And Open = '".$FilmTileOpen."'         ".
                     "   And Film = '".$FilmTileFilm."'         " ;
           $QRY_Timelytotal = mysql_query($sQuery,$connect) ;
           if ($OBJ_Timelytotal = mysql_fetch_object($QRY_Timelytotal))
           {
               $AgoDateVale = $OBJ_Timelytotal->SumNumPersons ;
           }
           if  ($AgoDateVale==null)
           {
               $AgoDateVale = "0" ;
           }

           $WorkDateVale = 0 ;

           $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                     "  From ".$sSingoName."                    ".
                     " Where SingoDate = '".$WorkDate."'        ".
                     "   And Open = '".$FilmTileOpen."'         ".
                     "   And Film = '".$FilmTileFilm."'         " ;
           $QRY_Timelytotal = mysql_query($sQuery,$connect) ;
           if ($OBJ_Timelytotal = mysql_fetch_object($QRY_Timelytotal))
           {
               $WorkDateVale = $OBJ_Timelytotal->SumNumPersons ;
           }

           // 찌꺼기 캐쉬를 다 지운다..
           $sQuery = "Delete From tmp_alltotal                 ".
                     " Where FilmCode    = '".$FilmTitle."'    " ;
           mysql_query($sQuery,$connect) ;

           // 캐쉬를 구성한다.
           $sQuery = "Insert Into tmp_alltotal    ".
                     "Values                      ".
                     "(                           ".
                     "   '".$FilmTitle."',        ".
                     "   '".$sCurrTime."',        ".
                     "   '".$AgoDateVale."',      ".
                     "   '".$WorkDateVale."'      ".
                     ")                           " ;
           mysql_query($sQuery,$connect) ;

           $sResult .= ("<Value1>".$AgoDateVale."</Value1>") ;
           $sResult .= ("<Value2>".$WorkDateVale."</Value2>") ;
       }



       $sResult .= "</Total8>" ;
       return $sResult ;
   }


   //
   // 상대영화
   //
   function getBarChart1($connect,$WorkDate)
   {
       $Now       = strtotime('now');
       $sCurrTime = date("YmdHis",$Now) ; // 현재시간...
       $sAgo10Sec = date("YmdHis",strtotime('-20 second',$Now)) ;   // 10초전 ..

       $strWorkDate = substr($WorkDate,0,4)."-".substr($WorkDate,4,2)."-".substr($WorkDate,6,2);
       $strAgoDate  = substr($AgoDate,0,4)."-".substr($AgoDate,4,2)."-".substr($AgoDate,6,2);

       $sResult = "" ;

       $sQuery = "SELECT sd.SangFilm, sf.Name,                             ".
                 "       Sum( sd.Score ) AS SumScore                       ".
                 "  FROM `wrk_sangdae` AS sd, bas_sangfilmtitle AS sf      ".
                 " WHERE sd.WorkDate = '". $WorkDate."'                    ".
                 "   AND sf.Code = sd.SangFilm                             ".
                 " GROUP BY sd.SangFilm, sf.Name                           ".
                 " ORDER BY SumScore Desc                                  ".
                 " LIMIT 0 , 10                                            " ;
//$sResult = $sQuery ;
       $QrySangdae = mysql_query($sQuery,$connect) ;
       while ( $ObjSangdae = mysql_fetch_object( $QrySangdae ) )
       {
            $sResult .= "<Total9>" ;
            $sResult .= "<FilmName>". mb_convert_encoding($ObjSangdae->Name,"UTF-8","EUC-KR")."</FilmName>" ;
            $sResult .= "<Value1>".$ObjSangdae->SumScore."</Value1>" ;
            $sResult .= "<Value2>0</Value2>" ;
            $sResult .= "</Total9>" ;
       }


       return $sResult ;
   }

   mysql_close($connect) ;
?>