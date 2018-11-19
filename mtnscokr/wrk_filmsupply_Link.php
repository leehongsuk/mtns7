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
        

        $sQuery = "Select * From bas_smsidchk       ".
                  " Where Id = '".$spacial_UserId."' " ;
        $QrySmsIdChk = mysql_query($sQuery,$connect) ;         
        if  ($ArrSmsIdChk = mysql_fetch_array($QrySmsIdChk)) // 이부장.. 
        {
            session_start();
            session_unregister("logged_UserId");
            $logged_UserId = "bros56" ;
            session_register("logged_UserId");
        }
        ?>
<head>
<title>CSB(Cinema Score Board)</title>
</head>

<frameset rows="2*, 3*" cols="1*">  
  <?  
  $upaddr     = "wrk_filmsupply_Link_Up.php?".
                "WorkDate=".$WorkDate."&".
                "logged_UserId=".$logged_UserId."&".
                "spacial_UserId=".$spacial_UserId."&".
                "filmproduce=".$filmproduce ;
  $bottomaddr = "nosingo.htm" ;
  ?>
  <frame name="up"     scrolling="auto" marginwidth="10" marginheight="14" src="<?=$upaddr?>">
  <frame name="bottom" scrolling="auto" marginwidth="10" marginheight="14" src="<?=$bottomaddr?>">
</frameset>             

<noframes>
<body bgcolor="#FFFFFF" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000">
<p>이 페이지를 보려면, 프레임을 볼 수 있는 브라우저가 필요합니다.</p>
</body>
</noframes>

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
                window.location = '../index_cokr.php' ; 
                //-->
            </script>
        </body>              
        <?
    }
    ?>
</html>
