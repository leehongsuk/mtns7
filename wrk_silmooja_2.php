<?
    session_start();

    //
    // 실무자 - 영화제목지정
    //

    include "config.php";


    // 정상적으로 로그인 했는지 체크한다.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = 'index_com.php'</script>";
    }
    else
    {
        if (!$ShowroomCode)
        {
           echo "<script language='JavaScript'>window.location = 'wrk_silmooja.php'</script>";
        }

        $ssn_ShowroomCode = $ShowroomCode ;

        if (session_is_registered("ssn_ShowroomCode"))
            session_unregister("ssn_ShowroomCode");
        session_register("ssn_ShowroomCode");

        $Today = time()-(3600*7) ; // 새벽 7시 까지 오늘로 간주한다...

        if (!$WorkDate)
        {
           $WorkDate = date("Ymd",$Today) ;
        }
?>

<html>

<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>영화제목지정</title>
</head>


<?
    $connect=dbconn();

    mysql_select_db($cont_db) ;

    $sQuery = "Select * From bas_showroom                        ".
              " Where Theather = '".substr($ShowroomCode,0,4)."' ".
              "   And Room     = '".substr($ShowroomCode,4,2)."' " ;
    $qry_showroom = mysql_query($sQuery,$connect) ;
    if  ($showroom_data = mysql_fetch_array($qry_showroom))
    {
        // 이부분은 수정되어야 한다.
        $silmoojaSilmooja  = $showroom_data["Silmooja"] ;  // 상영관을 잡고있는 실무자코드
        $silmoojaFilmTitle = $showroom_data["FilmTitle"] ; // 상영관을 잡고있는 영화코드
    }

    // 실무자가 파견된 상영장의 정보를 실무자 데이타베이스에 갱신한다.
    $sQuery = "Select * From bas_silmooja     ".
              " Where UserId = '".$UserId."'  " ;
    $qry_silmooja = mysql_query($sQuery,$connect) ;
    if  ($silmooja_data = mysql_fetch_array($qry_silmooja))
    {
        $silmoojaCode = $silmooja_data["Code"] ; // 실무자코드

        $sQuery = "Update bas_silmooja                                ".
                  "   Set Theather = '".substr($ShowroomCode,0,4)."', ".
                  "       Room     = '".substr($ShowroomCode,4,2)."'  ".
                  " Where Code = '".$silmoojaCode."'                  " ;
        mysql_query($sQuery,$connect) ;
    }

    $page_num = 10 ;

    if  ($Name) // 검색조건이 있다면..
    {
        $sQuery = "Select count(*) From bas_filmtitle ".
                  " Where Name like '%".$Name."%'     ".
                  " Order By Open Desc                ";
        $count_search = mysql_query($sQuery,$connect) ;
        $count_search_row = mysql_fetch_row($count_search);
        if  ( !$page ) { $page = 0; }
        $page_size = $page_num*$page;

        $sQuery = "Select * From bas_filmtitle    ".
                  " Where Name like '%".$Name."%' ".
                  " Order By Name                 ".
                  "limit $page_size,$page_num     " ;
        $qry_filmtitle = mysql_query($sQuery,$connect) ;
    }
    else
    {
        $sQuery = "Select count(*) From bas_filmtitle " ;
        $count_search = mysql_query($sQuery,$connect) ;
        $count_search_row = mysql_fetch_row($count_search);
        if  ( !$page ) { $page = 0; }
        $page_size = $page_num*$page;

        $sQuery = "Select * From bas_filmtitle  ".
                  " Order By Open Desc          ".
                  " limit $page_size,$page_num  " ;
        $qry_filmtitle = mysql_query($sQuery,$connect) ;
    }


    $page_1 = $count_search_row[0] / $page_num;
    $page_1 = intval($page_1);
    $page_2 = $count_search_row[0] % $page_num;

    if ( $page_2 > 0 ) { $page_1++; }

    $total_page = intval($page_1);
    $prev_page = $page - 1;
    $next_page = $page + 1;
    $now_page  = $page + 1;

    if  ( $page == 0 )
    {
        $prev_page_tag = "<A href=\"javascript:alert('더이상 페이지가 없습니다.');\">[ 이전 ]</A>";
    }
    else
    {
        $prev_page_tag = "<A href='".$PHP_SELF."?page=$prev_page&WorkDate=$WorkDate&ShowroomCode=$ShowroomCode&Name=$Name&BackAddr=wrk_silmooja.php'>[ 이전 ]</A>";
    }

    if  ( $now_page == $total_page )
    {
        $next_page_tag = "<A href=\"javascript:alert('더이상 페이지가 없습니다.');\">[ 다음 ]</A>";
    }
    else
    {
        $next_page_tag = "<A href='".$PHP_SELF."?page=$next_page&WorkDate=$WorkDate&ShowroomCode=$ShowroomCode&Name=$Name&BackAddr=wrk_silmooja.php'>[ 다음 ]</A>";
    }
?>

<script>
     <?
     if  ($silmoojaSilmooja!="")
     {
     ?>
     if  ((<?=$silmoojaSilmooja?>!="") && (<?=$silmoojaSilmooja?>!=<?=$silmoojaCode?>))
     {
         answer = confirm("이미 다른 실무자가 상영관의 도착보고를 완료했읍니다.!! 그래도 하시겠읍니까?") ;
         if   (answer==false)
         {
              location.href='<?=$BackAddr?>' ;
         }
     }
     <?
     }
     ?>
</script>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

   <script>

         function check_submit()
         {
            if(!write.Name.value)
            {
              //alert("검색대상을 입력해 주세요");
              //write.Name.focus();
              //return false;
            }
            return true;
         }

         // 상영관을 선택한 경우..
         function check_filmtitle(sFilmTileCode)
         {
            location.href="wrk_silmooja_3.php?FilmOpenCode="+sFilmTileCode+"&ShowroomCode=<?=$ShowroomCode?>&WorkDate=<?=$WorkDate?>&BackAddr=wrk_silmooja.php" ;
         }
   </script>

<? echo "<b>".$UserName . "</b>님을 환영합니다!" ; ?>
<a href="index_com.php?actcode=logout"><b>[LogOut]</b></a>
<a href="<?=$BackAddr?>?WorkDate=<?=$WorkDate?>"><b>[X]</b></a>

<center>

   <br><b>*영화제목지정(<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>)*</b><br>
<?

     $sQuery = "Select * From bas_showroom                        ".
               " Where Theather = '".substr($ShowroomCode,0,4)."' ".
               "   And Room     = '".substr($ShowroomCode,4,2)."' " ;
     $qry_showroom = mysql_query($sQuery,$connect) ;
     if  ($showroom_data = mysql_fetch_array($qry_showroom))
     {
         $showroom_Discript = $showroom_data["Discript"] ; // 상영관 명
         $showroom_Location = $showroom_data["Location"] ; // 상영관 지역
     }
     else
     {
         $showroom_Discript = "" ;
         $showroom_Location = "" ;
     }

     // 상영관의 소재지 지역을 구한다. ($locationName)
     $sQuery = "Select * From bas_location             ".
               " Where Code = '".$showroom_Location."' " ;
     $query1 = mysql_query($sQuery,$connect) ;
     if  ($location_data = mysql_fetch_array($query1))
     {
         $locationName = $location_data["Name"] ; // 상영관 소재지역명
     }

     echo $showroom_Discript ."-". $locationName ;
?>
   <form method=post name=write action="<?=$PHP_SELF?>?ShowroomCode=<?=$ShowroomCode?>&BackAddr=wrk_silmooja.php" onsubmit="return check_submit()">

   <table cellpadding=0 cellspacing=0 border=1>
   <tr>
          <td align=center>제목</td>
   </tr>
<?
   while  ($base_data = mysql_fetch_array($qry_filmtitle)) // 영화리스트 ...
   {
        $filmtitle_Open    = $base_data["Open"] ;
        $filmtitle_Code    = $base_data["Code"] ;
        $filmtitle_Name    = $base_data["Name"] ;

       // 배급사에 의해 허가되지않는 실무자는 영화를 선택할 수 없어야
       $sQuery = "Select * From bas_filmtitlesilmooja       ".
                 " Where Silmooja = '".$silmoojaCode."'     ".
                 "   And Open     = '".$filmtitle_Open."'   ".
                 "   And Film     = '".$filmtitle_Code."'   " ;
//echo $sQuery ;
       $qry_filmsupplytitlesilmooja = mysql_query($sQuery,$connect) ;
       if  ($filmsupplytitlesilmooja_data = mysql_fetch_array($qry_filmsupplytitlesilmooja))
       {
           // 이미 선택되어져 있는건은 굵게 표시한다.
           if  ((substr($silmoojaFilmTitle,0,6)==$filmtitle_Open) && (substr($silmoojaFilmTitle,6,2)==$filmtitle_Code))
           {
               ?>
               <tr>
               <td height=20 align=left><B>
               <a href="wrk_silmooja_3.php?FilmOpenCode=<?=$filmtitle_Open?><?=$filmtitle_Code?>&ShowroomCode=<?=$ShowroomCode?>&WorkDate=<?=$WorkDate?>&BackAddr=wrk_silmooja.php">
               <?=$filmtitle_Name?>
               </a></B>
               </td>
               </tr>
               <?
           }
           else
           {
               ?>
               <tr>
               <td height=20 align=left>
               <B><!-- <a OnClick="check_filmtitle('<?=$filmtitle_Open?><?=$filmtitle_Code?>');"> -->
               <a href="wrk_silmooja_3.php?FilmOpenCode=<?=$filmtitle_Open?><?=$filmtitle_Code?>&ShowroomCode=<?=$ShowroomCode?>&WorkDate=<?=$WorkDate?>&BackAddr=wrk_silmooja.php">
               <?=$filmtitle_Name?></B>
               </a>
               </td>
               </tr>
               <?
           }
       }
       else
       {
           ?>
           <tr>
           <td height=20 align=left> <font color="silver"><?=$filmtitle_Name?></font> </td>
           </tr>
           <?
       }
   }

?>
   </table>

   <BR>
   <a><?=$prev_page_tag?></a>
   [<a><?=$now_page?></a>/<?=$total_page?>]
   <a><?=$next_page_tag?></a>

   <form>
</center>

</body>
</html>

<?
        mysql_close($connect);
    }
?>