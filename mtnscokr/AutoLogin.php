<?
    session_start();
?>
<html>
<?
    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";        // {[������ ���̽�]} : ȯ�漳��
                   
        $connect = dbconn() ;        // {[������ ���̽�]} : ����

        mysql_select_db($cont_db) ;  // {[������ ���̽�]} : �����               

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

         if  ($CntUser>0) {  $ErrorMsg .= "�̹� ��ϵ� ���̵��Դϴ�.<BR>"  ; }

         $sQuery = "Select Count(*) As CntSilmooja       ".
                   "  From bas_silmooja                  ".
                   " Where Code = '".$RegSilmoojaCode."' " ;
         $QryCntSilmooja = mysql_query($sQuery,$connect) ;
         if  ($ArrCntSilmooja = mysql_fetch_array($QryCntSilmooja))
         {
              $CntSilmooja = $ArrCntSilmooja ["CntSilmooja"] ;
         }

         if  ($CntSilmooja>0) {  $ErrorMsg .= "�̹� ��ϵ� �ǹ��ھ��̵��Դϴ�.<BR>"  ; }

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
  <!-- �ڵ��α��� -->
  <head>
  <title>�ڵ��α���</title>
  </head>

  <link rel=stylesheet href=../mtnscom/style.css type=text/css>
  <META HTTP-EQUIV=Content-Type CONTENT="text/html; charset=euc-kr">

  <body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

  <script language="JavaScript">
      <!--
      function DeleteUser(userID) 
      {
          answer = confirm("������ �����Ͻð����ϱ�?") ;
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

             if  ($SortBy) // ��������..
             {
                 if  ($SortBy=="ID")   $sQuery = "select * from cfg_user order by UserId " ;
                 if  ($SortBy=="PW")   $sQuery = "select * from cfg_user order by UserPw " ;
                 if  ($SortBy=="Name") $sQuery = "select * from cfg_user order by Name " ;
             }
             $QryCfgUser= mysql_query($sQuery,$connect) ;
             ?>
             
             <B>�α��� ����Ʈ</B>

             <form method=post name=form1>             

             <input type="hidden" name="DeleteUserId">
             
             
             <table style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1 style="border-collapse:collapse">
                  <tr>
                      <td align=center width=80><a OnClick="location.href='<?=$PHP_SELF?>?SortBy=ID'">���̵�</a></td>
                      <td align=center width=70><a OnClick="location.href='<?=$PHP_SELF?>?SortBy=PW'">�� ȣ</a></td>
                      <td align=center width=45>�� ��</td>
                      <td align=center width=100>�ֹι�ȣ</td>
                      <td align=center width=60><a OnClick="location.href='<?=$PHP_SELF?>?SortBy=Name'">�� ��</a></td>
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
                          
                          <td align=center><input value="����" type="submit" onclick="return DeleteUser('<?=$UserId?>')"></td>

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

                     <td width=40><input value="�߰�" type="submit" onclick="return AddUser()"></td>
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
