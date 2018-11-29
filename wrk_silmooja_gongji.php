<?  
    session_start();
    
    include "config.php";

    // 정상적으로 로그인 했는지 체크한다.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = 'index_com.php'</script>";
    }
    else
    {
        $Today = time()-(3600*7) ; // 새벽 7시 까지 오늘로 간주한다...  

        if (!$WorkDate)
        {            
            $WorkDate = date("Ymd",$Today) ;
        }

        $connect=dbconn();
        mysql_select_db($cont_db) ;

        // 해당실무자를 구하고 ($silmoojaName) ..
        $sQuery = "Select * From bas_silmooja    ".
                  " Where UserId = '".$UserId."' " ;
        $QrySilmooja = mysql_query($sQuery,$connect) ;
        if  ($ArrSilmooja = mysql_fetch_array($QrySilmooja))
        {         
            $silmoojaCode = $ArrSilmooja["Code"] ;   // 실무자 코드
        }
?>

<html>

<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>실무자 전체 공지사항발송</title>
</head>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

   
<? echo "<b>" . $UserName . "</b>님을 환영합니다!" ; ?>
<a href="<?=$BackAddr?>?WorkDate=<?=$WorkDate?>"><b>[X]</b></a>


<center>
            
   <br><b>실무자 공지현황</b><br>
   
   <table>
      <tr>
           <td colspan=3>-최근 전송목록-</td>
      </tr>
      <?
      $Index = 0 ;
   
      $sQuery = "Select * From wrk_filmsilmoojagongji   ".
                " Where Silmooja = '".$silmoojaCode."'  ".
                " Order By GongjiNo Desc                ".
                " Limit 0,11                            " ;
      $QryFilmgongji = mysql_query($sQuery,$connect) ; 
      while ($ArrFilmgongji = mysql_fetch_array($QryFilmgongji))
      {
            $Index ++ ;
            if  ($Index==11)
            {
                // 최근 11 번째 이후의 자료는 다지운다..
                $sQuery = "Delete From wrk_filmsilmoojagongji                ".
                          " Where Film     = ".$ArrFilmgongji["Film"]."      ".
                          "   And Silmooja = ".$ArrFilmgongji["Silmooja"]."  ".
                          "   And GongjiNo = ".$ArrFilmgongji["GongjiNo"]."  " ;
                mysql_query($sQuery,$connect) ; 
            }
            else
            {
                ?>
                <tr>
                     <td align=center><B><?=$Index?>.</B></td>
                     <td><B><?=$ArrFilmgongji["FilmName"]?></B></td>
                     <td colspan=2><?=$ArrFilmgongji["Title"]?></td>
                </tr>
                <?
            }
      }
      ?>
   </table>

</center>

</body>


</html>

<?
    mysql_close($connect);

    }
?>