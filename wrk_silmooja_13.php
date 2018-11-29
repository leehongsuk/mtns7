<?
    session_start();

    //
    // 실무자 - 극장(상영관)지정
    //

    include "config.php";

    // 정상적으로 로그인 했는지 체크한다.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = 'index_com.php'</script>";
    }
    else
    {
        $Today = time()-(3600*7) ; // 새벽 7시 까지 오늘로 간주한다...  

        if (!$WorkDate)
        {
           $WorkDate = date("Ymd",$Today) ;
        }

        $connect=dbconn();

        mysql_select_db($cont_db) ;

        // 상영관 리스트  
        $sQuery = "Select * From bas_showroom           ".
                  " Where Theather = '".$SunTheather."' " ;
        $query_showroom = mysql_query($sQuery,$connect) ;
        $count_showroom_row = mysql_affected_rows(); 

        if  ($Ouput) // 여기서 저장한다.
        {
            $sQuery = "Delete From wrk_sunjae1               ".
                      " Where Theather  = '".$SunTheather."' " ;
            mysql_query($sQuery,$connect) ;

            $Items2 = explode(",", $Ouput); // "," 로 파싱,,,

            $showroom_data = mysql_fetch_array($query_showroom) ;
            $showroom_Theather  = $showroom_data["Theather"] ;
            $showroom_Room      = $showroom_data["Room"] ;
            $showroom_Location  = $showroom_data["Location"] ;

            $sQuery = "Select * From bas_theather             ".
                      " Where Code = '".$showroom_Theather."' " ;
            $query_theather = mysql_query($sQuery,$connect) ;
            if  ($theather_data = mysql_fetch_array($query_theather)) 
            {
                $theatherName = $theather_data["Discript"] ;
            }
            else
            {
                $theatherName = "" ;
            }

            $sQuery = "Insert Into wrk_sunjae1           ".
                      "Values                            ".
                      "(                                 ".
                      "      '".$WorkDate."',            ".
                      "      '".$showroom_Theather."',   ".
                      "      '".$Items2[0]."',           ".
                      "      '".$Items2[1]."',           ".
                      "      '".$Items2[2]."',           ".
                      "      '".$Items2[3]."',           ".
                      "      '".$showroom_Location."',   ".
                      "      '".$theatherName."',        ".
                      "      '".$Bigo."',                ".
                      "      '".$FilmSupply."'           ".
                      ")                                 " ;
            mysql_query($sQuery,$connect) ;
        }


        // 해당실무자를 구하고 ..
        $sQuery = "Select * From bas_silmooja           ".
                  " Where UserId = '".$logged_UserId."' " ;
        $query_silmooja = mysql_query($sQuery,$connect) ;
        if  ($silmooja_data = mysql_fetch_array($query_silmooja))
        {
            $silmoojaCode       = $silmooja_data["Code"] ;
            $silmoojaName       = $silmooja_data["Name"] ;
            $silmoojaFilmSupply = $silmooja_data["FilmSupply"] ;
        }        
?>

<html>

<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>선재현황보고</title>
</head>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
  

   <script>
         //<!-- 전송 버튼 --> 
         function check_submit()
         {
             sOuput = "" ;
             
             if  (write.poster.checked == true)
                 sOuput +=  "Y," ; else sOuput +=  "N," ;
             if  (write.paper.checked == true)
                 sOuput +=  "Y," ; else sOuput +=  "N," ;
             if  (write.banner.checked == true)
                 sOuput +=  "Y," ; else sOuput +=  "N," ;
             if  (write.signboard.checked == true)
                 sOuput +=  "Y," ; else sOuput +=  "N," ;

             sOuput = sOuput.substr(0,sOuput.length-1) ; // 마지막 한자는 잘라낸다.

             
             sUrl = "<?=$PHP_SELF?>?"
                  + "Ouput="+sOuput+"&"
                  + "WorkDate=<?=$WorkDate?>&"
                  + "SunTheather=<?=$SunTheather?>&"
                  + "SunRoom=<?=$SunRoom?>&"
                  + "Bigo="+write.bigo.value+"&"
                  + "FilmSupply="+<?=$silmoojaFilmSupply?>+"&"
                  + "BackAddr=wrk_silmooja.php" ;

             write.action = sUrl ;

             return true;
         }

         // 선재영화 입력하기 위한 화면으로 간다.
         function check_suntheether(sShowroom)
         {                                                                                                              
            sUrl = "wrk_silmooja_13_1.php?"
                 + "WorkDate=<?=$WorkDate?>&"
                 + "SunTheather=<?=$SunTheather?>&"
                 + "FilmSupply="+<?=$silmoojaFilmSupply?>+"&"
                 + "SunRoom="+sShowroom ;

            location.href = sUrl ;
         }
   </script>


<? echo "<b>".$UserName . "</b>님을 환영합니다!" ; ?>
<a href="index_com.php?actcode=logout"><b>[LogOut]</b></a>
<a href="<?=$BackAddr?>?WorkDate=<?=$WorkDate?>"><b>[X]</b></a>

<center>

   <br><b>*선재현황보고(<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>)*</b><br>


   <?
   $sQuery = "Select * From bas_theather        ".
             " Where Code = '".$SangTheather."' " ;
   $query_theather = mysql_query($sQuery,$connect) ;
   if  ($theather_data = mysql_fetch_array($query_theather)) 
   {
   ?>
        <b><font color=white><?=$theather_data["Discript"]?></font></b><br>
   <?
   }
   ?>

   <form method=post name=write onsubmit="return check_submit()">


   <table cellpadding=0 cellspacing=0 border=1>
   <tr>
        <td align=center>극장명</td>
        <td align=center>좌석수</td>
        <td align=center>영화명</td>
        <td align=center>포스터</td>
        <td align=center>전단</td>
        <td align=center>배너</td>
        <td align=center>스텐드</td>
        <td align=center>비고</td>
   </tr>
   
   <?
   $OnlyOne = true ;

   $rcCount = 0 ;

   mysql_data_seek($query_showroom, 0) ; // 레코드 처음으로 이동...
   
   while  ($showroom_data = mysql_fetch_array($query_showroom))
   {
        $rcCount = $rcCount + 1 ;

        $showroom_Theather  = $showroom_data["Theather"] ;
        $showroom_Room      = $showroom_data["Room"] ;
        $showroom_Discript  = $showroom_data["Discript"] ;
        $showroom_Seat      = $showroom_data["Seat"] ;
                                          
        ?>

        <tr>
               <!-- 상영관 -->
               <td align=left>  
               <?=$showroom_Discript?>
               </td>

               <!-- 좌석수 -->
               <td align=right>
               <?=$showroom_Seat?>
               &nbsp;
               </td>

               <?
               $sQuery = "Select * From wrk_sunjae1                   ".
                         " Where WorkDate  = '".$WorkDate."'          ".
                         "   And Theather  = '".$showroom_Theather."' " ;
               $query_sangdae0 = mysql_query($sQuery,$connect) ; 
               if   ($sangdae_data0 = mysql_fetch_array($query_sangdae0))
               {
                     $sangdae_Poster    = $sangdae_data0["Poster"] ;
                     $sangdae_Paper     = $sangdae_data0["Paper"] ;
                     $sangdae_Banner    = $sangdae_data0["Banner"] ;
                     $sangdae_SignBoard = $sangdae_data0["SignBoard"] ;
                     $sangdae_Bigo      = $sangdae_data0["Bigo"] ;
                     $sangdae_FilmSupply= $sangdae_data0["FilmSupply"] ;
               }
               else
               {
                     $sangdae_Poster    = "" ;
                     $sangdae_Paper     = "" ;
                     $sangdae_Banner    = "" ;
                     $sangdae_SignBoard = "" ;
                     $sangdae_Bigo      = "" ;
                     $sangdae_FilmSupply= "" ;
               }

               $sQuery = "Select Count(*) As CountOfSunJae2           ".
                         " From wrk_sunjae2                           ".
                         " Where WorkDate  = '".$WorkDate."'          ".
                         "   And Theather  = '".$showroom_Theather."' ".
                         "   And Room      = '".$showroom_Room."'     " ;
               $query_sangdae2 = mysql_query($sQuery,$connect) ; 
               if  ($sangdae_data2 = mysql_fetch_array($query_sangdae2))
               {
                   $sangdae_TheatherNum = $sangdae_data2["CountOfSunJae2"] ;
               }
               ?>


               <!-- 영화명 -->
               <td id="filmsDiv<?=$showroom_Room?>" align=right>
               <a OnClick="check_suntheether('<?=$showroom_Room?>');">개별입력(<?=$sangdae_TheatherNum?>)</a>
               </td>



               <?
               if   ($OnlyOne == true)
               {
                    $OnlyOne = false ;
               ?>
               
               
               <!-- 포스터 -->
               <td align=right rowspan=<?=$count_showroom_row?>>
               <?                     
               if  ($sangdae_Poster=='Y') 
               {
               ?>
               <input type="checkbox" value='<?=$sangdae_Poster?>' name=poster checked="checked">
               <?
               }
               else
               {
               ?>
               <input type="checkbox" value='<?=$sangdae_Poster?>' name=poster>
               <?
               } 
               ?>
               &nbsp;
               </td>

               <!--  전단 -->
               <td align=right rowspan=<?=$count_showroom_row?>>               
               <?                     
               if  ($sangdae_Paper=='Y') 
               {
               ?>
               <input type="checkbox" value='<?=$sangdae_Paper?>' name=paper checked="checked">
               <?
               }
               else
               {
               ?>
               <input type="checkbox" value='<?=$sangdae_Paper?>' name=paper>
               <?
               } 
               ?>
               &nbsp;
               </td>

               <!-- 배너 -->
               <td align=right rowspan=<?=$count_showroom_row?>>               
               <?                     
               if  ($sangdae_Banner=='Y') 
               {
               ?>
               <input type="checkbox" value='<?=$sangdae_Banner?>' name=banner checked="checked">
               <?
               }
               else
               {
               ?>
               <input type="checkbox" value='<?=$sangdae_Banner?>' name=banner>
               <?
               } 
               ?>
               &nbsp;
               </td>

               <!-- 스텐드 -->
               <td align=right rowspan=<?=$count_showroom_row?>>
               <?                     
               if  ($sangdae_SignBoard=='Y') 
               {
               ?>
               <input type="checkbox" value='<?=$sangdae_SignBoard?>' name=signboard checked="checked">
               <?
               }
               else
               {
               ?>
               <input type="checkbox" value='<?=$sangdae_SignBoard?>' name=signboard>
               <?
               } 
               ?>
               &nbsp;
               </td>

               <!-- 비고 -->
               <td align=right rowspan=<?=$count_showroom_row?>>
               <input type=text value='<?=$sangdae_Bigo?>' name=bigo>
               &nbsp;
               </td>

               <?
               } 
               ?>
        </tr>
   <?
   }
   ?>
   </table>

   <BR>
   <!-- 전송 버튼 --> 
   <input type=submit value="전송">

   </form>

</center>

</body>

</html>

<?
    
    if  ($Ouput) // 여기서 저장한다.
    { 
         echo "<script>alert('선재현황보고가 정상적으로 완료되었읍니다.');</script>" ;
         echo "<script>location.href='".$BackAddr."?WorkDate=".$WorkDate."'</script>" ;
    }          
    
    mysql_close($connect);
    }
?>