   <?
   $CountRooms = 0 ;


   while ($ArySingo = mysql_fetch_array($QrySingo))
   {
		$CountRooms++;

        for ($i = 1 ; $i <= 12 ; $i++)
        {
            $arrySumOfDegree[$i] = 0 ;  // 회차별 스코어 합계
        }
        $singoSilmooja     = $ArySingo["Silmooja"] ;      // 신고실무자
        $singoTheather     = $ArySingo["Theather"] ;      // 신고상영관
        $singoRoom         = $ArySingo["Room"] ;          //
        $singoOpen         = $ArySingo["Open"] ;          // 신고영화
        $singoFilm         = $ArySingo["Film"] ;          //
        $singoFilmType     = $ArySingo["FilmType"] ;      //
        $silmoojaName      = $ArySingo["SilmoojaName"] ;  // 신고 실무자명
        $showroomDiscript  = $ArySingo["Discript"] ;      // 신고 상영관명
        $showroomLocation  = $ArySingo["Location"] ;      // 신고 상영관지역
        $showroomMultiPlex = $ArySingo["MultiPlex"] ;     //
        $locationName      = $ArySingo["LocationName"] ;  // 신고 상영관지역명
        $showroomSeat      = $ArySingo["ShowRoomSeat"] ;  // 신고 상영관좌석
        $SumNumPersons     = $ArySingo["SumNumPersons"] ; // 총 스코어
        $showroomCntDgree  = $ArySingo["CntDgree"] ;      // 상영회차수
        $filmExcelTitle    = $ArySingo["ExcelTitle"] ;    //

		$GikumRate = 1.03 ;
		$sQuery = "SELECT IF( '".$WorkDate."' >=  '20160523', GikumRate,  1.03 ) GikumRate ".
                  "  FROM bas_theather                                                     ".
                  " WHERE code = ".$singoTheather."                                        " ; //echo $sQuery ;
		$qry_GikumRate = mysql_query($sQuery,$connect) ;
		if  ($AryGikumRate = mysql_fetch_array($qry_GikumRate))
		{
			$GikumRate	= $AryGikumRate["GikumRate"]; // = 1.03
		}

        $sQuery = "Select Count(distinct UnitPrice) CntUnitPrice".
                  " From ".$sSingoName."                        ".
                  " Where SingoDate  <= '".$WorkDate."'         ".
                  "   And Theather   = '".$singoTheather."'     ".
                  "   And Open       = '".$singoOpen."'         ".
                  "   And Film       = '".$singoFilm."'         ".
                  " Order By UnitPrice desc                     " ;
        $qry_CntUnitPrice = mysql_query($sQuery,$connect) ;
        if  ($AryCntUnitPrice = mysql_fetch_array($qry_CntUnitPrice))
        {
            $cntUnitPrice = $AryCntUnitPrice["CntUnitPrice"] ;
        }

        // 종영여부를 검사한다.
        $sQuery = "Select * From bas_silmoojatheatherfinish  ".
                  " Where Silmooja = '".$singoSilmooja."'    ".
                  "   And WorkDate <= '".$WorkDate."'        ".
                  "   And Theather = '".$singoTheather."'    ".
                  "   And Room     = '".$singoRoom."'        ".
                  "   And Open     = '".$singoOpen."'        ".
                  "   And Film     = '".$singoFilm."'        " ;
        $qry_silmoojatheatherfinish = mysql_query($sQuery,$connect) ;
        if  ($ArySilmoojatheatherfinish = mysql_fetch_array($qry_silmoojatheatherfinish))
        {
            $isFinished = true ;                                   // 종영이 되었음

            $TempDate = $ArySilmoojatheatherfinish["WorkDate"] ; // 종영일자

            // 하루 전날을 구한다.
            $FinishDate = date("Ymd",strtotime("-1 day",strtotime(substr($TempDate,0,4)."-".substr($TempDate,4,2)."-".substr($TempDate,6,2).""))) ;
        }
        else
        {
            $isFinished = false ;  // 종영되지 않았음
            $FinishDate = "" ;     //
        }


        // 영화 제목을 구하되 영화가 바뀌는 순간에만 저장하고
        // 두번이상 반복되면 영화명을 지운다.
        if  ($filmtitleNameTitle != $ArySingo["FilmTitleName"])
        {
            $filmtitleName      = $ArySingo["FilmTitleName"] ;
            $filmtitleNameTitle = $ArySingo["FilmTitleName"] ;
        }
        else
        {
            $filmtitleName = "" ;
        }

        mysql_free_result($qry_silmoojatheatherfinish) ;


       // 영화제목 출력 (변화되는 시점에만,)..
       if   (($filmtitleName!="") and ($FilmCode<>'00'))
       {
       ?>
           <?
           $WorkTime = mktime(0,0,0,substr($WorkDate,4,2),substr($WorkDate,6,2),substr($WorkDate,0,4));
           $OpenTime = mktime(0,0,0,substr($singoOpen,2,2),substr($singoOpen,4,2),"20".substr($singoOpen,0,2));
           $dur_day  = ($WorkTime - $OpenTime) / 86400  ;

           if  ($WorkGubun != 29)
           {
               ?>
               <table name=score cellpadding=0 cellspacing=0 border=1 bordercolor="#FFFFFF" width=100%>
               <tr>

                   <td align=left class=textare>
                   개봉일:<?=substr($singoOpen,0,2)."/".substr($singoOpen,2,2)."/".substr($singoOpen,4,2)?>
                   </td>

                   <td align=center colspan=19>

                       <!-- 영화제목출력 -->
                       <b><?=$filmtitleName?></b>

                       <?
                       if ($ToExel) // 엑셀
                       {
                           ?>
                           <BR><?=$filmExcelTitle?>
                           <?
                       }
                       ?>

                   </td>

                   <td align=right class=textare>
                       개봉일로 부터 <?=($dur_day+1)?>일째..<br>
                       조회일:<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>  <br>
                       시간:<?=date("h:i:s") ?>
                   </td>

               </tr>
               </table>
               <?
           }
           else
           {
               ?>
               <table name=score cellpadding=0 cellspacing=0 border=0 width=100%>
               <tr>
                   <td align=center colspan=21>
                       <!-- 영화제목출력 -->
                       <b><?=$filmtitleName?></b>

                       <?
                       if ($ToExel)  // 엑셀
                       {
                           ?>
                           <BR><?=$filmExcelTitle?>
                           <?
                       }
                       ?>
                   </td>
               </tr>
               <tr>

               <td align=left class=textare width=320>
                   <?=($dur_day+1)?>일차
                   개봉일:<?=substr($singoOpen,0,2)."/".substr($singoOpen,2,2)."/".substr($singoOpen,4,2)?>
                   상영일:<?=substr($WorkDate,2,2)."/".substr($WorkDate,4,2)."/".substr($WorkDate,6,2)?>
               </td>

               <td align=center colspan=19>

               </td>

               <td align=right class=textare width=320>
                  &nbsp;
               </td>
               </tr>
               </table>
               <?
           }
           ?>
           <br>
       <?
       }

       // 총 상영좌석수 = 회차수 * 상영관 자리수
       $showroomTotDgree = $showroomCntDgree * $showroomSeat ;

       if  ($showroomTotDgree==0)
       {
           $rateSeat = "[0%]" ;
       }
       else
       {
           if  ($SumNumPersons > 0)
           {
               // 점유율 = ( 총 스코어 / 총 상영좌석수 ) * 100 [%]  $SumNumPersons
               $rateSeat = "[".round(($SumNumPersons / $showroomTotDgree)*100.0)."%]" ;
           }
           else
           {
               $rateSeat = "[0%]" ;
           }
       }

       if  ($oldsingoTheather != $singoTheather)
       {
           $clrToggle = !$clrToggle ;

           $oldsingoTheather = $singoTheather ;
       }

       if  ($ToExel)   // 엑셀
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


       $ExitTheather = false ; // 일단 모든영화가 나타난다..

       if  ($TimJang == true)  // 팀장이고..
       {
           $sQuery = "Select Count(*) As cntSmschk           ".
                     "  From wrk_smschk                      ".
                     " Where Open     = '".$singoOpen."'     ".
                     "   And Film     = '".$singoFilm."'     ".
                     "   And Theather = '".$singoTheather."' ".
                     "   And Chk".$TimJangNo." = 'Y'         " ;
           $QrySmschk = mysql_query($sQuery,$connect) ;
           if  ($ArySmschk = mysql_fetch_array($QrySmschk) )
           {
               if  ($ArySmschk["cntSmschk"]==0) // 영화가 선택되지 않았다면.
               {
                   $ExitTheather = true ;  // 영화가 빠진다
               }
           }
           else
           {
               $ExitTheather = true ;  // 영화가 빠진다
           }
       }

       if  ($ExitTheather == false)
       {
           // 35mm는 35, 디지털 투디는 2 디지털 쓰리디는 3 아이맥스 투디는 29 아이맥스 쓰리디는 39 -
           /*
           if  ($nFilmTypeNo != "-1")
           {
               $FilmType = 0 ;

               $sQuery = "Select Type From ".$sFilmTypePrv."      ".
                         " Where WorkDate <= '".$WorkDate."'      ".
                         "   And Open     = '".$FilmOpen."'       ".
                         "   And Code     = '".$FilmCode."'       ".
                         "   And Theather = '".$singoTheather."'  ".
                         "   And Room     = '".$singoRoom."'      ".
                         " Order By WorkDate desc                 ".
                         " Limit 0 , 1                            "; //if  ($singoTheather =="2605") echo $sQuery."<BR>" ;
//echo $sQuery."<BR>" ;
               $QryFilmType = mysql_query($sQuery,$connect) ;
               if  ($ArrFilmType = mysql_fetch_array($QryFilmType))
               {
                    $FilmType = $ArrFilmType["Type"] ;
               }

               if   ($FilmType == 0)
               {
                    $sQuery = "Select Type From ".$sFilmType."         ".
                              " Where Open     = '".$FilmOpen."'       ".
                              "   And Code     = '".$FilmCode."'       ".
                              "   And Theather = '".$singoTheather."'  ".
                              "   And Room     = '".$singoRoom."'      "; //echo $sQuery."<BR>" ;
                    $QryFilmType = mysql_query($sQuery,$connect) ;
                    if  ($ArrFilmType = mysql_fetch_array($QryFilmType))
                    {
                        $FilmType = $ArrFilmType["Type"] ;
                    }
                    else
                    {
                        $sQuery = "Insert Into ".$sFilmType."    ".
                                  " Value                        ".
                                  "(                             ".
                                  "   '".$FilmOpen."',           ".
                                  "   '".$FilmCode."',           ".
                                  "   '".$singoTheather."',      ".
                                  "   '".$singoRoom."',          ".
                                  "   '35'                       ".
                                  ")                             " ; //echo $sQuery."<BR>" ;
                        mysql_query($sQuery,$connect) ;

                        $sQuery = "Insert Into ".$sFilmTypePrv." ".
                                  " Value                        ".
                                  "(                             ".
                                  "   '".$WorkDate."',           ".
                                  "   '".$FilmOpen."',           ".
                                  "   '".$FilmCode."',           ".
                                  "   '".$singoTheather."',      ".
                                  "   '".$singoRoom."',          ".
                                  "   '35'                       ".
                                  ")                             " ;
                        mysql_query($sQuery,$connect) ;//echo $sQuery."<BR>" ;

                        $FilmType = 35 ;
                    }
               }

               if   ($FilmType != 0) $EchoFilmType = "<BR>".$FilmType  ;
               else                  $EchoFilmType = "" ;
           }
           else
           {
               $EchoFilmType = "" ;
           }
           */
           if   (($nFilmTypeNo == $singoFilmType) || ($nFilmTypeNo == "") || ($nFilmTypeNo == "0"))
           {
           ?>

           <table style='table-layout:fixed' name=score cellpadding=0 cellspacing=0 border=1 bordercolor=#C0B0A0>

           <tr>
                 <!--             -->
                 <!-- 타이틀 찍기 -->
                 <!--             -->

                 <!-- 구역 및 지역 -->
                 <td class=textarea width=40 bgcolor=<?=$Color1?> align=center rowspan=<?=$cntUnitPrice+2?>>

                     <!-- 구역 -->
                     <span style="background-color:white;">
                     <?// =$CountRooms?>
                     <font color=#boc4de><B><?=$zoneName?></B></font>
                     </span>

                     <br>

                     <!-- 지역 -->
                     <B><?=$locationName?></B>

                     <?
                     if  ($ToExel)  // 엑셀
                     {
                         ?>
                         <br><?=$singoRoom?>
                         <br><?=$singoTheather?>
                         <?
                     }

                     if  ((!$filmproduce) && ($TimJang==false)) // 이부장 // 일반배급사 권한으로 들어 올때만 뿌려준다.
                     {
                         ?>
                         <a href="#" OnClick="endingShowroom('<?=$singoSilmooja?>','<?=$singoTheather.$singoRoom?>','<?=$FilmTile?>')">종영<br>처리</a>
                         <?
                     }

                     //echo $EchoFilmType ;
                     echo "<BR>".$singoFilmType ;
                     ?>
                 </td>

                 <!-- 상영관정보 상영관명,좌석수,점유율 -->
                 <td class=textarea width=120 bgcolor=<?=$Color2?> align=center rowspan=<?=$cntUnitPrice+2?>>


                     <B><?=$showroomDiscript?></B><br> <!-- 상영관명 -->
                                                       <!--[상영관코드(<?=$singoTheather?>,<?=$singoRoom?>)]      -->
                                                       <!-- 영화코드(<?=substr($FilmTile,0,6)?>,<?=substr($FilmTile,6,2)?>) -->
                     <?
                     if  (!$filmproduce) // 일반배급사 권한으로 들어 올때만 뿌려준다.
                     {
                     ?>
                         <!-- 좌석수 -->
                         <!-- 점유율 -->
                         <!-- 실무자이름 [실무자코드(<?=$singoSilmooja?>)] -->
                         <!-- 실무자양도 (wrk_filmsupply_Link_Chg.php) -->
                         <!-- 스코어수정 (wrk_filmsupply_Link_UpM.php) -->

                         <B><?=$showroomSeat?>석</B><br>


                         <?
                         if  ($TimJang==false) // 이부장
                         {
                         ?>
                         <B><?=$rateSeat?></B><br>
                         <?=$silmoojaName?>

                         <a href="#" onclick="yangdo_click('<?=$singoSilmooja?>','<?=$singoTheather.$singoRoom?>','<?=$FilmTile?>')">[양도]</a>
                         <br>

                         <a href="#" onclick="modify_click('<?=$singoSilmooja?>','<?=$singoTheather.$singoRoom?>','<?=$FilmTile?>')">[수정]</a>
                         <a href="#" onclick="delect_click('<?=$singoSilmooja?>','<?=$singoTheather.$singoRoom?>','<?=$FilmTile?>','<?=$singoOpen?>','<?=$singoFilm?>');">[삭제]</a>

                         <?
                         }

                         /*
                         $sQuery = "Select * From chk_extension_day          ". /////////////////////////////////////////////////////////
                                   " Where Theather   = '".$singoTheather."' ".
                                   "   And ObjectDay  = '".$WorkDate."'      ".
                                   "   And Open       = '".$singoOpen."'     ".
                                   "   And Film       = '".$singoFilm."'     " ;
                         $QryExtension = mysql_query($sQuery,$connect) ;
                         if  ($ArrExtension  = mysql_fetch_array($QryExtension))
                         {
                             echo "<BR>" ;
                             if  ($ArrExtension["Gubun"]=="Er") echo "<font color='red'>불일치</font>" ;
                             if  ($ArrExtension["Gubun"]=="Ok") echo "<font color='red'>일치</font>" ;
                             if  ($ArrExtension["Damdangja"] != "")
                             {
                                 echo  " [".$ArrExtension["Damdangja"]."]" ;
                             }
                         }
                         */
                         ?>
                     <?
                     /********************
                     $sQuery = "Select Phoneno From ".$sSingoName."      ".
                               " Where SingoDate  = '".$WorkDate."'      ".
                               "   And Theather   = '".$singoTheather."' ".
                               "   And Room       = '".$singoRoom."'     ".
                               "   And Open       = '".$singoOpen."'     ".
                               "   And Film       = '".$singoFilm."'     " ;
                     $QryPhone = mysql_query($sQuery,$connect) ;
                     if  ($ArrPhone  = mysql_fetch_array($QryPhone))
                     {
                         if  ($ArrPhone["Phoneno"]!="")
                         {
                         echo  "<BR>hp:" .$ArrPhone["Phoneno"]  ;
                         }
                         else
                         {
                         echo  "<BR>" ;
                         }
                     }
                     *****************/
                     echo "<BR><B>".$singoTheather."</B>" ;
                     //echo "<BR><B>(".$showroomMultiPlex.")</B>" ;

                     }
                     else // 영화사 권한으로 들어왔을 때
                     {
                     ?>
                         <B><?=$showroomSeat?>석</B><br> <!-- 좌석수 -->
                         <B><?=$rateSeat?></B><br>       <!-- 점유율 -->
                     <?
                     }


                     ?>


                     <br>
                     <!-- 상영관 변경 정보를 나타낸다. -->


                     <!-- 영화코드(<?=substr($FilmTile,0,6)?>,<?=substr($FilmTile,6,2)?>) -->
                     <?
                     $MovPrn = false ;
                     ?>

                 </td>


                 <?
                 for ($i = 1 ; $i <= 11 ; $i++)
                 {
                     //
                     // 실무자가 "111111", "222222" 일때..
                     //    오늘의 회차정보를 만든다.
                     //
                     if   ($singoSilmooja == "777777")
                     {
                          // 오늘회차 존재여부확인 ..
                          $sQuery = "Select * From ".$sDgrpName."           ".
                                    " Where Silmooja = '".$singoSilmooja."' ".
                                    "   And WorkDate = '".$WorkDate."'      ".
                                    "   And Open     = '".$singoOpen."'     ".
                                    "   And Film     = '".$singoFilm."'     ".
                                    "   And Theather = '".$singoTheather."' ".
                                    "   And Room     = '".$singoRoom."'     " ;
                          $qry_degreepriv = mysql_query($sQuery,$connect) ;
                          $degreepriv_data  = mysql_fetch_array($qry_degreepriv) ;
                          if  (!$degreepriv_data) // 오늘 회차 정보가 없다면..
                          {
                              $sQuery = "Insert Into ".$sDgrpName."  ".
                                        "Values                      ".
                                        "(                           ".
                                        "    '".$singoSilmooja."',   ".
                                        "    '".$WorkDate."',        ".
                                        "    '".$singoOpen."',       ".
                                        "    '".$singoFilm."',       ".
                                        "    '".$singoTheather."',   ".
                                        "    '".$singoRoom."',       ".
                                        "    '01',                   ".
                                        "    '10000',                ".
                                        "    '".$degreeDiscript."'   ".
                                        ")                           " ;
                              mysql_query($sQuery,$connect) ;
                          }
                     }
                     if   (($singoSilmooja == "111111") || ($singoSilmooja == "222222"))
                     {
                          // 오늘회차 존재여부확인 ..
                          $sQuery = "Select * From ".$sDgrpName."           ".
                                    " Where Silmooja = '".$singoSilmooja."' ".
                                    "   And WorkDate = '".$WorkDate."'      ".
                                    "   And Open     = '".$singoOpen."'     ".
                                    "   And Film     = '".$singoFilm."'     ".
                                    "   And Theather = '".$singoTheather."' ".
                                    "   And Room     = '".$singoRoom."'     " ;
                          $qry_degreepriv = mysql_query($sQuery,$connect) ;
                          $degreepriv_data  = mysql_fetch_array($qry_degreepriv) ;
                          if  (!$degreepriv_data) // 오늘 회차 정보가 없다면..
                          {
                              $sQuery = "Select * From ".$sDgrName."                ".
                                        " Where Silmooja = '".$singoSilmooja."'  ".
                                        "   And Open     = '".$singoOpen."'      ".
                                        "   And Film     = '".$singoFilm."'      ".
                                        "   And Theather = '".$singoTheather."'  ".
                                        "   And Room     = '".$singoRoom."'      " ;
                              $qry_degree = mysql_query($sQuery,$connect) ;

                              while ($degree_data  = mysql_fetch_array($qry_degree))
                              {
                                  // 오늘의 회차정보를 만든다.

                                  $Degree         = $degree_data["Degree"] ;   // 각각의 회차.
                                  $degreeTime     = $degree_data["Time"] ;     // 각각의 시간.
                                  $degreeDiscript = $degree_data["Discript"] ; // 각각의 상영관이름.

                                  $sQuery = "Insert Into ".$sDgrpName."  ".
                                            "Values                      ".
                                            "(                           ".
                                            "    '".$singoSilmooja."',   ".
                                            "    '".$WorkDate."',        ".
                                            "    '".$singoOpen."',       ".
                                            "    '".$singoFilm."',       ".
                                            "    '".$singoTheather."',   ".
                                            "    '".$singoRoom."',       ".
                                            "    '".$Degree."',          ".
                                            "    '".$degreeTime."',      ".
                                            "    '".$degreeDiscript."'   ".
                                            ")                           " ;
                                  mysql_query($sQuery,$connect) ;
                              }
                          }
                     }
                 }
                 ?>


                 <!-- 요금 -->
                 <?
                 if  ($WorkGubun != 29)
                 {
                     ?>
                     <td class=textarea width=40 bgcolor=<?=$Color3?> align=center>
                     요금
                     </td>
                     <?
                 }
                 ?>


                 <?
                 if  (($WorkGubun == 27) || ($WorkGubun == 29))
                 {
                     ?>
                     <td class=textarea width=40 bgcolor=<?=$Color3?> align=center>
                     기금<BR>요금
                     </td>
                     <?
                 }
                 ?>

                 <?
                 // 최근회차 존재여부확인 일자를 구함..
                 $sQuery = "Select Workdate From ".$sDgrpName."    ".
                           " Where Open     = '".$singoOpen."'     ".
                           "   And Film     = '".$singoFilm."'     ".
                           "   And Theather = '".$singoTheather."' ".
                           "   And Room     = '".$singoRoom."'     ".
                           "   And Degree   <> ''                  ".
                           "   And Degree   <> '00'                ".
                           "   And Workdate <= '".$WorkDate."'     ".
                           " Order By WorkDate Desc                ".
                           " LIMIT 0 , 1                           " ;
                 $qry_degreepriv = mysql_query($sQuery,$connect) ;
                 if  ($degreepriv_data  = mysql_fetch_array($qry_degreepriv)) // 오늘 회차 정보
                 {
                     $LastWorkDate = $degreepriv_data["Workdate"] ; // 최근회차 일자 ..
                 }
                 else
                 {
                     $LastWorkDate = "" ;
                 }

                 $LastDegree = 10 ;

                 $sQuery = "Select ShowDgree, SingoTime                 ".
                           "  From ".$sSingoName."                      ".
                           " Where SingoDate  = '".$WorkDate."'         ".
                           "   And Silmooja   = '".$singoSilmooja."'    ".
                           "   And Theather   = '".$singoTheather."'    ".
                           "   And Room       = '".$singoRoom."'        ".
                           "   And Open       = '".$singoOpen."'        ".
                           "   And Film       = '".$singoFilm."'        ".
                           " Group By ShowDgree                         ".
                           " Order By ShowDgree                         " ;
                 $QrySingoTime = mysql_query($sQuery,$connect) ;
                 $ArrSingoTime = mysql_fetch_array($QrySingoTime) ;

                 for ($i = 1 ; $i <= $LastDegree ; $i++)
                 {
                     $strdegree = sprintf("%2d",$i) ;

                     $SingoTime =  "__:__" ;
                     /*
                     if  ($ArrSingoTime["ShowDgree"] == $strdegree)
                     {
                         $SingoTime = substr($ArrSingoTime["SingoTime"],8,2).":".substr($ArrSingoTime["SingoTime"],10,2) ;
                         $ArrSingoTime = mysql_fetch_array($QrySingoTime) ;
                     }
                     if  (($i == $LastDegree) && ($ArrSingoTime["ShowDgree"] == "99"))
                     {
                         $SingoTime = substr($ArrSingoTime["SingoTime"],8,2).":".substr($ArrSingoTime["SingoTime"],10,2) ;
                         $ArrSingoTime = mysql_fetch_array($QrySingoTime) ;
                     }
                     */

                     if  ($i<$LastDegree) // 1회 부터 10회 까지..
                     {
                         $CntDgree = $count_data["CntDgree"] ;

                         $sQuery = "Select Time From ".$sDgrpName."            ".
                                   " Where Silmooja = '".$singoSilmooja."'     ".
                                   "   And WorkDate = '".$LastWorkDate."'      ".
                                   "   And Open     = '".$singoOpen."'         ".
                                   "   And Film     = '".$singoFilm."'         ".
                                   "   And Theather = '".$singoTheather."'     ".
                                   "   And Room     = '".$singoRoom."'         ".
                                   "   And Degree   = '".sprintf("%02d",$i)."' " ;
                         $qry_degreepriv = mysql_query($sQuery,$connect) ;
                         if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
                         {
                             $Hour = substr($degreepriv_data["Time"],0,2) ;
                             $Mint = substr($degreepriv_data["Time"],2,2) ;
                             ?>
                             <td class=textarea width=39 bgcolor=<?=$Color3?> align=center>
                             <?=$strdegree?>회<br><?=$Hour?>:<?=$Mint?><?
                             if  (!$filmproduce) // 일반배급사 권한으로 들어 올때만 뿌려준다.
                             {
                                     ?><!-- <br><FONT COLOR="red"><?=$SingoTime?></FONT> --><?
                             }
                             ?></td>
                             <?
                         }
                         else
                         {
                             ?>
                             <td class=textarea width=39 bgcolor=<?=$Color3?> align=center>
                             <font color="silver"><?=$strdegree?>회<br>__:__<?
                             if  (!$filmproduce) // 일반배급사 권한으로 들어 올때만 뿌려준다.
                             {
                                     ?><!-- <br>__:__ --><?
                             }
                             ?>
                             </td></font>
                             <?
                         }
                         $agree = true ;
                     }
                     else // 심야
                     {
                         $sQuery = "Select Time From ".$sDgrpName."            ".
                                   " Where Silmooja = '".$singoSilmooja."'     ".
                                   "   And WorkDate = '".$LastWorkDate."'      ".
                                   "   And Open     = '".$singoOpen."'         ".
                                   "   And Film     = '".$singoFilm."'         ".
                                   "   And Theather = '".$singoTheather."'     ".
                                   "   And Room     = '".$singoRoom."'         ".
                                   "   And Degree   = '99'                     " ;
                         $qry_degreepriv = mysql_query($sQuery,$connect) ;
                         if  ($degreepriv_data = mysql_fetch_array($qry_degreepriv))
                         {
                             $Hour = substr($degreepriv_data["Time"],0,2) ;
                             $Mint = substr($degreepriv_data["Time"],2,2) ;
                             ?>
                             <td class=textarea width=39 bgcolor=<?=$Color3?> align=center>
                             심야<br><?=$Hour?>:<?=$Mint?><?
                             if  (!$filmproduce) // 일반배급사 권한으로 들어 올때만 뿌려준다.
                             {
                                 ?><!-- <br><FONT COLOR="red"><?=$SingoTime?></FONT> --><?
                             }
                             ?>
                             </td>
                             <?
                         }
                         else
                         {
                             ?>
                             <td class=textarea width=39 bgcolor=<?=$Color3?> align=center>
                             <font color="silver">심야<br>__:__<?
                             if  (!$filmproduce) // 일반배급사 권한으로 들어 올때만 뿌려준다.
                             {
                                     ?><!-- <br>__:__ --><?
                             }
                             ?></font>
                             </td>

                             <?
                         }

                         $agree = true ;
                     }
                 }

                 //mysql_free_result($QrySingo1) ; ?????
                 ?>

                 <!-- 당일 합계 -->
                 <td class=textarea width=50 bgcolor=<?=$Color3?> align=center>
                 <?=$NBSP?>당일<?=$NBSP?><br><?=$NBSP?>합계<?=$NBSP?>
                 </td>

                 <!-- 전일 합계 -->
                 <td class=textarea width=60 bgcolor=<?=$Color3?> align=center>
                 <?=$NBSP?>전일<?=$NBSP?><br><?=$NBSP?>합계<?=$NBSP?>
                 </td>

                 <!-- 누계 -->
                 <td class=textarea width=60 bgcolor=<?=$Color3?> align=center>
                 <?=$NBSP?>누계<?=$NBSP?>
                 </td>

                 <?
                 if  ($TimJang==false)
                 {
                 ?>
                     <!-- 당일 금액 -->
                     <td class=textarea width=70 bgcolor=<?=$Color3?> align=center>
                     <?=$NBSP?>당일<?=$NBSP?><br><?=$NBSP?>금액<?=$NBSP?>
                     </td>



                     <?
                     $AccWidth = 100 ;
                     ?>


                     <?
                     if  ($WorkGubun == 29)
                     {
                         ?>
                         <!-- 당일 금액 -->
                         <td class=textarea width=70 bgcolor=<?=$Color3?> align=center>
                         <?=$NBSP?>기금적용<?=$NBSP?><br><?=$NBSP?>당일 금액<?=$NBSP?>
                         </td>

                         <!-- 누계 금액 -->
                         <td class=textarea width=<?=$AccWidth?> bgcolor=<?=$Color3?> align=center>
                         <?=$NBSP?>누계<?=$NBSP?><br><?=$NBSP?>금액<?=$NBSP?>
                         </td>
                         <?
                     }
                     else
                     {
                         ?>
                         <!-- 누계 금액 -->
                         <td class=textarea width=<?=$AccWidth?> bgcolor=<?=$Color3?> align=center>
                         <?=$NBSP?>누계<?=$NBSP?><br><?=$NBSP?>금액<?=$NBSP?>
                         </td>

                         <!-- 당일 금액 -->
                         <td class=textarea width=70 bgcolor=<?=$Color3?> align=center>
                         <?=$NBSP?>기금적용<?=$NBSP?><br><?=$NBSP?>당일 금액<?=$NBSP?>
                         </td>

                         <!-- 누계 금액 -->
                         <td class=textarea width=<?=$AccWidth?> bgcolor=<?=$Color3?> align=center>
                          <?=$NBSP?>기금적용<?=$NBSP?><br><?=$NBSP?>누계 금액<?=$NBSP?>
                         </td>

                         <!-- 비고 -->
                         <td class=textarea width=100 bgcolor=<?=$Color3?> align=center>
                         <?=$NBSP?>비고<?=$NBSP?><br>
                         <a href='#' onclick="bigo_click('<?=$singoSilmooja?>','<?=$singoTheather.$singoRoom?>','<?=$FilmTile?>')">
                         [등록]
                         </a>
                         <?
                     }
                     ?>
                 <?
                 }
                 ?>
                 </td>

                 <?
                    $DataRow = 1 ; // 비고를 컬럼을 찍기 위해 첫번째 셀만 늘여찍는다.
                 ?>
           </tr>

           <?
           //$SumOf01Degree = 0 ; // 01 회차 합계
           //$SumOf02Degree = 0 ; // 02 회차 합계
           //$SumOf03Degree = 0 ; // 03 회차 합계
           //$SumOf04Degree = 0 ; // 04 회차 합계
           //$SumOf05Degree = 0 ; // 05 회차 합계
           //$SumOf06Degree = 0 ; // 06 회차 합계
           //$SumOf07Degree = 0 ; // 07 회차 합계
           //$SumOf08Degree = 0 ; // 08 회차 합계
           //$SumOf09Degree = 0 ; // 09 회차 합계
           //$SumOf10Degree = 0 ; // 10 회차 합계
           //$SumOf11Degree = 0 ; // 11 회차 합계

           $SumOf99Degree = 0 ; // 심야 회차 합계
           $SumOfPsToday  = 0 ; // 당일 합계 합계
           $SumOfPsAgoDay = 0 ; // 전일 합계 합계
           $SumOfPsAcc    = 0 ; // 누계 합계
           $SumOfAmToday  = 0 ; // 당일금액 합계
           $SumOfAmToday3 = 0 ; // 당일금액 합계
           $SumOfAmAcc    = 0 ; // 누계금액 합계
           $SumOfAmAcc3   = 0 ; // 누계금액 합계

           $isFinishBlock = false ;

           // 오늘의 요금대역을 구한다. - 각 요금별로 데이타를 찍는다.
           $sQuery = "Select distinct UnitPrice                ".
                     "  From ".$sSingoName."                   ".
                     " Where SingoDate  <= '".$WorkDate."'     ".
                     "   And Theather   = '".$singoTheather."' ".
                     "   And Open       = '".$singoOpen."'     ".
                     "   And Film       = '".$singoFilm."'     ".
                     " Order By UnitPrice desc                 " ;
           $QrySingo3 = mysql_query($sQuery,$connect) ;
           while  ($UnitPrice_data = mysql_fetch_array($QrySingo3))
           {
                $SumOfToday = 0 ;

                $UnitPrice  = $UnitPrice_data["UnitPrice"] ;  // 요금별 스코어..
                ?>



           <!--             -->
           <!-- 데이타 찍기 -->
           <!--             -->
           <tr>
                <?
                // 요금 (0=미지정) 을 찍는다.
                if  ($UnitPrice > 0)
                {
                    if  ($WorkGubun != 29)
                    {
                        //if ($ToExel) { $sValue = $UnitPrice ; }
                        /*else */      { $sValue = "$NBSP".number_format($UnitPrice)."$NBSP" ; }
                        ?>
                        <td class=textarea bgcolor=<?=$Color3?> align=right>
                        <?=$sValue?>
                        </td>
                        <?
                    }
                    if  (($WorkGubun == 27) || ($WorkGubun == 29))
                    {
                        //if ($ToExel) { $sValue = round($UnitPrice / $GikumRate) ; }
                        /*else*/       { $sValue = "$NBSP".number_format(round($UnitPrice / $GikumRate))."$NBSP" ; }
                        ?>
                        <td class=textarea bgcolor=<?=$Color3?> align=right>
                        <?=$sValue?>
                        </td>
                        <?
                    }
                }
                else
                {
                    if  ($WorkGubun != 29)
                    {
                    ?>
                    <td class=textarea bgcolor=<?=$Color3?> align=center>
                    미지정
                    </td>
                    <?
                    }
                    if  (($WorkGubun == 27) || ($WorkGubun == 29))
                    {
                    ?>
                    <td class=textarea bgcolor=<?=$Color3?> align=center>
                    미지정
                    </td>
                    <?
                    }
                }



                if  ($isFinished == true)
                {
                    if  ($isFinishBlock == false)
                    {
                        ?>
                        <td class=textarea bgcolor=<?=$Color3?> colspan=11 rowspan=<?=($cntUnitPrice+1)?> align=center valign=middle>
                        종영(<?=substr($FinishDate,2,2)?>/<?=substr($FinishDate,4,2)?>/<?=substr($FinishDate,6,2)?>)처리됨
                        </td>

                        <?
                    }
                    $isFinishBlock = true ;
                }
                else
                {

                    $sQuery = "Select ShowDgree, NumPersons                ".
                              "  From ".$sSingoName."                      ".
                              " Where SingoDate  = '".$WorkDate."'         ".
                              "   And Silmooja   = '".$singoSilmooja."'    ".
                              "   And Theather   = '".$singoTheather."'    ".
                              "   And Room       = '".$singoRoom."'        ".
                              "   And Open       = '".$singoOpen."'        ".
                              "   And Film       = '".$singoFilm."'        ".
                              "   And UnitPrice  = '".$UnitPrice."'        ".
                              " Group By ShowDgree                         ".
                              " Order By ShowDgree                         " ;
                    $QrySingo2 = mysql_query($sQuery,$connect) ;

                    $agree = true ;

                    for ($i = 1 ; $i <= $LastDegree ; $i++)
                    {

                        if  ($agree==true) // 일치하는 자료가 있을 경우 만 한 레코드씩 읽는다.
                        {
                            $NumPersons_data = mysql_fetch_array($QrySingo2) ;
                        }

                        if  ($i<$LastDegree) // 1회 부터 10회 까지..
                        {
                            if  ($NumPersons_data["ShowDgree"] == sprintf("%02d",$i))
                            {
                                $SumOfDegree = "SumOf".sprintf("%02d",$i)."Degree" ;

                                $arrySumOfDegree[$i] += ($NumPersons_data["NumPersons"]) ; // 회차별 스코어 합계

                                $SumOfToday   += $NumPersons_data["NumPersons"] ;
                                $$SumOfDegree += $NumPersons_data["NumPersons"] ;
                                ?>

                                <td class=textarea bgcolor=<?=$Color3?> align=right>
                                <?=$NBSP?><?=$NumPersons_data["NumPersons"]?><?=$NBSP?>
                                </td>

                                <?

                                $agree = true ;
                            }
                            else
                            {
                                ?>
                                <td class=textarea bgcolor=<?=$Color3?> align=right><?=$NBSP?></td>
                                <?

                                $agree = false ;
                            }
                        }
                        else // 심야
                        {
                            if  ($NumPersons_data["ShowDgree"] == "99")
                            {
                                $arrySumOfDegree[$i] += $NumPersons_data["NumPersons"] ; // 회차별 스코어 합계

                                $SumOfToday    += $NumPersons_data["NumPersons"] ;
                                $SumOf99Degree += $NumPersons_data["NumPersons"] ;

                                ?>

                                <td class=textarea bgcolor=<?=$Color3?> align=right>
                                <?=$NBSP?><?=$NumPersons_data["NumPersons"]?><?=$NBSP?>
                                </td>

                                <?

                                $agree = true ;
                            }
                            else
                            {
                                ?>
                                <td class=textarea bgcolor=<?=$Color3?> align=center><?=$NBSP?></td>
                                <?

                                $agree = false ;
                            }
                        }
                    }
                }




                // 당일합계 출력
                $SumOfPsToday += $SumOfToday ; // 당일 합계 합계

                //if ($ToExel) { $sValue = $SumOfToday; }
                /*else*/       { $sValue = "$NBSP".number_format($SumOfToday)."$NBSP"; }
                ?>
                <td class=textarea bgcolor=<?=$Color3?> align=right>
                <?=$sValue?>
                </td>
                <?


                $sQuery = "Select Sum(ModifyScore) As SumOfModifyScore   ".
                          "  From bas_modifyscore                        ".
                          " Where Theather   = '".$singoTheather."'      ".
                          //"   And Room       = '".$singoRoom."'        ".
                          "   And Open       = '".$singoOpen."'          ".
                          "   And Film       = '".$singoFilm."'          ".
                          "   And UnitPrice  = '".$UnitPrice."'          ".
                          "   And ModifyDate = '".$AgoDate."'            " ;
                $qry_Agomodifyscore  = mysql_query($sQuery,$connect) ;
                if  ($Agomodifyscore_data = mysql_fetch_array($qry_Agomodifyscore))
                {
                    $AgoModifyScore = $Agomodifyscore_data["SumOfModifyScore"] ;
                }
                else
                {
                    $AgoModifyScore = 0 ;
                }
                // 전일합계
                $sQuery = "Select Sum(NumPersons) As SumNumPersons    ".
                          "  From ".$sSingoName."                     ".
                          " Where SingoDate  = '".$AgoDate."'         ".
                          "   And Theather   = '".$singoTheather."'   ".
                          "   And Room       = '".$singoRoom."'       ".
                          "   And Open       = '".$singoOpen."'       ".
                          "   And Film       = '".$singoFilm."'       ".
                          "   And UnitPrice  = '".$UnitPrice."'       " ;
                $QrySingo2 = mysql_query($sQuery,$connect) ;
                if  ($NumPersons_data = mysql_fetch_array($QrySingo2))
                {
                    $SumOfPsAgoDay += ($NumPersons_data["SumNumPersons"]+$AgoModifyScore) ; // 전일 합계 합계

                    // 전일합계 출력
                    //if ($ToExel) { $sValue = $NumPersons_data["SumNumPersons"]+$AgoModifyScore ; }
                    /*else*/       { $sValue = "$NBSP".number_format($NumPersons_data["SumNumPersons"]+$AgoModifyScore)."$NBSP" ; }
                    ?>
                    <td class=textarea bgcolor=<?=$Color3?> align=right>
                    <?=$sValue?>
                    </td>
                    <?
                }
                else
                {
                    // 전일합계 출력
                    ?>
                    <td class=textarea bgcolor=<?=$Color3?> align=center><?=$NBSP?></td>
                    <?
                }

                $SumOfAmToday += $SumOfToday * $UnitPrice  ; // 당일금액 합계
                if  ($WorkDate<"20070701")
                {
                     $SumOfAmToday3 += $SumOfToday  * $UnitPrice  ; // 당일금액 합계
                }
                else
                {
                    $SumOfAmToday3 += $SumOfToday  * round($UnitPrice / $GikumRate)  ; // 당일금액 합계
                }

                $FilmOpen = substr($FilmTile,0,6) ;
                $FilmCode = substr($FilmTile,6,2) ;

                if  ($FilmCode=='00')
                {
                    $CondOpenFilm = " And Open = '".$FilmOpen."' " ;
                }
                else
                {
                    $CondOpenFilm = " And Open = '".$FilmOpen."' ".
                                    " And Film = '".$FilmCode."' " ;
                }

                ////////////////////////////////////////////////////////////////////////////

                $CondFilmType = " And FilmType = '".$singoFilmType."' " ;   //echo $CondFilmType  ;
                $CondFilmTypeNo = " And FilmType = '".$nFilmTypeNo."' " ;   //echo $CondFilmTypeNo  ;


                // 누계 캐쉬 존재 검사..
                $sQuery = "Select Accu, TotAccu, AcMoney, TotAcMoney  \n".
                          "  From ".$sAccName."                       \n". // $sAccName
                          " Where WorkDate   = '".$WorkDate."'        \n".
                          "   And Theather   = '".$singoTheather."'   \n".
                          "   And Open       = '".$singoOpen."'       \n".
                          "   And Film       = '".$singoFilm."'       \n".
                          $CondFilmTypeNo."                           \n".  // 0 이면 필름 구분 없는 모두의 누계
                          "   And UnitPrice  = '".$UnitPrice."'       \n" ;  //if  ($singoTheather=="1137")	  eq($sQuery);
                $QryAccumulate = mysql_query($sQuery,$connect) ;
                if  ($AryAccumulate = mysql_fetch_array($QryAccumulate))
                {
                    $AccuScore = $AryAccumulate["Accu"] ;
                    $AccuMoney = $AryAccumulate["AcMoney"]  ;
                }
                else // 없으면
                {
                    // 당일누계 - 가격별
                    if ($nFilmTypeNo == "0")
                    {
                        $sQuery = "Select Sum(NumPersons) As SumNumPersons,  \n".
                                  "       Sum(TotAmount)  As SumTotAmount    \n".
                                  "  From ".$sSingoName."                    \n".
                                  " Where SingoDate  <= '".$WorkDate."'      \n".
                                  "   And Theather   = '".$singoTheather."'  \n".
                                  $CondOpenFilm."                            \n". // 필름 구분없이 해당필름 전체
                                  "   And UnitPrice  = '".$UnitPrice."'      \n" ;
                    }
                    else
                    {
                        $sQuery = "Select Sum(NumPersons) As SumNumPersons,  \n".
                                  "       Sum(TotAmount)  As SumTotAmount    \n".
                                  "  From ".$sSingoName."                    \n".
                                  " Where SingoDate  <= '".$WorkDate."'      \n".
                                  "   And Theather   = '".$singoTheather."'  \n".
                                  $CondOpenFilm."                            \n".   //
                                  $CondFilmType."                            \n".   // 해당필름타입만..
                                  "   And UnitPrice  = '".$UnitPrice."'      \n" ;
                    }
                    $QrySumSingo = mysql_query($sQuery,$connect) ;     //if  ($singoTheather=="1137") eq($sQuery);
                    if  ($ArySumSingo = mysql_fetch_array($QrySumSingo))
                    {
                        $AccuScore = $ArySumSingo["SumNumPersons"] ;
                        $AccuMoney = $ArySumSingo["SumTotAmount"] ;

                        $sQuery = "Insert Into ".$sAccName."           \n".  // $sAccName
                                  "Values                              \n".
                                  "(                                   \n".
                                  "    '".$WorkDate."',                \n".
                                  "    '".$singoSilmooja."',           \n".
                                  "    '".$singoTheather."',           \n".
                                  "    '".$singoOpen."',               \n".
                                  "    '".$singoFilm."',               \n".
                                  "    '".$nFilmTypeNo."',             \n".  // 0 이면 필름 구분 없는 모두의 누계
                                  "    '".$UnitPrice."',               \n".
                                  "    '".$AccuScore."',               \n".
                                  "    '0',                            \n".
                                  "    '".$AccuMoney."',               \n".
                                  "    '0',                            \n".
                                  "    '".$showroomLocation."',        \n".
                                  "    '".$SumOfToday."',              \n".
                                  "    '".$SumOfToday*$UnitPrice."'    \n".
                                  ")                                   \n" ;  //if  ($singoTheather=="1137") eq($sQuery);
                        mysql_query($sQuery,$connect) ;

                    }
                }

                //if ($ToExel) { $sValue = $AccuScore ; }
                /*else*/       { $sValue = "$NBSP".number_format($AccuScore)."$NBSP" ; }
                ?>
                <td class=textarea bgcolor=<?=$Color3?> align=right>
                <?=$sValue?>
                </td>




                <?
                $SumOfPsAcc += $AccuScore ;    // 누계스코어 합계

                if  ($TimJang==false)
                {
                    //if ($ToExel) { $sValue = ($SumOfToday)*$UnitPrice ; }
                    /*else*/       { $sValue = "$NBSP".number_format(($SumOfToday)*$UnitPrice)."$NBSP" ; }
                    ?>
                    <td class=textarea bgcolor=<?=$Color3?> align=right>
                    <?=$sValue?>
                    </td>
                    <?
                }

                if  ($WorkGubun != 29)
                {
                    if  ($TimJang==false)
                    {
                        // 누계금액
                        //if ($ToExel) { $sValue = $AccuMoney ; }
                        /*else*/       { $sValue = "$NBSP".number_format($AccuMoney)."$NBSP" ; }
                        ?>
                        <td class=textarea bgcolor=<?=$Color3?> align=right>
                        <?=$sValue?>
                        </td>
                        <?

                        $SumOfAmAcc += $AccuMoney ; // 누계금액 합계
                    }
                    ?>
					<!-- 기금적용 당일 금액  -->
                    <td class=textarea bgcolor=<?=$Color3?> align=right>
                        <?
                        if  ($WorkDate<"20070701")
                        {
                            //if ($ToExel) { $sValue = ($SumOfToday)*$UnitPrice ; }
                            /*else*/       { $sValue = "$NBSP".number_format(($SumOfToday)*$UnitPrice)."$NBSP" ; }
                        }
                        else
                        {
                            //if ($ToExel) { $sValue = ($SumOfToday)*round($UnitPrice/$GikumRate) ; }
                            /*else*/       { $sValue = "$NBSP".number_format(($SumOfToday)*round($UnitPrice / $GikumRate))."$NBSP" ; }
                        }
                        ?>
                        <?=$sValue?>
                    </td>
                    <?


                    $SumNumPersons7Ago = 0 ;
                    /*
                    $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                              "  From ".$sSingoName."                    ".
                              " Where SingoDate  < '20070701'            ".
                              "   And Theather   = '".$singoTheather."'  ".
                              $CondOpenFilm."                            ".
                              "   And UnitPrice  = '".$UnitPrice."'      " ;
                    $Qry7Ago = mysql_query($sQuery,$connect) ;
                    if  ($Arr7Ago = mysql_fetch_array($Qry7Ago))
                    {
                        $SumNumPersons7Ago = $Arr7Ago["SumNumPersons"] * $UnitPrice ;
                    }

                    $SumNumPersons7Aft = 0 ;
                    $sQuery = "Select Sum(NumPersons) As SumNumPersons   ".
                              "  From ".$sSingoName."                    ".
                              " Where SingoDate  <= '".$WorkDate."'        ".
                              "   And SingoDate  >= '20070701'           ".
                              "   And Theather   = '".$singoTheather."'  ".
                              $CondOpenFilm."                            ".
                              "   And UnitPrice  = '".$UnitPrice."'      " ;
                    $Qry7Aft = mysql_query($sQuery,$connect) ;
                    if  ($Arr7Aft = mysql_fetch_array($Qry7Aft))
                    {
                        $SumNumPersons7Aft = $Arr7Aft["SumNumPersons"] * round($UnitPrice / $GikumRate) ;
                    }
                    */
                    $SumNumPersons7Aft = $AccuScore * round($UnitPrice / $GikumRate) ;


                    $SumOfAmAcc3 += ($SumNumPersons7Ago+$SumNumPersons7Aft) ;

                    //if ($ToExel) { $sValue = $SumNumPersons7Ago+$SumNumPersons7Aft ; }
                    /*else*/       { $sValue = "$NBSP".number_format($SumNumPersons7Ago+$SumNumPersons7Aft)."$NBSP" ; }
                    ?>
					<!-- 기금적용 누계 금액  -->
                    <td class=textarea bgcolor=<?=$Color3?> align=right>
                    <?=$sValue?>
                    </td>


                    <?
                    if  ($TimJang==false)
                    {
                        // 비고 컬럼
                        if  ($DataRow==1) // 첫번째 데이타를 찍을때만 한번 늘여그린다.
                        {
                            $sQuery = "Select Count(*) As CntMagam              ".
                                      "  From wrk_magam                         ".
                                      " Where WorkDate = '".$WorkDate."'        ".
                                      "   And Theather = '".$singoTheather."'   ".
                                      "   And Room     = '".$singoRoom."'       ".
                                      "   And Open     = '".$singoOpen."'       ".
                                      "   And Film     = '".$singoFilm."'       " ;
                            $QryMagam = mysql_query($sQuery,$connect) ;
                            if  ($ArrMagam = mysql_fetch_array($QryMagam))
                            {
                                if  ($ArrMagam["CntMagam"] > 0)
                                {
                                    $IsMagam = true ;
                                }
                                else
                                {
                                    $IsMagam = false ;
                                }
                                $sQuery = "Select WorkTime From wrk_magam           ".
                                          " Where WorkDate = '".$WorkDate."'        ".
                                          "   And Theather = '".$singoTheather."'   ".
                                          "   And Room     = '".$singoRoom."'       ".
                                          "   And Open     = '".$singoOpen."'       ".
                                          "   And Film     = '".$singoFilm."'       " ;
                                $QryMagam = mysql_query($sQuery,$connect) ;
                                if  ($ArrMagam = mysql_fetch_array($QryMagam))
                                {
                                    $MagamWorkTime = substr($ArrMagam["WorkTime"],0,2).":".substr($ArrMagam["WorkTime"],2,2) ;
                                }
                            }

                            $sQuery = "Select Bigo From wrk_showroombigo          ".
                                      " Where Theather   = '".$singoTheather."'   ".
                                      "   And Room       = '".$singoRoom."'       ".
                                      "   And Open       = '".$singoOpen."'       ".
                                      "   And Film       = '".$singoFilm."'       " ;
                            $QrySingo2 = mysql_query($sQuery,$connect) ;
                            if  ($ShowroomBigo_data = mysql_fetch_array($QrySingo2))
                            {
                                ?>
                                <td class=textarea bgcolor=<?=$Color3?> rowspan=<?=($cntUnitPrice+1)?> align=left>
                                <div id=bigo<?=$singoTheather.$singoRoom.$singoOpen.$singoFilm?>>
                                <?=str_replace("\r","",str_replace("\n","<br>",$ShowroomBigo_data["Bigo"]))?>
                                </div>
                                <?
                                if  ($IsMagam==true)
                                {
                                    ?><!-- <FONT COLOR="#FF0000"><B>마감</B><br><?=$MagamWorkTime?></FONT> --><?
                                }
                                ?>
                                </td>
                                <?
                            }
                            else
                            {
                                ?>
                                <td class=textarea bgcolor=<?=$Color3?> rowspan=<?=($cntUnitPrice+1)?> align=center>
                                <div id=bigo<?=$singoTheather.$singoRoom.$singoOpen.$singoFilm?>><?=$NBSP?></div>
                                <?
                                if  ($IsMagam==true)
                                {
                                    ?><!-- <FONT COLOR="#FF0000"><B>마감</B><br><?=$MagamWorkTime?></FONT> --><?
                                }
                                ?>
                                </td>
                                <?
                            }
                        }
                    }
                    $DataRow++;
                }
                else
                {
                    ?>
                    <td class=textarea bgcolor=<?=$Color3?> align=right>
                        <?
                        if  ($WorkDate<"20070701")
                        {
                            //if ($ToExel) { $sValue = ($SumOfToday)*$UnitPrice ; }
                            /*else*/       { $sValue = "$NBSP".number_format(($SumOfToday)*$UnitPrice)."$NBSP" ; }
                        }
                        else
                        {

                            //if ($ToExel) { $sValue = ($SumOfToday)*round($UnitPrice / $GikumRate) ; }
                            /*else*/       { $sValue = "$NBSP".number_format(($SumOfToday)*round($UnitPrice / $GikumRate))."$NBSP" ; }
                        }
                        ?>
                        <?=$sValue?>
                    </td>
                    <?
                    if  ($TimJang==false)
                    {
                        // 누계금액
                        //if ($ToExel) { $sValue = $AccuMoney ; }
                        /*else*/       { $sValue = "$NBSP".number_format($AccuMoney)."$NBSP" ; }
                        ?>
                        <td class=textarea bgcolor=<?=$Color3?> align=right>
                        <?=$sValue?>
                        </td>
                        <?

                        $SumOfAmAcc += $AccuMoney ; // 누계금액 합계
                    }


                }
                ?>

           </tr>

           <?
           }

           mysql_free_result($QrySingo3) ;
           ?>


           <!--             -->
           <!--  합 계 찍기 -->
           <!--             -->
           <tr>
                <!-- 합계 -->
                <?
                if  ($WorkGubun != 29)
                {
                    ?>
                    <td class=textarea bgcolor=<?=$Color4?> class=tblsum align=center>합계</td>
                    <?
                }

                if  (($WorkGubun == 27) || ($WorkGubun == 29))
                {
                    ?>
                    <td class=textarea bgcolor=<?=$Color4?> class=tblsum align=center>합계</td>
                    <?
                }


                if  ($isFinished == false)
                {
                    // 회차별 스코어 합계
                    for ($i = 1 ; $i <= $LastDegree ; $i++)
                    {
                       //if ($ToExel) { $sValue = $arrySumOfDegree[$i] ; }
                       /*else*/       { $sValue = "$NBSP".number_format($arrySumOfDegree[$i])."$NBSP" ; }
                       ?>
                       <td class=textarea bgcolor=<?=$Color4?> align=right>
                       <?=$sValue?>
                       </td>
                       <?
                    }
                }
                ?>

                <!-- 당일 합계 합계 -->
                <?
                //if ($ToExel) { $sValue = $SumOfPsToday ; }
                /*else*/       { $sValue = "$NBSP".number_format($SumOfPsToday)."$NBSP" ; }
                ?>
                <td class=textarea bgcolor=<?=$Color4?> align=right>
                <?=$sValue?>
                </td>

                <!-- 전일 합계 합계 -->
                <?
                //if ($ToExel) { $sValue = $SumOfPsAgoDay ; }
                /*else*/       { $sValue = "$NBSP".number_format($SumOfPsAgoDay)."$NBSP" ; }
                ?>
                <td class=textarea bgcolor=<?=$Color4?> align=right>
                <?=$sValue?>
                </td>

                <!-- 누계 합계 -->
                <?
                //if ($ToExel) { $sValue = $SumOfPsAcc ; }
                /*else*/       { $sValue = "$NBSP".number_format($SumOfPsAcc)."$NBSP" ; }
                ?>
                <td class=textarea bgcolor=<?=$Color4?> align=right>
                <?=$sValue?>
                </td>


                <?
                if  ($TimJang==false)
                {
                    //if ($ToExel) { $sValue = $SumOfAmToday ; }
                    /*else*/       { $sValue = "$NBSP".number_format($SumOfAmToday)."$NBSP" ; }
                    ?>
                    <!-- 당일금액 합계 -->
                    <td class=textarea bgcolor=<?=$Color4?> align=right>
                    <?=$sValue?>
                    </td>
                    <?
                }

                if  ($WorkGubun != 29)
                {
                    if  ($TimJang==false)
                    {
                        ?>
                        <!-- 누계금액 합계 -->
                        <?
                        //if ($ToExel) { $sValue = $SumOfAmAcc ; }
                        /*else*/       { $sValue = "$NBSP".number_format($SumOfAmAcc)."$NBSP" ; }
                        ?>
                        <td class=textarea bgcolor=<?=$Color4?> align=right>
                        <?=$sValue?>
                        </td>

                        <!-- 당일금액 합계 -->
                        <?
                        //if ($ToExel) { $sValue = $SumOfAmToday3 ; }
                        /*else*/       { $sValue = "$NBSP".number_format($SumOfAmToday3)."$NBSP" ; }
                        ?>
                        <td class=textarea bgcolor=<?=$Color4?> align=right>
                        <?=$sValue?>
                        </td>

                        <!-- 누계금액 합계 -->
                        <?
                        //if ($ToExel) { $sValue = $SumOfAmAcc3 ; }
                        /*else*/       { $sValue = "$NBSP".number_format($SumOfAmAcc3)."$NBSP" ; }
                        ?>
                        <td class=textarea bgcolor=<?=$Color4?> align=right>
                        <?=$sValue?>
                        </td>
                        <?
                    }
                }
                else
                {
                    if  ($TimJang==false)
                    {
                        ?>
                        <!-- 당일금액 합계 -->
                        <?
                        //if ($ToExel) { $sValue = $SumOfAmToday3 ; }
                        /*else*/       { $sValue = "$NBSP".number_format($SumOfAmToday3)."$NBSP" ; }
                        ?>
                        <td class=textarea bgcolor=<?=$Color4?> align=right>
                        <?=$sValue?>
                        </td>

                        <!-- 누계금액 합계 -->
                        <?
                        //if ($ToExel) { $sValue = $SumOfAmAcc ; }
                        /*else*/       { $sValue = "$NBSP".number_format($SumOfAmAcc)."$NBSP" ; }
                        ?>
                        <td class=textarea bgcolor=<?=$Color4?> align=right>
                        <?=$sValue?>
                        </td>

                        <?
                    }
                }
                ?>
           </tr>
           </table>
           <?
           } ///////////////////
       }
   }
   ?>

