<?
    //
    // �ǹ��� - ����(�󿵰�)����
    //

    include "config.php";
    session_start();

    // ���������� �α��� �ߴ��� üũ�Ѵ�.
    if ((!$logged_UserId) || ($logged_UserId==""))
    {
       echo "<script language='JavaScript'>window.location = 'index.php'</script>";
    }
    else
    {
        $Today = time()-(3600*7) ; // ���� 7�� ���� ���÷� �����Ѵ�...  

        if (!$WorkDate)
        {
           $WorkDate = date("Ymd",$Today) ;
        }

        $connect=dbconn();

        mysql_select_db($cont_db) ;

        // �ش�ǹ��ڸ� ���ϰ� ..
        $query1 = mysql_query("Select * From bas_silmooja          ".
                              "Where UserId = '".$logged_UserId."' ",$connect) ;

        $silmooja_data = mysql_fetch_array($query1) ;

        if  ($silmooja_data)
        {
            $silmoojaCode = $silmooja_data["Code"] ;
            $silmoojaName = $silmooja_data["Name"] ;
        }

        
        if  ($gonextpage=="Yes") // "Ȯ��"�� ������ ���� �������� �Ѿ��.
        {
            echo "<script>location.href=\"wrk_silmooja.php?WorkDate=".$WorkDate."\"</script>" ;
        }
          
        $page_num = 10 ;

        //echo $Location ."<br><br><br><br>" ; ///////////////////////////////////////////////

        if  ($Discript) // �˻������� �ִٸ�..
        {
            if  ((!$Location) || ($Location=="000")) // ������ü
            {
                $count_search = mysql_query("Select count(*) From bas_theather  ".
                                            " Where Discript like '%".$Discript."%' ",$connect) ;
                $count_search_row = mysql_fetch_row($count_search);
                if  ( !$page ) { $page = 0; }
                $page_size = $page_num*$page;

                $query = mysql_query("Select * From bas_theather         ".
                                     " Where Discript like '%".$Discript."%' ".
                                     " Order By Discript                  ".
                                     "limit $page_size,$page_num         ",$connect) ;
            }
            else
            {
                $count_search = mysql_query("Select count(*) From bas_theather  ".
                                            " Where Location = '".$Location."'  ".
                                            "   And Discript like '%".$Discript."%' ",$connect) ;
                $count_search_row = mysql_fetch_row($count_search);
                if  ( !$page ) { $page = 0; }
                $page_size = $page_num*$page;

                $query = mysql_query("Select * From bas_theather               ".
                                     " Where Location = '".$Location."'        ".
                                     "   And Name like '%".$Discript."%'       ".
                                     " Order By Discript limit $page_size,$page_num",$connect) ;
            }
        }
        else
        {
            if  ((!$Location) || ($Location=="000")) // ������ü
            {
                $count_search = mysql_query("Select count(*) From bas_theather ",$connect) ;
                $count_search_row = mysql_fetch_row($count_search);
                if  ( !$page ) { $page = 0; }
                $page_size = $page_num*$page;

                $query = mysql_query("Select * From bas_theather                ".
                                     " Order By Discript limit $page_size,$page_num ",$connect) ;
            }
            else
            {
                $count_search = mysql_query("Select count(*) From bas_theather ".
                                            " Where Location = '".$Location."' ",$connect) ;
                $count_search_row = mysql_fetch_row($count_search);
                if  ( !$page ) { $page = 0; }
                $page_size = $page_num*$page;

                $query = mysql_query("Select * From bas_theather                ".
                                     " Where Location = '".$Location."'         ".
                                     " Order By Discript limit $page_size,$page_num ",$connect) ;
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
           $str_prev_page = "<A href=\"javascript:alert('���̻� �������� �����ϴ�.');\">[ ���� ]</A>";
        }
        else
        {
            $str_prev_page = "<A onclick='move_prev(write.checkboxs)'>[ ���� ]</A>";
        }

        if ( $now_page == $total_page )
        {
           $str_next_page = "<A href=\"javascript:alert('���̻� �������� �����ϴ�.');\">[ ���� ]</A>";
        }
        else
        {
            $str_next_page = "<A onclick='move_next(write.checkboxs)'>[ ���� ]</A>";
        } 
        
?>

<html>

<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>����(�󿵰�)����</title>
</head>

<body onload="write.Discript.focus();" BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
  
   <script>
         
         function search_loc(chk)
         {
            var selected ;
            var unselected ;

            selected   = "" ;
            unselected = "" ;
            
            if  (chk)  // �˻������ �ϳ��� �ִ°��
            {
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


            if  (chk)  // �˻������ �ϳ��� �ִ°��
            {
                if  (chk[0])  // �˻������ �ϳ��� ���õ� ���� �迭���°� �ƴϹǷ� 
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
                else // �˻������ �ϳ��� ���õ� ���� �迭���°� �ƴϹǷ� 
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
            
                write.gonextpage.value = "Yes" ; // ������������ �Ѿ��.

                return true;
            }   
         }

         function theather_click(sShowRoomCode,sShowRoomName)
         {
            opener.top.frames.up.document.all.chungtheatherCode.value = sShowRoomCode ;
            opener.top.frames.up.document.all.chungtheather.innerHTML = "<a href='#' onclick='showroom_click()'>"+sShowRoomName+"</a>" ;

            self.close() ;
         }

         function move_prev(chk)
         {
            write.action = '<?=$PHP_SELF?>?page=<?=$prev_page?>&Location=<?=$Location?>&Discript=<?=$Discript?>&BackAddr=wrk_silmooja.php&WorkDate='+<?=$WorkDate?> ;
            write.submit() ;
         }

         function move_next(chk)
         {
            write.action = '<?=$PHP_SELF?>?page=<?=$next_page?>&Location=<?=$Location?>&Discript=<?=$Discript?>&BackAddr=wrk_silmooja.php&WorkDate='+<?=$WorkDate?> ;
            write.submit() ;
         }

   </script>

<a href="#" OnClick="self.close();"><b>[X]</b></a>

<center>

   <br><b>*����(�󿵰�)����*</b><br>


   <form method=post name=write action="<?=$PHP_SELF?>?BackAddr=wrk_silmooja.php&WorkDate=<?=$WorkDate?>" onsubmit="return check_submit(write.checkboxs)">

    

   <input type=hidden name=gonextpage value=""> <!-- -->

   <input type=text name=Discript value='<?=$Discript?>' size=7 maxlength=20 class=input>

   <select name=Location>
       <?
       if  ((!$Location) || ($Location=="000"))
       {
       ?>
         <option selected value=000>��ü</option>
       <?
       } 
       else  
       {
       ?>
         <option value=000>��ü</option>
       <?
       }

       $query1 = mysql_query("Select * From bas_location Order By Name ",$connect) ;

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

   <input type=button value="�˻�" onclick='search_loc(write.checkboxs)'>
   <input type=submit value="Ȯ��">

   <table width=90% cellpadding=0 cellspacing=0 border=1>
   <tr>
        <td align=center>����</td>
        <td align=center>�󿵰�</td>
   </tr>
   
   <?
   $rcCount = 0 ;

   while  ($base_data = mysql_fetch_array($query))
   {
        $rcCount = $rcCount + 1 ;

        $showroom_Theather  = $base_data["Code"] ;
        $showroom_Discript  = $base_data["Discript"] ;
        $showroom_Location  = $base_data["Location"] ;

        $ShowRoomCode = $showroom_Theather ;

        $query1 = mysql_query("Select * From bas_location             ".
                              " Where Code = '".$showroom_Location."' ",$connect) ;
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
               <!-- ���� -->
               <td align=left>  
               <a href="#" OnClick="theather_click('<?=$ShowRoomCode?>','<?=$showroom_Discript?>');">
               <?=$location_Name?>       
               </a>
               </td>
               
               <!-- �󿵰� -->
               <td align=left>  
               <a href="#" OnClick="theather_click('<?=$ShowRoomCode?>','<?=$showroom_Discript?>');">
               <?=$showroom_Discript?>
               </a>
               </td>
        </tr>
   <?
   }
   ?>
   </table>
   
   <br>
   <a><?=$str_prev_page?></a>
   [<a><?=$now_page?></a>/<?=$total_page?>]
   <a><?=$str_next_page?></a>

   </form>

</center>

</body>

</html>

<?
    mysql_close($connect);
    }
?>
