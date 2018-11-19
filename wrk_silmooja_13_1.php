<?
    session_start();

    //
    // 실무자 - 극장(상영관)지정
    //

    include "config.php";
    

    // 정상적으로 로그인 했는지 체크한다.
    if  ((!$UserId) || ($UserId==""))
    {
        echo "<script language='JavaScript'>window.location = 'index_com.php'</script>";
    }
    else
    {
        $Today = time()-(3600*7) ; // 새벽 7시 까지 오늘로 간주한다...  

        if (!$WorkDate)
        {
           $WorkDate = date("Ymd",$Today) ;
        }

        $connect=dbconn();

        mysql_select_db($cont_db) ;


        if  ($Append) // 여기서 추가한다.
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


        if  ($Delete) // 여기서 삭제한다.
        {
            $Items1 = explode(",", $Delete); // "," 로 파싱,,,
            
            //echo count($Items1) ; // 배열의 갯수
            
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


        if  ($Ouput) // 여기서 저장한다.
        {
            $sQuery = "Delete From wrk_sunjae2               ".
                      " Where Workdate  = '".$WorkDate."'    ".
                      "   And Theather  = '".$SunTheather."' ".
                      "   And Room      = '".$SunRoom."'     " ;
            mysql_query($sQuery,$connect) ;

            $Items1 = explode(",", $Ouput); // "," 로 파싱,,,
            
            //echo count($Items1) ; // 배열의 갯수
            
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


        // 해당실무자를 구하고 ..
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
     // 해당상영관의 정보를 구한다.
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
       
       //<!-- 확인 버튼 --> 
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
           sOuput = sOuput.substr(0,sOuput.length-1) ; // 마지막 한자는 잘라낸다.

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

       //<!-- 영화추가 버튼 -->
       function AppenTitle()
       {
                     
           /*
           var idx = write.Tiles.length;

           for (var i = 0; i < write.TileList.length; i++ )
           {
               // 영화리스트에서 선택된 영화를 찾았을때..
               if  (write.TileList(i).selected == true)
               {
                   if  ((idx==1) && (write.Tiles(0).value=="XXX"))
                   {                          
                       // "등록된영화가 없음" 만 있는경우
                       write.Tiles(0).text  = write.TileList(i).text ;
                       write.Tiles(0).value = write.TileList(i).value ;
                   }
                   else
                   {
                       bFind = false ;

                       // 중복여부를 체크한다.
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
               // 영화리스트에서 선택된 영화를 찾았을때..
               if  (write.TileList.options(i).selected == true)
               {                   
                   sAppend += write.TileList.options(i).value+"," ;
               }               
           }

           
           
           sAppend = sAppend.substr(0,sAppend.length-1) ; // 마지막 한자는 잘라낸다.          

           
           sUrl = "wrk_silmooja_13_1.php?"
                + "Append="+sAppend+"&"
                + "WorkDate=<?=$WorkDate?>&"
                + "SunTheather=<?=$SunTheather?>&"
                + "SunRoom=<?=$SunRoom?>&"
                + "FilmSupply=<?=$FilmSupply?>&"
                + "BackAddr=wrk_silmooja.php" ;           
           
           location.href = sUrl ;
       }

       //<!-- 선택된 영화삭제 버튼 -->
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

           if  (write.Tiles.length == 0) // 내용을 다지웠을때....
           {
               write.Tiles.length += 1 ;
               write.Tiles(0).text  = "등록된영화가 없음" ;
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

           sDelete = sDelete.substr(0,sDelete.length-1) ; // 마지막 한자는 잘라낸다.

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


<? echo "<b>".$logged_Name . "</b>님을 환영합니다!" ; ?>
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
                   
                   <!-- 영화추가 버튼 -->
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
                           <option value="XXX">등록된영화가 없음</option>
                         </select>
                         <?
                   }
                   else
                   {
                        ?>
                        <select size=15 name=Tiles id="Tiles" MULTIPLE style="width:200;">
                             <?               
                             mysql_data_seek($qry_sunjae2, 0) ; // 레코드 처음으로 이동...
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
                  <!-- 선택된 영화삭제 버튼 -->
                  <a id=AnkDelete onClick="DeleteTitle()">
                  <img src="bt_del.gif" width="33" height="16" border="0">
                  </a>
             </TD>
         </TR>         
         </TABLE>

         <BR>

         <!-- 확인 버튼 -->
         <input type=submit value="확인">

   </form>

</center>

</body>

</html>

<?
    mysql_close($connect);
    }
?>
