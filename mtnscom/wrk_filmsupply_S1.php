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

        mysql_select_db($cont_db) ;

        
        $nCount = 0 ;
             

        if  ($selectFilmTitle!="")     // �������� ��
        {
            $sTemp = $selectFilmTitle ;

            while (($j = strpos($sTemp ,',')) > 0)
            {
                $item = substr($sTemp,0,$j) ;
                
                $qryfilmtitle = mysql_query("Select * From bas_filmtitle           ".
                                            " Where Open = '".substr($item,0,6)."' ".
                                            "   And Code = '".substr($item,6,2)."' ",$connect) ;          
                $filmtitle_data = mysql_fetch_array($qryfilmtitle) ;
                if  ($filmtitle_data)
                {
                    $filmtitleName        = $filmtitle_data["Name"] ;
                    $filmtitleFilmProduce = $filmtitle_data["FilmProduce"] ;
                }
                else
                {
                    $filmtitleName        = "" ;
                    $filmtitleFilmProduce =  "" ;
                }

                mysql_query("Insert Into bas_filmsupplytitle      ".
                            "Values (                             ".
                            "         '".$FilmSupply."',          ".
                            "         '".substr($item,0,6)."',    ".
                            "         '".substr($item,6,2)."',    ".
                            "         '".$filmtitleName."',       ".  
                            "         '".$filmtitleFilmProduce."' ".  
                            "        )                            ",$connect) ;          
                
                $nCount++ ;

                $sTemp = substr($sTemp,$j+1) ;
            }
        }

        $nCount = 0 ;

        if  ($unselectFilmTitle!="")     // �������� ��
        {
            $sTemp = $unselectFilmTitle ;

            while (($j = strpos($sTemp ,',')) > 0)
            {
                $item = substr($sTemp,0,$j) ;
                
                mysql_query("Delete From bas_filmsupplytitle       ".
                            " Where FilmSupply = '".$FilmSupply."' ".
                            "   And Open = '".substr($item,0,6)."' ".
                            "   And Film = '".substr($item,6,2)."' ",$connect) ;          
                
                $nCount++ ;

                $sTemp = substr($sTemp,$j+1) ;
            }
        }


        if  ($gonextpage=="Yes") // "Ȯ��"�� ������ ���� �������� �Ѿ��.
        {
            echo "<script>location.href=\"wrk_filmsupply.php\"</script>" ;
        }

        $page_num = 10 ;


        if  ($FilmName) // �˻������� �ִٸ�..
        {
            $count_search = mysql_query("Select count(*) from bas_filmtitle ".
                                        "Where Name like '%".$FilmName."%'  ",$connect) ;
            $count_search_row = mysql_fetch_row($count_search);
            if  ( !$page ) { $page = 0; }
            $page_size = $page_num*$page;

            $qryFilmtitle = mysql_query("Select * From bas_filmtitle              ".
                                        "Where Name like '%".$FilmName."%'        ".
                                        "Order By Name limit $page_size,$page_num ",$connect) ;
        }
        else
        {
            $count_search = mysql_query("select count(*) from bas_filmtitle ",$connect) ;
            $count_search_row = mysql_fetch_row($count_search);
            if  ( !$page ) { $page = 0; }
            $page_size = $page_num*$page;

            $qryFilmtitle = mysql_query("Select * From bas_filmtitle              ".
                                        "Order By Name limit $page_size,$page_num ",$connect) ;
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

<body onload="write.FilmName.focus();" BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
  
   <script>
         
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

                write.selectFilmTitle.value   = selected ;
                write.unselectFilmTitle.value = unselected ;

                write.action = 'wrk_filmsupply_S1.php?page=0&FilmName=<?=$FilmName?>&BackAddr=wrk_filmsupply_S1.php' ;
                write.submit() ;
            }
            else
            {
                write.action = 'wrk_filmsupply_S1.php?page=0&FilmName=<?=$FilmName?>&BackAddr=wrk_filmsupply_S1.php' ;
                write.submit() ;
            }
         }
         

         function check_submit(chk) // Ȯ��
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
            

                write.selectFilmTitle.value = selected ;
                write.unselectFilmTitle.value = unselected ;

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

            write.selectFilmTitle.value   = selected ;
            write.unselectFilmTitle.value = unselected ;

            write.action = 'wrk_filmsupply_S1.php?page=<?=$prev_page?>&FilmName=<?=$FilmName?>&BackAddr=wrk_silmooja.php' ;
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

            write.selectFilmTitle.value = selected ;
            write.unselectFilmTitle.value = unselected ;

            write.action = 'wrk_filmsupply_S1.php?page=<?=$next_page?>&FilemName=<?=$FilemName?>&BackAddr=wrk_silmooja.php' ;
            write.submit() ;
         }

   </script>


<? echo "<b>".$UserName . "</b>���� ȯ���մϴ�!" ; ?>
<a OnClick="location.href='../index_com.php?actcode=logout'"><b>[LogOut]</b></a>
<a OnClick="location.href='<?=$BackAddr?>'"><b>[X]</b></a>

<center>

   <br><b>*��ȭ����*</b><br>


   <form method=post name=write action="wrk_filmsupply_S1.php?FilmName=<?=$FilmName?>&BackAddr=wrk_silmooja.php" onsubmit="return check_submit(write.checkboxs)">

   <input type=hidden name=gonextpage value="">    <!-- -->
   <input type=hidden name=selectFilmTitle>    <!-- -->
   <input type=hidden name=unselectFilmTitle>  <!-- -->

   <input type=text name=FilmName value='<?=$FilmName?>' size=7 maxlength=50 class=input>

   <input type=button value="�˻�" onclick='search_loc(write.checkboxs)'>
   <input type=submit value="Ȯ��">

   <table cellpadding=0 cellspacing=0 border=1>
   <tr>
        <td align=center>����</td>
        <td align=center colspan=2>�ڵ�</td>
        <td align=center>��ȭ��</td>
        <td align=center>���ۿ�ȭ��</td>
   </tr>
   
   <?
   $rcCount = 0 ;

   while  ($base_data = mysql_fetch_array($qryFilmtitle))
   {
        $rcCount = $rcCount + 1 ;

        $filmtitle_Open         = $base_data["Open"] ;
        $filmtitle_Code         = $base_data["Code"] ;
        $filmtitle_Name         = $base_data["Name"] ;
        $filmtitle_FilmProduce  = $base_data["FilmProduce"] ;
        ?>

        <tr>
               <?
               $qryFilmsupplytitle = mysql_query("Select count(*) as cntFileTitle        ".
                                                 "  From bas_filmsupplytitle             ".
                                                 " Where FilmSupply = '".$FilmSupply."'  ".
                                                 "   and Open = '".$filmtitle_Open."'    ".
                                                 "   and Film = '".$filmtitle_Code."'    ",$connect) ;
               $filmsupplytitle_data = mysql_fetch_array($qryFilmsupplytitle) ;
               if  ($filmsupplytitle_data)
               {
                   if  ($filmsupplytitle_data["cntFileTitle"] > 0) 
                   {
                   ?>
                       <td align=center>
                       <input type=checkbox name="checkboxs" value=<?=$filmtitle_Open?><?=$filmtitle_Code?> checked>
                       </td> 
                   <?
                   }
                   else
                   {
                   ?>
                       <td align=center>
                       <input type=checkbox name="checkboxs" value=<?=$filmtitle_Open?><?=$filmtitle_Code?>>
                       </td> 
                   <?
                   }
               }
               else
               {
               ?>
                    <td align=center>
                    <input type=checkbox name="checkboxs" value=<?=$filmtitle_Open?><?=$filmtitle_Code?>>
                    </td> 
               <?
               }
               ?>

               <td align=left> <?=$filmtitle_Open?>        </td>
               <td align=left> <?=$filmtitle_Code?>        </td>
               <td align=left> <?=$filmtitle_Name?>        </td>
               
               <?
               $qryFilmsupplytitle = mysql_query("Select * From bas_filmproduce               ".
                                                 " Where Code = '".$filmtitle_FilmProduce."'  ",$connect) ;
               $filmsupplytitle_data = mysql_fetch_array($qryFilmsupplytitle) ;
               if  ($filmsupplytitle_data)
               {
               ?>
               <td align=left>  <?=$filmsupplytitle_data["Name"]?>  </td> 
               <?
               }
               ?>   
        </tr>
   <?
   }
   ?>
   </table>

   <BR>
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
