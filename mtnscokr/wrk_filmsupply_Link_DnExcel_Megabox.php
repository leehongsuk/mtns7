<?

set_time_limit(0) ; // �� ó���۾��� ���� ���� ����.....

include "config.php";

$connect=dbconn();

mysql_select_db($cont_db) ;

//$WorkDate = strtotime(substr($SingoDate,0,4)."-".substr($SingoDate,4,2)."-".substr($SingoDate,6,2)."") ;
//$WorkDate = $SingoDate ;

$Today = time()-(3600*7) ; // ���� 7�� ���� ���÷� �����Ѵ�...


// ���� �����Ѵ�.
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


// �迭�� ������ ��� �����Ѱ� ������ �����Ѵ�.
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

            if  (($Open != $current[0]) || ($Film != $current[1]))  // ���ο� ��ȭ.. ������ ���� 1��
            {
                $Open = $current[0] ;
                $Film = $current[1] ;

                $sSingoName = get_singotable($Open,$Film,$_connect) ;  // �Ű� ���̺� �̸�..
                $sAccName   = get_acctable($Open,$Film,$_connect) ;    // accumulate �̸�..
                $sDgrName   = get_degree($Open,$Film,$_connect) ;
                $sDgrpName  = get_degreepriv($Open,$Film,$_connect) ;
                $sShowroomorder = get_showroomorder($Open,$Film,$_connect) ;

                $chgFilm = true ;
            }

            $TheatherRoom = $current[2].$current[3] ;
            if  ($codeTheater != $TheatherRoom) // ���ο� ��
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

                    $Room2 = $current[3] ;  // ��

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

                    // ����ȸ�� ���翩��Ȯ�� ..
                    $sQuery = "Select * From ".$sDgrpName."           \n".
                              " Where Silmooja = '".$_Silmooja."'     \n".
                              "   And WorkDate = '".$_SingoDate."'    \n".
                              "   And Open     = '".$Open."'          \n".
                              "   And Film     = '".$Film."'          \n".
                              "   And Theather = '".$Theather."'      \n".
                              "   And Room     = '".$Room2."'         \n" ;  // eq($sQuery);
                    $qry_degreepriv = mysql_query($sQuery,$_connect) ;
                    $degreepriv_data  = mysql_fetch_array($qry_degreepriv) ;
                    if  (!$degreepriv_data) // ���� ȸ�� ������ ���ٸ�..
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

            $Degree     = "01" ; //  ȸ���� ������ 01ȸ��
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
                      "  '".$_FilmType."',           \n".//////////// 9��5�� //////
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

        <title>�������ھ�����</title>

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
           //echo "�ߺ�����(".$downfile.") ���� <br />" ;
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
                echo ($_FILES["file"]["name"]."�� ���ε���� �ʴ� �������� �Դϴ�.(�ݵ�� '.xls'�̾�� �մϴ�)");
            }
            else
            {
                echo "���ε����ϸ�: " . $_FILES["file"]["name"] . "<br />";
                //echo "Ÿ��: " . $_FILES["file"]["type"] . "<br />";
                //echo "ũ��: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
                //echo "�ӽ���������: " . $_FILES["file"]["tmp_name"]. "<br />";;
                /*
                if (file_exists($downpath . $downfile))
                {
                   //echo $_FILES["file"]["name"] . " already exists. ";
                   unlink($downpath . $downfile);
                   echo "�ߺ�����(".$downfile.") ���� <br />" ;
                }
                */
                move_uploaded_file($_FILES["file"]["tmp_name"],  $downpath . $downfile );
                //echo "��������: " . $downpath . $downfile." <br />" ;

            }
        }
    ?>

    <?
    require_once 'excel/Excel/reader.php';
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('euc-kr');
    $data->read( $downpath . $downfile );
    error_reporting(E_ALL ^ E_NOTICE);

    echo   "����:". $gubun."<br>";

    if  ($gubun=="�ް��ڽ�")
    {
        $xlsFilename = $_FILES["file"]["name"] ;

        $Item = explode(".", $xlsFilename); // "." �� �Ľ�,,,
        $SingoDate = $Item[0] ;

        $arrScores = array();

        $nameTheater = "" ;
        $nameFilm    = "" ;

        if  (strlen($SingoDate) != 8) // ��������
        {
            echo "���ϸ��� ��¥���� �̾�� �մϴ� yyyymmdd.xls  ��) 20101231.xls" ;
        }
        else
        {
            //
            // ������
            //
            if  ($digital=="yes")
            {
                // �ش� ��¥�� ��� Megabox �ϴ� �������.
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

                        if  ( $i > 1 ) // 2��° �� ���� ���ɴ� ..
                        {
                            if  ((trim($nameTheater) <> "") && (trim($nameFilm) <> ""))
                            {
                                $sQuery = "Select * From xls_mega_filmtitle   \n".
                                          " Where megaName = '".$nameFilm."'  \n" ;   //$Result = $sQuery;
                                $QryXlsFilmtitle = mysql_query($sQuery,$connect) ;
                                if  ($ArrXlsFilmtitle = mysql_fetch_array($QryXlsFilmtitle))
                                {
                                    $nameFilm = $ArrXlsFilmtitle["mtnsName"] ; // �ʸ��̸� ��ȯ

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
                                            $nameTheater = $ArrXlsTheather["mtnsName"] ; // �����̸� ��ȯ

                                            $sQuery = "Select * From bas_theather           \n".
                                                      " Where Discript = '".$nameTheater."' \n" ;
                                            $QryTheather = mysql_query($sQuery,$connect) ;
                                            if  ($ArrTheather = mysql_fetch_array($QryTheather))
                                            {
                                                $Theater  = $ArrTheather["Code"] ;
                                                $Discript = $ArrTheather["Discript"] ;

                                                $Result = "�Ϸ�"; $bErr = false ;
                                            }
                                            else
                                            {
                                                $Result = "����� �ش��ϴ� ������ ã���� �����ϴ�." ; $bErr = true ;
                                            }
                                        }
                                        else
                                        {
                                            $Result .= "�ش��ϴ� �����ڵ尡 �����ϴ�.(xls_mega_theather Ȯ��) : (".$nameTheater.")<br>" ;
                                        }
                                    }
                                    else
                                    {
                                        $Result = "��ȭ�� �ش��ϴ� ��ȭ�� ã���� �����ϴ�." ; $bErr = true ;
                                    }
                                }
                                else
                                {
                                    $Result .= "�ش��ϴ� ��ȭ�ڵ尡 �����ϴ�.(xls_mega_filmtitle Ȯ��) : (".$nameFilm."-???)<br>" ;
                                }
                            }
                            if  (trim($nameRoom) <> "")
                            {
                                $Room2 = ConvertRoom02($nameRoom)  ;  // �󿵰�

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
            // �Ϲ�
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

                        if  ( $i > 1 ) // 2��° ���κ��� �д´�.
                        {
                            if  ((trim($data->sheets[0]['cells'][$i][1]) <> "") && (trim($data->sheets[0]['cells'][$i][2]) <> ""))
                            {
                                $nameTheater = $data->sheets[0]['cells'][$i][1] ; // mega�� �����
                                $nameFilm    = $data->sheets[0]['cells'][$i][2] ; // mega�� �ʸ���

                                $sQuery = "Select * From xls_mega_filmtitle   \n".
                                          " Where megaName = '".$nameFilm."'  \n" ;   //$Result = $sQuery;
                                $QryXlsFilmtitle = mysql_query($sQuery,$connect) ;
                                if  ($ArrXlsFilmtitle = mysql_fetch_array($QryXlsFilmtitle))
                                {
                                    $nameFilm = $ArrXlsFilmtitle["mtnsName"] ; // ��ȯ�� �ʸ���

                                    $sQuery = "Select * From bas_filmtitle    \n".
                                              " Where Name = '".$nameFilm."'  \n" ; //eq($sQuery);
                                    $QryFilmtitle = mysql_query($sQuery,$connect) ;
                                    if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
                                    {
                                        $Open = $ArrFilmtitle["Open"] ;  // �ʸ� �ڵ�
                                        $Film = $ArrFilmtitle["Code"] ;  //

                                        $sQuery = "Select * From xls_mega_theather        \n".
                                                  " Where megaName = '".$nameTheater."'   \n" ;  //$Result = $sQuery; //eq($sQuery);
                                        $QryXlsTheather = mysql_query($sQuery,$connect) ;
                                        if  ($ArrXlsTheather = mysql_fetch_array($QryXlsTheather))
                                        {
                                            $nameTheater = $ArrXlsTheather["mtnsName"] ; // ��ȯ�� �����

                                            $sQuery = "Select * From bas_theather           \n".
                                                      " Where Discript = '".$nameTheater."' \n" ;
                                            $QryTheather = mysql_query($sQuery,$connect) ;
                                            if  ($ArrTheather = mysql_fetch_array($QryTheather))
                                            {
                                                $codeTheater = $ArrTheather["Code"] ; // �����ڵ�

                                                if  ($data->sheets[0]['cells'][$i][3] <> "") // �󿵰�
                                                {
                                                    $Room2 = ConvertRoom2($data->sheets[0]['cells'][$i][3])  ;  // �󿵰� �ڵ�

                                                    if  ((trim($data->sheets[0]['cells'][$i][4])!="") && (trim($data->sheets[0]['cells'][$i][5])!=""))
                                                    {
                                                        $arrItem = array( $Open,                              // ��ȭ�ڵ�
                                                                          $Film,
                                                                          $codeTheater,                       // �����ڵ�
                                                                          $Room2,                             // ��
                                                                          $data->sheets[0]['cells'][$i][4],   // ���ھ�
                                                                          $data->sheets[0]['cells'][$i][5] ); // �ܰ�
                                                        array_push($arrScores, $arrItem); // �߰��ϰ�

                                                        $Result = "�Ϸ�"; $bErr = false ;
                                                    }
                                                    else
                                                    {
                                                        $Result = "��� Ȥ�� ���ھ �����ϴ�." ; $bErr = true ;
                                                    }
                                                }
                                                else
                                                {
                                                    $Result = "�󿵰��� �����ϴ�." ; $bErr = true ;
                                                }
                                            }
                                            else
                                            {
                                                $Result = "����� �ش��ϴ� ������ ã���� �����ϴ�." ; $bErr = true ;
                                            }
                                        }
                                        else
                                        {
                                            $Result .= "�ش��ϴ� �����ڵ尡 �����ϴ�.(xls_mega_theather Ȯ��) : (".$nameTheater.")<br>" ; $bErr = true ;
                                        }
                                    }
                                    else
                                    {
                                        $Result = "��ȭ�� �ش��ϴ� ��ȭ�� ã���� �����ϴ�." ; $bErr = true ;
                                    }
                                }
                                else
                                {
                                    $Result .= "�ش��ϴ� ��ȭ�ڵ尡 �����ϴ�.(xls_mega_filmtitle Ȯ��) : (".$nameFilm."-???)<br>" ; $bErr = true ;
                                }
                            }
                            else
                            {
                                if  ( $data->sheets[0]['cells'][$i][4] == "�Ұ�" )
                                {
                                    $Cnt = megaUpload($Silmooja,$SingoDate,$arrScores,$FilmType,$connect) ;
                                }
                                else
                                {
                                    $Cnt = count($arrScores);
                                }

                                for ($k = 0 ; $k < $Cnt; $k++)
                                {
                                    array_pop($arrScores); // ������..
                                }
                            }
                        }
                        ?>


                        <TR>
                        <?
                        if  ($data->sheets[0]['numCols'] >= 5)
                        {
                             if  ( $data->sheets[0]['cells'][$i][4]<>"�Ұ�")
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
                                     if  (($j==4) && ($cellvalue<>"�Ұ�")) $tdcolor = "red" ;
                                     if  (($j==5) && ($data->sheets[0]['cells'][$i][4]<>"�Ұ�")) $tdcolor = "green" ;
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
                        array_pop($arrScores); // ������..
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

