   <?
echo "\n" ;
echo "일자"; 
echo (','); 
echo "지역"; 
echo (','); 
echo "스크린코드";
echo (','); 
echo "극장코드";
echo (','); 
echo "극장명"; 
echo (','); 
echo "좌석"; 
echo (','); 
echo "회차"; 
echo (','); 
echo "요금"; 
echo (','); 
echo "스코어"; 
echo (','); 
echo "합계"; 
echo (','); 
echo "당일합계"; 
echo (','); 
echo "전일합계"; 
echo (','); 
echo "누계"; 
echo (','); 
echo "당일금액"; 
echo (','); 
echo "누계금액";
echo "\n" ;

   while ($singo_data = mysql_fetch_array($qry_singo))
   {
        for ($i = 1 ; $i <= 12 ; $i++)
        {
            $arrySumOfDegree[$i] = 0 ;  // 회차별 스코어 합계
        }

        $singoSilmooja    = $singo_data["Silmooja"] ;      // 신고실무자
        $singoTheather    = $singo_data["Theather"] ;      // 신고상영관
        $singoRoom        = $singo_data["Room"] ;          //
        $singoOpen        = $singo_data["Open"] ;          // 신고영화
        $singoFilm        = $singo_data["Film"] ;          //
        $silmoojaName     = $singo_data["SilmoojaName"] ;  // 신고 실무자명  
        $showroomDiscript = $singo_data["Discript"] ;      // 신고 상영관명  
        $showroomLocation = $singo_data["Location"] ;      // 신고 상영관지역
        $locationName     = $singo_data["LocationName"] ;  // 신고 상영관지역명
        $showroomSeat     = $singo_data["ShowRoomSeat"] ;  // 신고 상영관좌석            
        $SumNumPersons    = $singo_data["SumNumPersons"] ; // 총 스코어
        $showroomCntDgree = $singo_data["CntDgree"] ;      // 상영회차수
        $cntUnitPrice     = $singo_data["cntUnitPrice"] ;  // 요금종류개수 (지역.상영관 컬럼의 확장을 위해 쓰임)     

        $sSingoName = get_singotable($singoOpen,$singoFilm,$connect) ;  // 신고 테이블 이름..
        $sAccName   = get_acctable($singoOpen,$singoFilm,$connect) ;  // accumulate 이름..

        // 종영여부를 검사한다.
        $sQuery = "Select * From bas_silmoojatheatherfinish  ".
                  " Where Silmooja = '".$singoSilmooja."'    ".
                  "   And WorkDate <= '".$WorkDate."'        ".
                  "   And Theather = '".$singoTheather."'    ".
                  "   And Room     = '".$singoRoom."'        ".
                  "   And Open     = '".$singoOpen."'        ".
                  "   And Film     = '".$singoFilm."'        " ;
        $qry_silmoojatheatherfinish = mysql_query($sQuery,$connect) ;
        if  ($silmoojatheatherfinish_data = mysql_fetch_array($qry_silmoojatheatherfinish))
        { 
            $isFinished = true ;                                   // 종영이 되었음

            $TempDate = $silmoojatheatherfinish_data["WorkDate"] ; // 종영일자 

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
        if  ($filmtitleNameTitle != $singo_data["FilmTitleName"])
        {
            $filmtitleName      = $singo_data["FilmTitleName"] ;
            $filmtitleNameTitle = $singo_data["FilmTitleName"] ;
        }
        else
        {
            $filmtitleName = "" ;
        }

        mysql_free_result($qry_silmoojatheatherfinish) ;



        // 총 상영좌석수 = 회차수 * 상영관 자리수 
        $showroomTotDgree = $showroomCntDgree * $showroomSeat ;  

/**************************?><table border=1>

         <!--             -->
         <!-- 타이틀 찍기 -->
         <!--             -->
         <tr>
              <td>일자</td>

              <td>지역</td>
              
              <td>극장</td>

              <td>좌석</td>

              <td>회차</td>

              <td>요금</td>

              <td>스코어</td>

              <td>합계</td>

              <td>당일합계</td>
              
              <td>전일합계</td>
              
              <td>누계</td>
              
              <td>당일금액</td>
              
              <td>누계금액</td>               
         </tr><?**************************/
    
         $SumOf99Degree = 0 ; // 심야 회차 합계
         $SumOfPsToday  = 0 ; // 당일 합계 합계
         $SumOfPsAgoDay = 0 ; // 전일 합계 합계

         $isFinishBlock = false ; // 종영여부

         for ($i = 1 ; $i <= 11 ; $i++)
         {
             if  ($i<11) $ShowDegree = sprintf("%02d",$i) ; // 1회 부터 10회 까지..
             else        $ShowDegree = "99" ;              // 심야

             
             $qry_dgree = mysql_query("Select distinct UnitPrice, NumPersons      ".
                                      "  From ".$sSingoName."                     ".
                                      " Where SingoDate  = '".$WorkDate."'        ".
                                      "   And Theather   = '".$singoTheather."'   ".
                                      "   And Room       = '".$singoRoom."'       ".
                                      "   And Open       = '".$singoOpen."'       ".
                                      "   And Film       = '".$singoFilm."'       ".
                                      "   And ShowDgree  = '".$ShowDegree."'      ".
                                      " Order By UnitPrice desc                   ",$connect) ;

             $affected_row = mysql_affected_rows() ;

             if  ($affected_row > 0)
             {
                 while ($dgree_data = mysql_fetch_array($qry_dgree))
                 {
                       $UnitPrice  = $dgree_data["UnitPrice"] ;
                       $NumPersons = $dgree_data["NumPersons"] ;
                       ?>
<?/******?>
                       <tr>       
                            <!-- 일자 -->
                            <td><B><?=$WorkDate?></B></td>
<?******/echo $WorkDate ; 
echo (',');?>
<?/******?>
                            <!-- 지역 -->
                            <td><?=$locationName?></td>
<?******/echo $locationName ; 
echo (',');
echo $singoRoom ;
echo (',');
echo $singoTheather ;
echo (',');?>
<?/******?>
                            <!-- 상영관명 -->
                            <td><?=$showroomDiscript?></td>
<?******/echo $showroomDiscript ; 
echo (',');?>
<?/******?>
                            <!-- 좌석수 -->
                            <td><?=$showroomSeat?></td>
<?******/echo $showroomSeat ; 
echo (',');?>
<?/******?>
                            <!-- 회차 -->
                            <td><?=$ShowDegree?></td>
<?******/echo $ShowDegree ; 
echo (',');?>
<?/******?>
                            <!-- 요금 (0=미지정) -->
<?******/

                            if  ($UnitPrice > 0) 
                            {
/******?>
                                <td><?=$UnitPrice?></td>
<?******/echo $UnitPrice ; 
echo (',');
                            }
                            else
                            {
/******?>
                                <td>미지정</td>
<?******/echo "미지정" ; 
echo (',');?>
<?
                            }

/******
                            if  ($isFinished == true)
                            {                            
                                if  ($isFinishBlock == false)
                                {                      
                                    $cntUnitPriceP1 = $cntUnitPrice+1 ;

                                    ?>
                                    <td rowspan=<?=$cntUnitPriceP1?>>
                                    종영(<?=substr($FinishDate,2,2)?>/<?=substr($FinishDate,4,2)?>/<?=substr($FinishDate,6,2)?>)처리됨
                                    </td>

                                    <?
                                }
                                $isFinishBlock = true ;
                            }
                            else
                            {     
                                
                            }
*********/?>
<?/******?>
                            <!-- 스코어 -->
                            <td><?=$NumPersons?></td>
<?******/echo $NumPersons ; 
echo (',');
                            $qry_SumNumPersons = mysql_query("Select Sum(NumPersons) As SumNumPersons    ".
                                                             "  From ".$sSingoName."                     ".
                                                             " Where SingoDate  = '".$WorkDate."'        ".
                                                             "   And Theather   = '".$singoTheather."'   ".
                                                             "   And Room       = '".$singoRoom."'       ".
                                                             "   And Open       = '".$singoOpen."'       ".
                                                             "   And Film       = '".$singoFilm."'       ".
                                                             "   And ShowDgree  = '".$ShowDegree."'      ",$connect) ;
                            if  ( $SumNumPersons_data = mysql_fetch_array($qry_SumNumPersons) )
                            {
                                $SumOfDegree = $SumNumPersons_data["SumNumPersons"] ;
                            }
                            else
                            {
                                $SumOfDegree = 0 ;
                            }
?>
<?/******?>
                            <!-- 합계 -->
                            <td><?=$SumOfDegree?></td>
<?******/echo $SumOfDegree ; 
echo (',');
                            $qry_SumNumPersons = mysql_query("Select Sum(NumPersons) As SumNumPersons    ".
                                                             "  From ".$sSingoName."                     ".
                                                             " Where SingoDate  = '".$WorkDate."'        ".
                                                             "   And Theather   = '".$singoTheather."'   ".
                                                             "   And Room       = '".$singoRoom."'       ".
                                                             "   And Open       = '".$singoOpen."'       ".
                                                             "   And Film       = '".$singoFilm."'       ".
                                                             "   And UnitPrice  = '".$UnitPrice."'       ",$connect) ;
                            if  ( $SumNumPersons_data = mysql_fetch_array($qry_SumNumPersons) )
                            {
                                $SumOfUnitPrice = $SumNumPersons_data["SumNumPersons"] ;
                            }
                            else
                            {
                                $SumOfUnitPrice = 0 ;
                            }
?>
<?/******?>
                            <!-- 당일합계 -->
                            <td><?=$SumOfUnitPrice?></td>
<?******/echo $SumOfUnitPrice ; 
echo (',');
                            $qry_singo2 = mysql_query("Select Sum(NumPersons) As SumNumPersons   ".
                                                      "  From ".$sSingoName."                    ".
                                                      " Where SingoDate  = '".$AgoDate."'        ".
                                                      "   And Theather   = '".$singoTheather."'  ".
                                                      "   And Room       = '".$singoRoom."'      ".
                                                      "   And Open       = '".$singoOpen."'      ".
                                                      "   And Film       = '".$singoFilm."'      ".
                                                      "   And UnitPrice  = '".$UnitPrice."'      ",$connect) ;
                            if  ($singo2_data = mysql_fetch_array($qry_singo2))
                            {
                                $SumAgoDay = $singo2_data["SumNumPersons"]+0 ;
                                                           
                                $SumOfPsAgoDay += $SumAgoDay ; // 전일 합계 합계
                            }
                            else
                            {                                
                                $SumAgoDay = "0" ;
                                
                            }
?>
<?/******?>
                            <!-- 전일합계 출력 -->
                            <td><?=$SumAgoDay?></td>
<?******/echo $SumAgoDay ; 
echo (',');
                            $qry_accumulate = mysql_query("Select Accu, TotAccu, AcMoney, TotAcMoney  ".
                                                          "  From ".$sAccName."                      ".
                                                          " Where WorkDate   = '".$WorkDate."'        ".
                                                          "   And Theather   = '".$singoTheather."'   ".
                                                          "   And Open       = '".$singoOpen."'       ".
                                                          "   And Film       = '".$singoFilm."'       ".
                                                          "   And UnitPrice  = '".$UnitPrice."'       ",$connect) ; 
                            $accumulate_data = mysql_fetch_array($qry_accumulate) ;
                            if  (!$accumulate_data)  // 없으면
                            {     
                                // 당일누계
                                $qry_singo2 = mysql_query("Select Sum(NumPersons) As SumNumPersons,  ".
                                                          "       Sum(TotAmount)  As SumTotAmount    ".
                                                          "  From ".$sSingoName."                   ".
                                                          " Where SingoDate  <= '".$WorkDate."'      ".
                                                          "   And Theather   = '".$singoTheather."'  ".
                                                          "   And Open       = '".$singoOpen."'       ".
                                                          "   And Film       = '".$singoFilm."'       ".
                                                          "   And UnitPrice  = '".$UnitPrice."'      ",$connect) ;
                                $NumPersons_data = mysql_fetch_array($qry_singo2) ;
                                if  ($NumPersons_data)
                                {
                                    mysql_query("Insert Into ".$sAccName."                   ".
                                                "Values                                       ".
                                                "(                                            ".
                                                "    '".$WorkDate."',                         ".
                                                "    '".$singoSilmooja."',                    ".
                                                "    '".$filmsupplyCode."',                   ".
                                                "    '".$singoTheather."',                    ".
                                                "    '".$singoOpen."',                        ".
                                                "    '".$singoFilm."',                        ".
                                                "    '".$UnitPrice."',                        ".
                                                "    '".$NumPersons_data["SumNumPersons"]."', ".
                                                "    '0',                                     ".
                                                "    '".$NumPersons_data["SumTotAmount"]."',  ".
                                                "    '0',                                     ".
                                                "    '".$showroomLocation."',                 ".
                                                "    '".$NumPersons."',                       ".
                                                "    '".$NumPersons*$UnitPrice."'             ".
                                                ")                                            ",$connect) ; 
                                }

                                // 당일누계
                                $qry_singo2 = mysql_query("Select Sum(NumPersons) As SumNumPersons,  ".
                                                          "       Sum(TotAmount)  As SumTotAmount    ".
                                                          "  From ".$sSingoName."                    ".
                                                          " Where SingoDate  <= '".$WorkDate."'      ".
                                                          "   And Theather   = '".$singoTheather."'  ".
                                                          "   And Open       = '".$singoOpen."'      ".
                                                          "   And Film       = '".$singoFilm."'      ",$connect) ;
                                $NumPersons_data = mysql_fetch_array($qry_singo2) ;
                                if  ($NumPersons_data)
                                {
                                    mysql_query("Update ".$sAccName."                                        ".
                                                "   Set TotAccu    = '".$NumPersons_data["SumNumPersons"]."', ".
                                                "       TotAcMoney = '".$NumPersons_data["SumTotAmount"]."',  ".
                                                "       Location   = '".$showroomLocation."',                 ".
                                                "       TodayScore = '".$NumPersons."',                       ".
                                                "       TodayMoney = '".$NumPersons*$UnitPrice."'             ".
                                                " Where WorkDate   = '".$WorkDate."'                          ".
                                                "   And Silmooja   = '".$singoSilmooja."'                     ".
                                                "   And Theather   = '".$singoTheather."'                     ".
                                                "   And Open       = '".$singoOpen."'                         ".
                                                "   And Film       = '".$singoFilm."'                         ",$connect) ; 
                                }
                            }

                            
                            $qry_accumulate = mysql_query("Select Accu, TotAccu, AcMoney, TotAcMoney  ".
                                                          "  From ".$sAccName."                      ".
                                                          " Where WorkDate   = '".$WorkDate."'        ".
                                                          "   And Theather   = '".$singoTheather."'   ".
                                                          "   And Open       = '".$singoOpen."'       ".
                                                          "   And Film       = '".$singoFilm."'       ".
                                                          "   And UnitPrice  = '".$UnitPrice."'       ",$connect) ; 
                            
                            $accumulate_data = mysql_fetch_array($qry_accumulate) ;                            
?>
<?/******?>
                            <!-- 누계 -->
                            <td><?=$accumulate_data["Accu"]?></td>
<?******/echo $accumulate_data["Accu"] ; 
echo (',');?>
<?/******?>
                            <!-- 당일금액 -->
                            <td><?=($SumOfUnitPrice * $UnitPrice)?></td>
<?******/echo ($SumOfUnitPrice * $UnitPrice) ; 
echo (',');?>
<?/******?>
                            <!-- 누계금액 -->
                            <td><?=($accumulate_data["Accu"] * $UnitPrice)?></td>
<?******/                            
echo ($accumulate_data["Accu"] * $UnitPrice) ;
echo "\n";?>
<?/******?>
                       </tr>
<?******/
                 }
             }
         }
   }         
?>