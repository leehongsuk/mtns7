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


        if  ($Append) // ���⼭ �߰��Ѵ�.
        {
            $sQuery = "Select Count(*) As CountOfSunjae2     ".
                      "  From wrk_sunjae2                    ".
                      " Where Workdate  = '".$WorkDate."'    ".
                      "   And Theather  = '".$SunTheather."' ".
                      "   And Room      = '".$SunRoom."'     " ;
            $query_cntsunjae2 = mysql_query($sQuery,$connect) ;
            if  ($cntsunjae2_data = mysql_fetch_array($query_cntsunjae2))
            {
                $CountOfSunjae2 = $cntsunjae2_data["CountOfSunjae2"] ;
            }
            else
            {
                $CountOfSunjae2 = 0 ;
            }
            $CountOfSunjae2 ++ ;

            $sQuery = "Select * From wrk_sunjae2               ".
                      " Where Workdate    = '".$WorkDate."'    ".
                      "   And Theather    = '".$SunTheather."' ".             
                      "   And Room        = '".$SunRoom."'     ".
                      "   And SunFilmCode = '".$Append."'      " ;
            $query_sunjae2 = mysql_query($sQuery,$connect) ;
            if  (!$sunjae2_data = mysql_fetch_array($query_sunjae2))
            {
                 $sQuery = "Select * From bas_sunfilmtitle ".
                           " Where code = ".$Append."      " ;
                 $queryTitle = mysql_query($sQuery,$connect) ;
                 if  ($Title_data = mysql_fetch_array($queryTitle))
                 {
                     $Discript = $Title_data["Name"] ;
                 }
                 else
                 {
                     $Discript = "" ;
                 }   

                 $sQuery = "Insert Into wrk_sunjae2    ".
                           "Values                     ".
                           "(                          ".
                           "   '".$WorkDate."',        ".
                           "   '".$SunTheather."',     ".
                           "   '".$SunRoom."',         ".
                           "   '".$CountOfSunjae2."',  ".
                           "   '".$Append."',          ".
                           "   '".$Discript."',        ".
                           "   '".$FilmSupply."'       ".
                           ")                          " ;
                 mysql_query($sQuery,$connect) ;
            }
        }


        if  ($Delete) // ���⼭ �����Ѵ�.
        {
            $Items1 = explode(",", $Delete); // "," �� �Ľ�,,,
            
            //echo count($Items1) ; // �迭�� ����
            
            $i = 0 ;
            
            foreach ($Items1 as $Item1) 
            {
                 $sQuery = "Select * From wrk_sunjae2               ".
                           " Where Workdate    = '".$WorkDate."'    ".
                           "   And Theather    = '".$SunTheather."' ".             
                           "   And Room        = '".$SunRoom."'     ".
                           "   And SunFilmCode = '".$Item1."'       " ;
                 $query_sunjae2 = mysql_query($sQuery,$connect) ;
                 if  ($sunjae2_data = mysql_fetch_array($query_sunjae2))
                 {
                     $sunjae2Seq = $sunjae2_data["Seq"] ;

                     $sQuery = "Delete From wrk_sunjae2                 ".
                               " Where Workdate    = '".$WorkDate."'    ".
                               "   And Theather    = '".$SunTheather."' ".
                               "   And Room        = '".$SunRoom."'     ".
                               "   And SunFilmCode = '".$Item1."'       " ;
                     mysql_query($sQuery,$connect) ;

                     $sQuery = "Select * From wrk_sunjae2            ".
                               " Where Workdate = '".$WorkDate."'    ".
                               "   And Theather = '".$SunTheather."' ".             
                               "   And Room     = '".$SunRoom."'     ".
                               "   And Seq      > '".$sunjae2Seq."'  ".
                               " Order By Seq                        " ;
                     $query_sjThen = mysql_query($sQuery,$connect) ;
                     while ($sjThen_data = mysql_fetch_array($query_sjThen))
                     {
                          $Seq0 = $sunjae2Seq ;
                          $Seq1 = $sunjae2Seq + 1 ;

                          $sQuery = "Update wrk_sunjae2                   ".
                                    "   Set Seq = '".$Seq0."'             ".
                                    " Where Workdate = '".$WorkDate."'    ".
                                    "   And Theather = '".$SunTheather."' ".             
                                    "   And Room     = '".$SunRoom."'     ".
                                    "   And Seq      = '".$Seq1."'        " ;
                          mysql_query($sQuery,$connect) ;
                          $sunjae2Seq ++ ;
                     }
                 }
            }
        }


        if  ($Ouput) // ���⼭ �����Ѵ�.
        {
            $sQuery = "Delete From wrk_sunjae2               ".
                      " Where Workdate  = '".$WorkDate."'    ".
                      "   And Theather  = '".$SunTheather."' ".
                      "   And Room      = '".$SunRoom."'     " ;
            mysql_query($sQuery,$connect) ;

            $Items1 = explode(",", $Ouput); // "," �� �Ľ�,,,
            
            //echo count($Items1) ; // �迭�� ����
            
            $i = 0 ;
            
            foreach ($Items1 as $Item1) 
            {
               $i ++ ;
               
               $sQuery = "Select * From bas_sunfilmtitle  ".
                         " Where code = ".$Item1."        " ;
               $queryTitle = mysql_query($sQuery,$connect) ;
               if  ($Title_data = mysql_fetch_array($queryTitle))
               {
                   $Discript = $Title_data["Name"] ;
               }
               else
               {
                   $Discript = "" ;
               }   
               $sQuery = "Insert Into wrk_sunjae2    ".
                         "Values                     ".
                         "(                          ".
                         "      '".$WorkDate."',     ".
                         "      '".$SunTheather."',  ".
                         "      '".$SunRoom."',      ".
                         "      '".$i."',            ".
                         "      '".$Item1."',        ".
                         "      '".$Discript."',     ".
                         "      '".$FilmSupply."'    ".
                         ")                          " ;
               mysql_query($sQuery,$connect) ;
            }
            ?>
            <script>location.href='wrk_silmooja_13.php?WorkDate=<?=$WorkDate?>&SunTheather=<?=$SunTheather?>&BackAddr=wrk_silmooja.php'</script>
            <?
        }


        // �ش�ǹ��ڸ� ���ϰ� ..
        $sQuery = "Select * From bas_silmooja          ".
                  "Where UserId = '".$logged_UserId."' " ;
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
<?
     // �ش�󿵰��� ������ ���Ѵ�.
     $sQuery = "Select * From bas_showroom           ".
               " Where Theather = '".$SunTheather."' ".
               "   And Room     = '".$SunRoom."'     " ;
     $query_showroom = mysql_query($sQuery,$connect) ;
     if  ($showroom_data = mysql_fetch_array($query_showroom))
     {
         $showroom_Discript  = $showroom_data["Discript"] ;
     }
     else
     {
         $showroom_Discript  = "" ;
     }
?>
<title><?=$showroom_Discript?></title>
</head>



<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

  

   <script>
       
       //<!-- Ȯ�� ��ư --> 
       function check_submit()
       {
           /*
           sOuput = "" ;
           
           for (var i = 0; i < write.Tiles.length; i++ )
           {
               if  (write.Tiles(i).value != "XXX")
               {                   
                   sOuput += write.Tiles(i).value + ","
               }
           }
           sOuput = sOuput.substr(0,sOuput.length-1) ; // ������ ���ڴ� �߶󳽴�.

           sUrl = "<?=$PHP_SELF?>?"
                + "Ouput="+sOuput+"&"
                + "WorkDate=<?=$WorkDate?>&"
                + "SunTheather=<?=$SunTheather?>&"
                + "SunRoom=<?=$SunRoom?>&"
                + "BackAddr=wrk_silmooja.php" ;

           write.action = sUrl ;
           return true;
           */

           sUrl = "wrk_silmooja_13.php?"
                + "WorkDate=<?=$WorkDate?>&"
                + "SunTheather=<?=$SunTheather?>&"
                + "FilmSupply=<?=$FilmSupply?>&"
                + "BackAddr=wrk_silmooja.php" ;

           write.action = sUrl ;
           return true;
       }

       //<!-- ��ȭ�߰� ��ư -->
       function AppenTitle()
       {
                     
           /*
           var idx = write.Tiles.length;

           for (var i = 0; i < write.TileList.length; i++ )
           {
               // ��ȭ����Ʈ���� ���õ� ��ȭ�� ã������..
               if  (write.TileList(i).selected == true)
               {
                   if  ((idx==1) && (write.Tiles(0).value=="XXX"))
                   {                          
                       // "��ϵȿ�ȭ�� ����" �� �ִ°��
                       write.Tiles(0).text  = write.TileList(i).text ;
                       write.Tiles(0).value = write.TileList(i).value ;
                   }
                   else
                   {
                       bFind = false ;

                       // �ߺ����θ� üũ�Ѵ�.
                       for (var j = 0; j < write.Tiles.length; j++ )
                       {
                           if  (write.Tiles(j).value == write.TileList(i).value)
                           {
                               bFind = true ;                               
                           }
                       }

                       if  (bFind == false )
                       {                       
                           write.Tiles.length += 1 ;
                           write.Tiles(idx).text  = write.TileList(i).text ;
                           write.Tiles(idx).value = write.TileList(i).value ;
                           idx += 1;
                       }
                   }                   
               }
           }
           */

           
           sAppend = "" ;           
           
           
           for (var i = 0; i < write.TileList.length; i++ )
           {          
               // ��ȭ����Ʈ���� ���õ� ��ȭ�� ã������..
               if  (write.TileList.options(i).selected == true)
               {                   
                   sAppend += write.TileList.options(i).value+"," ;
               }               
           }

           
           
           sAppend = sAppend.substr(0,sAppend.length-1) ; // ������ ���ڴ� �߶󳽴�.          

           
           sUrl = "wrk_silmooja_13_1.php?"
                + "Append="+sAppend+"&"
                + "WorkDate=<?=$WorkDate?>&"
                + "SunTheather=<?=$SunTheather?>&"
                + "SunRoom=<?=$SunRoom?>&"
                + "FilmSupply=<?=$FilmSupply?>&"
                + "BackAddr=wrk_silmooja.php" ;           
           
           location.href = sUrl ;
       }

       //<!-- ���õ� ��ȭ���� ��ư -->
       function DeleteTitle() 
       {
           /*
           var idx = write.Tiles.length ;           
           
           for (var i = 0; i < write.Tiles.length; i++ )
           {
               if  (write.Tiles[i].selected == true)
               {                   
                   if  (write.Tiles(j).value != "XXX")
                   {                   
                       for (var j = i; j < write.Tiles.length-1; j++ )
                       {
                           //write.Tiles(j).selected = write.Tiles(j+1).selected ;
                           write.Tiles(j).text     = write.Tiles(j+1).text ;
                           write.Tiles(j).value    = write.Tiles(j+1).value ;
                       }
                       write.Tiles.length -= 1 ;

                       idx += 1;
                   }
               }
           }

           if  (write.Tiles.length == 0) // ������ ����������....
           {
               write.Tiles.length += 1 ;
               write.Tiles(0).text  = "��ϵȿ�ȭ�� ����" ;
               write.Tiles(0).value = "XXX" ;
           }
           */

           sDelete = "" ;

           for (var i = 0; i < write.Tiles.length; i++ )
           {
               if  (write.Tiles.options(i).selected == true)
               {                   
                   if  (write.Tiles.options(i).value != "XXX")
                   {                   
                       sDelete += write.Tiles.options(i).value+"," ;
                   }
               }
           }        

           sDelete = sDelete.substr(0,sDelete.length-1) ; // ������ ���ڴ� �߶󳽴�.

           if  (sDelete != "")
           {                  
               sUrl = "wrk_silmooja_13_1.php?"
                    + "Delete="+sDelete+"&"
                    + "WorkDate=<?=$WorkDate?>&"
                    + "SunTheather=<?=$SunTheather?>&"
                    + "SunRoom=<?=$SunRoom?>&"
                    + "BackAddr=wrk_silmooja.php" ;           
               
               //AnkDelete.href = sUrl ;
               write.action = sUrl ;
               write.submit() ;
           }
           //return true ;
       }
   </script>


<? echo "<b>".$logged_Name . "</b>���� ȯ���մϴ�!" ; ?>
<a href="index.php?actcode=logout"><b>[LogOut]</b></a>
<a href="wrk_silmooja_13.php?WorkDate=<?=$WorkDate?>&SunTheather=<?=$SunTheather?>&BackAddr=wrk_silmooja.php"><b>[X]</b></a>

<center>

   <br><b>*<?=$showroom_Discript?>(<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>)*</b><br>


   <?
   $sQuery = "Select * From bas_theather         ".
             " Where Code = '".$SangTheather."'  " ;
   $query_theather = mysql_query($sQuery,$connect) ;
   if  ($theather_data = mysql_fetch_array($query_theather)) 
   {
         $theather_code = $theather_data["Code"] ;
   ?>
        <b><font color=white><?=$theather_data["Discript"]?></font></b><br>
   <?
   }
   ?>

   <form method=post name=write onsubmit="return check_submit()">

         <TABLE>
         <TR>
             <TD align=center>
                   <select name=TileList id="TileList" style="width:180;">
                   <?
                   $sQuery = "Select * From bas_sunfilmtitle   ".
                             " Where code <> ''                ".
                             " Order by code                   " ;
                   $queryTitle = mysql_query($sQuery,$connect) ;
                   while  ($Title_data = mysql_fetch_array($queryTitle))
                   {
                      if  ($Title_data["Code"] == "")
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
                   
                   <!-- ��ȭ�߰� ��ư -->
                   <a id=AnkAppend OnClick="AppenTitle()">
                   <!-- <a id=AnkAppend href="wrk_silmooja_13.php?WorkDate=<?=$WorkDate?>&SunTheather=<?=$SunTheather?>&BackAddr=wrk_silmooja.php"> -->
                   <img src="ico_homeup.gif" width="16" height="16" border="0" align="absmiddle">
                   </a>
             </TD>
         </TR>
         <TR>
             <TD align=center>
                   <?                
                   $nSeqSunFilm = 0 ;

                   $sQuery = "Select * From wrk_sunjae2            ".
                             " Where WorkDate = '".$WorkDate."'    ".
                             "   And Theather = '".$SunTheather."' ".
                             "   And Room     = '".$SunRoom."'     ".
                             " Order By Seq                        " ;
                   $qry_sunjae2 = mysql_query($sQuery,$connect) ;               
                   $sunjae2_date = mysql_fetch_array($qry_sunjae2) ;
                   if   (!$sunjae2_date) 
                   {
                         ?>
                         <select size=15 name=Tiles id="Tiles" MULTIPLE style="width:200;">
                           <option value="XXX">��ϵȿ�ȭ�� ����</option>
                         </select>
                         <?
                   }
                   else
                   {
                        ?>
                        <select size=15 name=Tiles id="Tiles" MULTIPLE style="width:200;">
                             <?               
                             mysql_data_seek($qry_sunjae2, 0) ; // ���ڵ� ó������ �̵�...
                             while ($sunjae2_date = mysql_fetch_array($qry_sunjae2))
                             {
                                  $nSeqSunFilm ++ ;

                                  $sunjae2SunFilmCode = $sunjae2_date["SunFilmCode"] ;

                                  $sQuery = "Select * From bas_sunfilmtitle                 ".
                                            " Where code = ".$sunjae2_date["SunFilmCode"]." " ;
                                  $queryTitle = mysql_query($sQuery,$connect) ;
                                  if  ($Title_data = mysql_fetch_array($queryTitle))
                                  {
                                       ?><option value=<?=$Title_data["Code"]?>><?=$Title_data["Name"]?></option><?
                                  }
                             }                                    
                             ?>
                        </Select>
                        <?
                   }
                   ?>
             </TD>
         </TR>
         <TR>
             <TD align=right>
                  <!-- ���õ� ��ȭ���� ��ư -->
                  <a id=AnkDelete onClick="DeleteTitle()">
                  <img src="bt_del.gif" width="33" height="16" border="0">
                  </a>
             </TD>
         </TR>         
         </TABLE>

         <BR>

         <!-- Ȯ�� ��ư -->
         <input type=submit value="Ȯ��">

   </form>

</center>

</body>

</html>

<?
    mysql_close($connect);
    }
?>
