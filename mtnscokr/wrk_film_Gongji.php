<?  
    session_start();

    // 정상적으로 로그인 했는지 체크한다.
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
            $FilmtitleName = $ArrFilmtitle["Name"] ; // 영화이름..
        }

        

        
        if  (($GongjiExec) && ($GongjiExec==true)) // 저장버튼을 누를때..
        {                    
            if  ($gongji_body)
            {
                // 배급사에 소속된 실무자의 리스트..
                $sQuery = "Select Code As Silmooja, Name             ".
                          "  From bas_silmooja                       " ;
                $QrySilmooja = mysql_query($sQuery,$connect) ;
                while ($ArrSilmooja = mysql_fetch_array($QrySilmooja))
                {
                     $SilmoojaCode = $ArrSilmooja["Silmooja"] ;
                     $SilmoojaName = $ArrSilmooja["Name"] ;                     
                      
                     //
                     // 필름 실무자에 대한 공지기록
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

                     // 실무자 당 공지 갱신......
                     $sQuery = "Update bas_silmooja                 ".
                               "   Set Gongji = '".$newBody."'      ".
                               " Where Code   = '".$SilmoojaCode."' " ;
                     mysql_query($sQuery,$connect) ;
                }


                //
                // 필름에 대한 공지기록
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
<title>실무자 전체 공지사항발송</title>
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
            
   <br><b>실무자 전체 공지사항발송</b><br>
   
   <form method=post action=<? echo $PHP_SELF."?GongjiExec=true&FilmTitle=".$FilmTitle."&logged_UserId=".$logged_UserId ; ?> name=write onsubmit="return check_submit()">
       <table border=0>
       <tr>
            <td><B>제목</B></td>
            <td><input name="gongji_title" size=52 type="text"><br></td>
       </tr>
       <tr>
            <td valign=top><B>내용</B></td>
            <td><textarea name="gongji_body" rows="7" cols="50" wrap="virtual" dir="ltr"></textarea></td>
       </tr>
       </table>
   <br>
   
   <input type="submit" name="save" value="저장" />

   </form>


   <table>
      <tr>
           <td colspan=2>-최근 전송목록-</td>
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
                // 최근 11 번째 이후의 자료는 다지운다..
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
    else // 로그인하지 않고 바로들어온다면..
    {
        ?>
        
        <!-- 로그인하지 않고 바로들어온다면 -->
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
 