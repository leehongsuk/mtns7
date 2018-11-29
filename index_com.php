<?
    session_start();
?>
<html>
<?
    include "index_config.php";  // {[데이터 베이스]} : 환경설정
               
    $connect = dbconn() ;        // {[데이터 베이스]} : 연결

    mysql_select_db($cont_db) ;  // {[데이터 베이스]} : 디비선택               

//eq($actcode) ;
    if  ($actcode=="login")  // 로그인 ..
    {
        // 회원 로그인 체크
        $sQuery = "Select * From cfg_user          ".
                  " Where UserId ='".$UserID."'    ".
                  "   And UserPw ='".$UserPW."'    " ;
        $QryUser = mysql_query($sQuery,$connect) ;
        if  ($ArrUser = mysql_fetch_array($QryUser))
        {
            // 실무자 로그인 체크
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
    if  ($actcode=="logout")  // 로그아웃 ..
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

        <!-- 실무자 로그인 -->
        <head>
             <title>실무자 로그인</title>
        </head>



        <!--사용자로그인 첫화면-->
        <body onload="form1.UserID.focus();" BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>


        <!-- 자바스크립트 시작 -->
        <!-- 자바스크립트 시작 -->
        <!-- 자바스크립트 시작 -->

        <script language="JavaScript">
            <!--        
            function check_submit()
            {
               if  (!form1.UserID.value)
               {
                   alert("ID를 입력하여 주십시요");
                   form1.UserID.focus();
                   return false;
               }
               if  (!form1.UserPW.value)
               {
                   alert("Password를 입력하여 주십시요");
                   form1.UserPW.focus();
                   return false;
               }

               form1.actcode.value="login" ; // 히든값..

               return true;
            }
            //-->
        </script>
  
        <!-- 자바스크립트 끝! -->
        <!-- 자바스크립트 끝! -->
        <!-- 자바스크립트 끝! -->




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
                    <td align=right><b>암호</b></td>
                    <td align=left><input type=password name=UserPW value='<?=$UserPW?>' size=15 maxlength=20 class=input></td>
               </tr>
               <tr>
                    <td align=center align=center colspan=2>

                        <input type=submit value="로그인">

                        <!--사용자로그인 실패-->
                        <?
                        // 회원로그인이 실패하였을 경우 에러 표시
                        if  ( ($actcode=="login") && (!$ArrUser) )  
                        {
                            echo "<br><B>로그인실패:계정을 확인하세요</B>" ;
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

    mysql_close($connect) ;      // {[데이터 베이스]} : 단절
?>
</html>
