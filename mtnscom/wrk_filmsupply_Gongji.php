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

        mysql_select_db($cont_db) ;
    
        
        if  (($GongjiExec) && ($GongjiExec=="true"))
        {
            if  ($gongji!="")
            {
                $newGongji = str_replace("\r\n", "<br>", $gongji) ;

                $sQuery = "Update bas_silmooja                       ".
                          "   Set Gongji     = '".$newGongji."'      " ;
                mysql_query($sQuery,$connect) ;
            }
            else
            {
                $sQuery = "Update bas_silmooja                        ".
                          "   Set Gongji      = 'none'                " ;
                mysql_query($sQuery,$connect) ;
            }
        }
?>

<html>

<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>�ǹ��� ��ü �������׹߼�</title>
</head>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

   
   <script>
         function check_submit()
         {
              //alert(write.bigo.value) ;
              //alert(opener.top.frames.bottom.document.bigo<?=$ShowRoom.$FilmTitle?>.value) ;
              //alert(opener.top.frames.bottom.document.all.bigo<?=$ShowRoom.$FilmTitle?>.innerHTML) ;
              //opener.top.frames.bottom.document.all.bigo<?=$ShowRoom.$FilmTitle?>.innerHTML = write.bigo.value ;

              return true ;
         }
   </script>


<? echo "<b>" . $UserName . "</b>���� ȯ���մϴ�!" ; ?>
<a OnClick="location.href='../index_com.php?actcode=logout'"><b>[LogOut]</b></a>
<a href="#" OnClick="self.close();"><b>[X]</b></a>


<center>
            
   <br><b>�ǹ��� ��ü �������׹߼�</b><br>
   
   <form method=post action=<? echo $PHP_SELF."?GongjiExec=true&logged_UserId=".$logged_UserId ; ?> name=write onsubmit="return check_submit()">
   
   <textarea name="gongji" rows="7" cols="50" wrap="virtual" dir="ltr"></textarea>
   <input type="submit" name="save" value="����" />

   </form>

</center>

</body>


</html>

<?
    mysql_close($connect);

    }
?>