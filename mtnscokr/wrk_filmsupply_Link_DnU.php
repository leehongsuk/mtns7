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

    session_start();

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



?>
   <link rel=stylesheet href=./LinkStyle.css type=text/css>
   <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

   <head>
   <title>���� ���к� ��Ȳ</title>
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
  <!--onload='Timer(<?=$sec?>)'-->

  <script language='JavaScript'>
  <!--
        //
        // ���� ���
        //

        function toexel_click()
        {
            <?
            if  ($filmproduce)
            {
                ?>
                botttomaddr = 'wrk_filmsupply_Link_DnU.php?'
                            + 'logged_UserId=<?=$logged_UserId?>&'
                            + 'FilmTile=<?=$FilmTile?>&'
                            + 'WorkDate=<?=$WorkDate?>&'
                            + 'WorkGubun=<?=$WorkGubun?>&'
                            + 'LocationCode=<?=$LocationCode?>&'
                            + 'filmproduce=<?=$filmproduce?>&'
                            + 'ToExel=Yes' ;
                <?
            }
            else
            {
                ?>
                botttomaddr = 'wrk_filmsupply_Link_DnU.php?'
                            + 'logged_UserId=<?=$logged_UserId?>&'
                            + 'FilmTile=<?=$FilmTile?>&'
                            + 'WorkDate=<?=$WorkDate?>&'
                            + 'WorkGubun=<?=$WorkGubun?>&'
                            + 'LocationCode=<?=$LocationCode?>&'
                            + 'ToExel=Yes' ;
                <?
            }
            ?>


            <?
            if  ($WorkGubun==1) // ���� ȸ���� ��Ȳ
            {
                ?>
                botttomaddr += ('&'+'bFilmType=<?=$bFilmType?>') ;
                <?
            }
            else
            {
                ?>
                botttomaddr += ('&'+'bFilmType=') ;
                <?
            }

            ?>

            botttomaddr += ('&'+'bFilmTypeNo=<?=$bFilmTypeNo?>') ;


            //alert(botttomaddr) ;
            top.frames.bottom.location.href = botttomaddr ;
        }
  //-->
  </script>

<center>

  <br><br>
  <b>���� ���к� ��Ȳ</b>
  <?

  if  ((!$ToExel) && ($TimJang==false)) // �������
  {
      ?>
      <a href="javascript: window.print();"><img src="print.gif" width="32" height="32" border="0"></a>
      <a href=# onclick="toexel_click();"><img src="exel.gif" width="32" height="32" border="0"></a>
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

   $sSingoName   = get_singotable($FilmOpen,$FilmCode,$connect) ;  // �Ű� ���̺� �̸�..
   $sAccName     = get_acctable($FilmOpen,$FilmCode,$connect) ;    // accumulate �̸�..
   $sDgrName     = get_degree($FilmOpen,$FilmCode,$connect) ;
   $sDgrpName    = get_degreepriv($FilmOpen,$FilmCode,$connect) ;
   $sFilmType    = get_FilmType($FilmOpen,$FilmCode,$connect) ;
   $sFilmTypePrv = get_FilmTypePrv($FilmOpen,$FilmCode,$connect) ;

   // Ư�� ��ȭ�� ���������� ������ �� ���
   if  (($FilmTile != "") && ($FilmTile != "00000000"))
   {
       if   ($singoFilm=='00')
       {
            $AddedCont  = "   And singo.open = '".$FilmOpen."' \n" ;
            $FinishCont = "   And singo.open = finish.open     \n" ;
       }
       else
       {
            $AddedCont  = "   And singo.open = '".$FilmOpen."' \n" .
                          "   And singo.film = '".$FilmCode."' \n" ;
            $FinishCont = "   And singo.open = finish.open     \n" .
                          "   And singo.film = finish.film     \n" ;
       }

       if   ($singoFilm=='00')
       {
            $modAddedCont  = "   And open = '".$FilmOpen."' \n" ;
       }
       else
       {
            $modAddedCont  = "   And open = '".$FilmOpen."' \n" .
                             "   And film = '".$FilmCode."' \n" ;
       }
   }


   if   (!$FilmTile) //
   {
       //echo "����" ;
   }
   else
   {
        if ($ToExel)
        {
            $ColorA =  '#ffffff' ;
            $ColorB =  '#ffffff' ;
            $ColorC =  '#ffffff' ;
            $ColorD =  '#ffffff' ;
        }
        else
        {
            $ColorA =  '#ffebcd' ;
            $ColorB =  '#dcdcec' ;
            $ColorC =  '#dcdcdc' ;
            $ColorD =  '#c0c0c0' ;
        }

        // ����
        $sLoc1 = "   And singo.location = 100    " ;


        // ���
        $sLoc2 = " And " ;

        $sQuery = "Select Location                     \n".
                  "  From bas_filmsupplyzoneloc        \n".
                  " Where Zone = '04'                  \n" ;
        $qryzoneloc = mysql_query($sQuery,$connect) ;
        while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
        {
             if  ($sLoc2 == " And ")
                 $sLoc2 .= "( singo.Location = '".$zoneloc_data["Location"]."' \n"  ;
             else
                 $sLoc2 .= " or singo.Location = '".$zoneloc_data["Location"]."' \n"  ;
        }
        $sLoc2 .= ")" ;


        //  �λ�
        $sLoc3 = "   And ( singo.location = 200     ".
                 "    Or   singo.location = 203     ".
                 "    Or   singo.location = 600     ".
                 "    Or   singo.location = 207     ".
                 "    Or   singo.location = 205     ".
                 "    Or   singo.location = 208     ".
                 "    Or   singo.location = 202     ".
                 "    Or   singo.location = 211     ".
                 "    Or   singo.location = 212     ".
                 "    Or   singo.location = 213     ".
                 "    Or   singo.location = 201 )   " ;

        //  ����
        $sQuery = "Select Location From bas_filmsupplyzoneloc ".
                  " Where Zone = '04'                         " ;
        $qryzoneloc = mysql_query($sQuery,$connect) ;

        $sLoc4 = " and " ;

        while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
        {
             if  ($sLoc4 == " and ")
                 $sLoc4 .= "( singo.Location <> '".$zoneloc_data["Location"]."' "  ;
             else
                 $sLoc4 .= " and singo.Location <> '".$zoneloc_data["Location"]."' "  ;
        }
        $sLoc4 .= " and singo.Location <> '100' "  ; // ����
        $sLoc4 .= " and singo.Location <> '200' "  ; // �λ�
        $sLoc4 .= " and singo.Location <> '203' "  ; // �뿵
        $sLoc4 .= " and singo.Location <> '600' "  ; // ���
        $sLoc4 .= " and singo.Location <> '207' "  ; // ����
        $sLoc4 .= " and singo.Location <> '205' "  ; // ����
        $sLoc4 .= " and singo.Location <> '208' "  ; // ����
        $sLoc4 .= " and singo.Location <> '202' "  ; // ����
        $sLoc4 .= " and singo.Location <> '211' "  ; // ��õ
        $sLoc4 .= " and singo.Location <> '212' "  ; // ��â
        $sLoc4 .= " and singo.Location <> '213' "  ; // ���
        $sLoc4 .= " and singo.Location <> '201' "  ; // â��
        $sLoc4 .= ")" ;

        ?>

        ����
        <? $FilmType =  0 ; include "wrk_filmsupply_Link_DnU1.php"; ?>
        35
        <? $FilmType = 35  ; include "wrk_filmsupply_Link_DnU1.php"; ?>
        2
        <? $FilmType =  2 ; include "wrk_filmsupply_Link_DnU1.php"; ?>
        3
        <? $FilmType =  3 ; include "wrk_filmsupply_Link_DnU1.php"; ?>
        29
        <? $FilmType = 29 ; include "wrk_filmsupply_Link_DnU1.php"; ?>
        39
        <? $FilmType = 39 ; include "wrk_filmsupply_Link_DnU1.php"; ?>



        <?
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
