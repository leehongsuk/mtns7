<?

   $FilmTileOpen = substr($FilmTile,0,6) ;
   $FilmTileFilm = substr($FilmTile,6,2) ;

   if   ((!$FilmTile) && (!$ZoneCode) && (!$LocationCode)) //
   {
       //echo "없음" ;
   }
   else
   {
       $sQuery = "Select * From bas_filmsupply         ".
                 " Where UserId = '".$logged_UserId."' " ;
       $query1 = mysql_query($sQuery,$connect) ;
       $filmsupply_data = mysql_fetch_array($query1) ;
       if ($filmsupply_data)
       {
           $filmsupplyCode = $filmsupply_data["Code"] ;
           $filmsupplyName = $filmsupply_data["Name"] ;
       }

       if   ($FilmTileFilm == '00') // 분리된영화의통합코드
       {
            $FilmCond = " Open = '".$FilmTileOpen."' " ;
       }
       else
       {
            $FilmCond = "    Open = '".$FilmTileOpen."' ".
                        "And Film = '".$FilmTileFilm."' " ;
       }

       $sSingoName     = get_singotable($FilmTileOpen,$FilmTileFilm,$connect) ;  // 신고 테이블 이름..
       $sShowroomorder = get_showroomorder($FilmOpen,$FilmCode,$connect) ;

       // 누계금액 부터 구한다. (신고일자, 배급사) // 영화 가 빠져있음 확인요
       $qrysingo3 = mysql_query("Select Sum(TotAmount) As SumTotAmount     ".
                                "  From ".$sSingoName."                    ".
                                " Where SingoDate  <= '".$WorkDate."'      ".
                                "   And ".$FilmCond."                      ".
                                "   And FilmSupply = '".$filmsupplyCode."' ".$AddCond,$connect) ;
       $SumTotAmount_data = mysql_fetch_array($qrysingo3) ;

       if  ($SumTotAmount_data)
       {
           $SumTotAmount = $SumTotAmount_data["SumTotAmount"] ;
       }
       else
       {
           $SumTotAmount = 0 ;
       }
















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
               if   ($FilmTileFilm == '00') // 분리된영화의통합코드
               {
                    $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' " ;
                    $OrderCont = " Order By Showroom.Discript,                    ".
                                 "          Singo.Theather,                       ".
                                 "          Singo.Room                            " ;
                    $FilmsupplyCont = "" ;
               }
               else
               {
                    $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' ".
                                  " And Singo.Film = '".$FilmTileFilm."' " ;
                    $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                 "          Showroom.Discript,                    ".
                                 "          Singo.Theather,                       ".
                                 "          Singo.Room                            " ;
                    $FilmsupplyCont = "" ;

                    $FilmsupplyCont = "   And Singo.Filmsupply = ShowroomOrder.FilmSupply " ;
               }
           }

           $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                    "       Singo.Theather,                          ".
                                    "       Singo.Room,                              ".
                                    "       Singo.Open,                              ".
                                    "       Singo.Film,                              ".
                                    "       Singo.Silmooja,                          ".
                                    "       Showroom.Discript,                       ".
                                    "       Showroom.Location,                       ".
                                    "       Location.Name As LocationName,           ".
                                    "       Showroom.Seat As ShowRoomSeat,           ".
                                    "       FilmTitle.Name As FilmTitleName,         ".
                                    "       Silmooja.Name	As SilmoojaName,           ".
                                    "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                                    "       Count(distinct ShowDgree) As CntDgree,   ".
                                    "       Count(distinct UnitPrice) As cntUnitPrice".
                                    "  From ".$sSingoName."   As Singo,              ".
                                    "       bas_showroom      As Showroom,           ".
                                    "       ".$sShowroomorder." As ShowroomOrder,    ".
                                    "       bas_filmtitle     As FilmTitle,          ".
                                    "       bas_silmooja      As Silmooja,           ".
                                    "       bas_location      As Location            ".
                                    " Where Singo.Singodate  = '".$WorkDate."'       ".
                                    "   And Singo.Silmooja   = Silmooja.Code         ".
                                    "   And Singo.Theather   = Showroom.Theather     ".
                                    "   And Singo.Room       = Showroom.Room         ".
                                    "   And Singo.Theather   = ShowroomOrder.Theather".
                                    "   And Singo.Room       = ShowroomOrder.Room    ".
                                    "   And Singo.Location   = Location.Code         ".
                                    "   And Singo.Open       = FilmTitle.Open        ".
                                    "   And Singo.Film       = FilmTitle.Code	       ".
                                    $FilmsupplyCont                                   .
                                    $AddedCont                                        .
                                    " Group By Singo.Theather,                       ".
                                    "          Singo.Room,                           ".
                                    "          Singo.Open,                           ".
                                    "          Singo.Film,                           ".
                                    "          Singo.Silmooja ,                      ".
                                    "          Showroom.Discript                     ".
                                    $OrderCont                                        ,$connect) ;

           include "wrk_filmsupply_Link_DnACvs1.php";



           //-----------
           // 경기출력
           //-----------
           $zoneName  = "경기" ;
           $qryzoneloc = mysql_query("select Location from bas_filmsupplyzoneloc ".
                                     " Where Code = '".$filmsupplyCode."'         ".
                                     "   And (  Zone = '04')                      ",$connect) ;

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
               if   ($FilmTileFilm == '00') // 분리된영화의통합코드
               {
                    $AddedCont = " And Singo.Open = '".$FilmTileOpen."' " ;
                    $OrderCont = " Order By Showroom.Discript,                    ".
                                 "          Singo.Theather,                       ".
                                 "          Singo.Room                            " ;
                    $FilmsupplyCont = "" ;
               }
               else
               {
                    $AddedCont = " And Singo.Open = '".$FilmTileOpen."' ".
                                 " And Singo.Film = '".$FilmTileFilm."' " ;
                    $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                 "          Showroom.Discript,                    ".
                                 "          Singo.Theather,                       ".
                                 "          Singo.Room                            " ;
                    $FilmsupplyCont = "   And Singo.Filmsupply = ShowroomOrder.FilmSupply " ;
               }
           }
           $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                    "       Singo.Theather,                          ".
                                    "       Singo.Room,                              ".
                                    "       Singo.Open,                              ".
                                    "       Singo.Film,                              ".
                                    "       Singo.Silmooja,                          ".
                                    "       Showroom.Discript,                       ".
                                    "       Showroom.Location,                       ".
                                    "       Location.Name As LocationName,           ".
                                    "       Showroom.Seat As ShowRoomSeat,           ".
                                    "       FilmTitle.Name As FilmTitleName,         ".
                                    "       Silmooja.Name	As SilmoojaName,           ".
                                    "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                                    "       Count(distinct ShowDgree) As CntDgree,   ".
                                    "       Count(distinct UnitPrice) As cntUnitPrice".
                                    "  From ".$sSingoName."   As Singo,              ".
                                    "       bas_showroom      As Showroom,           ".
                                    "       ".$sShowroomorder." As ShowroomOrder,    ".
                                    "       bas_filmtitle     As FilmTitle,          ".
                                    "       bas_silmooja      As Silmooja,           ".
                                    "       bas_location      As Location            ".
                                    " Where Singo.Singodate  = '".$WorkDate."'       ".
                                    "   And Singo.Silmooja   = Silmooja.Code         ".
                                    "   And Singo.Theather   = Showroom.Theather     ".
                                    "   And Singo.Room       = Showroom.Room         ".
                                    "   And Singo.Theather   = ShowroomOrder.Theather".
                                    "   And Singo.Room       = ShowroomOrder.Room    ".
                                    "   And Singo.Location   = Location.Code         ".
                                    "   And Singo.Open       = FilmTitle.Open        ".
                                    "   And Singo.Film       = FilmTitle.Code	       ".
                                    $FilmsupplyCont                                   .
                                    $AddedLoc                                         .
                                    $AddedCont                                        .
                                    " Group By Singo.Theather,                       ".
                                    "          Singo.Room,                           ".
                                    "          Singo.Open,                           ".
                                    "          Singo.Film,                           ".
                                    "          Singo.Silmooja ,                      ".
                                    "          Showroom.Discript                     ".
                                    $OrderCont                                        ,$connect) ;

           include "wrk_filmsupply_Link_DnACvs1.php";




           // (부산+울산+김해+창원)이 부산으로 묶인다.

           //-----------
           // 부산 출력
           //-----------

           $zoneName  = "부산" ;
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


           if  (($FilmTile != "") && ($FilmTile != "00000000")) // 특정영화만 선택하여 보고자 할 경우..
           {
               if   ($FilmTileFilm == '00') // 분리된영화의통합코드
               {
                    $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' " ;
                    $OrderCont = " Order By Showroom.Discript,                    ".
                                 "          Singo.Theather,                       ".
                                 "          Singo.Room                            " ;
                    $FilmsupplyCont = "" ;
               }
               else
               {
                    $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' ".
                                  " And Singo.Film = '".$FilmTileFilm."' " ;
                    $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                 "          Showroom.Discript,                    ".
                                 "          Singo.Theather,                       ".
                                 "          Singo.Room                            " ;
                    $FilmsupplyCont = "   And Singo.Filmsupply = ShowroomOrder.FilmSupply " ;
               }
           }

           $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                    "       Singo.Theather,                          ".
                                    "       Singo.Room,                              ".
                                    "       Singo.Open,                              ".
                                    "       Singo.Film,                              ".
                                    "       Singo.Silmooja,                          ".
                                    "       Showroom.Discript,                       ".
                                    "       Showroom.Location,                       ".
                                    "       Location.Name As LocationName,           ".
                                    "       Showroom.Seat As ShowRoomSeat,           ".
                                    "       FilmTitle.Name As FilmTitleName,         ".
                                    "       Silmooja.Name	As SilmoojaName,           ".
                                    "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                                    "       Count(distinct ShowDgree) As CntDgree,   ".
                                    "       Count(distinct UnitPrice) As cntUnitPrice".
                                    "  From ".$sSingoName."   As Singo,              ".
                                    "       bas_showroom      As Showroom,           ".
                                    "       ".$sShowroomorder." As ShowroomOrder,    ".
                                    "       bas_filmtitle     As FilmTitle,          ".
                                    "       bas_silmooja      As Silmooja,           ".
                                    "       bas_location      As Location            ".
                                    " Where Singo.Singodate  = '".$WorkDate."'       ".
                                    "   And Singo.Silmooja   = Silmooja.Code         ".
                                    "   And Singo.Theather   = Showroom.Theather     ".
                                    "   And Singo.Room       = Showroom.Room         ".
                                    "   And Singo.Theather   = ShowroomOrder.Theather".
                                    "   And Singo.Room       = ShowroomOrder.Room    ".
                                    "   And Singo.Location   = Location.Code         ".
                                    "   And Singo.Open       = FilmTitle.Open        ".
                                    "   And Singo.Film       = FilmTitle.Code	       ".
                                    $FilmsupplyCont                                   .
                                    $AddedCont                                        .
                                    " Group By Singo.Theather,                       ".
                                    "          Singo.Room,                           ".
                                    "          Singo.Open,                           ".
                                    "          Singo.Film,                           ".
                                    "          Singo.Silmooja ,                      ".
                                    "          Showroom.Discript                     ".
                                    $OrderCont                                        ,$connect) ;

           include "wrk_filmsupply_Link_DnACvs1.php";


           //-----------
           // 경강 출력
           //-----------
           $zoneName  = "경강" ;
           $query1 = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                 " Where Code  = '".$filmsupplyCode."'  ".
                                 "   And Zone  = '10'                   ",$connect) ;

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
                   if   ($FilmTileFilm == '00') // 분리된영화의통합코드
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' " ;
                        $OrderCont = " Order By Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                        $FilmsupplyCont = "" ;
                   }
                   else
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' ".
                                      " And Singo.Film = '".$FilmTileFilm."' " ;
                        $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                     "          Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                        $FilmsupplyCont = "   And Singo.Filmsupply = ShowroomOrder.FilmSupply " ;
                   }
               }

               $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                        "       Singo.Theather,                          ".
                                        "       Singo.Room,                              ".
                                        "       Singo.Open,                              ".
                                        "       Singo.Film,                              ".
                                        "       Singo.Silmooja,                          ".
                                        "       Showroom.Discript,                       ".
                                        "       Showroom.Location,                       ".
                                        "       Location.Name As LocationName,           ".
                                        "       Showroom.Seat As ShowRoomSeat,           ".
                                        "       FilmTitle.Name As FilmTitleName,         ".
                                        "       Silmooja.Name	As SilmoojaName,           ".
                                        "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                                        "       Count(distinct ShowDgree) As CntDgree,   ".
                                        "       Count(distinct UnitPrice) As cntUnitPrice".
                                        "  From ".$sSingoName."   As Singo,              ".
                                        "       bas_showroom      As Showroom,           ".
                                        "       ".$sShowroomorder." As ShowroomOrder,    ".
                                        "       bas_filmtitle     As FilmTitle,          ".
                                        "       bas_silmooja      As Silmooja,           ".
                                        "       bas_location      As Location            ".
                                        " Where Singo.Singodate  = '".$WorkDate."'       ".
                                        "   And Singo.Silmooja   = Silmooja.Code         ".
                                        "   And Singo.Theather   = Showroom.Theather     ".
                                        "   And Singo.Room       = Showroom.Room         ".
                                        "   And Singo.Theather   = ShowroomOrder.Theather".
                                        "   And Singo.Room       = ShowroomOrder.Room    ".
                                        "   And Singo.Location   = Location.Code         ".
                                        "   And Singo.Open       = FilmTitle.Open        ".
                                        "   And Singo.Film       = FilmTitle.Code	       ".
                                        $FilmsupplyCont                                   .
                                        $AddedCont                                        .
                                        " Group By Singo.Theather,                       ".
                                        "          Singo.Room,                           ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Singo.Silmooja ,                      ".
                                        "          Showroom.Discript                     ".
                                        $OrderCont                                        ,$connect) ;

               include "wrk_filmsupply_Link_DnACvs1.php";
           }

           //-----------
           // 충청 출력
           //-----------
           $zoneName  = "충청" ;
           $query1 = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                 " Where Code  = '".$filmsupplyCode."'  ".
                                 "   And Zone  = '35'                   ",$connect) ;

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
                   if   ($FilmTileFilm == '00') // 분리된영화의통합코드
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' " ;
                        $OrderCont = " Order By Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                        $FilmsupplyCont = "" ;
                   }
                   else
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' ".
                                      " And Singo.Film = '".$FilmTileFilm."' " ;
                        $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                     "          Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                        $FilmsupplyCont = "   And Singo.Filmsupply = ShowroomOrder.FilmSupply " ;
                   }
               }

               $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                        "       Singo.Theather,                          ".
                                        "       Singo.Room,                              ".
                                        "       Singo.Open,                              ".
                                        "       Singo.Film,                              ".
                                        "       Singo.Silmooja,                          ".
                                        "       Showroom.Discript,                       ".
                                        "       Showroom.Location,                       ".
                                        "       Location.Name As LocationName,           ".
                                        "       Showroom.Seat As ShowRoomSeat,           ".
                                        "       FilmTitle.Name As FilmTitleName,         ".
                                        "       Silmooja.Name	As SilmoojaName,           ".
                                        "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                                        "       Count(distinct ShowDgree) As CntDgree,   ".
                                        "       Count(distinct UnitPrice) As cntUnitPrice".
                                        "  From ".$sSingoName."   As Singo,              ".
                                        "       bas_showroom      As Showroom,           ".
                                        "       ".$sShowroomorder." As ShowroomOrder,    ".
                                        "       bas_filmtitle     As FilmTitle,          ".
                                        "       bas_silmooja      As Silmooja,           ".
                                        "       bas_location      As Location            ".
                                        " Where Singo.Singodate  = '".$WorkDate."'       ".
                                        "   And Singo.Silmooja   = Silmooja.Code         ".
                                        "   And Singo.Theather   = Showroom.Theather     ".
                                        "   And Singo.Room       = Showroom.Room         ".
                                        "   And Singo.Theather   = ShowroomOrder.Theather".
                                        "   And Singo.Room       = ShowroomOrder.Room    ".
                                        "   And Singo.Location   = Location.Code         ".
                                        "   And Singo.Open       = FilmTitle.Open        ".
                                        "   And Singo.Film       = FilmTitle.Code	       ".
                                        $FilmsupplyCont                                   .
                                        $AddedCont                                        .
                                        " Group By Singo.Theather,                       ".
                                        "          Singo.Room,                           ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Singo.Silmooja ,                      ".
                                        "          Showroom.Discript                     ".
                                        $OrderCont                                        ,$connect) ;


               include "wrk_filmsupply_Link_DnACvs1.php";
           }
           //-----------
           // 경남 출력
           //-----------
           $zoneName  = "경남" ;
           $query1 = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                 " Where Code  = '".$filmsupplyCode."'  ".
                                 "   And Zone  = '20'                   ",$connect) ;

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
               //울산,창원,김해를 뺀 경남이 경남

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
                   if   ($FilmTileFilm == '00') // 분리된영화의통합코드
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' " ;
                        $OrderCont = " Order By Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                        $FilmsupplyCont = "" ;
                   }
                   else
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' ".
                                      " And Singo.Film = '".$FilmTileFilm."' " ;
                        $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                     "          Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                        $FilmsupplyCont = "   And Singo.Filmsupply = ShowroomOrder.FilmSupply " ;
                   }
               }

               $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                        "       Singo.Theather,                          ".
                                        "       Singo.Room,                              ".
                                        "       Singo.Open,                              ".
                                        "       Singo.Film,                              ".
                                        "       Singo.Silmooja,                          ".
                                        "       Showroom.Discript,                       ".
                                        "       Showroom.Location,                       ".
                                        "       Location.Name As LocationName,           ".
                                        "       Showroom.Seat As ShowRoomSeat,           ".
                                        "       FilmTitle.Name As FilmTitleName,         ".
                                        "       Silmooja.Name	As SilmoojaName,           ".
                                        "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                                        "       Count(distinct ShowDgree) As CntDgree,   ".
                                        "       Count(distinct UnitPrice) As cntUnitPrice".
                                        "  From ".$sSingoName."   As Singo,              ".
                                        "       bas_showroom      As Showroom,           ".
                                        "       ".$sShowroomorder." As ShowroomOrder,    ".
                                        "       bas_filmtitle     As FilmTitle,          ".
                                        "       bas_silmooja      As Silmooja,           ".
                                        "       bas_location      As Location            ".
                                        " Where Singo.Singodate  = '".$WorkDate."'       ".
                                        "   And Singo.Silmooja   = Silmooja.Code         ".
                                        "   And Singo.Theather   = Showroom.Theather     ".
                                        "   And Singo.Room       = Showroom.Room         ".
                                        "   And Singo.Theather   = ShowroomOrder.Theather".
                                        "   And Singo.Room       = ShowroomOrder.Room    ".
                                        "   And Singo.Location   = Location.Code         ".
                                        "   And Singo.Open       = FilmTitle.Open        ".
                                        "   And Singo.Film       = FilmTitle.Code	       ".
                                        $FilmsupplyCont                                   .
                                        $AddedCont                                        .
                                        " Group By Singo.Theather,                       ".
                                        "          Singo.Room,                           ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Singo.Silmooja ,                      ".
                                        "          Showroom.Discript                     ".
                                        $OrderCont                                        ,$connect) ;

               include "wrk_filmsupply_Link_DnACvs1.php";
           }
           //-----------
           // 경북 출력
           //-----------
           $zoneName  = "경북" ;
           $query1 = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                 " Where Code  = '".$filmsupplyCode."'  ".
                                 "   And Zone  = '21'                   ",$connect) ;

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
                   if   ($FilmTileFilm == '00') // 분리된영화의통합코드
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' " ;
                        $OrderCont = " Order By Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                        $FilmsupplyCont = "" ;
                   }
                   else
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' ".
                                      " And Singo.Film = '".$FilmTileFilm."' " ;
                        $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                     "          Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                        $FilmsupplyCont = "   And Singo.Filmsupply = ShowroomOrder.FilmSupply " ;
                   }
               }

               $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                        "       Singo.Theather,                          ".
                                        "       Singo.Room,                              ".
                                        "       Singo.Open,                              ".
                                        "       Singo.Film,                              ".
                                        "       Singo.Silmooja,                          ".
                                        "       Showroom.Discript,                       ".
                                        "       Showroom.Location,                       ".
                                        "       Location.Name As LocationName,           ".
                                        "       Showroom.Seat As ShowRoomSeat,           ".
                                        "       FilmTitle.Name As FilmTitleName,         ".
                                        "       Silmooja.Name	As SilmoojaName,           ".
                                        "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                                        "       Count(distinct ShowDgree) As CntDgree,   ".
                                        "       Count(distinct UnitPrice) As cntUnitPrice".
                                        "  From ".$sSingoName."   As Singo,              ".
                                        "       bas_showroom      As Showroom,           ".
                                        "       ".$sShowroomorder." As ShowroomOrder,    ".
                                        "       bas_filmtitle     As FilmTitle,          ".
                                        "       bas_silmooja      As Silmooja,           ".
                                        "       bas_location      As Location            ".
                                        " Where Singo.Singodate  = '".$WorkDate."'       ".
                                        "   And Singo.Silmooja   = Silmooja.Code         ".
                                        "   And Singo.Theather   = Showroom.Theather     ".
                                        "   And Singo.Room       = Showroom.Room         ".
                                        "   And Singo.Theather   = ShowroomOrder.Theather".
                                        "   And Singo.Room       = ShowroomOrder.Room    ".
                                        "   And Singo.Location   = Location.Code         ".
                                        "   And Singo.Open       = FilmTitle.Open        ".
                                        "   And Singo.Film       = FilmTitle.Code	       ".
                                        $FilmsupplyCont                                   .
                                        $AddedCont                                        .
                                        " Group By Singo.Theather,                       ".
                                        "          Singo.Room,                           ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Singo.Silmooja ,                      ".
                                        "          Showroom.Discript                     ".
                                        $OrderCont                                        ,$connect) ;

               include "wrk_filmsupply_Link_DnACvs1.php";
           }
           //-----------
           // 호남 출력
           //-----------
           $zoneName  = "호남" ;
           $query1 = mysql_query("Select * From bas_filmsupplyzoneloc   ".
                                 " Where Code  = '".$filmsupplyCode."'  ".
                                 "   And Zone  = '50'                   ",$connect) ;

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
                   if   ($FilmTileFilm == '00') // 분리된영화의통합코드
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' " ;
                        $OrderCont = " Order By Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                        $FilmsupplyCont = "" ;
                   }
                   else
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' ".
                                      " And Singo.Film = '".$FilmTileFilm."' " ;
                        $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                     "          Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                        $FilmsupplyCont = "   And Singo.Filmsupply = ShowroomOrder.FilmSupply " ;
                   }
               }

               $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                        "       Singo.Theather,                          ".
                                        "       Singo.Room,                              ".
                                        "       Singo.Open,                              ".
                                        "       Singo.Film,                              ".
                                        "       Singo.Silmooja,                          ".
                                        "       Showroom.Discript,                       ".
                                        "       Showroom.Location,                       ".
                                        "       Location.Name As LocationName,           ".
                                        "       Showroom.Seat As ShowRoomSeat,           ".
                                        "       FilmTitle.Name As FilmTitleName,         ".
                                        "       Silmooja.Name	As SilmoojaName,           ".
                                        "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                                        "       Count(distinct ShowDgree) As CntDgree,   ".
                                        "       Count(distinct UnitPrice) As cntUnitPrice".
                                        "  From ".$sSingoName."   As Singo,              ".
                                        "       bas_showroom      As Showroom,           ".
                                        "       ".$sShowroomorder." As ShowroomOrder,    ".
                                        "       bas_filmtitle     As FilmTitle,          ".
                                        "       bas_silmooja      As Silmooja,           ".
                                        "       bas_location      As Location            ".
                                        " Where Singo.Singodate  = '".$WorkDate."'       ".
                                        "   And Singo.Silmooja   = Silmooja.Code         ".
                                        "   And Singo.Theather   = Showroom.Theather     ".
                                        "   And Singo.Room       = Showroom.Room         ".
                                        "   And Singo.Theather   = ShowroomOrder.Theather".
                                        "   And Singo.Room       = ShowroomOrder.Room    ".
                                        "   And Singo.Location   = Location.Code         ".
                                        "   And Singo.Open       = FilmTitle.Open        ".
                                        "   And Singo.Film       = FilmTitle.Code	       ".
                                        $FilmsupplyCont                                   .
                                        $AddedCont                                        .
                                        " Group By Singo.Theather,                       ".
                                        "          Singo.Room,                           ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Singo.Silmooja ,                      ".
                                        "          Showroom.Discript                     ".
                                        $OrderCont                                        ,$connect) ;

               include "wrk_filmsupply_Link_DnACvs1.php";
           }
           /***********/
           /*
           if  (($filmsupplyCode=="20001") || ($filmsupplyCode=="20002")) // DBO,청진영
           {
               $qryzone = mysql_query("Select * From bas_filmsupplyzoneloc ".
                                      " Where Code = '".$filmsupplyCode."'  ".
                                      "   And (  Zone = '35'                ".
                                      "      or Zone = '20'                ".
                                      "      or Zone = '21'                ".
                                      "      or Zone = '50')               ",$connect) ;

               $zone_data = mysql_fetch_array($qryzone) ;

               if  ($zone_data)
               {
                    $qryzone = mysql_query("Select * From bas_zone  ".
                                           "  where  Code = '35'    ".
                                           "      or Code = '20'    ".
                                           "      or Code = '21'    ".
                                           "      or Code = '50'    ",$connect) ;


                    $zone_data = mysql_fetch_array($qryzone) ;
                    if  ($zone_data)
                    {
                        $zoneName = $zone_data["Name"] ;
                    }

                    $qryzoneloc = mysql_query("select Location from bas_filmsupplyzoneloc ".
                                              " Where Code = '".$filmsupplyCode."'         ".
                                              "   And (  Zone = '35'                       ".
                                              "      or Zone = '20'                       ".
                                              "      or Zone = '21'                       ".
                                              "      or Zone = '50')                      ",$connect) ;


                    $AddedLoc = " and " ;

                    while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
                    {
                         if  ($AddedLoc == " and ")
                             $AddedLoc .= "( singo.Location = '".$zoneloc_data["Location"]."' "  ;
                         else
                             $AddedLoc .= " or singo.Location = '".$zoneloc_data["Location"]."' "  ;
                    }
                    $AddedLoc .= " or singo.Location = '200' "  ;
                    $AddedLoc .= ")" ;


                    if  (($FilmTile != "") && ($FilmTile != "00000000")) // 특정영화만 선택하여 보고자 할 경우..
                    {
                        if   ($FilmTileFilm == '00') // 분리된영화의통합코드
                        {
                             $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' " ;
                             $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                          "          Showroom.Discript,                    ".
                                          "          Singo.Theather,                       ".
                                          "          Singo.Room                            " ;
                             $FilmsupplyCont = "" ;
                        }
                        else
                        {
                             $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' ".
                                           " And Singo.Film = '".$FilmTileFilm."' " ;
                             $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                          "          Showroom.Discript,                    ".
                                          "          Singo.Theather,                       ".
                                          "          Singo.Room                            " ;
                        $FilmsupplyCont = "   And Singo.Filmsupply = ShowroomOrder.FilmSupply " ;
                        }
                    }

                    $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                        "       Singo.Theather,                          ".
                                        "       Singo.Room,                              ".
                                        "       Singo.Open,                              ".
                                        "       Singo.Film,                              ".
                                        "       Singo.Silmooja,                          ".
                                        "       Showroom.Discript,                       ".
                                        "       Showroom.Location,                       ".
                                        "       Location.Name As LocationName,           ".
                                        "       Showroom.Seat As ShowRoomSeat,           ".
                                        "       FilmTitle.Name As FilmTitleName,         ".
                                        "       Silmooja.Name	As SilmoojaName,           ".
                                        "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                                        "       Count(distinct ShowDgree) As CntDgree,   ".
                                        "       Count(distinct UnitPrice) As cntUnitPrice".
                                        "  From ".$sSingoName."   As Singo,              ".
                                        "       bas_showroom      As Showroom,           ".
                                        "       ".$sShowroomorder." As ShowroomOrder,    ".
                                        "       bas_filmtitle     As FilmTitle,          ".
                                        "       bas_silmooja      As Silmooja,           ".
                                        "       bas_location      As Location            ".
                                        " Where Singo.Singodate  = '".$WorkDate."'       ".
                                        "   And Singo.Silmooja   = Silmooja.Code         ".
                                        "   And Singo.Theather   = Showroom.Theather     ".
                                        "   And Singo.Room       = Showroom.Room         ".
                                        "   And Singo.Theather   = ShowroomOrder.Theather".
                                        "   And Singo.Room       = ShowroomOrder.Room    ".
                                        "   And Singo.Location   = Location.Code         ".
                                        "   And Singo.Open       = FilmTitle.Open        ".
                                        "   And Singo.Film       = FilmTitle.Code	       ".
                                        $FilmsupplyCont                                   .
                                        $AddedCont                                        .
                                        " Group By Singo.Theather,                       ".
                                        "          Singo.Room,                           ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Singo.Silmooja ,                      ".
                                        "          Showroom.Discript                     ".
                                        $OrderCont                                        ,$connect) ;

                    include "wrk_filmsupply_Link_DnACvs1.php";
               }
           }
           */



           //-----------
           // 지방출력
           //-----------
           $zoneName  = "지방" ;
           $qryzoneloc = mysql_query("select Location from bas_filmsupplyzoneloc ".
                                     " Where Code = '".$filmsupplyCode."'         ".
                                     "   And (  Zone = '04')                      ",$connect) ;

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
               if   ($FilmTileFilm == '00') // 분리된영화의통합코드
               {
                    $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' " ;
                    $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                 "          Showroom.Discript,                    ".
                                 "          Singo.Theather,                       ".
                                 "          Singo.Room                            " ;
                    $FilmsupplyCont = "" ;
               }
               else
               {
                    $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' ".
                                  " And Singo.Film = '".$FilmTileFilm."' " ;
                    $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                 "          Showroom.Discript,                    ".
                                 "          Singo.Theather,                       ".
                                 "          Singo.Room                            " ;
                    $FilmsupplyCont = "   And Singo.Filmsupply = ShowroomOrder.FilmSupply " ;
               }
           }
           $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                    "       Singo.Theather,                          ".
                                    "       Singo.Room,                              ".
                                    "       Singo.Open,                              ".
                                    "       Singo.Film,                              ".
                                    "       Singo.Silmooja,                          ".
                                    "       Showroom.Discript,                       ".
                                    "       Showroom.Location,                       ".
                                    "       Location.Name As LocationName,           ".
                                    "       Showroom.Seat As ShowRoomSeat,           ".
                                    "       FilmTitle.Name As FilmTitleName,         ".
                                    "       Silmooja.Name	As SilmoojaName,           ".
                                    "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                                    "       Count(distinct ShowDgree) As CntDgree,   ".
                                    "       Count(distinct UnitPrice) As cntUnitPrice".
                                    "  From ".$sSingoName."   As Singo,              ".
                                    "       bas_showroom      As Showroom,           ".
                                    "       ".$sShowroomorder." As ShowroomOrder,    ".
                                    "       bas_filmtitle     As FilmTitle,          ".
                                    "       bas_silmooja      As Silmooja,           ".
                                    "       bas_location      As Location            ".
                                    " Where Singo.Singodate  = '".$WorkDate."'       ".
                                    "   And Singo.Silmooja   = Silmooja.Code         ".
                                    "   And Singo.Theather   = Showroom.Theather     ".
                                    "   And Singo.Room       = Showroom.Room         ".
                                    "   And Singo.Theather   = ShowroomOrder.Theather".
                                    "   And Singo.Room       = ShowroomOrder.Room    ".
                                    "   And Singo.Location   = Location.Code         ".
                                    "   And Singo.Open       = FilmTitle.Open        ".
                                    "   And Singo.Film       = FilmTitle.Code	       ".
                                    $FilmsupplyCont                                   .
                                    $AddedLoc                                         .
                                    $AddedCont                                        .
                                    " Group By Singo.Theather,                       ".
                                    "          Singo.Room,                           ".
                                    "          Singo.Open,                           ".
                                    "          Singo.Film,                           ".
                                    "          Singo.Silmooja ,                      ".
                                    "          Showroom.Discript                     ".
                                    $OrderCont                                        ,$connect) ;

           include "wrk_filmsupply_Link_DnACvs1.php";



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
                                     " Where Code  = '".$filmsupplyCode."'  ".
                                     "   And Zone  = '".$ZoneCode."'        ",$connect) ;

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

                        // (울산 + 김해 + 창원 )

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

               //$zoneName = $filmsupplyzoneloc_data["Name"]
           }

           if  ($AddedCont != "") // 해당하는 자료가 있는경우..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // 특정영화만 선택하여 보고자 할 경우..
               {
                   if   ($FilmTileFilm == '00') // 분리된영화의통합코드
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' " ;
                        $OrderCont = " Order By Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                        $FilmsupplyCont = "" ;
                   }
                   else
                   {
                        $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' " .
                                      " And Singo.Film = '".$FilmTileFilm."' " ;
                        $OrderCont = " Order By ShowroomOrder.Seq,                    ".
                                     "          Showroom.Discript,                    ".
                                     "          Singo.Theather,                       ".
                                     "          Singo.Room                            " ;
                        $FilmsupplyCont = "   And Singo.Filmsupply = ShowroomOrder.FilmSupply " ;
                   }
               }
               $qry_singo = mysql_query("Select ShowroomOrder.Seq,                       ".
                                        "       Singo.Theather,                          ".
                                        "       Singo.Room,                              ".
                                        "       Singo.Open,                              ".
                                        "       Singo.Film,                              ".
                                        "       Singo.Silmooja,                          ".
                                        "       Showroom.Discript,                       ".
                                        "       Showroom.Location,                       ".
                                        "       Location.Name As LocationName,           ".
                                        "       Showroom.Seat As ShowRoomSeat,           ".
                                        "       FilmTitle.Name As FilmTitleName,         ".
                                        "       Silmooja.Name	As SilmoojaName,           ".
                                        "       Sum(Singo.NumPersons) As SumNumPersons,  ".
                                        "       Count(distinct ShowDgree) As CntDgree,   ".
                                        "       Count(distinct UnitPrice) As cntUnitPrice".
                                        "  From ".$sSingoName."   As Singo,              ".
                                        "       bas_showroom      As Showroom,           ".
                                        "       ".$sShowroomorder." As ShowroomOrder,    ".
                                        "       bas_filmtitle     As FilmTitle,          ".
                                        "       bas_silmooja      As Silmooja,           ".
                                        "       bas_location      As Location            ".
                                        " Where Singo.Singodate  = '".$WorkDate."'       ".
                                        "   And Singo.Silmooja   = Silmooja.Code         ".
                                        "   And Singo.Theather   = Showroom.Theather     ".
                                        "   And Singo.Room       = Showroom.Room         ".
                                        "   And Singo.Theather   = ShowroomOrder.Theather".
                                        "   And Singo.Room       = ShowroomOrder.Room    ".
                                        "   And Singo.Location   = Location.Code         ".
                                        "   And Singo.Open       = FilmTitle.Open        ".
                                        "   And Singo.Film       = FilmTitle.Code	       ".
                                        $FilmsupplyCont                                   .
                                        $AddedCont                                        .
                                        " Group By Singo.Theather,                       ".
                                        "          Singo.Room,                           ".
                                        "          Singo.Open,                           ".
                                        "          Singo.Film,                           ".
                                        "          Singo.Silmooja ,                      ".
                                        "          Showroom.Discript                     ".
                                        $OrderCont                                        ,$connect) ;
               $filmtitleNameTitle = "" ; // 두번이상 반복되면 영화명을 지우기 위해 ..

               include "wrk_filmsupply_Link_DnACvs1.php";
          }
       }

   }
?>