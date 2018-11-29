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

    if   ($DeleteUserId!="")
    {
         $sQuery = "Delete From cfg_user                  ".
                   " Where UserId = '".$DeleteUserId."'   " ;
         mysql_query($sQuery,$connect) ;

         $sQuery = "Delete From bas_silmooja             ".
                   " Where UserId = '".$DeleteUserId."'  " ;
         mysql_query($sQuery,$connect) ;
    }

    if   ($AddUser1 == "Yes")
    {
         $ErrorMsg = "" ;
         
         $sQuery = "Select Count(*) As CntUser         ".
                   "  From cfg_user                    ".
                   " Where UserId = '".$RegUserID."'   " ;
         $QryCntUser = mysql_query($sQuery,$connect) ;
         if  ($ArrCntUser = mysql_fetch_array($QryCntUser))
         {
             $CntUser = $ArrCntUser["CntUser"] ;
         }

         if  ($CntUser>0) {  $ErrorMsg .= "이미 등록된 아이디입니다.<BR>"  ; }

         $sQuery = "Select Count(*) As CntSilmooja       ".
                   "  From bas_silmooja                  ".
                   " Where Code = '".$RegSilmoojaCode."' " ;
         $QryCntSilmooja = mysql_query($sQuery,$connect) ;
         if  ($ArrCntSilmooja = mysql_fetch_array($QryCntSilmooja))
         {
              $CntSilmooja = $ArrCntSilmooja ["CntSilmooja"] ;
         }

         if  ($CntSilmooja>0) {  $ErrorMsg .= "이미 등록된 실무자아이디입니다.<BR>"  ; }

         if  (($CntUser==0) && ($CntSilmooja==0))
         {
             $sQuery = "Insert into cfg_user       ".
                       "Values                     ".
                       "(                          ".
                       "      '".$RegUserID."',    ".
                       "      '".$RegUserPW."',    ".
                       "      '".$RegUserName."',  ".
                       "      '',                  ".
                       "      '',                  ".
                       "      '".$RegJuminNo."',   ".
                       "      ''                   ".
                       ")                          " ;
             mysql_query($sQuery,$connect) ;

             $sQuery = "Insert into bas_silmooja      ".
                       "Values                        ".
                       "(                             ".
                       "      '".$RegSilmoojaCode."', ".
                       "      '".$RegUserID."',       ".
                       "      '".$RegUserPW."',       ".
                       "      '".$RegUserName."',     ".
                       "      '',                     ".
                       "      '',                     ".
                       "      '',                     ".
                       "      '".$RegJuminNo."',      ".
                       "      '',                     ".
                       "      '',                     ".
                       "      '',                     ".
                       "      '',                     ".
                       "      ''                      ".
                       ")                             " ;
             mysql_query($sQuery,$connect) ;
                       
         }
    }
?>
  <!-- 자동로그인 -->
  <head>
  <title>자동로그인</title>
  </head>

  <link rel=stylesheet href=../mtnscom/style.css type=text/css>
  <META HTTP-EQUIV=Content-Type CONTENT="text/html; charset=euc-kr">

  <body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

  <script language="JavaScript">
      <!--
      function DeleteUser(userID) 
      {
          answer = confirm("정말로 삭제하시겠읍니까?") ;
          if  (answer==true)
          {
              form1.DeleteUserId.value = userID ;

              return true ;
          }
          else
          {
              return false ;
          }           
      }

      function AddUser()
      {
           form2.AddUser1.value = "Yes" ;
           return true ;
      }
      //-->
  </script>

  <center>

          <br>

             <?           
             $sQuery = "select * from cfg_user Order by Name " ;

             if  ($SortBy) // 정렬조건..
             {
                 if  ($SortBy=="ID")   $sQuery = "select * from cfg_user order by UserId " ;
                 if  ($SortBy=="PW")   $sQuery = "select * from cfg_user order by UserPw " ;
                 if  ($SortBy=="Name") $sQuery = "select * from cfg_user order by Name " ;
             }
             $QryCfgUser= mysql_query($sQuery,$connect) ;
             ?>
             
             <B>로그인 리스트</B>

             <form method=post name=form1>             

             <input type="hidden" name="DeleteUserId">
             
             
             <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 style="border-collapse:collapse">
                  <tr>
                      <td align=center width=80><a OnClick="location.href='<?=$PHP_SELF?>?SortBy=ID'">아이디</a></td>
                      <td align=center width=70><a OnClick="location.href='<?=$PHP_SELF?>?SortBy=PW'">암 호</a></td>
                      <td align=center width=45>코 드</td>
                      <td align=center width=100>주민번호</td>
                      <td align=center width=60><a OnClick="location.href='<?=$PHP_SELF?>?SortBy=Name'">이 름</a></td>
                      <td align=center width=40>&nbsp;</td>
                  </tr>
                  <?
                  while ($ArrCfgUser = mysql_fetch_array($QryCfgUser))
                  {
                      $UserId   = $ArrCfgUser["UserId"] ;
                      $UserPw   = $ArrCfgUser["UserPw"] ;                      
                      $JuminNo  = $ArrCfgUser["Jumin"] ;
                      $UserName = $ArrCfgUser["Name"] ;

                      $sQuery = "Select * From bas_silmooja      ".
                                " Where UserId = '".$UserId."'   " ;
                      $QrySilmooja = mysql_query($sQuery,$connect) ;
                      if  ($ArrSilmooja = mysql_fetch_array($QrySilmooja)) 
                      {
                          $SilmoojaCode = $ArrSilmooja["Code"] . "&nbsp;" ;
                      }
                      else
                      {
                          $SilmoojaCode = "&nbsp;" ;
                      }
                      ?>
                      <tr>
                          <td>
                          <a OnClick="location.href='../index_com.php?actcode=login&UserID=<?=$UserId?>&UserPW=<?=$UserPw?>'">
                          <b><?=$UserId?></b></a>&nbsp;
                          </td>

                          <td><?=$UserPw?>&nbsp;</td>
                          
                          <td align=center><?=$SilmoojaCode?></td>

                          <td align=center>
                          <?
                          if  ($JuminNo != "") 
                          {                          
                              echo $JuminNo ;                          
                          }
                          else
                          { 
                              echo "&nbsp;" ;
                          } 
                          ?>
                          </td>
                          
                          <td>
                          <?                       
                          if  ($SilmoojaCode<>"&nbsp;")
                          {
                              echo $UserName."&nbsp;" ;                          
                          }
                          else
                          {
                              echo "<b>".$UserName."<b>&nbsp;" ;                          
                          } 
                          ?>
                          </td>
                          
                          <td align=center><input value="삭제" type="submit" onclick="return DeleteUser('<?=$UserId?>')"></td>

                      </tr>

                      
                      <?
                  }
                  ?>

                  
             </table>
              
              
             <BR><BR>

             <?
             $sQuery = "Select Max(Code) As MaxCode     ".
                       "  From bas_silmooja             ".
                       " Where Code <> '888888'         ".
                       "   And Code <> '777777'         ".
                       "   And Code <> '222222'         ".
                       "   And Code <> '111111'         " ;
             $QrySilmooja = mysql_query($sQuery,$connect) ;
             if  ($ArrSilmooja = mysql_fetch_array($QrySilmooja)) 
             {
                 $AutoSilmooCode =  $ArrSilmooja["MaxCode"] + 1 ;

                 if  ($AutoSilmooCode == 111111)  $AutoSilmooCode = 111112 ;
                 if  ($AutoSilmooCode == 222222)  $AutoSilmooCode = 222223 ;
                 if  ($AutoSilmooCode == 777777)  $AutoSilmooCode = 777778 ;
                 if  ($AutoSilmooCode == 888888)  $AutoSilmooCode = 888889 ;
             }
             ?>
             </form>
             
             <BR><BR>

             <form method=post name=form2>             

             <input type="hidden" name="AddUser1">

             <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 style="border-collapse:collapse">
                <tr>
                     <td class=textarea width=80 bgcolor=<?=$Color3?> align=center>
                     <input type=text name=RegUserID value='' size=11 maxlength=10>
                     </td>

                     <td width=70>
                     <input type=text name=RegUserPW value='' size=11 maxlength=10>
                     </td>

                     <td width=45>
                     <input type=text name=RegSilmoojaCode value='<?=$AutoSilmooCode?>' size=7 maxlength=6 readonly>
                     </td>
                     
                     <td width=100>
                     <input type=text name=RegJuminNo value='' size=15 maxlength=14>
                     </td>

                     <td width=60>
                     <input type=text name=RegUserName value='' size=9 maxlength=4>
                     </td>

                     <td width=40><input value="추가" type="submit" onclick="return AddUser()"></td>
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
