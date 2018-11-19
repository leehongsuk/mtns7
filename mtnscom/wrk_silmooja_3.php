<?
    session_start();

    //
    // 실무자 - 상영회차 지정
    //
    include "config.php";

    // 정상적으로 로그인 했는지 체크한다.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = 'index_com.php'</script>";
    }
    else
    {
        if  ( ((!$FilmOpenCode) || ($FilmOpenCode=="")) || ((!$WorkDate) || ($WorkDate=="")) )
        {
            echo "<script language='JavaScript'>window.location = 'wrk_silmooja.php'</script>";
        }


        $ssn_FilmOpenCode = $FilmOpenCode ;

        $FilmOpen = substr($FilmOpenCode,0,6) ;
        $FilmCode = substr($FilmOpenCode,6,2) ;

        $Theather = substr($ShowroomCode,0,4) ;
        $Room     = substr($ShowroomCode,4,2) ;

        if (session_is_registered("ssn_FilmOpenCode"))
            session_unregister("ssn_FilmOpenCode");
        session_register("ssn_FilmOpenCode");

        $Today = time()-(3600*7) ; // 새벽 7시 까지 오늘로 간주한다...  

        if (!$WorkDate)
        {
           $WorkDate = date("Ymd",$Today) ;
        }

        $connect=dbconn();

        mysql_select_db($cont_db) ;

        // 해당실무자를 구하고 ..
        $sQuery = "Select * From bas_silmooja    ".
                  " Where UserId = '".$UserId."' " ;
        $qry_silmooja = mysql_query($sQuery,$connect) ;
        if  ($silmooja_data = mysql_fetch_array($qry_silmooja))
        {
            $silmoojaCode = $silmooja_data["Code"] ; // 실무자코드
        }

        

        $sDgrName   = get_degree($FilmOpen,$FilmCode,$connect) ;  
        $sDgrpName  = get_degreepriv($FilmOpen,$FilmCode,$connect) ;  
        
        // 실무자가 선택한 영화정보
        $sQuery = "Select * From bas_filmtitle   ".
                  " Where Open = '".$FilmOpen."' ".
                  "   And Code = '".$FilmCode."' " ;
        $query1 = mysql_query($sQuery,$connect) ;
        if  ($filmtitle_data = mysql_fetch_array($query1))
        {
            $filmtitleName       = $filmtitle_data["Name"] ;        // 영화이름
            $filmtitleFilmSupply = $filmtitle_data["FilmSupply"] ;  // 배급사 코드
        }

        

        $sQuery = "Update bas_silmoojatheather             ".
                  "   Set Open     = '".$FilmOpen."',      ".
                  "       Film     = '".$FilmCode."',      ".
                  "       Title    = '".$filmtitleName."'  ".
                  " Where Silmooja = '".$silmoojaCode."'   ".
                  "   And Theather = '".$Theather."'       ".
                  "   And Room     = '".$Room."'           " ;
        mysql_query($sQuery,$connect) ;

        $sQuery = "Update bas_silmoojatheatherpriv         ".
                  "   Set Open     = '".$FilmOpen."',      ".
                  "       Film     = '".$FilmCode."',      ".
                  "       Title    = '".$filmtitleName."'  ".
                  " Where Silmooja = '".$silmoojaCode."'   ".
                  "   And WorkDate = '".$WorkDate."'       ".
                  "   And Theather = '".$Theather."'       ".
                  "   And Room     = '".$Room."'           " ;
        mysql_query($sQuery,$connect) ;

        $sQuery = "Update bas_showroom                     ".
                  "   Set FilmTitle = '".$FilmOpenCode."', ".
                  "       FilmName  = '".$filmtitleName."' ".
                  " Where Theather  = '".$Theather."'      ". 
                  "   And Room      = '".$Room."'          " ;
        mysql_query($sQuery,$connect) ;
?>

<html>

<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>상영회차지정</title>
</head>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

   <script>

         // 확인버튼을 눌렸을때..
         function check_submit()
         {
              isSelect = false ;
              <?
              for ($i=1 ; $i<=9 ; $i++)
              {
              ?>
                  if  (write.chk<?=$i?>Degree.checked==true)
                  {
                      isSelect = true ;
                  }
              <?
              }
              ?>
                   if  (write.chk99Degree.checked==true)
                   {
                       isSelect = true ;
                   }

                   if  (isSelect == false)
                   {
                       alert('적어도 한개이상은 선택되어져야 합니다.');
                       return false;
                   }
              <?
              for ($i=1 ; $i<=9 ; $i++)
              {
              ?>
                  if  (write.chk<?=$i?>Degree.checked==true)
                  {
                      if (!write.StartTime<?=$i?>.value)
                      {
                        alert('<? printf("%d회",$i); ?> 시간을 입력하여 주십시요');
                        write.StartTime<?=$i?>.focus();
                        return false;
                      }
                      if (write.StartTime<?=$i?>.value.length!=4)
                      {
                        alert('<? printf("%d회",$i); ?> 시간은 4자리 숫자입니다 (보기 예 참조)');
                        write.StartTime<?=$i?>.focus();
                        return false;
                      }
                  }
              <?
              }
              ?>
                   if  (write.chk99Degree.checked==true)
                   {
                       if  (!write.StartMidnight.value) 
                       {
                           alert('심야 시간을 입력하여 주십시요');
                           write.StartMidnight.focus();
                           return false;
                       }
                       if  (write.StartMidnight.value.length!=4)
                       {
                           alert('심야 시간은 4자리 숫자입니다 (보기 예 참조)');
                           write.StartMidnight.focus();
                           return false;
                       }
                   }

              ActAddr = "wrk_silmooja_5.php?"
                      + "BackAddr=wrk_silmooja.php&"
                      + "WorkDate=<?=$WorkDate?>" ;

              write.action = ActAddr ;

              return true ;
         }

   </script>

<? echo "<b>".$UserName . "</b>님을 환영합니다!" ; ?>
<a href="../index_com.php?actcode=logout"><b>[LogOut]</b></a>
<a href="<?=$BackAddr?>?WorkDate=<?=$WorkDate?>"><b>[X]</b></a>

<center>

     <br>
     <b>*상영회차지정(<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>)*</b><br>

     <?
     // 개별회차가 없는경우
     $sQuery = "Select * From ".$sDgrpName."          ".
               " Where Silmooja = '".$silmoojaCode."' ".
               "   And WorkDate = '".$WorkDate."'     ".
               "   And Open     = '".$FilmOpen."'     ".
               "   And Film     = '".$FilmCode."'     ".
               "   And Theather = '".$Theather."'     ".
               "   And Room     = '".$Room."'         " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     $degreepriv_data = mysql_fetch_array($qry_degreepriv) ;
     if  (!$degreepriv_data)
     {
         // 기본회차에서 개별회차를 생성.
         $sQuery = "Select * From ".$sDgrName."            ".
                   " Where Silmooja = '".$silmoojaCode."'  ".
                   "   And Open     = '".$FilmOpen."'      ".
                   "   And Film     = '".$FilmCode."'      ".
                   "   And Theather = '".$Theather."'      ".
                   "   And Room     = '".$Room."'          " ;
         $qry_temp = mysql_query($sQuery,$connect) ;
         if  ($temp_data  = mysql_fetch_array($qry_temp))
         {
             $sQuery = "Select * From ".$sDgrName."            ".
                       " Where Silmooja = '".$silmoojaCode."'  ".
                       "   And Open     = '".$FilmOpen."'      ".
                       "   And Film     = '".$FilmCode."'      ".
                       "   And Theather = '".$Theather."'      ".
                       "   And Room     = '".$Room."'          " ;
             $qry_degree = mysql_query($sQuery,$connect) ;
         }
         else
         {
             $sQuery = "Select * From ".$sDgrName."        ".
                       " Where Theather = '".$Theather."'  ".
                       "   And Room     = '".$Room."'      " ;
             $qry_degree = mysql_query($sQuery,$connect) ;
         }


         while ($degree_data = mysql_fetch_array($qry_degree))
         {
               $sQuery = "Insert Into ".$sDgrpName."           ".
                         "Values                               ".
                         "(                                    ".
                         "    '".$silmoojaCode."',             ".
                         "    '".$WorkDate."',                 ".
                         "    '".$FilmOpen."',                 ".
                         "    '".$FilmCode."',                 ".
                         "    '".$Theather."',                 ".
                         "    '".$Room."',                     ".
                         "    '".$degree_data["Degree"]."',    ".
                         "    '".$degree_data["Time"]."',      ".
                         "    '".$degree_data["Discript"]."'   ".
                         ")                                    " ;
               mysql_query($sQuery,$connect) ;
         
         }
     }


     $sQuery = "Select * From bas_showroom         ".
               " Where Theather = '".$Theather."'  ".
               "   And Room     = '".$Room."'      " ;
     $query1 = mysql_query($sQuery,$connect) ;


     $showroom_data = mysql_fetch_array($query1) ;

     if  ($showroom_data)
     {
         $showroom_Discript = $showroom_data["Discript"] ;
         $showroom_Location = $showroom_data["Location"] ;
         $showroom_Seat     = $showroom_data["Seat"] ;
     }
     else
     {
         $showroom_Discript = "" ;
         $showroom_Location = "" ;
         $showroom_Seat     = "" ;
     }

     // 상영관의 소재지 지역을 구한다. ($locationName)
     $sQuery = "Select * From bas_location             ".
               " Where Code = '".$showroom_Location."' " ;
     $query1 = mysql_query($sQuery,$connect) ;
     if  ($location_data = mysql_fetch_array($query1))
     {
         $locationName = $location_data["Name"] ;  // 상영관 소재지역 명
     }

     $sQuery = "Select * From bas_filmtitle   ".
               " Where Open = '".$FilmOpen."' ".
               "   And Code = '".$FilmCode."' " ;
     $query1 = mysql_query($sQuery,$connect) ;
     if  ($filmtitle_data = mysql_fetch_array($query1))
     {
         $filmtitle_Name = $filmtitle_data["Name"] ; // 영화이름
     }
     else
     {
         $filmtitle_Name = "" ;
     }

     echo $showroom_Discript ."-". $locationName . "<br>" . $filmtitle_Name."<br>" ;

     
     ?>
     시[2]분[2] 예) 1200

     <form method=post name=write onsubmit="return check_submit()">


     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '01'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk1Degree value=1 type="checkbox" checked><font color=white>1회</font>
         <input type=text name=StartTime1  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk1Degree value=1 type="checkbox"><font color=white>1회</font>
         <input type=text name=StartTime1  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>

     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '02'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk2Degree value=2 type="checkbox" checked><font color=white>2회</font>
         <input type=text name=StartTime2  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk2Degree value=2 type="checkbox"><font color=white>2회</font>
         <input type=text name=StartTime2  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>


     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '03'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk3Degree value=3 type="checkbox" checked><font color=white>3회</font>
         <input type=text name=StartTime3  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk3Degree value=3 type="checkbox"><font color=white>3회</font>
         <input type=text name=StartTime3  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>

     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '04'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk4Degree value=4 type="checkbox" checked><font color=white>4회</font>
         <input type=text name=StartTime4  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk4Degree value=4 type="checkbox"><font color=white>4회</font>
         <input type=text name=StartTime4  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>

     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '05'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk5Degree value=5 type="checkbox" checked><font color=white>5회</font>
         <input type=text name=StartTime5  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk5Degree value=5 type="checkbox"><font color=white>5회</font>
         <input type=text name=StartTime5  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>

     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '06'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk6Degree value=6 type="checkbox" checked><font color=white>6회</font>
         <input type=text name=StartTime6  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk6Degree value=6 type="checkbox"><font color=white>6회</font>
         <input type=text name=StartTime6  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>

     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '07'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk7Degree value=7 type="checkbox" checked><font color=white>7회</font>
         <input type=text name=StartTime7  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk7Degree value=7 type="checkbox"><font color=white>7회</font>
         <input type=text name=StartTime7  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>

     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '08'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk8Degree value=8 type="checkbox" checked><font color=white>8회</font>
         <input type=text name=StartTime8  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk8Degree value=8 type="checkbox"><font color=white>8회</font>
         <input type=text name=StartTime8  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>

     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '09'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk9Degree value=9 type="checkbox" checked><font color=white>9회</font>
         <input type=text name=StartTime9  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk9Degree value=9 type="checkbox"><font color=white>9회</font>
         <input type=text name=StartTime9  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>

     <?
     /******
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '10'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk10Degree value=10 type="checkbox" checked><font color=white>10회</font>
         <input type=text name=StartTime10  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk10Degree value=10 type="checkbox"><font color=white>10회</font>
         <input type=text name=StartTime10  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>

     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '11'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk11Degree value=11 type="checkbox" checked><font color=white>11회</font>
         <input type=text name=StartTime11  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?                                                                     
     }
     else
     {
         ?>
         <input name=chk11Degree value=11 type="checkbox"><font color=white>11회</font>
         <input type=text name=StartTime11  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ********/
     ?>

     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '99'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk99Degree value=99 type="checkbox" checked><font color=white>심야</font>
         <input type=text name=StartMidnight size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?                    
     }
     else
     {
         ?>
         <input name=chk99Degree value=99 type="checkbox"><font color=white>심야</font>
         <input type=text name=StartMidnight size=4 maxlength=4 class=input><BR>
         <?                    
     } 
     ?>
     
     <font color=white>좌석수(<?=$showroom_Seat?>석)변경:</font><input type=text name=Seat value='<?=$showroom_Seat?>' size=3 maxlength=3 class=input>

     <input type="hidden" name="OrgSeat" value="<?=$showroom_Seat?>">

     <input type=submit value="확인">

     </form>
</center>

</body>

</html>

<?  
        mysql_close($connect);
    }
?>