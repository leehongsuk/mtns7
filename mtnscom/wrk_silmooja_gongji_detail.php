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
?>

<html>

<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>실무자 전체 공지사항발송</title>
</head>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

   
<? echo "<b>" . $UserName . "</b>님을 환영합니다!" ; ?>
<a href="<?=$BackAddr2?>?WorkDate=<?=$WorkDate?>&BackAddr=wrk_silmooja.php"><b>[X]</b></a>


<center>
            
   <br><b>실무자 공지현황</b><br>
   
   <table border=1> 
      <?
      $Index = 0 ;
   
      $sQuery = "Select * From wrk_filmsilmoojagongji ".
                " Where Film     = '".$Film."'        ".
                "   And Silmooja = '".$Silmooja."'    ".
                "   And GongjiNo = '".$GongjiNo."'    " ;
      $QryFilmgongji = mysql_query($sQuery,$connect) ; 
      if  ($ArrFilmgongji = mysql_fetch_array($QryFilmgongji))
      {
          ?>
          <tr>
               <td><?=$ArrFilmgongji["Title"]?></td>
          </tr>
          <tr>
               <td><?=$ArrFilmgongji["Body"]?></td>
          </tr>
          <?
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