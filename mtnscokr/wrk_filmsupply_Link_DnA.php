<?
    set_time_limit(0) ; // �� ó���۾��� ���� ���� ����.....

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
        include "config.php";        // {[������ ���̽�]} : ȯ�漳��

        $connect = dbconn() ;        // {[������ ���̽�]} : ����

        mysql_select_db($cont_db) ;  // {[������ ���̽�]} : �����


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
            if  ($ActionCode=="Delete") // �� �󿵰��� �Ű�� ��ü�� �����Ѵ�.
            {
                $delTheather = substr($ShowRoom,0,4) ;
                $delRoom     = substr($ShowRoom,4,2) ;

                $sSingoName = get_singotable($Open,$Film,$connect) ;  // �Ű� ���̺� �̸�..

                $sQuery = "Delete From ".$sSingoName."            ".
                          " Where SingoDate = '".$WorkDate."'     ".
                          "   And Silmooja  = '".$silmoojaCode."' ".
                          "   And Theather  = '".$delTheather."'  ".
                          "   And Room      = '".$delRoom."'      ".
                          "   And Open      = '".$Open."'         ".
                          "   And Film      = '".$Film."'         " ;
                mysql_query($sQuery,$connect) ;
            }

            if  ($EndShowRoom!="") // ��������
            {
                // �ǹ��ڰ� ������ �󿵰��� �����Ѵ�.
                $sQuery = "Delete From bas_silmoojatheather                   ".
                          " Where Silmooja  = '".$silmoojaCode."'             ".
                          "   And Theather  = '".substr($EndShowRoom,0,4)."'  ".
                          "   And Room      = '".substr($EndShowRoom,4,2)."'  " ;
                mysql_query($sQuery,$connect) ;

                // �ǹ��ڰ� ������ �󿵰��� �����Ѵ�.
                $sQuery = "Delete From bas_silmoojatheatherpriv               ".
                          " Where Silmooja  = '".$silmoojaCode."'             ".
                          "   And WorkDate  = '".$WorkDate."'                 ".
                          "   And Theather  = '".substr($EndShowRoom,0,4)."'  ".
                          "   And Room      = '".substr($EndShowRoom,4,2)."'  " ;
                mysql_query($sQuery,$connect) ;

                // �����Ǵ� �󿵰��� �̸��� ���Ѵ�.
                $sQuery = "Select * From bas_showroom                         ".
                          " Where Theather  = '".substr($EndShowRoom,0,4)."'  ".
                          "   And Room      = '".substr($EndShowRoom,4,2)."'  " ;
                $qry_showroom = mysql_query($sQuery,$connect) ;
                if  ($showroom_data = mysql_fetch_array($qry_showroom))
                {
                    $showroomDiscript = $showroom_data["Discript"] ; // �����Ǵ� �󿵰��� �̸�
                }

                // �����Ǵ� �󿵰��� ��ȭ�� ���Ѵ�.
                $sQuery = "Select * From bas_filmtitle                   ".
                          " Where Open = '".substr($EndFilmTitle,0,6)."' ".
                          "   And Code = '".substr($EndFilmTitle,6,2)."' " ;
                $qry_filmtitle = mysql_query($sQuery,$connect) ;
                if  ($filmtitle_data = mysql_fetch_array($qry_filmtitle))
                {
                    $filmtitleName       = $filmtitle_data["Name"] ; // �����Ǵ� �󿵰��� ��ȭ

                }
                // �ǹ��ڰ� ������ �󿵰����������� �����
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

                // �󿵰��� �ǹ��� ������ ����� (���������δ� �ƹ��ǹ̾���)
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
   <title>���� ����</title>
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
         //  ����ó��
         //
         function endingShowroom(sSilmoojaCode,sEndShowRoom,sEndFilmTitle)
         {
            answer = confirm("������ ����ó���Ͻð����ϱ�?") ;
            if  (answer==true)
            {
                // WorkDate     : �۾�����
                // EndFilmTitle : ������ȭ
                // EndShowRoom  : �����󿵰�

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
         // ���ھ� ����
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
         // �����ڷ� �絵
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
         // �����ڷ� ����
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
         // �����ڷ� ����
         //
         function delect_click(singoSilmooja,TheatherRoom,FilmTile,sOpen,sFilm)
         {
             answer = confirm("������ �����Ͻð����ϱ�?") ;
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
         // �����ڷ� ��� ���
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
         // ���� ���
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
             if  ($WorkGubun==1) // ���� ȸ���� ��Ȳ
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
         //  csv ���
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
  <b>����ȸ������Ȳ</b>
  <?
  $sQuery = "Select * From bas_smsidchk        ".
            " Where Id = '".$spacial_UserId."' " ;
  $QrySmsIdChk = mysql_query($sQuery,$connect) ;
  if  ($ArrSmsIdChk = mysql_fetch_array($QrySmsIdChk)) // �̺���..
  {
      $TimJang   = true ;
      $TimJangNo = $ArrSmsIdChk["ChkNo"] ; // �����ȣ
  }
  else
  {
      $TimJang   = false ;
      $TimJangNo = "0" ; // �����ȣ
  }

  if  ((!$ToExel) && ($TimJang==false)) // �������
  {
      ?>
      <a href="javascript: window.print();"><img src="print.gif" width="32" height="32" border="0"></a>
      <a href=# onclick="toexel_click();"><img src="exel.gif" width="32" height="32" border="0"></a>
      <!-- <a href=# onclick="tocsv_click();"><img src="csv.jpg" width="32" height="32" border="0"></a> -->
      <?
  }
  ?>

  <!--                 -->
  <!-- ���ν��ھ� ���� -->
  <!--                 -->

  <br>
  <span id='count'></span>
  <br>


   <?
   //echo "����=".$ZoneCode. " - ����=".$LocationCode. " - ��ȭ=".$FilmTile ;

   $FilmOpen = substr($FilmTile,0,6) ;
   $FilmCode = substr($FilmTile,6,2) ;

   $sSingoName = get_singotable($FilmOpen,$FilmCode,$connect) ;  // �Ű� ���̺� �̸�..
   $sAccName   = get_acctable($FilmOpen,$FilmCode,$connect) ;    // accumulate �̸�..
   $sDgrName   = get_degree($FilmOpen,$FilmCode,$connect) ;
   $sDgrpName  = get_degreepriv($FilmOpen,$FilmCode,$connect) ;
   $sFilmType    =  get_FilmType($FilmOpen,$FilmCode,$connect) ;
   $sFilmTypePrv =  get_FilmTypePrv($FilmOpen,$FilmCode,$connect) ;

   if   ((!$FilmTile) && (!$ZoneCode) && (!$LocationCode)) //
   {
       //echo "����" ;
   }
   else
   {

       // �������
       // �������
       // �������
       // �������
       // �������
       // �������
       // �������
       // �������
       // �������
       // �������
       // �������

       if ($ToExel)  // �������
       {
          include "wrk_filmsupply_Link_DnZ.php";
       }
       // ������
       // ������
       // ������
       // ������
       // ������
       // ������
       // ������
       // ������
       // ������
       // ������
       // $ToExel


       if  ($ZoneCode=="9999") // "��ü"
       {
           $filmtitleNameTitle = "" ; // �ι��̻� �ݺ��Ǹ� ��ȭ���� ����� ���� ..

           if  ($TimJang==false) // �̺���..
           {
              //-----------
              // ���� ���
              //-----------
              $zoneName  = "����" ;

              $AddedCont = " And  Singo.Location = '100' " ;

              if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
              {
                  if   ($FilmCode == '00') // �и��ȿ�ȭ�������ڵ�
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
              if  ($WorkGubun == 34) // ���ʽ�
              {
                  $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
              }
              if  ($WorkGubun == 37) // �Ե����׸�
              {
                  $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
              }
              if  ($WorkGubun == 39) // �ް��ڽ�
              {
                  $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
              }
              if  ($WorkGubun == 56) // ��Ÿ
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
              // ������
              //-----------
              $zoneName  = "���" ;

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

              // ���

              if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
              {
                  if   ($FilmCode == '00') // �и��ȿ�ȭ�������ڵ�
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
              if  ($WorkGubun == 34) // ���ʽ�
              {
                  $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
              }
              if  ($WorkGubun == 37) // �Ե����׸�
              {
                  $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
              }
              if  ($WorkGubun == 39) // �ް��ڽ�
              {
                  $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
              }
              if  ($WorkGubun == 56) // ��Ÿ
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
           // �λ� ���
           //-----------

           $zoneName  = "�λ�" ;

           $AddedCont = " And ( Singo.Location = '200'   " . // �λ�
                        "  or   Singo.Location = '203'   " . // �뿵
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
               if   ($FilmCode == '00') // �и��ȿ�ȭ�������ڵ�
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
           if  ($WorkGubun == 34) // ���ʽ�
           {
               $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // �Ե����׸�
           {
               $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // �ް��ڽ�
           {
               $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
           }
           if  ($WorkGubun == 56) // ��Ÿ
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
           // �氭 ���
           //-----------
           $zoneName  = "�氭" ;

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

           if  ($AddedCont != "") // �氭������ �ش��ϴ� �ڷᰡ �ִ°��..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
               {
                   if   ($FilmCode == '00') // �и��ȿ�ȭ�������ڵ�
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
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
               }
               if  ($WorkGubun == 56) // ��Ÿ
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
           // ��û ���
           //-----------
           $zoneName  = "��û" ;

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

           if  ($AddedCont != "") // ��û������ �ش��ϴ� �ڷᰡ �ִ°��..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
               {
                   if   ($FilmCode == '00') // �и��ȿ�ȭ�������ڵ�
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
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
               }
               if  ($WorkGubun == 56) // ��Ÿ
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
           // �泲 ���
           //-----------
           $zoneName  = "�泲" ;

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

           if  ($AddedCont != "") // �泲������ �ش��ϴ� �ڷᰡ �ִ°��..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
               {
                   if   ($FilmCode == '00') // �и��ȿ�ȭ�������ڵ�
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
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
               }
               if  ($WorkGubun == 56) // ��Ÿ
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
           // ��� ���
           //-----------
           $zoneName  = "���" ;

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

           if  ($AddedCont != "") // �氭������ �ش��ϴ� �ڷᰡ �ִ°��..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
               {
                   if   ($FilmCode == '00') // �и��ȿ�ȭ�������ڵ�
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
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
               }
               if  ($WorkGubun == 56) // ��Ÿ
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
           // ȣ�� ���
           //-----------
           $zoneName  = "ȣ��" ;

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

           if  ($AddedCont != "") // �氭������ �ش��ϴ� �ڷᰡ �ִ°��..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
               {
                   if   ($FilmCode == '00') // �и��ȿ�ȭ�������ڵ�
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
               if  ($WorkGubun == 34) // ���ʽ�
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
               }
               if  ($WorkGubun == 37) // �Ե����׸�
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
               }
               if  ($WorkGubun == 39) // �ް��ڽ�
               {
                   $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
               }
               if  ($WorkGubun == 56) // ��Ÿ
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
           // �������
           //-----------
           $zoneName  = "����" ;

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
           $AddedLoc .= " and Singo.Location <> '100' "  ; // ����
           $AddedLoc .= " and Singo.Location <> '200' "  ; // �λ�
           $AddedLoc .= " and Singo.Location <> '203' "  ; // �뿵
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

           // ��� + ���� + �λ� + ��� + â�� + ���� + ���� + ���� + â�� �� ������ �������� �������� �Ѵ�.

           if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
           {
               if   ($FilmCode == '00') // �и��ȿ�ȭ�������ڵ�
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
           if  ($WorkGubun == 34) // ���ʽ�
           {
               $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // �Ե����׸�
           {
               $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // �ް��ڽ�
           {
               $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
           }
           if  ($WorkGubun == 56) // ��Ÿ
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

       //if   ($ZoneCode!="0000") // ��ü�� �ƴ� ��������..
       else
       {
           $AddedCont = "" ; // �߰����� �˻�����

           // Ư�������� ���������� ������ �� ���
           if  (($LocationCode) && ($LocationCode!=""))
           {
               $sQuery = "Select * From bas_location        ".
                         " Where Code = '".$LocationCode."' " ;
               $qryzone = mysql_query($sQuery,$connect) ;
               if  ($zone_data = mysql_fetch_array($qryzone))
               {
                   $zoneName = $zone_data["Name"] ;
               }

               if  ($LocationCode=="200")//  �λ��� (�λ�+���+����+â��)
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

                        $AddedCont .= " Or Singo.Location <> '203' ".  // �뿵
                                      " Or Singo.Location <> '600' ".  // ���
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

           if  ($WorkGubun == 28)
           {
               $AddedCont .= " And Singo.Silmooja = '777777' " ;
           }
           if  ($WorkGubun == 33)
           {
               $AddedCont .= " And Singo.Silmooja = '555595' " ;
           }
           if  ($WorkGubun == 34) // ���ʽ�
           {
               $AddedCont .= " And Showroom.MultiPlex  = '4' " ;
           }
           if  ($WorkGubun == 37) // �Ե����׸�
           {
               $AddedCont .= " And Showroom.MultiPlex  = '5' " ;
           }
           if  ($WorkGubun == 39) // �ް��ڽ�
           {
               $AddedCont .= " And Showroom.MultiPlex  = '3' " ;
           }
           if  ($WorkGubun == 56) // ��Ÿ
           {
               $AddedCont .= " And (Showroom.MultiPlex <> '4' and Showroom.MultiPlex <> '5' and Showroom.MultiPlex <> '3' and Showroom.MultiPlex <> '2') " ;
           }

           if  ($AddedCont != "") // �ش��ϴ� �ڷᰡ �ִ°��..
           {
               if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
               {
                   if   ($FilmCode == '00') // �и��ȿ�ȭ�������ڵ�
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
               $filmtitleNameTitle = "" ; // �ι��̻� �ݺ��Ǹ� ��ȭ���� ����� ���� ..

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
