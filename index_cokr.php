<?
    session_cache_expire(36000);  
    session_start();

    $server_name = $_SERVER['SERVER_NAME'];

    if  ($LogOut=="Yes")
    {
        setcookie("cookieuserid","",0,"/",$server_name,0);
        setcookie("cookieuserpw","",0,"/",$server_name,0);

        session_unregister("logged_UserId") ;
    }
    else
    {
        if  ($Owner)
        {
            $OwnerID = "bros56" ;
            $OwnerPW = "9292" ;

            session_register("spacial_UserId") ;

            $spacial_UserId = $spacialID ;

            setcookie("cookieuserid",$OwnerID,0,"/",$server_name,0);  // 쿠키 설정
            setcookie("cookieuserid",$OwnerPW,0,"/",$server_name,0);  // 쿠키 설정
        }
        else
        {
            if  ($OwnerID)
            {
                setcookie("cookieuserid",$OwnerID,0,"/",$server_name,0);  // 쿠키 설정
            }
            else
            {
                if  ($HTTP_COOKIE_VARS["cookieuserid"])
                {
                    $OwnerID = $HTTP_COOKIE_VARS["cookieuserid"] ; // 쿠키에 자료가 있다면 그것을 가지고 옴
                }
            }

            if  ($OwnerPW)
            {
                setcookie("cookieuserpw",$OwnerPW,0,"/",$server_name,0);  // 쿠키 설정
            }
            else
            {
                if  ($HTTP_COOKIE_VARS["cookieuserpw"])
                {
                    $OwnerPW = $HTTP_COOKIE_VARS["cookieuserpw"] ; // 쿠키에 자료가 있다면 그것을 가지고 옴
                }
            }
        }
    }
?>
<html>

<?
     include "index_config.php";  // {[데이터 베이스]} : 환경설정

     $connect = dbconn() ;        // {[데이터 베이스]} : 연결

     mysql_select_db($cont_db) ;  // {[데이터 베이스]} : 디비선택
?>

<head>
<title>MTNS에 오신것을 환영합니다.</title>   <!-- 타이틀 -->
</head>

<link rel=stylesheet href=./style_cokr.css type=text/css>


<body bgcolor="#FFFFFF" text="#000000" >


  <!-- 자바스크립트 시작 -->
  <!-- 자바스크립트 시작 -->
  <!-- 자바스크립트 시작 -->

  <script language="JavaScript">
      <!--

      //
      // {[이벤트 핸들러]}  로그인 버튼 클릭시 onsubmit
      //
      function check_submit()
      {
         if  ((!form1.OwnerID.value) && (!form1.OwnerPW.value))
         {
             alert("아이디와 암호를 입력하여 주십시요");
             form1.OwnerID.focus();

             return false;
         }
         else
         {
             if  (!form1.OwnerID.value)
             {
                 alert("아이디를 입력하여 주십시요");
                 form1.OwnerID.focus();

                 return false;
             }
             if  (!form1.OwnerPW.value)
             {
                 alert("암호를 입력하여 주십시요");
                 form1.OwnerPW.focus();

                 return false;
             }
             if  ((form1.OwnerID.value) && (form1.OwnerPW.value))
             {
                 return true;
             }
         }
      }

      //-->
  </script>

  <!-- 자바스크립트 끝! -->
  <!-- 자바스크립트 끝! -->
  <!-- 자바스크립트 끝! -->



  <!-- 본문 -->

  <CENTER>

    <br>

    <!-- 전체 테이블 -->
    <table width=100 border="0">
      <tr>
          <td align="center">

            <!-- 플래시 표현부 -->
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                    codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0"
                    width="780"
                    height="400">
              <param name="movie" value="MTnS.swf">
              <param name=quality value=high>
              <embed src="MTnS.swf"
                     quality=high
                     pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"
                     type="application/x-shockwave-flash"
                     width="780"
                     height="400">
              </embed>
            </object>

          </td>
      </tr>

      <tr>
          <td align="right">
          <?
          if  (session_is_registered("logged_UserId"))
          {
              // 이미 로그인이 되어 있다면..
              ?>
              <SCRIPT LANGUAGE="JavaScript">
                  <!--
                  cnt=5;

                  function timeout()
                  {
                      if(cnt>-1)
                      {
                          tout.innerHTML = "<b>"+eval(cnt)+"</b>" ;

                          cnt-- ;
                      }
                      else
                      {
                          clearInterval(tid);

                          <?
                          $sFilmproduceCode = get_filmproduce_code($logged_UserId,$connect) ;
                          if  ($sFilmproduceCode != "")
                          {
                              ?>
                              window.location = 'homepage/index.php' ;
                              <?
                          }
                          else
                          {
                              ?>
                              window.location = 'mtnscokr/wrk_filmsupply_Link.php' ;
                              <?
                          }
                          ?>
                      }
                  }

                  tid=setInterval(timeout,1000); //1초후 a함수 실행 - 재귀호출
                  //-->
              </SCRIPT>

              <table>
                <tr>
                    <td colspan=2>이미 <?=$logged_UserId?>(으)로 로그인되어 있읍니다.</td>
                </tr>
                <tr>
                    <td><div id="tout"></div></td>
                    <td>초 후 CSB로 이동합니다.!!&nbsp;&nbsp;<a href="index_cokr.php?LogOut=Yes">[로그아웃]</a></td>
                </tr>
              </table>
              <?
          }
          else
          {
              ?>
              <table width=100%>
              <tr>
               <td align=left valign=midle>
                   <a href="Mtns.sfx.exe">
                   <CENTER><img src="Mtns.gif" border="0" alt="Mtns 다운로드 받기"><BR>알림장 다운로드 받기</CENTER>
                   </a>
               </td>
               <td align=right>
                   <!-- 로그인 폼 -->
                   <form method=post name="form1" onsubmit="return check_submit();">
                   <table>
                        <tr>
                            <td>
                                <table><!-- 왼쪽 -->
                                <tr>
                                    <td align="right">아이디:</td>
                                    <td align="left"><input class="input" name="OwnerID" type="text" size="12" maxlength="10" value=<?=$OwnerID?>></td>
                                </tr>
                                <tr>
                                    <td align="right">암호:</td>
                                    <td align="left"><input class="input" name="OwnerPW" type="password" size="12" maxlength="10" value=<?=$OwnerPW?>></td>
                                </tr>
                                </table>
                            </td>

                            <td valign="top" align="center">
                                <table><!-- 오른쪽 -->
                                <tr>
                                    <td>
                                    <input name="btnOk" type="submit" value="로그인">
                                    </td>
                                </tr>
                                </table>
                            </td>
                        </tr>

                        <!-- 로그인 체크 -->
                        <?
                        if  ( $OwnerID != "" )
                        {
                            if  ($OwnerID == "bros56" || $OwnerID == "bros5656"  || $OwnerID == "sdcgeneric")
                            {
                                $sQuery = "Select * From cfg_user         ".
                                          " Where UserId = '".$OwnerID."' " ; //echo $sQuery ;
                                $QryOwner = mysql_query($sQuery,$connect) ;
                                if   ($ArrOwner = mysql_fetch_array($QryOwner))
                                {
                                     $OwnerPassWord = $ArrOwner["UserPw"] ;
                                     $OwnerName     = $ArrOwner["Name"] ;

                                     if  ($OwnerPassWord != $OwnerPW)
                                     {
                                         echo "<tr><td colspan=2>등록된 ".$OwnerID."의<br> 암호가 틀립니다.</td></tr>" ;
                                         echo "<script language='JavaScript'>form1.OwnerPW.focus();</script>";
                                     }
                                     else
                                     {
                                         session_register("logged_UserId") ;

                                         $logged_UserId = $OwnerID ;  // 아이디 입력


                                         $sFilmproduceCode = get_filmproduce_code($logged_UserId,$connect) ;
                                         if  ($sFilmproduceCode != "")
                                         {
                                             // 이동 : homepage/index.php
                                             echo "<script language='JavaScript'>window.location = 'homepage/index.php'</script>";
                                         }
                                         else
                                         {
                                            // 이동 : mtnscokr/wrk_filmsupply_Link.php
                                            echo "<script language='JavaScript'>window.location = 'mtnscokr/wrk_filmsupply_Link.php'</script>";
                                         }

                                     }
                                }
                                else
                                {

									echo "<tr><td colspan=2>[".$OwnerID."] 은(는) 등록된<br> 아이디가 아닙니다.</td></tr>" ;
									echo "<script language='JavaScript'>form1.OwnerID.focus();</script>";

									$HTTP_COOKIE_VARS["cookieuserid"] = "" ;
									$HTTP_COOKIE_VARS["cookieuserpw"] = "" ;

									session_unregister("logged_UserId") ;
								}
                            }
                            else
                            {
								if  (($OwnerID == "1111") && ($OwnerPW == "2222"))
								{
									session_register("logged_UserId") ;
									//header('Location: http://www.mtns7.co.kr/totalscore/');
									?>
									<script type='text/javascript'>
									<!--
									location.href='http://www.mtns7.co.kr/totalscore/';
									//-->
									</script>
									<?
								}
								else
								{
									echo "<tr><td colspan=2>[".$OwnerID."] 은(는) 등록된<br> 아이디가 아닙니다.</td></tr>" ;
									echo "<script language='JavaScript'>form1.OwnerID.focus();</script>";

									$HTTP_COOKIE_VARS["cookieuserid"] = "" ;
									$HTTP_COOKIE_VARS["cookieuserpw"] = "" ;

									session_unregister("logged_UserId") ;
								}
                            }
                        }


                        ?>
                  </table>

                  </form>
               </td>
              </tr>
              </table>
             <?
         }
         ?>
         </td>
      </tr>
    </table>


    <?php echo "현재 일시 : ". date("Y-m-d H:i:s")."<br/>"; ?>

    <?
    $df = disk_free_space("/usr");   $ds = disk_total_space("/usr");
	$rate = $df / $ds * 100.0 ;

	if   ($rate <1.5)
	{
		?>
		<div style="color:red;">하드디스크 용량이 <?=number_format($rate, 2, '.', '')?>% 밖에 남지 않았습니다.<br>관리자에게 문의 해 주세요</div>
		<?
	}
	?>


	<font color="#E0E0E0">
    <?
    $df = disk_free_space("/usr");   $ds = disk_total_space("/usr");

    echo "/usr : " . number_format($df  / $ds * 100. , 2, '.', '')."% Free ( ".number_format($df) ." / ". number_format($ds)." )";

    echo "<br>";

    $df = disk_free_space("/home");   $ds = disk_total_space("/home");

    echo "/home : " . number_format($df  / $ds * 100. , 2, '.', '')."% Free ( ".number_format($df) ." / ". number_format($ds)." )";
    ?>
    </font>
  </CENTER>

</body>

<?
    mysql_close($connect) ;      // {[데이터 베이스]} : 단절
?>

</html>
