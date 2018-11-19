<?
    session_start();
?>
<html>
<?
    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";        // {[데이터 베이스]} : 환경설정
                   
        $connect = dbconn() ;        // {[데이터 베이스]} : 연결

        mysql_select_db($cont_db) ;  // {[데이터 베이스]} : 디비선택



        if  ($changeCode) 
        {
            ?>
            <script>self.close();</script>
            <?
        }
  
        if  (($changeCode) && ($changeCode!=""))  // 실무자 업무양도
        {
            $Theather = substr($ShowRoom,0,4) ;
            
            $FilmOpen = substr($FilmTitle,0,6) ;
            $FilmCode = substr($FilmTitle,6,2) ;

            $sSingoName = get_singotable($FilmOpen,$FilmCode,$connect) ;  // 신고 테이블 이름..
            $sAccName   = get_acctable($FilmOpen,$FilmCode,$connect) ;    // accumulate 이름..
            $sDgrName   = get_degree($FilmOpen,$FilmCode,$connect) ;  
            $sDgrpName  = get_degreepriv($FilmOpen,$FilmCode,$connect) ;  
            

            // 상영관내 실무자정보를 갱신한다 - 의미없음
            $sQuery = "Update bas_showroom                           ".
                      "   Set Silmooja     = '".$changeCode."',      ".
                      "       SilmoojaName = '".$changeName."'       ".
                      " Where Silmooja = '".$silmooja_Code."'        ".
                      "   And Theather = '".$Theather."'             " ;
            mysql_query($sQuery,$connect) ;        

            // 상영관을 잡고 있는 실무자를 바꿔치기 한다.
            $sQuery = "Update bas_silmoojatheather                   ".
                      "   Set Silmooja = '".$changeCode."',          ".
                      "       Name     = '".$changeName."'           ".
                      " Where Silmooja = '".$silmooja_Code."'        ".
                      "   And Theather = '".$Theather."'             " ;
            mysql_query($sQuery,$connect) ;        

            // 상영관을 잡고 있는 실무자를 바꿔치기 한다.(오늘 자료만)
            $sQuery = "Update bas_silmoojatheatherpriv               ".
                      "   Set Silmooja = '".$changeCode."',          ".
                      "       Name     = '".$changeName."'           ".
                      " Where Silmooja = '".$silmooja_Code."'        ".
                      "   And Open     = '".$FilmOpen."'             ".
                      "   And Film     = '".$FilmCode."'             ".
                      "   And Theather = '".$Theather."'             " ;
            mysql_query($sQuery,$connect) ;        

            // 신고자료에서 실무자를 바꿔치기한다.
            $sQuery = "Update ".$sSingoName."                        ".
                      "   Set Silmooja = '".$changeCode."'           ".
                      " Where Silmooja = '".$silmooja_Code."'        ".
                      "   And Open     = '".$FilmOpen."'             ".
                      "   And Film     = '".$FilmCode."'             ".
                      "   And Theather = '".$Theather."'             " ;
            mysql_query($sQuery,$connect) ;        
             
            // 회차정보에서 실무자를 바꿔치기한다.(기본정보)
            $sQuery = "Update ".$sDgrName."                          ".
                      "   Set Silmooja = '".$changeCode."'           ".
                      " Where Silmooja = '".$silmooja_Code."'        ".
                      "   And Open     = '".$FilmOpen."'             ".
                      "   And Film     = '".$FilmCode."'             ".
                      "   And Theather = '".$Theather."'             " ;
            mysql_query($sQuery,$connect) ;        

            // 회차정보에서 실무자를 바꿔치기한다.(오늘 자료만)
            $sQuery = "Update ".$sDgrpName."                         ".
                      "   Set Silmooja = '".$changeCode."'           ".
                      " Where Silmooja = '".$silmooja_Code."'        ".
                      "   And Open     = '".$FilmOpen."'             ".
                      "   And Film     = '".$FilmCode."'             ".
                      "   And Theather = '".$Theather."'             " ;
            mysql_query($sQuery,$connect) ;        

            // 요금정보에서 실무자를 바꿔치기한다.(오늘 자료만)
            $sQuery = "Update bas_unitpricespriv                     ".
                      "   Set Silmooja = '".$changeCode."'           ".
                      " Where Silmooja = '".$silmooja_Code."'        ".
                      "   And Open     = '".$FilmOpen."'             ".
                      "   And Film     = '".$FilmCode."'             ".
                      "   And Theather = '".$Theather."'             " ;
            mysql_query($sQuery,$connect) ;        

            // 누적정보에서 실무자를 바꿔치기한다.
            $sQuery = "Update ".$sAccName."                          ".
                      "   Set Silmooja = '".$changeCode."'           ".
                      " Where Silmooja = '".$silmooja_Code."'        ".
                      "   And Open     = '".$FilmOpen."'             ".
                      "   And Film     = '".$FilmCode."'             ".
                      "   And Theather = '".$Theather."'             " ;
            mysql_query($sQuery,$connect) ;
        }
        
        $page_num = 10 ;

        $sQuery = "Select count(*) From bas_silmooja   ".
                  " Where Code <> '".$silmooja_Code."' " ;
        $count_search = mysql_query($sQuery,$connect) ;
        $count_search_row = mysql_fetch_row($count_search);

        if  ( !$page ) { $page = 0; }
        $page_size = $page_num*$page;

        $sQuery = "Select * From bas_silmooja                ".
                  " Where Code <> '".$silmooja_Code."'       ".
                  " Order By Name limit $page_size,$page_num " ;
        $qry_silmooja = mysql_query($sQuery,$connect) ;

        $page_1 = $count_search_row[0] / $page_num;
        $page_1 = intval($page_1);
        $page_2 = $count_search_row[0] % $page_num;

        if ( $page_2 > 0 ) { $page_1++; }

        $total_page = intval($page_1);
        $prev_page = $page - 1;
        $next_page = $page + 1;
        $now_page  = $page + 1;

        if  ( $page == 0 )
        {
            $prev_page_tag = "<A href=\"javascript:alert('더이상 페이지가 없습니다.');\">[ 이전 ]</A>";
        }
        else
        {
            $prev_page_tag = "<A href='".$PHP_SELF."?silmooja_Code=".$silmooja_Code."&ShowRoom=$ShowRoom&FilmTitle=$FilmTitle&page=$prev_page&Name=$Name&BackAddr=wrk_filmsupply.php'>[ 이전 ]</A>";
        }

        if  ( $now_page == $total_page )
        {
            $next_page_tag = "<A href=\"javascript:alert('더이상 페이지가 없습니다.');\">[ 다음 ]</A>";
        }
        else
        {
            $next_page_tag = "<A href='".$PHP_SELF."?silmooja_Code=".$silmooja_Code."&ShowRoom=$ShowRoom&FilmTitle=$FilmTitle&page=$next_page&Name=$Name&BackAddr=wrk_filmsupply.php'>[ 다음 ]</A>";
        }  
?>  
  <link rel=stylesheet href=../mtnscom/style.css type=text/css>

  <META HTTP-EQUIV=Content-Type CONTENT="text/html; charset=euc-kr">

  <head>

       <script>

       function Select_Page()
       {
          write.action = '<?=$PHP_SELF?>?page='+(write.CurPage.value-1)+'&silmooja_Code=<?=$silmooja_Code?>&ShowRoom=<?=$ShowRoom?>&FilmTitle=<?=$FilmTitle?>&Name=<?=$Name?>&BackAddr=wrk_filmsupply.php' ;
          write.submit() ;
       }         
         
       // 양도할 실무자를 선택했을때..
       function silmooja_click(sCode,sName)
       {
           answer = confirm("정말로 "+sName+"로 양도 하시겠읍니까?") ;
           if  (answer==true)
           {     
               write.action = '<?=$PHP_SELF?>?silmooja_Code=<?=$silmooja_Code?>&changeCode='+sCode+'&changeName='+sName+'&ShowRoom=<?=$ShowRoom?>&FilmTitle=<?=$FilmTitle?>'  ;
               //alert(write.action) ;

               write.submit() ;
           }           
       }

       </script>

  </head>


  <body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

  <?
  $sQuery = "Select * From cfg_user               ".
            " Where UserId ='".$logged_UserId."'  " ;
  $QryUser = mysql_query($sQuery,$connect) ;
  if  ($ArrUser = mysql_fetch_array($QryUser))
  {
      $UserName = $ArrUser["Name"]  ;
  }
  ?>
  <? echo "<b>".$UserName . "</b>님을 환영합니다!" ; ?>
  <a href="#" OnClick="self.close();"><b>[X]</b></a>

  <center>

   <br><b>*등록된실무자*</b><br>

   <form method=post name=write>

   <table border="1" cellpadding="2" cellspacing="0">
     <tr>
          <td align=center>코드</td>
          <td align=center>이름</td>
          <td align=center>핸드폰</td>
          <td align=center>상영관</td>
          <td align=center>영화</td>
     </tr>
   <?
   while  ($base_data = mysql_fetch_array($qry_silmooja))
   {
         $silmooja_Code   = $base_data["Code"] ;    // 실무자 코드
         $silmooja_UserId = $base_data["UserId"] ;  // 실무자 사용자ID
         $silmooja_Name   = $base_data["Name"] ;    // 실무자 이름
         $silmooja_HPNo   = $base_data["HPNo"] ;    // 실무자 핸폰

         $sQuery = "Select * From bas_silmoojatheatherpriv  ".
                   " Where Silmooja  = '".$silmooja_Code."' ".
                   "   And WorkDate  = '".$WorkDate."'      " ;
         $query1 = mysql_query($sQuery,$connect) ; 
         $silmothetprv_data = mysql_fetch_array($query1) ;
         if  (!$silmothetprv_data)
         {
         }

         if  ( ($silmooja_Theather!="") && ($silmooja_Room!="") )
         {
             $sQuery = "Select * From bas_showroom                   ".
                       " Where Theather = '".$silmooja_Theather."'   ".
                       "   And Room     = '".$silmooja_Room."'       " ;
             $qry_showroom = mysql_query($sQuery,$connect) ;
             $showroom_data = mysql_fetch_array($qry_showroom);

             if  ($showroom_data)
             {                                        
                 $showroom_Discript = $showroom_data["Discript"] ;
             }
         }
         else
         {
             $showroom_Discript = "" ;
         }
   ?>
     <tr>
          <td align=center>
          <?
          if  ($silmooja_Code!="") echo "<a onclick=\"silmooja_click('".$silmooja_Code."','".$silmooja_Name."');\">".$silmooja_Code."</a>" ;
          else                     echo "&nbsp;" ;
          ?>
          </td>

          <td align=left>
          <?
          if  ($silmooja_Name!="") echo "<a onclick=\"silmooja_click('".$silmooja_Code."','".$silmooja_Name."');\">".$silmooja_Name."</a>" ;
          else                     echo "&nbsp;" ;
          ?>
          </td>

          <td align=left>
          <?
          if  ($silmooja_HPNo!="") echo "<a onclick=\"silmooja_click('".$silmooja_Code."','".$silmooja_Name."');\">".$silmooja_HPNo."</a>" ;
          else                     echo "&nbsp;" ;
          ?>
          </td>

          <td>&nbsp;</td>
          <td>&nbsp;</td>

     </tr>
   <?
   }
   ?>
   </table>   

   <BR>
   <a><?=$prev_page_tag?></a>

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

   <a><?=$next_page_tag?></a>

   <form>
  </center>

  </body>

</html>


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
