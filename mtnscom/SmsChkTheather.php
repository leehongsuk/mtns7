<?
    session_start();
?>
<html>
<?    
    // 정상적으로 로그인 했는지 체크한다.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = '../index_com.php'</script>";
    }
    else
    {
        include "config.php";

        $connect = dbconn();

        mysql_select_db($cont_db) ; 


        $FilmOpen = substr($FilmTitle,0,6) ;
        $FilmCode = substr($FilmTitle,6,2) ;

        $sSingoName = get_singotable($FilmOpen,$FilmCode,$connect) ;  // 신고 테이블 이름..
?>

  <link rel=stylesheet href=./style.css type=text/css>
  <META HTTP-EQUIV=Content-Type CONTENT="text/html; charset=euc-kr">
  
  <head>
  <title>SMS 관리극장</title>
  </head>


  <body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
     <? echo "<b>". $UserName . "</b>님을 환영합니다!" ; ?>
     <a OnClick="location.href='../index_com.php?actcode=logout'"><b>[LogOut]</b></a>
     <a OnClick="location.href='<?= $BackAddr?>?logged_UserId=<?=$logged_UserId?>&logged_Name=<?=$logged_Name?>'"><b>[X]</b></a>

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

          <FORM METHOD=POST ACTION="<?=$PHP_SELF?>?Result=Yes&FilmTitle=<?=$FilmCodeTitle?>&logged_UserId=<?=$logged_UserId?>&logged_Name=<?=$logged_Name?>&BackAddr=wrk_filmsupply.php">

          <TABLE cellpadding=0 cellspacing=0 border=1 bordercolor=#C0B0A0>
                <TR>
                     <TD>&nbsp;지역&nbsp;</TD>
                     <TD>&nbsp;극장명&nbsp;</TD>
                     <TD>&nbsp;폰번호&nbsp;</TD>
                     <TD>&nbsp;전송이력&nbsp;</TD>
                     <TD>일수</TD>
                     <TD>선택1</TD>
                     <TD>선택2</TD>
                     <TD>선택3</TD>
                     <TD>선택4</TD>
                     <TD>선택5</TD>
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
                     <TD>&nbsp;<?=$LocationName?>&nbsp;</TD>
                     <?if  ($i==1) { ?><TD rowspan=<?=$CntPhoneNo?>><?=$Discript?></TD><? } ?>                     
                     <TD>&nbsp;<?=$PhoneNo?>&nbsp;&nbsp;</TD>
                     <TD>
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

                     <TD align="center">
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
                                 <TD rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>1" checked value="Y"></TD>
                                 <?
                             }
                             else
                             {
                                 ?>
                                 <TD rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>1" value="Y"></TD>
                                 <?
                             }

                             if  ($Smschk_data["Chk2"] == 'Y')
                             {
                                 ?>
                                 <TD rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>2" checked value="Y"></TD>
                                 <?
                             }
                             else
                             {
                                 ?>
                                 <TD rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>2" value="Y"></TD>
                                 <?
                             }

                             if  ($Smschk_data["Chk3"] == 'Y')
                             {
                                 ?>
                                 <TD rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>3" checked value="Y"></TD>
                                 <?
                             }
                             else
                             {
                                 ?>
                                 <TD rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>3" value="Y"></TD>
                                 <?
                             }

                             if  ($Smschk_data["Chk4"] == 'Y')
                             {
                                 ?>
                                 <TD rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>4" checked value="Y"></TD>
                                 <?
                             }
                             else
                             {
                                 ?>
                                 <TD rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>4" value="Y"></TD>
                                 <?
                             }

                             if  ($Smschk_data["Chk5"] == 'Y')
                             {
                                 ?>
                                 <TD rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>5" checked value="Y"></TD>
                                 <?
                             }
                             else
                             {
                                 ?>
                                 <TD rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>5" value="Y"></TD>
                                 <?
                             }
                         }
                         else
                         {
                             ?>
                             <TD rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>1" value="Y"></TD>
                             <TD rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>2" value="Y"></TD>
                             <TD rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>3" value="Y"></TD>
                             <TD rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>4" value="Y"></TD>
                             <TD rowspan=<?=$CntPhoneNo?>><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>5" value="Y"></TD>
                             <?
                         }
                     } 
                  }                  
              }
              else
              {
                  
                  ?>
                  <tr>      
                  <TD>&nbsp;<?=$LocationName?>&nbsp;</TD>
                  <TD><?=$Discript?></TD>
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
                  <TD>&nbsp;<?=$PhoneNo?>&nbsp;&nbsp;</TD>
                  <TD>
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
                      
                  <TD align="center">
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
                          <TD><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>1" checked value="Y"></TD>
                          <?
                      }
                      else
                      {
                          ?>
                          <TD><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>1" value="Y"></TD>
                          <?
                      }
                      if  ($Smschk_data["Chk2"] == 'Y')
                      {
                          ?>
                          <TD><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>2" checked value="Y"></TD>
                          <?
                      }
                      else
                      {
                          ?>
                          <TD><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>2" value="Y"></TD>
                          <?
                      }
                      if  ($Smschk_data["Chk3"] == 'Y')
                      {
                          ?>
                          <TD><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>3" checked value="Y"></TD>
                          <?
                      }
                      else
                      {
                          ?>
                          <TD><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>3" value="Y"></TD>
                          <?
                      }
                      if  ($Smschk_data["Chk4"] == 'Y')
                      {
                          ?>
                          <TD><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>4" checked value="Y"></TD>
                          <?
                      }
                      else
                      {
                          ?>
                          <TD><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>4" value="Y"></TD>
                          <?
                      }
                      if  ($Smschk_data["Chk5"] == 'Y')
                      {
                          ?>
                          <TD><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>5" checked value="Y"></TD>
                          <?
                      }
                      else
                      {
                          ?>
                          <TD><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>5" value="Y"></TD>
                          <?
                      }
                  }
                  else
                  {
                      ?>
                      <TD><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>1" value="Y"></TD>
                      <TD><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>2" value="Y"></TD>
                      <TD><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>3" value="Y"></TD>
                      <TD><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>4" value="Y"></TD>
                      <TD><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>5" value="Y"></TD>
                      <?
                  }
              }              
          }
          ?>
          

          <?
           /*******
          $sQuery = "Select Singo.RoomOrder,                         ".
                    "       Singo.Theather,                          ".
                    "       Singo.Open,                              ".
                    "       Singo.Film,                              ".
                    "       Singo.Silmooja,                          ".
                    "       Singo.PhoneNo,                           ".
                    "       Theather.Discript As Discript ,          ".
                    "       Location.Name     As LocationName        ".                    
                    "  From ".$sSingoName."     As Singo,            ".
                    "       bas_theather  As Theather,               ".
                    "       bas_location  As Location                ".
                    " Where Singo.Silmooja   = '111111'              ".
                    "   And Singo.Theather   = Theather.Code         ".
                    "   And Singo.Open       = '".$FilmOpen."'           ".
                    "   And Singo.Film       = '".$FilmCode."'           ".
                    "   And Singo.PhoneNo    <> '관리자'             ".
                    " Group By Singo.Theather,                       ".
                    "          Singo.Open,                           ".
                    "         Singo.PhoneNo,                        ".
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
              $Discript = $Singo_data["Discript"] ;
              $PhoneNo  = $Singo_data["PhoneNo"] ;
              $Theather = $Singo_data["Theather"] ;
              ?>

              <TR>     
                   <TD>&nbsp;<?=$LocationName?>&nbsp;</TD>
                   <TD><?=$Discript?></TD>
                   <TD>&nbsp;<?=$PhoneNo?>&nbsp;&nbsp;</TD>

                   <?                   
                   if  ($Result=="Yes") // 확인 버튼을 눌렸을 때 - 정보를 갱신한다....
                   {
                       $sQuery = "Delete From wrk_smschk            ".
                                 " Where Open     = '".$FilmOpen."' ".
                                 "   And Film     = '".$FilmCode."' ".
                                 "   And Theather = '".$Theather."' " ;
                       mysql_query($sQuery,$connect) ;

                       $Var = $FilmOpen.$FilmCode.$Theather ;

                       if  ($$Var == 'Y')
                       {
                           $sQuery = "Insert Into wrk_smschk    ".
                                     "Values                    ".
                                     "(                         ".
                                     "  '".$FilmOpen."',        ".
                                     "  '".$FilmCode."',        ".
                                     "  '".$Theather."',        ".
                                     "  '".$PhoneNo."',         ".
                                     "  '".$$Var."'             ".
                                     ")                         " ;
                           mysql_query($sQuery,$connect) ;
                       }
                   }

                   $sQuery = "Select Chk      From wrk_smschk    ".
                             " Where Open     = '".$FilmOpen."'  ".
                             "   And Film     = '".$FilmCode."'  ".
                             "   And Theather = '".$Theather."'  " ;
                   $qry_Smschk = mysql_query($sQuery,$connect) ;
                   if  ($Smschk_data = mysql_fetch_array($qry_Smschk) )
                   {
                       if  ($Smschk_data["Chk"] == 'Y')
                       {
                           ?>
                           <TD><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>" checked value="Y"></TD>
                           <?
                       }
                       else
                       {
                           ?>
                           <TD><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>" value="Y"></TD>
                           <?
                       }
                   }
                   else
                   {
                       ?>
                       <TD><INPUT TYPE="checkbox" NAME="<?=$FilmOpen.$FilmCode.$Theather?>" value="Y"></TD>
                       <?
                   }
                   ?>
              </TR>
              <?
          }
          
          ********/
          ?>          
           
           
          </TABLE>
          <BR>
          <INPUT TYPE="submit" Value="확인">
          <BR><BR>
          </FORM>
        
     </center>
  </body>

<?
    }

    mysql_close($connect);
?>

</html>
