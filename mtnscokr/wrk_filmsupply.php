<?
    session_start();

    // ���������� �α��� �ߴ��� üũ�Ѵ�.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = '../index_com.php'</script>";
    }
    else
    {
        include "config.php";

        $connect=dbconn();

        mysql_select_db($cont_db) ; // �ش��޻縦 ���ϰ�


        // �ڵ� ���ھ� ����
        // �ݵ�� ��޻�� ��ȭ�� 1���̻� �����Ͽ��� �ϰ�
        // �ǹ��� ���� ��޻簣 ������ ��ȭ�� �����Ͽ��� �ϸ�,
        // �������� �Ǿ� ȸ��������, ��������� �־�� �Ѵ�.
        if  ($AutoMakeScore)
        {
            // �ǹ��ڿ� ��޻簡 ��� �ִ� �󿵰����� ���γ����Ѵ�.
            $qry_silmoojafilmsupply = mysql_query("Select * From bas_silmoojatheather As silmooja,     ".
                                                  "              bas_filmsupplytitle  As filmsupply    ".
                                                  "Where silmooja.Open = filmsupply.Open               ".
                                                  "  And silmooja.Film = filmsupply.Film               ".
                                                  "  And filmsupply.FilmSupply = '".$filmsupplyCode."' ",$connect) ;

            while ($silmoojafilmsupply_data = mysql_fetch_array($qry_silmoojafilmsupply))
            {
                $Silmooja     = $silmoojafilmsupply_data["Silmooja"] ; // �ǹ����ڵ�
                $Theather     = $silmoojafilmsupply_data["Theather"] ; // �󿵰�
                $Room         = $silmoojafilmsupply_data["Room"] ;
                $silmoojaOpen = $silmoojafilmsupply_data["Open"] ;     // ��ȭ
                $silmoojaFilm = $silmoojafilmsupply_data["Film"] ;

                $sSingoName = get_singotable($silmoojaOpen,$silmoojaFilm,$connect) ;  // �Ű� ���̺� �̸�..
                $sAccName   = get_acctable($silmoojaOpen,$silmoojaFilm,$connect) ;    // accumulate �̸�..
                $sDgrName   = get_degree($silmoojaOpen,$silmoojaFilm,$connect) ;
                $sDgrpName  = get_degreepriv($silmoojaOpen,$silmoojaFilm,$connect) ;

                // �ǹ��ڰ� ��� �ִ� �󿵰����� �Ű�� ���� �ڷᰡ �����ϴ��� Ȯ���Ѵ�.
                $qry_cntSingo = mysql_query("Select Count(*) As cntSingo        ".
                                            "  From ".$sSingoName."             ".
                                            " Where Theather  = '".$Theather."' ".
                                            "   And Room      = '".$Room."'     ".
                                            "   And SingoDate = '".$WorkDate."' ",$connect) ;
                $cntSingo_data  = mysql_fetch_array($qry_cntSingo) ;
                if  ($cntSingo_data)
                {
                    if  ($cntSingo_data["cntSingo"] == 0) // �Ű��ڷᰡ ���°��
                    {
                        $qry_showroom = mysql_query("Select * From bas_showroom        ".
                                                    " Where Theather = '".$Theather."' ".
                                                    "   And Room     = '".$Room."'     ",$connect) ;
                        $showroom_data = mysql_fetch_array($qry_showroom) ;
                        if ($showroom_data)
                        {
                            $Location = $showroom_data["Location"] ;  // �󿵰� ����������
                        }

                        $existdegree = true ;

                        // ����ȸ�� ���翩��Ȯ�� ..
                        $qry_degreepriv = mysql_query("Select * From ".$sDgrpName."          ".
                                                      " Where Silmooja = '".$Silmooja."'     ".
                                                      "   And WorkDate = '".$WorkDate."'     ".
                                                      "   And Open     = '".$silmoojaOpen."' ".
                                                      "   And Film     = '".$silmoojaFilm."' ".
                                                      "   And Theather = '".$Theather."'     ".
                                                      "   And Room     = '".$Room."'         ",$connect) ;
                        $degreepriv_data  = mysql_fetch_array($qry_degreepriv) ;
                        if  (!$degreepriv_data)
                        {
                            $existdegree = false ;
                        }

                        // ������ ȸ����ŭ ....
                        $qry_temp = mysql_query("Select * From ".$sDgrName."              ".
                                                " Where Silmooja = '".$Silmooja."'     ".
                                                "   And Open     = '".$silmoojaOpen."' ".
                                                "   And Film     = '".$silmoojaFilm."' ".
                                                "   And Theather = '".$Theather."'     ".
                                                "   And Room     = '".$Room."'         ",$connect) ;
                        $temp_data  = mysql_fetch_array($qry_temp) ;

                        if  ($temp_data)
                        {
                            $qry_degree = mysql_query("Select * From ".$sDgrName."              ".
                                                      " Where Silmooja = '".$Silmooja."'     ".
                                                      "   And Open     = '".$silmoojaOpen."' ".
                                                      "   And Film     = '".$silmoojaFilm."' ".
                                                      "   And Theather = '".$Theather."'     ".
                                                      "   And Room     = '".$Room."'         ",$connect) ;
                        }
                        else
                        {
                            $qry_degree = mysql_query("Select * From ".$sDgrName."              ".
                                                      " Where Theather = '".$Theather."'     ".
                                                      "   And Room     = '".$Room."'         ",$connect) ;
                        }

                        while ($degree_data  = mysql_fetch_array($qry_degree))
                        {
                            $Degree         = $degree_data["Degree"] ;   // ������ ȸ��.
                            $degreeTime     = $degree_data["Time"] ;     // ������ �ð�.
                            $degreeDiscript = $degree_data["Discript"] ; // ������ �󿵰��̸�.

                            if  ($existdegree == false)
                            {
                                mysql_query("Insert Into ".$sDgrpName."     ".
                                            "Values (                       ".
                                            "         '".$Silmooja."',      ".
                                            "         '".$WorkDate."',      ".
                                            "         '".$silmoojaOpen."',  ".
                                            "         '".$silmoojaFilm."',  ".
                                            "         '".$Theather."',      ".
                                            "         '".$Room."',          ".
                                            "         '".$Degree."',        ".
                                            "         '".$degreeTime."',    ".
                                            "         '".$degreeDiscript."' ".
                                            "        )                      ",$connect) ;
                            }


                            $existunitprice = true ;

                            // ���ÿ�� ���翩��Ȯ�� ..
                            $qry_unitpricespriv = mysql_query("Select * From bas_unitpricespriv      ".
                                                              " Where Silmooja = '".$Silmooja."'     ".
                                                              "   And WorkDate = '".$WorkDate."'     ".
                                                              "   And Open     = '".$silmoojaOpen."' ".
                                                              "   And Film     = '".$silmoojaFilm."' ".
                                                              "   And Theather = '".$Theather."'     ".
                                                              "   And Room     = '".$Room."'         ",$connect) ;
                            $unitpricespriv_data  = mysql_fetch_array($qry_unitpricespriv) ;
                            if  (!$unitpricespriv_data)
                            {
                                $existunitprice = false ;
                            }

                            // ������ ��ݸ�ŭ .....
                            $qry_unitprices = mysql_query("Select * From bas_unitprices      ".
                                                          " Where Theather = '".$Theather."' ".
                                                          "   And Room     = '".$Room."'     ",$connect) ;
                            while ($unitprices_data  = mysql_fetch_array($qry_unitprices))
                            {
                                $UnitPrice         = $unitprices_data["UnitPrice"] ; // ������ ���.
                                $unitpriceDiscript = $unitprices_data["Discript"] ;  // ������ �󿵰��̸�.

                                if  ($existdegree == false)
                                {
                                    mysql_query("Insert Into bas_unitpricespriv    ".
                                                "Values (                          ".
                                                "         '".$Silmooja."',         ".
                                                "         '".$WorkDate."',         ".
                                                "         '".$silmoojaOpen."',     ".
                                                "         '".$silmoojaFilm."',     ".
                                                "         '".$Theather."',         ".
                                                "         '".$Room."',             ".
                                                "         '".$UnitPrice."',        ".
                                                "         '".$unitpriceDiscript."' ".
                                                "        )                         ",$connect) ;
                                }

                                //
                                // �Ű��ڷḦ ������ �����....
                                //
                                $qry_showroomorder = mysql_query("Select * From bas_showroomorder                ".
                                                                 " Where FilmSupply = '".$filmsupplyCode."'      ".
                                                                 "   And Theather   = '".$Theather."'            ".
                                                                 "   And Room       = '".$Room."'                ",$connect) ;
                                if  ($showroomorder_data = mysql_fetch_array($qry_showroomorder))
                                {
                                    $RoomOrder = $showroomorder_data["Seq"] ;
                                }
                                else
                                {
                                    $RoomOrder = -1 ;
                                }

                                mysql_query("Insert Into ".$sSingoName."        ".
                                            " Values (  '".date("YmdHis")."',   ".
                                            "           '".$WorkDate."',        ".
                                            "           '".$Silmooja."',        ".
                                            "           '".$filmsupplyCode."',  ".
                                            "           '".$Location."',        ".
                                            "           '".$Theather."',        ".
                                            "           '".$Room."',            ".
                                            "           '".$silmoojaOpen."',    ".
                                            "           '".$silmoojaFilm."',    ".
                                            "           '',          ".//////////// 9��5�� //////
                                            "           '".$Degree."',          ".
                                            "           '".$UnitPrice."',       ".
                                            "           '0',                    ".
                                            "           '0',                    ".
                                            "           '',                     ".
                                            "           '".$RoomOrder."'        ".
                                            " )                                 ",$connect) ;
                            }
                        }
                    }
                }
            }



            // �ǹ�������ó�������� ��޻簡 ��� �ִ� �󿵰����� ���γ����Ѵ�.
            $qry_silmoojafilmsupply = mysql_query("Select * From bas_silmoojatheatherfinish As silmoojafinish, ".
                                                  "              bas_filmsupplytitle  As filmsupply            ".
                                                  "Where silmoojafinish.Open = filmsupply.Open                 ".
                                                  "  And silmoojafinish.Film = filmsupply.Film                 ".
                                                  "  And filmsupply.FilmSupply = '".$filmsupplyCode."'         ",$connect) ;

            while ($silmoojafilmsupply_data = mysql_fetch_array($qry_silmoojafilmsupply))
            {
                $Silmooja     = $silmoojafilmsupply_data["Silmooja"] ; // �ǹ����ڵ�
                $Theather     = $silmoojafilmsupply_data["Theather"] ; // �󿵰�
                $Room         = $silmoojafilmsupply_data["Room"] ;
                $silmoojaOpen = $silmoojafilmsupply_data["Open"] ;     // ��ȭ
                $silmoojaFilm = $silmoojafilmsupply_data["Film"] ;

                // �ǹ��ڰ� ��� �ִ� �󿵰����� �Ű�� ���� �ڷᰡ �����ϴ��� Ȯ���Ѵ�.
                $qry_cntSingo = mysql_query("Select Count(*) As cntSingo        ".
                                            "  From ".$sSingoName."             ".
                                            " Where Theather  = '".$Theather."' ".
                                            "   And Room      = '".$Room."'     ".
                                            "   And SingoDate = '".$WorkDate."' ",$connect) ;
                $cntSingo_data  = mysql_fetch_array($qry_cntSingo) ;
                if  ($cntSingo_data)
                {
                    if  ($cntSingo_data["cntSingo"] == 0) // �Ű��ڷᰡ ���°��
                    {
                        $qry_showroom = mysql_query("Select * From bas_showroom        ".
                                                    " Where Theather = '".$Theather."' ".
                                                    "   And Room     = '".$Room."'     ",$connect) ;
                        $showroom_data = mysql_fetch_array($qry_showroom) ;
                        if ($showroom_data)
                        {
                            $Location = $showroom_data["Location"] ;  // �󿵰� ����������
                        }

                        $existdegree = true ;

                        // ����ȸ�� ���翩��Ȯ�� ..
                        $qry_degreepriv = mysql_query("Select * From ".$sDgrpName."          ".
                                                      " Where Silmooja = '".$Silmooja."'     ".
                                                      "   And WorkDate = '".$WorkDate."'     ".
                                                      "   And Open     = '".$silmoojaOpen."' ".
                                                      "   And Film     = '".$silmoojaFilm."' ".
                                                      "   And Theather = '".$Theather."'     ".
                                                      "   And Room     = '".$Room."'         ",$connect) ;
                        $degreepriv_data  = mysql_fetch_array($qry_degreepriv) ;
                        if  (!$degreepriv_data)
                        {
                            $existdegree = false ;
                        }

                        // ������ ȸ����ŭ ....
                        $qry_temp = mysql_query("Select * From ".$sDgrName."              ".
                                                  " Where Silmooja = '".$Silmooja."'     ".
                                                  "   And Open     = '".$silmoojaOpen."' ".
                                                  "   And Film     = '".$silmoojaFilm."' ".
                                                  "   And Theather = '".$Theather."'     ".
                                                  "   And Room     = '".$Room."'         ",$connect) ;
                        $temp_data  = mysql_fetch_array($qry_temp) ;

                        if  ($temp_data)
                        {
                            $qry_degree = mysql_query("Select * From ".$sDgrName."              ".
                                                      " Where Silmooja = '".$Silmooja."'     ".
                                                      "   And Open     = '".$silmoojaOpen."' ".
                                                      "   And Film     = '".$silmoojaFilm."' ".
                                                      "   And Theather = '".$Theather."'     ".
                                                      "   And Room     = '".$Room."'         ",$connect) ;
                        }
                        else
                        {
                            $qry_degree = mysql_query("Select * From ".$sDgrName."              ".
                                                      " Where Theather = '".$Theather."'     ".
                                                      "   And Room     = '".$Room."'         ",$connect) ;
                        }



                        while ($degree_data  = mysql_fetch_array($qry_degree))
                        {
                            $Degree         = $degree_data["Degree"] ;   // ������ ȸ��.
                            $degreeTime     = $degree_data["Time"] ;     // ������ �ð�.
                            $degreeDiscript = $degree_data["Discript"] ; // ������ �󿵰��̸�.

                            if  ($existdegree == false)
                            {
                                mysql_query("Insert Into ".$sDgrpName."     ".
                                            "Values (                       ".
                                            "         '".$Silmooja."',      ".
                                            "         '".$WorkDate."',      ".
                                            "         '".$silmoojaOpen."',  ".
                                            "         '".$silmoojaFilm."',  ".
                                            "         '".$Theather."',      ".
                                            "         '".$Room."',          ".
                                            "         '".$Degree."',        ".
                                            "         '".$degreeTime."',    ".
                                            "         '".$degreeDiscript."' ".
                                            "        )                      ",$connect) ;
                            }


                            $existunitprice = true ;

                            // ���ÿ�� ���翩��Ȯ�� ..
                            $qry_unitpricespriv = mysql_query("Select * From bas_unitpricespriv      ".
                                                              " Where Silmooja = '".$Silmooja."'     ".
                                                              "   And WorkDate = '".$WorkDate."'     ".
                                                              "   And Open     = '".$silmoojaOpen."' ".
                                                              "   And Film     = '".$silmoojaFilm."' ".
                                                              "   And Theather = '".$Theather."'     ".
                                                              "   And Room     = '".$Room."'         ",$connect) ;
                            $unitpricespriv_data  = mysql_fetch_array($qry_unitpricespriv) ;
                            if  (!$unitpricespriv_data)
                            {
                                $existunitprice = false ;
                            }

                            // ������ ��ݸ�ŭ .....
                            $qry_unitprices = mysql_query("Select * From bas_unitprices      ".
                                                          " Where Theather = '".$Theather."' ".
                                                          "   And Room     = '".$Room."'     ",$connect) ;
                            while ($unitprices_data  = mysql_fetch_array($qry_unitprices))
                            {
                                $UnitPrice         = $unitprices_data["UnitPrice"] ; // ������ ���.
                                $unitpriceDiscript = $unitprices_data["Discript"] ;  // ������ �󿵰��̸�.

                                if  ($existdegree == false)
                                {
                                    mysql_query("Insert Into bas_unitpricespriv    ".
                                                "Values (                          ".
                                                "         '".$Silmooja."',         ".
                                                "         '".$WorkDate."',         ".
                                                "         '".$silmoojaOpen."',     ".
                                                "         '".$silmoojaFilm."',     ".
                                                "         '".$Theather."',         ".
                                                "         '".$Room."',             ".
                                                "         '".$UnitPrice."',        ".
                                                "         '".$unitpriceDiscript."' ".
                                                "        )                         ",$connect) ;
                                }

                                //
                                // �Ű��ڷḦ ������ �����....
                                //
                                $qry_showroomorder = mysql_query("Select * From bas_showroomorder                ".
                                                                 " Where FilmSupply = '".$filmsupplyCode."'      ".
                                                                 "   And Theather   = '".$Theather."'            ".
                                                                 "   And Room       = '".$Room."'                ",$connect) ;
                                if  ($showroomorder_data = mysql_fetch_array($qry_showroomorder))
                                {
                                    $RoomOrder = $showroomorder_data["Seq"] ;
                                }
                                else
                                {
                                    $RoomOrder = -1 ;
                                }

                                mysql_query("Insert Into ".$sSingoName."        ".
                                            " Values (  '".date("YmdHis")."',   ".
                                            "           '".$WorkDate."',        ".
                                            "           '".$Silmooja."',        ".
                                            "           '".$filmsupplyCode."',  ".
                                            "           '".$Location."',        ".
                                            "           '".$Theather."',        ".
                                            "           '".$Room."',            ".
                                            "           '".$silmoojaOpen."',    ".
                                            "           '".$silmoojaFilm."',    ".
                                            "  '',          ".//////////// 9��5�� //////
                                            "           '".$Degree."',          ".
                                            "           '".$UnitPrice."',       ".
                                            "           '0',                    ".
                                            "           '0',                    ".
                                            "           ''    )                 ",$connect) ;
                            }
                        }
                    }
                }
            }
        }  // End of  if  ($AutoMakeScore)

        if  ($ClearAcc) // �޸� Ŭ����
        {
            $sQuery = "Delete From ".$sAccName." " ;
            mysql_query($sQuery,$connect) ;
        }
?>
<html>
  <link rel=stylesheet href=./style.css type=text/css>
  <META HTTP-EQUIV=Content-Type CONTENT="text/html; charset=euc-kr">

  <head>
  <title>��޻����</title>
  </head>

  <body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
     <? echo "<b>".$UserName . "</b>���� ȯ���մϴ�!" ; ?>
     <a href="../index_com.php?actcode=logout"><b>[LogOut]</b></a>
     <center>

        <table cellpadding=0 cellspacing=0 border=0>
        <tr height=25>
           <td align=left colspan=2>
           <ol>

             <li><b><a href="wrk_filmsupply_1.php?BackAddr=wrk_filmsupply.php">��ȸ���ڼ���</a></b></li>
                    <a href="wrk_filmsupply_1.php?BackAddr=wrk_filmsupply.php">(<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>)</a><br><br>
             <li><b><a href="wrk_filmsupply_2.php?BackAddr=wrk_filmsupply.php">������������</a></b></li><br><br>
             <li><b><a href="wrk_filmsupply_3.php?BackAddr=wrk_filmsupply.php">������������</a></b></li><br><br>
             <li><b><a OnClick="window.open('wrk_filmsupply_Gongji.php','','status=0,menubar=0, scrollbars=yes,resizable=yes,width=350,height=300')">�ǹ��ڰ����߼�</a></b></li><br><br>
             <li><b><a href="wrk_filmsupply_5.php?BackAddr=wrk_filmsupply.php">��ϵȽǹ���</a></b></li><br><br>
             <li><b><a href="wrk_filmsupply_S1.php?BackAddr=wrk_filmsupply.php">��ȭ����</a></b></li><br>
             <?
             // ��޻簡 ���õ� ��ȭ���� �����Ͽ� �������� �ǹ��ڸ� ������ �� �ֵ����Ѵ�.

             $qryFilmsupplytitle = mysql_query("Select * From bas_filmtitle ",$connect) ;
             while  ($filmsupplytitle_data = mysql_fetch_array($qryFilmsupplytitle))
             {
                 $Filmsupplytitle_Title = $filmsupplytitle_data["Name"] ;

                 $FilmTitle = $filmsupplytitle_data["Open"] . $filmsupplytitle_data["Code"] ;

                 ?>
                 <a OnClick="location.href='wrk_filmsupply_S2.php?FilmTitle=<?=$FilmTitle?>&BackAddr=wrk_filmsupply.php'"><?=$Filmsupplytitle_Title?></a>
                 <a OnClick="location.href='SmsChkTheather.php?FilmTitle=<?=$FilmTitle?>&logged_UserId=<?=$logged_UserId?>&logged_Name=<?=$logged_Name?>&BackAddr=wrk_filmsupply.php'">SMS</a>
                 <a OnClick="window.open('wrk_film_Gongji.php?FilmTitle=<?=$FilmTitle?>','','status=0,menubar=0, scrollbars=yes,resizable=yes,width=450,height=600')">����</a>
                 <br>
                 <?
             }
             ?>
             <br>
             <?

             $sQuery = "Select * From MofidyLimitDate " ;
             $qry_MdfLmtDat = mysql_query($sQuery,$connect) ;
             if  ($MdfLmtDat_data = mysql_fetch_array($qry_MdfLmtDat) )
             {
                 $MdfLmtDat = $MdfLmtDat_data["Value"] ;
             }
             else
             {
                 $MdfLmtDat = 0 ;
             }
             ?>
             <li> <b><a href="MofidyLimitDate.php?logged_UserId=<?=$logged_UserId?>&logged_Name=<?=$logged_Name?>&BackAddr=wrk_filmsupply.php">PHP�������� ����(<?=$MdfLmtDat?>)</a></b><br>
             <br>
             <li> <b><a href="<?=$PHP_SELF?>?ClearAcc=Yes">�޸� ����</a></b><br>
             <BR>

             <br><br>
           </ol>
           </td>
        </tr>
        </table>



        <b><a href="wrk_filmsupply_Link.php?logged_UserId=<?=$logged_UserId?>&WorkDate=<?=$WorkDate?>&BackAddr=wrk_filmsupply.php">���Ϻ���</a></b><br>



     </center>
  </body>

</html>

<?
    }

     mysql_close($connect);
?>