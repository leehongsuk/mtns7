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
        $sQuery = "Select * From bas_showroom            ".
                  " Where Theather = '".$ShowroomCode."' " ;
        $query_showroom = mysql_query($sQuery,$connect) ;

        if  ($Ouput)  // 여기서 저장한다.
        {
            $sQuery = "Delete From wrk_sangdae                ".
                      " Where Workdate  = '".$WorkDate."'     ".
                      "   And Theather  = '".$ShowroomCode."' " ;
            mysql_query($sQuery,$connect) ;

            $Items1 = explode(",", $Ouput); // "," 로 파싱,,,

            //echo count($Items1) ; // 배열의 갯수

            foreach ($Items1 as $Item1)
            {
               $Items2 = explode(";", $Item1); // ";" 로 파싱,,,

               $showroom_data = mysql_fetch_array($query_showroom) ;
               $showroom_Theather  = $showroom_data["Theather"] ;
               $showroom_Room      = $showroom_data["Room"] ;
               $showroom_Location  = $showroom_data["Location"] ;
               $showroom_Discript  = $showroom_data["Discript"] ;
               if  ($Items2[0]<>'')
               {
                   $sQuery = "Insert Into wrk_sangdae           ".
                             "Values                            ".
                             "(                                 ".
                             "      '".$WorkDate."',            ".
                             "      '".$showroom_Theather."',   ".
                             "      '".$showroom_Room."',       ".
                             "      '1',                        ".
                             "      '".$Items2[0]."',           ".
                             "      '".$Items2[1]."',           ".
                             "      '".$showroom_Location."',   ".
                             "      '".$showroom_Discript."'    ".
                             ")                                 " ;
                   mysql_query($sQuery,$connect) ;
               }
               if  ($Items2[2]<>'')
               {
                   $sQuery = "Insert Into wrk_sangdae           ".
                             "Values                            ".
                             "(                                 ".
                             "      '".$WorkDate."',            ".
                             "      '".$showroom_Theather."',   ".
                             "      '".$showroom_Room."',       ".
                             "      '2',                        ".
                             "      '".$Items2[2]."',           ".
                             "      '".$Items2[3]."',           ".
                             "      '".$showroom_Location."',   ".
                             "      '".$showroom_Discript."'    ".
                             ")                                 " ;
                   mysql_query($sQuery,$connect) ;
               }
            }
        }

        // 해당실무자를 구하고 ..
        $sQuery = "Select * From bas_silmooja           ".
                  " Where UserId = '".$logged_UserId."' " ;
        $query_silmooja = mysql_query($sQuery,$connect) ;
        if  ($silmooja_data = mysql_fetch_array($query_silmooja))
        {
            $silmoojaCode = $silmooja_data["Code"] ;
            $silmoojaName = $silmooja_data["Name"] ;
        }
?>

<html>

<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>스코어입력</title>
</head>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>


   <script>
           // <!-- 전송 버튼 -->
           function check_submit()
           {
              var sTemp ;
              var sOuput ;

              sOuput = "" ;
              //write.Tiles_<?=$showroom_Room?>

              <?
              mysql_data_seek($query_showroom, 0) ; // 레코드 처음으로 이동...
              while ($showroom_data = mysql_fetch_array($query_showroom))
              {
                   $showroom_Room      = $showroom_data["Room"] ;

              ?>
                   //alert(write.Tiles_<?=$showroom_Room?>1.value) ;
                   //alert(write.Tiles_<?=$showroom_Room?>2.value) ;

                   sOuput +=  write.Tiles_<?=$showroom_Room?>1.value + ';' ;
                   sOuput +=  write.score_<?=$showroom_Room?>1.value + ';' ;
                   sOuput +=  write.Tiles_<?=$showroom_Room?>2.value + ';' ;
                   sOuput +=  write.score_<?=$showroom_Room?>2.value + ',' ;
              <?
              }
              ?>
              //alert(sOuput.substr(0,sOuput.length-1)) ;
              sOuput = sOuput.substr(0,sOuput.length-1) ; // 마지막 한자는 잘라낸다.

              write.action =  "<?=$PHP_SELF?>?ShowroomCode=<?=$ShowroomCode?>&Ouput="+sOuput+"&BackAddr=wrk_silmooja.php&WorkDate=<?=$WorkDate?>"

              return true;
           }

           //
           //   숫자만 입력 받도록 제한한다.
           //
           //
           //

           function score_check()
           {
              edit = write.score.value ;

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
   </script>


<? echo "<b>".$UserName . "</b>님을 환영합니다!" ; ?>
<a href="../index_com.php?actcode=logout"><b>[LogOut]</b></a>
<a href="<?=$BackAddr?>?WorkDate=<?=$WorkDate?>"><b>[X]</b></a>

<center>

   <br><b>*스코어입력(<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>)*</b><br>


   <?
   $sQuery = "Select * From bas_theather        ".
             " Where Code = '".$ShowroomCode."' " ;
   $query_theather = mysql_query($sQuery,$connect) ;
   if  ($theather_data = mysql_fetch_array($query_theather))
   {
        $theather_code = $theather_data["Code"] ;

        $sQuery = "Select Max(Workdate) As MaxOfWorkdate    ".
                  "  From wrk_sangdae                       ".
                  " Where Theather  = '".$theather_code."'  " ;
        $Query_sangdae_max = mysql_query($sQuery,$connect) ;
        if  ($sangdae_max_data = mysql_fetch_array($Query_sangdae_max))
        {
            $AgoDate = $sangdae_max_data["MaxOfWorkdate"] ;
        }

        $sQuery = "Select Count(*) As CountOfSangdae        ".
                  "  From wrk_sangdae                       ".
                  " Where Workdate  = '".$WorkDate."'       ".
                  "   And Theather  = '".$theather_code."'  " ;
        $Query_sangdae_today = mysql_query($sQuery,$connect) ;
        if  ($sangdae_today_data = mysql_fetch_array($Query_sangdae_today))
        {
            if  ($sangdae_today_data["CountOfSangdae"] == 0)
            {
                $sQuery = "Select * From wrk_sangdae                ".
                          " Where Workdate  = '".$AgoDate."'        ".
                          "   And Theather  = '".$theather_code."'  " ;
                $Query_sangdae_agoday = mysql_query($sQuery,$connect) ;
                while ($sangdae_agoday_data = mysql_fetch_array($Query_sangdae_agoday))
                {
                     $sQuery = "Insert Into wrk_sangdae                        ".
                               "Values                                         ".
                               "(                                              ".
                               "   '".$WorkDate."',                            ".
                               "   '".$sangdae_agoday_data["Theather"]."',     ".
                               "   '".$sangdae_agoday_data["Room"]."',         ".
                               "   '".$sangdae_agoday_data["Gubun"]."',        ".
                               "   '".$sangdae_agoday_data["SangFilm"]."',     ".
                               "   '0',                                        ".
                               "   '".$sangdae_agoday_data["Location"]."',     ".
                               "   '".$sangdae_agoday_data["TheatherName"]."'  ".
                               ")                                              " ;
                     mysql_query($sQuery,$connect) ;
                }
            }
        }







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
        <td align=center>스코어</td>
   </tr>

   <?
   $rcCount = 0 ;

   mysql_data_seek($query_showroom, 0) ; // 레코드 처음으로 이동...
   while  ($showroom_data = mysql_fetch_array($query_showroom))
   {
        $rcCount = $rcCount + 1 ;

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
               $sangdae_SangFilm1 = "" ;
               $sangdae_Score1    = null ;
               $sangdae_SangFilm2 = "" ;
               $sangdae_Score2    = null ;

               $sQuery = "Select * From wrk_sangdae                ".
                         " Where WorkDate  = '".$WorkDate."'       ".
                         "   And Theather  = '".$theather_code."'  ".
                         "   And Room      = '".$showroom_Room."'  " ;
               $query_sangdae = mysql_query($sQuery,$connect) ;
               while ($sangdae_data = mysql_fetch_array($query_sangdae))
               {
                     $sangdae_Gubun = $sangdae_data["Gubun"] ;

                     if  ($sangdae_Gubun=="1")
                     {
                         $sangdae_SangFilm1 = $sangdae_data["SangFilm"] ;
                         $sangdae_Score1    = $sangdae_data["Score"] ;
                     }
                     else
                     {
                         $sangdae_SangFilm2 = $sangdae_data["SangFilm"] ;
                         $sangdae_Score2    = $sangdae_data["Score"] ;
                     }
               }
               ?>


               <!-- 영화명 -->
               <td align=right>
               <select name=Tiles_<?=$showroom_Room?>1>
                   <?
                   $sQuery = "Select * From bas_sangfilmtitle ".
                             " Where Gubun = 'S'              ".
                             " Order by code                  " ;
                   $queryTitle = mysql_query($sQuery,$connect) ;
                   while  ($Title_data = mysql_fetch_array($queryTitle))
                   {
                      if  ($sangdae_SangFilm1 == $Title_data["Code"])
                      {

                      ?>
                        <option selected value=<?=$Title_data["Code"]?>><?=$Title_data["Name"]?></option>
                      <?
                      }
                      else
                      {
                      ?>
                        <option value=<?=$Title_data["Code"]?>><?=$Title_data["Name"]?></option>
                      <?
                      }
                   }
                   ?>
               </select><br>
               <select name=Tiles_<?=$showroom_Room?>2>
                   <?
                   $sQuery = "Select * From bas_sangfilmtitle ".
                             " Where Gubun = 'S'              ".
                             " Order by code                  " ;
                   $queryTitle = mysql_query($sQuery,$connect) ;
                   while  ($Title_data = mysql_fetch_array($queryTitle))
                   {
                      if  ($sangdae_SangFilm2 == $Title_data["Code"])
                      {

                      ?>
                        <option selected value=<?=$Title_data["Code"]?>><?=$Title_data["Name"]?></option>
                      <?
                      }
                      else
                      {
                      ?>
                        <option value=<?=$Title_data["Code"]?>><?=$Title_data["Name"]?></option>
                      <?
                      }
                   }
                   ?>
               </select>
               </td>

               <!-- 스코어 -->
               <td align=right>
               <input type=text value='<?=$sangdae_Score1?>' name=score_<?=$showroom_Room?>1 size=7 maxlength=6 style='text-align:right' class=input><br>
               <input type=text value='<?=$sangdae_Score2?>' name=score_<?=$showroom_Room?>2 size=7 maxlength=6 style='text-align:right' class=input>
               </td>
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
         echo "<script>alert('스코어보고가 정상적으로 완료되었읍니다.');</script>" ;
         echo "<script>location.href='".$BackAddr."?WorkDate=".$WorkDate."'</script>" ;
    }


    mysql_close($connect);
    }
?>
