   
   <?   
   for ($i = 1 ; $i <= 15 ; $i++)
   {
       $arryTotSumOfDegree[$i] = 0 ;  // ȸ���� ���ھ� �հ�
   }

   $PutAct = False ;

   while ($singo_data = mysql_fetch_array($qry_singo))
   {
        $PutAct = True ; // �Ѱ��� �ڷ�� �ִٸ�...  �հ踦 ��´�..
        
        for ($i = 1 ; $i <= 12 ; $i++)
        {
            $arrySumOfDegree[$i] = 0 ;  // ȸ���� ���ھ� �հ�
        }

        $singoSilmooja    = $singo_data["Silmooja"] ;      // �Ű�ǹ���
        $singoTheather    = $singo_data["Theather"] ;      // �Ű�󿵰�
        $singoRoom        = $singo_data["Room"] ;          //
        $singoOpen        = $singo_data["Open"] ;          // �Ű�ȭ
        $singoFilm        = $singo_data["Film"] ;          //
        $silmoojaName     = $singo_data["SilmoojaName"] ;  // �Ű� �ǹ��ڸ�  
        $showroomDiscript = $singo_data["Discript"] ;      // �Ű� �󿵰���  
        $showroomLocation = $singo_data["Location"] ;      // �Ű� �󿵰�����
        $locationName     = $singo_data["LocationName"] ;  // �Ű� �󿵰�������
        $showroomSeat     = $singo_data["ShowRoomSeat"] ;  // �Ű� �󿵰��¼�            
        $SumNumPersons    = $singo_data["SumNumPersons"] ; // �� ���ھ�
        $showroomCntDgree = $singo_data["CntDgree"] ;      // ��ȸ����
        $cntRoom          = $singo_data["cntRoom"] ;       //

        
        // �󿵰� �� �ø����� ���Ѵ�..
        $nTotRow = 0 ;
        
        $sQuery = "Select distinct(Room) From ".$SingoName." ".
                  " Where SingoDate = '".$WorkDate."'        ".
                  "   And Theather  = '".$singoTheather."'   " ;
        $qry_Rooms = mysql_query($sQuery,$connect) ;
        while ($Rooms_data = mysql_fetch_array($qry_Rooms))
        {              
             $RoomNo = $Rooms_data["Room"] ;

             $sQuery = "Select count(distinct(UnitPrice)) As CntUP  ".
                       "  From ".$SingoName."                       ".
                       " Where SingoDate  = '".$WorkDate."'         ".
                       "   And Room       = '".$RoomNo."'           ".
                       "   And Theather   = '".$singoTheather."'    " ;
             $qry_CntUP = mysql_query($sQuery,$connect) ;
             if  ($CntUP_data = mysql_fetch_array($qry_CntUP))
             {
                 $nTotRow = $nTotRow + $CntUP_data["CntUP"] ;
                 $nTotRow = $nTotRow + 1 ; /////////////////////////////////////////
             }             
        }
        if  ($nTotRow == 0)
        {
            $nTotRow = 1 ;
        }



        // ��ȭ ������ ���ϵ� ��ȭ�� �ٲ�� �������� �����ϰ�
        // �ι��̻� �ݺ��Ǹ� ��ȭ���� �����.
        if  ($filmtitleNameTitle != $singo_data["FilmTitleName"])
        {
            $filmtitleName      = $singo_data["FilmTitleName"] ;
            $filmtitleNameTitle = $singo_data["FilmTitleName"] ;
        }
        else
        {
            $filmtitleName = "" ;
        }



        if  ($oldsingoTheather != $singoTheather)
        {
            $clrToggle = !$clrToggle ;

            $oldsingoTheather = $singoTheather ;
        }

        if  ($ToExel)// && ($filmsupplyCode=="20003")  // �������ͳ׼ų� 
        {
            $Color1 = "#ffffff" ;
            $Color2 = "#ffffff" ;
            $Color3 = "#ffffff" ;
            $Color4 = "#ffffff" ;
        }
        else
        {
            if  ($clrToggle==true)
            {
                $Color1 = "#ffccff" ;
                $Color2 = "#f1ebed" ;
                $Color3 = "#ffffff" ;
                $Color4 = "#66ccfe" ;
            }
            else
            {
                $Color1 = "#ffccff" ;
                $Color2 = "#f1ebed" ;
                $Color3 = "#ffffff" ;
                $Color4 = "#66ccfe" ;
            }
        }


        // ��ȭ���� ��� (��ȭ�Ǵ� ��������,)..
        if  ($PutTitle==True)
        {               
            ?>
            <table name=score cellpadding=0 cellspacing=0 border=1 bordercolor="#FFFFFF" width=100%>
            <tr>
            
            <td align=left class=textare>
            <?         
            $WorkTime = mktime(0,0,0,substr($WorkDate,4,2),substr($WorkDate,6,2),substr($WorkDate,0,4));
            $OpenTime = mktime(0,0,0,substr($singoOpen,2,2),substr($singoOpen,4,2),"20".substr($singoOpen,0,2));
            $dur_day  = ($WorkTime - $OpenTime) / 86400  ;
            ?>           
            <font size=2>
            <B>����:<?=$filmtitleName?></B>
            ��������:<?=substr($WorkDate,2,2)."�� ".substr($WorkDate,4,2)."�� ".substr($WorkDate,6,2)."�� "?>
            (���� <?=substr($singoOpen,0,2)."�� ".substr($singoOpen,2,2)."�� ".substr($singoOpen,4,2)."�� "?>)
            (�� <?=($dur_day+1)?>��°)
            </font>
            </td>
            
            </tr>
            </table>            


            <br>        
            <table style='table-layout:fixed' name=score cellpadding=0 cellspacing=0 border=1 bordercolor=<?=$Color4?> style="border-collapse:collapse">
            <tr height=50>
                  <!--             -->
                  <!-- Ÿ��Ʋ ��� -->
                  <!--             -->
                  <td class=textarea width=260 colspan=2 bgcolor=<?=$Color4?> align=center>
                  <b>SCREEN</b>
                  </td>

                  <td class=textarea width=55 bgcolor=<?=$Color4?> align=center>
                  <b>���</b>
                  </td>

                  <td class=textarea width=55 bgcolor=<?=$Color4?> align=center>
                  <b>1ȸ</b>
                  </td>

                  <td class=textarea width=45 bgcolor=<?=$Color4?> align=center>
                  <b>2ȸ</b>
                  </td>

                  <td class=textarea width=50 bgcolor=<?=$Color4?> align=center>
                  <b>3ȸ</b>
                  </td>

                  <td class=textarea width=50 bgcolor=<?=$Color4?> align=center>
                  <b>4ȸ</b>
                  </td>

                  <td class=textarea width=50 bgcolor=<?=$Color4?> align=center>
                  <b>5ȸ</b>
                  </td>

                  <td class=textarea width=50 bgcolor=<?=$Color4?> align=center>
                  <b>6ȸ</b>
                  </td>

                  <td class=textarea width=45 bgcolor=<?=$Color4?> align=center>
                  <b>7ȸ</b>
                  </td>

                  <td class=textarea width=45 bgcolor=<?=$Color4?> align=center>
                  <b>8ȸ</b>
                  </td>

                  <td class=textarea width=45 bgcolor=<?=$Color4?> align=center>
                  <b>9ȸ</b>
                  </td>

                  <td class=textarea width=45 bgcolor=<?=$Color4?> align=center>
                  <b>10ȸ</b>
                  </td>

                  <td class=textarea width=45 bgcolor=<?=$Color4?> align=center>
                  <b>�ɾ�</b>
                  </td>

                  <!-- ���� �հ� -->
                  <td class=textarea width=60 bgcolor=<?=$Color4?> align=center>
                  <b>&nbsp;����&nbsp;<br>&nbsp;�հ�&nbsp;</b>
                  </td>
                  
                  <!-- ���� �հ� -->
                  <td class=textarea width=60 bgcolor=<?=$Color4?> align=center>
                  <b>&nbsp;����&nbsp;<br>&nbsp;�հ�&nbsp;</b>
                  </td>
                  
                  <!-- ���� -->
                  <td class=textarea width=70 bgcolor=<?=$Color4?> align=center>
                  <b>&nbsp;����&nbsp;</b>
                  </td>                  
            </tr>
            <?
            $PutTitle=False ;
        }          
        
        $SumOfPsToday  = 0 ; // ���� �հ� �հ�

        $isFinishBlock = false ;

        // ������ ���뿪�� ���Ѵ�.
        $sQuery = "Select distinct Room, UnitPrice            ".
                  "  From ".$SingoName."                      ".
                  " Where SingoDate  = '".$WorkDate."'        ".
                  "   And Theather   = '".$singoTheather."'   ".
                  "   And Open       = '".$singoOpen."'       ".
                  "   And Film       = '".$singoFilm."'       ".
                  " Order By Room, UnitPrice Desc             " ;
        $qry_singo3 = mysql_query($sQuery,$connect) ;
        $bFirstLeftTitle = True ;
        
        while  ($UnitPrice_data = mysql_fetch_array($qry_singo3))
        {
             $SumOfToday = 0 ;

             $singoRoom  = $UnitPrice_data["Room"] ; 
             $singoPrice = $UnitPrice_data["UnitPrice"] ; 

             $sQuery = "Select Seat  From bas_seat                        ".
                       " Where Theather = '".$singoTheather."'            ".
                       "   And Room     = '".sprintf("%d",$singoRoom)."'  " ;
             $qry_showroom = mysql_query($sQuery,$connect) ;

             if  ($showroom_data = mysql_fetch_array($qry_showroom))
             {
                  $showroomSeat = $showroom_data["Seat"] ;
             }
             else
             {
                  $showroomSeat = 0 ;
             }
             ?>

             
             
             <!--             -->
             <!-- ����Ÿ ��� -->
             <!--             -->
             <tr> 
                  <?
                  if  ($bFirstLeftTitle == True) 
                  {                           
                      ?>             
                      <!-- �󿵰����� �󿵰���,�¼���,������ -->
                      <td class=textarea bgcolor=<?=$Color2?> valign=center rowspan=<?=$nTotRow + 1?>>
                          <font color=#f444e1><B>&nbsp;<?=$showroomDiscript?></B></font> <!-- �󿵰���(<?=$singoTheather?>) -->
                          <?
                          if  ($logged_UserId == "been")
                           {
                              echo "<br>".$singoTheather;
                           }
                          ?>
                      </td>
                      <?
                      

                      $singoOldRoom   = "" ;
                  } 

                  if  ($singoOldRoom !=  $singoRoom) // �󿵰� ����ȣ ..
                  {
                      $bFirstLeftRoom = True ;
                      $singoOldRoom   = $singoRoom ;
                  }
                  else
                  {
                      $bFirstLeftRoom = False ;
                  }

                  if  ($bFirstLeftRoom == True) 
                  {
                      $CntUP = 1 ;
                      $sQuery = "Select count(distinct(UnitPrice)) As CntUP   ".
                                "  From ".$SingoName."                        ".
                                " Where SingoDate = '".$WorkDate."'           ".
                                "   And Theather = '".$singoTheather."'       ".
                                "   And Room     = '".$singoRoom."'           " ;
                      $qry_CntUP = mysql_query($sQuery,$connect) ;
                      if  ($CntUP_data = mysql_fetch_array($qry_CntUP))
                      {
                          $CntUP = $CntUP_data["CntUP"] ;
                          $CntUP = $CntUP + 1 ;
                      }                      
                      ?>
                      
                      <td class=textarea bgcolor=<?=$Color3?> rowspan=<?=$CntUP?> class=tblsum align=center>
                      <?
                      if  ($logged_UserId == "been")
                      {
                      ?>
                          <?=number_format($singoRoom)?>�� <B><?=$showroomSeat?>��</B> <br><br>
                          <a href="#" onclick="delect_click('<?=$singoSilmooja?>','<?=$singoTheather.$singoRoom?>','<?=$FilmTile?>');">[����]</a>
                          <?=$silmoojaName?>
                          <?
                          if  ($singoSilmooja=="666666") 
                          {
                              ?>
                              <a href="#" onclick="modify_click('<?=$singoSilmooja?>','<?=$singoTheather.$singoRoom?>','<?=$FilmTile?>');">[����]</a>
                              <?
                          }
                          ?>
                      <?
                      }
                      else
                      {
                      ?>                 
                          <?=number_format($singoRoom)?>�� <br>
                          <B><?=$showroomSeat?>��</B> 
                      <?
                      }
                      ?>
                      </td>

                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center></td>
                      
                      <?                      
                      $bFirstLeftRoom = False ;                      
                  }
                  ?>

                  <td class=textarea bgcolor=<?=$Color3?> class=tblsum align=center>
                  <B><?=number_format($singoPrice)?></B>
                  </td>

                  <?
                  $sQuery = "Select * From bas_theatherfinish        ".
                            " Where Theather = '".$singoTheather."'  ".
                            "   And Open     = '".$singoOpen."'      ".
                            "   And Film     = '".$singoFilm."'      ".
                            "   And WorkDate < '".$WorkDate."'       " ;
                  $qry_Theatherfinish  = mysql_query($sQuery,$connect) ; 
             
                  if  ($Theatherfinish_data = mysql_fetch_array($qry_Theatherfinish))
                  {
                      $isFinished = true ;

                      $FinishDate = $Theatherfinish_data["WorkDate"] ;
                  }
                  else
                  {
                      $isFinished = false ;
                  }

                  if  ($isFinished == true)
                  {                 
                      if  ($isFinishBlock == false)
                      {                      
                          $cntUnitPriceP1 = $CntUP+1 ;

                          ?>
                          <td class=textarea bgcolor=<?=$Color3?> colspan=11 rowspan=<?=$cntUnitPriceP1?> align=center valign=middle>
                          <font color=red><B>����(<?=substr($FinishDate,2,2)?>/<?=substr($FinishDate,4,2)?>/<?=substr($FinishDate,6,2)?>)ó����</B></font>
                          </td>

                          <?
                      }
                      $isFinishBlock = true ;
                  }
                  else
                  {     
                      $sQuery = "Select ShowDgree,                           ".
                                "       SUM(NumPersons) As SumOfNumPersons   ".
                                "  From ".$SingoName."                       ".
                                " Where SingoDate  = '".$WorkDate."'         ".
                                "   And Silmooja   = '".$singoSilmooja."'    ".
                                "   And Theather   = '".$singoTheather."'    ".
                                "   And Room       = '".$singoRoom."'        ".
                                "   And Open       = '".$singoOpen."'        ".
                                "   And Film       = '".$singoFilm."'        ".
                                "   And UnitPrice  = '".$singoPrice."'       ".
                                " Group By ShowDgree                         ".
                                " Order By ShowDgree                         " ;
                      $qry_singo2 = mysql_query($sQuery,$connect) ;
                     
                      $agree = true ;
                      for ($i = 1 ; $i <= 11 ; $i++)
                      {
                          if  ($agree==true) // ��ġ�ϴ� �ڷᰡ ���� ��� �� �� ���ڵ徿 �д´�.
                          {
                              $NumPersons_data = mysql_fetch_array($qry_singo2) ;
                          }

                          if  ($i<11) // 1ȸ ���� 10ȸ ����..
                          {
                              if  ($NumPersons_data["ShowDgree"] == sprintf("%02d",$i)) 
                              {
                                  $SumOfDegree = "SumOf".sprintf("%02d",$i)."Degree" ;

                                  $arrySumOfDegree[$i] += $NumPersons_data["SumOfNumPersons"] ; // ȸ���� ���ھ� �հ�
                                  
                                  $SumOfToday   += $NumPersons_data["SumOfNumPersons"] ;
                                  $$SumOfDegree += $NumPersons_data["SumOfNumPersons"] ;
                                  ?>

                                  <td class=textarea bgcolor=<?=$Color3?> align=right>
                                  <?=$NBSP?><?=$NumPersons_data["SumOfNumPersons"]?><?=$NBSP?>                             
                                  </td>
                                  
                                  <?
                                  
                                  $agree = true ;
                              }
                              else
                              {
                                  ?>
                                  <td class=textarea bgcolor=<?=$Color3?> align=center>&nbsp;</td>
                                  <?

                                  $agree = false ;
                              }
                          }
                          else // �ɾ�
                          {
                              if  ($NumPersons_data["ShowDgree"] == "99") 
                              {
                                  $arrySumOfDegree[$i] += $NumPersons_data["SumOfNumPersons"] ; // ȸ���� ���ھ� �հ�
                                  
                                  $SumOfToday    += $NumPersons_data["SumOfNumPersons"] ;
                                  ?>
                                  
                                  <td class=textarea bgcolor=<?=$Color3?> align=right>
                                  <?=$NBSP?><?=$NumPersons_data["SumOfNumPersons"]?><?=$NBSP?>
                                  </td>

                                  <?
                                  
                                  $agree = true ;
                              }
                              else
                              {
                                  ?>
                                  <td class=textarea bgcolor=<?=$Color3?> align=center>&nbsp;</td>
                                  <?
                                  
                                  $agree = false ;
                              }
                          }
                      }
                  }
                                                 
                  // �����հ� ���
                  $SumOfPsToday += $SumOfToday ; // ���� �հ� �հ�

                  ?>
                  <td class=textarea bgcolor=<?=$Color3?> align=right>
                  <b><?=$NBSP?><?=number_format($SumOfToday)?><?=$NBSP?></b>
                  </td>
                  <?

                  $AgoModifyScore = 0 ;
                  
                  // �����հ�
                  if   ($singoRoom=='0')
                  {
                      $CondRoom = "" ;
                  }
                  else
                  {
                      $CondRoom = " And Room = '".$singoRoom."' " ;
                  }

                  $sQuery = "Select Sum(NumPersons) As SumNumPersons  ".
                            "  From ".$SingoName."                    ".
                            " Where SingoDate  = '".$AgoDate."'       ".
                            "   And Theather   = '".$singoTheather."' ".
                            $CondRoom                                  .
                            "   And Open       = '".$singoOpen."'     ".
                            "   And Film       = '".$singoFilm."'     " ;
                  $qry_singo2 = mysql_query($sQuery,$connect) ;

                  $NumPersons_data = mysql_fetch_array($qry_singo2) ;
                  if  ($NumPersons_data)
                  {
                      //$SumOfPsAgoDay += ($NumPersons_data["SumNumPersons"]+$AgoModifyScore) ; // ���� �հ� �հ�

                      // �����հ� ���
                      ?>
                      <td class=textarea bgcolor=<?=$Color3?> align=right>
                      <b><?=$NBSP?><?=number_format($NumPersons_data["SumNumPersons"]+$AgoModifyScore)?><?=$NBSP?></b>
                      </td>
                      <?
                  }
                  else
                  {
                      // �����հ� ���
                      ?>
                      <td class=textarea bgcolor=<?=$Color3?> align=center>&nbsp;</td>
                      <?
                  }


                  $FilmTileOpen = substr($FilmTile,0,6) ;
                  $FilmTileFilm = substr($FilmTile,6,2) ;

                  if   ($FilmTileFilm=='00')
                  {
                       $CondOpenFilm = " And Open = '".$FilmTileOpen."' " ;
                  }
                  else
                  {
                       $CondOpenFilm = " And Open = '".$FilmTileOpen."' " .
                                       " And Film = '".$FilmTileFilm."' " ;
                  }

               
                  // ���ϴ���
                  $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                            "  From ".$SingoName."                     ".
                            " Where SingoDate  <= '".$WorkDate."'      ".
                            "   And Theather   = '".$singoTheather."'  ".
                            $CondRoom                                   .
                            "   And Open       = '".$singoOpen."'      ".
                            "   And Film       = '".$singoFilm."'      ".
                            "   And UnitPrice  = '".$singoPrice."'     " ;
                  $qry_singo2 = mysql_query($sQuery,$connect) ;
                  $NumPersons_data = mysql_fetch_array($qry_singo2) ;
                  if  ($NumPersons_data)
                  {
                      ?>
                      <td class=textarea bgcolor=<?=$Color3?> align=right>
                      <b><?=$NBSP?><font color="#0000ff"><?=number_format($NumPersons_data["SumNumPersons"])?></font><?=$NBSP?></b>
                      </td>                      
                      <?
                  }
                  else
                  {
                      ?>
                      <td class=textarea bgcolor=<?=$Color3?> align=center>&nbsp;</td>
                      <?
                  }
                  ?>
             </tr>
             
             <?              
              $bFirstLeftTitle = False ;        
        }
        ?>


        
        <!--             -->
        <!--  �� �� ��� -->
        <!--             -->
        <tr height=22>          
             <!-- �հ� -->
             <td class=textarea bgcolor=<?=$Color2?> class=tblsum align=center>
             �հ�
             </td>

             <td class=textarea bgcolor=<?=$Color2?> class=tblsum align=center>             
             </td>

             <?
             if  ($isFinished == false)
             {
                 // ȸ���� ���ھ� �հ�
                 for ($i = 1 ; $i <= 11 ; $i++)
                 {
                    ?>
                    <td class=textarea bgcolor=<?=$Color2?> align=right>
                    <?=$NBSP?><?=number_format($arrySumOfDegree[$i])?><?=$NBSP?>
                    </td>
                    <?
                    $arryTotSumOfDegree[$i] += $arrySumOfDegree[$i] ;
                 }
             }
             ?>

             <!-- ���� �հ� �հ� -->
             <td class=textarea bgcolor=<?=$Color2?> align=right>
             <b><?=$NBSP?><?=number_format($SumOfPsToday)?><?=$NBSP?></b>
             <?
             $arryTotSumOfDegree[12] += $SumOfPsToday ;
             ?>
             </td>              
             
             <!-- ���� �հ� �հ� -->
             <?
             // �����հ�
             $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                       "  From ".$SingoName."                     ".
                       " Where SingoDate  = '".$AgoDate."'        ".
                       "   And Theather   = '".$singoTheather."'  ".
                       "   And Open       = '".$singoOpen."'      ".
                       "   And Film       = '".$singoFilm."'      " ;
             $qry_singo2 = mysql_query($sQuery,$connect) ;
             $NumPersons_data = mysql_fetch_array($qry_singo2) ;
             if  ($NumPersons_data)
             {
                 ?>
                 <td class=textarea bgcolor=<?=$Color2?> align=right>
                 <b><?=$NBSP?><?=number_format($NumPersons_data["SumNumPersons"])?><?=$NBSP?></b>
                 <?
                 $arryTotSumOfDegree[13] += $NumPersons_data["SumNumPersons"] ;
                 ?>
                 </td>        
                 <?
             }
             ?>
             
             <?
             // ���ϴ���
             $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                       "  From ".$SingoName."                     ".
                       " Where SingoDate  <= '".$WorkDate."'      ".
                       "   And Theather   = '".$singoTheather."'  ".
                       "   And Open       = '".$singoOpen."'      ".
                       "   And Film       = '".$singoFilm."'      " ;
             $qry_singo2 = mysql_query($sQuery,$connect) ;
             $NumPersons_data = mysql_fetch_array($qry_singo2) ;
             if  ($NumPersons_data)
             {
                 ?>                                                                      
                 <td class=textarea bgcolor=<?=$Color2?> align=right>
                 <b><?=$NBSP?><font color="#0000ff"><?=number_format($NumPersons_data["SumNumPersons"])?><?=$NBSP?></font></b>
                 </td>                      
                 <?
                 $arryTotSumOfDegree[14] += $NumPersons_data["SumNumPersons"] ;
             }
             ?>
        </tr>
   <?
   } 
   
   if   ($PutAct==True)
   {
   ?>
        <tr height=22>
              <!--             -->
              <!--  �� �� ��� -->
              <!--             -->

              <td class=textarea colspan=2 bgcolor=<?=$Color1?> align=center>
              <b>�հ�</b>
              </td>

              <td class=textarea bgcolor=<?=$Color1?> class=tblsum align=center>
              </td>

              <?
              // ȸ���� ���ھ� �հ�
              for ($i = 1 ; $i <= 12 ; $i++)
              {
                 ?>
                 <td class=textarea bgcolor=<?=$Color1?> align=right>
                 <b><?=$NBSP?><?=number_format($arryTotSumOfDegree[$i])?><?=$NBSP?></b>
                 </td>
                 <?
                  $arryTotTotSumOfDegree[$i] += $arryTotSumOfDegree[$i] ;
              }

              
              ?>

              <?
              $qry_singo2 = mysql_query("Select Sum(NumPersons) As SumNumPersons  ".
                                        "  From ".$SingoName." As Singo           ".
                                        " Where Singo.SingoDate  = '".$AgoDate."' ".
                                        $AddedCont                                ,$connect) ;
              $NumPersons_data = mysql_fetch_array($qry_singo2) ;
              if  ($NumPersons_data)
              {
                  ?>
                  <td class=textarea bgcolor=<?=$Color1?> align=right>
                  <b><?=number_format($NumPersons_data["SumNumPersons"])?><?=$NBSP?></b>
                  </td>
                  <?
                  $arryTotTotSumOfDegree[13] += $NumPersons_data["SumNumPersons"] ;
              }
              ?>

              <?
              $qry_singo2 = mysql_query("Select Sum(NumPersons) As SumNumPersons    ".
                                        "  From ".$SingoName." As Singo             ".
                                        " Where Singo.SingoDate  <= '".$WorkDate."' ".
                                        $AddedCont                                   ,$connect) ;
              $NumPersons_data = mysql_fetch_array($qry_singo2) ;
              if  ($NumPersons_data)
              {
                  ?>
                  <td class=textarea bgcolor=<?=$Color1?> align=right>
                  <b><font color="#0000ff"><?=number_format($NumPersons_data["SumNumPersons"])?></font><?=$NBSP?></b>
                  </td>
                  <?
                  $arryTotTotSumOfDegree[14] += $NumPersons_data["SumNumPersons"] ;
              }
              ?>
        </tr>
   <?
   }      
   ?>

   <?
   if   ($PutTail==True)
   { 
        if   ($PutTotal==True)
        {
        ?>
        <tr  height=25>
              <!--             -->
              <!--  �� �� ��� -->
              <!--             -->

              <td class=textarea colspan=2 bgcolor=<?=$Color2?> align=center>              
              <b>�� �հ�</b>
              </td>

              <td class=textarea bgcolor=<?=$Color1?> class=tblsum align=center>
              </td>

              <?
              // ȸ���� ���ھ� �հ�
              for ($i = 1 ; $i <= 13 ; $i++)
              {
                 ?>
                 <td class=textarea bgcolor=<?=$Color2?> align=right>
                 <b><?=$NBSP?><?=number_format($arryTotTotSumOfDegree[$i])?><?=$NBSP?></b>
                 </td>               
                 <?
              }
              ?>
              
              <?
              if  ($ZoneCode=="9")
              {
                   $AddedCont = "" ;
              }
              else
              {
                   $AddedCont = " And  Singo.Location = '".$LocationCode."' " ;
              }

              if   ($FilmTileFilm == '00') // �и��ȿ�ȭ�������ڵ�
              {
                   $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' " ;
              }
              else
              {
                   $AddedCont .= " And Singo.Open = '".$FilmTileOpen."' ".
                                 " And Singo.Film = '".$FilmTileFilm."' " ;
              }
              $sQuery = "Select Sum(NumPersons) As SumNumPersons    ".
                        "  From ".$SingoName." As Singo             ".
                        " Where Singo.SingoDate  <= '".$WorkDate."' ".
                        $AddedCont                                   ;
              $qry_singo2 = mysql_query($sQuery,$connect) ;
              $NumPersons_data = mysql_fetch_array($qry_singo2) ;
              if  ($NumPersons_data)
              {
              //$arryTotTotSumOfDegree[14]
              ?>
              <td class=textarea bgcolor=<?=$Color2?> align=right>
              <b><?=$NBSP?><font color="#0000ff"><?=number_format($NumPersons_data["SumNumPersons"])?></font><?=$NBSP?></b>
              </td>               
              <?
              }
              ?>
        </tr>
        <?
        } 
        ?>
      </table>
      <br>
   <?
   }
   ?>