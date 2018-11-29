<?

set_time_limit(0) ; // 긴 처리작업이 있을 때만 쓴다.....

include "config.php";

$connect=dbconn();

mysql_select_db($cont_db) ;

//$WorkDate = strtotime(substr($SingoDate,0,4)."-".substr($SingoDate,4,2)."-".substr($SingoDate,6,2)."") ;
$WorkDate = $SingoDate ;

$Today = time()-(3600*7) ; // 새벽 7시 까지 오늘로 간주한다...

$filename = date("Ymd",time()).".csv" ;




function megaUpload($_arrScores,$_connect)
{
    $Cnt = count($_arrScores);

    if  ($Cnt > 0)
    {
        echo "<xmp>";
        print_r($_arrScores);
        echo "</xmp>";

        for ($k = 0 ; $k < $Cnt; $k++)
        {
            array_pop($_arrScores); // 빼내고..
        }
    }
    //mysql_query($sQuery,$_connect) ;
}

?>
<html>
    <head>

        <title>엑셀스코어전송</title>

        <meta http-equiv="Content-Type" content="text/html; charset=euc-kr">

    </head>

    <style type="text/css">
        body {background-color: #ffffff; color: #000000;}
        body, td, th, h1, h2 {font-family: sans-serif;}

        table {border-collapse: collapse;}
        .center {text-align: center;}
        .center table { margin-left: auto; margin-right: auto; text-align: left;}
        .center th { text-align: center !important; }
        td, th { border: 1px solid #000000; font-size: 75%; vertical-align: baseline;}
    </style>

    <body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <?
        $downpath = "/usr/local/apache2/htdocs/excel/" ;
        $downfile = "excelupload.xls" ;

        if (file_exists($downpath . $downfile))
        {
           unlink($downpath . $downfile);
           //echo "중복파일(".$downfile.") 삭제 <br />" ;
        }

        if ($_FILES["file"]["error"] > 0)
        {
            echo "Error: " . $_FILES["file"]["error"] . "<br />";
        }
        else
        {
            $ext_name = substr( strrchr($_FILES["file"]["name"],"."),1);

            if ($ext_name != "xls")
            {
                echo ($_FILES["file"]["name"]."은 업로드되지 않는 파일종류 입니다.(반드시 '.xls'이어야 합니다)");
            }
            else
            {
                echo "업로드파일명: " . $_FILES["file"]["name"] . "<br />";
                //echo "타입: " . $_FILES["file"]["type"] . "<br />";
                //echo "크기: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
                //echo "임시저장파일: " . $_FILES["file"]["tmp_name"]. "<br />";;
                /*
                if (file_exists($downpath . $downfile))
                {
                   //echo $_FILES["file"]["name"] . " already exists. ";
                   unlink($downpath . $downfile);
                   echo "중복파일(".$downfile.") 삭제 <br />" ;
                }
                */
                move_uploaded_file($_FILES["file"]["tmp_name"],  $downpath . $downfile );
                //echo "저장파일: " . $downpath . $downfile." <br />" ;

            }
        }
    ?>

    <?
    require_once 'excel/Excel/reader.php';
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('euc-kr');
    $data->read( $downpath . $downfile );
    error_reporting(E_ALL ^ E_NOTICE);

    echo   "구분:". $gubun."<br>";

    if  ($gubun=="롯데씨네마")
    {
    ?>
        <table border="0" cellpadding="3" >
        <?
        $StartLine = 2 ; ///////////////////////////////////////////////////
        $arrScores = array();

        for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++)
        {
            $Result = "" ;

            ?>
            <tr>

            <?
            echo "<TD align='right'><B>".$i."</B></TD>";

            for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++)
            {


                $cellvalue = $data->sheets[0]['cells'][$i][$j] ;

                $tdcolor = "black" ;

                if  ($data->sheets[0]['cells'][$i][4] == "영화관별 소계")
                {
                    $tdcolor = "blue" ;
                    $StartLine = $i + 1 ;
                }
                if  ($data->sheets[0]['cells'][$i][5] == "상영관별 소계")
                {
                    $tdcolor = "blue" ;
                    $StartLine = $i + 1 ;
                }


                if  (($j==5) && ($cellvalue == "상영관별 소계"))
                {

                    $FilmName     = $data->sheets[0]['cells'][$i][2] ; // 영화명
                    $TheatherName = $data->sheets[0]['cells'][$i][3] ; // 상영관명

                    $sQuery = "Select * From xls_lotte_filmtitle  ".
                              " Where lotteName = '".$FilmName."' " ;
                    $QryXlsFilmtitle = mysql_query($sQuery,$connect) ;
                    if  ($ArrXlsFilmtitle = mysql_fetch_array($QryXlsFilmtitle))
                    {
                        $FilmName = $ArrXlsFilmtitle["mtnsName"] ; // 필름이름 변환

                        $sQuery = "Select * From bas_filmtitle   ".
                                  " Where Name = '".$FilmName."' " ;
                        $QryFilmtitle = mysql_query($sQuery,$connect) ;
                        if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
                        {
                            $Open = $ArrFilmtitle["Open"] ;
                            $Film = $ArrFilmtitle["Code"] ;

                            $sSingoName = get_singotable($Open,$Film,$connect) ;  // 신고 테이블 이름..
                            $sAccName   = get_acctable($Open,$Film,$connect) ;    // accumulate 이름..
                            $sDgrName   = get_degree($Open,$Film,$connect) ;
                            $sDgrpName  = get_degreepriv($Open,$Film,$connect) ;
                            $sShowroomorder = get_showroomorder($Open,$Film,$connect) ;


                            $sQuery = "Select * From xls_lotte_theather       ".
                                      " Where lotteName = '".$TheatherName."' " ;
                            $QryXlsTheather = mysql_query($sQuery,$connect) ;
                            if  ($ArrXlsTheather = mysql_fetch_array($QryXlsTheather))
                            {
                                $TheatherName = $ArrXlsTheather["mtnsName"] ; // 극장이름 변환

                                $sQuery = "Select Code                                                               ".
										  "      ,Location                                                           ".
										  "      ,IF( '".$SingoDate."' >=  '20160523', GikumRate,  1.03 ) GikumRate  ".
										  "  From bas_theather                                                       ".
                                          " Where Discript = '".$TheatherName."' " ;
                                $QryTheather = mysql_query($sQuery,$connect) ;
                                if  ($ArrTheather = mysql_fetch_array($QryTheather))
                                {
                                    $Theather  = $ArrTheather["Code"] ;
                                    $Location  = $ArrTheather["Location"] ;
									$GikumRate = $ArrTheather["GikumRate"] ;

                                    $tempRoom  = sprintf("%02d",$data->sheets[0]['cells'][$i][4]) ;  // 상영관

                                    if  ($tempRoom=="1관")  $Room2 =  "01" ;
                                    if  ($tempRoom=="2관")  $Room2 =  "02" ;
                                    if  ($tempRoom=="3관")  $Room2 =  "03" ;
                                    if  ($tempRoom=="4관")  $Room2 =  "04" ;
                                    if  ($tempRoom=="5관")  $Room2 =  "05" ;
                                    if  ($tempRoom=="6관")  $Room2 =  "06" ;
                                    if  ($tempRoom=="7관")  $Room2 =  "07" ;
                                    if  ($tempRoom=="8관")  $Room2 =  "08" ;
                                    if  ($tempRoom=="9관")  $Room2 =  "09" ;
                                    if  ($tempRoom=="10관")  $Room2 =  "10" ;
                                    if  ($tempRoom=="11관")  $Room2 =  "11" ;
                                    if  ($tempRoom=="12관")  $Room2 =  "12" ;

                                    $sQuery = "Delete From ".$sSingoName."        \n".
                                              "Where SingoDate='".$SingoDate."'   \n".
                                              "  And Silmooja='".$Silmooja."'     \n".
                                              "  And Location='".$Location."'     \n".
                                              "  And Theather='".$Theather."'     \n".
                                              "  And Room='".$Room2."'            \n".
                                              "  And Open='".$Open."'             \n".
                                              "  And Film='".$Film."'             \n" ;
//eq($sQuery);
//                                    mysql_query($sQuery,$connect) ;

                                    $sQuery = "Select * From ".$sShowroomorder."      ".
                                              " Where Theather   = '".$Theather."'    ".
                                              "   And Room       = '".$Room2."'       " ;
                                    $QryShowroomorder = mysql_query($sQuery,$connect) ;
                                    if  ($ArrShowroomorder = mysql_fetch_array($QryShowroomorder))
                                    {
                                        $RoomOrder = $ArrShowroomorder["Seq"] ;
                                    }
                                    else
                                    {
                                        $RoomOrder = -1 ;
                                    }
                                    /*
                                    echo "<xmp>";
                                    print_r($arrScores);
                                    echo "</xmp>";
                                    */
                                    // count($arrScores) ;
                                    foreach($arrScores as $current)
                                    {
                                        $SingoDate  = $current[0] ;
                                        $Degree     = sprintf("%02d",$current[1]) ;  // 상영관 ;
                                        $UnitPrice  = $current[2] ;
                                        $Score      = $current[3] ;
                                        $GikumAount = get_GikumAount2($UnitPrice,$GikumRate,$Score) ;


                                        $sQuery = "Insert Into ".$sSingoName."   \n".
                                                  "Values                        \n".
                                                  "(                             \n".
                                                  "  '".$SingoDate."100000',     \n".
                                                  "  '".$SingoDate."',           \n".
                                                  "  '".$Silmooja."',            \n".
                                                  "  '".$Location."',            \n".
                                                  "  '".$Theather."',            \n".
                                                  "  '".$Room2."',               \n".
                                                  "  '".$Open."',                \n".
                                                  "  '".$Film."',                \n".
                                                  "  '',          ".//////////// 9月5日 //////
                                                  "  '".$Degree."',              \n".
                                                  "  '".$UnitPrice."',           \n".
                                                  "  '".$Score."',               \n".
                                                  "  '".$UnitPrice * $Score."',  \n".
                                                  "  '".$GikumAount."',          \n".
                                                  "  '',                         \n".
                                                  "  '".$RoomOrder."'            \n".
                                                  ")                             \n" ;
//eq($sQuery);
//                                        mysql_query($sQuery,$connect) ;
                                    }
                                    $Cnt = count($arrScores);

                                    for ($k = 0 ; $k < $Cnt; $k++)
                                    {
                                        array_pop($arrScores); // 빼내고..
                                    }

                                    $Result .= "완료" ;
                                }
                                else
                                {
                                    $Result .= "해당하는 극장코드가 없읍니다. : (".$TheatherName.")<br>" ;
                                }
                            }
                            else
                            {
                                $Result .= "해당하는 극장코드가 없읍니다.(xls_lotte_theather 확인) : (".$TheatherName.")<br>" ;
                            }
                        }
                        else
                        {
                            $Result .= "해당하는 영화코드가 없읍니다. : (".$FilmName.")<br>" ;
                        }
                    }
                    else
                    {
                        $Result .= "해당하는 영화코드가 없읍니다.(xls_lotte_filmtitle 확인) : (".$FilmName."-???)<br>" ;
                    }

                }
                else
                {
                   if  (($j==6) && ($cellvalue == "상영회차별 소계"))
                   {
                       $tdcolor = "red" ;

                       $bResult = true ;
                       for ($k = $StartLine ; $k < $i; $k++)
                       {
                            $Room2 =  "__" ;
                            $tempRoom  = $data->sheets[0]['cells'][$k][4] ;  // 상영관

                            if  ($tempRoom=="1관")  $Room2 =  "01" ;
                            if  ($tempRoom=="2관")  $Room2 =  "02" ;
                            if  ($tempRoom=="3관")  $Room2 =  "03" ;
                            if  ($tempRoom=="4관")  $Room2 =  "04" ;
                            if  ($tempRoom=="5관")  $Room2 =  "05" ;
                            if  ($tempRoom=="6관")  $Room2 =  "06" ;
                            if  ($tempRoom=="7관")  $Room2 =  "07" ;
                            if  ($tempRoom=="8관")  $Room2 =  "08" ;
                            if  ($tempRoom=="9관")  $Room2 =  "09" ;
                            if  ($tempRoom=="10관")  $Room2 =  "10" ;
                            if  ($tempRoom=="11관")  $Room2 =  "11" ;
                            if  ($tempRoom=="12관")  $Room2 =  "12" ;


                            $Dgree2 =  "__" ;
                            $tempDgree = $data->sheets[0]['cells'][$k][5] ;   // 회차

                            if  ($tempDgree=="1회")  $Dgree2 =  "01" ;
                            if  ($tempDgree=="2회")  $Dgree2 =  "02" ;
                            if  ($tempDgree=="3회")  $Dgree2 =  "03" ;
                            if  ($tempDgree=="4회")  $Dgree2 =  "04" ;
                            if  ($tempDgree=="5회")  $Dgree2 =  "05" ;
                            if  ($tempDgree=="6회")  $Dgree2 =  "06" ;
                            if  ($tempDgree=="7회")  $Dgree2 =  "07" ;
                            if  ($tempDgree=="8회")  $Dgree2 =  "08" ;
                            if  ($tempDgree=="9회")  $Dgree2 =  "09" ;
                            if  ($tempDgree=="10회")  $Dgree2 =  "10" ;
                            if  ($tempDgree=="11회")  $Dgree2 =  "11" ;
                            if  ($tempDgree=="12회")  $Dgree2 =  "12" ;

                            $num4  = is_numeric($Room2) ;
                            $num5  = is_numeric($Dgree2) ;
                            $num6  = is_numeric($data->sheets[0]['cells'][$k][6]) ;
                            $num7  = is_numeric($data->sheets[0]['cells'][$k][7]) ;

                            if  ($data->sheets[0]['cells'][$k][4] != "영화관별 소계")
                            {
                                if  (($num4 == true) && ($num5 == true) && ($num6 == true) && ($num7 == true))
                                {
                                    $tempDgree = $data->sheets[0]['cells'][$k][5] ;   // 회차

                                    if  ($tempDgree=="1회")  $Dgree2 =  "01" ;
                                    if  ($tempDgree=="2회")  $Dgree2 =  "02" ;
                                    if  ($tempDgree=="3회")  $Dgree2 =  "03" ;
                                    if  ($tempDgree=="4회")  $Dgree2 =  "04" ;
                                    if  ($tempDgree=="5회")  $Dgree2 =  "05" ;
                                    if  ($tempDgree=="6회")  $Dgree2 =  "06" ;
                                    if  ($tempDgree=="7회")  $Dgree2 =  "07" ;
                                    if  ($tempDgree=="8회")  $Dgree2 =  "08" ;
                                    if  ($tempDgree=="9회")  $Dgree2 =  "09" ;
                                    if  ($tempDgree=="10회")  $Dgree2 =  "10" ;
                                    if  ($tempDgree=="11회")  $Dgree2 =  "11" ;
                                    if  ($tempDgree=="12회")  $Dgree2 =  "12" ;

                                    $arrItem = array( $data->sheets[0]['cells'][$k][1],   // 상영일자
                                                      $Dgree2,
                                                      $data->sheets[0]['cells'][$k][6],   // 단가
                                                      $data->sheets[0]['cells'][$k][7] ); // 스코어

                                    array_push($arrScores, $arrItem); // 추가하고
                                }
                                else
                                {
                                    $bResult = false ;
                                }
                            }
                       }

                       if  ($bResult == false)
                       {
                           $Result .= "오류<br>" ;
                       }
                       /*
                       eq($StartLine) ;
                       echo "<xmp>";
                       print_r($arrScores);
                       echo "</xmp>";
                       */

                       $StartLine = $i + 1 ;

                   }
                   else
                   {
                       if  (
                           ($data->sheets[0]['cells'][$i][4] != "영화관별 소계") &&
                           ($data->sheets[0]['cells'][$i][5] != "상영관별 소계") &&
                           ($data->sheets[0]['cells'][$i][6] != "상영회차별 소계")
                           )
                       {
                           if  (($j==6) && ($i>1))  $tdcolor = "Orange" ;
                           if  (($j==7) && ($i>1))  $tdcolor = "green" ;
                       }
                   }
                }

                if  (($j>=6) && ($j<=8))
                {
                     $align =  "Right" ;
                }
                else
                {
                     $align =  "Left" ;
                }

                echo "<TD align='$align'><font color=$tdcolor>".$cellvalue."</font></TD>";
            }

            if  (
                ($i >1) &&
                ($data->sheets[0]['cells'][$i][4] != "영화관별 소계") &&
                ($data->sheets[0]['cells'][$i][5] != "상영관별 소계") &&
                ($data->sheets[0]['cells'][$i][6] != "상영회차별 소계")
                )
            {
                $sErr = "";

                $Room2 =  "__" ;
                $tempRoom  = $data->sheets[0]['cells'][$i][4] ;  // 상영관

                if  ($tempRoom=="1관")  $Room2 =  "01" ;
                if  ($tempRoom=="2관")  $Room2 =  "02" ;
                if  ($tempRoom=="3관")  $Room2 =  "03" ;
                if  ($tempRoom=="4관")  $Room2 =  "04" ;
                if  ($tempRoom=="5관")  $Room2 =  "05" ;
                if  ($tempRoom=="6관")  $Room2 =  "06" ;
                if  ($tempRoom=="7관")  $Room2 =  "07" ;
                if  ($tempRoom=="8관")  $Room2 =  "08" ;
                if  ($tempRoom=="9관")  $Room2 =  "09" ;
                if  ($tempRoom=="10관")  $Room2 =  "10" ;
                if  ($tempRoom=="11관")  $Room2 =  "11" ;
                if  ($tempRoom=="12관")  $Room2 =  "12" ;


                $Dgree2 =  "__" ;
                $tempDgree = $data->sheets[0]['cells'][$i][5] ;   // 회차

                if  ($tempDgree=="1회")  $Dgree2 =  "01" ;
                if  ($tempDgree=="2회")  $Dgree2 =  "02" ;
                if  ($tempDgree=="3회")  $Dgree2 =  "03" ;
                if  ($tempDgree=="4회")  $Dgree2 =  "04" ;
                if  ($tempDgree=="5회")  $Dgree2 =  "05" ;
                if  ($tempDgree=="6회")  $Dgree2 =  "06" ;
                if  ($tempDgree=="7회")  $Dgree2 =  "07" ;
                if  ($tempDgree=="8회")  $Dgree2 =  "08" ;
                if  ($tempDgree=="9회")  $Dgree2 =  "09" ;
                if  ($tempDgree=="10회")  $Dgree2 =  "10" ;
                if  ($tempDgree=="11회")  $Dgree2 =  "11" ;
                if  ($tempDgree=="12회")  $Dgree2 =  "12" ;



                $num4  = is_numeric($Room2) ;
                $num5  = is_numeric($Dgree2) ;
                $num6  = is_numeric($data->sheets[0]['cells'][$i][6]) ;
                $num7  = is_numeric($data->sheets[0]['cells'][$i][7]) ;


                if  ($num4==false) { $sErr .= "관명이 숫자가 아님, " ; }
                if  ($num5==false) { $sErr .= "상영차가 숫자가 아님, " ; }
                if  ($num6==false) { $sErr .= "발권금액이 숫자가 아님, " ; }
                if  ($num7==false) { $sErr .= "매수가 숫자가 아님, ";  }

                if  (($num4 == true) && ($num5 == true) && ($num6 == true) && ($num7 == true))
                {}
                else
                {
                    $Result .= "오류(".$sErr.")<br>" ;
                }
            }
            ?>
            <td>
            <?=$Result?>
            </td>

            </tr>
            <?
        }
        ?>
        </table>
    <?
    }
    ?>

    <?
    if  ($gubun=="메가박스")
    {
        $xlsFilename = $_FILES["file"]["name"] ;

        $arrScores = array();

        $nameTheater = "" ;
        $nameFile = "" ;
    ?>
        <TABLE border="0" cellpadding="3" >
        <?
         for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++)
         {
             if ($i>1)
             {
                  if  (($data->sheets[0]['cells'][$i][1] <> "") && ($data->sheets[0]['cells'][$i][2] <> ""))
                  {
                      $nameTheater = $data->sheets[0]['cells'][$i][1] ;
                      $nameFile    = $data->sheets[0]['cells'][$i][2] ;

                      megaUpload($arrScores,$connect) ;
                  }

                  if  ($data->sheets[0]['cells'][$i][3] <> "")
                  {
                      $tempRoom  = $data->sheets[0]['cells'][$i][3] ;  // 상영관

                      if  ($tempRoom=="1관")  $Room2 =  "01" ;
                      if  ($tempRoom=="2관")  $Room2 =  "02" ;
                      if  ($tempRoom=="3관")  $Room2 =  "03" ;
                      if  ($tempRoom=="4관")  $Room2 =  "04" ;
                      if  ($tempRoom=="5관")  $Room2 =  "05" ;
                      if  ($tempRoom=="6관")  $Room2 =  "06" ;
                      if  ($tempRoom=="7관")  $Room2 =  "07" ;
                      if  ($tempRoom=="8관")  $Room2 =  "08" ;
                      if  ($tempRoom=="9관")  $Room2 =  "09" ;
                      if  ($tempRoom=="10관")  $Room2 =  "10" ;
                      if  ($tempRoom=="11관")  $Room2 =  "11" ;
                      if  ($tempRoom=="12관")  $Room2 =  "12" ;
                  }
                  if  ( $data->sheets[0]['cells'][$i][4]<>"계")
                  {
                      $arrItem = array( $nameTheater,                       // 극장
                                        $Room2,                             // 관
                                        $data->sheets[0]['cells'][$i][4],   // 스코어
                                        $data->sheets[0]['cells'][$i][5] ); // 단가
                      array_push($arrScores, $arrItem); // 추가하고
                  }
             }
             ?>
             <TR>
             <?
             if  ($data->sheets[0]['numCols'] >= 5)
             {


                 if  ( $data->sheets[0]['cells'][$i][4]<>"계")
                 {
                      $tdcolor = "blue" ;
                      $StartLine = $i + 1 ;
                 }

                 for ($j = 1; $j <= 5; $j++)
                 {
                     $tdcolor = "black" ;

                     $cellvalue = $data->sheets[0]['cells'][$i][$j] ;

                     if($i>1)
                     {
                         if  (($j==3) && ($cellvalue<>"")) $tdcolor = "blue" ;
                         if  (($j==4) && ($cellvalue<>"계")) $tdcolor = "red" ;
                         if  (($j==5) && ($data->sheets[0]['cells'][$i][4]<>"계")) $tdcolor = "green" ;
                     }
                     echo "<TD align='$align'><font color=$tdcolor>".$cellvalue."</font></TD>";
                 }
             }
             ?>
             <TR>
             <?

        }

        megaUpload($arrScores,$connect) ;


        ?>
        </TABLE>
    <?
    }
    ?>



    </body>

</html>



<?
mysql_close($connect);
?>

