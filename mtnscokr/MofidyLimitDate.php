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

        mysql_select_db($cont_db) ; 

        if  ($ModidfyLimitDate != "")
        {
            $sQuery = "Select * From MofidyLimitDate " ;
            $QryMofidyLimitDate = mysql_query($sQuery,$connect) ;
            if  (mysql_fetch_array($QryMofidyLimitDate))
            {   
                // 수정일자 갱신..
                $sQuery = "Update MofidyLimitDate                 ".
                          "   Set Value = '".$ModidfyLimitDate."' " ;
                mysql_query($sQuery,$connect) ;   
            }
            else
            {
                $sQuery = "Insert Into MofidyLimitDate    ".
                          "Values                         ".
                          "(                              ".
                          "    '".$ModidfyLimitDate."'    ".
                          ")                              " ;
                mysql_query($sQuery,$connect) ;   
            }
        }
?>
<html>
  <link rel=stylesheet href=./LinkStyle.css type=text/css>
  <META HTTP-EQUIV=Content-Type CONTENT="text/html; charset=euc-kr">
  
  <head>
  <title>PHP수정일자 설정</title>
  </head>

  

  <body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 >

     <center>
        <b>PHP수정일자 설정</b>
        
          <FORM METHOD=POST ACTION="<?=$PHP_SELF?>?logged_UserId=<?=$logged_UserId?>&logged_Name=<?=$logged_Name?>&BackAddr=wrk_filmsupply.php">
          <?
          $sQuery = "Select * From MofidyLimitDate              " ;
          $qry_MdfLmtDat = mysql_query($sQuery,$connect) ;
          if  ($MdfLmtDat_data = mysql_fetch_array($qry_MdfLmtDat) )
          {
              $MdfLmtDat = $MdfLmtDat_data["Value"] ;
          }
          else
          {
              $MdfLmtDat = 0 ;
          }
          ?>
          <INPUT TYPE="text" NAME="ModidfyLimitDate" SIZE="3" width="3" Value="<?=$MdfLmtDat?>">
          <INPUT TYPE="submit" Value="확인">
          </FORM>
        
     </center>
  </body>

  <?
  if  ($ModidfyLimitDate!='')
  {
  ?>
  <SCRIPT LANGUAGE="JavaScript">
  <!--
  alert("PHP수정일자가 설정되었읍니다") ;
  //-->
  </SCRIPT>
  <?
  }
  ?>

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
