<?

set_time_limit(0) ; // �� ó���۾��� ���� ���� ����.....

include "config.php";

$connect=dbconn();

mysql_select_db($cont_db) ;

//$WorkDate = strtotime(substr($SingoDate,0,4)."-".substr($SingoDate,4,2)."-".substr($SingoDate,6,2)."") ;
//$WorkDate = $SingoDate ;

$Today = time()-(3600*7) ; // ���� 7�� ���� ���÷� �����Ѵ�...


function ConvertRoom2($tempRoom)
{
    $Room2 =  "__" ;

    $nMiner = strpos($tempRoom,"-") ;
    $nChung = strpos($tempRoom,"��") ;
    $nKawn  = strpos($tempRoom,"��") ;

    $nStart = 0 ;

    if ($nChung!="")
    {
        if ($nChung < $nKawn) $nStart = $nChung + 2 ;
    }
    if ($nKawn>=0)
    {

        if ($nMiner!="") $nStart = $nMiner + 1 ;

        $Room2 = sprintf("%02d",trim(substr($tempRoom,$nStart,($nKawn-$nStart)))) ;
    }

    return $Room2 ;
}

function ConvertPrice($tempRoom)
{
    $Price =  "" ;

    $nWon  = strpos($tempRoom,"��") ;

    $nStart = 0 ;

    if ($nWon!="")
    {
        $Price = trim(substr($tempRoom,0,$nWon)) ;
    }

    return $Price ;
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

    if  ($gubun=="CGV")
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
           if ( $digital ==  "yes" )
           {
               // �ش� ��¥�� ��� cgv�� �ϴ� �������.
               $sQuery = "Delete From wrk_digital_account     \n".
                         " Where DigDate  = '".$SingoDate."'  \n".
                         "   And Gubun    = 'CGV'             \n" ;// $Result = $sQuery ;
               mysql_query($sQuery,$connect) ;
           }
    ?>

        <TABLE border="0" cellpadding="3" >

        <?
        for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++)
        {
            $Result = "" ;

            if  ($i>=11)
            {
                //$Silmooja = "777777" ;

                // ����� 3, ��ȭ�� 5
                //if  ((trim($data->sheets[0]['cells'][$i][3]) <> "") && (trim($data->sheets[0]['cells'][$i][5]) <> ""))
                if  (trim($data->sheets[0]['cells'][$i][5]) <> "")
                {
                    $tdcolor = "blue" ;

                    if  (trim($data->sheets[0]['cells'][$i][3]) != "")   $TheatherName = trim($data->sheets[0]['cells'][$i][3]) ;

                    $FilmName = trim($data->sheets[0]['cells'][$i][5]) ;

                    if  (trim($data->sheets[0]['cells'][$i][6]) <> "")
                    {
                        $Room2 = ConvertRoom2(trim($data->sheets[0]['cells'][$i][6]));  if  ($Room2=="00") $Result = "[��]��ȣ�ν� ����" ; //  eq($TheatherName ." ".$Room2 );
                    }


                    $sQuery = "Select * From bas_filmtitle   \n".
                              " Where Name = '".$FilmName."' \n" ;  // eq($sQuery);
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

						$sQuery = "Select Code                                                                \n".
                                  "      ,Location                                                            \n".
                                  "      ,Discript                                                            \n".
                                  "      ,IF( '".$SingoDate."' >=  '20160523', GikumRate,  1.03 ) GikumRate   \n".
                                  "  From bas_theather                                                        \n".
                                  " Where Discript = '".$TheatherName."'                                      \n" ;
                        $QryTheather = mysql_query($sQuery,$connect) ;
                        if  ($ArrTheather = mysql_fetch_array($QryTheather))
                        {
                            $Theather  = $ArrTheather["Code"] ;
                            $Location  = $ArrTheather["Location"] ;
                            $Discript  = $ArrTheather["Discript"] ;
							$GikumRate = $ArrTheather["GikumRate"] ;

                            $sQuery = "Select * From ".$sShowroomorder."  \n".
                                      " Where Theather = '".$Theather."'  \n".
                                      "   And Room     = '".$Room2."'     \n" ; // $Result = $sQuery ;
                            $QryShowroomorder = mysql_query($sQuery,$connect) ;
                            if  ($ArrShowroomorder = mysql_fetch_array($QryShowroomorder))
                            {
                                $RoomOrder = $ArrShowroomorder["Seq"] ; // ������� �ִ°͸� �ްڴ�..

                                if  ($digital=="yes")   /////////////// digital üũ
								{
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
											  "  And Film      = '".$Film."'      \n" ; //$Result = $sQuery ;
									mysql_query($sQuery,$connect) ; /////////////////

									$sQuery = "Delete From ".$sDgrpName."         \n".
											  " Where Silmooja = '".$Silmooja."'  \n".
											  "   And WorkDate = '".$SingoDate."' \n".
											  "   And Open     = '".$Open."'      \n".
											  "   And Film     = '".$Film."'      \n".
											  "   And Theather = '".$Theather."'  \n".
											  "   And Room     = '".$Room2."'     \n" ; //$Result = $sQuery ;
									mysql_query($sQuery,$connect) ;
								}
                                $nStarti = $i + 1 ;
                            }
                            else
                            {
                                $RoomOrder = -1 ;
                                $Result = "RoomOrderȮ�����ּ���" ;
                                $nStarti = -1 ;
                            }
                        }
                        else
                        {
                            $Result = "�ش��ϴ� �����ڵ尡 �����ϴ�." ;
                            $nStarti = -1 ;
                        }
                    }
                    else
                    {
                        $Result = "�ش��ϴ� ��ȭ�ڵ尡 �����ϴ�." ;
                        $nStarti = -1 ;
                    }

                }
                else
                {
                    if  (trim($data->sheets[0]['cells'][$i][7]) == "��")
                    {
                        $tdcolor = "green" ;

                        $nEndi = $i - 1 ;

                        //$Result = $nStarti."-". $nEndi ;

						$CntDegreeScore = 0 ;
						for ($k = 9; $k <= 18; $k++)
						{
							if(trim($data->sheets[0]['cells'][$i][$k])!="")
							{
								$CntDegreeScore ++ ; // ȸ���� ī��Ʈ�Ѵ�.
							}
						}

                        if  ($nStarti != -1)
                        {
                            //$less4000 = false ;

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
                                          "        'CGV',                  \n".
                                          "        '".$Discript."'         \n".
                                          "       )                        \n" ;// $Result = $sQuery ;
                                mysql_query($sQuery,$connect) ;
                            }
							else
							{
								for ($j = $nStarti; $j <= $nEndi; $j++)
								{
									$UnitPrice = ConvertPrice(trim($data->sheets[0]['cells'][$j][7])) ;
									//if  ($UnitPrice >= 4000)
									//{
										$Score = trim($data->sheets[0]['cells'][$j][19]) ;

										if  ($Score!="")
										{
											 $sQuery = "Insert Into ".$sSingoName."                \n".
													   "Values                                     \n".
													   "(                                          \n".
													   "  '".$SingoDate."100000',                  \n".
													   "  '".$SingoDate."',                        \n".
													   "  '".$Silmooja."',                         \n".
													   "  '".$Location."',                         \n".
													   "  '".$Theather."',                         \n".
													   "  '".$Room2."',                            \n".
													   "  '".$Open."',                             \n".
													   "  '".$Film."',                             \n".
													   "  '".$FilmType."',                         \n". //////////// 9��5�� //////
													   "  '01',                                    \n".
													   "  '".$UnitPrice."',                        \n".
													   "  '".$Score."',                            \n".
													   "  '".$UnitPrice * $Score."',               \n".
													   "  '".get_GikumAount2($UnitPrice,$GikumRate,$Score)."', \n".
													   "  '',                                      \n".
													   "  '".$RoomOrder."'                         \n".
													   ")                                          \n" ; //$Result .= $sQuery."<br>" ;
											 mysql_query($sQuery,$connect) ;					   
										}														   
									//}
									//else
									//{
									//	$less4000 = true ;
									//}
								}
							}
                            
                            //$Result = "�Ϸ�";
                            /*
                            if  ($less4000 == true)
                            {
                                $Result = "4000�� ������ �ݾ��� �����ϴ�.<br>�ش罺�ھ�� �Էµ��� �ʽ��ϴ�" ;
                            }
                            else
                            {
                                $Result = "" ;
                            }
                            */
                        }
                        else
                        {
                            $Result = "" ;
                        }
                    }
                    else
                    {
                        if  (trim($data->sheets[0]['cells'][$i][7]) <> "")
                        {
                            $UnitPrice = ConvertPrice(trim($data->sheets[0]['cells'][$i][7])) ;
                            /*
							if  ($UnitPrice < 4000)
                            {
                                $Strike1 = "<STRIKE>";
                                $Strike2 = "</STRIKE>";

                                $tdcolor = "red" ;
                                $Result = "<font color=$tdcolor><B>4000</B>�� ������ �ݾ��� �����ϴ�.<br>�ش罺�ھ�� �Էµ��� �ʽ��ϴ�</font>" ;
                            }
                            else
                            {
								*/
                                $Strike1 = "";
                                $Strike2 = "";

                                $tdcolor = "black" ;
                                $Result = "" ;
                            //}
                        }
                        else
                        {
                            $tdcolor = "black" ;
                        }

                    }
                }
            }
            ?>
			<TR>
				<?
				echo "<TD align='right'><B>".$i."</B></TD>";

				for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++)
				{
					$cellvalue = $data->sheets[0]['cells'][$i][$j] ;

					echo "<TD align='$align'><font color=$tdcolor>".$Strike1.$cellvalue.$Strike2."</font></TD>";
				}
				?>
				<td><?=$Result?></td>

            </TR>
            <?
            $Result = "" ;
        }
        ?>
        </TABLE>

    <?
        }
    }
    ?>

    </body>

</html>


<?
mysql_close($connect);
?>

