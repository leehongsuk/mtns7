<?

   $FilmTileOpen = substr($FilmTile,0,6) ;
   $FilmTileFilm = substr($FilmTile,6,2) ;

   if   ((!$FilmTile) && (!$ZoneCode) && (!$LocationCode)) //
   {
       //echo "����" ;
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

       if   ($FilmTileFilm == '00') // �и��ȿ�ȭ�������ڵ�
       {
            $FilmCond = " Open = '".$FilmTileOpen."' " ;
       }
       else
       {
            $FilmCond = "    Open = '".$FilmTileOpen."' ".
                        "And Film = '".$FilmTileFilm."' " ;
       }

       $sSingoName     = get_singotable($FilmTileOpen,$FilmTileFilm,$connect) ;  // �Ű� ���̺� �̸�..
       $sShowroomorder = get_showroomorder($FilmOpen,$FilmCode,$connect) ;

       // ����ݾ� ���� ���Ѵ�. (�Ű�����, ��޻�) // ��ȭ �� �������� Ȯ�ο�
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
















       if   ($ZoneCode=="9999") // "��ü"
       {
           $filmtitleNameTitle = "" ; // �ι��̻� �ݺ��Ǹ� ��ȭ���� ����� ���� ..


           //-----------
           // ���� ���
           //-----------
           $zoneName  = "����" ;
           $AddedCont = " And  Singo.Location = '100' " ;

           if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
           {
               if   ($FilmTileFilm == '00') // �и��ȿ�ȭ�������ڵ�
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
           // ������
           //-----------
           $zoneName  = "���" ;
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

           // ���

           if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
           {
               if   ($FilmTileFilm == '00') // �и��ȿ�ȭ�������ڵ�
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




           // (�λ�+���+����+â��)�� �λ����� ���δ�.

           //-----------
           // �λ� ���
           //-----------

           $zoneName  = "�λ�" ;
           $AddedCont = " And ( Singo.Location = '200'   " . // �λ�
                        "  or   Singo.Location = '600'   " . // ���
                        "  or   Singo.Location = '207'   " . // ����
                        "  or   Singo.Location = '205'   " . // ����
                        "  or   Singo.Location = '208'   " . // ����
                        "  or   Singo.Location = '202'   " . // ����
                        "  or   Singo.Location = '211'   " . // ��õ
                        "  or   Singo.Location = '212'   " . // ��â
                        "  or   Singo.Location = '213'   " . // ���
                        "  or   Singo.Location = '201' ) " ; // â��


           if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
           {
               if   ($FilmTileFilm == '00') // �и��ȿ�ȭ�������ڵ�
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
           // �氭 ���
           //-----------
           $zoneName  = "�氭" ;
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

           if  ($AddedCont != "") // �氭������ �ش��ϴ� �ڷᰡ �ִ°��..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
               {
                   if   ($FilmTileFilm == '00') // �и��ȿ�ȭ�������ڵ�
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
           // ��û ���
           //-----------
           $zoneName  = "��û" ;
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

           if  ($AddedCont != "") // ��û������ �ش��ϴ� �ڷᰡ �ִ°��..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
               {
                   if   ($FilmTileFilm == '00') // �и��ȿ�ȭ�������ڵ�
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
           // �泲 ���
           //-----------
           $zoneName  = "�泲" ;
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
               //���,â��,���ظ� �� �泲�� �泲

               $AddedCont .= " Or Singo.Location <> '600' " ;
               $AddedCont .= " Or Singo.Location <> '201' " ;
               $AddedCont .= " Or Singo.Location <> '207' " ;

               $AddedCont .= ")" ;
           }
           else
           {
               $AddedCont = "" ;
           }

           if  ($AddedCont != "") // �泲������ �ش��ϴ� �ڷᰡ �ִ°��..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
               {
                   if   ($FilmTileFilm == '00') // �и��ȿ�ȭ�������ڵ�
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
           // ��� ���
           //-----------
           $zoneName  = "���" ;
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

           if  ($AddedCont != "") // �氭������ �ش��ϴ� �ڷᰡ �ִ°��..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
               {
                   if   ($FilmTileFilm == '00') // �и��ȿ�ȭ�������ڵ�
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
           // ȣ�� ���
           //-----------
           $zoneName  = "ȣ��" ;
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

           if  ($AddedCont != "") // �氭������ �ش��ϴ� �ڷᰡ �ִ°��..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
               {
                   if   ($FilmTileFilm == '00') // �и��ȿ�ȭ�������ڵ�
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
           if  (($filmsupplyCode=="20001") || ($filmsupplyCode=="20002")) // DBO,û����
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


                    if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
                    {
                        if   ($FilmTileFilm == '00') // �и��ȿ�ȭ�������ڵ�
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
           // �������
           //-----------
           $zoneName  = "����" ;
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
           $AddedLoc .= " and Singo.Location <> '100' "  ; // ����
           $AddedLoc .= " and Singo.Location <> '200' "  ; // �λ�
           $AddedLoc .= " and Singo.Location <> '600' "  ; // ���
           $AddedLoc .= " and Singo.Location <> '207' "  ; // ����
           $AddedLoc .= " and Singo.Location <> '205' "  ; // ����
           $AddedLoc .= " and Singo.Location <> '208' "  ; // ����
           $AddedLoc .= " and Singo.Location <> '202' "  ; // ����
           $AddedLoc .= " and Singo.Location <> '211' "  ; // ��õ
           $AddedLoc .= " and Singo.Location <> '212' "  ; // ��â
           $AddedLoc .= " and Singo.Location <> '213' "  ; // ���
           $AddedLoc .= " and Singo.Location <> '201' "  ; // â��
           $AddedLoc .= ")" ;

           // ��� + ���� + �λ� + ��� + â�� + ���� �� ������ �������� �������� �Ѵ�.

           if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
           {
               if   ($FilmTileFilm == '00') // �и��ȿ�ȭ�������ڵ�
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

       //if   ($ZoneCode!="0000") // ��ü�� �ƴ� ��������..
       else
       {
           $AddedCont = "" ; // �߰����� �˻�����

           // Ư�������� ���������� ������ �� ���
           if  (($LocationCode) && ($LocationCode!=""))
           {
               $qryzone = mysql_query("Select * From bas_location        ".
                                      " Where Code = '".$LocationCode."' ",$connect) ;


               $zone_data = mysql_fetch_array($qryzone) ;
               if  ($zone_data)
               {
                   $zoneName = $zone_data["Name"] ;
               }

               if  ($LocationCode=="200")//  �λ��� (�λ�+���+����+â��)
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

           // Ư�������� ���������� ������ �� ���
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
                   if  ($ZoneCode == '20') // �泲�ΰ�� �λ��� �����Ѵ�.
                   {
                        $AddedCont .= " Or Singo.Location = '200' " ;

                        // (��� + ���� + â�� )

                        $AddedCont .= " Or Singo.Location <> '600' ".  // ���
                                      " Or Singo.Location <> '207' ".  // ����
                                      " Or Singo.Location <> '205' ".  // ����
                                      " Or Singo.Location <> '208' ".  // ����
                                      " Or Singo.Location <> '202' ".  // ����
                                      " Or Singo.Location <> '211' ".  // ��õ
                                      " Or Singo.Location <> '212' ".  // ��â
                                      " Or Singo.Location <> '213' ".  // ���
                                      " Or Singo.Location <> '201' " ; // â��

                   }
                   $AddedCont .= ")" ;
               }
               else
               {
                   $AddedCont = "" ;
               }

               //$zoneName = $filmsupplyzoneloc_data["Name"]
           }

           if  ($AddedCont != "") // �ش��ϴ� �ڷᰡ �ִ°��..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
               {
                   if   ($FilmTileFilm == '00') // �и��ȿ�ȭ�������ڵ�
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
               $filmtitleNameTitle = "" ; // �ι��̻� �ݺ��Ǹ� ��ȭ���� ����� ���� ..

               include "wrk_filmsupply_Link_DnACvs1.php";
          }
       }

   }
?>