<?
    include "config.php";

    session_start();

    $connect=dbconn();

    mysql_select_db($cont_db) ;

    if  ($actcode=="insert")  // �űԻ���� ��Ͻ� ... insert ��
    {
        $sQuery = "Select * From cfg_user Where UserId ='".$UserID."'" ;
        $result = mysql_query($sQuery,$connect) ;
        if  ($member_data = mysql_fetch_array($result))
        {
            mysql_close($connect) ;
            ?>
            <html>
            <body BGCOLOR='#666699'>

            <script language='JavaScript'>
                alert('�̹� ���� ���̵�� ��ϵ� ����ڰ� �����ϴ�.') ;
                window.location = 'index.php?actcode=logout';
            </script>

            </body>
            </html>          
            <?
        }
        else
        {
            if  (($UserID != "") && ($UserPW1 != ""))
            {
                $sQuery = "Insert Into cfg_user      ".
                          "Values ('".$UserID."',    ".
                          "        '".$UserPW1."',   ".
                          "        '".$UserName."',  ".
                          "        '".$eMail."',     ".
                          "        '".$Homepage."',  ".
                          "        '".$Jumin."',     ".
                          "        '".$Discript."'   ".
                          "       )                  " ;
                mysql_query($sQuery,$connect) ;

                $sQuery = "Select * From bas_silmooja ".
                          " Where Jumin ='".$Jumin."' " ;
                $silmooja = mysql_query($sQuery,$connect) ;
                if  ($silmooja_data = mysql_fetch_array($silmooja))  // ���Խ� �ֹι�ȣ�� ��ġ�ϴ� �ǹ��ڰ� ������쿡�� �ǹ����� �α��� ������ �����Ѵ�.
                {
                    $sQuery = "Update bas_silmooja            ".
                              "   SET UserId = '".$UserID."', ".
                              "       UserPw = '".$UserPW1."' ".
                              " Where Jumin = '".$Jumin."'    " ;
                    mysql_query($sQuery,$connect) ;
                }

                $logged_SeqNo     = $SeqNo ;
                $logged_UserId    = $UserID ;
                $logged_UserPw    = $UserPW1 ;
                $logged_Name      = $Name ;
                $logged_eMail     = $eMail ;
                $logged_Homepage  = $Homepage ;
                $logged_Jumin     = $Jumin ;
                $logged_Discript  = $Discript ;
                $logged_Time      = time();

                session_register("logged_UserId") ;
                session_register("logged_UserPw") ;
                session_register("logged_Name") ;
                session_register("logged_eMail") ;
                session_register("logged_Homepage") ;
                session_register("logged_Jumin") ;
                session_register("logged_Discript") ;
                session_register("logged_Time") ;
            }
        }
    }

    if  ($actcode=="update")  // �α��εǾ��ִ� ����� ������ update��
    {
        $sQuery = "Update cfg_user                       ".
                  "   Set UserPW     = '".$UserPW1."',   ".
                  "       Name       = '".$UserName."',  ".
                  "       eMail      = '".$eMail."',     ".
                  "       Homepage   = '".$Homepage."',  ".
                  "       Jumin      = '".$Jumin."',     ".
                  "       Discript   = '".$Discript."'   ".
                  " Where UserID = '".$UserID."'         " ;
        mysql_query($sQuery,$connect) ;

        if  ($beforJumin)  
        {
            $sQuery = "Update bas_silmooja              ".
                      "   Set UserId = '',              ".
                      "       UserPw = ''               ".
                      " Where Jumin = '".$beforJumin."' " ;
            mysql_query(,$connect) ;
        }
        
        $sQuery = "Select * From bas_silmooja Where Jumin ='".$Jumin."' " ;
        $silmooja = mysql_query($sQuery,$connect) ;
        if  ($silmooja_data = mysql_fetch_array($silmooja))  // ���Խ� �ֹι�ȣ�� ��ġ�ϴ� �ǹ��ڰ� ������쿡�� �ǹ����� �α��� ������ �����Ѵ�.
        {
            $sQuery = "Update bas_silmooja            ".
                      "   Set UserId = '".$UserID."', ".
                      "       UserPw = '".$UserPW1."' ".
                      " Where Jumin = '".$Jumin."'    " ;
            mysql_query($sQuery,$connect) ;
        }


        session_unregister("logged_UserId") ;
        session_unregister("logged_UserPw") ;
        session_unregister("logged_Name") ;
        session_unregister("logged_eMail") ;
        session_unregister("logged_Homepage") ;
        session_unregister("logged_Jumin") ;
        session_unregister("logged_Discript") ;
        session_unregister("logged_Time") ;

        $logged_SeqNo     = $SeqNo ;
        $logged_UserId    = $UserID ;
        $logged_UserPw    = $UserPW1 ;
        $logged_Name      = $Name ;
        $logged_eMail     = $eMail ;
        $logged_Homepage  = $Homepage ;
        $logged_Jumin     = $Jumin ;
        $logged_Discript  = $Discript ;
        $logged_Time      = time();

        session_register("logged_UserId") ;
        session_register("logged_UserPw") ;
        session_register("logged_Name") ;
        session_register("logged_eMail") ;
        session_register("logged_Homepage") ;
        session_register("logged_Jumin") ;
        session_register("logged_Discript") ;
        session_register("logged_Time") ;
    }

    if  ($actcode=="secession")  // �α��εǾ��ִ� ����� Ż��ó��..
    {
        $sQuery = "Delete From cfg_user          ".
                  " Where UserID = '".$UserID."' " ;
        mysql_query($sQuery,$connect) ;

        $sQuery = "Select * From bas_silmooja Where Jumin ='".$Jumin."' " ;
        $silmooja = mysql_query($sQuery,$connect) ;
        if  ($silmooja_data = mysql_fetch_array($silmooja))  // ���Խ� �ֹι�ȣ�� ��ġ�ϴ� �ǹ��ڰ� ������쿡�� �ǹ����� �α��� ������ �����Ѵ�.
        {
            $sQuery = "Update bas_silmooja         ".
                      "   Set UserId = '',         ".
                      "       UserPw = ''          ".
                      " Where Jumin = '".$Jumin."' " ;
            mysql_query($sQuery,$connect) ;
        }

        session_unregister("logged_UserId") ;
        session_unregister("logged_UserPw") ;
        session_unregister("logged_Name") ;
        session_unregister("logged_eMail") ;
        session_unregister("logged_Homepage") ;
        session_unregister("logged_Jumin") ;
        session_unregister("logged_Discript") ;
        session_unregister("logged_Time") ;
    }

    mysql_close($connect) ;

    // index.php �� �̵��Ѵ�.
    if  ($actcode=="secession")  // �α��εǾ��ִ� ����� Ż��ó��..
    {
        echo "<script language='JavaScript'>window.location = 'index.php?actcode=logout'</script>";
    }
    else
    {
        echo "<script language='JavaScript'>window.location = 'index.php?UserID=$UserID&UserPW=$UserPW1&actcode=login'</script>";
    }  
?>
