<?
    session_start();
?>
<html>
<?
    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";        // {[데이터 베이스]} : 환경설정
                   
        $connect = dbconn() ;        // {[데이터 베이스]} : 연결

        mysql_select_db($cont_db) ;  // {[데이터 베이스]} : 디비선택


        if  ($active)  // 수정확인 되었을 때...
        {
            $sQuery = "Select * From bas_modifyscore                    ".
                      " Where Theather   = '".substr($ShowRoom,0,4)."'  ".
                      "   And Room       = '".substr($ShowRoom,4,2)."'  ".
                      "   And Silmooja   = '".$silmooja_Code."'         ".
                      "   And Open       = '".substr($FilmTitle,0,6)."' ".
                      "   And Film       = '".substr($FilmTitle,6,2)."' ".
                      "   And UnitPrice  = '".$UnitPrice."'             ".
                      "   And ModifyDate = '".$WorkDate."'              " ;
            $QryModifyscore = mysql_query($sQuery,$connect) ;
            if  ($ArrModifyscore = mysql_fetch_array($QryModifyscore))
            {
                $sQuery = "Delete From bas_modifyscore                      ".
                          " Where Theather   = '".substr($ShowRoom,0,4)."'  ".
                          "   And Room       = '".substr($ShowRoom,4,2)."'  ".
                          "   And Silmooja   = '".$silmooja_Code."'         ".
                          "   And Open       = '".substr($FilmTitle,0,6)."' ".
                          "   And Film       = '".substr($FilmTitle,6,2)."' ".
                          "   And UnitPrice  = '".$UnitPrice."'             ".
                          "   And ModifyDate = '".$WorkDate."'              " ;
                mysql_query($sQuery,$connect) ;
            }

            if   ($ModifyValue != 0)
            {
                 $sQuery = "Insert Into bas_modifyscore         ".
                           "Values                              ".
                           "(                                   ".       
                           "      '".substr($ShowRoom,0,4)."',  ".
                           "      '".substr($ShowRoom,4,2)."',  ".
                           "      '".$silmooja_Code."',         ".
                           "      '".substr($FilmTitle,0,6)."', ".
                           "      '".substr($FilmTitle,6,2)."', ".
                           "      '".$Location."',              ".
                           "      '".$UnitPrice."',             ".
                           "      '".$WorkDate."',              ".
                           "      '".$ModifyValue."',           ".
                           "      '".$ModifyValue*$UnitPrice."' ".
                           ")                                   " ;
                 mysql_query($sQuery,$connect) ;
            }
        }
?>  
  <link rel=stylesheet href=./style.css type=text/css>
  <META HTTP-EQUIV=Content-Type CONTENT="text/html; charset=euc-kr">

  <head>

       <script>

       // 확인을 눌렸을때..
       function ok_click(sCode,sName)
       {
           write.action = "wrk_filmsupply_Link_Edt.php?"
                         + "logged_UserId=<?=$logged_UserId?>&"
                         + "WorkDate=<?=$WorkDate?>&"
                         + "FilmTitle=<?=$FilmTitle?>&"
                         + "silmooja_Code=<?=$silmooja_Code?>&"
                         + "ShowRoom=<?=$ShowRoom?>&"
                         + "Location=<?=$Location?>&"
                         + "UnitPrice=<?=$UnitPrice?>&"
                         + "active=true&"
                         + "BackAddr=wrk_filmsupply_Link_Up.php"
           write.submit() ;
       }
       </script>

  </head>


  <body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>


  <center>

   <BR>
   <BR>
   <BR>

   <form method=post name=write onsubmit="return check_submit()">

   <table border="1" cellpadding="2" cellspacing="0">
     <tr>
          <td align=center>스코어수정</td>
          <td align=center>

          <?
          // 기존에 수정된 내용이 있으면 보여준다. (가장 최근꺼만......)
          $sQuery = "Select * From bas_modifyscore                   ".
                    " Where Theather  = '".substr($ShowRoom,0,4)."'  ".
                    "   And Room      = '".substr($ShowRoom,4,2)."'  ".
                    "   And Silmooja  = '".$silmooja_Code."'         ".
                    "   And Open      = '".substr($FilmTitle,0,6)."' ".
                    "   And Film      = '".substr($FilmTitle,6,2)."' ".
                    "   And UnitPrice = '".$UnitPrice."'             ".
                    "   And ModifyDate <= '".$WorkDate."'            ".
                    " Order By ModifyDate Desc                       " ;
          $QryModifyscore = mysql_query($sQuery,$connect) ;
          if  ($ArrModifyscore = mysql_fetch_array($QryModifyscore))
          {
               ?>                 
               <input type=text name=ModifyValue value='<?=$ArrModifyscore["ModifyScore"]?>' size=8 maxlength=10 class=input>
               (<?=$ArrModifyscore["ModifyDate"]?>)
               <?
          }
          else
          {
               ?>
               <input type=text name=ModifyValue value='0' size=8 maxlength=10 class=input>
               <?
          }
          ?>          
          </td>
     </tr>
   </table>   
   
   <BR>
   <BR>

   <input type=button value="확인" onclick="ok_click()">

   <form>

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
