<?
    session_start();
    
    include "config.php";
    
    $connect = dbconn();
    mysql_select_db($cont_db) ; 
?>
<html>
      <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">


      <head>
            <script type="text/javascript" src="js/jquery-1.8.3.js"></script>
            <script language="javascript">
            <!--
                $(document).ready(function()
                {
                    //alert("aaaaa");
                });

                function ok(open,
                            code,
                            bChk35, 
                            bChk2,  
                            bChk20, 
                            bChk3,  
                            bChk30, 
                            bChk29, 
                            bChk39, 
                            bChk24, 
                            bChk34, 
                            bChk294,
                            bChk394,
                            bChk4)
                {
                    
                    $.post("wrk_filmsupply_Link_DnFilmTypeLimit_post.php", 
                       { 
                           open:open,     
                           code:code,     
                           bChk35:bChk35,   
                           bChk2:bChk2,   
                           bChk20:bChk20,  
                           bChk3:bChk3,   
                           bChk30:bChk30,  
                           bChk29:bChk29,  
                           bChk39:bChk39,  
                           bChk24:bChk24,  
                           bChk34:bChk34,  
                           bChk294:bChk294, 
                           bChk394:bChk394, 
                           bChk4:bChk4  
                       },
                       function(data)
                       {
                            alert("변경완료!") ;
                       });                    
                }
            //-->
            </script>
      </head>

      <body>
            <br>

            <form name="frmMain" method=post action="wrk_filmsupply_Link_DnFilmAdjust_Form.php">

                <table border="0" cellspacing="1" bgcolor="#3399CC"  align="center">

                   <?
                   $sQuery = "Select * From bas_filmtitle ".
                             " Order By Open  Desc        " ;
                   $QryFilmtitle = mysql_query($sQuery,$connect) ;
                   while ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
                   {
                        $i ++ ;
                        if ($i % 2==0) $bgcolor = "#ffffff"; else $bgcolor = "#f0f0f0";

                        $Open      = $ArrFilmtitle["Open"] ;
                        $Code      = $ArrFilmtitle["Code"] ;
                        $Name      = $ArrFilmtitle["Name"] ;
                        $SingoName = $ArrFilmtitle["SingoName"] ;
                        $AccName   = $ArrFilmtitle["AccName"] ;
                        $DgrName   = $ArrFilmtitle["DgrName"] ;
                        $DgrpName  = $ArrFilmtitle["DgrpName"] ;
                        $FtName    = $ArrFilmtitle["FtName"] ;
                        $FtpName   = $ArrFilmtitle["FtpName"] ;
                        $RoomOrder = $ArrFilmtitle["RoomOrder"] ;
                        $Extension = $ArrFilmtitle["Extension"] ;
                        $Finish    = $ArrFilmtitle["Finish"] ;

                        $sQuery = "Select * from bas_filmtitle_typelimit      ".
                                  " Where Open = '$Open'  And Code = '$Code'  " ;
                        $QryFilmtitleTL = mysql_query($sQuery,$connect) ;
                        $ArrFilmtitleTL = mysql_fetch_array($QryFilmtitleTL);
                        if (!$ArrFilmtitleTL)
                        {
                            $sQuery = "Insert Into bas_filmtitle_typelimit  (`Open`, `Code`)  ".
                                      " Values ( '$Open' , '$Code' )                          " ;
                            mysql_query($sQuery,$connect) ;
                        }

                        $sQuery = "Select * From bas_filmtitle_typelimit       ".
                                  " Where Open = '$Open' And  Code = '$Code'   " ;
                        $QryFilmtitleTL = mysql_query($sQuery,$connect) ;
                        if ($ArrFilmtitleTL = mysql_fetch_array($QryFilmtitleTL))
                        {
                            $bChk35  = $ArrFilmtitleTL['Type35']=='Y'?"checked":"" ;
                            $bChk2   = $ArrFilmtitleTL['Type2']=='Y'?"checked":"" ;
                            $bChk20  = $ArrFilmtitleTL['Type20']=='Y'?"checked":"" ;
                            $bChk3   = $ArrFilmtitleTL['Type3']=='Y'?"checked":"" ;
                            $bChk30  = $ArrFilmtitleTL['Type30']=='Y'?"checked":"" ;
                            $bChk29  = $ArrFilmtitleTL['Type29']=='Y'?"checked":"" ;
                            $bChk39  = $ArrFilmtitleTL['Type39']=='Y'?"checked":"" ;
                            $bChk24  = $ArrFilmtitleTL['Type24']=='Y'?"checked":"" ;
                            $bChk34  = $ArrFilmtitleTL['Type34']=='Y'?"checked":"" ;
                            $bChk294 = $ArrFilmtitleTL['Type294']=='Y'?"checked":"" ;
                            $bChk394 = $ArrFilmtitleTL['Type394']=='Y'?"checked":"" ;
                            $bChk4   = $ArrFilmtitleTL['Type4']=='Y'?"checked":"" ;
                        }
                        ?>
                        <tr bgcolor=<?=$bgcolor?>>
                             <td align="center">&nbsp;<?=$Open?> : <?=$Code?>&nbsp;</td>
                             <td align="center"><B><?=$Name?></B></td>
                             <td>&nbsp;
                                 <input name='chk35_<?=$Open.$Code?>' type='checkbox' value='35'  <?=$bChk35?>>35
                                 <input name='chk2_<?=$Open.$Code?>' type='checkbox' value='2'   <?=$bChk2?>>2
                                 <input name='chk20_<?=$Open.$Code?>' type='checkbox' value='20'  <?=$bChk20?>>20
                                 <input name='chk3_<?=$Open.$Code?>' type='checkbox' value='3'   <?=$bChk3?>>3
                                 <input name='chk30_<?=$Open.$Code?>' type='checkbox' value='30'  <?=$bChk30?>>30 
                                 <input name='chk29_<?=$Open.$Code?>' type='checkbox' value='29'  <?=$bChk29?>>29 
                                 <input name='chk39_<?=$Open.$Code?>' type='checkbox' value='39'  <?=$bChk39?>>39 
                                 <input name='chk24_<?=$Open.$Code?>' type='checkbox' value='24'  <?=$bChk29?>>24 
                                 <input name='chk34_<?=$Open.$Code?>' type='checkbox' value='34'  <?=$bChk34?>>34 
                                 <input name='chk294_<?=$Open.$Code?>' type='checkbox' value='294' <?=$bChk294?>>294
                                 <input name='chk394_<?=$Open.$Code?>' type='checkbox' value='394' <?=$bChk394?>>394
                                 <input name='chk4_<?=$Open.$Code?>' type='checkbox' value='4' <?=$bChk4?>>4
                                 &nbsp;
                                 <input type="button" 
                                        value="확인" 
                                        onclick="ok('<?=$Open?>','<?=$Code?>',
                                                    chk35_<?=$Open.$Code?>.checked,
                                                    chk2_<?=$Open.$Code?>.checked,
                                                    chk20_<?=$Open.$Code?>.checked,
                                                    chk3_<?=$Open.$Code?>.checked,
                                                    chk30_<?=$Open.$Code?>.checked,
                                                    chk29_<?=$Open.$Code?>.checked,
                                                    chk39_<?=$Open.$Code?>.checked,
                                                    chk24_<?=$Open.$Code?>.checked,
                                                    chk34_<?=$Open.$Code?>.checked,
                                                    chk294_<?=$Open.$Code?>.checked,
                                                    chk394_<?=$Open.$Code?>.checked,
                                                    chk4_<?=$Open.$Code?>.checked
                                                    )">
                                 &nbsp;
                             </td>
                        </tr>
                        <?
                   }
                   ?>

                </table>

            </form>


            <br>
            <br>
            <br>
      </body>

      <?
      mysql_close($connect);
      ?>
</html>
