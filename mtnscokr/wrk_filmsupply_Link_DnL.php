<?
  session_start();
?>
<?
  set_time_limit(0) ; // �� ó���۾��� ���� ���� ����.....


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
        include "config.php";        // {[������ ���̽�]} : ȯ�漳��

        $connect = dbconn() ;        // {[������ ���̽�]} : ����

        mysql_select_db($cont_db) ;  // {[������ ���̽�]} : �����


        $FilmOpen = substr($FilmTile,0,6) ;
        $FilmCode = substr($FilmTile,6,2) ;

        $Theather = substr($ShowRoom,0,4) ;
        $Room     = substr($ShowRoom,4,2) ;

        $sSingoName     = get_singotable($FilmOpen,$FilmCode,$connect) ;  // �Ű� ���̺� �̸�..
        $sShowroomorder = get_showroomorder($FilmOpen,$FilmCode,$connect) ;


        if  ($ActionCode=="Delete") // �� �󿵰��� �Ű�� ��ü�� �����Ѵ�.
        {
            mysql_query("Delete From ".$sSingoName."             ".
                        " Where SingoDate = '".$WorkDate."'      ".
                        "   And Silmooja  = '".$silmoojaCode."'  ".
                        "   And Theather  = '".$Theather."'      ".
                        "   And Room      = '".$Room."'          ",$connect) ;
        }



        if  ($EndShowRoom!="") // ��������
        {
            // �ǹ��ڰ� ������ �󿵰��� �����Ѵ�.
            mysql_query("Delete From bas_silmoojatheather                   ".
                        " Where Silmooja  = '".$silmoojaCode."'             ".
                        "   And Theather  = '".substr($EndShowRoom,0,4)."'  ".
                        "   And Room      = '".substr($EndShowRoom,4,2)."'  ",$connect) ;

            // �ǹ��ڰ� ������ �󿵰��� �����Ѵ�.
            mysql_query("Delete From bas_silmoojatheatherpriv               ".
                        " Where Silmooja  = '".$silmoojaCode."'             ".
                        "   And WorkDate  = '".$WorkDate."'                 ".
                        "   And Theather  = '".substr($EndShowRoom,0,4)."'  ".
                        "   And Room      = '".substr($EndShowRoom,4,2)."'  ",$connect) ;

            // �����Ǵ� �󿵰��� �̸��� ���Ѵ�.
            $qry_showroom = mysql_query("Select * From bas_showroom                         ".
                                        " Where Theather  = '".substr($EndShowRoom,0,4)."'  ".
                                        "   And Room      = '".substr($EndShowRoom,4,2)."'  ",$connect) ;
            if  ($showroom_data = mysql_fetch_array($qry_showroom))
            {
                $showroomDiscript = $showroom_data["Discript"] ; // �����Ǵ� �󿵰��� �̸�
            }

            // �����Ǵ� �󿵰��� ��ȭ�� ���Ѵ�.
            $qry_filmtitle = mysql_query("Select * From bas_filmtitle                   ".
                                         " Where Open = '".substr($EndFilmTitle,0,6)."' ".
                                         "   And Code = '".substr($EndFilmTitle,6,2)."' ",$connect) ;
            if  ($filmtitle_data = mysql_fetch_array($qry_filmtitle))
            {
                $filmtitleName = $filmtitle_data["Name"] ; // �����Ǵ� �󿵰��� ��ȭ
            }
            // �ǹ��ڰ� ������ �󿵰����������� �����
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

            // �󿵰��� �ǹ��� ������ ����� (���������δ� �ƹ��ǹ̾���)
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
<title>SMS��Ȳ</title>
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
         // ���� ���
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
  <b>SMS��Ȳ</b>

  <!--                 -->
  <!-- ���ν��ھ� ���� -->
  <!--                 -->

  <br>
  <span id='count'></span>
  <br>


   <?
   //echo "����=".$ZoneCode. " - ����=".$LocationCode. " - ��ȭ=".$FilmTile ;

   if   ((!$FilmTile) && (!$ZoneCode) && (!$LocationCode)) //
   {
       //echo "����" ;
   }
   else
   {
       if   ($FilmCode == '00') // �и��ȿ�ȭ�������ڵ�
       {
            $FilmCond = " Open = '".$FilmOpen."' " ;
       }
       else
       {
            $FilmCond = "    Open = '".$FilmOpen."' ".
                        "And Film = '".$FilmCode."' " ;
       }

       $silmoojaCode = "111111" ;

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
               if   ($FilmCode == '00') // �и��ȿ�ȭ�������ڵ�
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
           //$filmtitleNameTitle = "" ; // �ι��̻� �ݺ��Ǹ� ��ȭ���� ����� ���� ..

           include "wrk_filmsupply_Link_DnL1.php";




           //-----------
           // ������
           //-----------
           $zoneName  = "���" ;
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

           // ���


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
           //$filmtitleNameTitle = "" ; // �ι��̻� �ݺ��Ǹ� ��ȭ���� ����� ���� ..

           include "wrk_filmsupply_Link_DnL1.php";






           //-----------
           // �λ� ���
           //-----------

           $zoneName  = "�λ�" ;
           $AddedCont = " And ( Singo.Location = '200'   " . // �λ�
                        "  or   Singo.Location = '203'   " . // ����
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
           //$filmtitleNameTitle = "" ; // �ι��̻� �ݺ��Ǹ� ��ȭ���� ����� ���� ..

           include "wrk_filmsupply_Link_DnL1.php";




           //-----------
           // �氭 ���
           //-----------
           $zoneName  = "�氭" ;
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
               //$filmtitleNameTitle = "" ; // �ι��̻� �ݺ��Ǹ� ��ȭ���� ����� ���� ..

               include "wrk_filmsupply_Link_DnL1.php";
           }

           //-----------
           // ��û ���
           //-----------
           $zoneName  = "��û" ;
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
               //$filmtitleNameTitle = "" ; // �ι��̻� �ݺ��Ǹ� ��ȭ���� ����� ���� ..

               include "wrk_filmsupply_Link_DnL1.php";
           }
           //-----------
           // �泲 ���
           //-----------
           $zoneName  = "�泲" ;
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
               //$filmtitleNameTitle = "" ; // �ι��̻� �ݺ��Ǹ� ��ȭ���� ����� ���� ..

               include "wrk_filmsupply_Link_DnL1.php";
           }
           //-----------
           // ��� ���
           //-----------
           $zoneName  = "���" ;
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
               //$filmtitleNameTitle = "" ; // �ι��̻� �ݺ��Ǹ� ��ȭ���� ����� ���� ..

               include "wrk_filmsupply_Link_DnL1.php";
           }
           //-----------
           // ȣ�� ���
           //-----------
           $zoneName  = "ȣ��" ;
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
               //$filmtitleNameTitle = "" ; // �ι��̻� �ݺ��Ǹ� ��ȭ���� ����� ���� ..

               include "wrk_filmsupply_Link_DnL1.php";
           }




           //-----------
           // �������
           //-----------
           $zoneName  = "����" ;
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

           // ��� + ���� + �λ� + ��� + â�� + ���� �� ������ �������� �������� �Ѵ�.

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
           //$filmtitleNameTitle = "" ; // �ι��̻� �ݺ��Ǹ� ��ȭ���� ����� ���� ..

           include "wrk_filmsupply_Link_DnL1.php";

           ?>
           </table>
           <?
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

           if  ($AddedCont != "") // �ش��ϴ� �ڷᰡ �ִ°��..
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
               $filmtitleNameTitle = "" ; // �ι��̻� �ݺ��Ǹ� ��ȭ���� ����� ���� ..

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
