<?
    session_start();  

    //
    // 실무자 - 도착보고완료
    //
    

    // 정상적으로 로그인 했는지 체크한다.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = 'index_com.php'</script>";
    }
    else
    {
        include "config.php";

        $connect=dbconn();

        mysql_select_db($cont_db) ;

        // 해당실무자를 구하고 ..
        $sQuery = "Select * From bas_silmooja    ".
                  " Where UserId = '".$UserId."' " ;
        $query1 = mysql_query($sQuery,$connect) ;
        if  ($silmooja_data = mysql_fetch_array($query1))
        {
            $silmoojaCode = $silmooja_data["Code"] ; // 실무자코드
        }

        $sQuery = "Select * From bas_silmoojatheather                     ".
                  " Where Silmooja  = '".$silmoojaCode."'                 ".
                  "   And Theather  = '".substr($ssn_ShowroomCode,0,4)."' ".
                  "   And Room      = '".substr($ssn_ShowroomCode,4,2)."' " ;
        $qrySilthr = mysql_query($sQuery,$connect) ;
        if  ($silthr_data = mysql_fetch_array($qrySilthr))
        {
            $silthrOpen = $silthr_data["Open"] ; // 실무자가 선택한 영화..
            $silthrFilm = $silthr_data["Film"] ;

            $sSingoName = get_singotable($silthrOpen,$silthrFilm,$connect) ;  // 신고 테이블 이름..
        }

        /* 편당요금을 데이터베이스에 기입한다...*/
        $sQuery = "Select * From bas_showroom                            ".
                  " Where Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
                  "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' " ;
        $query1 = mysql_query($sQuery,$connect) ;
        if  ($showroom_data = mysql_fetch_array($query1))
        {
            $showroom_Discript = $showroom_data["Discript"] ;
        }



        // 찌꺼기 요금정보를 지운다.
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

            // 요금정보를 갱신한다.
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
<title>도착보고완료</title>
</head>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>


    <?
    // 해당실무자를 구하고 ..
    $sQuery = "Select * From bas_silmooja    ".
              " Where UserId = '".$UserId."' " ;
    $qry_silmooja = mysql_query($sQuery,$connect) ;
    if  ($silmooja_data = mysql_fetch_array($qry_silmooja))
    {         
        $silmoojaCode     = $silmooja_data["Code"] ;
        $silmoojaTheather = $silmooja_data["Theather"] ;
        $silmoojaRoom     = $silmooja_data["Room"] ;
        $silmoojaName     = $silmooja_data["Name"] ;


        // 실무자가 파견된 상영관..
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

            // 상영관정보에 해당실무자를 지정한다. 다른 실무자가 입력한는걸 막기위해서..
            $sQuery = "Update bas_showroom                       ".
                      "   Set Silmooja     = '".$silmoojaCode."',".
                      "       SilmoojaName = '".$silmoojaName."' ".  
                      " Where Theather = '".$silmoojaTheather."' ".                               
                      "   And Room     = '".$silmoojaRoom."'     " ;
            mysql_query($sQuery,$connect) ;

            // 상영관에서 상영하는 영화정보를 구하고
            $sQuery = "Select * From bas_filmtitle    ".
                      " Where Open = '".$FilmOpen."'  ".
                      "   And Code = '".$FilmCode."'  " ;
            $query3 = mysql_query($sQuery,$connect) ;
            if  ($filmtitle_data = mysql_fetch_array($query3))
            {
                $filmtitleName = $filmtitle_data["Name"] ; // 영화이름
            }
        }

        // 신고 회차및 가격대 변경에 따른 신고자료를 재정비한다.
        $reSingoQury = "Delete From ".$sSingoName."                 ".
                       " Where SingoDate = '".$WorkDate."'          ".
                       "   And Theather  = '".$silmoojaTheather."'  ".
                       "   And Room      = '".$silmoojaRoom."'      " ;

        // 상영관 회차정보를 구하고
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

             $arryDegree[] = $degree_data["Degree"] ; // 회차
             $arryTime[]   = $degree_data["Time"] ;   // 시작시간
        }

        // 편당 가격대를 구한다.
        $sQuery = "Select * From bas_unitprices                ".
                  " Where Theather  = '".$silmoojaTheather."'  ".
                  "   And Room      = '".$silmoojaRoom."'      ".
                  " Order By UnitPrice                         " ;
        $query2 = mysql_query($sQuery,$connect) ;
        while ($unitprices_data = mysql_fetch_array($query2))
        {
             $reSingoQury = $reSingoQury . "and UnitPrice <> '".$unitprices_data["UnitPrice"]."' " ;
             
             $arryUnitPrice[] = $unitprices_data["UnitPrice"] ; // 요금
        }
        mysql_query($reSingoQury,$connect) ; // 신고 회차및 가격대 변경에 따른 신고자료를 재정비한다.
                                             // 당일 해당되지않는 신고건들은 삭제한다.
    }
    ?>

   <center>
   
   <table cellpadding=0 cellspacing=0 border=1>
   <tr>
            <td align=left>실무자</td>
            <td align=left><?=$silmoojaName?></td>
   </tr>
   <tr>
            <td align=left>파견상영관</td>
            <td align=left><?=$showroomDiscript?></td>
   </tr>
   <tr>
            <td align=left>영화제목</td>
            <td align=left><?=$filmtitleName?></td>
   </tr>
   <tr>
            <td align=left>회차</td>
            <td align=center>
            <?
               for ($i=0;$i<count($arryDegree);$i++)
               {
                  if  ($arryDegree[$i]=="99")
                  {
                      echo "심야 [".  substr($arryTime[$i],0,2).":".substr($arryTime[$i],2,2). "] <br>" ;
                  }
                  else
                  {
                      echo $arryDegree[$i] ."회 [".  substr($arryTime[$i],0,2).":".substr($arryTime[$i],2,2). "] <br>" ;
                  }

               }
            ?>
            </td>
   </tr>
   <tr>
            <td align=left>편당단가</td>
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
    echo "<script language='JavaScript'>alert('도착보고가 완료되었읍니다.');</script>";
    echo "<script language='JavaScript'>location.href='".$BackAddr."?WorkDate=".$WorkDate."'</script>";
?>

</html>

<?
        mysql_close($connect);
    } 
?>