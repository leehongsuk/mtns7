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

        


        if   ($DeleteCode!="")
        {
             $sQuery = "Delete From bas_sangfilmtitle   ". 
                       " Where Code = '".$DeleteCode."' " ;
             mysql_query($sQuery,$connect) ;
        }


        $MaxCode =  1 ;

        $sQuery = "Select Max(Code) As MaxCode  ".
                  "  From bas_sangfilmtitle     " ;
        $QryMaxCode = mysql_query($sQuery,$connect) ;
        if  ($ArrMaxCode = mysql_fetch_array($QryMaxCode))
        {
            $MaxCode = $ArrMaxCode["MaxCode"] + 1 ;
        }

        


        if   ($AddSang == "Yes")
        {
             $ErrorMsg = "" ;
             
             $sQuery = "Select Count(*) As CntCode  ".
                       "  From bas_sangfilmtitle    ".
                       " Where Code = '".$Code."'   " ;
             $QryCntCode = mysql_query($sQuery,$connect) ;
             if  ($ArrCntCode = mysql_fetch_array($QryCntCode))
             {
                 $CntCode = $ArrCntCode["CntCode"] ;
             }

             if  ($CntCode>0) {  $ErrorMsg .= "이미 등록된 아이디입니다.<BR>"  ; }

             if  ($CntCode==0) 
             {
                 $sQuery = "Insert into bas_sangfilmtitle ".
                           "Values                        ".
                           "(                             ".
                           "      '".$Code."',            ".
                           "      '".$Name."',            ".
                           "      '".$TagName."',         ".
                           "      '".$FilmSupply."'       ".
                           ")                             " ;
                 mysql_query($sQuery,$connect) ;
             }
        }

        $ColorA =  '#ffebcd' ;
        $ColorB =  '#dcdcec' ;    
        $ColorC =  '#dcdcdc' ;
        $ColorD =  '#c0c0c0' ;
?>
  <!-- 자동로그인 -->
  <head>
  <title>자동로그인</title>
  </head>

  <link rel=stylesheet href=./LinkStyle.css type=text/css>
  <META HTTP-EQUIV=Content-Type CONTENT="text/html; charset=euc-kr">

<?
    
?>

  <body bgcolor=#fafafa  topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

  <script language="JavaScript">
      <!--
      function DeleteSang(sCode) 
      {
          answer = confirm("정말로 삭제하시겠읍니까?") ;
          if  (answer==true)
          {
              form1.DeleteCode.value = sCode ;

              return true ;
          }
          else
          {
              return false ;
          }           
      }

      function AddUser()
      {
           form2.AddSang.value = "Yes" ;
           return true ;
      }
      //-->
  </script>

  <center>

          <br>

             <?           
             $sQuery = "select * from bas_sangfilmtitle Order by Name " ;

             if  ($SortBy) // 정렬조건..
             {
                 if  ($SortBy=="Code") $sQuery = "select * from bas_sangfilmtitle order by Code " ;
                 if  ($SortBy=="Name") $sQuery = "select * from bas_sangfilmtitle order by Name " ;
             }
             $QryCfgUser= mysql_query($sQuery,$connect) ;
             ?>
             
             <B>상대영화 리스트</B>

             <form method=post name=form1>             

             <input type="hidden" name="DeleteCode">
             
             
             <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 style="border-collapse:collapse" bordercolor='#C0B0A0'>
                  <tr>
                      <td bgcolor=<?=$ColorA?> align=center width=70><a OnClick="location.href='<?=$PHP_SELF?>?SortBy=Code'">코 드</a></td>
                      <td bgcolor=<?=$ColorA?> align=center width=300><a OnClick="location.href='<?=$PHP_SELF?>?SortBy=Name'">이 름</a></td>
                      <td bgcolor=<?=$ColorA?> align=center width=300>이 름</td>
                      <td bgcolor=<?=$ColorA?> align=center width=60>구 분</td>
                      <td bgcolor=<?=$ColorA?> align=center width=60>&nbsp;</td>
                  </tr>
                  <?
                  while ($ArrCfgUser = mysql_fetch_array($QryCfgUser))
                  {
                      $Code       = $ArrCfgUser["Code"] ;
                      $Name       = $ArrCfgUser["Name"] ;
                      $TagName    = $ArrCfgUser["TagName"] ;
                      if  ($ArrCfgUser["FilmSupply"]=="20003")
                      {
                          $FilmSupply = "예상" ;
                      }
                      else
                      {
                          $FilmSupply = "" ;
                      }
                      ?>
                      <tr>
                          <td bgcolor=<?=$ColorC?> align=center><?=$Code?>&nbsp;</td>
                          <td bgcolor=<?=$ColorC?>><?=$Name?>&nbsp;</td>
                          <td bgcolor=<?=$ColorC?>><?=$TagName?>&nbsp;</td>
                          <td bgcolor=<?=$ColorC?> align=center><?=$FilmSupply?>&nbsp;</td>
                          <td bgcolor=<?=$ColorC?> align=center><input value="삭제" type="submit" onclick="return DeleteSang('<?=$Code?>')"></td>
                      </tr>
                      <?
                  }
                  ?>

                  
             </table>
              
              
             <BR><BR>

             </form>
             
             <BR><BR>

             <form method=post name=form2>             

             <input type="hidden" name="AddSang">

             <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 style="border-collapse:collapse" bordercolor='#C0B0A0'>
                <tr>
                     <td bgcolor=<?=$ColorA?> width=70 align=center>
                     <input type=text name=Code     value='<?=$MaxCode?>' size=70 maxlength=3>
                     </td>

                     <td bgcolor=<?=$ColorA?> width=300>
                     <input type=text name=Name     value='' size=300 maxlength=100>
                     </td>
  
                     <td bgcolor=<?=$ColorA?> width=300>
                     <input type=text name=TagName  value='' size=300 maxlength=100>
                     </td>

                     <td bgcolor=<?=$ColorA?> width=70>
                         <select name="FilmSupply">
                             <option value="">
                             <option value="20003">예상
                         </select>
                     </td>

                     <td bgcolor=<?=$ColorA?> width=60 align=center>
                     <input value="추가" type="submit" onclick="return AddUser()">
                     </td>
                </tr>
                <?
                if  ($ErrorMsg != "")
                {
                ?>
                <tr>
                   <td colspan=6><?=$ErrorMsg?></td>
                </tr>
                <?
                } 
                ?>
             </table>

             </form>

             <BR><BR>

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
