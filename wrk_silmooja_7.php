<?
    session_start();
?>
<html>
    <style type="text/css">
    .item {
        color : white;
    }
    </style>
<?
    // ���������� �α��� �ߴ��� üũ�Ѵ�.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = 'index_com.php'</script>";
    }
    else
    {
        include "config.php";

        $connect=dbconn();

        mysql_select_db($cont_db) ;

        $MdfLmtDat = get_mofidylimitdate($connect) ;

        // �ش�ǹ��ڸ� ���ϰ� ($silmoojaName) ..
        $sQuery = "Select * From bas_silmooja     ".
                  " Where UserId = '".$UserId."'  " ;
        $QrySilmooja = mysql_query($sQuery,$connect) ;
        if  ($ArySilmooja = mysql_fetch_array($QrySilmooja))
        {
            $silmoojaCode     = $ArySilmooja["Code"] ;   // �ǹ��� �ڵ�
            $silmoojaTheather = substr($ShowRoom,0,4) ;    // �󿵰� �ڵ�
            $silmoojaRoom     = substr($ShowRoom,4,2) ;

            // �ǹ��ڰ� ��� �ִ� �󿵰�-��ȭ ������ ���Ѵ�.
            $sQuery = "Select * From bas_silmoojatheatherpriv        ".
                      " Where Silmooja = '".$silmoojaCode."'         ".
                      "   And WorkDate = '".$WorkDate."'             ".
                      "   And Theather = '".$silmoojaTheather."'     ".
                      "   And Room     = '".$silmoojaRoom."'         " ;
            $query2 = mysql_query($sQuery,$connect) ;
            if  ($ArySilmoojatheather = mysql_fetch_array($query2))
            {
                $silmoojatheatherOpen = $ArySilmoojatheather["Open"] ; // ��ȭ�ڵ�
                $silmoojatheatherFilm = $ArySilmoojatheather["Film"] ; //
            }

            $sSingoName = get_singotable($silmoojatheatherOpen,$silmoojatheatherFilm,$connect) ;
            $sAccName   = get_acctable($silmoojatheatherOpen,$silmoojatheatherFilm,$connect) ;  // accumulate �̸�..
        }




        $Ago2Date = date("Ymd",$Today-((3600*24)*$MdfLmtDat)) ; //////////////////////((3600*24)*[�ϼ�])/////////////////////////////////////////

        $ToDate = date("Ymd",$Today) ; // ������ ���� (�����ڷ�� ��)

        // �Ϸ� ������ ���Ѵ�.
        $AgoDate = date("Ymd",strtotime("-1 day",strtotime(substr($WorkDate,0,4)."-".substr($WorkDate,4,2)."-".substr($WorkDate,6,2).""))) ;



        if  ($ActionCode=="Delete") // �� �󿵰��� �Ű�� ��ü�� �����Ѵ�.
        {
            // �ش�ǹ��ڰ� ��� �ִ� ��ȭ�� ���Ѵ�.
            $sQuery = "Select * From bas_silmoojatheatherpriv        ".
                      " Where Silmooja = '".$silmoojaCode."'         ".
                      "   And WorkDate = '".$WorkDate."'             ".
                      "   And Theather = '".substr($ShowRoom,0,4)."' ".
                      "   And Room     = '".substr($ShowRoom,4,2)."' " ;
            $QrySilmoojatheather = mysql_query($sQuery,$connect) ;
            if  ($ArySilmoojatheather = mysql_fetch_array($QrySilmoojatheather))
            {
                $silmoojatheatherOpen = $ArySilmoojatheather["Open"] ;
                $silmoojatheatherFilm = $ArySilmoojatheather["Film"] ;
            }

            $sQuery = "Delete From bas_silmoojatheatherpriv           ".
                      " Where WorkDate = '".$WorkDate."'              ".
                      "   And Silmooja = '".$silmoojaCode."'          ".
                      "   And Theather = '".substr($ShowRoom,0,4)."'  ".
                      "   And Room     = '".substr($ShowRoom,4,2) ."' " ;
            mysql_query($sQuery,$connect) ;

            $sQuery = "Delete From bas_silmoojatheather                ".
                      " Where Silmooja  = '".$silmoojaCode."'          ".
                      "   And Theather  = '".substr($ShowRoom,0,4)."'  ".
                      "   And Room      = '".substr($ShowRoom,4,2) ."' " ;
            mysql_query($sQuery,$connect) ;

            $sQuery = "Delete From ".$sSingoName."                     ".
                      " Where SingoDate = '".$WorkDate."'              ".
                      "   And Silmooja  = '".$silmoojaCode."'          ".
                      "   And Theather  = '".substr($ShowRoom,0,4)."'  ".
                      "   And Room      = '".substr($ShowRoom,4,2)."'  ".
                      "   And Open      = '".$silmoojatheatherOpen."'  ".
                      "   And Film      = '".$silmoojatheatherFilm."'  " ;
            mysql_query($sQuery,$connect) ;

            echo "<script>location.href='".$BackAddr."?SangTheather=".$SangTheather."'</script>";
        }

        //
        // ���� ����
        //
        if  ($ActionCode=="Magam")
        {
            // ���� �ڷ� ���θ� �˻�
            $sQuery = "Select Count(*) As CntMagam                   ".
                      "  From wrk_magam                              ".
                      " Where WorkDate = '".$WorkDate."'             ".
                      "   And Theather = '".substr($ShowRoom,0,4)."' ".
                      "   And Room     = '".substr($ShowRoom,4,2)."' ".
                      "   And Open     = '".$silmoojatheatherOpen."' ".
                      "   And Film     = '".$silmoojatheatherFilm."' " ;
            $QryMagam = mysql_query($sQuery,$connect) ;
            if  ($ArrMagam = mysql_fetch_array($QryMagam))
            {
                if  ($ArrMagam["CntMagam"] > 0) // �����ڷᰡ �ִٸ�...
                {
                    $sQuery = "Delete From wrk_magam                         ".
                              " Where Silmooja = '".$silmoojaCode."'         ".
                              "   And WorkDate = '".$WorkDate."'             ".
                              "   And Theather = '".substr($ShowRoom,0,4)."' ".
                              "   And Room     = '".substr($ShowRoom,4,2)."' ".
                              "   And Open     = '".$silmoojatheatherOpen."' ".
                              "   And Film     = '".$silmoojatheatherFilm."' " ;
                    mysql_query($sQuery,$connect) ;
                }
                else                            // �����ڷᰡ ���ٸ�..
                {
                    $sQuery = "Insert Into wrk_magam           ".
                              "Values                          ".
                              "(                               ".
                              "   '".$silmoojaCode."',         ".
                              "   '".$WorkDate."',             ".
                              "   '".substr($ShowRoom,0,4)."', ".
                              "   '".substr($ShowRoom,4,2)."', ".
                              "   '".$silmoojatheatherOpen."', ".
                              "   '".$silmoojatheatherFilm."', ".
                              "   '".date("His")."'            ".
                              ")                               " ;
                    mysql_query($sQuery,$connect) ;
                }
            }
        }

        // �ش�ǹ��ڸ� ���ϰ� ($silmoojaName) ..
        $sQuery = "Select * From bas_silmooja     ".
                  " Where UserId = '".$UserId."'  " ;
        $QrySilmooja = mysql_query($sQuery,$connect) ;
        if  ($ArySilmooja = mysql_fetch_array($QrySilmooja))
        {
            $silmoojaCode     = $ArySilmooja["Code"] ;   // �ǹ��� �ڵ�
            $silmoojaUserId   = $ArySilmooja["UserId"] ; // ����� ���̵�
            $silmoojaName     = $ArySilmooja["Name"] ;   // �ǹ����̸�
            $silmoojaTheather = substr($ShowRoom,0,4) ;    // �󿵰� �ڵ�
            $silmoojaRoom     = substr($ShowRoom,4,2) ;

            // �ǹ��ڰ� ��� �ִ� �󿵰�-��ȭ ������ ���Ѵ�.
            $sQuery = "Select * From bas_silmoojatheatherpriv        ".
                      " Where Silmooja = '".$silmoojaCode."'         ".
                      "   And WorkDate = '".$WorkDate."'             ".
                      "   And Theather = '".$silmoojaTheather."'     ".
                      "   And Room     = '".$silmoojaRoom."'         " ;
            $query2 = mysql_query($sQuery,$connect) ;
            if  ($ArySilmoojatheather = mysql_fetch_array($query2))
            {
                $silmoojatheatherOpen = $ArySilmoojatheather["Open"] ; // ��ȭ�ڵ�
                $silmoojatheatherFilm = $ArySilmoojatheather["Film"] ; //

                $sDgrName   = get_degree($silmoojatheatherOpen,$silmoojatheatherFilm,$connect) ;
                $sDgrpName  = get_degreepriv($silmoojatheatherOpen,$silmoojatheatherFilm,$connect) ;

                // ��ȭ�� ��� �ִ� ��޻縦 ���Ѵ�.
                $sQuery = "Select * From bas_filmtitle               ".
                          " Where Open = '".$silmoojatheatherOpen."' ".
                          "   And Code = '".$silmoojatheatherFilm."' " ;
                $QryFilmtitle = mysql_query($sQuery,$connect) ;
                if  ($AryFilmtitle = mysql_fetch_array($QryFilmtitle))
                {
                    $filmtitleName = $AryFilmtitle["Name"] ;
                }
                else
                {
                    $filmtitleName = "��ȭ[��޻�]��������" ;
                }

                // �ǹ��ڰ� �İߵ� �󿵰�..
                $sQuery = "Select * From bas_showroom                ".
                          " Where Theather = '".$silmoojaTheather."' ".
                          "   And Room     = '".$silmoojaRoom."'     " ;
                $query2 = mysql_query($sQuery,$connect) ;
                if  ($AryShowroom = mysql_fetch_array($query2))
                {
                    $showroomDiscript = $AryShowroom["Discript"] ;
                    $showroomLocation = $AryShowroom["Location"] ;
                    $showroomSeat     = $AryShowroom["Seat"] ;
                    //$showroomFilmSupply = $AryShowroom["FilmSupply"] ;

                    // �󿵰��� ������ ������ ���Ѵ�. ($locationName)
                    $sQuery = "Select * From bas_location            ".
                              " Where Code = '".$showroomLocation."' " ;
                    $query3 = mysql_query($sQuery,$connect) ;
                    if  ($AryLocation = mysql_fetch_array($query3))
                    {
                        $locationName = $AryLocation["Name"] ;
                    }
                    else
                    {
                        $locationName = "��������" ;
                    }
                }
            }
            else
            {
                $sQuery = "Select * From bas_silmoojatheather      ".
                          " Where Silmooja  = '".$silmoojaCode."'  " ;
                $QrySilmothet = mysql_query($sQuery,$connect) ;
                while ($ArySilmothet = mysql_fetch_array($QrySilmothet))
                {
                     $sQuery = "Insert Into bas_silmoojatheatherpriv        ".  // �ǹ��� �󿵰����� ����
                               "Values (                                    ".
                               "         '".$silmoojaCode."',               ".
                               "         '".$WorkDate."',                   ".
                               "         '".$silmoojaTheather."',           ".
                               "         '".$silmoojaRoom."',               ".
                               "         '".$ArySilmothet["Open"]."',     ".
                               "         '".$ArySilmothet["Film"]."',     ".
                               "         '".$ArySilmothet["Name"]."',     ".
                               "         '".$ArySilmothet["Showroom"]."', ".
                               "         '".$ArySilmothet["Title"]."'     ".
                               "        )                                   " ;
                     mysql_query($sQuery,$connect) ;
                }

                //echo "<script>alert('�������� ���� �󿵰��Դϴ�.".$WorkDate."���� �������� �ѹ��� �ϼ���.');</script>" ;
                //echo "<script language='JavaScript'>window.location = 'index_com.php'</script>";
            }

            // ����ȸ�� ���翩��Ȯ�� ..
            $sQuery = "Select * From ".$sDgrpName."                  ".
                      " Where Silmooja = '".$silmoojaCode."'         ".
                      "   And WorkDate = '".$WorkDate."'             ".
                      "   And Open     = '".$silmoojatheatherOpen."' ".
                      "   And Film     = '".$silmoojatheatherFilm."' ".
                      "   And Theather = '".$silmoojaTheather."'     ".
                      "   And Room     = '".$silmoojaRoom."'         " ;
            $QryDegreepriv = mysql_query($sQuery,$connect) ;
            $AryDegreepriv  = mysql_fetch_array($QryDegreepriv) ;
            if  (!$AryDegreepriv) // ���� ȸ�� ������ ���ٸ�..
            {
                $sQuery = "Select * From ".$sDgrName."                      ".
                          " Where Silmooja = '".$silmoojaCode."'         ".
                          "   And Open     = '".$silmoojatheatherOpen."' ".
                          "   And Film     = '".$silmoojatheatherFilm."' ".
                          "   And Theather = '".$silmoojaTheather."'     ".
                          "   And Room     = '".$silmoojaRoom."'         " ;
                $QryTemp = mysql_query($sQuery,$connect) ;
                if  ($AryTemp  = mysql_fetch_array($QryTemp))
                {
                    $sQuery = "Select * From ".$sDgrName."                      ".
                              " Where Silmooja = '".$silmoojaCode."'         ".
                              "   And Open     = '".$silmoojatheatherOpen."' ".
                              "   And Film     = '".$silmoojatheatherFilm."' ".
                              "   And Theather = '".$silmoojaTheather."'     ".
                              "   And Room     = '".$silmoojaRoom."'         " ;
                    $QryDegree = mysql_query($sQuery,$connect) ;
                }
                else
                {
                    $sQuery = "Select * From ".$sDgrName."                      ".
                              " Where Theather = '".$silmoojaTheather."'     ".
                              "   And Room     = '".$silmoojaRoom."'         " ;
                    $QryDegree = mysql_query($sQuery,$connect) ;
                }

                while ($AryDegree  = mysql_fetch_array($QryDegree))
                {
                    // ������ ȸ�������� �����.

                    $Degree         = $AryDegree["Degree"] ;   // ������ ȸ��.
                    $degreeTime     = $AryDegree["Time"] ;     // ������ �ð�.
                    $degreeDiscript = $AryDegree["Discript"] ; // ������ �󿵰��̸�.

                    $sQuery = "Insert Into ".$sDgrpName."        ".
                              "Values                            ".
                              "(                                 ".
                              "    '".$silmoojaCode."',          ".
                              "    '".$WorkDate."',              ".
                              "    '".$silmoojatheatherOpen."',  ".
                              "    '".$silmoojatheatherFilm."',  ".
                              "    '".$silmoojaTheather."',      ".
                              "    '".$silmoojaRoom."',          ".
                              "    '".$Degree."',                ".
                              "    '".$degreeTime."',            ".
                              "    '".$degreeDiscript."'         ".
                              ")                                 " ;
                    mysql_query($sQuery,$connect) ;
                }
            }

            // �󿵰� ȸ�������� ���ϰ� ($arryDegree[],$arryTime[])
            $sQuery = "Select * From ".$sDgrpName."                  ".
                      " Where Silmooja = '".$silmoojaCode."'         ".
                      "   And WorkDate = '".$WorkDate."'             ".
                      "   And Open     = '".$silmoojatheatherOpen."' ".
                      "   And Film     = '".$silmoojatheatherFilm."' ".
                      "   And Theather = '".$silmoojaTheather."'     ".
                      "   And Room     = '".$silmoojaRoom."'         ".
                      " Order By Degree                              " ;
            $QryDegreepriv = mysql_query($sQuery,$connect) ;
            while ($AryDegreepriv = mysql_fetch_array($QryDegreepriv))
            {
                 // ������ 1~9ȸ, �ɾ� �� �Ѵ�.
                 if  (($AryDegreepriv["Degree"] == "99")
                      && ($AryDegreepriv["Degree"] <= "09")
                      && ($AryDegreepriv["Degree"] >= "01"))
                 {
                     $arryDegree[] = $AryDegreepriv["Degree"] ; // ȸ��
                     $arryTime[]   = $AryDegreepriv["Time"] ;   // �ð�
                     $arrySend[]   = "" ; // ���۽ð�
                 }
                 else
                 {
                     $arryDegree[] = $AryDegreepriv["Degree"] ; // ȸ��
                     $arryTime[]   = $AryDegreepriv["Time"] ;   // �ð�
                     $arrySend[]   = "" ; // ���۽ð�
                 }
            }

            // ������� ���� ���翩��Ȯ�� ..
            $sQuery = "Select * From bas_unitpricespriv              ".
                      " Where Silmooja = '".$silmoojaCode."'         ".
                      "   And WorkDate = '".$WorkDate."'             ".
                      "   And Open     = '".$silmoojatheatherOpen."' ".
                      "   And Film     = '".$silmoojatheatherFilm."' ".
                      "   And Theather = '".$silmoojaTheather."'     ".
                      "   And Room     = '".$silmoojaRoom."'         " ;
            $QryDegreepriv = mysql_query($sQuery,$connect) ;
            $AryDegreepriv  = mysql_fetch_array($QryDegreepriv) ;
            if  (!$AryDegreepriv)
            {
                $sQuery = "Select * From bas_unitprices              ".
                          " Where Theather = '".$silmoojaTheather."' ".
                          "   And Room     = '".$silmoojaRoom."'     " ;
                $QryDegree = mysql_query($sQuery,$connect) ;
                while ($AryDegree  = mysql_fetch_array($QryDegree))
                {
                    $UnitPrice      = $AryDegree["UnitPrice"] ;  // ������ ���
                    $degreeDiscript = $AryDegree["Discript"] ;   // ������ �󿵰��̸�.

                    $sQuery = "Insert Into bas_unitpricespriv  ".
                              "Values                          ".
                              "(                               ".
                              "  '".$silmoojaCode."',          ".
                              "  '".$WorkDate."',              ".
                              "  '".$silmoojatheatherOpen."',  ".
                              "  '".$silmoojatheatherFilm."',  ".
                              "  '".$silmoojaTheather."',      ".
                              "  '".$silmoojaRoom."',          ".
                              "  '".$UnitPrice."',             ".
                              "  '".$degreeDiscript."'         ".
                              ")                               " ;
                    mysql_query($sQuery,$connect) ;
                }
            }
            // ��� ���ݴ븦 ���Ѵ�. ($arryUnitPrice[])
            $sQuery = "Select * From bas_unitpricespriv              ".
                      " Where Silmooja = '".$silmoojaCode."'         ".
                      "   And WorkDate = '".$WorkDate."'             ".
                      "   And Open     = '".$silmoojatheatherOpen."' ".
                      "   And Film     = '".$silmoojatheatherFilm."' ".
                      "   And Theather = '".$silmoojaTheather."'     ".
                      "   And Room     = '".$silmoojaRoom."'         ".
                      " Order By UnitPrice Desc                      " ;
            $QryUnitpricespriv = mysql_query($sQuery,$connect) ;

            while ($AryUnitpricespriv = mysql_fetch_array($QryUnitpricespriv))
            {
                 $arryUnitPrice[] = $AryUnitpricespriv["UnitPrice"] ;
            }
        }
        else
        {
            $singoDataAll = "" ; //�Ű���Ÿ�� �����ϰ� ����������.

            mysql_close($connect);

            echo "<script language='JavaScript'>window.location = 'index_com.php'</script>";
        }


        if ((!$silmoojaUserId) || ($silmoojaUserId==""))
        {
            mysql_close($connect);

            echo "<script language='JavaScript'>window.location = 'index_com.php'</script>";
        }


        if  ($selectDegree != "")
        {
            if  (($UserId           != "") &&
                 ($WorkDate         != "") &&
                 ($silmoojaCode     != "") &&
                 ($silmoojaTheather != "") &&
                 ($silmoojaRoom     != ""))
            {
                  $ShowDegree = sprintf("%02d",$selectDegree) ;
                  $sQuery = "Delete From ".$sSingoName."                     ".
                            " Where SingoDate = '".$WorkDate."'              ".
                            "   And Silmooja  = '".$silmoojaCode."'          ".
                            "   And Theather  = '".$silmoojaTheather."'      ".
                            "   And Room      = '".$silmoojaRoom."'          ".
                            "   And ShowDgree = '".$ShowDegree."'            ".
                            "   And Open      = '".$silmoojatheatherOpen."'  ".
                            "   And Film      = '".$silmoojatheatherFilm."'  " ;
                  mysql_query($sQuery,$connect) ;
            }

            $sShowroomorder = get_showroomorder($silmoojatheatherOpen,$silmoojatheatherFilm,$connect) ;

            $Tmp = "singoData".$ShowDegree ;
            $sTemp1 = $$Tmp ;

            while (($i = strpos($sTemp1,'.')) > 0)
            {
                $sItem1 = substr($sTemp1,0,$i) ;


                $nCount = 0 ;

                $sTemp2 = $sItem1 ;

                while (($j = strpos($sTemp2 ,',')) > 0)
                {
                    $nCount++ ;

                    $sItem2 = substr($sTemp2,0,$j) ;

                    if  ($nCount==1)  $singoDegree     = $sItem2 ;  // ȸ�� ����
                    if  ($nCount==2)  $singoPrice      = $sItem2 ;  // ��� ����
                    if  ($nCount==3)  $singoNumPerson  = $sItem2 ;  // ���ھ�

                    $sTemp2 = substr($sTemp2,$j+1) ;
                }

                if  (($UserId               != "") &&
                     ($WorkDate             != "") &&
                     ($silmoojaCode         != "") &&
                     ($showroomLocation     != "") &&
                     ($silmoojaTheather     != "") &&
                     ($silmoojaRoom         != "") &&
                     ($silmoojatheatherOpen != "") &&
                     ($silmoojatheatherFilm != "") &&
                     ($singoDegree          != "") &&
                     ($singoPrice           != "") &&
                     ($singoNumPerson       != ""))
                {
					 $GikumRate = 1.03 ;
					 $sQuery = "SELECT IF( '".$WorkDate."' >=  '20160523', GikumRate,  1.03 ) GikumRate ".
					 		   "  FROM bas_theather                                                     ".
					 		   " WHERE code = ".$silmoojaTheather."                                     " ; //echo $sQuery ;
		 			 $qry_GikumRate = mysql_query($sQuery,$connect) ;
					 if  ($AryGikumRate = mysql_fetch_array($qry_GikumRate))
					 {
					 	 $GikumRate	= $AryGikumRate["GikumRate"]; // = 1.03
				 	 }

                     $sQuery = "Select * From ".$sShowroomorder."              ".
                               " Where Theather   = '".$silmoojaTheather."'    ".
                               "   And Room       = '".$silmoojaRoom."'        " ;
                     $QryShowroomorder = mysql_query($sQuery,$connect) ;
                     if  ($AryShowroomorder = mysql_fetch_array($QryShowroomorder))
                     {
                         $RoomOrder = $AryShowroomorder["Seq"] ;
                     }
                     else
                     {
                         $RoomOrder = -1 ;
                     }

                     if  ($singoPrice >= $MinPrice) // �ּ� �ݾ�.
                     {
                         $sQuery = "Insert Into ".$sSingoName."             ".
                                   "Values                                  ".
                                   "(                                       ".
                                   "  '".date("YmdHis")."',                 ".
                                   "  '".$WorkDate."',                      ".
                                   "  '".$silmoojaCode."',                  ".
                                   "  '".$showroomLocation."',              ".
                                   "  '".$silmoojaTheather."',              ".
                                   "  '".$silmoojaRoom."',                  ".
                                   "  '".$silmoojatheatherOpen."',          ".
                                   "  '".$silmoojatheatherFilm."',          ".
                                   "  '".$FilmType."',                      ".//////////// 9��5�� //////
                                   "  '".$singoDegree."',                   ".
                                   "  '".$singoPrice."',                    ".
                                   "  '".$singoNumPerson."',                ".
                                   "  '".$singoPrice * $singoNumPerson."',  ".
                                   "  '".get_GikumAount2($singoPrice,$GikumRate,$singoNumPerson)."', ".
                                   "  '',                                   ".
                                   "  '".$RoomOrder."'                      ".
                                   ")                                       " ;
//echo  $sQuery."<BR>" ;
                         mysql_query($sQuery,$connect) ;
                     }
                }

                $sTemp1 = substr($sTemp1,$i+1) ;
            }

            if  (($UserId           != "") &&
                 ($WorkDate         != "") &&
                 ($silmoojaCode     != "") &&
                 ($silmoojaTheather != "") &&
                 ($silmoojaRoom     != ""))
            {
                $sOpenTime = "00:00:00" ;

                $sQuery = "Select Time From ".$sDgrpName."               ".
                          " Where Silmooja = '".$silmoojaCode."'         ".
                          "   And WorkDate = '".$WorkDate."'             ".
                          "   And Open     = '".$silmoojatheatherOpen."' ".
                          "   And Film     = '".$silmoojatheatherFilm."' ".
                          "   And Theather = '".$silmoojaTheather."'     ".
                          "   And Room     = '".$silmoojaRoom."'         ".
                          "   And Degree   = '".$singoDegree."'          " ;
                $QryDegreeTime = mysql_query($sQuery,$connect) ;
                if  ($ArrDegreeTime = mysql_fetch_array($QryDegreeTime))
                {
                    $sOpenHoure = (string)SubStr($ArrDegreeTime["Time"],0,2) ;
                    $sOpenMinut = (string)SubStr($ArrDegreeTime["Time"],2,2) ;

                    if   ($sOpenHoure == "24")  $sOpenHoure = (string)"00" ;  // �ð�����
                    if   ($sOpenHoure == "25")  $sOpenHoure = (string)"01" ;
                    if   ($sOpenHoure == "26")  $sOpenHoure = (string)"02" ;
                    if   ($sOpenHoure == "27")  $sOpenHoure = (string)"03" ;
                    if   ($sOpenHoure == "28")  $sOpenHoure = (string)"04" ;
                    if   ($sOpenHoure == "29")  $sOpenHoure = (string)"05" ;
                    if   ($sOpenHoure == "30")  $sOpenHoure = (string)"06" ;

                    $sOpenTime  = $sOpenHoure . ":" . $sOpenMinut . ":00" ;
                }
                $sSendHoure = date("H") ;
                $sSendMinut = date("i") ;
                $sSendTime = $sSendHoure.":".$sSendMinut.":00" ;

                if  ($singoDegree=="99") // �ɾ� �ΰ�� ..
                {
                    $nGapTime = (strtotime("$sSendTime") - strtotime("$sOpenTime")) / 60 ; // �󿵽ð��� ���۽ð��� ���̸� ������

                    if  ( (($sOpenHoure >= "22") && ($sOpenHoure <= "23")) && (($sSendHoure >= "00") && ($sSendHoure <= "07")) )
                    {
                        $nGapTime = $nGapTime + (24*60) ;
                    }
                }
                else
                {

                    $nGapTime = (strtotime("$sSendTime") - strtotime("$sOpenTime")) / 60 ; // �󿵽ð��� ���۽ð��� ���̸� ������
                }

                $sQuery = "Delete From wrk_silmoosiljuk                    ".
                          " Where Code     = '".$silmoojaCode."'           ".
                          "   And WorkDate = '".$WorkDate."'               ".
                          "   And Theather = '".$silmoojaTheather."'       ".
                          "   And Room     = '".$silmoojaRoom."'           ".
                          "   And Degree   = '".$singoDegree."'            ".
                          "   And Open     = '".$silmoojatheatherOpen."'   ".
                          "   And Film     = '".$silmoojatheatherFilm."'   " ;
                mysql_query($sQuery,$connect) ;

                $sQuery = "Insert Into wrk_silmoosiljuk             ".
                          "Values                                   ".
                          "(                                        ".
                          "      '".$silmoojaCode."',               ".
                          "      '".$WorkDate."',                   ".
                          "      '".$silmoojaTheather."',           ".
                          "      '".$silmoojaRoom."',               ".
                          "      '".$singoDegree."',                ".
                          "      '".$silmoojatheatherOpen."',       ".
                          "      '".$silmoojatheatherFilm."',       ".
                          "      '".$silmoojaName."',               ".
                          "      '".$sOpenHoure.":".$sOpenMinut."', ".
                          "      '".$sSendHoure.":".$sSendMinut."', ".
                          "       ".$nGapTime."                     ".
                          ")                                        " ;
                mysql_query($sQuery,$connect) ;

                $sQuery = "Update ".$sSingoName."                          ".
                          "   Set FilmType  = '".$FilmType."'              ".//////////// 9��5�� //////
                          " Where SingoDate = '".$WorkDate."'              ".
                          "   And Silmooja  = '".$silmoojaCode."'          ".
                          "   And Theather  = '".$silmoojaTheather."'      ".
                          "   And Room      = '".$silmoojaRoom."'          ".
                          //"   And ShowDgree = '".$ShowDegree."'            ".
                          "   And Open      = '".$silmoojatheatherOpen."'  ".
                          "   And Film      = '".$silmoojatheatherFilm."'  " ;
//echo  $sQuery."<BR>" ;
                mysql_query($sQuery,$connect) ;
            }
        }

        //
        // �Ű� ����Ÿ�� �ִ°�� (���簪..)
        //
        //

        if  ($singoDataAll != "") //
        {
            //
            // ���������� �����ͺ��̽��� �����͸� �Է��Ѵ�.
            //

            $sQuery = "Select * From bas_filmsupplyzoneloc      ".
                      " Where Location = '".$showroomLocation."'" ;
            $QryFilmsupplyzoneloc = mysql_query($sQuery,$connect) ;
            if  ($AryFilmsupplyzoneloc = mysql_fetch_array($QryFilmsupplyzoneloc))
            {
                $ZoneCode = $AryFilmsupplyzoneloc["Zone"] ;
            }

            if  ($ZoneCode == "00")
            {
                $ZoneName = $locationName ;
            }
            else
            {
                $sQuery = "Select * From bas_zone        ".
                          " Where Code = '".$ZoneCode."' " ;
                $QryZone = mysql_query($sQuery,$connect) ;
                if  ($AryZone = mysql_fetch_array($QryZone))
                {
                    $ZoneName = $AryZone["Name"] ;
                }
            }

            $showroomCntDgree = count($arryDegree) ;

            // �� ���¼��� = ȸ���� * �󿵰� �ڸ���
            $showroomTotDgree = $showroomCntDgree * $showroomSeat ;

            if  ($showroomTotDgree==0)
            {
                $rateSeat = "(0%)" ;
            }
            else
            {
                if  ($SumNumPersons > 0)
                {
                    // ������ = ( �� ���ھ� / �� ���¼��� ) * 100 [%]  $SumNumPersons
                    $rateSeat = "(".round($SumNumPersons/$showroomTotDgree*100.0)."%)" ;
                }
                else
                {
                    $rateSeat = "(0%)" ;
                }
            }

            $rowSpan = count($arryUnitPrice) + 2 ;



            if  (($UserId           != "") &&
                 ($WorkDate         != "") &&
                 ($silmoojaCode     != "") &&
                 ($silmoojaTheather != "") &&
                 ($silmoojaRoom     != ""))
            {
                  $sQuery = "Delete From ".$sSingoName."                     ".
                            " Where SingoDate = '".$WorkDate."'              ".
                            "   And Silmooja  = '".$silmoojaCode."'          ".
                            "   And Theather  = '".$silmoojaTheather."'      ".
                            "   And Room      = '".$silmoojaRoom."'          ".
                            "   And Open      = '".$silmoojatheatherOpen."'  ".
                            "   And Film      = '".$silmoojatheatherFilm."'  " ;
                  mysql_query($sQuery,$connect) ;
            }

            $sTemp1 = $singoDataAll ;

            $sShowroomorder = get_showroomorder($silmoojatheatherOpen,$silmoojatheatherFilm,$connect) ;

            while (($i = strpos($sTemp1,'.')) > 0)
            {
                $sItem1 = substr($sTemp1,0,$i) ;


                $nCount = 0 ;

                $sTemp2 = $sItem1 ;

                while (($j = strpos($sTemp2 ,',')) > 0)
                {
                    $nCount++ ;

                    $sItem2 = substr($sTemp2,0,$j) ;

                    if  ($nCount==1)  $singoDegree     = $sItem2 ;  // ȸ�� ����
                    if  ($nCount==2)  $singoPrice      = $sItem2 ;  // ��� ����
                    if  ($nCount==3)  $singoNumPerson  = $sItem2 ;  // ���ھ�

                    $sTemp2 = substr($sTemp2,$j+1) ;
                }

                if  (($UserId               != "") &&
                     ($WorkDate             != "") &&
                     ($silmoojaCode         != "") &&
                     ($showroomLocation     != "") &&
                     ($silmoojaTheather     != "") &&
                     ($silmoojaRoom         != "") &&
                     ($silmoojatheatherOpen != "") &&
                     ($silmoojatheatherFilm != "") &&
                     ($singoDegree          != "") &&
                     ($singoPrice           != "") &&
                     ($singoNumPerson       != ""))
                {
					 $GikumRate = 1.03 ;
					 $sQuery = "SELECT IF( '".$WorkDate."' >=  '20160523', GikumRate,  1.03 ) GikumRate ".
					 		   "  FROM bas_theather                                                     ".
					 		   " WHERE code = ".$silmoojaTheather."                                     " ; //echo $sQuery ;
		 			 $qry_GikumRate = mysql_query($sQuery,$connect) ;
					 if  ($AryGikumRate = mysql_fetch_array($qry_GikumRate))
					 {
					 	 $GikumRate	= $AryGikumRate["GikumRate"]; // = 1.03
				 	 }

                     $sQuery = "Select * From ".$sShowroomorder."              ".
                               " Where Theather   = '".$silmoojaTheather."'    ".
                               "   And Room       = '".$silmoojaRoom."'        " ;
                     $QryShowroomorder = mysql_query($sQuery,$connect) ;
                     if  ($AryShowroomorder = mysql_fetch_array($QryShowroomorder))
                     {
                         $RoomOrder = $AryShowroomorder["Seq"] ;
                     }
                     else
                     {
                         $RoomOrder = -1 ;
                     }

                     if  ($singoPrice >= $MinPrice) // �ּ� �ݾ�.
                     {
                         $sQuery = "Insert Into ".$sSingoName."             ".
                                   "Values                                  ".
                                   "(                                       ".
                                   "  '".date("YmdHis")."',                 ".
                                   "  '".$WorkDate."',                      ".
                                   "  '".$silmoojaCode."',                  ".
                                   "  '".$showroomLocation."',              ".
                                   "  '".$silmoojaTheather."',              ".
                                   "  '".$silmoojaRoom."',                  ".
                                   "  '".$silmoojatheatherOpen."',          ".
                                   "  '".$silmoojatheatherFilm."',          ".
                                   "  '".$FilmType."',                      ".//////////// 9��5�� //////
                                   "  '".$singoDegree."',                   ".
                                   "  '".$singoPrice."',                    ".
                                   "  '".$singoNumPerson."',                ".
                                   "  '".$singoPrice * $singoNumPerson."',  ".
                                   "  '".get_GikumAount2($singoPrice,$GikumRate,$singoNumPerson)."', ".
                                   "  '',                                   ".
                                   "  '".$RoomOrder."'                      ".
                                   ")                                       " ;
//echo  $sQuery."<BR>" ;
                         mysql_query($sQuery,$connect) ;
                     }
                }

                $sTemp1 = substr($sTemp1,$i+1) ;
            }

        }
?>


<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>���ھ��</title>
</head>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

   <script>

         var nlstDegree, nlstPrice ;
         var nTotDegree ;
         var nTotal ;
         var picedSell ;
         var arry_degree = new Array(<?=count($arryDegree)?>) ;
         var arry_price  = new Array(<?=count($arryUnitPrice)?>) ;
         var arry_totdegree = new Array(<?=count($arryDegree)?>) ; // ȸ���� �հ�
         var arry_totprice  = new Array(<?=count($arryUnitPrice)?>+1) ;


         function delete_click()
         {
             answer = confirm("������ �����Ͻð����ϱ�?") ;
             if  (answer==true)
             {
                 //singodelete.action = "<?=$PHP_SELF?>?M2005=<?=$M2005?>&ActionCode=Delete" ;
                 singodelete.submit() ;
             }
         }

         //---------------------------------------------------------------
         //
         // ������ 2�ڸ����ڷ� ���鶧..  0����ä����
         //
         function fn(m)
         {
            z = '00' ;

            return z.substr(0,z.length-String(m).length) + m ;
         }

         function check_submit_degree(str,index)
         {
//alert(write.score.value);
             var nSelect = -1 ;
             for(var i=0; i<write.FilmType.length; i++)
             {
                 if  (write.FilmType[i].checked)
                 {
                     nSelect = i ;
                     break;
                 }
             }
             if (nSelect == -1)
             {
                 alert("�ʸ������� ���õ��� �ʾҽ��ϴ�.") ;
             }
             else
             {
                 var  singoUnit = "" ;
                 var  singoDataDgr = "" ;

                 if ( str=="99")
                 {
                     answer = confirm("�ɾ߸� �����Ͻð����ϱ�?") ;
                 }
                 else
                 {
                     answer = confirm(""+str+"ȸ�� �����Ͻð����ϱ�?") ;
                 }
                 if  (answer==true)
                 {
                     if  ((picedSell!=null) && (nlstDegree!=null) && (nlstPrice!=null) && (write.score.value!="")) // ������ �ѹ� ���õǾ��� ������ �ԷµǾ��ִٸ�
                     {
                         if   (score_check()==false)  return false ;

                         <?
                         if  ($M2005=="Yes")
                         {
                             ?>
                             picedSell.value = number_format(write.score.value)  ;  // Ȯ�ι�ư�� �����Ͱ� ���� ����� �ϵ����Ѵ�.
                             <?
                         }
                         else
                         {
                             ?>
                             picedSell.innerHTML = number_format(write.score.value)  ;  // Ȯ�ι�ư�� �����Ͱ� ���� ����� �ϵ����Ѵ�.
                             <?
                         }
                         ?>


                         arry_sel[nlstDegree][nlstPrice] = eval(write.score.value) ;  // 2���� �迭�� ���ݻ����� ��ġ�Ѵ�.

                         nTotDegree = 0 ;

                         for (i=0;i< <?=count($arryUnitPrice)?>;i++)
                         {
                             nTotDegree = nTotDegree + (arry_sel[nlstDegree][i]) ;
                         }

                         //arry_totdegree[nlstDegree].innerHTML = nTotDegree ;
                         arry_totdegree[nlstDegree].innerHTML = number_format(nTotDegree) ;

                         nTotal = 0 ;

                         for (j=0;j<<?=count($arryDegree)?>;j++)
                         {
                                nTotal = nTotal + (arry_sel[j][nlstPrice]) ;
                         }
                         //arry_totprice[nlstPrice].innerHTML = eval(nTotal) ;
                         arry_totprice[nlstPrice].innerHTML = number_format(nTotal) ;

                         nTotal = 0 ;

                         for (j=0;j<<?=count($arryDegree)?>;j++)
                         {
                             for (i=0;i<<?=count($arryUnitPrice)?>;i++)
                             {
                                nTotal = nTotal + (arry_sel[j][i]) ;
                             }
                         }

                         //arry_totprice[<?=count($arryUnitPrice)?>].innerHTML = nTotal ;
                         arry_totprice[<?=count($arryUnitPrice)?>].innerHTML = number_format(nTotal) ;
                     }

                     i = eval(str) ;

                     singoDataDgr = "" ;

                     for (j=0;j< <?=count($arryUnitPrice)?>;j++)
                     {
                        if   (arry_sel[index][j]!=null)
                        {
                             singoUnit  = arry_degree[index] +"," + arry_price[j]  +"," + arry_sel[index][j] +",."  ;

                             singoDataDgr = singoDataDgr +  singoUnit ;
                        }
                     }

                     write.singoDataAll.value = "" ; // ȸ���������̹Ƿ�  all�� �����.
                     write.selectDegree.value = str ;
                     if (str==1)
                     {
                        write.singoData01.value = singoDataDgr ; // ȸ��������
                     }
                     if (str==2)
                     {
                        write.singoData02.value = singoDataDgr ; // ȸ��������
                     }
                     if (str==3)
                     {
                        write.singoData03.value = singoDataDgr ; // ȸ��������
                     }
                     if (str==4)
                     {
                        write.singoData04.value = singoDataDgr ; // ȸ��������
                     }
                     if (str==5)
                     {
                        write.singoData05.value = singoDataDgr ; // ȸ��������
                     }
                     if (str==6)
                     {
                        write.singoData06.value = singoDataDgr ; // ȸ��������
                     }
                     if (str==7)
                     {
                        write.singoData07.value = singoDataDgr ; // ȸ��������
                     }
                     if (str==8)
                     {
                        write.singoData08.value = singoDataDgr ; // ȸ��������
                     }
                     if (str==9)
                     {
                        write.singoData09.value = singoDataDgr ; // ȸ��������
                     }
                     if (str==10)
                     {
                        write.singoData10.value = singoDataDgr ; // ȸ��������
                     }
                     if (str==99)
                     {
                        write.singoData99.value = singoDataDgr ; // ȸ��������
                     }
                     write.submit() ;
                 }
             }
         }

         function number_format(str) // 1234 -> 1,234 �� �ٲ��ش�.
         {
             str = ""+str+"";

             var retValue = "";

             for(i=0; i<str.length; i++)
             {
                 if  (i > 0 && (i%3)==0)
                 {
                     retValue = str.charAt(str.length - i -1) + "," + retValue;
                 }
                 else
                 {
                     retValue = str.charAt(str.length - i -1) + retValue;
                 }
             }
             return retValue;
         }

         function number_string(str)// 1,234 -> 1234 �� �ٲ��ش�.
         {
             str = ""+str+"";

             var retValue = "";

             for(i=0; i<str.length; i++)
             {
                 if  (str.charAt(i) != ",")
                 {
                     retValue = retValue + str.charAt(i) ;
                 }
             }
             return retValue;
         }


         // �ڹٽ�ũ��Ʈ���� ������ �迭������ ���������� ���������ʴ´�...
         var arry_sel    = new Array(<?=count($arryDegree)?>) ;
         for (i=0;i< <?=count($arryDegree)?>;i++)
         {
            arry_sel[i]  = new Array(<?=count($arryUnitPrice)?>) ;
         }

         for (i=0;i< <?=count($arryDegree)?>;i++)
         {
             for (j=0;j< <?=count($arryUnitPrice)?>;j++)
             {
                arry_sel[i][j] = 0 ;
             }
         }

  <?
     echo "\n" ;
     for ($i=0;$i<count($arryDegree);$i++)
     {
        echo "arry_degree[".$i."] = \"".$arryDegree[$i]."\" ; \n" ;
     }
     for ($i=0;$i<count($arryUnitPrice);$i++)
     {
        echo "arry_price[".$i."] = \"".$arryUnitPrice[$i]."\" ; \n" ;
     }
  ?>

         picedSell = null ;

         //
         //   "����"  �� ������ �� ..
         //
         //
         //

         function check_submit()
         {
            var  singoUnit = "" ;
            var  singoDataAll = "" ;

            var nSelect = -1 ;
            for(var i=0; i<write.FilmType.length; i++)
            {
                if  (write.FilmType[i].checked)
                {
                    nSelect = i ;
                    break;
                }
            }
            if (nSelect == -1)
            {
                alert("�ʸ������� ���õ��� �ʾҽ��ϴ�.") ;

                return false;
            }
            else
            {
                if  ((picedSell!=null) && (nlstDegree!=null) && (nlstPrice!=null) && (write.score.value!="")) // ������ �ѹ� ���õǾ��� ������ �ԷµǾ��ִٸ�
                {
                    if   (score_check()==false)  return false ;

                    <?
                    if  ($M2005=="Yes")
                    {
                        ?>
                        picedSell.value = number_format(write.score.value)  ;  // Ȯ�ι�ư�� �����Ͱ� ���� ����� �ϵ����Ѵ�.
                        <?
                    }
                    else
                    {
                        ?>
                        picedSell.innerHTML = number_format(write.score.value)  ;  // Ȯ�ι�ư�� �����Ͱ� ���� ����� �ϵ����Ѵ�.
                        <?
                    }
                    ?>


                    arry_sel[nlstDegree][nlstPrice] = eval(write.score.value) ;  // 2���� �迭�� ���ݻ����� ��ġ�Ѵ�.

                    nTotDegree = 0 ;

                    for (i=0;i< <?=count($arryUnitPrice)?>;i++)
                    {
                        nTotDegree = nTotDegree + (arry_sel[nlstDegree][i]) ;
                    }

                    //arry_totdegree[nlstDegree].innerHTML = nTotDegree ;
                    arry_totdegree[nlstDegree].innerHTML = number_format(nTotDegree) ;

                    nTotal = 0 ;

                    for (j=0;j<<?=count($arryDegree)?>;j++)
                    {
                           nTotal = nTotal + (arry_sel[j][nlstPrice]) ;
                    }
                    //arry_totprice[nlstPrice].innerHTML = eval(nTotal) ;
                    arry_totprice[nlstPrice].innerHTML = number_format(nTotal) ;

                    nTotal = 0 ;

                    for (j=0;j<<?=count($arryDegree)?>;j++)
                    {
                        for (i=0;i<<?=count($arryUnitPrice)?>;i++)
                        {
                           nTotal = nTotal + (arry_sel[j][i]) ;
                        }
                    }

                    //arry_totprice[<?=count($arryUnitPrice)?>].innerHTML = nTotal ;
                    arry_totprice[<?=count($arryUnitPrice)?>].innerHTML = number_format(nTotal) ;
                }

                for (i=0;i< <?=count($arryDegree)?>;i++)
                {
                    for (j=0;j< <?=count($arryUnitPrice)?>;j++)
                    {
                       if   (arry_sel[i][j]!=null)
                       {
                            singoUnit  = arry_degree[i] +"," + arry_price[j]  +"," + arry_sel[i][j] +",."  ;

                            singoDataAll = singoDataAll +  singoUnit ;
                       }
                    }
                }

                write.singoDataAll.value = singoDataAll ;
                write.action =  "<?=$PHP_SELF?>?M2005=<?=$M2005?>&ShowRoom=<?=$silmoojaTheather?><?=$silmoojaRoom?>&BackAddr=wrk_silmooja.php&WorkDate=<?=$WorkDate?>" ;  // action �� �ְ�

                return true;
            }
         }

         //
         //   "-" Ȥ�� Ư������� ����� �� ..
         //
         //
         //

         function select_price(nDegree,nPrice,sell)
         {
            if  ((picedSell!=null) && (nlstDegree!=null) && (nlstPrice!=null) && (write.score.value!="")) // ������ �ѹ� ���õǾ��� ������ �ԷµǾ��ִٸ�
            {
                if   (score_check()==false)  return ;

                <?
                if  ($M2005=="Yes")
                {
                    ?>
                    picedSell.value = number_format(write.score.value)  ;  // Ȯ�ι�ư�� �����Ͱ� ���� ����� �ϵ����Ѵ�.
                    <?
                }
                else
                {
                    ?>
                    picedSell.innerHTML = number_format(write.score.value)  ;  // Ȯ�ι�ư�� �����Ͱ� ���� ����� �ϵ����Ѵ�.
                    <?
                }
                ?>

                arry_sel[nlstDegree][nlstPrice] = eval(write.score.value) ;  // 2���� �迭�� ���ݻ����� ��ġ�Ѵ�.

                nTotDegree = 0 ;

                for (i=0;i< <?=count($arryUnitPrice)?>;i++)
                {
                    nTotDegree = nTotDegree + (arry_sel[nlstDegree][i]) ;
                }

                //arry_totdegree[nlstDegree].innerHTML = nTotDegree ;
                arry_totdegree[nlstDegree].innerHTML = number_format(nTotDegree) ;

                nTotal = 0 ;

                for (j=0;j<<?=count($arryDegree)?>;j++)
                {
                       nTotal = nTotal + (arry_sel[j][nlstPrice]) ;
                }
                //arry_totprice[nlstPrice].innerHTML = eval(nTotal) ;
                arry_totprice[nlstPrice].innerHTML = number_format(nTotal) ;

                nTotal = 0 ;

                for (j=0;j<<?=count($arryDegree)?>;j++)
                {
                    for (i=0;i<<?=count($arryUnitPrice)?>;i++)
                    {
                       nTotal = nTotal + (arry_sel[j][i]) ;
                    }
                }

                //arry_totprice[<?=count($arryUnitPrice)?>].innerHTML = nTotal ;
                arry_totprice[<?=count($arryUnitPrice)?>].innerHTML = number_format(nTotal) ;
            }

            <?
            if  ($M2005=="Yes")
            {
                ?>
                if  (sell.innerHTML=="-")
                {
                    write.score.value = "" ;
                }
                else
                {
                    write.score.value = number_string(sell.value) ; // ��Ȳ���� ��ġ�� �����Է� �ڽ��� ���� ������ ������ ���ֵ����Ѵ�.
                }
                <?
            }
            else
            {
                ?>
                if  (sell.innerHTML=="-")
                {
                    write.score.value = "" ;
                }
                else
                {
                    write.score.value = number_string(sell.innerHTML) ; // ��Ȳ���� ��ġ�� �����Է� �ڽ��� ���� ������ ������ ���ֵ����Ѵ�.
                }
                <?
            }
            ?>

            if  ((nlstDegree!=null) && (nlstPrice!=null))
            {
                if  (write.score.value == "")
                {
                    arry_sel[nDegree][nPrice] = 0 ;  // 2���� �迭�� ���ݻ����� ��ġ�Ѵ�.
                }
                else
                {
                    arry_sel[nDegree][nPrice] = eval(write.score.value) ;  // 2���� �迭�� ���ݻ����� ��ġ�Ѵ�.

                    nTotDegree = 0 ;

                    for (i=0;i< <?=count($arryUnitPrice)?>;i++)
                    {
                        nTotDegree = nTotDegree + (arry_sel[nlstDegree][i]) ;
                    }

                    //arry_totdegree[nlstDegree].innerHTML = nTotDegree ;
                    arry_totdegree[nlstDegree].innerHTML = number_format(nTotDegree) ;

                    nTotal = 0 ;

                    for (j=0;j<<?=count($arryDegree)?>;j++)
                    {
                           nTotal = nTotal + (arry_sel[j][nlstPrice]) ;
                    }
                    //arry_totprice[nlstPrice].innerHTML = eval(nTotal) ;
                    arry_totprice[nlstPrice].innerHTML = number_format(nTotal) ;

                    nTotDegree = 0 ;

                    for (i=0;i< <?=count($arryUnitPrice)?>;i++)
                    {
                        nTotDegree = nTotDegree + (arry_sel[nlstDegree][i]) ;
                    }

                    //arry_totdegree[nlstDegree].innerHTML = nTotDegree ;
                    arry_totdegree[nlstDegree].innerHTML = number_format(nTotDegree) ;

                    nTotal = 0 ;

                    for (j=0;j<<?=count($arryDegree)?>;j++)
                    {
                           nTotal = nTotal + (arry_sel[j][nlstPrice]) ;
                    }
                    //arry_totprice[nlstPrice].innerHTML = eval(nTotal) ;
                    arry_totprice[nlstPrice].innerHTML = number_format(nTotal) ;

                    nTotal = 0 ;

                    for (j=0;j<<?=count($arryDegree)?>;j++)
                    {
                        for (i=0;i<<?=count($arryUnitPrice)?>;i++)
                        {
                           nTotal = nTotal + (arry_sel[j][i]) ;
                        }
                    }

                    //arry_totprice[<?=count($arryUnitPrice)?>].innerHTML = nTotal ;
                    arry_totprice[<?=count($arryUnitPrice)?>].innerHTML = number_format(nTotal) ;
                }
            }

            nlstDegree = nDegree ;
            nlstPrice  = nPrice  ;

            picedSell = sell ; // ���������� ���õ� ���� ����..

            write.score.focus() ;
            write.score.select();
         }

         //
         //   "Ȯ��"  �� ������ �� ..
         //
         //
         //

         function click_update()
         {
            if  (picedSell==null)
            {
                alert("���� ������ ���ھ �����ϼ���!") ;
                write.score.focus() ;
                write.score.select();
            }
            else
            {
                if  ((nlstDegree!=null) && (nlstPrice!=null))
                {
                    if  (write.score.value=="")
                    {
                        picedSell.innerHTML = "-" ;
                        arry_sel[nlstDegree][nlstPrice] = 0 ;  // 2���� �迭�� ���ݻ����� ��ġ�Ѵ�.
                    }
                    else
                    {
                        if   (score_check()==false)  return ;

                        <?
                        if  ($M2005=="Yes")
                        {
                            ?>
                            picedSell.value = number_format(write.score.value) ;
                            <?
                        }
                        else
                        {
                            ?>
                            picedSell.innerHTML = number_format(write.score.value) ;
                            <?
                        }
                        ?>

                        arry_sel[nlstDegree][nlstPrice] = eval(write.score.value) ;  // 2���� �迭�� ���ݻ����� ��ġ�Ѵ�.
                    }

                    nTotDegree = 0 ;

                    for (i=0;i< <?=count($arryUnitPrice)?>;i++)
                    {
                        nTotDegree = nTotDegree + arry_sel[nlstDegree][i] ;
                    }

                    //arry_totdegree[nlstDegree].innerHTML = nTotDegree ;
                    arry_totdegree[nlstDegree].innerHTML = number_format(nTotDegree) ;

                    nTotal = 0 ;

                    for (j=0;j<<?=count($arryDegree)?>;j++)
                    {
                           nTotal = nTotal + (arry_sel[j][nlstPrice]) ;
                    }
                    //arry_totprice[nlstPrice].innerHTML = eval(nTotal) ;
                    arry_totprice[nlstPrice].innerHTML = number_format(nTotal) ;
                }

                nTotal = 0 ;

                for (j=0;j<<?=count($arryDegree)?>;j++)
                {
                    for (i=0;i<<?=count($arryUnitPrice)?>;i++)
                    {
                       nTotal = nTotal + (arry_sel[j][i]) ;
                    }
                }

                //arry_totprice[<?=count($arryUnitPrice)?>].innerHTML = nTotal ;
                arry_totprice[<?=count($arryUnitPrice)?>].innerHTML = number_format(nTotal) ;
            }
         }

         //
         //   "��������"  �� ������ �� ..
         //
         //
         //

         function click_magam()
         {
            location.href="<?=$PHP_SELF?>?M2005=<?=$M2005?>&WorkDate=<?=$WorkDate?>&ShowRoom=<?=$ShowRoom?>&ActionCode=Magam&BackAddr=<?=$BackAddr?>" ;
         }


         //
         //   ���ڸ� �Է� �޵��� �����Ѵ�.
         //
         //
         //

         function score_check()
         {
            edit = write.score.value ;

            if ((edit !="") && (edit.search(/\D/) != -1))
            {
                alert("���ڸ� �Է½ÿ�!") ;

                write.score.value = "";

                edit = edit.replace(/\D/g, "")

                write.score.focus() ;
                write.score.select();

                return false ;
            }
            else
            {
                return true ;
            }
         }

   </script>




<? echo "<b>".$UserName . "</b>���� ȯ���մϴ�!" ; ?>
<a href="index_com.php?actcode=logout"><b>[LogOut]</b></a>
<a href="<?=$BackAddr?>?WorkDate=<?=$WorkDate?>"><b>[X]</b></a>

<center>

   <?
   if  (session_is_registered("logged_UserId"))
   {
   ?>
   <br><b>*���ھ��<a href='wrk_silmooja_70.php?M2005=<?=$M2005?>&ShowRoom=<?=$ShowRoom?>&BackAddr=<?=$PHP_SELF?>'>(<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>)</a>*</b><br>
   <?
   }
   else
   {
   ?>
   <br><b>*���ھ��(<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>)*</b><br>
   <?
   }
   ?>

   <?
   echo "<b>" . $filmtitleName . "</b><br>" ;    // ��ȭ����
   echo $showroomDiscript."(".$showroomSeat.")-". $locationName  ; // ����� - ������
   ?>

   <form method=post name=write onsubmit="return check_submit()">
       <input type=text name=score size=7 maxlength=4 style='text-align:right' class=input onKeyDown="javascript:if (event.keyCode == 13) return false;">
       <input type=button value="Ȯ��" OnClick="click_update()">
       <input type="submit" value="����">
       <BR>

       <input name=loggedUserId type=hidden value=<?=logged_UserId?>>
       <input name=singoDataAll type=hidden value=<?
       for ($j=0; $j<count($arryDegree); $j++)  // ȸ�� ����Ʈ..
       {
           $sQuery = "Select * From ".$sSingoName."                  ".
                     " Where SingoDate = '".$WorkDate."'             ".
                     "   And Silmooja  = '".$silmoojaCode."'         ".
                     "   And Theather  = '".$silmoojaTheather."'     ".
                     "   And Room      = '".$silmoojaRoom."'         ".
                     "   And Open      = '".$silmoojatheatherOpen."' ".
                     "   And Film      = '".$silmoojatheatherFilm."' ".
                     "   And ShowDgree = '".$arryDegree[$j]."'       ".
                     " Order By UnitPrice Desc                       " ;
           $QrySingo = mysql_query($sQuery,$connect) ;
           while ($ArrSingo = mysql_fetch_array($QrySingo))
           {
                $UnitPrice = $ArrSingo["UnitPrice"] ;

                $SingoDataDgr = $arryDegree[$j].",".$UnitPrice.",".$ArrSingo["NumPersons"].",." ; // �ش�Ű��..
                echo $SingoDataDgr ;

                $SingoData = "SingoData".$j ;
                $$SingoData .= $SingoDataDgr ;
           }
       }
       ?>>

       <input name=selectDegree type=hidden value="">
       <?
       for ($j=0; $j<count($arryDegree); $j++)  // ȸ�� ����Ʈ..
       {
           ?>
           <input name=singoData<?=$arryDegree[$j]?> type=hidden value=<?
           $SingoData = "SingoData".$j ;
           echo $$SingoData ;?>>
           <?
       }
       ?>

       <br>
       <?
       $FilmType = "" ;

       $sQuery = "Select FilmType From ".$sSingoName."           ".
                 " Where SingoDate = '".$WorkDate."'             ".
                 "   And Silmooja  = '".$silmoojaCode."'         ".
                 "   And Theather  = '".$silmoojaTheather."'     ".
                 "   And Room      = '".$silmoojaRoom."'         ".
                 "   And Open      = '".$silmoojatheatherOpen."' ".
                 "   And Film      = '".$silmoojatheatherFilm."' " ; // echo $sQuery ;
       $QryWorkDate = mysql_query($sQuery,$connect) ;
       if  ($ArrWorkDate = mysql_fetch_array($QryWorkDate))
       {
           $FilmType = $ArrWorkDate["FilmType"] ;
       }
       if  ($FilmType == "")
       {
           $sQuery = "Select FilmType From ".$sSingoName."           ".
                     " Where SingoDate = '".$AgoDate."'              ".
                     "   And Silmooja  = '".$silmoojaCode."'         ".
                     "   And Theather  = '".$silmoojaTheather."'     ".
                     "   And Room      = '".$silmoojaRoom."'         ".
                     "   And Open      = '".$silmoojatheatherOpen."' ".
                     "   And Film      = '".$silmoojatheatherFilm."' " ; // echo $sQuery ;
           $QryAgoDate = mysql_query($sQuery,$connect) ;
           if  ($ArrAgoDate = mysql_fetch_array($QryAgoDate))
           {
               $FilmType = $ArrAgoDate["FilmType"] ;
           }
       }

       $sQuery = "Select * From bas_filmtitle_typelimit     ".
                 " Where Open = '".$silmoojatheatherOpen."' ".
                 "   And Code = '".$silmoojatheatherFilm."' " ; //eq($sQuery );
       $QryTypelimit = mysql_query($sQuery,$connect) ;
       if  ($ArrTypelimit = mysql_fetch_array($QryTypelimit))
       {           
           $Type35    = $ArrTypelimit["Type35"] ;
           $Type2     = $ArrTypelimit["Type2"] ;
           $Type20    = $ArrTypelimit["Type20"] ;
           $Type3     = $ArrTypelimit["Type3"] ;
           $Type30    = $ArrTypelimit["Type30"] ;
           $Type29    = $ArrTypelimit["Type29"] ;
           $Type39    = $ArrTypelimit["Type39"] ;
           $Type24    = $ArrTypelimit["Type24"] ;
           $Type34    = $ArrTypelimit["Type34"] ;        
           $Type294   = $ArrTypelimit["Type294"] ;        
           $Type394   = $ArrTypelimit["Type394"] ;        
           $Type4     = $ArrTypelimit["Type4"] ;        
       }
       
       $bChk35 = "" ;
       $bChk2  = "" ;
       $bChk20  = "" ;
       $bChk3  = "" ;
       $bChk30  = "" ;
       $bChk29 = "" ;
       $bChk39 = "" ;
       $bChk24 = "" ;
       $bChk34 = "" ;
       $bChk294 = "" ;
       $bChk394 = "" ;
       $bChk4 = "" ;

       if  ($FilmType == "35" )  $bChk35  = "checked" ; else $bChk35 = "" ;
       if  ($FilmType == "2"  )  $bChk2   = "checked" ; else $bChk2  = "" ;
       if  ($FilmType == "20" )  $bChk20  = "checked" ; else $bChk20 = "" ;
       if  ($FilmType == "3"  )  $bChk3   = "checked" ; else $bChk3  = "" ;
       if  ($FilmType == "30" )  $bChk30  = "checked" ; else $bChk30 = "" ;
       if  ($FilmType == "29" )  $bChk29  = "checked" ; else $bChk29 = "" ;
       if  ($FilmType == "39" )  $bChk39  = "checked" ; else $bChk39 = "" ;
       if  ($FilmType == "24" )  $bChk24  = "checked" ; else $bChk24 = "" ;
       if  ($FilmType == "34" )  $bChk34  = "checked" ; else $bChk34 = "" ;
       if  ($FilmType == "294" ) $bChk294 = "checked" ; else $bChk294 = "" ;
       if  ($FilmType == "394" ) $bChk394 = "checked" ; else $bChk394 = "" ;
       if  ($FilmType == "4" )   $bChk4   = "checked" ; else $bChk4 = "" ;

       ?>
       <span class="item">
       <? if($Type35  == "Y") { ?> <input type='radio' name='FilmType' value='35'  <?=$bChk35?> >35mm<br> <? } ?>
       <? if($Type2   == "Y") { ?> <input type='radio' name='FilmType' value='2'   <?=$bChk2?>  >������2D<br> <? } ?>
       <? if($Type3   == "Y") { ?> <input type='radio' name='FilmType' value='3'   <?=$bChk3?>  >������3D<br> <? } ?>
       <? if($Type29  == "Y") { ?> <input type='radio' name='FilmType' value='29'  <?=$bChk29?> >���̸ƽ�2D<br> <? } ?>
       <? if($Type39  == "Y") { ?> <input type='radio' name='FilmType' value='39'  <?=$bChk39?> >���̸ƽ�3D<br> <? } ?>
       <? if($Type20  == "Y") { ?> <input type='radio' name='FilmType' value='20'  <?=$bChk20?> >������ ����<br> <? } ?>
       <? if($Type30  == "Y") { ?> <input type='radio' name='FilmType' value='30'  <?=$bChk30?> >������3D ����<br> <? } ?>
       <? if($Type24  == "Y") { ?> <input type='radio' name='FilmType' value='24'  <?=$bChk24?> >2D HFR<br> <? } ?>
       <? if($Type34  == "Y") { ?> <input type='radio' name='FilmType' value='34'  <?=$bChk34?> >3D HFR<br> <? } ?>
       <? if($Type294 == "Y") { ?> <input type='radio' name='FilmType' value='294' <?=$bChk294?>>IMAX 2D HFR<br> <? } ?>
       <? if($Type394 == "Y") { ?> <input type='radio' name='FilmType' value='394' <?=$bChk394?>>IMAX 3D HFR<br> <? } ?>
       <? if($Type4   == "Y") { ?> <input type='radio' name='FilmType' value='4'   <?=$bChk4?>  >4D<br> <? } ?>
       </span>
       <br>

       <?
       if  ($singoDataAll!="") // �Ű� ����Ÿ�� �ִ°�� (���簪..)
       {
           echo "<script>alert('���ھ�� ���������� �Ϸ�Ǿ����ϴ�.');</script>" ;
       }
       ?>

       <!-- �Է� ���̺� -->
       <table cellpadding=0 cellspacing=0 border=1>

           <!-- Ÿ��Ʋ -->
           <tr>
                <td align=center>���</td>
                <?
                for ($i=0 ; $i<count($arryDegree) ; $i++)
                {
                   if  ($arryDegree[$i]=="99")  // �ɾ�ȸ��
                   {
                       ?>
                       <td align=center>�ɾ�<br>
                       <?=substr($arryTime[$i],0,2)?>:<?=substr($arryTime[$i],2,2)?></td>
                       <?
                   }
                   else
                   {
                       ?>
                       <td align=center><?=(int)$arryDegree[$i]?>ȸ<br>
                       <?=substr($arryTime[$i],0,2)?>:<?=substr($arryTime[$i],2,2)?></td>
                       <?
                   }
                }
                ?>
                <td align=center>����</td>
                <td align=center>����</td>
           </tr>


           <?
           $TotSumNumPersons  = 0 ;
           $TotSumNumPersonsY = 0 ;

           for ($i=0;$i<count($arryUnitPrice);$i++) // ��簡���� ����Ʈ..
           {
           ?>
           <tr>
                <?
                if   ($arryUnitPrice[$i] == 0)
                {
                    ?><td align=center>������</td><?
                }
                else
                {
                    ?><td align=center><?=number_format($arryUnitPrice[$i])?></td><?
                }


                $totPriceNumPersons = 0 ;

                for ($j=0; $j<count($arryDegree); $j++)  // ȸ�� ����Ʈ..
                {
                    // �Ű��� �� �ش�Ű���� ã�´�.
                    $sQuery = "Select * From ".$sSingoName."                  ".
                              " Where SingoDate = '".$WorkDate."'             ".
                              "   And Silmooja  = '".$silmoojaCode."'         ".
                              "   And Theather  = '".$silmoojaTheather."'     ".
                              "   And Room      = '".$silmoojaRoom."'         ".
                              "   And Open      = '".$silmoojatheatherOpen."' ".
                              "   And Film      = '".$silmoojatheatherFilm."' ".
                              "   And ShowDgree = '".$arryDegree[$j]."'       ".
                              "   And UnitPrice = '".$arryUnitPrice[$i]."'    " ;
                    $query1 = mysql_query($sQuery,$connect) ;
                    if  ($ArySingo = mysql_fetch_array($query1))
                    {
                        // �ش�Ű��..
                        if  ($ArySingo["NumPersons"] != "0")
                        {
                             $singoNumPersons = $ArySingo["NumPersons"] ;

                             $totNumPersons[$j]  += $singoNumPersons ;
                             $totPriceNumPersons += $singoNumPersons ;
                        }
                        else
                        {
                            $singoNumPersons = "-" ;
                        }

                        $arrySend[$j] = substr($ArySingo["SingoTime"],8,2).":".substr($ArySingo["SingoTime"],10,2) ;
                    }
                    else
                    {
                        $singoNumPersons = "-" ;
                        $arrySend[$j] = ":" ;
                    }

                    ?>
                    <td align=center>
                    <?
                    if  ($WorkDate > $Ago2Date) // ��Ʋ ���ڷḦ �ԷºҰ�(dbo����)..
                    {
                        if  ($M2005=="Yes")
                        {
                            ?>
                            <input  id="sellp<?=$i?>d<?=$j?>" type=button value="<?=number_format($singoNumPersons)?>" OnClick="select_price(<?=$j?>,<?=$i?>,sellp<?=$i?>d<?=$j?>)">
                            <?
                        }
                        else
                        {
                            ?>
                            <a OnClick='select_price(<?=$j?>,<?=$i?>,sellp<?=$i?>d<?=$j?>)'>
                            <div id="sellp<?=$i?>d<?=$j?>"><?=number_format($singoNumPersons)?></div>
                            </a>
                            <?
                        }
                    }
                    else
                    {
                        ?>
                        <div id="sellp<?=$i?>d<?=$j?>"><?=number_format($singoNumPersons)?></div>
                        <?
                    }
                    ?>
                    </td>

                    <?
                    $PriceTotNumPersons[$i]  += $singoNumPersons ;
                    ?>

                    <?

                    if  ($singoNumPersons!="-")
                    {
                       ?>
                       <script>
                                arry_sel[<?=$j?>][<?=$i?>] = <?=$singoNumPersons?> ;
                       </script>
                       <?
                    }

                }

                ?>

                <? // ���� ?>
                <td align=center>
                <b><div id="PriceTot<?=$i?>"><?=number_format($totPriceNumPersons)?></div></b>
                </td>
                <script>
                         arry_totprice[<?=$i?>] = PriceTot<?=$i?>  ;
                </script>

                <?

                $CondOpenFilm = " And Open = '".$silmoojatheatherOpen."' ".
                                " And Film = '".$silmoojatheatherFilm."' " ;
                $CondFilmType = " And FilmType = '".$FilmType."' " ;

                $MonthStart = substr($WorkDate,0,6) . "01" ; // ����..

                $sQuery = "Select Sum(NumPersons) As SumNumPersons,   ".
                          "       Sum(TotAmount)  As SumTotAmount     ".
                          "  From ".$sSingoName."                     ".
                          " Where SingoDate >= '".$MonthStart."'      ".
                          "   And SingoDate <= '".$WorkDate."'        ".
                          "   And Theather  = '".$silmoojaTheather."' ".
                          $CondOpenFilm."                             ".
                          $CondFilmType."                             ".
                          "   And UnitPrice = '".$arryUnitPrice[$i]."'" ;
                $QrySingoYS = mysql_query($sQuery,$connect) ;
                if  ($ArySingoYS = mysql_fetch_array($QrySingoYS))
                {
                    $TotSumNumPersonsY += $ArySingoYS["SumNumPersons"] ; // ���հ� .
                }


                // ���ϴ��� - �ʸ� Ÿ�Ժ�
                $sQuery = "Select Sum(NumPersons) As SumNumPersons,   ".
                          "       Sum(TotAmount)  As SumTotAmount     ".
                          "  From ".$sSingoName."                     ".
                          " Where SingoDate <= '".$WorkDate."'        ".
                          "   And Theather  = '".$silmoojaTheather."' ".
                          $CondOpenFilm."                             ".
                          $CondFilmType."                             ".
                          "   And UnitPrice = '".$arryUnitPrice[$i]."'" ; //echo $sQuery ;
                $QrySingo2 = mysql_query($sQuery,$connect) ;
                if  ($AryNumPersons = mysql_fetch_array($QrySingo2))
                {
                    $TotSumNumPersons += $AryNumPersons["SumNumPersons"] ; // ���հ� .
                    $TotTotAmount     += $AryNumPersons["SumTotAmount"] ; // �ѱݾ� .

                    $sQuery = "Select Accu, TotAccu, AcMoney, TotAcMoney       ".
                              "  From ".$sAccName."                            ".
                              " Where WorkDate   = '".$WorkDate."'             ".
                              "   And Silmooja   = '".$silmoojaCode."'         ".
                              "   And Theather   = '".$silmoojaTheather."'     ".
                              "   And Open       = '".$silmoojatheatherOpen."' ".
                              "   And Film       = '".$silmoojatheatherFilm."' ".
                              "   And FilmType   = '".$FilmType."'             ".
                              "   And UnitPrice  = '".$arryUnitPrice[$i]."'    " ;
                    $QryAccumulate = mysql_query($sQuery,$connect) ;
                    if  ($AryAccumulate = mysql_fetch_array($QryAccumulate))  // ���� ���������� ���� ���
                    {
                        // Update
                        $sQuery = "Update ".$sAccName."                                               ".
                                  "   Set Accu       = '".$AryNumPersons["SumNumPersons"]."',         ".
                                  "       AcMoney    = '".$AryNumPersons["SumTotAmount"]."',          ".
                                  "       Location   = '".$showroomLocation."',                       ".
                                  "       TodayScore = '".$totPriceNumPersons."',                     ".
                                  "       TodayMoney = '".$arryUnitPrice[$i] * $totPriceNumPersons."' ".
                                  " Where WorkDate   = '".$WorkDate."'                                ".
                                  "   And Silmooja   = '".$silmoojaCode."'                            ".
                                  "   And Theather   = '".$silmoojaTheather."'                        ".
                                  "   And Open       = '".$silmoojatheatherOpen."'                    ".
                                  "   And Film       = '".$silmoojatheatherFilm."'                    ".
                                  "   And FilmType   = '".$FilmType."'                                ".
                                  "   And UnitPrice  = '".$arryUnitPrice[$i]."'                       " ;
                        mysql_query($sQuery,$connect) ;
                    }
                    else
                    {
                        // Insert
                        $sQuery = "Insert Into ".$sAccName."                          ".
                                  "Values                                             ".
                                  "(                                                  ".
                                  "    '".$WorkDate."',                               ".
                                  "    '".$silmoojaCode."',                           ".
                                  "    '".$silmoojaTheather."',                       ".
                                  "    '".$silmoojatheatherOpen."',                   ".
                                  "    '".$silmoojatheatherFilm."',                   ".
                                  "    '".$FilmType."',                               ".
                                  "    '".$arryUnitPrice[$i]."',                      ".
                                  "    '".$AryNumPersons["SumNumPersons"]."',         ".
                                  "    '0',                                           ".
                                  "    '".$AryNumPersons["SumTotAmount"]."',          ".
                                  "    '0',                                           ".
                                  "    '".$showroomLocation."',                       ".
                                  "    '".$totPriceNumPersons."',                     ".
                                  "    '".$arryUnitPrice[$i] * $totPriceNumPersons."' ".
                                  ")                                                  " ;
                        mysql_query($sQuery,$connect) ;
                    }
                }

                // ���ϴ��� - �ʸ� ��ü
                $sQuery = "Select Sum(NumPersons) As SumNumPersons,   ".
                          "       Sum(TotAmount)  As SumTotAmount     ".
                          "  From ".$sSingoName."                     ".
                          " Where SingoDate <= '".$WorkDate."'        ".
                          "   And Theather  = '".$silmoojaTheather."' ".
                          $CondOpenFilm."                             ".
                          "   And UnitPrice = '".$arryUnitPrice[$i]."'" ; //echo $sQuery ;
                $QrySingo2 = mysql_query($sQuery,$connect) ;
                if  ($AryNumPersons = mysql_fetch_array($QrySingo2))
                {
                    $sQuery = "Select Accu, TotAccu, AcMoney, TotAcMoney       ".
                              "  From ".$sAccName."                            ".
                              " Where WorkDate   = '".$WorkDate."'             ".
                              "   And Silmooja   = '".$silmoojaCode."'         ".
                              "   And Theather   = '".$silmoojaTheather."'     ".
                              "   And Open       = '".$silmoojatheatherOpen."' ".
                              "   And Film       = '".$silmoojatheatherFilm."' ".
                              "   And FilmType   = '0'             ".
                              "   And UnitPrice  = '".$arryUnitPrice[$i]."'    " ;
                    $QryAccumulate = mysql_query($sQuery,$connect) ;
                    if  ($AryAccumulate = mysql_fetch_array($QryAccumulate))  // ���� ���������� ���� ���
                    {
                        // Update
                        $sQuery = "Update ".$sAccName."                                               ".
                                  "   Set Accu       = '".$AryNumPersons["SumNumPersons"]."',         ".
                                  "       AcMoney    = '".$AryNumPersons["SumTotAmount"]."',          ".
                                  "       Location   = '".$showroomLocation."',                       ".
                                  "       TodayScore = '".$totPriceNumPersons."',                     ".
                                  "       TodayMoney = '".$arryUnitPrice[$i] * $totPriceNumPersons."' ".
                                  " Where WorkDate   = '".$WorkDate."'                                ".
                                  "   And Silmooja   = '".$silmoojaCode."'                            ".
                                  "   And Theather   = '".$silmoojaTheather."'                        ".
                                  "   And Open       = '".$silmoojatheatherOpen."'                    ".
                                  "   And Film       = '".$silmoojatheatherFilm."'                    ".
                                  "   And FilmType   = '0'                                ".
                                  "   And UnitPrice  = '".$arryUnitPrice[$i]."'                       " ;
                        mysql_query($sQuery,$connect) ;
                    }
                    else
                    {
                        // Insert
                        $sQuery = "Insert Into ".$sAccName."                          ".
                                  "Values                                             ".
                                  "(                                                  ".
                                  "    '".$WorkDate."',                               ".
                                  "    '".$silmoojaCode."',                           ".
                                  "    '".$silmoojaTheather."',                       ".
                                  "    '".$silmoojatheatherOpen."',                   ".
                                  "    '".$silmoojatheatherFilm."',                   ".
                                  "    '0',                               ".
                                  "    '".$arryUnitPrice[$i]."',                      ".
                                  "    '".$AryNumPersons["SumNumPersons"]."',         ".
                                  "    '0',                                           ".
                                  "    '".$AryNumPersons["SumTotAmount"]."',          ".
                                  "    '0',                                           ".
                                  "    '".$showroomLocation."',                       ".
                                  "    '".$totPriceNumPersons."',                     ".
                                  "    '".$arryUnitPrice[$i] * $totPriceNumPersons."' ".
                                  ")                                                  " ;
                        mysql_query($sQuery,$connect) ;
                    }
                }
                ?>
                <td align=right><?=number_format($ArySingoYS["SumNumPersons"])?></td>

           </tr>
           <?
           }

           // Delete
           $sQuery = "Delete From ".$sAccName."                       ".
                     " Where WorkDate   > '".$WorkDate."'             ".
                     "   And Silmooja   = '".$silmoojaCode."'         ".
                     "   And Theather   = '".$silmoojaTheather."'     ".
                     "   And Open       = '".$silmoojatheatherOpen."' ".
                     "   And Film       = '".$silmoojatheatherFilm."' " ;
           mysql_query($sQuery,$connect) ;

           ?>

           <tr>

               <td align=center>
               <B>�հ�</B>
               </td>
               <?
               $totTodayNumPersons = 0 ;

               for ($j=0;$j<count($arryDegree);$j++)  // ȸ�� ����Ʈ..
               {
                   ?>
                   <td align=center>
                   <?

                   if  (($totNumPersons[$j] > $showroomSeat) && ($UserId=="9006"))
                   {
                   ?>
                   <S><B><div id="totdrg<?=$j?>"><?=number_format($totNumPersons[$j])?></div></B></S>
                   +<?=$totNumPersons[$j]-$showroomSeat?>
                   <?
                   }
                   else
                   {

                   ?>
                   <B><div id="totdrg<?=$j?>"><?=number_format($totNumPersons[$j])?></div></B>
                   <?
                   }
                   ?>
                   </td>

                   <script>arry_totdegree[<?=$j?>] = totdrg<?=$j?> ;</script>
                   <?
                   $totTodayNumPersons += $totNumPersons[$j] ;
               }
               ?>

               <? // ���� �� �հ� ?>
               <td align=center>
               <b><div id="PriceTotTot"><?=number_format($totTodayNumPersons)?></div></b>
               </td>

               <script>arry_totprice[<?=$i?>] = PriceTotTot ;</script>

               <td align=right>
               <b><?=number_format($TotSumNumPersonsY)?></b>
               </td>
               <?
               $sQuery = "Update ".$sAccName."                            ".
                         "   Set TotAccu    = '".$TotSumNumPersons."',    ".
                         "       TotAcMoney = '".$TotTotAmount."'         ".
                         " Where WorkDate   = '".$WorkDate."'             ".
                         "   And Silmooja   = '".$silmoojaCode."'         ".
                         "   And Theather   = '".$silmoojaTheather."'     ".
                         "   And Open       = '".$silmoojatheatherOpen."' ".
                         "   And Film       = '".$silmoojatheatherFilm."' ".
                         "   And FilmType   = '".$FilmType."'             " ;
               mysql_query($sQuery,$connect) ;
               ?>
           </tr>
           <!-- ȸ�������� -->
           <!--
           <tr>

               <td align=center>
               <B>����</B>
               </td>
               <?
               for ($j=0;$j<count($arryDegree);$j++)  // ȸ�� ����Ʈ..
               {
                   // ȸ��������
                   ?>
                   <td align=center>
                   <?
                   if  ($arryDegree[$j] != "99" )
                   {
                       $BV = $arryDegree[$j]."ȸ" ;
                   }
                   else
                   {
                       $BV = "�ɾ�" ;
                   }
                   ?>
                   <input type=button value="<?=$BV?>" OnClick="check_submit_degree(<?=$arryDegree[$j]?>,<?=$j?>)"><br>
                   <?//=$arrySend[$j]?>
                   </td>
                   <?
               }
               ?>
               <td align=center colspan=2>
               <?
               //
               // ������ ��ȭ�� �ϴ� �ǹ��ڿ��Ը� �ߵ��� �Ѵ�.
               //

               $sQuery = "Select Count(*) As CntSilmoojaChk        ".
                         "  From bas_filmtitlesilmooja             ".
                         " Where Silmooja   = '".$silmoojaCode."'  " ;
               $QrySilmoojaChk = mysql_query($sQuery,$connect) ;
               if  ($ArrSilmoojaChk = mysql_fetch_array($QrySilmoojaChk))
               {
                   $CntSilmoojaChk = $ArrSilmoojaChk["CntSilmoojaChk"] ;

                   if  ($CntSilmoojaChk>=1)
                   {
                       $sQuery = "Select Count(*) As CntMagam                   ".
                                 "  From wrk_magam                              ".
                                 " Where WorkDate = '".$WorkDate."'             ".
                                 "   And Theather = '".$silmoojaTheather."'     ".
                                 "   And Room     = '".$silmoojaRoom."'         ".
                                 "   And Open     = '".$silmoojatheatherOpen."' ".
                                 "   And Film     = '".$silmoojatheatherFilm."' " ;
                       $QryMagam = mysql_query($sQuery,$connect) ;
                       if  ($ArrMagam = mysql_fetch_array($QryMagam))
                       {
                           if  ($ArrMagam["CntMagam"] > 0)
                           {
                               ?><input type=button value="�������" OnClick="click_magam()"><?
                           }
                           else
                           {
                               ?><input type=button value="��������" OnClick="click_magam()"><?
                           }
                       }
                   }
               }
               ?>
               </td>
           </tr>
           -->
       </table>

       <br>


   </form>



   <br>

   <!-- �� �󿵰��� �Ű�� ��ü�� �����Ѵ�. -->
   <!--  ���ó��
   <form method=post name=singodelete action="<?=$PHP_SELF?>?ActionCode=Delete&BackAddr=<?=$BackAddr?>&WorkDate=<?=$WorkDate?>">
        <input name=singoDataAll type=hidden value=<?=$WorkDate?>>
        <input name=silmooja     type=hidden value=<?=$silmoojaCode?>>
        <input name=ShowRoom     type=hidden value=<?=$ShowRoom?>>
        <input type=button name=delete value=�����ڷ���ü���� onclick="delete_click();">
   </form>
   -->
</center>
</body>

<?
    mysql_close($connect);
    }
?>
</html>

