<?
    session_start();

    //
    // 실무자 - 편당요금지정
    //



    // 정상적으로 로그인 했는지 체크한다.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = 'index_com.php'</script>";
    }
    else
    {
        if (!$ssn_FilmOpenCode)
        {
           echo "<script language='JavaScript'>window.location = 'wrk_silmooja.php?WorkDate=".$WorkDate."'</script>";
        }

        include "config.php";

        $connect=dbconn();

        mysql_select_db($cont_db) ;

        $ToDate = date("Ymd",$Today) ; // 무조건 오늘 (이전자료와 비교)


        if  ($OrgSeat!=$Seat)
        {
            $sQuery = "Update bas_showroom                                    ".
                      "   Set Seat     = '".$Seat."'                          ".
                      " Where Theather = '".substr($ssn_ShowroomCode,0,4)."'  ".
                      "   And Room     = '".substr($ssn_ShowroomCode,4,2)."'  " ;
            mysql_query($sQuery,$connect) ;
        }


        // 해당실무자를 구하고 ..
        $sQuery = "Select * From bas_silmooja    ".
                  " Where UserId = '".$UserId."' " ;
        $qry_silmooja = mysql_query($sQuery,$connect) ;

        $silmooja_data = mysql_fetch_array($qry_silmooja) ;

        if  ($silmooja_data)
        {
            $silmoojaCode = $silmooja_data["Code"] ; // 실무자코드
        }

        $sQuery = "Select * From bas_silmoojatheather                     ".
                  " Where Silmooja  = '".$silmoojaCode."'                 ".
                  "   And Theather  = '".substr($ssn_ShowroomCode,0,4)."' ".
                  "   And Room      = '".substr($ssn_ShowroomCode,4,2)."' " ;
        $qrySilthr = mysql_query($sQuery,$connect) ;
        if  ($silthr_data = mysql_fetch_array($qrySilthr))
        {
            $silthrOpen = $silthr_data["Open"] ;  // 실무자가 선택한 영화
            $silthrFilm = $silthr_data["Film"] ;

            $sDgrName   = get_degree($silthrOpen,$silthrFilm,$connect) ;
            $sDgrpName  = get_degreepriv($silthrOpen,$silthrFilm,$connect) ;
        }

        /* 회차와 시작시간을 데이터베이스에 기입한다...*/
        $sQuery = "Select * From bas_showroom                            ".
                  " Where Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
                  "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' " ;
        $query1 = mysql_query($sQuery,$connect) ;
        if  ($showroom_data = mysql_fetch_array($query1))
        {
            $showroom_Discript = $showroom_data["Discript"] ;  // 상영관명
        }




        // 찌꺼기 회차를 지운다.
        if  ($ToDate == $WorkDate) // 작업일자와 오늘 일자가 같을경우 ..
        {
            $sQuery = "Delete From ".$sDgrName."                             ".
                      " Where Silmooja = '".$silmoojaCode."'                 ".
                      "   And Open     = '".$silthrOpen."'                   ".
                      "   And Film     = '".$silthrFilm."'                   ".
                      "   And Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
                      "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' " ;
            mysql_query($sQuery,$connect) ;
        }
        $sQuery = "Delete From ".$sDgrpName."                            ".
                  " Where Silmooja = '".$silmoojaCode."'                 ".
                  "   And WorkDate = '".$WorkDate."'                     ".
                  "   And Open     = '".$silthrOpen."'                   ".
                  "   And Film     = '".$silthrFilm."'                   ".
                  "   And Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
                  "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' " ;
        mysql_query($sQuery,$connect) ;

        $nStart = 0 ;

        for ($i=1 ; $i<=11 ; $i++)
        {
            $nDegree    = "chk".$i."Degree" ;
            $sStartTime = "StartTime".$i ;

            if  ($$nDegree)
            {
                if  ($ToDate == $WorkDate) // 작업일자와 오늘 일자가 같을경우 ..
                {
                    $sQuery = "Insert Into ".$sDgrName."                ".
                              "Values                                   ".
                              "(                                        ".
                              "    '".substr($ssn_ShowroomCode,0,4)."', ".
                              "    '".substr($ssn_ShowroomCode,4,2)."', ".
                              "    '".sprintf("%02d",$i)."',            ".
                              "    '".$silmoojaCode."',                 ".
                              "    '".$silthrOpen."',                   ".
                              "    '".$silthrFilm."',                   ".
                              "    '".$$sStartTime."',                  ".
                              "    '".$showroom_Discript."'             ".
                              ")                                        " ;
                    mysql_query($sQuery,$connect) ;
                }
                $sQuery = "Insert Into ".$sDgrpName."               ".
                          "Values                                   ".
                          "(                                        ".
                          "    '".$silmoojaCode."',                 ".
                          "    '".$WorkDate."',                     ".
                          "    '".$silthrOpen."',                   ".
                          "    '".$silthrFilm."',                   ".
                          "    '".substr($ssn_ShowroomCode,0,4)."', ".
                          "    '".substr($ssn_ShowroomCode,4,2)."', ".
                          "    '".sprintf("%02d",$i)."',            ".
                          "    '".$$sStartTime."',                  ".
                          "    '".$showroom_Discript."'             ".
                          ")                                        " ;
                mysql_query($sQuery,$connect) ;
            }
        }


        if  ($chk99Degree) // 심야건이 있는 경우
        {
            if  ($ToDate == $WorkDate) // 작업일자와 오늘 일자가 같을경우 ..
            {
                $sQuery = "Insert Into ".$sDgrName."                ".
                          "Values                                   ".
                          "(                                        ".
                          "    '".substr($ssn_ShowroomCode,0,4)."', ".
                          "    '".substr($ssn_ShowroomCode,4,2)."', ".
                          "    '99',                                ".
                          "    '".$silmoojaCode."',                 ".
                          "    '".$silthrOpen."',                   ".
                          "    '".$silthrFilm."',                   ".
                          "    '".$StartMidnight."',                ".
                          "    '".$showroom_Discript."'             ".
                          ")                                        " ;
                mysql_query($sQuery,$connect) ;
            }

            $sQuery = "Insert Into ".$sDgrpName."               ".
                      "Values                                   ".
                      "(                                        ".
                      "    '".$silmoojaCode."',                 ".
                      "    '".$WorkDate."',                     ".
                      "    '".$silthrOpen."',                   ".
                      "    '".$silthrFilm."',                   ".
                      "    '".substr($ssn_ShowroomCode,0,4)."', ".
                      "    '".substr($ssn_ShowroomCode,4,2)."', ".
                      "    '99',                                ".
                      "    '".$StartMidnight."',                ".
                      "    '".$showroom_Discript."'             ".
                      ")                                        " ;
            mysql_query($sQuery,$connect) ;
        }
?>

<html>

<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>편당요금지정</title>
</head>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
   <script>
         // 확인 을 눌렸을때
         function check_submit()
         {
            var sPrices ;

            sPrices = "" ;

            if  (write.chk9000.checked==true) sPrices = sPrices + write.chk9000.value + "," ;
            if  (write.chk8000.checked==true) sPrices = sPrices + write.chk8000.value + "," ;
            if  (write.chk7000.checked==true) sPrices = sPrices + write.chk7000.value + "," ;
            if  (write.chk6500.checked==true) sPrices = sPrices + write.chk6500.value + "," ;
            if  (write.chk6000.checked==true) sPrices = sPrices + write.chk6000.value + "," ;
            if  (write.chk5500.checked==true) sPrices = sPrices + write.chk5500.value + "," ;
            if  (write.chk5000.checked==true) sPrices = sPrices + write.chk5000.value + "," ;
            if  (write.chk4500.checked==true) sPrices = sPrices + write.chk4500.value + "," ;
            if  (write.chk4000.checked==true) sPrices = sPrices + write.chk4000.value + "," ;
            if  (write.chk3500.checked==true) sPrices = sPrices + write.chk3500.value + "," ;
            if  (write.chk3000.checked==true) sPrices = sPrices + write.chk3000.value + "," ;
            if  (write.chk2500.checked==true) sPrices = sPrices + write.chk2500.value + "," ;
            if  (write.chk2000.checked==true) sPrices = sPrices + write.chk2000.value + "," ;
            if  (write.chk0000.checked==true) sPrices = sPrices + write.chk0000.value + "," ;
            if  ( (write.chkgita1.checked==true) && (write.txtgita1.value!='') ) sPrices = sPrices + write.txtgita1.value + "," ;
            if  ( (write.chkgita2.checked==true) && (write.txtgita2.value!='') ) sPrices = sPrices + write.txtgita2.value + "," ;
            if  ( (write.chkgita3.checked==true) && (write.txtgita3.value!='') ) sPrices = sPrices + write.txtgita3.value + "," ;
            if  ( (write.chkgita4.checked==true) && (write.txtgita4.value!='') ) sPrices = sPrices + write.txtgita4.value + "," ;
            if  ( (write.chkgita5.checked==true) && (write.txtgita5.value!='') ) sPrices = sPrices + write.txtgita5.value + "," ;
            if  ( (write.chkgita6.checked==true) && (write.txtgita6.value!='') ) sPrices = sPrices + write.txtgita6.value + "," ;
            if  ( (write.chkgita7.checked==true) && (write.txtgita7.value!='') ) sPrices = sPrices + write.txtgita7.value + "," ;
            if  ( (write.chkgita8.checked==true) && (write.txtgita8.value!='') ) sPrices = sPrices + write.txtgita8.value + "," ;
            if  ( (write.chkgita9.checked==true) && (write.txtgita9.value!='') ) sPrices = sPrices + write.txtgita9.value + "," ;
            if  ( (write.chkgita10.checked==true) && (write.txtgita10.value!='') ) sPrices = sPrices + write.txtgita10.value + "," ;
            if  ( (write.chkgita11.checked==true) && (write.txtgita11.value!='') ) sPrices = sPrices + write.txtgita11.value + "," ;
            if  ( (write.chkgita12.checked==true) && (write.txtgita12.value!='') ) sPrices = sPrices + write.txtgita12.value + "," ;
            if  ( (write.chkgita13.checked==true) && (write.txtgita13.value!='') ) sPrices = sPrices + write.txtgita13.value + "," ;
            if  ( (write.chkgita14.checked==true) && (write.txtgita14.value!='') ) sPrices = sPrices + write.txtgita14.value + "," ;
            if  ( (write.chkgita15.checked==true) && (write.txtgita15.value!='') ) sPrices = sPrices + write.txtgita15.value + "," ;

            location.href= "wrk_silmooja_6.php?Prices="+sPrices+"&WorkDate=<?=$WorkDate?>&BackAddr=wrk_silmooja.php" ;
            return false;
         }
   </script>

<? echo "<b>".$UserName . "</b>님을 환영합니다!" ; ?>
<a href="index_com.php?actcode=logout"><b>[LogOut]</b></a>
<a href="<?=$BackAddr?>?WorkDate=<?=$WorkDate?>"><b>[X]</b></a>

<center>

   <br><b>*편당요금지정(<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>)*</b><br>
     <?
     $sQuery = "Select * From bas_showroom                            ".
               " Where Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
               "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' " ;
     $query1 = mysql_query($sQuery,$connect) ;
     if  ($showroom_data = mysql_fetch_array($query1))
     {
         $showroom_Discript = $showroom_data["Discript"] ;
     }
     else
     {
         $showroom_Discript = "" ;
     }

     $sQuery = "Select * From bas_filmtitle                       ".
               " Where Open = '".substr($ssn_FilmOpenCode,0,6)."' ".
               "   And Code = '".substr($ssn_FilmOpenCode,6,2)."' " ;
     $query1 = mysql_query($sQuery,$connect) ;
     if  ($filmtitle_data = mysql_fetch_array($query1))
     {
         $filmtitle_Name = $filmtitle_data["Name"] ;
     }
     else
     {
         $filmtitle_Name = "" ;
     }

   echo $showroom_Discript . "<br>" . $filmtitle_Name ;

   ?>


   <?
   // 개별 요금이 없는경우
   $sQuery = "Select * From bas_unitpricespriv                      ".
             " Where Silmooja = '".$silmoojaCode."'                 ".
             "   And WorkDate = '".$WorkDate."'                     ".
             "   And Open     = '".substr($ssn_FilmOpenCode,0,6)."' ".
             "   And Film     = '".substr($ssn_FilmOpenCode,6,2)."' ".
             "   And Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
             "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' " ;
   $qry_degreepriv = mysql_query($sQuery,$connect) ;
   $degreepriv_data = mysql_fetch_array($qry_degreepriv) ;
   if  (!$degreepriv_data)
   {
       // 개별 요금을 만든다.
       $sQuery = "Select * From bas_unitprices                          ".
                 " Where Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
                 "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' " ;
       $qry_unitprices = mysql_query($sQuery,$connect) ;

       while ($unitprices_data = mysql_fetch_array($qry_unitprices))
       {
            $sQuery = "Insert Into bas_unitpricespriv           ".
                      "Values                                   ".
                      "(                                        ".
                      "    '".$silmoojaCode."',                 ".
                      "    '".$WorkDate."',                     ".
                      "    '".substr($ssn_FilmOpenCode,0,6)."', ".
                      "    '".substr($ssn_FilmOpenCode,6,2)."', ".
                      "    '".substr($ssn_ShowroomCode,0,4)."', ".
                      "    '".substr($ssn_ShowroomCode,4,2)."', ".
                      "    '".$unitprices_data["UnitPrice"]."', ".
                      "    '".$unitprices_data["Discript"]."'   ".
                      ")                                        " ;
            mysql_query($sQuery,$connect) ;
       }
   }

   ?>

   <form method=post name=write onsubmit="return check_submit()">

   <input type=submit value="확인"><BR>

   <?
   $sQuery = "Select * From bas_unitpricespriv                      ".
             " Where Silmooja = '".$silmoojaCode."'                 ".
             "   And WorkDate = '".$WorkDate."'                     ".
             "   And Open     = '".substr($ssn_FilmOpenCode,0,6)."' ".
             "   And Film     = '".substr($ssn_FilmOpenCode,6,2)."' ".
             "   And Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
             "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' ".
             "   And UnitPrice= 9000                                " ;
   $qry_degreepriv = mysql_query($sQuery,$connect) ;
   if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
   {
       ?>
       <input name=chk9000 value=9000 type="checkbox" checked><font color=white>9,000</font><BR>
       <?
   }
   else
   {
       ?>
       <input name=chk9000 value=9000 type="checkbox"><font color=white>9,000</font><BR>
       <?
   }
   ?>

   <?
   $sQuery = "Select * From bas_unitpricespriv                      ".
             " Where Silmooja = '".$silmoojaCode."'                 ".
             "   And WorkDate = '".$WorkDate."'                     ".
             "   And Open     = '".substr($ssn_FilmOpenCode,0,6)."' ".
             "   And Film     = '".substr($ssn_FilmOpenCode,6,2)."' ".
             "   And Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
             "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' ".
             "   And UnitPrice= 8000                                " ;
   $qry_degreepriv = mysql_query($sQuery,$connect) ;
   if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
   {
       ?>
       <input name=chk8000 value=8000 type="checkbox" checked><font color=white>8,000</font><BR>
       <?
   }
   else
   {
       ?>
       <input name=chk8000 value=8000 type="checkbox"><font color=white>8,000</font><BR>
       <?
   }
   ?>

   <?
   $sQuery = "Select * From bas_unitpricespriv                      ".
             " Where Silmooja = '".$silmoojaCode."'                 ".
             "   And WorkDate = '".$WorkDate."'                     ".
             "   And Open     = '".substr($ssn_FilmOpenCode,0,6)."' ".
             "   And Film     = '".substr($ssn_FilmOpenCode,6,2)."' ".
             "   And Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
             "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' ".
             "   And UnitPrice= 7000                                " ;
   $qry_degreepriv = mysql_query($sQuery,$connect) ;
   if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
   {
       ?>
       <input name=chk7000 value=7000 type="checkbox" checked><font color=white>7,000</font><BR>
       <?
   }
   else
   {
       ?>
       <input name=chk7000 value=7000 type="checkbox"><font color=white>7,000</font><BR>
       <?
   }
   ?>


   <?
   $sQuery = "Select * From bas_unitpricespriv                      ".
             " Where Silmooja = '".$silmoojaCode."'                 ".
             "   And WorkDate = '".$WorkDate."'                     ".
             "   And Open     = '".substr($ssn_FilmOpenCode,0,6)."' ".
             "   And Film     = '".substr($ssn_FilmOpenCode,6,2)."' ".
             "   And Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
             "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' ".
             "   And UnitPrice= 6500                                " ;
   $qry_degreepriv = mysql_query($sQuery,$connect) ;
   if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
   {
       ?>
       <input name=chk6500 value=6500 type="checkbox" checked><font color=white>6,500</font><BR>
       <?
   }
   else
   {
       ?>
       <input name=chk6500 value=6500 type="checkbox"><font color=white>6,500</font><BR>
       <?
   }
   ?>

   <?
   $sQuery = "Select * From bas_unitpricespriv                      ".
             " Where Silmooja = '".$silmoojaCode."'                 ".
             "   And WorkDate = '".$WorkDate."'                     ".
             "   And Open     = '".substr($ssn_FilmOpenCode,0,6)."' ".
             "   And Film     = '".substr($ssn_FilmOpenCode,6,2)."' ".
             "   And Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
             "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' ".
             "   And UnitPrice= 6000                                " ;
   $qry_degreepriv = mysql_query($sQuery,$connect) ;
   if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
   {
       ?>
       <input name=chk6000 value=6000 type="checkbox" checked><font color=white>6,000</font><BR>
       <?
   }
   else
   {
       ?>
       <input name=chk6000 value=6000 type="checkbox"><font color=white>6,000</font><BR>
       <?
   }
   ?>

   <?
   $sQuery = "Select * From bas_unitpricespriv                      ".
             " Where Silmooja = '".$silmoojaCode."'                 ".
             "   And WorkDate = '".$WorkDate."'                     ".
             "   And Open     = '".substr($ssn_FilmOpenCode,0,6)."' ".
             "   And Film     = '".substr($ssn_FilmOpenCode,6,2)."' ".
             "   And Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
             "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' ".
             "   And UnitPrice= 5500                                " ;
   $qry_degreepriv = mysql_query($sQuery,$connect) ;
   if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
   {
       ?>
       <input name=chk5500 value=5500 type="checkbox" checked><font color=white>5,500</font><BR>
       <?
   }
   else
   {
       ?>
       <input name=chk5500 value=5500 type="checkbox"><font color=white>5,500</font><BR>
       <?
   }
   ?>

   <?
   $sQuery = "Select * From bas_unitpricespriv                      ".
             " Where Silmooja = '".$silmoojaCode."'                 ".
             "   And WorkDate = '".$WorkDate."'                     ".
             "   And Open     = '".substr($ssn_FilmOpenCode,0,6)."' ".
             "   And Film     = '".substr($ssn_FilmOpenCode,6,2)."' ".
             "   And Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
             "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' ".
             "   And UnitPrice= 5000                                " ;
   $qry_degreepriv = mysql_query($sQuery,$connect) ;
   if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
   {
       ?>
       <input name=chk5000 value=5000 type="checkbox" checked><font color=white>5,000</font><BR>
       <?
   }
   else
   {
       ?>
       <input name=chk5000 value=5000 type="checkbox"><font color=white>5,000</font><BR>
       <?
   }
   ?>

   <?
   $sQuery = "Select * From bas_unitpricespriv                      ".
             " Where Silmooja = '".$silmoojaCode."'                 ".
             "   And WorkDate = '".$WorkDate."'                     ".
             "   And Open     = '".substr($ssn_FilmOpenCode,0,6)."' ".
             "   And Film     = '".substr($ssn_FilmOpenCode,6,2)."' ".
             "   And Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
             "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' ".
             "   And UnitPrice= 4500                                " ;
   $qry_degreepriv = mysql_query($sQuery,$connect) ;
   if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
   {
       ?>
       <input name=chk4500 value=4500 type="checkbox" checked><font color=white>4,500</font><BR>
       <?
   }
   else
   {
       ?>
       <input name=chk4500 value=4500 type="checkbox"><font color=white>4,500</font><BR>
       <?
   }
   ?>

   <?
   $sQuery = "Select * From bas_unitpricespriv                      ".
             " Where Silmooja = '".$silmoojaCode."'                 ".
             "   And WorkDate = '".$WorkDate."'                     ".
             "   And Open     = '".substr($ssn_FilmOpenCode,0,6)."' ".
             "   And Film     = '".substr($ssn_FilmOpenCode,6,2)."' ".
             "   And Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
             "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' ".
             "   And UnitPrice= 4000                                " ;
   $qry_degreepriv = mysql_query($sQuery,$connect) ;
   if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
   {
       ?>
       <input name=chk4000 value=4000 type="checkbox" checked><font color=white>4,000</font><BR>
       <?
   }
   else
   {
       ?>
       <input name=chk4000 value=4000 type="checkbox"><font color=white>4,000</font><BR>
       <?
   }
   ?>

   <?
   $sQuery = "Select * From bas_unitpricespriv                      ".
             " Where Silmooja = '".$silmoojaCode."'                 ".
             "   And WorkDate = '".$WorkDate."'                     ".
             "   And Open     = '".substr($ssn_FilmOpenCode,0,6)."' ".
             "   And Film     = '".substr($ssn_FilmOpenCode,6,2)."' ".
             "   And Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
             "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' ".
             "   And UnitPrice= 3500                                " ;
   $qry_degreepriv = mysql_query($sQuery,$connect) ;
   if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
   {
       ?>
       <input name=chk3500 value=3500 type="checkbox" checked><font color=white>3,500</font><BR>
       <?
   }
   else
   {
       ?>
       <input name=chk3500 value=3500 type="checkbox"><font color=white>3,500</font><BR>
       <?
   }
   ?>

   <?
   $sQuery = "Select * From bas_unitpricespriv                      ".
             " Where Silmooja = '".$silmoojaCode."'                 ".
             "   And WorkDate = '".$WorkDate."'                     ".
             "   And Open     = '".substr($ssn_FilmOpenCode,0,6)."' ".
             "   And Film     = '".substr($ssn_FilmOpenCode,6,2)."' ".
             "   And Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
             "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' ".
             "   And UnitPrice= 3000                                " ;
   $qry_degreepriv = mysql_query($sQuery,$connect) ;
   if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
   {
       ?>
       <input name=chk3000 value=3000 type="checkbox" checked><font color=white>3,000</font><BR>
       <?
   }
   else
   {
       ?>
       <input name=chk3000 value=3000 type="checkbox"><font color=white>3,000</font><BR>
       <?
   }
   ?>

   <?
   $sQuery = "Select * From bas_unitpricespriv                      ".
             " Where Silmooja = '".$silmoojaCode."'                 ".
             "   And WorkDate = '".$WorkDate."'                     ".
             "   And Open     = '".substr($ssn_FilmOpenCode,0,6)."' ".
             "   And Film     = '".substr($ssn_FilmOpenCode,6,2)."' ".
             "   And Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
             "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' ".
             "   And UnitPrice= 2500                                " ;
   $qry_degreepriv = mysql_query($sQuery,$connect) ;
   if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
   {
       ?>
       <input name=chk2500 value=2500 type="checkbox" checked><font color=white>2,500</font><BR>
       <?
   }
   else
   {
       ?>
       <input name=chk2500 value=2500 type="checkbox"><font color=white>2,500</font><BR>
       <?
   }
   ?>

   <?
   $sQuery = "Select * From bas_unitpricespriv                      ".
             " Where Silmooja = '".$silmoojaCode."'                 ".
             "   And WorkDate = '".$WorkDate."'                     ".
             "   And Open     = '".substr($ssn_FilmOpenCode,0,6)."' ".
             "   And Film     = '".substr($ssn_FilmOpenCode,6,2)."' ".
             "   And Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
             "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' ".
             "   And UnitPrice= 2000                                " ;
   $qry_degreepriv = mysql_query($sQuery,$connect) ;
   if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
   {
       ?>
       <input name=chk2000 value=2000 type="checkbox" checked><font color=white>2,000</font><BR>
       <?
   }
   else
   {
       ?>
       <input name=chk2000 value=2000 type="checkbox"><font color=white>2,000</font><BR>
       <?
   }
   ?>

   <?
   $sQuery = "Select * From bas_unitpricespriv                      ".
             " Where Silmooja = '".$silmoojaCode."'                 ".
             "   And WorkDate = '".$WorkDate."'                     ".
             "   And Open     = '".substr($ssn_FilmOpenCode,0,6)."' ".
             "   And Film     = '".substr($ssn_FilmOpenCode,6,2)."' ".
             "   And Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
             "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' ".
             "   And UnitPrice= 0                                   " ;
   $qry_degreepriv = mysql_query($sQuery,$connect) ;
   if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
   {
       ?>
       <input name=chk0000 value=0    type="checkbox" checked><font color=white>미지정</font><BR>
       <?
   }
   else
   {
       ?>
       <input name=chk0000 value=0    type="checkbox"><font color=white>미지정</font><BR>
       <?
   }
   ?>

   <?
   $i = 1 ;

   $sQuery = "Select * From bas_unitpricespriv                      ".
             " Where Silmooja = '".$silmoojaCode."'                 ".
             "   And WorkDate = '".$WorkDate."'                     ".
             "   And Open     = '".substr($ssn_FilmOpenCode,0,6)."' ".
             "   And Film     = '".substr($ssn_FilmOpenCode,6,2)."' ".
             "   And Theather = '".substr($ssn_ShowroomCode,0,4)."' ".
             "   And Room     = '".substr($ssn_ShowroomCode,4,2)."' ".
             "   And UnitPrice<>8000                                ".
             "   And UnitPrice<>7000                                ".
             "   And UnitPrice<>6500                                ".
             "   And UnitPrice<>6000                                ".
             "   And UnitPrice<>5500                                ".
             "   And UnitPrice<>5000                                ".
             "   And UnitPrice<>4500                                ".
             "   And UnitPrice<>4000                                ".
             "   And UnitPrice<>3500                                ".
             "   And UnitPrice<>3000                                ".
             "   And UnitPrice<>2500                                ".
             "   And UnitPrice<>2000                                ".
             "   And UnitPrice<>0                                   " ;
   $qry_degreepriv = mysql_query($sQuery,$connect) ;
   while ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
   {
       ?>
       <input name=chkgita<?=$i?>  type="checkbox" checked><input type=text name=txtgita<?=$i?>  size=5 maxlength=6 class=input value=<?=$degreepriv_data["UnitPrice"]?>><BR>
       <?
       $i += 1 ;
   }

   for  ( ; $i <= 15 ; $i++ )
   {
       ?>
       <input name=chkgita<?=$i?>  type="checkbox"><input type=text name=txtgita<?=$i?>  size=5 maxlength=6 class=input><BR>
       <?
   }
   ?>

   </form>

</center>

</body>

</html>

<?
        mysql_close($connect);
    }
?>