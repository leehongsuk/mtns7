<?  
    session_start();

    // ���������� �α��� �ߴ��� üũ�Ѵ�.
    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";

        $connect=dbconn();

        mysql_select_db($cont_db) ;

        $FilmOpen = substr($FilmTitle,0,6) ;
        $FilmCode = substr($FilmTitle,6,2) ;

        $sQuery = "Select * From bas_filmtitle   ".
                  " Where Open = '".$FilmOpen."' ".
                  "   And Code = '".$FilmCode."' " ;
        $QryFilmtitle = mysql_query($sQuery,$connect) ;
        if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $FilmtitleName = $ArrFilmtitle["Name"] ; // ��ȭ�̸�..
        }

        

        
        if  (($GongjiExec) && ($GongjiExec==true)) // �����ư�� ������..
        {                    
            if  ($gongji_body)
            {
                // ��޻翡 �Ҽӵ� �ǹ����� ����Ʈ..
                $sQuery = "Select Code As Silmooja, Name             ".
                          "  From bas_silmooja                       " ;
                $QrySilmooja = mysql_query($sQuery,$connect) ;
                while ($ArrSilmooja = mysql_fetch_array($QrySilmooja))
                {
                     $SilmoojaCode = $ArrSilmooja["Silmooja"] ;
                     $SilmoojaName = $ArrSilmooja["Name"] ;                     
                      
                     //
                     // �ʸ� �ǹ��ڿ� ���� �������
                     //
                     $sQuery = "Select Max(GongjiNo) As MaxGongjiNo   ".
                               "  From wrk_filmsilmoojagongji         ".
                               " Where Film     = '".$FilmTitle."'    ".
                               "   And Silmooja = '".$SilmoojaCode."' " ;
                     $QryMaxGongjiNo = mysql_query($sQuery,$connect) ;
                     if  ($ArrMaxGongjiNo = mysql_fetch_array($QryMaxGongjiNo))
                     {
                         $MaxGongjiNo = $ArrMaxGongjiNo["MaxGongjiNo"] + 1 ;
                     }

                     $newBody = str_replace("\r\n", "<br>", $gongji_body) ;

                     $sQuery = "Insert Into wrk_filmsilmoojagongji  ".
                               "Values                              ".
                               "(                                   ".
                               "      '".$FilmTitle."',             ".
                               "      '".$SilmoojaCode."',          ".
                               "      '".$MaxGongjiNo."',           ".
                               "      '".$gongji_title."',          ".
                               "      '".$newBody."',               ".                      
                               "      '".$FilmtitleName."',         ".
                               "      '".$SilmoojaName."'           ".
                               ")                                   " ;
                     mysql_query($sQuery,$connect) ;

                     // �ǹ��� �� ���� ����......
                     $sQuery = "Update bas_silmooja                 ".
                               "   Set Gongji = '".$newBody."'      ".
                               " Where Code   = '".$SilmoojaCode."' " ;
                     mysql_query($sQuery,$connect) ;
                }


                //
                // �ʸ��� ���� �������
                //
                $sQuery = "Select Max(GongjiNo) As MaxGongjiNo   ".
                          "  From wrk_filmgongji                 ".
                          " Where Film     = '".$FilmTitle."'    " ;
                $QryMaxGongjiNo = mysql_query($sQuery,$connect) ;
                if  ($ArrMaxGongjiNo = mysql_fetch_array($QryMaxGongjiNo))
                {
                    $MaxGongjiNo = $ArrMaxGongjiNo["MaxGongjiNo"] + 1 ;
                }

                $newBody = str_replace("\r\n", "<br>", $gongji_body) ;
                
                $sQuery = "Insert Into wrk_filmgongji  ".
                          "Values                      ".
                          "(                           ".
                          "      '".$FilmTitle."',     ".
                          "      '".$MaxGongjiNo."',   ".
                          "      '".$gongji_title."',  ".
                          "      '".$newBody."',       ".
                          "      '".$FilmtitleName."'  ".
                          ")                           " ;
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

<body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 >

   
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
   
   <?
   echo "<script>\n" ;

   echo "function select_click(index) { \n" ;

   $Index = 0 ;
   $sQuery = "Select * From wrk_filmgongji    ".
             " Where Film  = '".$FilmTitle."' ".
             " Order By GongjiNo Desc         ".
             " Limit 0,10                     " ;
   $QryFilmgongji = mysql_query($sQuery,$connect) ; 
   while ($ArrFilmgongji = mysql_fetch_array($QryFilmgongji))
   {
        $Index ++ ;

        echo " temp = '".$ArrFilmgongji["Body"]."'.split('<br>') ; " ;

        echo "if (index==".$Index.") \n " ;
        echo "{ \n" ;
        echo "  write.gongji_title.value = '".$ArrFilmgongji["Title"]."' ; \n" ;
        echo "  write.gongji_body.value  = ''                  \n" ;
        echo "  for(i=0;i<temp.length;i++)                     \n" ;
        echo "  {                                              \n" ;
        echo "     write.gongji_body.value +=  temp[i]+'\\n'   \n" ;
        echo "  }                                              \n" ;
        echo "} \n";
   }

   echo "}\n" ;

   echo "</script>\n" ;
   ?>


<center>
            
   <br><b>�ǹ��� ��ü �������׹߼�</b><br>
   
   <form method=post action=<? echo $PHP_SELF."?GongjiExec=true&FilmTitle=".$FilmTitle."&logged_UserId=".$logged_UserId ; ?> name=write onsubmit="return check_submit()">
       <table border=0>
       <tr>
            <td><B>����</B></td>
            <td><input name="gongji_title" size=52 type="text"><br></td>
       </tr>
       <tr>
            <td valign=top><B>����</B></td>
            <td><textarea name="gongji_body" rows="7" cols="50" wrap="virtual" dir="ltr"></textarea></td>
       </tr>
       </table>
   <br>
   
   <input type="submit" name="save" value="����" />

   </form>


   <table>
      <tr>
           <td colspan=2>-�ֱ� ���۸��-</td>
      </tr>
      <?
      $Index = 0 ;
   
      $sQuery = "Select * From wrk_filmgongji    ".
                " Where Film  = '".$FilmTitle."' ".
                " Order By GongjiNo Desc         ".
                " Limit 0,11                     " ;
      $QryFilmgongji = mysql_query($sQuery,$connect) ; 
      while ($ArrFilmgongji = mysql_fetch_array($QryFilmgongji))
      {
            $Index ++ ;
            if  ($Index==11)
            {
                // �ֱ� 11 ��° ������ �ڷ�� �������..
                $sQuery = "Delete From wrk_filmgongji                        ".
                          " Where Film     = '".$FilmTitle."'                ".
                          "   And GongjiNo <= ".$ArrFilmgongji["GongjiNo"]." " ;
                mysql_query($sQuery,$connect) ; 
            }
            else
            {
                ?>
                <tr>
                     <td align=right><B><?=$Index?></B></td>
                     <td><A HREF="#" onclick="select_click(<?=$Index?>)"><?=$ArrFilmgongji["Title"]?></A></td>
                </tr>
                <?
            }
      }
      ?>
   </table>

</center>

</body>


        <?
        mysql_close($connect);       
    }
    else // �α������� �ʰ� �ٷε��´ٸ�..
    {
        ?>
        
        <!-- �α������� �ʰ� �ٷε��´ٸ� -->
        <body>
            <script language="JavaScript">
                <!-- 
                window.top.location = '../index_cokr.php' ; 
                //-->
            </script>
        </body>      
        
        <?
    }
    ?>
</html>
 