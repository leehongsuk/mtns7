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

        // �ش�ǹ��ڸ� ���ϰ� ($silmoojaName) ..
        $sQuery = "Select * From bas_silmooja    ".
                  " Where UserId = '".$UserId."' " ;
        $QrySilmooja = mysql_query($sQuery,$connect) ;
        if  ($ArrSilmooja = mysql_fetch_array($QrySilmooja))
        {         
            $silmoojaCode = $ArrSilmooja["Code"] ;   // �ǹ��� �ڵ�
        }
?>

<html>

<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>�ǹ��� ��ü �������׹߼�</title>
</head>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

   
<? echo "<b>" . $UserName . "</b>���� ȯ���մϴ�!" ; ?>
<a href="<?=$BackAddr?>?WorkDate=<?=$WorkDate?>"><b>[X]</b></a>


<center>
            
   <br><b>�ǹ��� ������Ȳ</b><br>
   
   <table>
      <tr>
           <td colspan=3>-�ֱ� ���۸��-</td>
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
                // �ֱ� 11 ��° ������ �ڷ�� �������..
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