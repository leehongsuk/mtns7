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
        $sQuery = "Select * From bas_showroom           ".
                  " Where Theather = '".$SunTheather."' " ;
        $query_showroom = mysql_query($sQuery,$connect) ;
        $count_showroom_row = mysql_affected_rows(); 

        if  ($Ouput) // ���⼭ �����Ѵ�.
        {
            $sQuery = "Delete From wrk_sunjae1               ".
                      " Where Theather  = '".$SunTheather."' " ;
            mysql_query($sQuery,$connect) ;

            $Items2 = explode(",", $Ouput); // "," �� �Ľ�,,,

            $showroom_data = mysql_fetch_array($query_showroom) ;
            $showroom_Theather  = $showroom_data["Theather"] ;
            $showroom_Room      = $showroom_data["Room"] ;
            $showroom_Location  = $showroom_data["Location"] ;

            $sQuery = "Select * From bas_theather             ".
                      " Where Code = '".$showroom_Theather."' " ;
            $query_theather = mysql_query($sQuery,$connect) ;
            if  ($theather_data = mysql_fetch_array($query_theather)) 
            {
                $theatherName = $theather_data["Discript"] ;
            }
            else
            {
                $theatherName = "" ;
            }

            $sQuery = "Insert Into wrk_sunjae1           ".
                      "Values                            ".
                      "(                                 ".
                      "      '".$WorkDate."',            ".
                      "      '".$showroom_Theather."',   ".
                      "      '".$Items2[0]."',           ".
                      "      '".$Items2[1]."',           ".
                      "      '".$Items2[2]."',           ".
                      "      '".$Items2[3]."',           ".
                      "      '".$showroom_Location."',   ".
                      "      '".$theatherName."',        ".
                      "      '".$Bigo."',                ".
                      "      '".$FilmSupply."'           ".
                      ")                                 " ;
            mysql_query($sQuery,$connect) ;
        }


        // �ش�ǹ��ڸ� ���ϰ� ..
        $sQuery = "Select * From bas_silmooja           ".
                  " Where UserId = '".$logged_UserId."' " ;
        $query_silmooja = mysql_query($sQuery,$connect) ;
        if  ($silmooja_data = mysql_fetch_array($query_silmooja))
        {
            $silmoojaCode       = $silmooja_data["Code"] ;
            $silmoojaName       = $silmooja_data["Name"] ;
            $silmoojaFilmSupply = $silmooja_data["FilmSupply"] ;
        }        
?>

<html>

<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>������Ȳ����</title>
</head>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
  

   <script>
         //<!-- ���� ��ư --> 
         function check_submit()
         {
             sOuput = "" ;
             
             if  (write.poster.checked == true)
                 sOuput +=  "Y," ; else sOuput +=  "N," ;
             if  (write.paper.checked == true)
                 sOuput +=  "Y," ; else sOuput +=  "N," ;
             if  (write.banner.checked == true)
                 sOuput +=  "Y," ; else sOuput +=  "N," ;
             if  (write.signboard.checked == true)
                 sOuput +=  "Y," ; else sOuput +=  "N," ;

             sOuput = sOuput.substr(0,sOuput.length-1) ; // ������ ���ڴ� �߶󳽴�.

             
             sUrl = "<?=$PHP_SELF?>?"
                  + "Ouput="+sOuput+"&"
                  + "WorkDate=<?=$WorkDate?>&"
                  + "SunTheather=<?=$SunTheather?>&"
                  + "SunRoom=<?=$SunRoom?>&"
                  + "Bigo="+write.bigo.value+"&"
                  + "FilmSupply="+<?=$silmoojaFilmSupply?>+"&"
                  + "BackAddr=wrk_silmooja.php" ;

             write.action = sUrl ;

             return true;
         }

         // ���翵ȭ �Է��ϱ� ���� ȭ������ ����.
         function check_suntheether(sShowroom)
         {                                                                                                              
            sUrl = "wrk_silmooja_13_1.php?"
                 + "WorkDate=<?=$WorkDate?>&"
                 + "SunTheather=<?=$SunTheather?>&"
                 + "FilmSupply="+<?=$silmoojaFilmSupply?>+"&"
                 + "SunRoom="+sShowroom ;

            location.href = sUrl ;
         }
   </script>


<? echo "<b>".$UserName . "</b>���� ȯ���մϴ�!" ; ?>
<a href="index_com.php?actcode=logout"><b>[LogOut]</b></a>
<a href="<?=$BackAddr?>?WorkDate=<?=$WorkDate?>"><b>[X]</b></a>

<center>

   <br><b>*������Ȳ����(<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>)*</b><br>


   <?
   $sQuery = "Select * From bas_theather        ".
             " Where Code = '".$SangTheather."' " ;
   $query_theather = mysql_query($sQuery,$connect) ;
   if  ($theather_data = mysql_fetch_array($query_theather)) 
   {
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
        <td align=center>������</td>
        <td align=center>����</td>
        <td align=center>���</td>
        <td align=center>���ٵ�</td>
        <td align=center>���</td>
   </tr>
   
   <?
   $OnlyOne = true ;

   $rcCount = 0 ;

   mysql_data_seek($query_showroom, 0) ; // ���ڵ� ó������ �̵�...
   
   while  ($showroom_data = mysql_fetch_array($query_showroom))
   {
        $rcCount = $rcCount + 1 ;

        $showroom_Theather  = $showroom_data["Theather"] ;
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
               $sQuery = "Select * From wrk_sunjae1                   ".
                         " Where WorkDate  = '".$WorkDate."'          ".
                         "   And Theather  = '".$showroom_Theather."' " ;
               $query_sangdae0 = mysql_query($sQuery,$connect) ; 
               if   ($sangdae_data0 = mysql_fetch_array($query_sangdae0))
               {
                     $sangdae_Poster    = $sangdae_data0["Poster"] ;
                     $sangdae_Paper     = $sangdae_data0["Paper"] ;
                     $sangdae_Banner    = $sangdae_data0["Banner"] ;
                     $sangdae_SignBoard = $sangdae_data0["SignBoard"] ;
                     $sangdae_Bigo      = $sangdae_data0["Bigo"] ;
                     $sangdae_FilmSupply= $sangdae_data0["FilmSupply"] ;
               }
               else
               {
                     $sangdae_Poster    = "" ;
                     $sangdae_Paper     = "" ;
                     $sangdae_Banner    = "" ;
                     $sangdae_SignBoard = "" ;
                     $sangdae_Bigo      = "" ;
                     $sangdae_FilmSupply= "" ;
               }

               $sQuery = "Select Count(*) As CountOfSunJae2           ".
                         " From wrk_sunjae2                           ".
                         " Where WorkDate  = '".$WorkDate."'          ".
                         "   And Theather  = '".$showroom_Theather."' ".
                         "   And Room      = '".$showroom_Room."'     " ;
               $query_sangdae2 = mysql_query($sQuery,$connect) ; 
               if  ($sangdae_data2 = mysql_fetch_array($query_sangdae2))
               {
                   $sangdae_TheatherNum = $sangdae_data2["CountOfSunJae2"] ;
               }
               ?>


               <!-- ��ȭ�� -->
               <td id="filmsDiv<?=$showroom_Room?>" align=right>
               <a OnClick="check_suntheether('<?=$showroom_Room?>');">�����Է�(<?=$sangdae_TheatherNum?>)</a>
               </td>



               <?
               if   ($OnlyOne == true)
               {
                    $OnlyOne = false ;
               ?>
               
               
               <!-- ������ -->
               <td align=right rowspan=<?=$count_showroom_row?>>
               <?                     
               if  ($sangdae_Poster=='Y') 
               {
               ?>
               <input type="checkbox" value='<?=$sangdae_Poster?>' name=poster checked="checked">
               <?
               }
               else
               {
               ?>
               <input type="checkbox" value='<?=$sangdae_Poster?>' name=poster>
               <?
               } 
               ?>
               &nbsp;
               </td>

               <!--  ���� -->
               <td align=right rowspan=<?=$count_showroom_row?>>               
               <?                     
               if  ($sangdae_Paper=='Y') 
               {
               ?>
               <input type="checkbox" value='<?=$sangdae_Paper?>' name=paper checked="checked">
               <?
               }
               else
               {
               ?>
               <input type="checkbox" value='<?=$sangdae_Paper?>' name=paper>
               <?
               } 
               ?>
               &nbsp;
               </td>

               <!-- ��� -->
               <td align=right rowspan=<?=$count_showroom_row?>>               
               <?                     
               if  ($sangdae_Banner=='Y') 
               {
               ?>
               <input type="checkbox" value='<?=$sangdae_Banner?>' name=banner checked="checked">
               <?
               }
               else
               {
               ?>
               <input type="checkbox" value='<?=$sangdae_Banner?>' name=banner>
               <?
               } 
               ?>
               &nbsp;
               </td>

               <!-- ���ٵ� -->
               <td align=right rowspan=<?=$count_showroom_row?>>
               <?                     
               if  ($sangdae_SignBoard=='Y') 
               {
               ?>
               <input type="checkbox" value='<?=$sangdae_SignBoard?>' name=signboard checked="checked">
               <?
               }
               else
               {
               ?>
               <input type="checkbox" value='<?=$sangdae_SignBoard?>' name=signboard>
               <?
               } 
               ?>
               &nbsp;
               </td>

               <!-- ��� -->
               <td align=right rowspan=<?=$count_showroom_row?>>
               <input type=text value='<?=$sangdae_Bigo?>' name=bigo>
               &nbsp;
               </td>

               <?
               } 
               ?>
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
         echo "<script>alert('������Ȳ���� ���������� �Ϸ�Ǿ����ϴ�.');</script>" ;
         echo "<script>location.href='".$BackAddr."?WorkDate=".$WorkDate."'</script>" ;
    }          
    
    mysql_close($connect);
    }
?>