<?
    session_start();

    //
    // �ǹ��� - ����(�󿵰�)����
    //

    include "config.php";

    // ���������� �α��� �ߴ��� üũ�Ѵ�.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = 'index_com.php'</script>";
    }
    else
    {
        $Today = time()-(3600*7) ; // ���� 7�� ���� ���÷� �����Ѵ�...

        if (!$WorkDate)
        {
           $WorkDate = date("Ymd",$Today) ;
        }

        $connect=dbconn();

        mysql_select_db($cont_db) ;


        // �󿵰� ����Ʈ
        $sQuery = "Select * From bas_showroom            ".
                  " Where Theather = '".$ShowroomCode."' " ;
        $query_showroom = mysql_query($sQuery,$connect) ;

        if  ($Ouput)  // ���⼭ �����Ѵ�.
        {
            $sQuery = "Delete From wrk_sangdae                ".
                      " Where Workdate  = '".$WorkDate."'     ".
                      "   And Theather  = '".$ShowroomCode."' " ;
            mysql_query($sQuery,$connect) ;

            $Items1 = explode(",", $Ouput); // "," �� �Ľ�,,,

            //echo count($Items1) ; // �迭�� ����

            foreach ($Items1 as $Item1)
            {
               $Items2 = explode(";", $Item1); // ";" �� �Ľ�,,,

               $showroom_data = mysql_fetch_array($query_showroom) ;
               $showroom_Theather  = $showroom_data["Theather"] ;
               $showroom_Room      = $showroom_data["Room"] ;
               $showroom_Location  = $showroom_data["Location"] ;
               $showroom_Discript  = $showroom_data["Discript"] ;
               if  ($Items2[0]<>'')
               {
                   $sQuery = "Insert Into wrk_sangdae           ".
                             "Values                            ".
                             "(                                 ".
                             "      '".$WorkDate."',            ".
                             "      '".$showroom_Theather."',   ".
                             "      '".$showroom_Room."',       ".
                             "      '1',                        ".
                             "      '".$Items2[0]."',           ".
                             "      '".$Items2[1]."',           ".
                             "      '".$showroom_Location."',   ".
                             "      '".$showroom_Discript."'    ".
                             ")                                 " ;
                   mysql_query($sQuery,$connect) ;
               }
               if  ($Items2[2]<>'')
               {
                   $sQuery = "Insert Into wrk_sangdae           ".
                             "Values                            ".
                             "(                                 ".
                             "      '".$WorkDate."',            ".
                             "      '".$showroom_Theather."',   ".
                             "      '".$showroom_Room."',       ".
                             "      '2',                        ".
                             "      '".$Items2[2]."',           ".
                             "      '".$Items2[3]."',           ".
                             "      '".$showroom_Location."',   ".
                             "      '".$showroom_Discript."'    ".
                             ")                                 " ;
                   mysql_query($sQuery,$connect) ;
               }
            }
        }

        // �ش�ǹ��ڸ� ���ϰ� ..
        $sQuery = "Select * From bas_silmooja           ".
                  " Where UserId = '".$logged_UserId."' " ;
        $query_silmooja = mysql_query($sQuery,$connect) ;
        if  ($silmooja_data = mysql_fetch_array($query_silmooja))
        {
            $silmoojaCode = $silmooja_data["Code"] ;
            $silmoojaName = $silmooja_data["Name"] ;
        }
?>

<html>

<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>���ھ��Է�</title>
</head>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>


   <script>
           // <!-- ���� ��ư -->
           function check_submit()
           {
              var sTemp ;
              var sOuput ;

              sOuput = "" ;
              //write.Tiles_<?=$showroom_Room?>

              <?
              mysql_data_seek($query_showroom, 0) ; // ���ڵ� ó������ �̵�...
              while ($showroom_data = mysql_fetch_array($query_showroom))
              {
                   $showroom_Room      = $showroom_data["Room"] ;

              ?>
                   //alert(write.Tiles_<?=$showroom_Room?>1.value) ;
                   //alert(write.Tiles_<?=$showroom_Room?>2.value) ;

                   sOuput +=  write.Tiles_<?=$showroom_Room?>1.value + ';' ;
                   sOuput +=  write.score_<?=$showroom_Room?>1.value + ';' ;
                   sOuput +=  write.Tiles_<?=$showroom_Room?>2.value + ';' ;
                   sOuput +=  write.score_<?=$showroom_Room?>2.value + ',' ;
              <?
              }
              ?>
              //alert(sOuput.substr(0,sOuput.length-1)) ;
              sOuput = sOuput.substr(0,sOuput.length-1) ; // ������ ���ڴ� �߶󳽴�.

              write.action =  "<?=$PHP_SELF?>?ShowroomCode=<?=$ShowroomCode?>&Ouput="+sOuput+"&BackAddr=wrk_silmooja.php&WorkDate=<?=$WorkDate?>"

              return true;
           }

           //
           //   ���ڸ� �Է� �޵��� �����Ѵ�.
           //
           //
           //

           function score_check()
           {
              edit = write.score.value ;

              if ((edit !="") && (edit.search(/\D/) != -1))
              {
                  alert("���ڸ� �Է½ÿ�!") ;

                  write.score.value = "";

                  edit = edit.replace(/\D/g, "")

                  write.score.focus() ;
                  write.score.select();

                  return false ;
              }
              else
              {
                  return true ;
              }
           }
   </script>


<? echo "<b>".$UserName . "</b>���� ȯ���մϴ�!" ; ?>
<a href="../index_com.php?actcode=logout"><b>[LogOut]</b></a>
<a href="<?=$BackAddr?>?WorkDate=<?=$WorkDate?>"><b>[X]</b></a>

<center>

   <br><b>*���ھ��Է�(<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>)*</b><br>


   <?
   $sQuery = "Select * From bas_theather        ".
             " Where Code = '".$ShowroomCode."' " ;
   $query_theather = mysql_query($sQuery,$connect) ;
   if  ($theather_data = mysql_fetch_array($query_theather))
   {
        $theather_code = $theather_data["Code"] ;

        $sQuery = "Select Max(Workdate) As MaxOfWorkdate    ".
                  "  From wrk_sangdae                       ".
                  " Where Theather  = '".$theather_code."'  " ;
        $Query_sangdae_max = mysql_query($sQuery,$connect) ;
        if  ($sangdae_max_data = mysql_fetch_array($Query_sangdae_max))
        {
            $AgoDate = $sangdae_max_data["MaxOfWorkdate"] ;
        }

        $sQuery = "Select Count(*) As CountOfSangdae        ".
                  "  From wrk_sangdae                       ".
                  " Where Workdate  = '".$WorkDate."'       ".
                  "   And Theather  = '".$theather_code."'  " ;
        $Query_sangdae_today = mysql_query($sQuery,$connect) ;
        if  ($sangdae_today_data = mysql_fetch_array($Query_sangdae_today))
        {
            if  ($sangdae_today_data["CountOfSangdae"] == 0)
            {
                $sQuery = "Select * From wrk_sangdae                ".
                          " Where Workdate  = '".$AgoDate."'        ".
                          "   And Theather  = '".$theather_code."'  " ;
                $Query_sangdae_agoday = mysql_query($sQuery,$connect) ;
                while ($sangdae_agoday_data = mysql_fetch_array($Query_sangdae_agoday))
                {
                     $sQuery = "Insert Into wrk_sangdae                        ".
                               "Values                                         ".
                               "(                                              ".
                               "   '".$WorkDate."',                            ".
                               "   '".$sangdae_agoday_data["Theather"]."',     ".
                               "   '".$sangdae_agoday_data["Room"]."',         ".
                               "   '".$sangdae_agoday_data["Gubun"]."',        ".
                               "   '".$sangdae_agoday_data["SangFilm"]."',     ".
                               "   '0',                                        ".
                               "   '".$sangdae_agoday_data["Location"]."',     ".
                               "   '".$sangdae_agoday_data["TheatherName"]."'  ".
                               ")                                              " ;
                     mysql_query($sQuery,$connect) ;
                }
            }
        }







   ?>
        <b><font color=white><?=$theather_data["Discript"]?></font></b><br>
   <?
   }
   ?>

   <form method=post name=write onsubmit="return check_submit()">


   <table cellpadding=0 cellspacing=0 border=1>
   <tr>
        <td align=center>�����</td>
        <td align=center>�¼���</td>
        <td align=center>��ȭ��</td>
        <td align=center>���ھ�</td>
   </tr>

   <?
   $rcCount = 0 ;

   mysql_data_seek($query_showroom, 0) ; // ���ڵ� ó������ �̵�...
   while  ($showroom_data = mysql_fetch_array($query_showroom))
   {
        $rcCount = $rcCount + 1 ;

        $showroom_Room      = $showroom_data["Room"] ;
        $showroom_Discript  = $showroom_data["Discript"] ;
        $showroom_Seat      = $showroom_data["Seat"] ;

        ?>

        <tr>
               <!-- �󿵰� -->
               <td align=left>
               <?=$showroom_Discript?>
               </td>

               <!-- �¼��� -->
               <td align=right>
               <?=$showroom_Seat?>
               &nbsp;
               </td>

               <?
               $sangdae_SangFilm1 = "" ;
               $sangdae_Score1    = null ;
               $sangdae_SangFilm2 = "" ;
               $sangdae_Score2    = null ;

               $sQuery = "Select * From wrk_sangdae                ".
                         " Where WorkDate  = '".$WorkDate."'       ".
                         "   And Theather  = '".$theather_code."'  ".
                         "   And Room      = '".$showroom_Room."'  " ;
               $query_sangdae = mysql_query($sQuery,$connect) ;
               while ($sangdae_data = mysql_fetch_array($query_sangdae))
               {
                     $sangdae_Gubun = $sangdae_data["Gubun"] ;

                     if  ($sangdae_Gubun=="1")
                     {
                         $sangdae_SangFilm1 = $sangdae_data["SangFilm"] ;
                         $sangdae_Score1    = $sangdae_data["Score"] ;
                     }
                     else
                     {
                         $sangdae_SangFilm2 = $sangdae_data["SangFilm"] ;
                         $sangdae_Score2    = $sangdae_data["Score"] ;
                     }
               }
               ?>


               <!-- ��ȭ�� -->
               <td align=right>
               <select name=Tiles_<?=$showroom_Room?>1>
                   <?
                   $sQuery = "Select * From bas_sangfilmtitle ".
                             " Where Gubun = 'S'              ".
                             " Order by code                  " ;
                   $queryTitle = mysql_query($sQuery,$connect) ;
                   while  ($Title_data = mysql_fetch_array($queryTitle))
                   {
                      if  ($sangdae_SangFilm1 == $Title_data["Code"])
                      {

                      ?>
                        <option selected value=<?=$Title_data["Code"]?>><?=$Title_data["Name"]?></option>
                      <?
                      }
                      else
                      {
                      ?>
                        <option value=<?=$Title_data["Code"]?>><?=$Title_data["Name"]?></option>
                      <?
                      }
                   }
                   ?>
               </select><br>
               <select name=Tiles_<?=$showroom_Room?>2>
                   <?
                   $sQuery = "Select * From bas_sangfilmtitle ".
                             " Where Gubun = 'S'              ".
                             " Order by code                  " ;
                   $queryTitle = mysql_query($sQuery,$connect) ;
                   while  ($Title_data = mysql_fetch_array($queryTitle))
                   {
                      if  ($sangdae_SangFilm2 == $Title_data["Code"])
                      {

                      ?>
                        <option selected value=<?=$Title_data["Code"]?>><?=$Title_data["Name"]?></option>
                      <?
                      }
                      else
                      {
                      ?>
                        <option value=<?=$Title_data["Code"]?>><?=$Title_data["Name"]?></option>
                      <?
                      }
                   }
                   ?>
               </select>
               </td>

               <!-- ���ھ� -->
               <td align=right>
               <input type=text value='<?=$sangdae_Score1?>' name=score_<?=$showroom_Room?>1 size=7 maxlength=6 style='text-align:right' class=input><br>
               <input type=text value='<?=$sangdae_Score2?>' name=score_<?=$showroom_Room?>2 size=7 maxlength=6 style='text-align:right' class=input>
               </td>
        </tr>
   <?
   }
   ?>
   </table>

   <BR>
   <!-- ���� ��ư -->
   <input type=submit value="����">

   </form>

</center>

</body>

</html>

<?
    if  ($Ouput) // ���⼭ �����Ѵ�.
    {
         echo "<script>alert('���ھ�� ���������� �Ϸ�Ǿ����ϴ�.');</script>" ;
         echo "<script>location.href='".$BackAddr."?WorkDate=".$WorkDate."'</script>" ;
    }


    mysql_close($connect);
    }
?>
