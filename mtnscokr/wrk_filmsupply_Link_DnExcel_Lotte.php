<?

set_time_limit(0) ; // �� ó���۾��� ���� ���� ����.....

include "config.php";

$connect=dbconn();

mysql_select_db($cont_db) ;

//$WorkDate = strtotime(substr($SingoDate,0,4)."-".substr($SingoDate,4,2)."-".substr($SingoDate,6,2)."") ;
$WorkDate = $SingoDate ;               // �������ڰ� �ƴ� �� ���� ���ڷ� �Ѵ�.

$Today = time()-(3600*7) ; // ���� 7�� ���� ���÷� �����Ѵ�...



function ConvertDgree2($tempDgree)
{
    $Dgree2 =  "__" ;

    if  ($tempDgree=="1ȸ")  $Dgree2 =  "01" ;
    if  ($tempDgree=="2ȸ")  $Dgree2 =  "02" ;
    if  ($tempDgree=="3ȸ")  $Dgree2 =  "03" ;
    if  ($tempDgree=="4ȸ")  $Dgree2 =  "04" ;
    if  ($tempDgree=="5ȸ")  $Dgree2 =  "05" ;
    if  ($tempDgree=="6ȸ")  $Dgree2 =  "06" ;
    if  ($tempDgree=="7ȸ")  $Dgree2 =  "07" ;
    if  ($tempDgree=="8ȸ")  $Dgree2 =  "08" ;
    if  ($tempDgree=="9ȸ")  $Dgree2 =  "09" ;
    if  ($tempDgree=="10ȸ")  $Dgree2 =  "10" ;
    if  ($tempDgree=="11ȸ")  $Dgree2 =  "11" ;
    if  ($tempDgree=="12ȸ")  $Dgree2 =  "12" ;
    if  ($tempDgree=="13ȸ")  $Dgree2 =  "13" ;
    if  ($tempDgree=="14ȸ")  $Dgree2 =  "14" ;
    if  ($tempDgree=="15ȸ")  $Dgree2 =  "15" ;
    if  ($tempDgree=="16ȸ")  $Dgree2 =  "16" ;
    if  ($tempDgree=="17ȸ")  $Dgree2 =  "17" ;
    if  ($tempDgree=="18ȸ")  $Dgree2 =  "18" ;
    if  ($tempDgree=="19ȸ")  $Dgree2 =  "19" ;

    return $Dgree2 ;
}

function ConvertRoom2($tempRoom)
{
    $Room2 =  "__" ;

    if  ($tempRoom=="1��")  $Room2 =  "01" ;
    if  ($tempRoom=="2��")  $Room2 =  "02" ;
    if  ($tempRoom=="3��")  $Room2 =  "03" ;
    if  ($tempRoom=="4��")  $Room2 =  "04" ;
    if  ($tempRoom=="5��")  $Room2 =  "05" ;
    if  ($tempRoom=="6��")  $Room2 =  "06" ;
    if  ($tempRoom=="7��")  $Room2 =  "07" ;
    if  ($tempRoom=="8��")  $Room2 =  "08" ;
    if  ($tempRoom=="9��")  $Room2 =  "09" ;
    if  ($tempRoom=="10��")  $Room2 =  "10" ;
    if  ($tempRoom=="11��")  $Room2 =  "11" ;
    if  ($tempRoom=="12��")  $Room2 =  "12" ;
    if  ($tempRoom=="13��")  $Room2 =  "13" ;
    if  ($tempRoom=="14��")  $Room2 =  "14" ;
    if  ($tempRoom=="15��")  $Room2 =  "15" ;
    if  ($tempRoom=="16��")  $Room2 =  "16" ;
    if  ($tempRoom=="17��")  $Room2 =  "17" ;
    if  ($tempRoom=="18��")  $Room2 =  "18" ;
    if  ($tempRoom=="19��")  $Room2 =  "19" ;
    if  ($tempRoom=="20��")  $Room2 =  "20" ;
    if  ($tempRoom=="21��")  $Room2 =  "21" ;
    if  ($tempRoom=="22��")  $Room2 =  "22" ;
    if  ($tempRoom=="23��")  $Room2 =  "23" ;
    if  ($tempRoom=="24��")  $Room2 =  "24" ;
    if  ($tempRoom=="25��")  $Room2 =  "25" ;

    return $Room2 ;
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

    if  ($gubun=="�Ե����׸�")
    {
         if ( $digital ==  "yes" )
         {
              $SingoDate = $data->sheets[0]['cells'][2][1] ;

             // �ش� ��¥�� ��� Lotte �ϴ� �������.
             $sQuery = "Delete From wrk_digital_account     \n".
                       " Where DigDate  = '".$SingoDate."'  \n".
                       "   And Gubun    = 'Lotte'           \n" ; //eq( $sQuery) ;
             mysql_query($sQuery,$connect) ;
         }
    ?>
        <table border="0" cellpadding="3" >
        <?
        $StartLine = 2 ;
        $arrScores = array();

		$oldDegree = "" ;

        $CntDegreeScore = 0;

        for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++)
        {
            $Result = "" ;

            if  ($oldDegree != $data->sheets[0]['cells'][$i][5])
            {
                if  (($data->sheets[0]['cells'][$i][5] != "�󿵰��� �Ұ�")
                    && ($data->sheets[0]['cells'][$i][5] != ""))
                {
                    $CntDegreeScore ++ ; // ȸ���� �ٲ� ���� ī����..
                }
                $oldDegree = $data->sheets[0]['cells'][$i][5] ;	//eq($CntDegreeScore.":".$data->sheets[0]['cells'][$i][5]);
            }
            ?>
            <tr>

            <?
            echo "<TD align='right'><B>".$i."</B></TD>";

            for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++)
            {
                $cellvalue = $data->sheets[0]['cells'][$i][$j] ;

                $tdcolor = "black" ;

                if  ($data->sheets[0]['cells'][$i][3] == "��ȭ�� �Ұ�")
                {
                    $tdcolor = "gray" ;
                    $StartLine = $i + 1 ;
                }
                if  ($data->sheets[0]['cells'][$i][4] == "��ȭ���� �Ұ�")
                {
                    $tdcolor = "gray" ;
                    $StartLine = $i + 1 ;
                }
                if  ($data->sheets[0]['cells'][$i][5] == "�󿵰��� �Ұ�")
                {
                    $tdcolor = "blue" ;
                    $StartLine = $i + 1 ;
                }




                if  (($j==3) && ($cellvalue == "��ȭ�� �Ұ�"))
                {
                }
                else
                {
                    if  (($j==5) && ($cellvalue == "�󿵰��� �Ұ�"))
                    {
                        $FilmName     = $data->sheets[0]['cells'][$i][2] ; // ��ȭ��
                        $TheatherName = $data->sheets[0]['cells'][$i][3] ; // �󿵰���

                        $sQuery = "Select * From xls_lotte_filmtitle  ".
                                  " Where lotteName = '".$FilmName."' " ;
                        $QryXlsFilmtitle = mysql_query($sQuery,$connect) ;
                        if  ($ArrXlsFilmtitle = mysql_fetch_array($QryXlsFilmtitle))
                        {
                            $FilmName = $ArrXlsFilmtitle["mtnsName"] ; // �ʸ��̸� ��ȯ

                            $sQuery = "Select * From bas_filmtitle   ".
                                      " Where Name = '".$FilmName."' " ;
                            $QryFilmtitle = mysql_query($sQuery,$connect) ;
                            if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
                            {
                                $Open = $ArrFilmtitle["Open"] ;
                                $Film = $ArrFilmtitle["Code"] ;

                                $sSingoName     = get_singotable($Open,$Film,$connect) ;  // �Ű� ���̺� �̸�..
                                $sAccName       = get_acctable($Open,$Film,$connect) ;    // accumulate �̸�..
                                $sDgrName       = get_degree($Open,$Film,$connect) ;
                                $sDgrpName      = get_degreepriv($Open,$Film,$connect) ;
                                $sShowroomorder = get_showroomorder($Open,$Film,$connect) ;


                                $sQuery = "Select * From xls_lotte_theather       \n".
                                          " Where lotteName = '".$TheatherName."' \n" ;  //eq($sQuery);
                                $QryXlsTheather = mysql_query($sQuery,$connect) ;
                                if  ($ArrXlsTheather = mysql_fetch_array($QryXlsTheather))
                                {
                                    $TheatherName = $ArrXlsTheather["mtnsName"] ; // �����̸� ��ȯ

									$sQuery = "Select Code                                                                \n".
											  "      ,Location                                                            \n".
											  "      ,Discript                                                            \n".
											  "      ,IF( '".$SingoDate."' >=  '20160523', GikumRate,  1.03 ) GikumRate   \n".
											  "  From bas_theather                                                        \n".
											  " Where Discript = '".$TheatherName."'                                      \n" ;  //eq($sQuery);
                                    $QryTheather = mysql_query($sQuery,$connect) ;
                                    if  ($ArrTheather = mysql_fetch_array($QryTheather))
                                    {
                                        $Theather  = $ArrTheather["Code"] ;
                                        $Location  = $ArrTheather["Location"] ;
                                        $Discript  = $ArrTheather["Discript"] ;
										$GikumRate = $ArrTheather["GikumRate"] ;

                                        $Room2     = ConvertRoom2($data->sheets[0]['cells'][$i][4])  ;  // �󿵰�
                                        $SingoDate =  $arrScores[0][0] ;

										if  ($digital=="yes")   /////////////// digital üũ
                                        {
                                            $sQuery = "Insert Into wrk_digital_account \n".
                                                      "Values(                         \n".
                                                      "        '".$SingoDate."',       \n".
                                                      "        '".$Open."',            \n".
                                                      "        '".$Film."',            \n".
                                                      "        '".$FilmType."',        \n".
                                                      "        '".$Theather."',        \n".
                                                      "        '".$Room2."',           \n".
                                                      "        ".$CntDegreeScore.",    \n".
                                                      "        'Lotte',                \n".
                                                      "        '".$Discript."'         \n".
                                                      "       )                        \n" ;// $Result = $sQuery ;
                                            mysql_query($sQuery,$connect) ;
                                        }
										else
										{
											$sQuery = "Delete From ".$sSingoName."        \n".
													  "Where SingoDate = '".$SingoDate."' \n".
													  "  And Silmooja  = '".$Silmooja."'  \n".
													  "  And Location  = '".$Location."'  \n".
													  "  And Theather  = '".$Theather."'  \n".
													  "  And Room      = '".$Room2."'     \n".
													  "  And Open      = '".$Open."'      \n".
													  "  And Film      = '".$Film."'      \n" ; //eq($sQuery);
											mysql_query($sQuery,$connect) ; /////////////////

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

											// ����ȸ�� ���翩��Ȯ�� ..
											$sQuery = "Select * From ".$sDgrpName."           \n".
													  " Where Silmooja = '".$Silmooja."'      \n".
													  "   And WorkDate = '".$SingoDate."'     \n".
													  "   And Open     = '".$Open."'          \n".
													  "   And Film     = '".$Film."'          \n".
													  "   And Theather = '".$Theather."'      \n".
													  "   And Room     = '".$Room2."'         \n" ;   //eq($sQuery);
											$qry_degreepriv = mysql_query($sQuery,$connect) ;
											$degreepriv_data  = mysql_fetch_array($qry_degreepriv) ;
											if  (!$degreepriv_data) // ���� ȸ�� ������ ���ٸ�..
											{
												$sQuery = "Insert Into ".$sDgrpName."  \n".
														  "Values                      \n".
														  "(                           \n".
														  "    '".$Silmooja."',        \n".
														  "    '".$SingoDate."',       \n".
														  "    '".$Open."',            \n".
														  "    '".$Film."',            \n".
														  "    '".$Theather."',        \n".
														  "    '".$Room2."',           \n".
														  "    '01',                   \n".
														  "    '1000',                 \n".
														  "    '".$TheatherName."'     \n".
														  ")                           \n" ;   //eq($sQuery);
												mysql_query($sQuery,$connect) ;
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
												//$Degree     = sprintf("%02d",$current[1]) ;  // �󿵰� ;
												$Degree     = "01" ; // ������ 1ȸ����..
												$UnitPrice  = $current[2] ;

												$sQuery = "Select NumPersons                    \n".
														  "  From ".$sSingoName."               \n".
														  " Where SingoDate = '".$SingoDate."'  \n".
														  "   And Silmooja  = '".$Silmooja."'   \n".
														  "   And Location  = '".$Location."'   \n".
														  "   And Theather  = '".$Theather."'   \n".
														  "   And Room      = '".$Room2."'      \n".
														  "   And Open      = '".$Open."'       \n".
														  "   And Film      = '".$Film."'       \n".
														  "   And ShowDgree = '".$Degree."'     \n".
														  "   And UnitPrice = '".$UnitPrice."'  \n" ;   //eq($sQuery);
												$QryNumPersons = mysql_query($sQuery,$connect) ;
												if  ($ArrNumPersons  = mysql_fetch_array($QryNumPersons))
												{
													 $OldNumPersons = $ArrNumPersons["NumPersons"] ;

													 $sQuery = "Delete From ".$sSingoName."          \n".
															   " Where SingoDate = '".$SingoDate."'  \n".
															   "   And Silmooja  = '".$Silmooja."'   \n".
															   "   And Location  = '".$Location."'   \n".
															   "   And Theather  = '".$Theather."'   \n".
															   "   And Room      = '".$Room2."'      \n".
															   "   And Open      = '".$Open."'       \n".
															   "   And Film      = '".$Film."'       \n".
															   "   And ShowDgree = '".$Degree."'     \n".
															   "   And UnitPrice = '".$UnitPrice."'  \n" ;  // eq($sQuery);
													 mysql_query($sQuery,$connect) ;

												}
												else
												{
													$OldNumPersons = 0 ;
												}

												$Score      = $current[3] + $OldNumPersons ;
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
														  "  '".$FilmType."',            \n".//////////// 9��5�� //////
														  "  '".$Degree."',              \n".
														  "  '".$UnitPrice."',           \n".
														  "  '".$Score."',               \n".
														  "  '".$UnitPrice * $Score."',  \n".
														  "  '".$GikumAount."',          \n".
														  "  '',                         \n".
														  "  '".$RoomOrder."'            \n".
														  ")                             \n" ; //eq($sQuery);
												mysql_query($sQuery,$connect) ; /////////////////
											}
											$Cnt = count($arrScores);

											for ($k = 0 ; $k < $Cnt; $k++)
											{
												array_pop($arrScores); // ������..
											}
										}


                                        $Result .= "�Ϸ�" ;
                                    }
                                    else
                                    {
                                        $Result .= "�ش��ϴ� �����ڵ尡 �����ϴ�. (bas_theather Ȯ��): (".$TheatherName.")<br>" ;
                                    }
                                }
                                else
                                {
                                    $Result .= "�ش��ϴ� �����ڵ尡 �����ϴ�.(xls_lotte_theather Ȯ��) : (".$TheatherName.")<br>" ;
                                }
                            }
                            else
                            {
                                $Result .= "�ش��ϴ� ��ȭ�ڵ尡 �����ϴ�. (bas_filmtitle Ȯ��): (".$FilmName.")<br>" ;
                            }
                        }
                        else
                        {
                            $Result .= "�ش��ϴ� ��ȭ�ڵ尡 �����ϴ�.(xls_lotte_filmtitle Ȯ��) : (".$FilmName."-???)<br>" ;
                        }
                        if  ($Result != "�Ϸ�")
                        {
                            $Cnt = count($arrScores);

                            for ($k = 0 ; $k < $Cnt; $k++)
                            {
                                array_pop($arrScores); // ������..
                            }
                        }
						$CntDegreeScore = 0 ;
                    }
                    else
                    {
                       if  (($j==6) && ($cellvalue == "��ȸ���� �Ұ�"))
                       {
                           $tdcolor = "red" ;

                           $bResult = true ;
                           for ($k = $StartLine ; $k < $i; $k++)
                           {
                                $Room2  = ConvertRoom2($data->sheets[0]['cells'][$k][4])  ;  // �󿵰�
                                $Dgree2 = ConvertDgree2($data->sheets[0]['cells'][$k][5]) ;  // ȸ��

                                $num4  = is_numeric($Room2) ;
                                $num5  = is_numeric($Dgree2) ;
                                $num6  = is_numeric($data->sheets[0]['cells'][$k][6]) ;
                                $num7  = is_numeric($data->sheets[0]['cells'][$k][7]) ;

                                if  ($data->sheets[0]['cells'][$k][4] != "��ȭ���� �Ұ�")
                                {
                                    if  (($num4 == true) && ($num5 == true) && ($num6 == true) && ($num7 == true))
                                    {
                                        $Dgree2 = ConvertDgree2($data->sheets[0]['cells'][$k][5]) ;   // ȸ��

                                        $arrItem = array( $data->sheets[0]['cells'][$k][1],   // ������
                                                          $Dgree2,
                                                          $data->sheets[0]['cells'][$k][6],   // �ܰ�
                                                          $data->sheets[0]['cells'][$k][7] ); // ���ھ�

                                        array_push($arrScores, $arrItem); // �߰��ϰ�
                                    }
                                    else
                                    {
                                        $bResult = false ;
                                    }
                                }
                           }

                           if  ($bResult == false)
                           {
                               $Result .= "����<br>" ;
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
                               ($data->sheets[0]['cells'][$i][3] != "��ȭ�� �Ұ�") &&
                               ($data->sheets[0]['cells'][$i][4] != "��ȭ���� �Ұ�") &&
                               ($data->sheets[0]['cells'][$i][5] != "�󿵰��� �Ұ�") &&
                               ($data->sheets[0]['cells'][$i][6] != "��ȸ���� �Ұ�")
                               )
                           {
                               if  (($j==6) && ($i>1))  $tdcolor = "Orange" ;
                               if  (($j==7) && ($i>1))  $tdcolor = "green" ;
                           }
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
                ($data->sheets[0]['cells'][$i][3] != "��ȭ�� �Ұ�") &&
                ($data->sheets[0]['cells'][$i][4] != "��ȭ���� �Ұ�") &&
                ($data->sheets[0]['cells'][$i][5] != "�󿵰��� �Ұ�") &&
                ($data->sheets[0]['cells'][$i][6] != "��ȸ���� �Ұ�")
                )
            {
                $sErr = "";

                $Room2  = ConvertRoom2($data->sheets[0]['cells'][$i][4])  ;   // �󿵰�
                $Dgree2 = ConvertDgree2($data->sheets[0]['cells'][$i][5]) ;   // ȸ��

                $num4  = is_numeric($Room2) ;
                $num5  = is_numeric($Dgree2) ;
                $num6  = is_numeric($data->sheets[0]['cells'][$i][6]) ;
                $num7  = is_numeric($data->sheets[0]['cells'][$i][7]) ;

                if  ($num4==false) { $sErr .= "������ ���ڰ� �ƴ�, " ; }
                if  ($num5==false) { $sErr .= "������ ���ڰ� �ƴ�, " ; }
                if  ($num6==false) { $sErr .= "�߱Ǳݾ��� ���ڰ� �ƴ�, " ; }
                if  ($num7==false) { $sErr .= "�ż��� ���ڰ� �ƴ�, ";  }

                if  (($num4 == true) && ($num5 == true) && ($num6 == true) && ($num7 == true))
                {}
                else
                {
                    $Result .= "����(".$sErr.")<br>" ;
                }
            }
            ?>
            <td><?=$Result?></td>

            </tr>
            <?
        }
        ?>
        </table>
    <?
    }
    ?>


    </body>

</html>



<?
mysql_close($connect);
?>

