   <?   
   while ($singo_data = mysql_fetch_array($qry_singo))
   {
        $singoSilmooja    = $singo_data["Silmooja"] ;      // 신고실무자
        $singoTheather    = $singo_data["Theather"] ;      // 신고상영관
        $singoRoom        = $singo_data["Room"] ;          //
        $singoOpen        = $singo_data["Open"] ;          // 신고영화
        $singoFilm        = $singo_data["Film"] ;          //
        $showroomDiscript = $singo_data["Discript"] ;      // 신고 상영관명  
        $showroomCntDgree = $singo_data["CntDgree"] ;      // 상영회차수


        // 영화 제목을 구하되 영화가 바뀌는 순간에만 저장하고
        // 두번이상 반복되면 영화명을 지운다.
        if  ($filmtitleNameTitle != $singo_data["FilmTitleName"])
        {
            $filmtitleName      = $singo_data["FilmTitleName"] ;
            $filmtitleNameTitle = $singo_data["FilmTitleName"] ;
        }
        else
        {
            $filmtitleName = "" ;
        }

        //mysql_free_result($qry_silmoojatheatherfinish) ;



        // 영화제목 출력 (변화되는 시점에만,)..
        if   (($filmtitleName!="") and ($FilmTileFilm<>'00'))
        {
        ?>
            <table name=score cellpadding=0 cellspacing=0 border=1 bordercolor="#FFFFFF" width=100%>
            <tr>
            
            <td align=left  width=30%>
            개봉일:<?=substr($singoOpen,0,2)."/".substr($singoOpen,2,2)."/".substr($singoOpen,4,2)?>
            </td>

            <!-- 영화제목출력 -->
            <td align=center>
            <b><?=$filmtitleName?></b>            
            </td>
                
            <td align=right width=30%>
            &nbsp;
            </td>
            
            </tr>
            </table>

            <br>


            <table style='table-layout:fixed' name=score cellpadding=0 cellspacing=0 border=1 bordercolor=#C0B0A0>
            <tr height=30>
                  <!--             -->
                  <!-- 타이틀 찍기 -->
                  <!--             -->

                  <td class=textarea bgcolor="#b0c4de" width=40 align=center>
                  <B>지역</B>
                  </td>

                  <td class=textarea bgcolor="#b0c4de" width=150 align=center>
                  <B>상영관</B>                  
                  </td>

                  <td class=textarea bgcolor="#b0c4de" width=600 align=center>
                  <B>보고내용</B>
                  </td>

                  <!-- 보고자 -->
                  <td class=textarea bgcolor="#b0c4de" width=100 align=center>
                  <B>보고자</B>
                  <br>
                  </td>                  
            </tr>
        <?
        }


        $ExitTheather = false ;

        $sQuery = "Select * From bas_smsidchk        ".
                  " Where Id = '".$spacial_UserId."' " ;
        $QrySmsIdChk = mysql_query($sQuery,$connect) ;         
        if  ($ArrSmsIdChk = mysql_fetch_array($QrySmsIdChk)) // 이부장.. 
        { 
            $TimJang = true ;
        }
        else
        {
            $TimJang = false ;
        }

        if  ($TimJang == true)
        {
            $sQuery = "Select Count(*) As cntSmschk           ".
                      "  From wrk_smschk                      ".
                      " Where Open     = '".$singoOpen."'     ".
                      "   And Film     = '".$singoFilm."'     ".
                      "   And Theather = '".$singoTheather."' " ;
            $qry_Smschk = mysql_query($sQuery,$connect) ;

            if  ($Smschk_data = mysql_fetch_array($qry_Smschk) )
            {
                if  ($Smschk_data["cntSmschk"]==0)
                {
                    $ExitTheather = true ;
                }
            }
        }

        if  ($ExitTheather == false)
        {
        
            if  ($oldsingoTheather != $singoTheather)
            {
                $clrToggle = !$clrToggle ;

                $oldsingoTheather = $singoTheather ;
            }

            if  ($ToExel)//
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
                    $Color1 = "#b0c4de" ;
                    $Color2 = "#efebcd" ;
                    $Color3 = "#dcdcdc" ;
                    $Color4 = "#c0c0c0" ;
                }
                else
                {
                    $Color1 = "#c0d4ee" ;
                    $Color2 = "#fffbdd" ;
                    $Color3 = "#ececec" ;
                    $Color4 = "#d0d0d0" ;
                }
            }
            ?>    
           
             

            <?
            $FirstCol  = true ;
            
            $cntSMSreq = 0 ;

            
            $qry_cntSMSreq = mysql_query("Select Count(*) As cntSMSreq              ".
                                         "  From wrk_smsreq                         ".
                                         " Where RcvDate  = '".$WorkDate."'         ".
                                         "   And Theather   = '".$singoTheather."'  ".
                                         "   And Room       = '".$singoRoom."'      ".
                                         "   And Film       = '".$singoFilm."'      ",$connect) ; 
            if  ($cntSMSreq_data = mysql_fetch_array($qry_cntSMSreq))
            {
                $cntSMSreq = $cntSMSreq_data["cntSMSreq"] ;
            }


            $qry_ShowDgree = mysql_query("Select DISTINCT SingoTime, ShowDgree, Phoneno ".
                                         "  From ".$sSingoName."                        ".
                                         " Where Singodate  = '".$WorkDate."'           ".
                                         "   And Theather   = '".$singoTheather."'      ".
                                         "   And Room       = '".$singoRoom."'          ".
                                         "   And Open       = '".$singoOpen."'          ".
                                         "   And Film       = '".$singoFilm."'          ".
                                         " Order By ShowDgree                           ",$connect) ; 
            $affected_rows = mysql_affected_rows() ;

            $cntLine = $affected_rows + $cntSMSreq ;
            
            while ($ShowDgree_data = mysql_fetch_array($qry_ShowDgree))
            {
                $SingoTime = $ShowDgree_data["SingoTime"] ;
                $ShowDgree = $ShowDgree_data["ShowDgree"] ;
                $Phoneno   = $ShowDgree_data["Phoneno"] ;

                
                ?>
                    <!--             -->
                    <!-- 데이타 찍기 -->
                    <!--             -->
                    <tr>          
                         <?
                         if  ($FirstCol == true)
                         {
                             ?>
                             <!-- 구역 및 지역 -->
                             <td class=textarea bgcolor=<?=$Color1?> rowspan=<?=$cntLine?> align=center>                   
                                 &nbsp;<B><?=$zoneName?></B>
                             </td>

                             <!-- 상영관정보 상영관명 -->
                             <td class=textarea bgcolor=<?=$Color2?> rowspan=<?=$cntLine?>>
                                 &nbsp;<B><?=$showroomDiscript?></B><br> <!-- 상영관명 -->
                                 <B>(<?=$singoTheather?>)</B>
                             </td>
                             <?
                             $FirstCol = false ;
                         }
                         ?>

                         <td class=textarea bgcolor=<?=$Color3?>>
                         &nbsp;<?echo substr($SingoTime,8,2)."시".substr($SingoTime,10,2)."분"; ?>
                         &nbsp;<?=$ShowDgree?>회
                         <?
                         $qry_Score = mysql_query("Select UnitPrice, NumPersons                  ".
                                                  "  From ".$sSingoName."                        ".
                                                  " Where Singodate  = '".$WorkDate."'           ".
                                                  "   And Theather   = '".$singoTheather."'      ".
                                                  "   And Room       = '".$singoRoom."'          ".
                                                  "   And Open       = '".$singoOpen."'          ".
                                                  "   And Film       = '".$singoFilm."'          ".
                                                  "   And ShowDgree  = '".$ShowDgree."'          ".
                                                  "   And Phoneno    = '".$Phoneno."'            ".
                                                  " Order By UnitPrice Desc                      ",$connect) ; 
                         while ($Score_data = mysql_fetch_array($qry_Score))
                         {
                             echo $Score_data["UnitPrice"] . "원",$Score_data["NumPersons"] . "명 " ;
                         }
                         ?>
                         </td>              
                         
                         <td class=textarea bgcolor=<?=$Color3?> align=center>
                         <B><?=$Phoneno?></B>
                         </td>
                    </tr>
                <?
            }

            $qry_SMSreq = mysql_query("Select *                                  ".
                                      "  From wrk_smsreq                         ".
                                      " Where RcvDate  = '".$WorkDate."'         ".
                                      "   And Theather   = '".$singoTheather."'  ".
                                      "   And Room       = '".$singoRoom."'      ".
                                      "   And Film       = '".$singoFilm."'      ",$connect) ; 
            while ($SMSreq_data = mysql_fetch_array($qry_SMSreq))
            {
                $cntSMSreq = $SMSreq_data["cntSMSreq"] ;
                $Action = $SMSreq_data["Action"] ;
                ?>
                <tr > 
                     <td class=textarea bgcolor=<?=$Color3?> valign=center>
                     &nbsp;<font color=red>
                     <?echo substr($SMSreq_data["RcvTime"],0,2) . "시" . substr($SMSreq_data["RcvTime"],2,2) . "분"?> 조회
                     (<?=$Action?>)
                     </font>
                     </td>

                     <td class=textarea bgcolor=<?=$Color3?> align=center >
                     <b><?=$SMSreq_data["PhoneNo"]?></b>
                     </td>
                </tr> 
                <?
            }
        }
   }         
   ?> 
   
