<?
    session_start();
?>
<html>
<?
    include "index_config.php";  // {[������ ���̽�]} : ȯ�漳��
               
    $connect = dbconn() ;        // {[������ ���̽�]} : ����

    mysql_select_db($cont_db) ;  // {[������ ���̽�]} : �����               

//eq($actcode) ;
    if  ($actcode=="login")  // �α��� ..
    {
        // ȸ�� �α��� üũ
        $sQuery = "Select * From cfg_user          ".
                  " Where UserId ='".$UserID."'    ".
                  "   And UserPw ='".$UserPW."'    " ;
        $QryUser = mysql_query($sQuery,$connect) ;
        if  ($ArrUser = mysql_fetch_array($QryUser))
        {
            // �ǹ��� �α��� üũ
            $sQuery = "Select * From bas_silmooja     ".
                      " Where UserId ='".$UserID."'   " ;
            $QrySilmooja = mysql_query($sQuery,$connect) ;
            if  ($ArrSilmooja = mysql_fetch_array($QrySilmooja))
            {
                session_register("UserId") ;
                session_register("UserPw") ;
                session_register("UserName") ;

                $UserId   = $ArrSilmooja["UserId"]  ;
                $UserPw   = $ArrSilmooja["UserPw"]  ;
                $UserName = $ArrSilmooja["Name"]  ;
            }            
        }
        if  ($UserId == "bros56")
        {
            session_register("UserId") ;
            session_register("UserPw") ;
            session_register("UserName") ;

            $UserId   = $ArrSilmooja["UserId"]  ;
            $UserPw   = $ArrSilmooja["UserPw"]  ;
            $UserName = $ArrSilmooja["Name"]  ;
        }
    }
    if  ($actcode=="logout")  // �α׾ƿ� ..
    {
        session_unregister("UserId") ;
        session_unregister("UserPw") ;
        session_unregister("UserName") ;

        $UserId   = null ;
        $UserPw   = null ;
        $UserName = null ;
    }    
     
    

    //        
    if  ($UserId == null)
    {
        ?>
        

        <link rel=stylesheet href=./style_com.css type=text/css>
        <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

        <!-- �ǹ��� �α��� -->
        <head>
             <title>�ǹ��� �α���</title>
        </head>



        <!--����ڷα��� ùȭ��-->
        <body onload="form1.UserID.focus();" BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>


        <!-- �ڹٽ�ũ��Ʈ ���� -->
        <!-- �ڹٽ�ũ��Ʈ ���� -->
        <!-- �ڹٽ�ũ��Ʈ ���� -->

        <script language="JavaScript">
            <!--        
            function check_submit()
            {
               if  (!form1.UserID.value)
               {
                   alert("ID�� �Է��Ͽ� �ֽʽÿ�");
                   form1.UserID.focus();
                   return false;
               }
               if  (!form1.UserPW.value)
               {
                   alert("Password�� �Է��Ͽ� �ֽʽÿ�");
                   form1.UserPW.focus();
                   return false;
               }

               form1.actcode.value="login" ; // ���簪..

               return true;
            }
            //-->
        </script>
  
        <!-- �ڹٽ�ũ��Ʈ ��! -->
        <!-- �ڹٽ�ũ��Ʈ ��! -->
        <!-- �ڹٽ�ũ��Ʈ ��! -->




        <form name=form1 method=post action="index_com.php" onsubmit="return check_submit();">
           
        <center>
        <br>
           <input type="hidden" name="actcode">

           <table cellpadding=1 cellspacing=0 width=250 border=0>
               <tr>
                    <td align=right><b>ID</b></td>
                    <td align=left><input type=text name=UserID value='<?=$UserID?>' size=15 maxlength=20 class=input></td>
               </tr>
               <tr>
                    <td align=right><b>��ȣ</b></td>
                    <td align=left><input type=password name=UserPW value='<?=$UserPW?>' size=15 maxlength=20 class=input></td>
               </tr>
               <tr>
                    <td align=center align=center colspan=2>

                        <input type=submit value="�α���">

                        <!--����ڷα��� ����-->
                        <?
                        // ȸ���α����� �����Ͽ��� ��� ���� ǥ��
                        if  ( ($actcode=="login") && (!$ArrUser) )  
                        {
                            echo "<br><B>�α��ν���:������ Ȯ���ϼ���</B>" ;
                        }
                        ?>
                    
                    </td>
               </tr>
           </table>
           
        </center>

        </form>

        
        </body>
        <?    
    }
    else    
    {
        if  ($UserId == "bros56")
        {
            echo "<script language='JavaScript'>window.location = 'mtnscom/wrk_filmsupply.php'</script>";
        }
        else
        {
            echo "<script language='JavaScript'>window.location = 'mtnscom/wrk_silmooja.php'</script>";
        }
    }

    mysql_close($connect) ;      // {[������ ���̽�]} : ����
?>
</html>
