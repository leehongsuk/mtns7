<?
    set_time_limit(0) ; // �� ó���۾��� ���� ���� ����.....

    session_start();
?>
<html>
<?
    $FilmOpen = substr($FilmTile,0,6) ;
    $FilmCode = substr($FilmTile,6,2) ;

    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";        // {[������ ���̽�]} : ȯ�漳��

        $connect = dbconn() ;        // {[������ ���̽�]} : ����

        mysql_select_db($cont_db) ;  // {[������ ���̽�]} : �����

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
                     FormUpload.file.select(); // value�� ���� select ����!
                     document.execCommand('Delete'); // ����������!!
                 }
            });

        //-->
        </script>

   </head>


   <body bgcolor=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 >

<center>

   <br><br>

   <b>�󿵰��� �ʸ���������(<span id="output"></span>)</b>



   <BR><BR>
   <?
   //echo "����=".$ZoneCode. " - ����=".$LocationCode. " - ��ȭ=".$FilmTile ;

   $FilmOpen = substr($FilmTile,0,6) ;
   $FilmCode = substr($FilmTile,6,2) ;

   $sSingoName    = get_singotable($FilmOpen,$FilmCode,$connect) ;  // �Ű� ���̺� �̸�..
   $Showroomorder = get_showroomorder($FilmOpen,$FilmCode,$connect) ;
   $sAccName      = get_acctable($FilmOpen,$FilmCode,$connect) ;    // accumulate �̸�..
   $sDgrName      = get_degree($FilmOpen,$FilmCode,$connect) ;
   $sDgrpName     = get_degreepriv($FilmOpen,$FilmCode,$connect) ;


   $TableOrder = $Showroomorder."_tmp" ;

   drop_table($TableOrder,$connect) ;
   create_tbleorder($TableOrder,$Showroomorder,$connect) ;

   if   ((!$FilmTile) && (!$ZoneCode) && (!$LocationCode)) //
   {
       //echo "����" ;
   }
   else
   {
       if  ($ZoneCode=="9999") // "��ü"
       {

           //-----------
           // ���� ���
           //-----------
           $zoneName  = "����" ; //echo "<BR>".$zoneName."<BR>";

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
           // ������
           //-----------
           $zoneName  = "���" ; //echo "<BR>".$zoneName."<BR>";

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

           // ���

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
           // �λ� ���
           //-----------

           $zoneName  = "�λ�" ; //echo "<BR>".$zoneName."<BR>";

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
           // �氭 ���
           //-----------
           $zoneName  = "�氭" ; //echo "<BR>".$zoneName."<BR>";

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

           if  ($AddedCont != "") // �氭������ �ش��ϴ� �ڷᰡ �ִ°��..
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
           // ��û ���
           //-----------
           $zoneName  = "��û" ; //echo "<BR>".$zoneName."<BR>";

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

           if  ($AddedCont != "") // ��û������ �ش��ϴ� �ڷᰡ �ִ°��..
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
           // �泲 ���
           //-----------
           $zoneName  = "�泲" ; //echo "<BR>".$zoneName."<BR>";

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

           if  ($AddedCont != "") // �泲������ �ش��ϴ� �ڷᰡ �ִ°��..
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
           // ��� ���
           //-----------
           $zoneName  = "���" ; //echo "<BR>".$zoneName."<BR>";

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

           if  ($AddedCont != "") // �氭������ �ش��ϴ� �ڷᰡ �ִ°��..
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
           // ȣ�� ���
           //-----------
           $zoneName  = "ȣ��" ; //echo "<BR>".$zoneName."<BR>";

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

           if  ($AddedCont != "") // �氭������ �ش��ϴ� �ڷᰡ �ִ°��..
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
           // �������
           //-----------
           $zoneName  = "����" ; //echo "<BR>".$zoneName."<BR>";

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
           $AddedCont .= " and Singo.Location <> '100' "  ; // ����
           $AddedCont .= " and Singo.Location <> '200' "  ; // �λ�
           $AddedCont .= " and Singo.Location <> '600' "  ; // ���
           $AddedCont .= " and Singo.Location <> '207' "  ; // ����
           $AddedCont .= " and Singo.Location <> '205' "  ; // ����
           $AddedCont .= " and Singo.Location <> '208' "  ; // ����
           $AddedCont .= " and Singo.Location <> '202' "  ; // ����
           $AddedCont .= " and Singo.Location <> '211' "  ; // ��õ
           $AddedCont .= " and Singo.Location <> '212' "  ; // ��â
           $AddedCont .= " and Singo.Location <> '213' "  ; // ���
           $AddedCont .= " and Singo.Location <> '201' "  ; // â��
           $AddedCont .= ")" ;

           // ��� + ���� + �λ� + ��� + â�� + ���� �� ������ �������� �������� �Ѵ�.

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



       //if   ($ZoneCode!="0000") // ��ü�� �ƴ� ��������..
       else
       {
           $AddedCont = "" ; // �߰����� �˻�����

           // Ư�������� ���������� ������ �� ���
           if  (($LocationCode) && ($LocationCode!=""))
           {
               $sQuery = "Select * From bas_location
                           Where Code = '".$LocationCode."' " ; //eq($sQuery);
               $qryzone = mysql_query($sQuery,$connect) ;
               if  ($zone_data = mysql_fetch_array($qryzone))
               {
                   $zoneName = $zone_data["Name"] ;
               }

               if  ($LocationCode=="200")//  �λ��� (�λ�+���+����+â��+����+����)
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

           // Ư�������� ���������� ������ �� ���
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
                   if  ($ZoneCode == '20') // �泲�ΰ�� �λ��� �����Ѵ�.
                   {
                        $AddedCont .= " Or Singo.Location = '200' " ;

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
   else // �α������� �ʰ� �ٷε��´ٸ�..
   {
       ?>

       <!-- �α������� �ʰ� �ٷε��´ٸ� -->
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
