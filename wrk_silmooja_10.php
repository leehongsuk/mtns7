<?
    //
    // 실무자 - 극장(상영관)지정
    //

    include "config.php";
    session_start();

    // 정상적으로 로그인 했는지 체크한다.
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

        // 해당실무자를 구하고 ..
        $sQuery = "Select * From bas_silmooja   ".
                  "Where UserId = '".$UserId."' " ;
        $query1 = mysql_query($sQuery,$connect) ;
        if  ($silmooja_data = mysql_fetch_array($query1))
        {
            $silmoojaCode = $silmooja_data["Code"] ;
            $silmoojaName = $silmooja_data["Name"] ;
        }

        
        $nCount = 0 ;

        if  ($unselectTheather!="")   // 선택이 되지않았을 때
        {        
            $sTemp = $unselectTheather ;

            while (($j = strpos($sTemp ,',')) > 0)
            {
                $item = substr($sTemp,0,$j) ;  // 선택되지않은 상영관
                
                $sQuery = "Delete From bas_silmoojasangdae             ". // 실무자 상영관선택 정보
                          " Where Silmooja  = '".$silmoojaCode."'      ".
                          "   And Theather  = '".substr($item,0,4)."'  " ;
                mysql_query($sQuery,$connect) ;
                
                $nCount++ ;

                $sTemp = substr($sTemp,$j+1) ;
            }
        }

        
        $nCount = 0 ;

        //
        //  체크가 선택되었을때 
        //

        if  ($selectTheather != "")     
        {
            $sTemp = $selectTheather ;

            while (($j = strpos($sTemp ,',')) > 0)
            {
                $item = substr($sTemp,0,$j) ; // 선택된상영관
                
                $sQuery = "Select * From bas_theather             ".
                          " Where Code  = '".substr($item,0,4)."' " ;
                $qryShowroom = mysql_query($sQuery,$connect) ;
                if  ($Showroom_data = mysql_fetch_array($qryShowroom))
                {
                    $ShowroomDiscript = $Showroom_data["Discript"] ;
                }

                $sQuery = "Select * From bas_silmoojasangdae         ".
                          " Where Silmooja = '".$silmoojaCode."'     ".
                          "   And Theather = '".substr($item,0,4)."' " ;
                $qry_silmoojatheather = mysql_query($sQuery,$connect) ;
                $silmoojatheather_data = mysql_fetch_array($qry_silmoojatheather) ;
                if  (!$silmoojatheather_data)
                {
                     $sQuery = "Insert Into bas_silmoojasangdae    ".  // 실무자 상영관선택 정보
                               "Values (                           ".
                               "         '".$silmoojaCode."',      ".
                               "         '".substr($item,0,4)."',  ".
                               "         '".$silmoojaName."',      ".
                               "         '".$ShowroomDiscript."'   ".
                               "        )                          " ;
                     mysql_query($sQuery,$connect) ;          
                }
                
                $nCount++ ;

                $sTemp = substr($sTemp,$j+1) ;
            }
        }
        
        if  ($gonextpage=="Yes") // "확인"을 누르고 다음 페이지로 넘어간다.
        {
            echo "<script>location.href=\"wrk_silmooja.php?WorkDate=".$WorkDate."\"</script>" ;
        }
          
        $page_num = 10 ;

        //echo $Location ."<br><br><br><br>" ; ///////////////////////////////////////////////


        if  ($Discript) // 검색조건이 있다면..
        {
            if  ((!$Location) || ($Location=="000")) // 지역전체
            {
                $sQuery = "Select count(*) From bas_theather      ".
                          " Where Discript like '%".$Discript."%' " ;
                $count_search = mysql_query($sQuery,$connect) ;
                $count_search_row = mysql_fetch_row($count_search);

                if  ( !$page ) { $page = 0; }
                $page_size = $page_num*$page;

                $sQuery = "Select * From bas_theather             ".
                          " Where Discript like '%".$Discript."%' ".
                          " Order By Discript                     ".
                          "limit $page_size,$page_num             " ;
                $query = mysql_query($sQuery,$connect) ;
            }
            else
            {
                $sQuery = "Select count(*) From bas_theather      ".
                          " Where Location = '".$Location."'      ".
                          "   And Discript like '%".$Discript."%' " ;
                $count_search = mysql_query($sQuery,$connect) ;
                $count_search_row = mysql_fetch_row($count_search);
                if  ( !$page ) { $page = 0; }
                $page_size = $page_num*$page;

                $sQuery = "Select * From bas_theather                   ".
                          " Where Location = '".$Location."'            ".
                          "   And Discript like '%".$Discript."%'       ".
                          " Order By Discript limit $page_size,$page_num" ;
                $query = mysql_query($sQuery,$connect) ;
            }
        }
        else
        {
            if  ((!$Location) || ($Location=="000")) // 지역전체
            {
                $sQuery = "Select count(*) From bas_theather " ;
                $count_search = mysql_query($sQuery,$connect) ;
                $count_search_row = mysql_fetch_row($count_search);
                if  ( !$page ) { $page = 0; }
                $page_size = $page_num*$page;

                $sQuery = "Select * From bas_theather                    ".
                          " Order By Discript limit $page_size,$page_num " ;
                $query = mysql_query($sQuery,$connect) ;
            }
            else
            {
                $sQuery = "Select count(*) From bas_theather ".
                          " Where Location = '".$Location."' " ;
                $count_search = mysql_query($sQuery,$connect) ;
                $count_search_row = mysql_fetch_row($count_search);
                if  ( !$page ) { $page = 0; }
                $page_size = $page_num*$page;

                $sQuery = "Select * From bas_theather                   ".
                          " Where Location = '".$Location."'            ".
                          " Order By Discript limit $page_size,$page_num" ;
                $query = mysql_query($sQuery,$connect) ;
            }
        }

        $page_1 = $count_search_row[0] / $page_num;
        $page_1 = intval($page_1);
        $page_2 = $count_search_row[0] % $page_num;

        if ( $page_2 > 0 ) { $page_1++; }
        $total_page = intval($page_1);
        $prev_page = $page - 1;
        $next_page = $page + 1;
        $now_page = $page + 1;

        if ( $page == 0 )
        {
           $str_prev_page = "<A href=\"javascript:alert('더이상 페이지가 없습니다.');\">[ 이전 ]</A>";
        }
        else
        {
            $str_prev_page = "<A onclick='move_prev(write.checkboxs)'>[ 이전 ]</A>";
        }

        if ( $now_page == $total_page )
        {
           $str_next_page = "<A href=\"javascript:alert('더이상 페이지가 없습니다.');\">[ 다음 ]</A>";
        }
        else
        {
            $str_next_page = "<A onclick='move_next(write.checkboxs)'>[ 다음 ]</A>";
        } 
        
?>

<html>

<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>극장(상영관)지정</title>
</head>

<body onload="write.Discript.focus();" BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
  
   <script>
         function Select_Page()
         {
            write.action = '<?=$PHP_SELF?>?page='+(write.CurPage.value-1)+'&Location=<?=$Location?>&Discript=<?=$Discript?>&BackAddr=wrk_silmooja.php&WorkDate='+<?=$WorkDate?> ;
            write.submit() ;
         }         
         
         function search_loc(chk)
         {
            var selected ;
            var unselected ;

            selected   = "" ;
            unselected = "" ;
            
            if  (chk)  // 검색결과가 하나라도 있는경우
            {
                if  (chk[0])  // 검색결과가 하나만 선택된 경우는 배열형태가 아니므로 
                {
                    for (var i = 0;i<chk.length; i++)
                    {
                        if  (chk[i].checked)
                        {
                            selected = selected + chk[i].value + "," ;
                        }
                        else
                        {
                            unselected = unselected + chk[i].value + "," ;
                        }
                    }
                }
                else // 검색결과가 하나만 선택된 경우는 배열형태가 아니므로 
                {
                    if  (chk.checked)
                    {
                        selected = selected + chk.value + "," ;
                    }
                    else
                    {
                        unselected = unselected + chk.value + "," ;
                    }
                }                
                
                write.selectTheather.value   = selected ;
                write.unselectTheather.value = unselected ;

                write.action = '<?=$PHP_SELF?>?page=0&Location=<?=$Location?>&Discript=<?=$Discript?>&BackAddr=wrk_silmooja.php&WorkDate='+<?=$WorkDate?> ;
                write.submit() ;
            }
            else
            {
                write.action = '<?=$PHP_SELF?>?page=0&Location=<?=$Location?>&Discript=<?=$Discript?>&BackAddr=wrk_silmooja.php&WorkDate='+<?=$WorkDate?> ;
                write.submit() ;
            }
         }
         

         function check_submit(chk)
         {
            var selected ;
            var unselected ;

            selected   = "" ;
            unselected = "" ;


            if  (chk)  // 검색결과가 하나라도 있는경우
            {
                if  (chk[0])  // 검색결과가 하나만 선택된 경우는 배열형태가 아니므로 
                {
                    for (var i = 0;i<chk.length; i++)
                    {
                        if  (chk[i].checked)
                        {
                            selected = selected + chk[i].value + "," ;
                        }
                        else
                        {
                            unselected = unselected + chk[i].value + "," ;
                        }
                    }
                    
                }
                else // 검색결과가 하나만 선택된 경우는 배열형태가 아니므로 
                {
                    if  (chk.checked)
                    {
                        selected = selected + chk.value + "," ;
                    }
                    else
                    {
                        unselected = unselected + chk.value + "," ;
                    }
                }            
            
                write.selectTheather.value = selected ;
                write.unselectTheather.value = unselected ;


                write.gonextpage.value = "Yes" ; // 다음페이지로 넘어간다.

                return true;
            }   
         }

         function check_theether(sShowroom)
         {
            //location.href="wrk_silmooja_2.php?ShowroomCode="+sShowroom+"&BackAddr=wrk_silmooja.php" ;
         }

         function move_prev(chk)
         {
            var selected ;
            var unselected ;

            selected   = "" ;
            unselected = "" ;

            for (var i = 0;i<chk.length; i++)
            {
                if  (chk[i].checked)
                {
                    selected = selected + chk[i].value + "," ;
                }
                else
                {
                    unselected = unselected + chk[i].value + "," ;
                }
            }

            write.selectTheather.value   = selected ;
            write.unselectTheather.value = unselected ;

            write.action = '<?=$PHP_SELF?>?page=<?=$prev_page?>&Location=<?=$Location?>&Discript=<?=$Discript?>&BackAddr=wrk_silmooja.php&WorkDate='+<?=$WorkDate?> ;
            write.submit() ;
         }

         function move_next(chk)
         {
            var selected ;
            var unselected ;

            selected   = "" ;
            unselected = "" ;

            for (var i = 0;i<chk.length; i++)
            {
                if  (chk[i].checked)
                {
                    selected = selected + chk[i].value + "," ;
                }
                else
                {
                    unselected = unselected + chk[i].value + "," ;
                }
            }

            write.selectTheather.value = selected ;
            write.unselectTheather.value = unselected ;

            write.action = '<?=$PHP_SELF?>?page=<?=$next_page?>&Location=<?=$Location?>&Discript=<?=$Discript?>&BackAddr=wrk_silmooja.php&WorkDate='+<?=$WorkDate?> ;
            write.submit() ;
         }

   </script>
<? echo "<b>".$UserName . "</b>님을 환영합니다!" ; ?>
<a href="index.php?actcode=logout"><b>[LogOut]</b></a>
<a href="<?=$BackAddr?>?WorkDate=<?=$WorkDate?>"><b>[X]</b></a>

<center>

   <br><b>*극장(상영관)지정(<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>)*</b><br>


   <form method=post name=write action="<?=$PHP_SELF?>?BackAddr=wrk_silmooja.php&WorkDate=<?=$WorkDate?>" onsubmit="return check_submit(write.checkboxs)">

   <input type=hidden name=gonextpage value="">    <!-- -->
   <input type=hidden name=selectTheather>    <!-- -->
   <input type=hidden name=unselectTheather>  <!-- -->

   <input type=text name=Discript value='<?=$Discript?>' size=7 maxlength=20 class=input>

   <select name=Location>
       <?
       if  ((!$Location) || ($Location=="000"))
       {
       ?>
         <option selected value=000>전체</option>
       <?
       } 
       else  
       {
       ?>
         <option value=000>전체</option>
       <?
       }

       $sQuery = "Select * From bas_location Order By Name " ;
       $query1 = mysql_query($sQuery,$connect) ;

       while ($location_data = mysql_fetch_array($query1) )
       {
         if  ($Location==$location_data["Code"])
         {
         ?>
            <option selected value=<?=$location_data["Code"]?>><?=$location_data["Name"]?></option>
         <?
         } 
         else  
         {
         ?>
            <option value=<?=$location_data["Code"]?>><?=$location_data["Name"]?></option>
         <?
         }
       }
       ?>
   </select>

   <input type=button value="검색" onclick='search_loc(write.checkboxs)'>
   <input type=submit value="확인">

   <table cellpadding=0 cellspacing=0 border=1>
   <tr>
        <td align=center>선택</td>
        <td align=center>상영관</td>
        <td align=center>지역</td>
   </tr>
   
   <?
   $rcCount = 0 ;

   while  ($base_data = mysql_fetch_array($query))
   {
        $rcCount = $rcCount + 1 ;

        $showroom_Theather  = $base_data["Code"] ;
        $showroom_Discript  = $base_data["Discript"] ;
        $showroom_Location  = $base_data["Location"] ;

        $sQuery = "Select * From bas_location             ".
                  " Where Code = '".$showroom_Location."' " ;
        $query1 = mysql_query($sQuery,$connect) ;
        $location_data = mysql_fetch_array($query1) ;

        if  ($location_data)
        {
            $location_Name = $location_data["Name"] ;
        }
        else
        {
            $location_Name = "" ;
        }
        ?>

        <tr>
               <!-- 선택 체크박스 -->
               <?
               $sQuery = "Select count(*) As cntTheather              ".
                         "  From bas_silmoojasangdae                  ".
                         " Where Silmooja  = '".$silmoojaCode."'      ".
                         "   And Theather  = '".$showroom_Theather."' " ;
               $qrySilthr = mysql_query($sQuery,$connect) ;
               if  ($silthr_data = mysql_fetch_array($qrySilthr))
               {
                   if  ($silthr_data["cntTheather"] > 0) 
                   {
                   ?>
                       <td align=center>
                       <input type=checkbox name="checkboxs" value=<?=$showroom_Theather?> checked>
                       </td> 
                   <?
                   }
                   else
                   {
                   ?>
                       <td align=center>
                       <input type=checkbox name="checkboxs" value=<?=$showroom_Theather?>>
                       </td> 
                   <?
                   }
               }
               else
               {
               ?>
                    <td align=center>
                    <input type=checkbox name="checkboxs" value=<?=$showroom_Theather?>>
                    </td> 
               <?
               }
               ?>
               <!-- 상영관 -->
               <td align=left>  <?=$showroom_Discript?>   </td>
               <!-- 지역 -->
               <td align=left>  <?=$location_Name?>       </td>
        </tr>
   <?
   }
   ?>
   </table>


   <a><?=$str_prev_page?></a>
   
   <!--[<a><?=$now_page?></a>/<?=$total_page?>]-->

   [<select name=CurPage onchange='Select_Page();'>
       <?
       for  ($i = 1 ; $i <= $total_page ; $i++)
       {
         if  ($i == $now_page)
         {
         ?>
            <option selected value=<?=$i?>><?=$i?></option>
         <?
         } 
         else  
         {
         ?>
            <option value=<?=$i?>><?=$i?></option>
         <?
         }
       }
       ?>
   </select>/<?=$total_page?>]

   <a><?=$str_next_page?></a>

   </form>

</center>

</body>

</html>

<?
    mysql_close($connect);
    }
?>
