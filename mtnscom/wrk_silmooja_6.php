<?
    session_start();  

    //
    // �ǹ��� - ��������Ϸ�
    //
    

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

        // �ش�ǹ��ڸ� ���ϰ� ..
        $sQuery = "Select * From bas_silmooja    ".
                  " Where UserId = '".$UserId."' " ;
        $query1 = mysql_query($sQuery,$connect) ;
        if  ($silmooja_data = mysql_fetch_array($query1))
        {
            $silmoojaCode = $silmooja_data["Code"] ; // �ǹ����ڵ�
        }

        $sQuery = "Select * From bas_silmoojatheather                     ".
                  " Where Silmooja  = '".$silmoojaCode."'                 ".
                  "   And Theather  = '".substr($ssn_ShowroomCode,0,4)."' ".
                  "   And Room      = '".substr($ssn_ShowroomCode,4,2)."' " ;
        $qrySilthr = mysql_query($sQuery,$connect) ;
        if  ($silthr_data = mysql_fetch_array($qrySilthr))
        {
            $silthrOpen = $silthr_data["Open"] ; // �ǹ��ڰ� ������ ��ȭ..
            $silthrFilm = $silthr_data["Film"] ;

            $sSingoName = get_singotable($silthrOpen,$silthrFilm,$connect) ;  // �Ű� ���̺� �̸�..
        }

        /* ������� �����ͺ��̽��� �����Ѵ�...*/
        $sQuery = "Select * From bas_showroom                            ".
                  " Where Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
                  "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' " ;
        $query1 = mysql_query($sQuery,$connect) ;
        if  ($showroom_data = mysql_fetch_array($query1))
        {
            $showroom_Discript = $showroom_data["Discript"] ;
        }



        // ��� ��������� �����.
        $sQuery = "Delete From bas_unitprices                             ".
                  " Where Theather  = '".substr($ssn_ShowroomCode,0,4)."' ".
                  "   And Room      = '".substr($ssn_ShowroomCode,4,2)."' " ;
        mysql_query($sQuery,$connect) ;

        $sQuery = "Delete From bas_unitpricespriv                         ".
                  " Where Silmooja = '".$silmoojaCode."'                  ".
                  "   And WorkDate = '".$WorkDate."'                      ".
                  "   And Open     = '".$silthrOpen."'                    ".
                  "   And Film     = '".$silthrFilm."'                    ".
                  "   And Theather  = '".substr($ssn_ShowroomCode,0,4)."' ".
                  "   And Room      = '".substr($ssn_ShowroomCode,4,2)."' " ;
        mysql_query($sQuery,$connect) ;

        $sTemp = $Prices ;

        while (($i = strpos($sTemp,',')) > 0)
        {
            $sItem = substr($sTemp,0,$i) ;
            $sTemp = substr($sTemp,$i+1) ;

            // ��������� �����Ѵ�.
            $sQuery = "Insert Into bas_unitprices                   ".
                      "Values ('".substr($ssn_ShowroomCode,0,4)."', ".
                      "        '".substr($ssn_ShowroomCode,4,2)."', ".
                      "        '".$sItem."',                        ".
                      "        '".$showroom_Discript."'             ".
                      "       )                                     " ;
            mysql_query($sQuery,$connect) ;

            $sQuery = "Insert Into bas_unitpricespriv               ".
                      "Values ('".$silmoojaCode."',                 ".
                      "        '".$WorkDate."',                     ".
                      "        '".$silthrOpen."',                   ".
                      "        '".$silthrFilm."',                   ".
                      "        '".substr($ssn_ShowroomCode,0,4)."', ".
                      "        '".substr($ssn_ShowroomCode,4,2)."', ".
                      "        '".$sItem."',                        ".
                      "        '".$showroom_Discript."'             ".
                      "       )                                     " ;
            mysql_query($sQuery,$connect) ;
        }
?>

<html>

<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>��������Ϸ�</title>
</head>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>


    <?
    // �ش�ǹ��ڸ� ���ϰ� ..
    $sQuery = "Select * From bas_silmooja    ".
              " Where UserId = '".$UserId."' " ;
    $qry_silmooja = mysql_query($sQuery,$connect) ;
    if  ($silmooja_data = mysql_fetch_array($qry_silmooja))
    {         
        $silmoojaCode     = $silmooja_data["Code"] ;
        $silmoojaTheather = $silmooja_data["Theather"] ;
        $silmoojaRoom     = $silmooja_data["Room"] ;
        $silmoojaName     = $silmooja_data["Name"] ;


        // �ǹ��ڰ� �İߵ� �󿵰�..
        $sQuery = "Select * From bas_showroom                 ".
                  " Where Theather = '".$silmoojaTheather."'  ".
                  "   And Room     = '".$silmoojaRoom."'      " ;
        $query2 = mysql_query($sQuery,$connect) ;
        if  ($showroom_data = mysql_fetch_array($query2))
        {
            $showroomFilmTitle = $showroom_data["FilmTitle"] ;
            $showroomDiscript  = $showroom_data["Discript"] ;

            $FilmOpen = substr($showroomFilmTitle,0,6) ;
            $FilmCode = substr($showroomFilmTitle,6,2) ;            

            $sDgrName   = get_degree($FilmOpen,$FilmCode,$connect) ;  
            $sDgrpName  = get_degreepriv($FilmOpen,$FilmCode,$connect) ;  

            // �󿵰������� �ش�ǹ��ڸ� �����Ѵ�. �ٸ� �ǹ��ڰ� �Է��Ѵ°� �������ؼ�..
            $sQuery = "Update bas_showroom                       ".
                      "   Set Silmooja     = '".$silmoojaCode."',".
                      "       SilmoojaName = '".$silmoojaName."' ".  
                      " Where Theather = '".$silmoojaTheather."' ".                               
                      "   And Room     = '".$silmoojaRoom."'     " ;
            mysql_query($sQuery,$connect) ;

            // �󿵰����� ���ϴ� ��ȭ������ ���ϰ�
            $sQuery = "Select * From bas_filmtitle    ".
                      " Where Open = '".$FilmOpen."'  ".
                      "   And Code = '".$FilmCode."'  " ;
            $query3 = mysql_query($sQuery,$connect) ;
            if  ($filmtitle_data = mysql_fetch_array($query3))
            {
                $filmtitleName = $filmtitle_data["Name"] ; // ��ȭ�̸�
            }
        }

        // �Ű� ȸ���� ���ݴ� ���濡 ���� �Ű��ڷḦ �������Ѵ�.
        $reSingoQury = "Delete From ".$sSingoName."                 ".
                       " Where SingoDate = '".$WorkDate."'          ".
                       "   And Theather  = '".$silmoojaTheather."'  ".
                       "   And Room      = '".$silmoojaRoom."'      " ;

        // �󿵰� ȸ�������� ���ϰ�
        $sQuery = "Select * From ".$sDgrpName."                ".
                  " Where Silmooja = '".$silmoojaCode."'       ".
                  "   And WorkDate = '".$WorkDate."'           ".
                  "   And Open     = '".$silthrOpen."'         ".
                  "   And Film     = '".$silthrFilm."'         ".
                  "   And Theather  = '".$silmoojaTheather."'  ".
                  "   And Room      = '".$silmoojaRoom."'      ".
                  " Order By Degree                            " ;
        $qry_temp = mysql_query($sQuery,$connect) ;
        if  ($temp_data  = mysql_fetch_array($qry_temp))
        {
            $sQuery = "Select * From ".$sDgrName."                 ".
                      " Where Silmooja = '".$silmoojaCode."'       ".
                      "   And Open     = '".$silthrOpen."'         ".
                      "   And Film     = '".$silthrFilm."'         ".
                      "   And Theather  = '".$silmoojaTheather."'  ".
                      "   And Room      = '".$silmoojaRoom."'      ".
                      " Order By Degree                            " ;
        }
        else
        {
            $sQuery = "Select * From ".$sDgrName."                 ".
                      " Where Theather  = '".$silmoojaTheather."'  ".
                      "   And Room      = '".$silmoojaRoom."'      ".
                      " Order By Degree                            " ;
        }
        $query2 = mysql_query($sQuery,$connect) ;
        while ($degree_data = mysql_fetch_array($query2))
        {
             $reSingoQury = $reSingoQury . "and ShowDgree <> '".$degree_data["Degree"]."' " ;

             $arryDegree[] = $degree_data["Degree"] ; // ȸ��
             $arryTime[]   = $degree_data["Time"] ;   // ���۽ð�
        }

        // ��� ���ݴ븦 ���Ѵ�.
        $sQuery = "Select * From bas_unitprices                ".
                  " Where Theather  = '".$silmoojaTheather."'  ".
                  "   And Room      = '".$silmoojaRoom."'      ".
                  " Order By UnitPrice                         " ;
        $query2 = mysql_query($sQuery,$connect) ;
        while ($unitprices_data = mysql_fetch_array($query2))
        {
             $reSingoQury = $reSingoQury . "and UnitPrice <> '".$unitprices_data["UnitPrice"]."' " ;
             
             $arryUnitPrice[] = $unitprices_data["UnitPrice"] ; // ���
        }
        mysql_query($reSingoQury,$connect) ; // �Ű� ȸ���� ���ݴ� ���濡 ���� �Ű��ڷḦ �������Ѵ�.
                                             // ���� �ش�����ʴ� �Ű�ǵ��� �����Ѵ�.
    }
    ?>

   <center>
   
   <table cellpadding=0 cellspacing=0 border=1>
   <tr>
            <td align=left>�ǹ���</td>
            <td align=left><?=$silmoojaName?></td>
   </tr>
   <tr>
            <td align=left>�İ߻󿵰�</td>
            <td align=left><?=$showroomDiscript?></td>
   </tr>
   <tr>
            <td align=left>��ȭ����</td>
            <td align=left><?=$filmtitleName?></td>
   </tr>
   <tr>
            <td align=left>ȸ��</td>
            <td align=center>
            <?
               for ($i=0;$i<count($arryDegree);$i++)
               {
                  if  ($arryDegree[$i]=="99")
                  {
                      echo "�ɾ� [".  substr($arryTime[$i],0,2).":".substr($arryTime[$i],2,2). "] <br>" ;
                  }
                  else
                  {
                      echo $arryDegree[$i] ."ȸ [".  substr($arryTime[$i],0,2).":".substr($arryTime[$i],2,2). "] <br>" ;
                  }

               }
            ?>
            </td>
   </tr>
   <tr>
            <td align=left>���ܰ�</td>
            <td align=center>
            <?
               for ($i=0;$i<count($arryUnitPrice);$i++)
               {
                  echo $arryUnitPrice[$i]." <br>" ;
               }
            ?>
            </td>
   </tr>
   </table>
   
   </center>


</body>

<?
    echo "<script language='JavaScript'>alert('�������� �Ϸ�Ǿ����ϴ�.');</script>";
    echo "<script language='JavaScript'>location.href='".$BackAddr."?WorkDate=".$WorkDate."'</script>";
?>

</html>

<?
        mysql_close($connect);
    } 
?>