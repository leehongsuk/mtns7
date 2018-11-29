<?

set_time_limit(0) ; // 긴 처리작업이 있을 때만 쓴다.....

include "config.php";

$connect=dbconn();

mysql_select_db($cont_db) ;

//$WorkDate = strtotime(substr($SingoDate,0,4)."-".substr($SingoDate,4,2)."-".substr($SingoDate,6,2)."") ;
//$WorkDate = $SingoDate ;

$Today = time()-(3600*7) ; // 새벽 7시 까지 오늘로 간주한다...


// 관을 결정한다.
function ConvertRoom2($tempRoom)
{
    if  ($tempRoom=="1")  $Room2 =  "01" ;
    if  ($tempRoom=="2")  $Room2 =  "02" ;
    if  ($tempRoom=="3")  $Room2 =  "03" ;
    if  ($tempRoom=="4")  $Room2 =  "04" ;
    if  ($tempRoom=="5")  $Room2 =  "05" ;
    if  ($tempRoom=="6")  $Room2 =  "06" ;
    if  ($tempRoom=="7")  $Room2 =  "07" ;
    if  ($tempRoom=="8")  $Room2 =  "08" ;
    if  ($tempRoom=="9")  $Room2 =  "09" ;
    if  ($tempRoom=="10")  $Room2 =  "10" ;
    if  ($tempRoom=="11")  $Room2 =  "11" ;
    if  ($tempRoom=="12")  $Room2 =  "12" ;
    if  ($tempRoom=="13")  $Room2 =  "13" ;
    if  ($tempRoom=="14")  $Room2 =  "14" ;
    if  ($tempRoom=="15")  $Room2 =  "15" ;
    if  ($tempRoom=="16")  $Room2 =  "16" ;
    if  ($tempRoom=="17")  $Room2 =  "17" ;
    if  ($tempRoom=="18")  $Room2 =  "18" ;
    if  ($tempRoom=="19")  $Room2 =  "19" ;

    return $Room2 ;
}

function ConvertRoom02($tempRoom)
{
    if  ($tempRoom=="1")  $Room2 =  "01" ;
    if  ($tempRoom=="2")  $Room2 =  "02" ;
    if  ($tempRoom=="3")  $Room2 =  "03" ;
    if  ($tempRoom=="4")  $Room2 =  "04" ;
    if  ($tempRoom=="5")  $Room2 =  "05" ;
    if  ($tempRoom=="6")  $Room2 =  "06" ;
    if  ($tempRoom=="7")  $Room2 =  "07" ;
    if  ($tempRoom=="8")  $Room2 =  "08" ;
    if  ($tempRoom=="9")  $Room2 =  "09" ;

    return $Room2 ;
}


// 배열의 내용을 디비에 저장한고 갯수를 리턴한다.
function megaUpload($_Silmooja,$_SingoDate,$_arrScores,$_FilmType,$_connect)
{
    $Cnt = count($_arrScores);

    if  ($Cnt > 0)
    {
        /*
        echo "<xmp>";
        print_r($_arrScores);
        echo "</xmp>";
        */

        $Open = "" ;
        $Film = "" ;
        $codeTheater = "" ;

        foreach($_arrScores as $current)
        {
            $chgFilm = false ;

            if  (($Open != $current[0]) || ($Film != $current[1]))  // 새로운 영화.. 지금은 단지 1개
            {
                $Open = $current[0] ;
                $Film = $current[1] ;

                $sSingoName = get_singotable($Open,$Film,$_connect) ;  // 신고 테이블 이름..
                $sAccName   = get_acctable($Open,$Film,$_connect) ;    // accumulate 이름..
                $sDgrName   = get_degree($Open,$Film,$_connect) ;
                $sDgrpName  = get_degreepriv($Open,$Film,$_connect) ;
                $sShowroomorder = get_showroomorder($Open,$Film,$_connect) ;

                $chgFilm = true ;
            }

            $TheatherRoom = $current[2].$current[3] ;
            if  ($codeTheater != $TheatherRoom) // 새로운 관
            {
                $codeTheater = $TheatherRoom ;

                $sQuery = "Select Code                                                                 \n".
						  "      ,Location                                                             \n".
						  "      ,Discript                                                             \n".
						  "      ,IF( '".$_SingoDate."' >=  '20160523', GikumRate,  1.03 ) GikumRate   \n".
				          "  From bas_theather                                                         \n".
                          " Where Code = '".$current[2]."'                                             \n" ; //eq($sQuery);
                $QryTheather = mysql_query($sQuery,$_connect) ;
                if  ($ArrTheather = mysql_fetch_array($QryTheather))
                {
                    $Theather     = $ArrTheather["Code"] ;
                    $Location     = $ArrTheather["Location"] ;
                    $TheatherName = $ArrTheather["Discript"] ;
					$GikumRate    = $ArrTheather["GikumRate"] ;

                    $Room2 = $current[3] ;  // 관

                    $sQuery = "Delete From ".$sSingoName."        \n".
                              "Where SingoDate='".$_SingoDate."'  \n".
                              "  And Silmooja='".$_Silmooja."'    \n".
                              "  And Location='".$Location."'     \n".
                              "  And Theather='".$Theather."'     \n".
                              "  And Room='".$Room2."'            \n".
                              "  And Open='".$Open."'             \n".
                              "  And Film='".$Film."'             \n" ; //  eq($sQuery);
                    mysql_query($sQuery,$_connect) ; /////////////////

                    $sQuery = "Select * From ".$sShowroomorder."      ".
                              " Where Theather   = '".$Theather."'    ".
                              "   And Room       = '".$Room2."'       " ;
                    $QryShowroomorder = mysql_query($sQuery,$_connect) ;
                    if  ($ArrShowroomorder = mysql_fetch_array($QryShowroomorder))
                    {
                        $RoomOrder = $ArrShowroomorder["Seq"] ;
                    }
                    else
                    {
                        $RoomOrder = -1 ;
                    }

                    // 오늘회차 존재여부확인 ..
                    $sQuery = "Select * From ".$sDgrpName."           \n".
                              " Where Silmooja = '".$_Silmooja."'     \n".
                              "   And WorkDate = '".$_SingoDate."'    \n".
                              "   And Open     = '".$Open."'          \n".
                              "   And Film     = '".$Film."'          \n".
                              "   And Theather = '".$Theather."'      \n".
                              "   And Room     = '".$Room2."'         \n" ;  // eq($sQuery);
                    $qry_degreepriv = mysql_query($sQuery,$_connect) ;
                    $degreepriv_data  = mysql_fetch_array($qry_degreepriv) ;
                    if  (!$degreepriv_data) // 오늘 회차 정보가 없다면..
                    {
                        $sQuery = "Insert Into ".$sDgrpName."  \n".
                                  "Values                      \n".
                                  "(                           \n".
                                  "    '".$_Silmooja."',       \n".
                                  "    '".$_SingoDate."',      \n".
                                  "    '".$Open."',            \n".
                                  "    '".$Film."',            \n".
                                  "    '".$Theather."',        \n".
                                  "    '".$Room2."',           \n".
                                  "    '01',                   \n".
                                  "    '1000',                 \n".
                                  "    '".$TheatherName."'     \n".
                                  ")                           \n" ;  // eq($sQuery);
                        mysql_query($sQuery,$_connect) ;
                    }
                }
            }

            $Degree     = "01" ; //  회차는 무조건 01회차
            $UnitPrice  = $current[4] ;
            $Score      = $current[5] ;
            $GikumAount = get_GikumAount2($UnitPrice,$GikumRate,$Score) ;


            $sQuery = "Insert Into ".$sSingoName."   \n".
                      "Values                        \n".
                      "(                             \n".
                      "  '".$_SingoDate."100000',    \n".
                      "  '".$_SingoDate."',          \n".
                      "  '".$_Silmooja."',           \n".
                      "  '".$Location."',            \n".
                      "  '".$Theather."',            \n".
                      "  '".$Room2."',               \n".
                      "  '".$Open."',                \n".
                      "  '".$Film."',                \n".
                      "  '".$_FilmType."',           \n".//////////// 9月5日 //////
                      "  '".$Degree."',              \n".
                      "  '".$UnitPrice."',           \n".
                      "  '".$Score."',               \n".
                      "  '".$UnitPrice * $Score."',  \n".
                      "  '".$GikumAount."',          \n".
                      "  '',                         \n".
                      "  '".$RoomOrder."'            \n".
                      ")                             \n" ; //eq($sQuery);
          mysql_query($sQuery,$_connect) ; /////////////////
        }
    }

    return $Cnt ;
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

    if  ($gubun=="메가박스")
    {
        $xlsFilename = $_FILES["file"]["name"] ;

        $Item = explode(".", $xlsFilename); // "." 로 파싱,,,
        $SingoDate = $Item[0] ;

        $arrScores = array();

        $nameTheater = "" ;
        $nameFilm    = "" ;

        if  (strlen($SingoDate) != 8) // 길이조사
        {
            echo "파일명이 날짜형태 이어야 합니다 yyyymmdd.xls  예) 20101231.xls" ;
        }
        else
        {
            //
            // 디지털
            //
            if  ($digital=="yes")
            {
                // 해당 날짜의 모든 Megabox 일단 다지운다.
                 $sQuery = "Delete From wrk_digital_account     \n".
                           " Where DigDate  = '".$SingoDate."'  \n".
                           "   And Gubun    = 'Megabox'         \n" ; //eq( $sQuery) ;
                 mysql_query($sQuery,$connect) ;
                ?>
                <TABLE border="0" cellpadding="3" >
                    <?
                    $Result = "" ;
                    $bErr = false ;

                    for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++)
                    {
                        $Result = "" ;

                        $nameTheater = $data->sheets[0]['cells'][$i][1] ;
                        $nameFilm    = $data->sheets[0]['cells'][$i][2] ;
                        $nameRoom    = $data->sheets[0]['cells'][$i][3] ;
                        $nameScore   = $data->sheets[0]['cells'][$i][4] ;

                        if  ( $i > 1 ) // 2번째 줄 부터 스케닝 ..
                        {
                            if  ((trim($nameTheater) <> "") && (trim($nameFilm) <> ""))
                            {
                                $sQuery = "Select * From xls_mega_filmtitle   \n".
                                          " Where megaName = '".$nameFilm."'  \n" ;   //$Result = $sQuery;
                                $QryXlsFilmtitle = mysql_query($sQuery,$connect) ;
                                if  ($ArrXlsFilmtitle = mysql_fetch_array($QryXlsFilmtitle))
                                {
                                    $nameFilm = $ArrXlsFilmtitle["mtnsName"] ; // 필름이름 변환

                                    $sQuery = "Select * From bas_filmtitle    \n".
                                              " Where Name = '".$nameFilm."'  \n" ; //eq($sQuery);
                                    $QryFilmtitle = mysql_query($sQuery,$connect) ;
                                    if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
                                    {
                                        $Open = $ArrFilmtitle["Open"] ;
                                        $Film = $ArrFilmtitle["Code"] ;

                                        $sQuery = "Select * From xls_mega_theather        \n".
                                                  " Where megaName = '".$nameTheater."'   \n" ;  //$Result = $sQuery; //eq($sQuery);
                                        $QryXlsTheather = mysql_query($sQuery,$connect) ;
                                        if  ($ArrXlsTheather = mysql_fetch_array($QryXlsTheather))
                                        {
                                            $nameTheater = $ArrXlsTheather["mtnsName"] ; // 극장이름 변환

                                            $sQuery = "Select * From bas_theather           \n".
                                                      " Where Discript = '".$nameTheater."' \n" ;
                                            $QryTheather = mysql_query($sQuery,$connect) ;
                                            if  ($ArrTheather = mysql_fetch_array($QryTheather))
                                            {
                                                $Theater  = $ArrTheather["Code"] ;
                                                $Discript = $ArrTheather["Discript"] ;

                                                $Result = "완료"; $bErr = false ;
                                            }
                                            else
                                            {
                                                $Result = "극장명에 해당하는 극장을 찾을수 없읍니다." ; $bErr = true ;
                                            }
                                        }
                                        else
                                        {
                                            $Result .= "해당하는 극장코드가 없읍니다.(xls_mega_theather 확인) : (".$nameTheater.")<br>" ;
                                        }
                                    }
                                    else
                                    {
                                        $Result = "영화명에 해당하는 영화를 찾을수 없읍니다." ; $bErr = true ;
                                    }
                                }
                                else
                                {
                                    $Result .= "해당하는 영화코드가 없읍니다.(xls_mega_filmtitle 확인) : (".$nameFilm."-???)<br>" ;
                                }
                            }
                            if  (trim($nameRoom) <> "")
                            {
                                $Room2 = ConvertRoom02($nameRoom)  ;  // 상영관

                                $sQuery = "Insert Into wrk_digital_account \n".
                                          "Values(                         \n".
                                          "        '".$SingoDate."',       \n".
                                          "        '".$Open."',            \n".
                                          "        '".$Film."',            \n".
                                          "        '".$FilmType."',        \n".
                                          "        '".$Theater."',         \n".
                                          "        '".$Room2."',           \n".
                                          "        ".$nameScore.",         \n".
                                          "        'Megabox',              \n".
                                          "        '".$Discript."'         \n".
                                          "       )                        \n" ; //$Result = $sQuery ;
                                mysql_query($sQuery,$connect) ;
                            }
                        }
                        ?>
                        <tr>
                            <td><?=$nameTheater?></td>
                            <td><?=$nameFilm?></td>
                            <td><?=$nameRoom?></td>
                            <td><?=$nameScore?></td>
                            <td><?=$Result?></td>
                        </tr>
                        <?
                    }
                    ?>
                </TABLE>
                <?
            }
            //
            // 일반
            //
            else
            {
            ?>
                <TABLE border="0" cellpadding="3" >
                    <?
                    $bErr = false ;

                    for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++)
                    {
                        $Result = "" ;

                        if  ( $i > 1 ) // 2번째 라인부터 읽는다.
                        {
                            if  ((trim($data->sheets[0]['cells'][$i][1]) <> "") && (trim($data->sheets[0]['cells'][$i][2]) <> ""))
                            {
                                $nameTheater = $data->sheets[0]['cells'][$i][1] ; // mega쪽 극장명
                                $nameFilm    = $data->sheets[0]['cells'][$i][2] ; // mega쪽 필름명

                                $sQuery = "Select * From xls_mega_filmtitle   \n".
                                          " Where megaName = '".$nameFilm."'  \n" ;   //$Result = $sQuery;
                                $QryXlsFilmtitle = mysql_query($sQuery,$connect) ;
                                if  ($ArrXlsFilmtitle = mysql_fetch_array($QryXlsFilmtitle))
                                {
                                    $nameFilm = $ArrXlsFilmtitle["mtnsName"] ; // 변환된 필름명

                                    $sQuery = "Select * From bas_filmtitle    \n".
                                              " Where Name = '".$nameFilm."'  \n" ; //eq($sQuery);
                                    $QryFilmtitle = mysql_query($sQuery,$connect) ;
                                    if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
                                    {
                                        $Open = $ArrFilmtitle["Open"] ;  // 필름 코드
                                        $Film = $ArrFilmtitle["Code"] ;  //

                                        $sQuery = "Select * From xls_mega_theather        \n".
                                                  " Where megaName = '".$nameTheater."'   \n" ;  //$Result = $sQuery; //eq($sQuery);
                                        $QryXlsTheather = mysql_query($sQuery,$connect) ;
                                        if  ($ArrXlsTheather = mysql_fetch_array($QryXlsTheather))
                                        {
                                            $nameTheater = $ArrXlsTheather["mtnsName"] ; // 변환된 극장명

                                            $sQuery = "Select * From bas_theather           \n".
                                                      " Where Discript = '".$nameTheater."' \n" ;
                                            $QryTheather = mysql_query($sQuery,$connect) ;
                                            if  ($ArrTheather = mysql_fetch_array($QryTheather))
                                            {
                                                $codeTheater = $ArrTheather["Code"] ; // 극장코드

                                                if  ($data->sheets[0]['cells'][$i][3] <> "") // 상영관
                                                {
                                                    $Room2 = ConvertRoom2($data->sheets[0]['cells'][$i][3])  ;  // 상영관 코드

                                                    if  ((trim($data->sheets[0]['cells'][$i][4])!="") && (trim($data->sheets[0]['cells'][$i][5])!=""))
                                                    {
                                                        $arrItem = array( $Open,                              // 영화코드
                                                                          $Film,
                                                                          $codeTheater,                       // 극장코드
                                                                          $Room2,                             // 관
                                                                          $data->sheets[0]['cells'][$i][4],   // 스코어
                                                                          $data->sheets[0]['cells'][$i][5] ); // 단가
                                                        array_push($arrScores, $arrItem); // 추가하고

                                                        $Result = "완료"; $bErr = false ;
                                                    }
                                                    else
                                                    {
                                                        $Result = "요금 혹은 스코어가 없읍니다." ; $bErr = true ;
                                                    }
                                                }
                                                else
                                                {
                                                    $Result = "상영관이 없읍니다." ; $bErr = true ;
                                                }
                                            }
                                            else
                                            {
                                                $Result = "극장명에 해당하는 극장을 찾을수 없읍니다." ; $bErr = true ;
                                            }
                                        }
                                        else
                                        {
                                            $Result .= "해당하는 극장코드가 없읍니다.(xls_mega_theather 확인) : (".$nameTheater.")<br>" ; $bErr = true ;
                                        }
                                    }
                                    else
                                    {
                                        $Result = "영화명에 해당하는 영화를 찾을수 없읍니다." ; $bErr = true ;
                                    }
                                }
                                else
                                {
                                    $Result .= "해당하는 영화코드가 없읍니다.(xls_mega_filmtitle 확인) : (".$nameFilm."-???)<br>" ; $bErr = true ;
                                }
                            }
                            else
                            {
                                if  ( $data->sheets[0]['cells'][$i][4] == "소계" )
                                {
                                    $Cnt = megaUpload($Silmooja,$SingoDate,$arrScores,$FilmType,$connect) ;
                                }
                                else
                                {
                                    $Cnt = count($arrScores);
                                }

                                for ($k = 0 ; $k < $Cnt; $k++)
                                {
                                    array_pop($arrScores); // 빼내고..
                                }
                            }
                        }
                        ?>


                        <TR>
                        <?
                        if  ($data->sheets[0]['numCols'] >= 5)
                        {
                             if  ( $data->sheets[0]['cells'][$i][4]<>"소계")
                             {
                                  $tdcolor = "blue" ;
                                  $StartLine = $i + 1 ;
                             }
                             echo "<TD align='right'><B>".$i."</B></TD>";

                             for ($j = 1; $j <= 5; $j++)
                             {
                                 if  (($j>=4) && ($j<=5))
                                 {
                                      $align =  "Right" ;
                                 }
                                 else
                                 {
                                      $align =  "Left" ;
                                 }

                                 $tdcolor = "black" ;

                                 $cellvalue = $data->sheets[0]['cells'][$i][$j] ;

                                 if($i>1)
                                 {
                                     if  (($j==3) && ($cellvalue<>"")) $tdcolor = "blue" ;
                                     if  (($j==4) && ($cellvalue<>"소계")) $tdcolor = "red" ;
                                     if  (($j==5) && ($data->sheets[0]['cells'][$i][4]<>"소계")) $tdcolor = "green" ;
                                 }
                                 echo "<TD align='$align'><font color=$tdcolor>".$cellvalue."</font></TD>";
                             }
                        }
                        ?>
                        <td><?=$Result?></td>

                        <TR>
                        <?

                    }

                    $Cnt = megaUpload($Silmooja,$SingoDate,$arrScores,$FilmType,$connect) ;

                    for ($k = 0 ; $k < $Cnt; $k++)
                    {
                        array_pop($arrScores); // 빼내고..
                    }
                    ?>
                </TABLE>
                <?
            }
        }
    }
    ?>

    </body>

</html>


<?
mysql_close($connect);
?>

