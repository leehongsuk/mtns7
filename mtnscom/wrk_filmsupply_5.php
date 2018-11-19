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

        $page_num = 10 ;

        $count_search = mysql_query("select count(*) from bas_silmooja    ",$connect) ;
        $count_search_row = mysql_fetch_row($count_search);
        if  ( !$page ) { $page = 0; }
        $page_size = $page_num*$page;

        $qrySilmooja = mysql_query("select * from bas_silmooja                    ".
                                   "order by Name asc limit $page_size,$page_num ",$connect) ;

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
            $prev_page = "<A href='./wrk_filmsupply_5.php?page=$prev_page&Name=$Name&BackAddr=wrk_filmsupply.php'>[ 이전 ]</A>";
        }

        if ( $now_page == $total_page )
        {
           $next_page = "<A href=\"javascript:alert('더이상 페이지가 없습니다.');\">[ 다음 ]</A>";
        }
        else
        {
            $next_page = "<A href='./wrk_filmsupply_5.php?page=$next_page&Name=$Name&BackAddr=wrk_filmsupply.php'>[ 다음 ]</A>";
        }  
?>
<html>
  
  <link rel=stylesheet href=./style.css type=text/css>
  <META HTTP-EQUIV=Content-Type CONTENT="text/html; charset=euc-kr">

  <head>
       <script>

       function check_submit()
       {
           return true ;
       }

       </script>
  </head>


  <body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

  <? echo "<b>".$UserName . "</b>님을 환영합니다!" ; ?>
  <a OnClick="location.href='../index_com.php?actcode=logout'"><b>[LogOut]</b></a>
  <a OnClick="location.href='<?=$BackAddr?>'"><b>[X]</b></a>

  <center>

   <br><b>*등록된실무자*</b><br>

   <form method=post name=write action="wrk_filmsupply_5.php?ShowroomCode=<?=$ShowroomCode?>&BackAddr=wrk_filmsupply.php" onsubmit="return check_submit()">

   <table border="1" cellpadding="2" cellspacing="0">
     <tr>
          <td align=left>코드</td>
          <td align=left>ID</td>
          <td align=left>이름</td>
          <td align=left>주민번호</td>
          <!--<td align=left>핸드폰</td>-->
          <td align=left>지역</td>
          <td align=left>상영관</td>
     </tr>
   <?
   while  ($base_data = mysql_fetch_array($qrySilmooja))
   {
         $silmooja_Code     = $base_data["Code"] ;
         $silmooja_UserId   = $base_data["UserId"] ;
         $silmooja_Name     = $base_data["Name"] ;
         $silmooja_Jumin    = $base_data["Jumin"] ;
         $silmooja_HPNo     = $base_data["HPNo"] ;
         $silmooja_Theather = $base_data["Theather"] ;
         $silmooja_Room     = $base_data["Room"] ;

         if  ( ($silmooja_Theather!="") && ($silmooja_Room!="") )
         {
             $query3 = mysql_query("select * from bas_showroom                  ".
                                   "where Theather = '".$silmooja_Theather."'   ".
                                   "  and Room     = '".$silmooja_Room."'       ",$connect) ;
             $showroom_data = mysql_fetch_array($query3);

             if  ($showroom_data)
             {                                        
                 $showroom_Location = $showroom_data["Location"] ;
                 $showroom_Discript = $showroom_data["Discript"] ;

                 $query4 = mysql_query("select * from bas_location            ".
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
                 $location_Name = "" ;
             }
         }
         else
         {
             $showroom_Discript = "" ;
             $location_Name = "" ;
         }
   ?>
     <tr>
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

          <!--<td align=left>
          <?
          if  ($silmooja_HPNo!="") echo "<a onclick=\"edit_zone('".$silmooja_Code."');\">".$silmooja_HPNo."</a>" ;
          else                     echo "&nbsp;" ;
          ?>
          </td>-->
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
   <a><?=$prev_page?></a>
   [<a><?=$now_page?></a>/<?=$total_page?>]
   <a><?=$next_page?></a>

   <form>
  </center>

  </body>

</html>
<?
    mysql_close($connect);
    }
?>

