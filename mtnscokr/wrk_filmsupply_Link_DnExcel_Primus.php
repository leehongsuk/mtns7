<?

set_time_limit(0) ; // �� ó���۾��� ���� ���� ����.....

include "config.php";

$connect=dbconn();

mysql_select_db($cont_db) ;

//$WorkDate = strtotime(substr($SingoDate,0,4)."-".substr($SingoDate,4,2)."-".substr($SingoDate,6,2)."") ;
$WorkDate = $SingoDate ;

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

    return $Dgree2 ;
}

function ConvertRoom2($tempRoom)
{
    $Room2 =  "__" ;

    $nChung = strpos($tempRoom,"��") ;
    $nKawn  = strpos($tempRoom,"��") ;

    $nStart = 0 ;

    if ($nChung!="") $nStart = $nChung + 2 ;
    if ($nKawn>=0)   $Room2 = sprintf("%02d",trim(substr($tempRoom,$nStart,($nKawn-$nStart)))) ;

    return $Room2 ;
}

function ConvertUnitPrice($tempUnitPrice)
{
    $nPos = strpos($tempUnitPrice,"��") ;

    if  ($nPos >= 0) { $UnitPrice = substr($tempUnitPrice,0,$nPos) ; }
    else             { $UnitPrice = 0 ; }

    return $UnitPrice ;
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
        $ErrorMsg = "" ;

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

    if  ($gubun=="�����ӽ�")
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
            for ($i=1 ; $i<=19 ; $i++)
            {
                $arrySells[$i] = "" ;  // �迭����..
            }

            if ( $digital ==  "yes" )
            {
                // �ش� ��¥�� ��� Primuse�� �ϴ� �������.
                $sQuery = "Delete From wrk_digital_account     \n".
                          " Where DigDate  = '".$SingoDate."'  \n".
                          "   And Gubun    = 'Primuse'         \n" ;// $Result = $sQuery ;
                mysql_query($sQuery,$connect) ;
            }
        ?>
            <table border="0" cellpadding="3" >
            <?
            $bTitleAfter = false ;

            $arrScores = array();

            for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++)
            {
                $Result = "" ;
                ?>
                <TR>
                <?
                echo "<TD align='right'><B>".$i."</B></TD>";




                if  ($data->sheets[0]['numCols']!=19)
                {
                    $Result = "Į������ Ʋ���ϴ�. - �ݵ�� 19Į���̿��� �մϴ�." ;
                }
                else
                {
                    for ($j = 1; $j <= /*$data->sheets[0]['numCols']*/20; $j++)
                    {
                        $arrySells[$j] = $data->sheets[0]['cells'][$i][$j] ;
                    }

                    if  ($bTitleAfter == true)
                    {
                        $Result = "" ;

                        if  ($arrySells[3]!="") // 'C' �÷��� �ڷ� üũ
                        {
                            $ExcTheatherName = $arrySells[3] ;
                        }
                        if  ($arrySells[5]!="") // 'D' �÷��� �ڷ� üũ
                        {
                            $ExcFilmName     = $arrySells[5] ;

                            $nStartRoom = $i + 1 ;
                        }
                        if  ($arrySells[7]=="��")
                        {
                            $nEndRoom = $i - 1 ;

							$CntDegreeScore = 0 ;
							for ($k = 9; $k <= 18; $k++)
							{
								if(trim($arrySells[$k])!="")
								{
									$CntDegreeScore ++ ; // ȸ���� ī��Ʈ�Ѵ�.
								}
							}

                            $sQuery = "Select * From xls_primus_filmtitle     ".
                                      " Where primusName = '".$ExcFilmName."' " ;  //eq($sQuery) ;
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

                                    $sQuery = "Select * From xls_primus_theather          \n".
                                              " Where primusName = '".$ExcTheatherName."' \n" ; //eq($sQuery);
                                    $QryXlsTheather = mysql_query($sQuery,$connect) ;
                                    if  ($ArrXlsTheather = mysql_fetch_array($QryXlsTheather))
                                    {
                                        $TheatherName = $ArrXlsTheather["mtnsName"] ; // �����̸� ��ȯ

                                        $sQuery = "Select Code                                                               \n".
												  "      ,Location                                                           \n".
												  "      ,Discript                                                           \n".
												  "      ,IF( '".$SingoDate."' >=  '20160523', GikumRate,  1.03 ) GikumRate   \n".
												  "  From bas_theather                                                       \n".
                                                  " Where Discript = '".$TheatherName."'                                     \n" ;  //eq($sQuery);
                                        $QryTheather = mysql_query($sQuery,$connect) ;
                                        if  ($ArrTheather = mysql_fetch_array($QryTheather))
                                        {
                                            $Theather  = $ArrTheather["Code"] ;
                                            $Location  = $ArrTheather["Location"] ;
                                            $Discript  = $ArrTheather["Discript"] ;
											$GikumRate = $ArrTheather["GikumRate"] ;

                                            $Room2 = ConvertRoom2($data->sheets[0]['cells'][$nStartRoom-1][6])  ;  // �󿵰�

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
														  "        'Primuse',              \n".
														  "        '".$Discript."'         \n".
														  "       )                        \n" ; //$Result = $sQuery ;
												mysql_query($sQuery,$connect) ;

												$Result .= "�Ϸ�" ;
											}
											else
											{
												$sQuery = "Select Count(*) As CntSingo         \n".
														  "  From ".$sSingoName."              \n".
														  "Where SingoDate = '".$SingoDate."'  \n".
														  "  And Silmooja  = '".$Silmooja."'   \n".
														  "  And Location  = '".$Location."'   \n".
														  "  And Theather  = '".$Theather."'   \n".
														  "  And Room      = '".$Room2."'      \n".
														  "  And Open      = '".$Open."'       \n".
														  "  And Film      = '".$Film."'       \n".
														  "  And FilmType  = '".$FilmType."'   \n"; //if  ($Theather=="2445") eq($sQuery); //eq($sQuery);
												$QryCntSingo = mysql_query($sQuery,$connect) ;
												if  ($ArrCntSingo = mysql_fetch_array($QryCntSingo))
												{
													 $CntSingo = $ArrCntSingo["CntSingo"] ;
												}
												//eq($TheatherName.":".$CntSingo."\n");


												if  ($CntSingo > 0)
												{
													$ErrorMsg .=  $TheatherName.":".$Room2."���� �̹� �ڷᰡ �ֽ��ϴ�.\\n" ;
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
													mysql_query($sQuery,$connect) ;

													$sQuery = "Select * From ".$sShowroomorder."     \n".
															  " Where Theather   = '".$Theather."'   \n".
															  "   And Room       = '".$Room2."'      \n" ; //eq($sQuery);
													$QryShowroomorder = mysql_query($sQuery,$connect) ;
													if  ($ArrShowroomorder = mysql_fetch_array($QryShowroomorder))
													{
														$RoomOrder = $ArrShowroomorder["Seq"] ;												
													
														for ($k = $nStartRoom  ; $k <= $nEndRoom ; $k++)
														{
															$Degree     = "01" ; // ������ 1ȸ����..
															$UnitPrice  = ConvertUnitPrice($data->sheets[0]['cells'][$k][7]) ;
															$Score      = $data->sheets[0]['cells'][$k][19] ;
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
															mysql_query($sQuery,$connect) ;
														}

														// ����ȸ�� ���翩��Ȯ�� ..
														$sQuery = "Select * From ".$sDgrpName."           \n".
																  " Where Silmooja = '".$Silmooja."'      \n".
																  "   And WorkDate = '".$SingoDate."'     \n".
																  "   And Open     = '".$Open."'          \n".
																  "   And Film     = '".$Film."'          \n".
																  "   And Theather = '".$Theather."'      \n".
																  "   And Room     = '".$Room2."'         \n" ;  //if  ($Theather=="2445") eq($sQuery); ///////////
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
																	  ")                           \n" ;  //if  ($Theather=="2445") eq($sQuery);  //eq($sQuery);
															mysql_query($sQuery,$connect) ;
														}

														$Result .= "�Ϸ�" ;
													}
													else
													{
														$Result .= "�ش��ϴ� ��������ڵ尡 �����ϴ�. (bas_showroomorder Ȯ��, ���ڵ�Ȯ��): (".$TheatherName.")<br>" ;
													}												
												}
											}
                                        }
                                        else
                                        {
                                            $Result .= "�ش��ϴ� �����ڵ尡 �����ϴ�. (bas_theather Ȯ��): (".$TheatherName.")<br>" ;
                                        }
                                    }
                                    else
                                    {
                                        $Result .= "�ش��ϴ� �����ڵ尡 �����ϴ�.(xls_primus_theather Ȯ��) : (".$TheatherName.")<br>" ;
                                    }
                                }
                                else
                                {
                                    $Result .= "�ش��ϴ� ��ȭ�ڵ尡 �����ϴ�. (bas_filmtitle Ȯ��): (".$FilmName.")<br>" ;
                                }
                            }
                            else
                            {
                                $Result .= "�ش��ϴ� ��ȭ�ڵ尡 �����ϴ�.(xls_primus_filmtitle Ȯ��) : (".$FilmName."-???)<br>" ;
                            }
                        }
                    }

                    if  (($arrySells[2]=="�����ڵ�") &&
                         ($arrySells[3]=="�����") &&
                         ($arrySells[4]=="��ȭ�ڵ�") &&
                         ($arrySells[5]=="��ȭ��")  &&
                         ($arrySells[6]=="�󿵰�"))
                    {
                        $bTitleAfter = true ; $Result = "Ÿ��Ʋ�ν�" ;
                    }
                }

                for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++)
                {
                    $cellvalue = $data->sheets[0]['cells'][$i][$j] ;

                    //echo "<TD>".$i.",".$j." ".$cellvalue."</TD>";
                    echo "<TD>".$cellvalue."</TD>";
                }
                ?>

                <td><?=$Result?></td>

                <TR>
                <?
            }
            ?>
            </table>
    <?
        }
    }
    ?>


    </body>

    <?
//echo $ErrorMsg ;
    if ($ErrorMsg != "")
    {
    ?>
        <script language="javascript">
        <!--
        alert("<?=$ErrorMsg?>");
        //-->
        </script>
    <?
    }
    ?>

</html>



<?
mysql_close($connect);
?>

