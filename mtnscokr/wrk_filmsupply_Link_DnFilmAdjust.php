<?
    session_start();
?>
<html>
      <?
      $db1   = "mtns" ;
      $db2   = "mtnsback" ;

      $connect1 = mysql_connect( "localhost", "mtns",     "5421")  or  Error("DB 접속시 에러가 발생했습니다");
      $connect2 = mysql_connect( "localhost", "mtnsback", "5421")  or  Error("DB 접속시 에러가 발생했습니다");

      mysql_select_db($db1,  $connect1) ;
      mysql_select_db($db2,  $connect2) ;

      ////
      /// 해당 테이블의 레코드 갯수를 출력한다.. 단 테이블이 없으면 (X)
      //
      function PrnState($_Gbn,$_Cnt)
      {
		  if  ($_Gbn=="ORG") $color = "#dd1111" ; // 원본 DB 컬러
		  if  ($_Gbn=="BAK") $color = "#1111dd" ; // 사본 DB 컬러

		  if  ($_Cnt == -1)
          {
              $Result = "<font color='".$color."'>X</font>" ;
          }
          else
          {
              $Result = "<font color='".$color."'>".number_format($_Cnt)."</font>" ;
          }


          return $Result ;
      }

      ////
      /// 해당 테이블의 레코드 갯수를 구한다.. 단 테이블이 없으면 -1
      //
      function GetCount($_Table,$_connect,$_db)
      {
          $Result = -1 ;

          if  ($_Table!="")
          {
              $sQuery = "show tables where tables_in_".$_db." ='".$_Table."' " ;//echo $sQuery ;
              if ($QryCnt = mysql_query($sQuery,$_connect))
              {
                  if ($ArrCnt = mysql_fetch_array($QryCnt))
                  {
                      $sQuery = "Select Count(*) As Cnt From ".$_Table." " ;//echo $sQuery ;
                      $QryCnt = mysql_query($sQuery,$_connect) ;
                      if ($ArrCnt = mysql_fetch_array($QryCnt))
                      {
                          $Result = $ArrCnt["Cnt"] ;
                      }
                  }
              }
          }

          return $Result ;
      }

      ////
      /// 해당 테이블의 레코드 갯수를 구한다.. 테이블이 없을 수는 없고..  그테이블의 필름의 해당건수
      //
      function GetRowCount($_Table,$_FieldOpen,$_FieldFilm,$_Open,$_Film,$_connect,$_db)
      {
          $Result = -1 ;
          
          if  ($_Table!="")
          {
              $sQuery = "show tables where tables_in_".$_db." ='".$_Table."' " ; //echo $sQuery ;
              if ($QryCnt = mysql_query($sQuery,$_connect))
              {
                  if ($ArrCnt = mysql_fetch_array($QryCnt))
                  {
                      $sQuery = "Select Count(*) As Cnt 
                                   From ".$_Table." 
                                  Where $_FieldOpen = '".$_Open."'
                                    And $_FieldFilm = '".$_Film."'
                                " ;//echo $sQuery ;
                      $QryCnt = mysql_query($sQuery,$_connect) ;
                      if ($ArrCnt = mysql_fetch_array($QryCnt))
                      {
                          $Result = $ArrCnt["Cnt"] ;
                      }      
                  }
              }
          }
          return $Result ;
      }
      ?>
      <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">


      
      <?
      $page_num = 30 ;
      
      if  ( !$page ) { $page = 0; }
      $page_size = $page_num * $page;
      
      $sQuery = "Select count(*) 
                   From bas_filmtitle    
                " ;
      $count_search = mysql_query($sQuery,$connect1) ;
      $count_search_row = mysql_fetch_row($count_search);
      
      
      $page_1 = $count_search_row[0] / $page_num;
      $page_1 = intval($page_1);
      $page_2 = $count_search_row[0] % $page_num;
      
      if ( $page_2 > 0 ) { $page_1++; }
      $total_page = intval($page_1);
      $prev_page = $page - 1;
      $next_page = $page + 1;
      $now_page = $page + 1;
      
      if ( $page == 0 )
      {            
          $str_prev_page = "<input  type=button value=\"이전\" OnClick=\"javascript:alert('더이상 페이지가 없습니다.');\">";
      }
      else
      {
          $str_prev_page = "<input  type=button value=\"이전\" OnClick=\"move_prev(write.checkboxs);\">";
      }
      
      if ( $now_page == $total_page )
      {
         $str_next_page = "<input  type=button value=\"다음\" OnClick=\"javascript:alert('더이상 페이지가 없습니다.');\">";
      }
      else
      {
          $str_next_page = "<input  type=button value=\"다음\" OnClick=\"move_next(write.checkboxs);\">";
      } 
      ?>
      <head>
            <Script Language="JavaScript">
            <!--
                 function Select_Page()
                 {
                    frmMain.action = "<?=$PHP_SELF?>?page="+(frmMain.CurPage.value-1) ;
                    frmMain.submit() ;
                 }

                 function move_prev(chk)
                 {
                    frmMain.action = "<?=$PHP_SELF?>?page=<?=$prev_page?>" ;
                    frmMain.submit() ;
                 }

                 function move_next(chk)
                 {
                    frmMain.action = "<?=$PHP_SELF?>?page=<?=$next_page?>" ;
                    frmMain.submit() ;
                 }

                 //
                 // '추가'을 눌렸을때..
                 //
                 function btnAddition_OnClick()
                 {
                     var sError = "" ; // 에러메시지

                     if  (frmMain.txtOpen.value=="")
                     {
                          sError += "상영일이 없읍니다." + "\n";
                     }
                     else
                     {
                         if  (frmMain.txtOpen.value.length != 6)
                         {
                             sError = sError + "상영일은 6자리이어야 합니다.\n" ;
                         }
                     }

                     if  (frmMain.txtCode1.value=="")
                     {
                          sError += "코드1가 없읍니다." + "\n";
                     }
                     else
                     {
                         if  (frmMain.txtCode1.value.length != 2)
                         {
                             sError = sError + "코드1는 2자리이어야 합니다.\n" ;
                         }
                     }


                     if  (frmMain.txtName1.value=="")
                     {
                          sError += "이름1이 없읍니다." + "\n";
                     }

                     if  ((frmMain.txtCode2.value!="") || (frmMain.txtName2.value!=""))
                     {
                         if  (frmMain.txtCode2.value=="")
                         {
                              sError += "코드2가 없읍니다." + "\n";
                         }
                         else
                         {
                             if  (frmMain.txtCode2.value.length != 2)
                             {
                                 sError = sError + "코드2는 2자리이어야 합니다.\n" ;
                             }
                         }


                         if  (frmMain.txtName2.value=="")
                         {
                              sError += "이름2이 없읍니다." + "\n";
                         }
                     }

                     if  ((frmMain.txtCode3.value!="") || (frmMain.txtName3.value!=""))
                     {
                         if  (frmMain.txtCode3.value=="")
                         {
                              sError += "코드3가 없읍니다." + "\n";
                         }
                         else
                         {
                             if  (frmMain.txtCode3.value.length != 2)
                             {
                                 sError = sError + "코드3는 2자리이어야 합니다.\n" ;
                             }
                         }


                         if  (frmMain.txtName3.value=="")
                         {
                              sError += "이름3이 없읍니다." + "\n";
                         }
                     }

                     if  ((frmMain.txtCode4.value!="") || (frmMain.txtName4.value!=""))
                     {
                         if  (frmMain.txtCode4.value=="")
                         {
                              sError += "코드4가 없읍니다." + "\n";
                         }
                         else
                         {
                             if  (frmMain.txtCode4.value.length != 2)
                             {
                                 sError = sError + "코드4는 2자리이어야 합니다.\n" ;
                             }
                         }


                         if  (frmMain.txtName4.value=="")
                         {
                              sError += "이름4이 없읍니다." + "\n";
                         }
                     }

                     if  ((frmMain.txtCode5.value!="") || (frmMain.txtName5.value!=""))
                     {
                         if  (frmMain.txtCode5.value=="")
                         {
                              sError += "코드5가 없읍니다." + "\n";
                         }
                         else
                         {
                             if  (frmMain.txtCode5.value.length != 2)
                             {
                                 sError = sError + "코드5는 2자리이어야 합니다.\n" ;
                             }
                         }


                         if  (frmMain.txtName5.value=="")
                         {
                              sError += "이름5이 없읍니다." + "\n";
                         }
                     }

                     if  (sError != "")
                     {
                          alert(sError) ;
                     }
                     else
                     {
                         frmMain.hidBack.value="<?=$PHP_SELF?>" ;
                         frmMain.hidType.value="AdditionFilmTitle" ;
                         frmMain.submit() ;
                     }
                 }


                 ////
                 /// 복원/백업
                 //
                 function BackupRestore_OnClick(Gubun,Open,Code)
                 {
                    frmMain.hidOpen.value=Open ;
                    frmMain.hidCode.value=Code ;
                    frmMain.hidBack.value="<?=$PHP_SELF?>?page=<?=$page?>" ;
                    frmMain.hidType.value=Gubun ;
                    frmMain.submit() ;
                 }

                 ////
                 /// 종영상영
                 //
                 function OpenClose_OnClick(Open,Code)
                 {
                    frmMain.hidOpen.value=Open ;
                    frmMain.hidCode.value=Code ;
                    frmMain.hidBack.value="<?=$PHP_SELF?>?page=<?=$page?>" ;
                    frmMain.hidType.value="OpenClose" ;
                    frmMain.submit() ;
                 }
            //-->
            </script>
      </head>

      <body>

            <br>
            <br>
            <br>

            <form name="frmMain" method=post action="wrk_filmsupply_Link_DnFilmAdjust_Form.php">

            <input type="hidden" name="hidBack">
            <input type="hidden" name="hidOpen">
            <input type="hidden" name="hidCode">
            <input type="hidden" name="hidType">

                <table border="0" cellspacing="1" bgcolor="#3399CC"  align="center">

                   <tr bgcolor="#ffffff">
                           <td colspan=2  align="center">새영화</td>
                           <td colspan=7>
                                <table border=0>
                                <tr>
                                 <td><input type="text" name="txtOpen" size="6" maxlength="6"></td>
                                 <td>/</td>
                                 <td><input type="text" name="txtCode1" size="2" maxlength="2"></td>
                                 <td>:</td>
                                 <td><input type="text" name="txtName1" size="40"" maxlength="100"></td>

                                 <td ><input type="button" name="btnAddition" value="추가" onclick="btnAddition_OnClick();"></td>
                                </tr>
                                <tr>
                                 <td colspan=3 align=right>ExcelTitle</td>
                                 <td>:</td>
                                 <td><input type="text" name="txtExcelTitle" size="20"" maxlength="20"></td>

                                 <td></td>
                                </tr>
                                <tr>
                                 <td></td>
                                 <td>/</td>
                                 <td><input type="text" name="txtCode2" size="2" maxlength="2"></td>
                                 <td>:</td>
                                 <td><input type="text" name="txtName2" size="40"" maxlength="100"></td>
                                 <td></td>
                                </tr>
                                <tr>
                                 <td></td>
                                 <td>/</td>
                                 <td><input type="text" name="txtCode3" size="2" maxlength="2"></td>
                                 <td>:</td>
                                 <td><input type="text" name="txtName3" size="40"" maxlength="100"></td>
                                 <td></td>
                                </tr>
                                <tr>
                                 <td></td>
                                 <td>/</td>
                                 <td><input type="text" name="txtCode4" size="2" maxlength="2"></td>
                                 <td>:</td>
                                 <td><input type="text" name="txtName4" size="40"" maxlength="100"></td>
                                 <td></td>
                                </tr>
                                <tr>
                                 <td></td>
                                 <td>/</td>
                                 <td><input type="text" name="txtCode5" size="2" maxlength="2"></td>
                                 <td>:</td>
                                 <td><input type="text" name="txtName5" size="40"" maxlength="100"></td>
                                 <td></td>
                                </tr>
                                </table>
                           </td>
                   </tr>


                   <tr bgcolor="#ffffff"><td colspan=9></td></tr>

                   <tr bgcolor="#ffffff" height="50">
                           <td colspan="9" align="right">                               
                                 
								 <font color='#dd1111'>■</font>원본DB <font color='#1111dd'>■</font>사본DB

                                <a><?=$str_prev_page?></a>
                       
                                <!--[<a><?=$now_page?></a>/<?=$total_page?>]-->
                                
                                [<select name=CurPage onchange='Select_Page();'>
                                    <?
                                    for  ($i = 1 ; $i <= $total_page ; $i++)
                                    {
                                      if  ($i == $now_page)
                                      {
                                      ?>
                                         <option selected value=<?=$i?>><?=$i?></option>
                                      <?
                                      } 
                                      else  
                                      {
                                      ?>
                                         <option value=<?=$i?>><?=$i?></option>
                                      <?
                                      }
                                    }
                                    ?>
                                </select>/<?=$total_page?>]
                                
                                <a><?=$str_next_page?></a>

                                &nbsp;&nbsp;

                            </td>
                   </tr>
                   <tr bgcolor="#dfdfdf">
                           <td colspan=2 align="center">코드</td>
                           <td rowspan=7 align="center">이름</td>
                           <td colspan=3 align="center">신고테이블</td>
                           <td colspan=3 align="center">bas_silmoojatheather</td>
                   </tr>                   
                   <tr bgcolor="#dfdfdf">
                           <td colspan=2 align="center">종영/Extention</td>
                           <!-- 이름 -->
                           <td colspan=3 align="center">신고누적정보</td>
                           <td colspan=3 align="center">bas_silmoojatheatherpriv</td>
                   </tr>
                   <tr bgcolor="#dfdfdf">
                           <td rowspan=5 colspan=2></td><!-- 코드,이름 -->
                           <td colspan=3 align="center">회차정보</td>
                           <td colspan=3 align="center">bas_degree</td>
                   </tr>
                   <tr bgcolor="#dfdfdf">
                           <!-- 코드,이름 -->
                           <td colspan=3 align="center">회차정보(일자별)</td>
                           <td colspan=3 align="center">bas_degreepriv</td>
                   </tr>
                   <tr bgcolor="#dfdfdf">
                           <!-- 코드,이름 -->
                           <td colspan=3 align="center">상영관순서정보</td>
                           <td colspan=3 align="center">&nbsp;</td>
                   </tr>
                   <tr bgcolor="#dfdfdf">
                           <!-- 코드,이름 -->
                           <td colspan=3 align="center">필름종류</td>
                           <td colspan=3 align="center">&nbsp;</td>
                   </tr>
                   <tr bgcolor="#dfdfdf">
                           <!-- 코드,이름 -->
                           <td colspan=3 align="center">필름종류(일자별)</td>
                           <td colspan=3 align="center">&nbsp;</td>
                   </tr>

                    <?
                    /*
                    bas_filmsupplytitle
                    bas_filmsupplytitlesilmooja
                    bas_filmtitle_typelimit
                    bas_filmtitlesilmooja
                    bas_modifyscore
                    bas_silmoojafilm
                    bas_silmoojafilmpriv
                    bas_silmoojatheatherfinish
                    bas_silmoojatheatherpriv
                    bas_theather_rate
                    bas_unitpricespriv
                    chk_extension_day
                    chk_extension_month
                    wrk_changroom
                    wrk_magam
                    wrk_showroombigo
                    wrk_silmoosiljuk
                    wrk_smschk
                    wrk_smsreq
                    wrk_digital_account
                    */                    

                    $sQuery = "Select *                
                                 From bas_filmtitle      
                                Order By Open
                                limit $page_size,$page_num      
                               " ;
                    $QryFilmtitle = mysql_query($sQuery,$connect1) ;
                    while ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
                    {
                        $i ++ ;
                        if ($i % 2==0) $bgcolor = "#ffffff"; else $bgcolor = "#f0f0f0";
                    
                        $Open      = $ArrFilmtitle["Open"] ;
                        $Film      = $ArrFilmtitle["Code"] ;
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
                        ?>
                        <tr bgcolor=<?=$bgcolor?>>
                            <td colspan=2 align="center"><?=$Open?> / <?=$Film?></td>
                            <td rowspan=7 align="center"><B><?=$Name?></B></td>
                            <td><?=$SingoName?></td>
                            <td><?=PrnState("ORG",GetCount($SingoName,$connect1,$db1))?></td>
                            <td><?=PrnState("BAK",GetCount($SingoName,$connect2,$db2))?></td>
                            <? $tablename = "bas_silmoojatheather" ; ?>
                            <td><?=$tablename?></td>
                            <td><?=PrnState("ORG",GetRowCount($tablename,"Open","Film",$Open,$Film,$connect1,$db1))?></td>
                            <td><?=PrnState("BAK",GetRowCount($tablename,"Open","Film",$Open,$Film,$connect2,$db2))?></td>
                        </tr>
                        <tr bgcolor=<?=$bgcolor?>>
                            <td colspan=2 align="center">종영:<?=$Finish?> / Ext:<?=$Extension?></td>
                            <!-- 이름 -->
                            <td><?=$AccName?></td>
                            <td><?=PrnState("ORG",GetCount($AccName,$connect1,$db1))?></td>
                            <td><?=PrnState("BAK",GetCount($AccName,$connect2,$db2))?></td>
                            <? $tablename = "bas_silmoojatheatherpriv" ; ?>
                            <td><?=$tablename?></td>
                            <td><?=PrnState("ORG",GetRowCount($tablename,"Open","Film",$Open,$Film,$connect1,$db1))?></td>
                            <td><?=PrnState("BAK",GetRowCount($tablename,"Open","Film",$Open,$Film,$connect2,$db2))?></td>
                        </tr>
                        <tr bgcolor=<?=$bgcolor?>>
                            <td align="center"><input type="button" value="복원" onclick="BackupRestore_OnClick('Restore','<?=$Open?>','<?=$Film?>');"></td>
                            <td align="center"><input type="button" value="백업" onclick="BackupRestore_OnClick('Backup','<?=$Open?>','<?=$Film?>');"></td>
                    
                            <td><?=$DgrName?></td>
                            <td><?=PrnState("ORG",GetCount($DgrName,$connect1,$db1))?></td>
                            <td><?=PrnState("BAK",GetCount($DgrName,$connect2,$db2))?></td>                           
                            <? $tablename = "bas_degree" ; ?>
                            <td><?=$tablename?></td>
                            <td><?=PrnState("ORG",GetRowCount($tablename,"Open","Film",$Open,$Film,$connect1,$db1))?></td>
                            <td><?=PrnState("BAK",GetRowCount($tablename,"Open","Film",$Open,$Film,$connect2,$db2))?></td>
                        </tr>
                        <tr bgcolor=<?=$bgcolor?>>
                            <!-- 코드,이름 -->
                            <td rowspan=4 colspan=2 align="center"><input type="button" value="종영/상영" onclick="OpenClose_OnClick('<?=$Open?>','<?=$Film?>');"></td>
                            <td><?=$DgrpName?></td>
                            <td><?=PrnState("ORG",GetCount($DgrpName,$connect1,$db1))?></td>
                            <td><?=PrnState("BAK",GetCount($DgrpName,$connect2,$db2))?></td>                           
                            <? $tablename = "bas_degreepriv" ; ?>
                            <td><?=$tablename?></td>
                            <td><?=PrnState("ORG",GetRowCount($tablename,"Open","Film",$Open,$Film,$connect1,$db1))?></td>
                            <td><?=PrnState("BAK",GetRowCount($tablename,"Open","Film",$Open,$Film,$connect2,$db2))?></td>
                        </tr>
                        <tr bgcolor=<?=$bgcolor?>>
                            <!-- 코드,이름 -->
                            <td><?=$RoomOrder?></td>
                            <td><?=PrnState("ORG",GetCount($RoomOrder,$connect1,$db1))?></td>
                            <td><?=PrnState("BAK",GetCount($RoomOrder,$connect2,$db2))?></td>
                            <td colspan=3 >&nbsp;</td>
                        </tr>
                        <tr bgcolor=<?=$bgcolor?>>
                            <!-- 코드,이름 -->
                            <td><?=$FtName?></td>
                            <td><?=PrnState("ORG",GetCount($FtName,$connect1,$db1))?></td>
                            <td><?=PrnState("BAK",GetCount($FtName,$connect2,$db2))?></td>
                            <td colspan=3 >&nbsp;</td>
                        </tr>
                        <tr bgcolor=<?=$bgcolor?>>
                            <!-- 코드,이름 -->
                            <td><?=$FtpName?></td>
                            <td><?=PrnState("ORG",GetCount($FtpName,$connect1,$db1))?></td>
                            <td><?=PrnState("BAK",GetCount($FtpName,$connect2,$db2))?></td>
                            <td colspan=3 >&nbsp;</td>
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
      mysql_close($connect1);
      mysql_close($connect2);
      ?>
</html>
