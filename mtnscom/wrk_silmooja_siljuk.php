<?  
    session_start();

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

        // 해당실무자를 구하고 ($silmoojaName) ..
        $sQuery = "Select * From bas_silmooja    ".
                  " Where UserId = '".$UserId."' " ;
        $QrySilmooja = mysql_query($sQuery,$connect) ;
        if  ($ArrSilmooja = mysql_fetch_array($QrySilmooja))
        {         
            $silmoojaCode = $ArrSilmooja["Code"] ;   // 실무자 코드
        }
?>

<html>

<link rel=stylesheet href=./style.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>실무자 실적현황</title>
</head>

<body BGCOLOR="#666699" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>

   
<? echo "<b>" . $UserName . "</b>님을 환영합니다!" ; ?>
<a href="<?=$BackAddr?>?WorkDate=<?=$WorkDate?>"><b>[X]</b></a>


<center>
            
   <br><b>실무자 실적현황(<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>)</b><br>
   <br>
   <?
   $sQuery = "Select * From bas_filmtitlesilmooja   ".
             " Where Silmooja = '".$silmoojaCode."' " ;
   $QryFilmsupplytitlesilmooja = mysql_query($sQuery,$connect) ;
   while ($ArrQryFilmsupplytitlesilmooja = mysql_fetch_array($QryFilmsupplytitlesilmooja))
   {
         $FilmOpen = $ArrQryFilmsupplytitlesilmooja["Open"] ;
         $FilmCode = $ArrQryFilmsupplytitlesilmooja["Film"] ;

         $sQuery = "Select * From bas_filmtitle   ".
                   " Where Open = '".$FilmOpen."' ".
                   "   And Code = '".$FilmCode."' " ;
         $QryFilmtitle = mysql_query($sQuery,$connect) ;
         if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
         {
             $FilmtitleName = $ArrFilmtitle["Name"] ;
         }
         echo $FilmtitleName."<br>" ;

echo "지금은 시험운영중입니다.";
         $sQuery = "Select Count(Distinct Code, Name) As CntName       ".
                   "  From wrk_silmoosiljuk                      ".
                   " Where WorkDate = '".$WorkDate."'            ".
                   "   And Open     = '".$FilmOpen."'            ".
                   "   And Film     = '".$FilmCode."'            " ;
         $QrySilmoosiljuk = mysql_query($sQuery,$connect) ;
         if  ($ArrSilmoosiljuk = mysql_fetch_array($QrySilmoosiljuk))
         {
         
         }
         ?>
         <table cellpadding=0 cellspacing=0 border=1>       
             <tr>
                <td align=center>순위</td>
                <td align=center>지역</td>
                <td align=center>극&nbsp;&nbsp;장</td>
                <td align=center>시간차</td>
             </tr>
           <?
           $i = 1 ;
           $sQuery = "Select Code, Name, Theather, Room,          ".
                     "       Round(Avg(GapMinute)) As AvgGapMinute ".
                     "  From wrk_silmoosiljuk                      ".
                     " Where WorkDate = '".$WorkDate."'            ".
                     "   And Open     = '".$FilmOpen."'            ".
                     "   And Film     = '".$FilmCode."'            ".
                     " Group By Code, Name, Theather, Room                         ".
                     " Order By AvgGapMinute                       " ;
           $QrySilmoosiljuk = mysql_query($sQuery,$connect) ;
           while ($ArrSilmoosiljuk = mysql_fetch_array($QrySilmoosiljuk))
           {
             $Theather = $ArrSilmoosiljuk["Theather"] ;
             $Room     = $ArrSilmoosiljuk["Room"] ;

             $sQuery = "Select Discript,Location      ".
                       " From bas_theather            ".
                       " Where Code = '".$Theather."' " ;
             $QryTheather = mysql_query($sQuery,$connect) ;
             if  ($ArrTheather = mysql_fetch_array($QryTheather))
             {
                 $TheatherName = $ArrTheather["Discript"] ;
                 $Location     = $ArrTheather["Location"] ;
             }

             
             $sQuery = "Select Name From bas_location ".
                       " Where Code = '".$Location."' " ;
             $QryLocation = mysql_query($sQuery,$connect) ;
             if  ($ArrLocation = mysql_fetch_array($QryLocation))
             {
                 $LocationName = $ArrLocation["Name"] ;
             }

           ?>      
             <tr>
                <td align=right><?=$i++?>&nbsp;</td>
                <td align=center><?=$LocationName?></td>
                <td><?=$TheatherName?></td>
                <td align=right><?=$ArrSilmoosiljuk["AvgGapMinute"]?>&nbsp;</td>
             </tr>
           <?
           } 
           ?>
         </table>
         <br>
         <br>
         <?
   }   
   ?>
   

</center>

</body>


</html>

<?
    mysql_close($connect);

    }
?>