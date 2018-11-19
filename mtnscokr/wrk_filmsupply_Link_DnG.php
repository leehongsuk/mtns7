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

        // 영화타이틀을 구한다.
        $qryfilmtitle = mysql_query("Select * From bas_filmtitle               ".
                                    " Where Open = '".substr($FilmTile,0,6)."' ".
                                    "   and Code = '".substr($FilmTile,6,2)."' ") ;
        if  ($filmtitle_data = mysql_fetch_array($qryfilmtitle))
        {
            $filmtitleCode = $filmtitle_data["Open"].$filmtitle_data["Code"] ;
            $filmtitleName = $filmtitle_data["Name"] ;
        }


        $timestamp2 = mktime(0,0,0,substr($FromDate,4,2),substr($FromDate,6,2),substr($FromDate,0,4));
        $dur_time2  = (time() - $timestamp2) / 86400;      

        $timestamp1 = mktime(0,0,0,substr($ToDate,4,2),substr($ToDate,6,2),substr($ToDate,0,4));
        $dur_time1  = (time() - $timestamp1) / 86400;       

        $dur_day    = $dur_time2 - $dur_time1 + 1 ;  // 일수
?>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>극장별부금정산</title>
</head>

<body>
   <script>
         //
         // 상영관선택
         //
         function showroom_click()
         {
             popupaddr = "wrk_filmsupply_Link_DnG1.php?"
                       + "logged_UserId=<?=logged_UserId?>"  ;

             popupoption = "status=0, "
                         + "menubar=0, "
                         + "scrollbars=yes, "
                         + "resizable=yes, "
                         + "width=400, "
                         + "height=500" ;

             window.open(popupaddr,'',popupoption) ;
         }

         //
         //   숫자만 입력 받도록 제한한다.
         //
         //
         //

         function score_check()
         {
            edit = write.rate.value ;

            if ((edit !="") && (edit.search(/\D/) != -1))
            {
                alert("숫자만 입력시오!") ;

                write.score.value = "";

                edit = edit.replace(/\D/g, "")

                write.score.focus() ;
                write.score.select();

                return false ;
            }
            else
            {
                return true ;
            }
         }

         //
         //  "계산" 을 눌렸을때..
         //

         function calc_click()
         {
             if  (write.rate.value == "")
             {
                 alert("요율이 없읍니다.!") ;

                 return false ;
             }
             
             if  (write.showroom.value == "")
             {
                 alert("상영관이 선택되지 않았읍니다.!") ;

                 return false ;
             }             

             if   (score_check()==false)  return false ;

             submitaddr = "<?=$PHP_SELF?>?"
                        + "logged_UserId=<?=$logged_UserId?>&"
                        + "FilmTile=<?=$FilmTile?>&"
                        + "FromDate=<?=$FromDate?>&"
                        + "ToDate=<?=$ToDate?>" ;

             write.action = submitaddr ;
             write.submit() ;
         }

    </script>
     
    <center>
    
    <form method=post name=write>

    <input name=showroom   type=hidden value='<?=$showroom?>'>       <!-- 상영관 -->
    <input name=filmsupply type=hidden value='<?=$filmsupplyCode?>'> <!-- 배급사코드 -->
    <input name=filmtitle  type=hidden value='<?=$filmtitleCode?>'>  <!-- 영화제목 -->
    <input name=fromdate   type=hidden value='<?=$FromDate?>'>       <!-- 시간일 -->
    <input name=todate     type=hidden value='<?=$ToDate?>'>         <!-- 종료일 -->
    
    <table width=90% border=1>    
    
    <tr>
    
    <td align=center colspan=3>영화공급가액 산출계산서</td>

    <td align=center>
    <input type=text   name=rate size=4 maxlength=2 class=input value=<?=$rate?>>%
    <input type=button name=calc value="계산" onclick="calc_click()">
    </td>
    
    </tr>

    <tr>
    <td width=25% align=center>영화관</td>
    <td align=center colspan=3>
    <?
    if  ($showroom)
    {
        $qry_showroom = mysql_query("Select * From bas_showroom                    ".
                                    " Where Theather = '".substr($showroom,0,4)."' ".
                                    "   And Room     = '".substr($showroom,4,2)."' ",$connect) ;
        if  ($showroomData = mysql_fetch_array($qry_showroom))
        {
            ?>
            <div id=showroomname>
            <a href="#" onclick="showroom_click()"><?=$showroomData["Discript"]?></a>
            </div>
            <?
        }
    }
    else
    {
        ?>
        <div id=showroomname>
        <a href="#" onclick="showroom_click()">[상영관 선택]</a>
        </div>
        <?
    }
    ?>

    </td>
    </tr>
    
    <tr>
    <td width=25% align=center>배급사</td>
    <?
        if  ($filmsupplyName) 
        {
            echo "<td colspan=3 align=center>".$filmsupplyName."</td>" ; 
        }
        else
        {
            echo "<td colspan=3 align=center>&nbsp;</td>" ;  
        }
    ?>    
    </tr>
    
    <tr>
    <td width=25% align=center>영화명</td>
    <?
        if  ($filmtitleName) 
        {
            echo "<td colspan=3 align=center>".$filmtitleName."</td>" ;  
        }
        else
        {
            echo "<td colspan=3 align=center>&nbsp;</td>" ;  
        }
    ?>
    
    </tr>
    
    <tr>
    <td width=25% align=center>공급기간</td>
    <td colspan=3>
    <?=substr($FromDate,0,4)?>년<?=substr($FromDate,4,2)?>월<?=substr($FromDate,6,2)?>일 ~ 
    <?=substr($ToDate,0,4)?>년<?=substr($ToDate,4,2)?>월<?=substr($ToDate,6,2)?>일 (<?=$dur_day?>일간)
    </td>
    </tr>
    <!--
    <tr>
    <td align=center colspan=4>수입금액</td>
    </tr>
    -->
    </table>

    </form>

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
