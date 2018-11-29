<?
    session_start();
?>
<html>
<?
    // 정상적으로 로그인 했는지 체크한다.
    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";

        $connect=dbconn();

        mysql_select_db($cont_db) ; // 해당배급사를 구하고

        $page_num = 10 ;

        $sQuery = "Select Count(*) From bas_silmooja   " ;
        $count_search = mysql_query($sQuery,$connect) ;
        $count_search_row = mysql_fetch_row($count_search);

        if  ( !$page ) { $page = 0; }
        $page_size = $page_num*$page;

        $sQuery = "Select * From bas_silmooja                    ".
                  " Order By Name asc limit $page_size,$page_num " ;
        $qrySilmooja = mysql_query($sQuery,$connect) ;

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

        $ColorA =  '#ffebcd' ;
        $ColorB =  '#dcdcec' ;    
        $ColorC =  '#dcdcdc' ;
        $ColorD =  '#c0c0c0' ;
?>
<html>
  
  <link rel=stylesheet href=./LinkStyle.css type=text/css>
  <META HTTP-EQUIV=Content-Type CONTENT="text/html; charset=euc-kr">

  <head>
       <script>

       function check_submit()
       {
           return true ;
       }

       </script>
  </head>


  <body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 >


  <center>

   <br><b>*등록된실무자*</b><br>

   <form method=post name=write action="wrk_filmsupply_5.php?ShowroomCode=<?=$ShowroomCode?>&BackAddr=wrk_filmsupply.php" onsubmit="return check_submit()">

   <table border="1" cellpadding="2" cellspacing="0" bordercolor='#C0B0A0'>
     <tr>
          <td align=left bgcolor=<?=$ColorA?>>코드</td>
          <td align=left bgcolor=<?=$ColorA?>>ID</td>
          <td align=left bgcolor=<?=$ColorA?>>이름</td>
          <td align=left bgcolor=<?=$ColorA?>>주민번호</td>
          <td align=left bgcolor=<?=$ColorA?>>지역</td>
          <td align=left bgcolor=<?=$ColorA?>>상영관</td>
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
             $sQuery = "Select * From bas_showroom                   ".
                       " Where Theather = '".$silmooja_Theather."'   ".
                       "   And Room     = '".$silmooja_Room."'       " ;
             $query3 = mysql_query($sQuery,$connect) ;
             if  ($showroom_data = mysql_fetch_array($query3))
             {                                        
                 $showroom_Location = $showroom_data["Location"] ;
                 $showroom_Discript = $showroom_data["Discript"] ;

                 $sQuery = "Select * From bas_location             ".
                           " Where Code = '".$showroom_Location."' " ;
                 $query4 = mysql_query($sQuery,$connect) ;
                 if  ($location_data = mysql_fetch_array($query4))
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
          <td align=center bgcolor=<?=$ColorC?>>
          <?
          if  ($silmooja_Code!="") echo "<a onclick=\"edit_zone('".$silmooja_Code."');\">".$silmooja_Code."</a>" ;
          else                     echo "&nbsp;" ;
          ?>
          </td>

          <td align=left bgcolor=<?=$ColorC?>>
          <?
          if  ($silmooja_UserId!="") echo "<a onclick=\"edit_zone('".$silmooja_Code."');\">".$silmooja_UserId."</a>" ;
          else                       echo "&nbsp;" ;
          ?>
          </td>

          <td align=left bgcolor=<?=$ColorC?>>
          <?
          if  ($silmooja_Name!="") echo "<a onclick=\"edit_zone('".$silmooja_Code."');\">".$silmooja_Name."</a>" ;
          else                     echo "&nbsp;" ;
          ?>
          </td>

          <td align=left bgcolor=<?=$ColorC?>>
          <?
          if  ($silmooja_Jumin!="") echo "<a onclick=\"edit_zone('".$silmooja_Code."');\">".$silmooja_Jumin."</a>" ;
          else                      echo "&nbsp;" ;
          ?>
          </td>

          <td align=left bgcolor=<?=$ColorC?>>
          <?
          if  ($location_Name!="") echo "<a onclick=\"edit_zone('".$silmooja_Code."');\">".$location_Name."</a>" ;
          else                     echo "&nbsp;" ;
          ?>
          </td>

          <td align=left bgcolor=<?=$ColorC?>>
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

       <?
        mysql_close($connect);       
    }
    else // 로그인하지 않고 바로들어온다면..
    {
        ?>
        
        <!-- 로그인하지 않고 바로들어온다면 -->
        <body>
            <script language="JavaScript">
                <!-- 
                window.top.location = '../index_cokr.php' ; 
                //-->
            </script>
        </body>      
        
        <?
    }
    ?>
</html>
 