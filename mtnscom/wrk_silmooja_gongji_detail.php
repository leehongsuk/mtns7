<?  
    session_start();
    
    include "config.php";
    

    // ���������� �α��� �ߴ��� üũ�Ѵ�.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = 'index_com.php'</script>";
    }
    else
    {
        $Today = time()-(3600*7) ; // ���� 7�� ���� ���÷� �����Ѵ�...  

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
<title>�ǹ��� ��ü �������׹߼�</title>
</head>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

   
<? echo "<b>" . $UserName . "</b>���� ȯ���մϴ�!" ; ?>
<a href="<?=$BackAddr2?>?WorkDate=<?=$WorkDate?>&BackAddr=wrk_silmooja.php"><b>[X]</b></a>


<center>
            
   <br><b>�ǹ��� ������Ȳ</b><br>
   
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