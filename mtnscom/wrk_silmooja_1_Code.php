<?
    session_start();

    //
    // �ǹ��� - ����(�󿵰�)����
    //

    include "config.php";

    // ���������� �α��� �ߴ��� üũ�Ѵ�.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = 'index_com.php'</script>";
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
        $sQuery = "Select * From bas_silmooja           ".
                  " Where UserId = '".$logged_UserId."' " ;
        $query1 = mysql_query($sQuery,$connect) ;
        if  ($silmooja_data = mysql_fetch_array($query1))
        {
            $silmoojaCode = $silmooja_data["Code"] ;
            $silmoojaName = $silmooja_data["Name"] ;
        }

        
        $nCount = 0 ;

        

        
        $nCount = 0 ;

        
        
        
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
            if  ((!$Location) || ($Location=="000")) // ������ü
            {
                $sQuery = "Select count(*) From bas_theather  " ;
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
                $sQuery = "Select count(*) From bas_theather  ".
                          " Where Location = '".$Location."'  " ;
                $count_search = mysql_query($sQuery,$connect) ;
                $count_search_row = mysql_fetch_row($count_search);

                if  ( !$page ) { $page = 0; }
                $page_size = $page_num*$page;

                $sQuery = "Select * From bas_theather                    ".
                          " Where Location = '".$Location."'             ".
                          " Order By Discript limit $page_size,$page_num " ;
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
            $str_prev_page = "<input  type=button value=\"����\" OnClick=\"javascript:alert('���̻� �������� �����ϴ�.');\">";
        }
        else
        {
            $str_prev_page = "<input  type=button value=\"����\" OnClick=\"move_prev(write.checkboxs);\">";
        }

        if ( $now_page == $total_page )
        {
           $str_next_page = "<input  type=button value=\"����\" OnClick=\"javascript:alert('���̻� �������� �����ϴ�.');\">";
        }
        else
        {
            $str_next_page = "<input  type=button value=\"����\" OnClick=\"move_next(write.checkboxs);\">";
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
            
                write.selectTheather.value = selected ;
                write.unselectTheather.value = unselected ;

                write.gonextpage.value = "Yes" ; // ������������ �Ѿ��.

                return true;
            }   
         }

         function check_theether(sShowroom)
         {
            //location.href="wrk_silmooja_2.php?ShowroomCode="+sShowroom+"&BackAddr=wrk_silmooja.php" ;
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
<? echo "<b>".$UserName . "</b>���� ȯ���մϴ�!" ; ?>
<a href="../index_com.php?actcode=logout"><b>[LogOut]</b></a>
<a href="<?=$BackAddr?>?WorkDate=<?=$WorkDate?>"><b>[X]</b></a>

<center>

   <br><b>*�����ڵ�ǥ(<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>)*</b><br>


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
         <option selected value=000>��ü</option>
       <?
       } 
       else  
       {
       ?>
         <option value=000>��ü</option>
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

   <input type=button value="�˻�" onclick='search_loc(write.checkboxs)'>
   <input type=submit value="Ȯ��">

   <table cellpadding=0 cellspacing=0 border=1>
   <tr>
        <td align=center>�󿵰�</td>
        <td align=center>����</td>
        <td align=center>�ڵ�</td>
        <!-- 
        <td align=center>�ǹ���</td>
        <td align=center>��ȭ</td>
        -->
   </tr>
   
   <?
   $rcCount = 0 ;

   while  ($base_data = mysql_fetch_array($query))
   {
        $rcCount = $rcCount + 1 ;

        $showroom_Theather  = $base_data["Code"] ;
        $showroom_Discript  = $base_data["Discript"] ;
        $showroom_Location  = $base_data["Location"] ;
        $showroom_Seat      = $base_data["Seat"] ;

        $sQuery = "Select * From bas_location              ".
                  " Where Code = '".$showroom_Location."'  " ;
        $query1 = mysql_query($sQuery,$connect) ;
        if  ($location_data = mysql_fetch_array($query1))
        {
            $location_Name = $location_data["Name"] ;
        }
        else
        {
            $location_Name = "" ;
        }
        ?>

        <tr>
               <!-- �󿵰� -->
               <td align=left>  <?=$showroom_Discript?>   </td>
               <!-- ���� -->
               <td align=left>  <?=$location_Name?>       </td>
               <!-- �����ڵ� -->
               <td align=right> <?=$showroom_Theather?>      </td>

               <?
               /*
               //�̹� �ٸ��ǹ��ڰ� �󿵰��� ��� ������ .
               $sQuery = "Select * From bas_silmoojatheather          ".                
                         " Where Silmooja <> '".$silmoojaCode."'      ".
                         "   And Theather  = '".$showroom_Theather."' ".
                         "   And Room      = '".$showroom_Room."'     " ;
               $qry_silmoojatheather = mysql_query($sQuery,$connect) ;
               $silmoojatheather_data = mysql_fetch_array($qry_silmoojatheather) ;
               if  ($silmoojatheather_data)
               {
                   $silmoojatheatherTitle = $silmoojatheather_data["Title"] ;
                   
                   $sQuery = "Select * From bas_silmooja                             ".
                             " Where Code = '".$silmoojatheather_data["Silmooja"]."' " ;
                   $query1 = mysql_query($sQuery,$connect) ;

                   $othersilmooja_data = mysql_fetch_array($query1) ;

                   if  ($othersilmooja_data)
                   {
                       $othersilmoojaName  = $othersilmooja_data["Name"] ;                       
                   }
                   ?>
                   <td align=center>&nbsp;<?=$othersilmoojaName?>&nbsp;</td>
                   <td align=center>&nbsp;<?=$silmoojatheatherTitle?>&nbsp;</td>
                   <?
               }
               else
               {
                   ?>
                   <td align=center>&nbsp;</td>
                   <td align=center>&nbsp;</td>
                   <?
               }
               */
               ?>

        </tr>
   <?
   }
   ?>
   </table>

   <!-- <font color="white">���ߺ��� �̸��� �ִٸ� �󿵰��� �������� ���ÿ�.<br>(2�� ���û��� ���� ����)</font> -->
   <br>

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
