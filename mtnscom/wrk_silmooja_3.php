<?
    session_start();

    //
    // �ǹ��� - ��ȸ�� ����
    //
    include "config.php";

    // ���������� �α��� �ߴ��� üũ�Ѵ�.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = 'index_com.php'</script>";
    }
    else
    {
        if  ( ((!$FilmOpenCode) || ($FilmOpenCode=="")) || ((!$WorkDate) || ($WorkDate=="")) )
        {
            echo "<script language='JavaScript'>window.location = 'wrk_silmooja.php'</script>";
        }


        $ssn_FilmOpenCode = $FilmOpenCode ;

        $FilmOpen = substr($FilmOpenCode,0,6) ;
        $FilmCode = substr($FilmOpenCode,6,2) ;

        $Theather = substr($ShowroomCode,0,4) ;
        $Room     = substr($ShowroomCode,4,2) ;

        if (session_is_registered("ssn_FilmOpenCode"))
            session_unregister("ssn_FilmOpenCode");
        session_register("ssn_FilmOpenCode");

        $Today = time()-(3600*7) ; // ���� 7�� ���� ���÷� �����Ѵ�...  

        if (!$WorkDate)
        {
           $WorkDate = date("Ymd",$Today) ;
        }

        $connect=dbconn();

        mysql_select_db($cont_db) ;

        // �ش�ǹ��ڸ� ���ϰ� ..
        $sQuery = "Select * From bas_silmooja    ".
                  " Where UserId = '".$UserId."' " ;
        $qry_silmooja = mysql_query($sQuery,$connect) ;
        if  ($silmooja_data = mysql_fetch_array($qry_silmooja))
        {
            $silmoojaCode = $silmooja_data["Code"] ; // �ǹ����ڵ�
        }

        

        $sDgrName   = get_degree($FilmOpen,$FilmCode,$connect) ;  
        $sDgrpName  = get_degreepriv($FilmOpen,$FilmCode,$connect) ;  
        
        // �ǹ��ڰ� ������ ��ȭ����
        $sQuery = "Select * From bas_filmtitle   ".
                  " Where Open = '".$FilmOpen."' ".
                  "   And Code = '".$FilmCode."' " ;
        $query1 = mysql_query($sQuery,$connect) ;
        if  ($filmtitle_data = mysql_fetch_array($query1))
        {
            $filmtitleName       = $filmtitle_data["Name"] ;        // ��ȭ�̸�
            $filmtitleFilmSupply = $filmtitle_data["FilmSupply"] ;  // ��޻� �ڵ�
        }

        

        $sQuery = "Update bas_silmoojatheather             ".
                  "   Set Open     = '".$FilmOpen."',      ".
                  "       Film     = '".$FilmCode."',      ".
                  "       Title    = '".$filmtitleName."'  ".
                  " Where Silmooja = '".$silmoojaCode."'   ".
                  "   And Theather = '".$Theather."'       ".
                  "   And Room     = '".$Room."'           " ;
        mysql_query($sQuery,$connect) ;

        $sQuery = "Update bas_silmoojatheatherpriv         ".
                  "   Set Open     = '".$FilmOpen."',      ".
                  "       Film     = '".$FilmCode."',      ".
                  "       Title    = '".$filmtitleName."'  ".
                  " Where Silmooja = '".$silmoojaCode."'   ".
                  "   And WorkDate = '".$WorkDate."'       ".
                  "   And Theather = '".$Theather."'       ".
                  "   And Room     = '".$Room."'           " ;
        mysql_query($sQuery,$connect) ;

        $sQuery = "Update bas_showroom                     ".
                  "   Set FilmTitle = '".$FilmOpenCode."', ".
                  "       FilmName  = '".$filmtitleName."' ".
                  " Where Theather  = '".$Theather."'      ". 
                  "   And Room      = '".$Room."'          " ;
        mysql_query($sQuery,$connect) ;
?>

<html>

<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>��ȸ������</title>
</head>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

   <script>

         // Ȯ�ι�ư�� ��������..
         function check_submit()
         {
              isSelect = false ;
              <?
              for ($i=1 ; $i<=9 ; $i++)
              {
              ?>
                  if  (write.chk<?=$i?>Degree.checked==true)
                  {
                      isSelect = true ;
                  }
              <?
              }
              ?>
                   if  (write.chk99Degree.checked==true)
                   {
                       isSelect = true ;
                   }

                   if  (isSelect == false)
                   {
                       alert('��� �Ѱ��̻��� ���õǾ����� �մϴ�.');
                       return false;
                   }
              <?
              for ($i=1 ; $i<=9 ; $i++)
              {
              ?>
                  if  (write.chk<?=$i?>Degree.checked==true)
                  {
                      if (!write.StartTime<?=$i?>.value)
                      {
                        alert('<? printf("%dȸ",$i); ?> �ð��� �Է��Ͽ� �ֽʽÿ�');
                        write.StartTime<?=$i?>.focus();
                        return false;
                      }
                      if (write.StartTime<?=$i?>.value.length!=4)
                      {
                        alert('<? printf("%dȸ",$i); ?> �ð��� 4�ڸ� �����Դϴ� (���� �� ����)');
                        write.StartTime<?=$i?>.focus();
                        return false;
                      }
                  }
              <?
              }
              ?>
                   if  (write.chk99Degree.checked==true)
                   {
                       if  (!write.StartMidnight.value) 
                       {
                           alert('�ɾ� �ð��� �Է��Ͽ� �ֽʽÿ�');
                           write.StartMidnight.focus();
                           return false;
                       }
                       if  (write.StartMidnight.value.length!=4)
                       {
                           alert('�ɾ� �ð��� 4�ڸ� �����Դϴ� (���� �� ����)');
                           write.StartMidnight.focus();
                           return false;
                       }
                   }

              ActAddr = "wrk_silmooja_5.php?"
                      + "BackAddr=wrk_silmooja.php&"
                      + "WorkDate=<?=$WorkDate?>" ;

              write.action = ActAddr ;

              return true ;
         }

   </script>

<? echo "<b>".$UserName . "</b>���� ȯ���մϴ�!" ; ?>
<a href="../index_com.php?actcode=logout"><b>[LogOut]</b></a>
<a href="<?=$BackAddr?>?WorkDate=<?=$WorkDate?>"><b>[X]</b></a>

<center>

     <br>
     <b>*��ȸ������(<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>)*</b><br>

     <?
     // ����ȸ���� ���°��
     $sQuery = "Select * From ".$sDgrpName."          ".
               " Where Silmooja = '".$silmoojaCode."' ".
               "   And WorkDate = '".$WorkDate."'     ".
               "   And Open     = '".$FilmOpen."'     ".
               "   And Film     = '".$FilmCode."'     ".
               "   And Theather = '".$Theather."'     ".
               "   And Room     = '".$Room."'         " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     $degreepriv_data = mysql_fetch_array($qry_degreepriv) ;
     if  (!$degreepriv_data)
     {
         // �⺻ȸ������ ����ȸ���� ����.
         $sQuery = "Select * From ".$sDgrName."            ".
                   " Where Silmooja = '".$silmoojaCode."'  ".
                   "   And Open     = '".$FilmOpen."'      ".
                   "   And Film     = '".$FilmCode."'      ".
                   "   And Theather = '".$Theather."'      ".
                   "   And Room     = '".$Room."'          " ;
         $qry_temp = mysql_query($sQuery,$connect) ;
         if  ($temp_data  = mysql_fetch_array($qry_temp))
         {
             $sQuery = "Select * From ".$sDgrName."            ".
                       " Where Silmooja = '".$silmoojaCode."'  ".
                       "   And Open     = '".$FilmOpen."'      ".
                       "   And Film     = '".$FilmCode."'      ".
                       "   And Theather = '".$Theather."'      ".
                       "   And Room     = '".$Room."'          " ;
             $qry_degree = mysql_query($sQuery,$connect) ;
         }
         else
         {
             $sQuery = "Select * From ".$sDgrName."        ".
                       " Where Theather = '".$Theather."'  ".
                       "   And Room     = '".$Room."'      " ;
             $qry_degree = mysql_query($sQuery,$connect) ;
         }


         while ($degree_data = mysql_fetch_array($qry_degree))
         {
               $sQuery = "Insert Into ".$sDgrpName."           ".
                         "Values                               ".
                         "(                                    ".
                         "    '".$silmoojaCode."',             ".
                         "    '".$WorkDate."',                 ".
                         "    '".$FilmOpen."',                 ".
                         "    '".$FilmCode."',                 ".
                         "    '".$Theather."',                 ".
                         "    '".$Room."',                     ".
                         "    '".$degree_data["Degree"]."',    ".
                         "    '".$degree_data["Time"]."',      ".
                         "    '".$degree_data["Discript"]."'   ".
                         ")                                    " ;
               mysql_query($sQuery,$connect) ;
         
         }
     }


     $sQuery = "Select * From bas_showroom         ".
               " Where Theather = '".$Theather."'  ".
               "   And Room     = '".$Room."'      " ;
     $query1 = mysql_query($sQuery,$connect) ;


     $showroom_data = mysql_fetch_array($query1) ;

     if  ($showroom_data)
     {
         $showroom_Discript = $showroom_data["Discript"] ;
         $showroom_Location = $showroom_data["Location"] ;
         $showroom_Seat     = $showroom_data["Seat"] ;
     }
     else
     {
         $showroom_Discript = "" ;
         $showroom_Location = "" ;
         $showroom_Seat     = "" ;
     }

     // �󿵰��� ������ ������ ���Ѵ�. ($locationName)
     $sQuery = "Select * From bas_location             ".
               " Where Code = '".$showroom_Location."' " ;
     $query1 = mysql_query($sQuery,$connect) ;
     if  ($location_data = mysql_fetch_array($query1))
     {
         $locationName = $location_data["Name"] ;  // �󿵰� �������� ��
     }

     $sQuery = "Select * From bas_filmtitle   ".
               " Where Open = '".$FilmOpen."' ".
               "   And Code = '".$FilmCode."' " ;
     $query1 = mysql_query($sQuery,$connect) ;
     if  ($filmtitle_data = mysql_fetch_array($query1))
     {
         $filmtitle_Name = $filmtitle_data["Name"] ; // ��ȭ�̸�
     }
     else
     {
         $filmtitle_Name = "" ;
     }

     echo $showroom_Discript ."-". $locationName . "<br>" . $filmtitle_Name."<br>" ;

     
     ?>
     ��[2]��[2] ��) 1200

     <form method=post name=write onsubmit="return check_submit()">


     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '01'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk1Degree value=1 type="checkbox" checked><font color=white>1ȸ</font>
         <input type=text name=StartTime1  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk1Degree value=1 type="checkbox"><font color=white>1ȸ</font>
         <input type=text name=StartTime1  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>

     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '02'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk2Degree value=2 type="checkbox" checked><font color=white>2ȸ</font>
         <input type=text name=StartTime2  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk2Degree value=2 type="checkbox"><font color=white>2ȸ</font>
         <input type=text name=StartTime2  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>


     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '03'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk3Degree value=3 type="checkbox" checked><font color=white>3ȸ</font>
         <input type=text name=StartTime3  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk3Degree value=3 type="checkbox"><font color=white>3ȸ</font>
         <input type=text name=StartTime3  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>

     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '04'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk4Degree value=4 type="checkbox" checked><font color=white>4ȸ</font>
         <input type=text name=StartTime4  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk4Degree value=4 type="checkbox"><font color=white>4ȸ</font>
         <input type=text name=StartTime4  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>

     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '05'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk5Degree value=5 type="checkbox" checked><font color=white>5ȸ</font>
         <input type=text name=StartTime5  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk5Degree value=5 type="checkbox"><font color=white>5ȸ</font>
         <input type=text name=StartTime5  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>

     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '06'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk6Degree value=6 type="checkbox" checked><font color=white>6ȸ</font>
         <input type=text name=StartTime6  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk6Degree value=6 type="checkbox"><font color=white>6ȸ</font>
         <input type=text name=StartTime6  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>

     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '07'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk7Degree value=7 type="checkbox" checked><font color=white>7ȸ</font>
         <input type=text name=StartTime7  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk7Degree value=7 type="checkbox"><font color=white>7ȸ</font>
         <input type=text name=StartTime7  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>

     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '08'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk8Degree value=8 type="checkbox" checked><font color=white>8ȸ</font>
         <input type=text name=StartTime8  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk8Degree value=8 type="checkbox"><font color=white>8ȸ</font>
         <input type=text name=StartTime8  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>

     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '09'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk9Degree value=9 type="checkbox" checked><font color=white>9ȸ</font>
         <input type=text name=StartTime9  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk9Degree value=9 type="checkbox"><font color=white>9ȸ</font>
         <input type=text name=StartTime9  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>

     <?
     /******
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '10'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk10Degree value=10 type="checkbox" checked><font color=white>10ȸ</font>
         <input type=text name=StartTime10  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?
     }
     else
     {
         ?>
         <input name=chk10Degree value=10 type="checkbox"><font color=white>10ȸ</font>
         <input type=text name=StartTime10  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ?>

     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '11'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk11Degree value=11 type="checkbox" checked><font color=white>11ȸ</font>
         <input type=text name=StartTime11  size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?                                                                     
     }
     else
     {
         ?>
         <input name=chk11Degree value=11 type="checkbox"><font color=white>11ȸ</font>
         <input type=text name=StartTime11  size=4 maxlength=4 class=input><BR>
         <?
     } 
     ********/
     ?>

     <?
     $sQuery = "Select * From ".$sDgrpName."            ".
               " Where Silmooja = '".$silmoojaCode."'   ".
               "   And WorkDate = '".$WorkDate."'       ".
               "   And Open     = '".$FilmOpen."'       ".
               "   And Film     = '".$FilmCode."'       ".
               "   And Theather = '".$Theather."'       ".
               "   And Room     = '".$Room."'           ".
               "   And Degree   = '99'                  " ;
     $qry_degreepriv = mysql_query($sQuery,$connect) ;
     if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
     {
         ?>
         <input name=chk99Degree value=99 type="checkbox" checked><font color=white>�ɾ�</font>
         <input type=text name=StartMidnight size=4 maxlength=4 class=input value=<?=$degreepriv_data["Time"]?>><BR>
         <?                    
     }
     else
     {
         ?>
         <input name=chk99Degree value=99 type="checkbox"><font color=white>�ɾ�</font>
         <input type=text name=StartMidnight size=4 maxlength=4 class=input><BR>
         <?                    
     } 
     ?>
     
     <font color=white>�¼���(<?=$showroom_Seat?>��)����:</font><input type=text name=Seat value='<?=$showroom_Seat?>' size=3 maxlength=3 class=input>

     <input type="hidden" name="OrgSeat" value="<?=$showroom_Seat?>">

     <input type=submit value="Ȯ��">

     </form>
</center>

</body>

</html>

<?  
        mysql_close($connect);
    }
?>