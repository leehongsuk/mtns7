<?
    set_time_limit(0) ; // 긴 처리작업이 있을 때만 쓴다.....

    if ($ToExel)
    {
        header("Content-type: application/vnd.ms-excel;charset=KSC5601");
        header("Content-Disposition: attachment; filename=excel_name.xls");
        header("Content-Description: GamZa Excel Data");

        $NBSP="" ;
    }
    else
    {
        $NBSP="&nbsp;" ;
    }

?>

<!-- 일일 보고서 -->
<html>

    <link rel=stylesheet href=./LinkStyle.css type=text/css>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

    <head>
    <title>선재보고서</title>

    <style type="text/css" media="all">

        #beauty,
        #use-th,
        #use-th-beauty{
         border-collapse: collapse;
         border: 0px solid #CCC;
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

        #use-th td {
         border: 0px solid #CCC;
        }
    </style>

    <script language="javascript">
				<!--
				    //
								// 엑셀 출력
								//
								function toexel_click()
								{
												location.href = '<?=$PHP_SELF?>?'
																												+ 'Code=<?=$_GET["Code"]?>&'
																												+ 'ToExel=Yes' ;
								}
				-->
				</script>

</head>



<body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0 >
[<?
$WorkDate = substr($Date,0,4)."/".substr($Date,4,2)."/".substr($Date,6,2) ;
echo $WorkDate ;
?>]

<?
        require_once "DBConnect.php";

								error_reporting(0);
								$DBConn = DB_Connect2() ; // 데이터베이스연결
								if  (!$DBConn) die(sql_error());

								$_SangFilm = $_GET["Code"] ;
								$sQuery = "Select *                        ".
																		"  From bas_sangfilmtitle        ".
																		" Where Code = '".$_SangFilm."'  " ; //eq($sQuery );
								$QrySangfilmtitle = mysql_query_err($sQuery,$DBConn,__FILE__,__LINE__) ;
								if  ($ArySangfilmtitle = mysql_fetch_array($QrySangfilmtitle))
								{
												$SangfilmtitleName = $ArySangfilmtitle["Name"] ;
								}

        ?>
        <center>


        <br>
        <center>선재보고서(<?=$SangfilmtitleName?>)
        <?
        if  (!$ToExel)
								{
            ?><a href=# onClick="toexel_click();"><img src="exel.gif" width="32" height="32" border="0"></a><?
        }
								?>
        </center>
        <br>


        <table style='table-layout:fixed'  border="1" id="use-th-beauty">
             <tr>
                <th id="first" width=140 align=center>극장_</th>
                <th width=30  align=center>관</th>
                <th width=250 align=center>본영화</th>
                <th width=250 align=center>예고편</th>
                <th width=200 align=center>포스터</th>
                <th width=200 align=center>배너</th>
                <th width=200 align=center>스텐디</th>
                <th width=200 align=center>전단</th>
                <th width=200 align=center>스틸</th>
             </tr>
             <?
													$sQuery = "Select Theather.Code,                          ".
													          "       Theather.Discript,                      ".
													          "       Localtion.Name                          ".
													          "  From bas_theather      As Theather,          ".
																							"       bas_location      As Localtion,         ".
																							"       bas_srorder_081023_70  As RoomOrder          ".
													          " Where Theather.location  = Localtion.Code     ".
                       "   And Theather.Code      = RoomOrder.Theather ".
																							" GROUP BY Theather.Code                        ".
																							" Order By RoomOrder.Seq                        " ; //eq($sQuery) ;
 													$QryTheather = mysql_query_err($sQuery,$DBConn,__FILE__,__LINE__) ;
													while ($AryTheather = mysql_fetch_array($QryTheather))
													{
																		$Theather = $AryTheather["Code"] ;
																		$Discript = $AryTheather["Discript"] ;
																		$Location = $AryTheather["Name"] ;


																		$CntTrailerFilm = 0;

																		$sQuery = "Select Count(*) As CntTrailerFilm  ".
																		          "  From wrk_TrailerFilm             ".
																												" Where Theather = '".$Theather."'  ".
																												"   And SangFilm = '".$_SangFilm."' ".
																												"   And WorkDate >= '".$WorkDate."' " ; //if  ($Theather == '1953' ) eq($sQuery) ;
																		$QryCntTrailerFilm = mysql_query_err($sQuery,$DBConn,__FILE__,__LINE__) ;
																		if ($AryCntTrailerFilm = mysql_fetch_array($QryCntTrailerFilm))
																		{
																						$CntTrailerFilm = $AryCntTrailerFilm["CntTrailerFilm"] ;
																		}

																		$CntSunjae = 0 ;

																		$sQuery = "Select Count(*) As CntSunjae       ".
																		          "  From wrk_Sunjae                  ".
																												" Where Theather = '".$Theather."'  ".
																												"   And SangFilm = '".$_SangFilm."' ".
																												"   And ( PicPoster = 'Y'           ".
																												"       Or PicBener = 'Y'           ".
																												"       Or PicStand = 'Y'           ".
																												"       Or PicWholePaper = 'Y'      ".
																												"       Or PicStill = 'Y'  )        " ;
																		$QryCntSunjae = mysql_query_err($sQuery,$DBConn,__FILE__,__LINE__) ;
																		if ($AryCntSunjae = mysql_fetch_array($QryCntSunjae))
																		{
																						$CntSunjae = $AryCntSunjae["CntSunjae"] ;
																		}

                  //echo $Theather."]".	$Discript.":".$CntTrailerFilm ."-".			$CntSunjae."<br>"		;
                  $updir = "/usr/local/apache2/htdocs/cr/sunjaefile"; // 업로드 될 디렉토리..
                  $filename = sprintf("%s/%s",$_SangFilm.$Theather."0.jpg");
                  if  (file_exists($filename))
                  {
                      $sQuery = "Update wrk_Sunjae                  ".
                                "   Set PicPoster = 'Y'             ".
                                " Where Theather = '".$Theather."'  ".
                                "   And SangFilm = '".$_SangFilm."' " ;
                      mysql_query_err($sQuery,$DBConn,__FILE__,__LINE__) ;

                      $sQuery = "Update wrk_Sunjae                  ".
                                "   Set PicBener = 'Y'              ".
                                " Where Theather = '".$Theather."'  ".
                                "   And SangFilm = '".$_SangFilm."' " ;
                      mysql_query_err($sQuery,$DBConn,__FILE__,__LINE__) ;

                      $sQuery = "Update wrk_Sunjae                  ".
                                "   Set PicStand = 'Y'              ".
                                " Where Theather = '".$Theather."'  ".
                                "   And SangFilm = '".$_SangFilm."' " ;
                      mysql_query_err($sQuery,$DBConn,__FILE__,__LINE__) ;

                      $sQuery = "Update wrk_Sunjae                  ".
                                "   Set PicWholePaper = 'Y'         ".
                                " Where Theather = '".$Theather."'  ".
                                "   And SangFilm = '".$_SangFilm."' " ;
                      mysql_query_err($sQuery,$DBConn,__FILE__,__LINE__) ;

                      $sQuery = "Update wrk_Sunjae                  ".
                                "   Set PicStill = 'Y'              ".
                                " Where Theather = '".$Theather."'  ".
                                "   And SangFilm = '".$_SangFilm."' " ;
                      mysql_query_err($sQuery,$DBConn,__FILE__,__LINE__) ;
                  }

																		if  (($CntTrailerFilm > 0) || ($CntSunjae > 0))
																		{
																						$sQuery = "Select * From wrk_Sunjae           ".
																																" Where Theather = '".$Theather."'  ".
																																"   And SangFilm = '".$_SangFilm."' " ;
																						$QrySunjae = mysql_query_err($sQuery,$DBConn,__FILE__,__LINE__) ;
																						if ($ArySunjae = mysql_fetch_array($QrySunjae))
																						{
																										$CmtPoster      = $ArySunjae["CmtPoster"] ;
																										$DatePoster     = $ArySunjae["DatePoster"] ;
																										$CmtBener       = $ArySunjae["CmtBener"] ;
																										$DateBener      = $ArySunjae["DateBener"] ;
																										$CmtStand       = $ArySunjae["CmtStand"] ;
																										$DateStand      = $ArySunjae["DateStand"] ;
																										$CmtWholePaper  = $ArySunjae["CmtWholePaper"] ;
																										$DateWholePaper = $ArySunjae["DateWholePaper"] ;
																										$CmtStill       = $ArySunjae["CmtStill"] ;
																										$DateStill      = $ArySunjae["DateStill"] ;
																						}

																						$sQuery = "Select count(distinct Room) As CntRoom  ".
																																"  From bas_showroom                     ".
																																" Where Theather     = '".$Theather."'   " ;
																						$QryCntRoom = mysql_query_err($sQuery,$DBConn,__FILE__,__LINE__) ;
																						if ($AryCntRoom = mysql_fetch_array($QryCntRoom))
																						{
																										$CntRoom = $AryCntRoom["CntRoom"] ;
																						}

																						$i = 0 ;

																						$sQuery = "Select distinct Room                ".
																																"  From bas_showroom                 ".
																																" Where Theather = '".$Theather."'   ".
																																" Order By Room                      " ;
																						$QryRoom = mysql_query_err($sQuery,$DBConn,__FILE__,__LINE__) ;
																						while ($AryRoom = mysql_fetch_array($QryRoom))
																						{
																										$Room = $AryRoom["Room"] ;

																										?>
																								 	<tr>
																														<?
                              if  ($i==0)
                              {
                              ?>
                              <td align="center" rowspan="<?=$CntRoom?>"><font color="#0000FF"><?=$AryTheather["Discript"]?></font><br>(<?=$Location?>)</td>
                              <?
                              }
                              ?>

                              <td align="center"><?=$Room?></td>

                              <?
                              $SangFilmList = "" ;

                              $sQuery = "Select SangFilm                     ".
                                        "  From wrk_TrailerFilm              ".
                                        " Where SangFilm != ''               ".
                                        "   And Theather = '".$Theather."'   ".
                                        "   And Room     = '".$Room."'       ".
																												"   And WorkDate >= '".$WorkDate."' " ; //eq($sQuery);
                              $QrySangFilm = mysql_query_err($sQuery,$DBConn,__FILE__,__LINE__) ;
                              while ($ArySangFilm = mysql_fetch_array($QrySangFilm))
                              {
                                   $SangFilm = $ArySangFilm["SangFilm"] ;

                                   $SangFilmName = "" ;
                                   $sQuery = "Select Name                    ".
                                             "  From bas_sangfilmtitle       ".
                                             " Where Code = '".$SangFilm."'  " ;
                                   $QrySangFilmName = mysql_query_err($sQuery,$DBConn,__FILE__,__LINE__) ;
                                   if ($ArySangFilmName = mysql_fetch_array($QrySangFilmName))
                                   {
                                       if  ($SangFilm==$_SangFilm)
																																							{
																																							    $SangFilmName = "<B>".$ArySangFilmName["Name"]."</B>" ;
																																							}
																																							else
																																							{
																																							    $SangFilmName = $ArySangFilmName["Name"] ;
																																							}
                                   }

                                   if  ($SangFilmList != "")
                                   {
                                       $SangFilmList .= "," ;
                                   }
                                   $SangFilmList .= $SangFilmName ;
                              }
                              ?>
                              <td><?if ($ToExel) { echo "[";}  echo $SangFilmList ; if ($ToExel) { echo "]"; } ?></td>

                              <?
                              $TrailerFilmList = "" ;

                              $sQuery = "Select TrailerFilm                  ".
                                        "  From wrk_TrailerFilm              ".
                                        " Where TrailerFilm != ''            ".
                                        "   And Theather = '".$Theather."'   ".
                                        "   And Room     = '".$Room."'       ".
																												"   And WorkDate >= '".$WorkDate."' " ; //eq($sQuery );
                              $QryTrailerFilm = mysql_query_err($sQuery,$DBConn,__FILE__,__LINE__) ;
                              while ($AryTrailerFilm = mysql_fetch_array($QryTrailerFilm))
                              {
                                   $TrailerFilm = $AryTrailerFilm["TrailerFilm"] ;

                                   $TrailerFilmName = "" ;
                                   $sQuery = "Select Name                       ".
                                             "  From bas_sangfilmtitle            ".
                                             " Where Code = '".$TrailerFilm."'  " ;//eq($sQuery );
                                   $QryTrailerFilmName = mysql_query_err($sQuery,$DBConn,__FILE__,__LINE__) ;
                                   if ($AryTrailerFilmName = mysql_fetch_array($QryTrailerFilmName))
                                   {
                                       $TrailerFilmName = $AryTrailerFilmName["Name"] ;
                                   }

                                   if  ($TrailerFilmList != "")
                                   {
                                       $TrailerFilmList .= "," ;
                                   }
                                   $TrailerFilmList .= $TrailerFilmName ;
                              }
                              ?>
                              <td><?if ($ToExel) { echo "["; } echo $TrailerFilmList ; if ($ToExel) { echo "]"; }?></td>

                              <?
                              if  ($i==0)
                              {
                              $updir = "/usr/local/apache2/htdocs/cr/sunjaefile";
                              ?>

                              <td rowspan="<?=$CntRoom?>" align="center" valign="middle">
                              <?
                              $filename = sprintf("%s/%s",$updir,$_SangFilm.$Theather."0.jpg");
                              if  (file_exists($filename))
                              {
                              ?>
                              <table id="use-th">
                                 <tr>
                                     <td align="center"><? if ($ToExel) { echo "O"; } else {?><img  width="188" height="140" src="sunjaefile/<?=$_SangFilm?><?=$Theather?>0.jpg"><? }?></td>
                                 </tr>
                                 <tr>
                                     <td align="center" ><?=$CmtPoster?></td>
                                 </tr>
                                 <tr>
                                     <td align="center" ><?=$DatePoster?></td>
                                 </tr>
                             </table>
                              <?
                              }
                              ?>
                              </td>


                              <td rowspan="<?=$CntRoom?>" align="center" valign="middle">
                              <?
                              $filename = sprintf("%s/%s",$updir,$_SangFilm.$Theather."1.jpg");
                              if  (file_exists($filename))
                              {
                              ?>
                              <table id="use-th">
                                 <tr>
                                     <td align="center"><? if ($ToExel) { echo "O"; } else {?><img  width="188" height="140" src="sunjaefile/<?=$_SangFilm?><?=$Theather?>1.jpg"><? }?></td>
                                 </tr>
                                 <tr>
                                     <td align="center" ><?=$CmtBener?></td>
                                 </tr>
                                 <tr>
                                     <td align="center" ><?=$DateBener?></td>
                                 </tr>
                             </table>
                              <?
                              }
                              ?>
                              </td>


                              <td rowspan="<?=$CntRoom?>" align="center" valign="middle">
                              <?
                              $filename = sprintf("%s/%s",$updir,$_SangFilm.$Theather."2.jpg");
                              if  (file_exists($filename))
                              {
                              ?>
                              <table id="use-th">
                                 <tr>
                                     <td align="center"><? if ($ToExel) { echo "O"; } else {?><img  width="188" height="140" src="sunjaefile/<?=$_SangFilm?><?=$Theather?>2.jpg"><? }?></td>
                                 </tr>
                                 <tr>
                                     <td align="center" ><?=$CmtStand?></td>
                                 </tr>
                                 <tr>
                                     <td align="center" ><?=$DateStand?></td>
                                 </tr>
                             </table>
                              <?
                              }
                              ?>
                              </td>


                              <td rowspan="<?=$CntRoom?>" align="center" valign="middle">
                              <?
                              $filename = sprintf("%s/%s",$updir,$_SangFilm.$Theather."3.jpg");
                              if  (file_exists($filename))
                              {
                              ?>
                              <table id="use-th">
                                 <tr>
                                     <td align="center"><? if ($ToExel) { echo "O"; } else {?><img  width="188" height="140" src="sunjaefile/<?=$_SangFilm?><?=$Theather?>3.jpg"><? }?></td>
                                 </tr>
                                 <tr>
                                     <td align="center" ><?=$CmtWholePaper?></td>
                                 </tr>
                                 <tr>
                                     <td align="center" ><?=$DateWholePaper?></td>
                                 </tr>
                             </table>
                              <?
                              }
																										    ?>
                              </td>

                              <td rowspan="<?=$CntRoom?>" align="center" valign="middle">
                              <?
                              $filename = sprintf("%s/%s",$updir,$_SangFilm.$Theather."4.jpg");
                              if  (file_exists($filename))
                              {
                              ?>
                              <table id="use-th">
                                 <tr>
                                     <td align="center"><? if ($ToExel) { echo "O"; } else {?><img  width="188" height="140" src="sunjaefile/<?=$_SangFilm?><?=$Theather?>4.jpg"><? }?></td>
                                 </tr>
                                 <tr>
                                     <td align="center" ><?=$CmtStill?></td>
                                 </tr>
                                 <tr>
                                     <td align="center" ><?=$DatePoster?></td>
                                 </tr>
                             </table>
                              <?
                              }
                              ?>
                              </td>
                              <?
                              }
                              ?>
																									 </tr>
																									 <?
																									 $i ++ ;
																						}
																		}
													}

													if   ($ExistTrailerFilm == false)
													{
													     $sQuery = "Select count(*) As CntSunjae                    ".
																												"  From wrk_Sunjae       As Sunjae,              ".
																												"       bas_theather     As Theather             ".
																												" Where Sunjae.SangFilm     = '".$_SangFilm."'    ".
																												"  And  Sunjae.Theather     = Theather.Code      " ;
																		$QryCntSunjae = mysql_query_err($sQuery,$DBConn,__FILE__,__LINE__) ;
																		if  ($AryCntSunjae = mysql_fetch_array($QryCntSunjae))
																		{
																						if   ($AryCntSunjae["CntSunjae"] == 1)
																						{
																						    ?><td><?=$AryTheather["Discript"]?></td>
                          <?
																						}
																		}
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
