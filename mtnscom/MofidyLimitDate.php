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

        if  ($ModidfyLimitDate != "")
        {
            $sQuery = "Select * From MofidyLimitDate " ;
            $QryMofidyLimitDate = mysql_query($sQuery,$connect) ;
            if  (mysql_fetch_array($QryMofidyLimitDate))
            {   
                // �������� ����..
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
  <link rel=stylesheet href=./style.css type=text/css>
  <META HTTP-EQUIV=Content-Type CONTENT="text/html; charset=euc-kr">
  
  <head>
  <title>PHP�������� ����</title>
  </head>

  

  <body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
     <? echo "<b>".$UserName . "</b>���� ȯ���մϴ�!" ; ?>
     <a OnClick="location.href='../index_com.php?actcode=logout'"><b>[LogOut]</b></a>
     <a OnClick="location.href='<?= $BackAddr?>?logged_UserId=<?=$logged_UserId?>&logged_Name=<?=$logged_Name?>'"><b>[X]</b></a>

     <center>
        <b>PHP�������� ����</b>
        
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
          <INPUT TYPE="submit" Value="Ȯ��">
          </FORM>
        
     </center>
  </body>

  <?
  if  ($ModidfyLimitDate!='')
  {
  ?>
  <SCRIPT LANGUAGE="JavaScript">
  <!--
  alert("PHP�������ڰ� �����Ǿ����ϴ�") ;
  //-->
  </SCRIPT>
  <?
  }
  ?>

</html>

<?
    }

     mysql_close($connect);
?>