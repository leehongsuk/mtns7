<?
    set_time_limit(0) ; // �� ó���۾��� ���� ���� ����.....

    session_start();

    $NBSP="&nbsp;" ;
?>

<!-- ���� ���� -->
<html>

<link rel=stylesheet href=./LinkStyle.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>���� �Է�</title>

<style type="text/css" media="all">


    table {
     width: 350px;
     margin-bottom: 20px;
    }
    th, td {
     padding: 3px;
    }
    caption {
     text-align: left;
     color: #F60;
     padding: 10px;
     font-size: 1.2em;
     font-weight: bold;
    }

    #beauty,
    #use-th,
    #use-th-beauty{
     border-collapse: collapse;
     border: 1px solid #CCC;
    }
    #beauty {
    }
    #use-th {
    }
    #use-th-beauty {
     font-size: 0.9em;
     border: none;
    }
    #use-th-beauty td {
     border: 1px solid #CCC;
    }
    #use-th-beauty th {
     background: #366B9F url(th_bg.png) top repeat-x ;
     color: #FFF;
     height: 22px;
     border: 1px solid #A1C3E6;
    }
    #use-th-beauty th.row {
     background-color: #BDDBF9;
     background-image: none;
     height: auto;
     color: #356EAB;
     font-weight: normal;
    }
    #use-th-beauty td {
     padding-left: 5px;
    }
</style>


  <script language="JavaScript">
      <!--
      function ChgTheatherRate()
      {
           frmMain.Changing.value = "Yes" ;
           return true ;
      }
      //-->
  </script>

</head>



<body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 >


<?
    // ���������� �α��� �ߴ��� üũ�Ѵ�.
    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";        // {[������ ���̽�]} : ȯ�漳��

        $connect = dbconn() ;        // {[������ ���̽�]} : ����

        mysql_select_db($cont_db) ;  // {[������ ���̽�]} : �����

        $Open = substr($FilmTile,0,6) ;
        $Film = substr($FilmTile,6,2) ;

		$TblTheatherRate = get_theather_rate($Open,$Film,$connect) ; //�ʸ��� ������ ������ ���̺��� ���� �� ���Ѵ�.

        $SingoName     = get_singotable($Open,$Film,$connect) ;  // �Ű� ���̺� �̸�..
        $Showroomorder = get_showroomorder($Open,$Film,$connect) ;
        $TblTheatherRate  = get_theather_rate($Open,$Film,$connect) ;

        if  (($LocCode) || ($ZoneCode))
        {
            if  ($LocCode != "") // Ư������(3)�� ���������� ������ �� ���
            {
                $sQuery = "Select * From bas_location   ".
                          " Where Code = '".$LocCode."' " ;
                $QryLocation = mysql_query($sQuery,$connect) ;
                if  ($ArrLocation = mysql_fetch_array($QryLocation))
                {
                    $LocationName = $ArrLocation["Name"] ; // �����̸�
                }

                if  ($LocCode=="200") // ������ �λ��� (�λ�+���+����+â��)
                {
                    $AddedCont = " And (Singo.Location = '200'  ".
                                 " Or Singo.Location = '202'  ".
                                 " Or Singo.Location = '600'  ".
                                 " Or Singo.Location = '207'  ".
                                 " Or Singo.Location = '205'  ".
                                 " Or Singo.Location = '208'  ".
                                 " Or Singo.Location = '202'  ".
                                 " Or Singo.Location = '211'  ".
                                 " Or Singo.Location = '212'  ".
                                 " Or Singo.Location = '213'  ".
                                 " Or Singo.Location = '201') " ;
                }
                else
                {
                    $AddedCont = " And Singo.Location = '".$LocCode."' " ;
                }
            }


            if  ($ZoneCode != "") // Ư������(2)�� ���������� ������ �� ���
            {
                $sQuery = "Select * From bas_zone          ".
                          " Where Code = '".$ZoneCode."'   " ;  //Eq($sQuery) ;
                $QryZone = mysql_query($sQuery,$connect) ;
                if  ($ArrZone = mysql_fetch_array($QryZone))
                {
                    $ZoneName = $ArrZone["Name"] ; // �����̸�
                }


                $AddedCont = "" ;

                $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                          " Where Zone  = '".$ZoneCode."'        " ;
                $QryZoneloc = mysql_query($sQuery,$connect) ;
                while ($ArrZoneloc = mysql_fetch_array($QryZoneloc))
                {
                    if  ($AddedCont == "")
                    {
                        $AddedCont .= " And  ( Singo.Location = '".$ArrZoneloc["Location"]."' " ;
                    }
                    else
                    {
                        $AddedCont .= " Or Singo.Location = '".$ArrZoneloc["Location"]."' " ;
                    }
                }

                if  ($AddedCont != "")
                {
                    if  ($ZoneCode == '20') // �泲�ΰ�� �λ��� �����Ѵ�.
                    {
                         $AddedCont .= " Or Singo.Location  = '200' ".
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
            }
        }

        if  (($FilmTile != "") && ($FilmTile != "00000000")) // Ư����ȭ�� �����Ͽ� ������ �� ���..
        {
            if  ($FilmTileFilm == '00') // �и��ȿ�ȭ�������ڵ�
            {
                $AddedCont .= " And Singo.Open = '".$Open."' " ;
            }
            else
            {
                $AddedCont .= " And Singo.Open = '".$Open."' ".
                              " And Singo.Film = '".$Film."' " ;
            }
        }

        ?>
        <center>


        <br>
        <br>
        <form method=post action="" name="frmMain">

        <table border="1" id="use-th-beauty">
             <tr>
                <th id="first" width=70 align=center>����</th>
                <th width=200 align=center>�����</th>
                <th width=70 align=center>����</th>
             </tr>
        <?



        $TableOrder = $Showroomorder."_tmp" ;

        drop_table($TableOrder,$connect) ;
		create_tbleorder($TableOrder,$Showroomorder,$connect) ;

        $sQuery = "Select Singo.Theather,                          ".
                  "       Singo.Open,                              ".
                  "       Singo.Film,                              ".
                  "       Theather.Discript,                       ".
                  "       Theather.Location                        ".
                  "  From ".$SingoName."     As Singo,             ".
                  "       ".$TableOrder."    As TableOrder,       ".
                  "       bas_theather       As Theather,          ".
                  "       bas_silmooja       As Silmooja,          ".
                  "       bas_location       As Location           ".
                  " Where Singo.Silmooja   = Silmooja.Code         ".
                  "   And Singo.Theather   = Theather.Code         ".
                  "   And Singo.Location   = Location.Code         ".
                  "   And Theather.Code    = TableOrder.Theather   ".
                  $AddedCont."                                     ".
                  " Group By Singo.Theather,                       ".
                  "          Singo.Open,                           ".
                  "          Singo.Film                            ".
                  " Order By TableOrder.Seq                       " ; // Eq($sQuery) ;
        $QrySingo = mysql_query($sQuery,$connect);
        while  ($ArrSingo = mysql_fetch_array($QrySingo))
        {
             $singoTheather    = $ArrSingo["Theather"] ;      // �Ű�󿵰�
             $singoOpen        = $ArrSingo["Open"] ;          // �Ű�ȭ
             $singoFilm        = $ArrSingo["Film"] ;          //

             $showroomDiscript = $ArrSingo["Discript"] ;      // �Ű� �󿵰���
             $showroomLocation = $ArrSingo["Location"] ;      // �Ű� �󿵰�����

             $locationName     = $ArrSingo["LocationName"] ;  // �Ű� �󿵰�������


             //$TheatherRate  = get_theather_rate_value($WorkDate,$showroomLocation,$singoTheather,$singoOpen,$singoFilm,$connect) ;

			 $theather_rate_default = get_theather_rate_value_default($showroomLocation,$singoTheather,$singoOpen,$singoFilm,$connect) ; // �ش������ �ش��ʸ��� ����Ʈ ����
			 $TheatherRate = get_theather_rate_value_date($TblTheatherRate,$theather_rate_default,$WorkDate,$singoTheather,$singoOpen,$singoFilm,$connect) ;


             $RateValue = "T".$singoTheather ;
             if  ($$RateValue)
             {
                 $TheatherRate = $$RateValue ;    // ���� �Էµ� ����

                 $sQuery = "Update ".$TblTheatherRate."             ".
                           "   Set Rate     = '".$TheatherRate."'   ".
                           " Where WorkDate = '".$WorkDate."'       ".
                           "   And Theather = '".$singoTheather."'  ".
                           "   And Open     = '".$singoOpen."'      ".
                           "   And Film     = '".$singoFilm."'      " ;
                 mysql_query($sQuery,$connect);
             }


             $sQuery = "Update bas_theather_rate                ".
                       "   Set Rate = '".$TheatherRate."'       ".
                       " Where Theather = '".$singoTheather."'  ".
                       "   And Open     = '".$singoOpen."'      ".
                       "   And Film     = '".$singoFilm."'      " ;
             $QryTheatherRate = mysql_query($sQuery,$connect);
             ?>
             <tr>
                <th class="row" align=center><?=$singoTheather?></th>
                <td><?=$showroomDiscript?></td>
                <td align=center><input size=5 type="text" name=T<?=$singoTheather?> value="<?=$TheatherRate?>"  style="text-align:right;"></td>
             </tr>
             <?
        }
        ?>
        </table>

        <br>
        <br>

        <input type="hidden" name="Changing" value="">
        <input type="hidden" name="WorkDate" value="<?=$WorkDate?>">
        <input type="submit" value="Ȯ��"  onclick="return ChgTheatherRate()">



        </form>

        <br>
        <br>

        </center>



        <?
        mysql_close($connect);       // {[������ ���̽�]} : ����
    }
    else
    {
        ?>
        <!-- �α������� �ʰ� �ٷε��´ٸ� -->
        <script language="JavaScript">
            <!--
            window.top.location = '../index_net.php' ;
            //-->
        </script>
        <?
    }
?>




</body>

</html>
