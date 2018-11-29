<?
    set_time_limit(0) ; // 긴 처리작업이 있을 때만 쓴다.....

    $NBSP="&nbsp;" ;
?>

<!-- 일일 보고서 -->
<html>

    <link rel=stylesheet href=./LinkStyle.css type=text/css>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

    <head>
    <title>상대영화/선재보고 보고서</title>

    <style type="text/css" media="all">
        table {
         width: 350px;
         margin-bottom: 20px;
        }
        th, td {
         padding: 3px;
        }
        caption {
         text-align: left;
         color: #F60;
         padding: 10px;
         font-size: 1.2em;
         font-weight: bold;
        }

        #beauty,
        #use-th,
        #use-th-beauty{
         border-collapse: collapse;
         border: 1px solid #CCC;
        }
        #beauty {
        }
        #use-th {
        }
        #use-th-beauty {
         font-size: 0.9em;
         border: none;
        }
        #use-th-beauty td {
         border: 1px solid #CCC;
        }
        #use-th-beauty th {
         background: #366B9F url(th_bg.png) top repeat-x ;
         color: #FFF;
         height: 22px;
         border: 1px solid #A1C3E6;
        }
        #use-th-beauty th.row {
         background-color: #BDDBF9;
         background-image: none;
         height: auto;
         color: #356EAB;
         font-weight: normal;
        }
        #use-th-beauty td {
         padding-left: 5px;
        }
    </style>

</head>



<body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 >


<?
        include "config.php";        // {[데이터 베이스]} : 환경설정

        $connect = dbconn() ;        // {[데이터 베이스]} : 연결

        mysql_select_db($cont_db) ;  // {[데이터 베이스]} : 디비선택

        ?>
        <center>


        <br>
        <br>


        <table style='table-layout:fixed'  border="1" id="use-th-beauty">
             <tr>
                <th id="first" width=70 align=center>극장</th>
                <th width=150 align=center>극장명</th>
                <?
                for ($i=0 ; $i<$dur_day ; $i++)
                {
                   ?>
                   <th width=50 align=center>&nbsp;<B><?=date("d",$timestamp2 + ($i * 86400)) ;?></B>&nbsp;</th>
                   <?
                }
                ?>
             </tr>
        <?
        $sQuery = "Select Singo.Theather,                          ".
                  "       Singo.Open,                              ".
                  "       Singo.Film,                              ".
                  "       Theather.Discript,                       ".
                  "       Theather.Location                        ".
                  "  From ".$SingoName."     As Singo,             ".
                  "       bas_theather       As Theather,          ".
                  "       bas_silmooja       As Silmooja,          ".
                  "       bas_location       As Location           ".
                  " Where Singo.Silmooja   = Silmooja.Code         ".
                  "   And Singo.Theather   = Theather.Theather     ".
                  "   And Singo.Location   = Location.Code         ".
                  $AddedCont."                                     ".
                  " Group By Singo.Theather,                       ".
                  "          Singo.Open,                           ".
                  "          Singo.Film                            " ;  //Eq($sQuery) ;
        $QrySingo = mysql_query($sQuery,$connect) or die(ex(__FILE__,__LINE__,$sQuery));
        while  ($ArrSingo = mysql_fetch_array($QrySingo))
        {
             $singoTheather    = $ArrSingo["Theather"] ;      // 신고상영관
             $singoOpen        = $ArrSingo["Open"] ;          // 신고영화
             $singoFilm        = $ArrSingo["Film"] ;          //

             $showroomDiscript = $ArrSingo["Discript"] ;      // 신고 상영관명
             $showroomLocation = $ArrSingo["Location"] ;      // 신고 상영관지역

             $locationName     = $ArrSingo["LocationName"] ;  // 신고 상영관지역명

             ?>
             <tr>
                <th class="row" align=center><?=$singoTheather?></th>
                <td><?=$showroomDiscript?></td>
                <?
                for ($i=0 ; $i<$dur_day ; $i++)
                {
                   $WorkDate = date("Ymd",$timestamp2 + ($i * 86400)) ;

                   $sQuery = "Select * From bas_theather_rate         ".
                             " Where Theather = '".$singoTheather."'  ".
                             "   And Open     = '".$singoOpen."'      ".
                             "   And Film     = '".$singoFilm."'      " ;
                   $QryTheatherRate = mysql_query($sQuery,$connect) or die(ex(__FILE__,__LINE__,$sQuery));
                   if  ($ArrTheatherRate = mysql_fetch_array($QryTheatherRate))
                   {
                       $TheatherRate = $ArrTheatherRate["Rate"] ;
                   }
                   else
                   {
                       $TheatherRate = 50 ;

                       $sQuery = "Insert Into bas_theather_rate       ".
                                 "Values                              ".
                                 "(                                   ".
                                 "      '".$singoTheather."',         ".
                                 "      '".$singoOpen."',             ".
                                 "      '".$singoFilm."',             ".
                                 "      '".$TheatherRate."'           ".
                                 ")                                   " ;
                       mysql_query($sQuery,$connect) or die(ex(__FILE__,__LINE__,$sQuery));
                   }

                   $sQuery = "Select * From wrk_theather_rate         ".
                             " Where WorkDate = '".$WorkDate."'       ".
                             "   And Theather = '".$singoTheather."'  ".
                             "   And Open     = '".$singoOpen."'      ".
                             "   And Film     = '".$singoFilm."'      " ;
                   $QryTheatherRate = mysql_query($sQuery,$connect) or die(ex(__FILE__,__LINE__,$sQuery));
                   if  ($ArrTheatherRate = mysql_fetch_array($QryTheatherRate))
                   {
                       $TheatherRate = $ArrTheatherRate["Rate"] ;
                   }
                   else
                   {
                       $sQuery = "Insert Into wrk_theather_rate       ".
                                 "Values                              ".
                                 "(                                   ".
                                 "      '".$WorkDate."',              ".
                                 "      '".$singoTheather."',         ".
                                 "      '".$singoOpen."',             ".
                                 "      '".$singoFilm."',             ".
                                 "      '".$TheatherRate."'           ".
                                 ")                                   " ;  // Eq($WorkDate) ;
                       mysql_query($sQuery,$connect) or die(ex(__FILE__,__LINE__,$sQuery));
                   }


                   ?>
                   <td width=60 align=center>&nbsp;<?=$TheatherRate?>&nbsp;</td>
                   <?
                }
                ?>
             </tr>
             <?
        }
        ?>
        </table>

        <br>
        <br>


        <br>
        <br>

        </center>



        <?
        mysql_close($connect);       // {[데이터 베이스]} : 단절
?>




</body>

</html>
