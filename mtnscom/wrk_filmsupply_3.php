<?
    session_start();

    // 정상적으로 로그인 했는지 체크한다.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = '../index_com.php'</script>";
    }
    else
    {
        include "config.php";
        
        $connect=dbconn();

        mysql_select_db($cont_db) ; // 해당배급사를 구하고


        if  ($Action == "Execute") // 구역 사항 추가 수정..
        {   
            if  ($ActSource == 'insert') 
            {
                mysql_query("insert bas_zone                                    ".
                            "values ( '".$Code."','".$Name."','".$Discript."' ) ",$connect) ;
            }
            
            if  ($ActSource == 'update') 
            {
                mysql_query("update bas_zone                    ".
                            "   set Name     = '".$Name."',     ".
                            "       Discript = '".$Discript."'  ".
                            "where Code = '".$Code."'           " ,$connect) ;
            }
        }

        if  ($SelectedLocation) // 선택된 지역이 있을 경우
        {
            mysql_query("delete from bas_filmsupplyzoneloc   ".
                        " where Zone = '".$Code."'           ",$connect) ; // 선택된 지역을 지운다.

            $sTemp1 = $SelectedLocation ;

            while (($i = strpos($sTemp1,'.')) > 0)
            {
                $sItem1 = substr($sTemp1,0,$i) ;

                $nCount = 0 ;

                $sTemp2 = $sItem1 ;

                while (($j = strpos($sTemp2 ,',')) > 0)
                {
                    $nCount++ ;

                    $sItem2 = substr($sTemp2,0,$j) ;

                    if  ($nCount==1)  $locationText  = $sItem2 ;
                    if  ($nCount==2)  $locationValue = $sItem2 ;

                    $sTemp2 = substr($sTemp2,$j+1) ;
                }

                mysql_query("insert into bas_filmsupplyzoneloc ".
                            "values (  '".$Code."',            ".
                            "          '".$locationValue."',   ".
                            "          '".$locationText."'     ".
                            "       )                          ",$connect) ;


                $sTemp1 = substr($sTemp1,$i+1) ;
            }
        }
        

        $page_num = 10 ;

        $count_search = mysql_query("select count(*) from bas_zone ",$connect) ;
        $count_search_row = mysql_fetch_row($count_search);
        if  ( !$page ) { $page = 0; }
        $page_size = $page_num*$page;

        $query = mysql_query("select * from bas_zone order by Name desc limit $page_size,$page_num",$connect) ;

        //echo "select * from bas_zone order by Name desc limit $page_size,$page_num" ;

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
           $prev_page = "<A href=\"javascript:alert('더이상 페이지가 없습니다.');\">[ 이전 ]</A>";
        }
        else
        {
            $prev_page = "<A href='./wrk_fiulmsupply_3.php?page=$prev_page&Name=$Name&BackAddr=wrk_filmsupply_3.php'>[ 이전 ]</A>";
        }

        if ( $now_page == $total_page )
        {
           $next_page = "<A href=\"javascript:alert('더이상 페이지가 없습니다.');\">[ 다음 ]</A>";
        }
        else
        {
            $next_page = "<A href='./wrk_fiulmsupply_3.php?page=$next_page&Name=$Name&BackAddr=wrk_filmsupply_3.php'>[ 다음 ]</A>";
        }

        mysql_close($connect);
?>


<html>

<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">
<head>
<title>복합구역지정</title>
</head>


<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
   <script>
         function check_submit()
         {
            return true;
         }
         function new_zone()   // 신규 ...
         {
             location.href='wrk_filmsupply_4.php?ActCode=New&BackAddr=wrk_filmsupply_3.php' ;
         }
         function delete_zone(sCode) // 삭제 ..
         {
             answer = confirm("정말로 삭제하시겠읍니까?") ;
             if  (answer==true)
             {
                 location.href='wrk_filmsupply_4.php?ActCode=Del&Code='+sCode+'&BackAddr=wrk_filmsupply_3.php' ;  
             }             
         }
         function edit_zone(sCode) // 수정 ..
         {
             location.href='wrk_filmsupply_4.php?ActCode=Edt&Code='+sCode+'&BackAddr=wrk_filmsupply_3.php' ;  
         }
   </script>

  <? echo "<b>".$UserName . "</b>님을 환영합니다!" ; ?>
  <a OnClick="location.href='../index_com.php?actcode=logout'"><b>[LogOut]</b></a>
  <a OnClick="location.href='<?=$BackAddr?>'"><b>[X]</b></a>

<center>
   <br><b>*복합구역지정*</b><br>

   <form method=post name=write action="wrk_fiulmsupply_3.php?ShowroomCode=<?=$ShowroomCode?>&BackAddr=wrk_silmooja.php" onsubmit="return check_submit()">

   <table cellpadding=0 cellspacing=0 border=1>
     <tr>
              <td align=left>코드</td>
              <td align=left>구역</td>
              <td align=left>설명</td>
              <td align=center colspan=2>
              <input type=button  value="신규"  onclick="new_zone();">
              </td>
     </tr>
<?
   while  ($base_data = mysql_fetch_array($query))
   {
       $zone_Code     = $base_data["Code"] ;
       $zone_Name     = $base_data["Name"] ;
       $zone_Discript = $base_data["Discript"] ;
?>
       <tr>
            <td align=center>
            <a onclick="edit_zone('<?=$zone_Code?>');">
            <?=$zone_Code?>
            </a>
            </td>

            <td align=left>
            <a onclick="edit_zone('<?=$zone_Code?>');">
            <?=$zone_Name?>
            </a>
            </td>

            <td align=left>
            <a onclick="edit_zone('<?=$zone_Code?>');">
            <?=$zone_Discript?>
            </a>
            </td>

            <td align=left>
            <input type=button  value="삭제" onclick="delete_zone('<?=$zone_Code?>');">
            </td>

            <td align=left>
            <input type=button  value="수정" onclick="edit_zone('<?=$zone_Code?>');">
            </td>
       </tr>
<?
   }
?>
   </table>   

   <BR>
   <a><?=$prev_page?></a>
   [<a><?=$now_page?></a>/<?=$total_page?>]
   <a><?=$next_page?></a>

   </form>

</center>

</body>

</html>
<?
    } 
?>