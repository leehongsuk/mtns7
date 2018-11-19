<?
    session_start();
?>
<html>
<?
    // 정상적으로 로그인 했는지 체크한다.
    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";

        $connect = dbconn();

        mysql_select_db($cont_db) ; 


        $FilmOpen = substr($FilmTitle,0,6) ;
        $FilmCode = substr($FilmTitle,6,2) ;

        $sSingoName = get_singotable($FilmOpen,$FilmCode,$connect) ;  // 신고 테이블 이름..

        $ColorA =  '#ffebcd' ;
        $ColorB =  '#dcdcec' ;    
        $ColorC =  '#dcdcdc' ;
        $ColorD =  '#c0c0c0' ;
?>

  <link rel=stylesheet href=./LinkStyle.css type=text/css>
  <META HTTP-EQUIV=Content-Type CONTENT="text/html; charset=euc-kr">
  
  <head>
  <title>SMS 관리극장</title>
  </head>


  <body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 >

     <center>

        <b>SMS 관리극장</b>
        
          <?
          $sQuery = "Select * From bas_filmtitle    ".
                    " Where Open = '".$FilmOpen."'  ".
                    "   And Code = '".$FilmCode."'  " ;
          $qry_TitleName = mysql_query($sQuery,$connect) ;
          if ($TitleName_data = mysql_fetch_array($qry_TitleName) )
          {
             echo "<br>".$TitleName_data["Name"]."<br>" ; // 영화명출력
          }          
          ?>

          <FORM METHOD=POST ACTION="<?=$PHP_SELF?>?Result=Yes&FilmTitle=<?=$FilmTitle?>&logged_UserId=<?=$logged_UserId?>&logged_Name=<?=$logged_Name?>&BackAddr=wrk_filmsupply.php">

          <TABLE cellpadding=0 cellspacing=0 border=1 bordercolor='#C0B0A0'>
                <TR>
                     <td bgcolor=<?=$ColorA?>>&nbsp;지역&nbsp;</td>
                     <td bgcolor=<?=$ColorA?>>&nbsp;극장명&nbsp;</td>
                     <td bgcolor=<?=$ColorA?>>&nbsp;폰번호&nbsp;</td>
                     <td bgcolor=<?=$ColorA?>>&nbsp;전송이력&nbsp;</td>
                     <td bgcolor=<?=$ColorA?>>일수</td>
                     <td bgcolor=<?=$ColorA?>>선택1</td>
                     <td bgcolor=<?=$ColorA?>>선택2</td>
                     <td bgcolor=<?=$ColorA?>>선택3</td>
                     <td bgcolor=<?=$ColorA?>>선택4</td>
                     <td bgcolor=<?=$ColorA?>>선택5</td>
                </TR>
          <?
          $sQuery = "Select Singo.RoomOrder,                         ".
                    "       Singo.Theather,                          ".
                    "       Singo.Open,                              ".
                    "       Singo.Film,                              ".
                    "       Singo.Silmooja,                          ".
                    "       Theather.Discript As Discript ,          ".
                    "       Location.Name     As LocationName,       ".
                    "       Count(distinct Singo.PhoneNo) As CntPhoneNo ". 
                    "  From ".$sSingoName."     As Singo,            ".
                    "       bas_theather  As Theather,               ".
                    "       bas_location  As Location                ".
                    " Where Singo.Theather   = Theather.Code         ".
                    "   And Singo.Open       = '".$FilmOpen."'       ".
                    "   And Singo.Film       = '".$FilmCode."'       ".
                    "   And Singo.Silmooja   <> '888888'             ".
                    "   And Singo.Silmooja   <> '777777'              ".
                    "   And Singo.PhoneNo    <> '관리자'             ".
                    "   And Singo.Location   = Location.Code         ".
                    " Group By Singo.Theather,                       ".
                    "          Singo.Open,                           ".
                    "          Singo.Film,                           ".
                    "          Singo.Silmooja ,                      ".
                    "          Theather.Discript                     ".
                    " Order By Singo.RoomOrder,                      ".
                    "          Theather.Discript,                    ".
                    "          Singo.Theather,                       ".
                    "          Singo.Room                            " ; 
          $qry_Singo = mysql_query($sQuery,$connect) ;
          while ($Singo_data = mysql_fetch_array($qry_Singo) )
          {
              $Discript     = $Singo_data["Discript"]."(".$Singo_data["Silmooja"].")" ;
              $Theather     = $Singo_data["Theather"] ;
              $CntPhoneNo   = $Singo_data["CntPhoneNo"] ;
              $LocationName = $Singo_data["LocationName"] ;              
              
              if  ($Result=="Yes") // 확인 버튼을 눌렸을 때 - 정보를 갱신한다....
              {
                  $sQuery = "Delete From wrk_smschk             ".
                            " Where Open     = '".$FilmOpen."'  ".
                            "   And Film     = '".$FilmCode."'  ".
                            "   And Theather = '".$Theather."'  " ;
                  mysql_query($sQuery,$connect) ;

                  $Var1 = $FilmOpen.$FilmCode.$Theather."1" ;
                  $Var2 = $FilmOpen.$FilmCode.$Theather."2" ;
                  $Var3 = $FilmOpen.$FilmCode.$Theather."3" ;
                  $Var4 = $FilmOpen.$FilmCode.$Theather."4" ;
                  $Var5 = $FilmOpen.$FilmCode.$Theather."5" ;

                  if  (($$Var1 == 'Y') || ($$Var2 == 'Y') || ($$Var3 == 'Y') || ($$Var4 == 'Y') || ($$Var5 == 'Y'))
                  {
                      $sQuery = "Insert Into wrk_smschk    ".
                                "Values                    ".
                                "(                         ".
                                "  '".$FilmOpen."',        ".
                                "  '".$FilmCode."',        ".
                                "  '".$Theather."',        ".
                                "  '".$PhoneNo."',         ".
                                "  '".$$Var1."',           ".
                                "  '".$$Var2."',           ".
                                "  '".$$Var3."',           ".
                                "  '".$$Var4."',           ".
                                "  '".$$Var5."'            ".
                                ")                         " ;
                      mysql_query($sQuery,$connect) ;
                  }
              }
              
              
              
              
              
              if  ($CntPhoneNo>1)
              {
                  $i = 0 ;
                  
                  $sQuery = "Select distinct PhoneNo               ".
                            "  From ".$sSingoName."                ".
                            " Where Silmooja   = '111111'          ".
                            "   And Theather   = '".$Theather."'   ".
                            "   And Open       = '".$FilmOpen."'   ".
                            "   And Film       = '".$FilmCode."'   ".
                            "   And PhoneNo    <> '관리자'         " ;
                  $QRY_PhoneNo = mysql_query($sQuery,$connect) ;
                  while ($OBJ_PhoneNo = mysql_fetch_object($QRY_PhoneNo) )
                  {
                     $i ++ ;
                     $PhoneNo = $OBJ_PhoneNo->PhoneNo ;
                     ?>
                     <tr>   
                     <td bgcolor=<?=$ColorC?>>&nbsp;<?=$LocationName?>&nbsp;</TD>
                     <?if  ($i==1) { ?><td bgcolor=<?=$ColorC?> rowspan=<?=$CntPhoneNo?>><?=$Discript?></TD><? } ?>                     
                     <td bgcolor=<?=$ColorC?>>&nbsp;<?=$PhoneNo?>&nbsp;&nbsp;</TD>
                     <td bgcolor=<?=$ColorC?>>
                     <?
                     $Temp     = "" ;
                     $sBfMonth = "" ;
                     $nDayNum  = 0 ;

                     $sQuery = "Select distinct SingoDate             ".
                               "  From ".$sSingoName."                ".
                               " Where Silmooja   = '111111'          ".
                               "   And Theather   = '".$Theather."'   ".
                               "   And Open       = '".$FilmOpen."'   ".
                               "   And Film       = '".$FilmCode."'   ".
                               "   And PhoneNo    = '".$PhoneNo."'    ".
                               " Order By SingoDate                   " ; 
                     $QRY_SingoDate = mysql_query($sQuery,$connect) ;
                     while ($OBJ_SingoDate = mysql_fetch_object($QRY_SingoDate) )
                     {
                          $nDayNum++ ;
                          
                          $sMonth = substr($OBJ_SingoDate->SingoDate,4,2) ;
                          $sDay   = substr($OBJ_SingoDate->SingoDate,6,2) ;

                          if  ($Temp != "")
                          {
                              $Temp .= "," ;
                          }
                          if  ($sBfMonth != $sMonth)
                          {
                              $Temp .= "<b>".$sMonth."</b>/" ;
                          }
                          $Temp .= $sDay ;

                          $sBfMonth = $sMonth ;
                     }
                     echo $Temp ;
                     ?>
                     </TD>

                     <td bgcolor=<?=$ColorC?> align="center">
                     <?=$nDayNum?>
                     </TD>

                     <?
                     if  ($i==1) 
                     { 
                         $sQuery = "Select Chk1,Chk2,Chk3,Chk4,Chk5    ".
                                   "  From wrk_smschk                  ".
                                   " Where Open     = '".$FilmOpen."'  ".
                                   "   And Film     = '".$FilmCode."'  ".
                                   "   And Theather = '".$Theather."'  " ;
                         $qry_Smschk = mysql_query($sQuery,$connect) ;
                         if  ($Smschk_data = mysql_fetch_array($qry_Smschk) )
                         {
                             if  ($Smschk_data["Chk1"] == 'Y')
                             {
                                 ?>
                                 <td bgcolor=<?=$ColorC?> rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>1" checked value="Y"></TD>
                                 <?
                             }
                             else
                             {
                                 ?>
                                 <td bgcolor=<?=$ColorC?> rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>1" value="Y"></TD>
                                 <?
                             }

                             if  ($Smschk_data["Chk2"] == 'Y')
                             {
                                 ?>
                                 <td bgcolor=<?=$ColorC?> rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>2" checked value="Y"></TD>
                                 <?
                             }
                             else
                             {
                                 ?>
                                 <td bgcolor=<?=$ColorC?> rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>2" value="Y"></TD>
                                 <?
                             }

                             if  ($Smschk_data["Chk3"] == 'Y')
                             {
                                 ?>
                                 <td bgcolor=<?=$ColorC?> rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>3" checked value="Y"></TD>
                                 <?
                             }
                             else
                             {
                                 ?>
                                 <td bgcolor=<?=$ColorC?> rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>3" value="Y"></TD>
                                 <?
                             }

                             if  ($Smschk_data["Chk4"] == 'Y')
                             {
                                 ?>
                                 <td bgcolor=<?=$ColorC?> rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>4" checked value="Y"></TD>
                                 <?
                             }
                             else
                             {
                                 ?>
                                 <td bgcolor=<?=$ColorC?> rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>4" value="Y"></TD>
                                 <?
                             }

                             if  ($Smschk_data["Chk5"] == 'Y')
                             {
                                 ?>
                                 <td bgcolor=<?=$ColorC?> rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>5" checked value="Y"></TD>
                                 <?
                             }
                             else
                             {
                                 ?>
                                 <td bgcolor=<?=$ColorC?> rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>5" value="Y"></TD>
                                 <?
                             }
                         }
                         else
                         {
                             ?>
                             <td bgcolor=<?=$ColorC?> rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>1" value="Y"></TD>
                             <td bgcolor=<?=$ColorC?> rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>2" value="Y"></TD>
                             <td bgcolor=<?=$ColorC?> rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>3" value="Y"></TD>
                             <td bgcolor=<?=$ColorC?> rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>4" value="Y"></TD>
                             <td bgcolor=<?=$ColorC?> rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>5" value="Y"></TD>
                             <?
                         }
                     } 
                  }                  
              }
              else
              {
                  
                  ?>
                  <tr>      
                  <td bgcolor=<?=$ColorC?>>&nbsp;<?=$LocationName?>&nbsp;</TD>
                  <td bgcolor=<?=$ColorC?>><?=$Discript?></TD>
                  <?
                  $sQuery = "Select distinct PhoneNo               ".
                            "  From ".$sSingoName."                ".
                            " Where Silmooja   = '111111'          ".
                            "   And Theather   = '".$Theather."'   ".
                            "   And Open       = '".$FilmOpen."'   ".
                            "   And Film       = '".$FilmCode."'   ".
                            "   And PhoneNo    <> '관리자'         " ;
                  $QRY_PhoneNo = mysql_query($sQuery,$connect) ;
                  if  ($OBJ_PhoneNo = mysql_fetch_object($QRY_PhoneNo) )
                  {
                      $PhoneNo = $OBJ_PhoneNo->PhoneNo ;
                  }
                  else
                  {
                      $PhoneNo = "" ;
                  }
                  ?>                  
                  <td bgcolor=<?=$ColorC?>>&nbsp;<?=$PhoneNo?>&nbsp;&nbsp;</TD>
                  <td bgcolor=<?=$ColorC?>>
                  <?
                  $Temp     = "" ;
                  $sBfMonth = "" ;
                  $nDayNum  = 0 ;

                  $sQuery = "Select distinct SingoDate             ".
                            "  From ".$sSingoName."                ".
                            " Where Theather   = '".$Theather."'   ".
                            "   And Open       = '".$FilmOpen."'   ".
                            "   And Film       = '".$FilmCode."'   ".
                            "   And Silmooja   = '111111'          ".
                            "   And PhoneNo    = '".$PhoneNo."'    ".
                            " Order By SingoDate                   " ; 
                  $QRY_SingoDate = mysql_query($sQuery,$connect) ;
                  while ($OBJ_SingoDate = mysql_fetch_object($QRY_SingoDate) )
                  {
                       $nDayNum++ ;
                          
                       $sMonth = substr($OBJ_SingoDate->SingoDate,4,2) ;
                       $sDay   = substr($OBJ_SingoDate->SingoDate,6,2) ;

                       if  ($Temp != "")
                       {
                           $Temp .= "," ;
                       }
                       if  ($sBfMonth != $sMonth)
                       {
                           $Temp .= "<b>".$sMonth."</b>/" ;
                       }
                       $Temp .= $sDay ;

                       $sBfMonth = $sMonth ;
                  }
                  echo $Temp ;

                  if   ($Temp == "")
                  {
                       echo "&nbsp;" ;
                  }
                  ?>
                  </TD>
                      
                  <td bgcolor=<?=$ColorC?> align="center">
                     <?=$nDayNum?>
                  </TD>

                  <?
                  $sQuery = "Select Chk1,Chk2,Chk3,Chk4,Chk5    ".
                            "  From wrk_smschk                  ".
                            " Where Open     = '".$FilmOpen."'  ".
                            "   And Film     = '".$FilmCode."'  ".
                            "   And Theather = '".$Theather."'  " ;
                  $qry_Smschk = mysql_query($sQuery,$connect) ;
                  if  ($Smschk_data = mysql_fetch_array($qry_Smschk) )
                  {
                      if  ($Smschk_data["Chk1"] == 'Y')
                      {
                          ?>
                          <td bgcolor=<?=$ColorC?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>1" checked value="Y"></TD>
                          <?
                      }
                      else
                      {
                          ?>
                          <td bgcolor=<?=$ColorC?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>1" value="Y"></TD>
                          <?
                      }
                      if  ($Smschk_data["Chk2"] == 'Y')
                      {
                          ?>
                          <td bgcolor=<?=$ColorC?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>2" checked value="Y"></TD>
                          <?
                      }
                      else
                      {
                          ?>
                          <td bgcolor=<?=$ColorC?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>2" value="Y"></TD>
                          <?
                      }
                      if  ($Smschk_data["Chk3"] == 'Y')
                      {
                          ?>
                          <td bgcolor=<?=$ColorC?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>3" checked value="Y"></TD>
                          <?
                      }
                      else
                      {
                          ?>
                          <td bgcolor=<?=$ColorC?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>3" value="Y"></TD>
                          <?
                      }
                      if  ($Smschk_data["Chk4"] == 'Y')
                      {
                          ?>
                          <td bgcolor=<?=$ColorC?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>4" checked value="Y"></TD>
                          <?
                      }
                      else
                      {
                          ?>
                          <td bgcolor=<?=$ColorC?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>4" value="Y"></TD>
                          <?
                      }
                      if  ($Smschk_data["Chk5"] == 'Y')
                      {
                          ?>
                          <td bgcolor=<?=$ColorC?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>5" checked value="Y"></TD>
                          <?
                      }
                      else
                      {
                          ?>
                          <td bgcolor=<?=$ColorC?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>5" value="Y"></TD>
                          <?
                      }
                  }
                  else
                  {
                      ?>
                      <td bgcolor=<?=$ColorC?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>1" value="Y"></TD>
                      <td bgcolor=<?=$ColorC?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>2" value="Y"></TD>
                      <td bgcolor=<?=$ColorC?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>3" value="Y"></TD>
                      <td bgcolor=<?=$ColorC?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>4" value="Y"></TD>
                      <td bgcolor=<?=$ColorC?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>5" value="Y"></TD>
                      <?
                  }
              }              
          }
          ?>
          
           
           
          </TABLE>
          <BR>
          <INPUT TYPE="submit" Value="확인">
          <BR><BR>
          </FORM>
        
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
 