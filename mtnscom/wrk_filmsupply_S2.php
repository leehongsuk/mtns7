<?
    session_start();

    // ���������� �α��� �ߴ��� üũ�Ѵ�.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = '../index_com.php'</script>";
    }
    else
    {
        include "config.php";
        
        $connect=dbconn();

        mysql_select_db($cont_db) ; // �ش��޻縦 ���ϰ�

        $nCount = 0 ;


        if  ($unselectSilmooja!="")   // ������ �����ʾ��� ��
        {        
            $sTemp = $unselectSilmooja ;

            while (($j = strpos($sTemp ,',')) > 0)
            {
                $item = substr($sTemp,0,$j) ;

                mysql_query("Delete From bas_filmtitlesilmooja          ".
                            " Where Open        = '".substr($FilmTitle,0,6)."' ".
                            "   And Film        = '".substr($FilmTitle,6,2)."' ".
                            "   And Silmooja    = '".$item."'                  ",$connect) ;
                
                
                $nCount++ ;

                $sTemp = substr($sTemp,$j+1) ;
            }
        }

        $nCount = 0 ;

        if  ($selectSilmooja!="")     // �������� ��
        {
            $sTemp = $selectSilmooja ;

            while (($j = strpos($sTemp ,',')) > 0)
            {
                $item = substr($sTemp,0,$j) ;
                
                $query2 = mysql_query("Select * From bas_filmtitle               ".
                                      " Where Open = '".substr($FilmTitle,0,6)."' ".
                                      "   And Code = '".substr($FilmTitle,6,2)."' ",$connect) ;
                $filmtitle_data = mysql_fetch_array($query2);

                if  ($filmtitle_data)
                {
                    $filmtitleName = $filmtitle_data["Name"] ;
                }
                else 
                {
                    $filmtitleName = "" ;
                }
               
                $query2 = mysql_query("Select * From bas_silmooja   ".
                                      " Where Code = '".$item."'     ",$connect) ;
                $silmooja_data = mysql_fetch_array($query2);

                if  ($silmooja_data)
                {
                    $silmoojaName = $silmooja_data["Name"] ;
                }
                else 
                {
                    $silmoojaName = "" ;
                }
                
                mysql_query("Insert Into bas_filmtitlesilmooja   ".
                            "Values (                                  ".
                            "         '".substr($FilmTitle,0,6)."',    ".
                            "         '".substr($FilmTitle,6,2)."',    ".
                            "         '".$item."',                     ".
                            "         '".$filmtitleName."',            ".
                            "         '".$silmoojaName."'              ".
                            "        )                                 ",$connect) ;          
                
                $nCount++ ;

                $sTemp = substr($sTemp,$j+1) ;
            }
        }

        if  ($gonextpage=="Yes") // "Ȯ��"�� ������ ���� �������� �Ѿ��.
        {
            //location.href="wrk_filmsupply_S2.php?ShowroomCode="+sShowroom+"&BackAddr=wrk_filmsupply.php" ;
            //echo "<script>location.href=\"wrk_filmsupply.php\"</script>" ;
        }

        $page_num = 10 ;

        if  ($silmooName) // �˻������� �ִٸ�..
        {
            $count_search = mysql_query("Select count(*) From bas_silmooja     ".
                                        " Where Name like '%".$silmooName."%'  ",$connect) ;
            $count_search_row = mysql_fetch_row($count_search);
            if  ( !$page ) { $page = 0; }
            $page_size = $page_num*$page;

            $qrySilmooja = mysql_query("Select * From bas_silmooja               ".
                                       " Where Name like '%".$silmooName."%'     ".
                                       " Order By Name limit $page_size,$page_num",$connect) ;
        }
        else
        {
            $count_search = mysql_query("Select count(*) From bas_silmooja ",$connect) ;
            $count_search_row = mysql_fetch_row($count_search);
            if  ( !$page ) { $page = 0; }
            $page_size = $page_num*$page;

            $qrySilmooja = mysql_query("Select * From bas_silmooja                ".
                                       " Order By Name limit $page_size,$page_num ",$connect) ;
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
            //$str_prev_page = "<A href='./wrk_filmsupply_S2.php?page=$prev_page&Name=$Name&BackAddr=wrk_filmsupply.php'>[ ���� ]</A>";
            $str_prev_page = "<A onclick='move_prev(write.checkboxs)'>[ ���� ]</A>";
        }

        if ( $now_page == $total_page )
        {
           $str_next_page = "<A href=\"javascript:alert('���̻� �������� �����ϴ�.');\">[ ���� ]</A>";
        }
        else
        {
            //$str_next_page = "<A href='./wrk_filmsupply_S2.php?page=$next_page&Name=$Name&BackAddr=wrk_filmsupply.php'>[ ���� ]</A>";
            $str_next_page = "<A onclick='move_next(write.checkboxs)'>[ ���� ]</A>";
        }  
?>
<html>
  
  <link rel=stylesheet href=./style.css type=text/css>
  <META HTTP-EQUIV=Content-Type CONTENT="text/html; charset=euc-kr">

  <head>
       <script>

         function search_silmooja(chk)
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

                write.selectSilmooja.value   = selected ;
                write.unselectSilmooja.value = unselected ;

                write.action = 'wrk_filmsupply_S2.php?page=0&FilmTitle=<?=$FilmTitle?>&silmooName=<?=$silmooName?>&BackAddr=wrk_filmsupply.php' ;
                write.submit() ;
            }
            else
            {
                write.action = 'wrk_filmsupply_S2.php?page=0&FilmTitle=<?=$FilmTitle?>&silmooName=<?=$silmooName?>&BackAddr=wrk_filmsupply.php' ;
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
            
                write.selectSilmooja.value = selected ;
                write.unselectSilmooja.value = unselected ;

                write.gonextpage.value = "Yes" ; // ������������ �Ѿ��.

                return true;
            }   
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

            write.selectSilmooja.value   = selected ;
            write.unselectSilmooja.value = unselected ;

            write.action = 'wrk_filmsupply_S2.php?page=<?=$prev_page?>&FilmTitle=<?=$FilmTitle?>&silmooName=<?=$silmooName?>&BackAddr=wrk_filmsupply.php' ;
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

            write.selectSilmooja.value = selected ;
            write.unselectSilmooja.value = unselected ;

            write.action = 'wrk_filmsupply_S2.php?page=<?=$next_page?>&FilmTitle=<?=$FilmTitle?>&silmooName=<?=$silmooName?>&BackAddr=wrk_filmsupply.php' ;
            write.submit() ;
         }
       </script>
  </head>


  <body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

  <? echo "<b>".$UserName . "</b>���� ȯ���մϴ�!" ; ?>
  <a OnClick="location.href='../index_com.php?actcode=logout'"><b>[LogOut]</b></a>
  <a OnClick="location.href='<?=$BackAddr?>'"><b>[X]</b></a>

  <center>

   <br><b>*�ǹ��ڼ���*</b><br>
                                                              
   <form method=post name=write action="wrk_filmsupply_S2.php?FilmTitle=<?=$FilmTitle?>&BackAddr=wrk_filmsupply.php" onsubmit="return check_submit(write.checkboxs)">

   <input type=hidden name=gonextpage value="">    <!-- -->
   <input type=hidden name=selectSilmooja>    <!-- -->
   <input type=hidden name=unselectSilmooja>  <!-- -->

   <input type=text name=silmooName value='<?=$silmooName?>' size=7 maxlength=20 class=input>
   <input type=button value="�˻�" onclick='search_silmooja(write.checkboxs)'>

   <input type=submit value="Ȯ��">
   
   <table border="1" cellpadding="2" cellspacing="0">
     <tr>
          <td align=center>����</td>
          <td align=left>�ڵ�</td>
          <td align=left>ID</td>
          <td align=left>�̸�</td>
          <td align=left>�ֹι�ȣ</td>
          <td align=left>�Ҽ�</td>
          <td align=left>����</td>
          <td align=left>�󿵰�</td>
     </tr>
   <?
   while  ($base_data = mysql_fetch_array($qrySilmooja))
   {
         $silmooja_Code       = $base_data["Code"] ;
         $silmooja_UserId     = $base_data["UserId"] ;
         $silmooja_Name       = $base_data["Name"] ;
         $silmooja_FilmSupply = $base_data["FilmSupply"] ;
         $silmooja_Jumin      = $base_data["Jumin"] ;
         $silmooja_HPNo       = $base_data["HPNo"] ;
         $silmooja_Theather   = $base_data["Theather"] ;
         $silmooja_Room       = $base_data["Room"] ;

         if  ( ($silmooja_Theather!="") && ($silmooja_Room!="") )
         {
             $query3 = mysql_query("Select * From bas_showroom                  ".
                                   "where Theather = '".$silmooja_Theather."'   ".
                                   "   And Room     = '".$silmooja_Room."'       ",$connect) ;
             $showroom_data = mysql_fetch_array($query3);

             if  ($showroom_data)
             {                                        
                 $showroom_Location   = $showroom_data["Location"] ;
                 $showroom_SilmooName = $showroom_data["SilmooName"] ;
                 $showroom_Discript   = $showroom_data["Discript"] ;

                 $query4 = mysql_query("Select * From bas_location            ".
                                       "where Code = '".$showroom_Location."' ",$connect) ;
                 $location_data = mysql_fetch_array($query4);

                 if  ($location_data)
                 {                                        
                     $location_Name = $location_data["Name"] ;
                 }
                 else
                 {   
                     $location_Name = "" ;
                 }
             }
             else
             {
                 $showroom_Discript = "" ;
                 $location_Name     = "" ;
             }
         }
         else
         {
             $showroom_Discript = "" ;
             $location_Name     = "" ;
         }
   ?>
     <tr>
          <td align=center>
          <?

          $sQuery = "Select * From bas_filmtitlesilmooja        ".
                    " Where Open       = '".substr($FilmTitle,0,6)."' ".
                    "   And Film       = '".substr($FilmTitle,6,2)."' ".
                    "   And Silmooja   = '".$silmooja_Code."'         " ;
          $QryFilmsupplytitlesilmooja = mysql_query($sQuery,$connect) ;
          if  ($ArrFilmsupplytitlesilmooja = mysql_fetch_array($QryFilmsupplytitlesilmooja))
          {
              echo "<input type=checkbox name='checkboxs' value=".$silmooja_Code." checked>" ;
          }
          else 
          {
              echo "<input type=checkbox name='checkboxs' value=".$silmooja_Code.">" ;
          }
          ?>

          
          </td>

          <td align=center>
          <?
          if  ($silmooja_Code!="") echo "<a onclick=\"edit_zone('".$silmooja_Code."');\">".$silmooja_Code."</a>" ;
          else                     echo "&nbsp;" ;
          ?>
          </td>

          <td align=left>
          <?
          if  ($silmooja_UserId!="") echo "<a onclick=\"edit_zone('".$silmooja_Code."');\">".$silmooja_UserId."</a>" ;
          else                       echo "&nbsp;" ;
          ?>
          </td>

          <td align=left>
          <?
          if  ($silmooja_Name!="") echo "<a onclick=\"edit_zone('".$silmooja_Code."');\">".$silmooja_Name."</a>" ;
          else                     echo "&nbsp;" ;
          ?>
          </td>

          <td align=left>
          <?
          if  ($silmooja_Jumin!="") echo "<a onclick=\"edit_zone('".$silmooja_Code."');\">".$silmooja_Jumin."</a>" ;
          else                      echo "&nbsp;" ;
          ?>
          </td>

          <td align=left>
          <input type=checkbox name='members' checked>
          </td>

          <td align=left>
          <?
          if  ($location_Name!="") echo "<a onclick=\"edit_zone('".$silmooja_Code."');\">".$location_Name."</a>" ;
          else                     echo "&nbsp;" ;
          ?>
          </td>

          <td align=left>
          <?
          if  ($showroom_Discript!="") echo "<a onclick=\"edit_zone('".$silmooja_Code."');\">".$showroom_Discript."</a>" ;
          else                         echo "&nbsp;" ;
          ?>                  
          </td>

     </tr>
   <?
   }
   ?>
   </table>   

   <BR>
   <a><?=$str_prev_page?></a>
   [<a><?=$now_page?></a>/<?=$total_page?>]
   <a><?=$str_next_page?></a>

   <form>
  </center>

  </body>

</html>

<?
    mysql_close($connect);

    }
?>
