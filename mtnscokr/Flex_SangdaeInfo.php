<?
   set_time_limit(0) ; // 긴 처리작업이 있을 때만 쓴다.....

   //$WorkDate  = $_POST["WorkDate"] ;
   //$FilmTitle = $_POST["FilmTitle"] ;

   $Now       = strtotime('now');
   $sCurrDay  = date("Ymd",$Now) ;    // 현재일...
   $sCurrTime = date("YmdHis",$Now) ; // 현재시간...
   
   $sAgo1Hour = date("YmdHis",strtotime('-60 minutes',$Now)) ;  // 60분전 ..
   $sAgo2Hour = date("YmdHis",strtotime('-120 minutes',$Now)) ; // 120분전 ..

   include "config.php" ;

   // DB접속
   $connect = dbconn() ;

   mysql_select_db($cont_db) ; 


   //
   // 상대영화현황(일자별).............................................................................................................
   //
   if  ($Action=="getSangdaeDate") 
   {       
       $xmlObj = new xmlWriter();
       $xmlObj->openMemory();

       //$xmlObj->startDocument('1.0','euc-kr'); // 반드시 utf-8 로할것..
       $xmlObj->startDocument('1.0','utf-8');
       $xmlObj->startElement ('Totals'); 
       
       //<mx:PieSeries labelPosition="inside" 
       //             	          field="Value" 
       //             	          displayName="FilmName"
       //             	          nameField="FilmName"    <---------
       //             	          labelFunction="displayValue"
       //             	          showDataEffect="{interpolate}">
       //

       if   (($ZoneLoc == "9999") || ($ZoneLoc == "0")) // 전체
       {
            // 영화리스트 (전체)
            $sQuery = "Select SangFilm,                   ".
                      "       Sum(Score) As SumOfScore    ".
                      "  From wrk_sangdae                 ".
                      " Where WorkDate = '".$CurrDate."'  ".
                      " Group By SangFilm                 ".
                      " Order By SumOfScore desc          " ;
       }
       else
       {
            if   (strlen($ZoneLoc) == 3) // 지역
            {
                 // 영화리스트 (지역)
                 $sQuery = "Select SangFilm,                    ".
                           "       Sum(Score) As SumOfScore     ".
                           "  From wrk_sangdae                  ".
                           " Where WorkDate = '".$CurrDate."'   ".
                           "   And Location = '".$ZoneLoc."'    ".
                           " Group By SangFilm                  ".
                           " Order By SumOfScore desc           " ;
            }
            if   (strlen($ZoneLoc) == 2)  // 구역
            {
                 // 해당 구역의 모든 지역들을 구한다.
                 $AddedLoc = " and " ;

                 $sQuery = "Select Location                  ".
                           "  From bas_filmsupplyzoneloc     ".
                           " Where Code = '".$FilmSupply."'  ".
                           "   And Zone = '".$ZoneLoc."'     " ;
                 $QryZoneloc = mysql_query($sQuery,$connect) ;
                 while ($ArrZoneloc = mysql_fetch_array($QryZoneloc))
                 {            
                      if  ($AddedLoc == " and ")
                          $AddedLoc .= "( Location = '".$ArrZoneloc["Location"]."' "  ;
                      else
                          $AddedLoc .= " or Location = '".$ArrZoneloc["Location"]."' "  ;
                 }
                 $AddedLoc .= ")" ;                     

                 // 영화리스트 (구역)
                 $sQuery = "Select SangFilm,                    ".
                           "       Sum(Score) As SumOfScore     ".
                           "  From wrk_sangdae                  ".
                           " Where WorkDate = '".$CurrDate."'   ".
                           $AddedLoc."                          ".
                           " Group By SangFilm                  ".
                           " Order By SumOfScore desc           " ;
            }
       }

       $Rankiong = 0 ;
       $QrySangFilm = mysql_query($sQuery,$connect) ;
       while ($ArrSangFilm = mysql_fetch_array($QrySangFilm))
       {
            $Rankiong ++ ;

            if  ($Rankiong <= 10)
            {
                $SangFilmCode = $ArrSangFilm["SangFilm"] ; 

                // 영화명을 구함..
                $sQuery = "Select * From bas_sangfilmtitle   ".
                          " Where Code = '".$SangFilmCode."' " ;
                $QryIndyfilmtitle = mysql_query($sQuery,$connect) ;
                if  ($ArrIndyfilmtitle = mysql_fetch_array($QryIndyfilmtitle))
                {
                    $indyfilmtitleName = $ArrIndyfilmtitle["Name"] ;
                }
                else
                {
                    $indyfilmtitleName = $SangFilmCode ;
                }            

                $sQuery = "Select Sum(Score) As SumOfScore           ".
                          "  From wrk_sangdae                        ".
                          " Where SangFilm = '".$SangFilmCode."'     ".
                          "   And WorkDate = '".$CurrDate."'         " ;
                $QrySumScore = mysql_query($sQuery,$connect) ;
                if   ($ArrSumScore = mysql_fetch_array($QrySumScore)) 
                {
                    $SumOfScore = $ArrSumScore["SumOfScore"] ;

                    $xmlObj->startElement('Total'); 
                    $xmlObj->writeElement('FilmName',mb_convert_encoding($indyfilmtitleName,"UTF-8","EUC-KR"));
                    //$xmlObj->writeElement('FilmName',$indyfilmtitleName);
                    $xmlObj->writeElement('Value',   $SumOfScore) ;
                    $xmlObj->endElement(); 
                }
            }
       }
       
       //<Total><FilmName>영화1</FilmName><Value>3</Value></Total>
       //<Total><FilmName>영화2</FilmName><Value>2</Value></Total>
       //<Total><FilmName>영화3</FilmName><Value>3</Value></Total>
       
       $xmlObj->endElement(); 
     
       print $xmlObj->outputMemory(true);    
   }



   //
   // 상대영화현황(기간별) 1 ......................................................................................................
   //
   if  ($Action=="getSangdaeTerm1") 
   {       
       $xmlObj = new xmlWriter();
       $xmlObj->openMemory();

       //$xmlObj->startDocument('1.0','euc-kr'); // 반드시 utf-8 로할것..
       $xmlObj->startDocument('1.0','UTF-8');
       $xmlObj->startElement ('Totals'); 

       if   (($ZoneLoc == "9999") || ($ZoneLoc == "0")) // 전체
       {
            // 영화리스트 (전체)
            $sQuery = "Select SangFilm,                   ".
                      "       Sum(Score) As SumOfScore    ".
                      "  From wrk_sangdae                 ".
                      " Where WorkDate >= '".$FromDate."' ".
                      "   And WorkDate <= '".$ToDate."'   ".
                      " Group By SangFilm                 ".
                      " Order By SumOfScore desc          " ;
       }
       else
       {
            if   (strlen($ZoneLoc) == 3) // 지역
            {
                 // 영화리스트 (지역)
                 $sQuery = "Select SangFilm,                    ".
                           "       Sum(Score) As SumOfScore     ".
                           "  From wrk_sangdae                  ".
                           " Where WorkDate >= '".$FromDate."'  ".
                           "   And WorkDate <= '".$ToDate."'    ".
                           "   And Location = '".$ZoneLoc."'    ".
                           " Group By SangFilm                  ".
                           " Order By SumOfScore desc           " ;
            }
            if   (strlen($ZoneLoc) == 2)  // 구역
            {
                 // 해당 구역의 모든 지역들을 구한다.
                 $AddedLoc = " and " ;

                 $sQuery = "Select Location                  ".
                           "  From bas_filmsupplyzoneloc     ".
                           " Where Code = '".$FilmSupply."'  ".
                           "   And Zone = '".$ZoneLoc."'     " ;
                 $QryZoneloc = mysql_query($sQuery,$connect) ;
                 while ($ArrZoneloc = mysql_fetch_array($QryZoneloc))
                 {            
                      if  ($AddedLoc == " and ")
                          $AddedLoc .= "( Location = '".$ArrZoneloc["Location"]."' "  ;
                      else
                          $AddedLoc .= " or Location = '".$ArrZoneloc["Location"]."' "  ;
                 }
                 $AddedLoc .= ")" ;                     

                 // 영화리스트 (구역)
                 $sQuery = "Select SangFilm,                    ".
                           "       Sum(Score) As SumOfScore     ".
                           "  From wrk_sangdae                  ".
                           " Where WorkDate >= '".$FromDate."'  ".
                           "   And WorkDate <= '".$ToDate."'    ".
                           $AddedLoc                             .
                           " Group By SangFilm                  ".
                           " Order By SumOfScore desc           " ;
            }
       }

       $Rankiong = 0 ;
       
       
       $QrySangFilm = mysql_query($sQuery,$connect) ;
       while ($ArrSangFilm = mysql_fetch_array($QrySangFilm))
       {
            $Rankiong ++ ;

            if  ($Rankiong <= 10)
            {
                $SangFilmCode = $ArrSangFilm["SangFilm"] ; 

                // 영화명을 구함..
                $sQuery = "Select * From bas_sangfilmtitle   ".
                          " Where Code = '".$SangFilmCode."' " ;
                $QryIndyfilmtitle = mysql_query($sQuery,$connect) ;
                if  ($ArrIndyfilmtitle = mysql_fetch_array($QryIndyfilmtitle))
                {
                    $indyfilmtitleName = $ArrIndyfilmtitle["TagName"] ;
                }
                else
                {
                    $indyfilmtitleName = $SangFilmCode ;
                }            
                                               
                $xmlObj->startElement('Total'); 
                $xmlObj->writeElement('FilmName',mb_convert_encoding($indyfilmtitleName,"UTF-8","EUC-KR"));
                $xmlObj->endElement(); 
            }
       }

       
       //<Total><FilmName>영화1</FilmName></Total>
       //<Total><FilmName>영화2</FilmName></Total>
       //<Total><FilmName>영화3</FilmName></Total>

       $xmlObj->endElement(); 
     
       print $xmlObj->outputMemory(true);    
   }


   //
   // 상대영화현황(기간별) 2 ......................................................................................................
   //
   
   if  ($Action=="getSangdaeTerm2") 
   {       
       $xmlObj = new xmlWriter();
       $xmlObj->openMemory();

       //$xmlObj->startDocument('1.0','euc-kr'); // 반드시 utf-8 로할것..
       $xmlObj->startDocument('1.0','UTF-8');
       $xmlObj->startElement ('Totals'); 

       if   (($ZoneLoc == "9999") || ($ZoneLoc == "0")) // 전체
       {
            // 영화리스트 (전체)
            $sQuery = "Select SangFilm,                   ".
                      "       Sum(Score) As SumOfScore    ".
                      "  From wrk_sangdae                 ".
                      " Where WorkDate >= '".$FromDate."' ".
                      "   And WorkDate <= '".$ToDate."'   ".
                      " Group By SangFilm                 ".
                      " Order By SumOfScore desc          " ;
            $QrySangFilm = mysql_query($sQuery,$connect) ;
       }
       else
       {
            if   (strlen($ZoneLoc) == 3) // 지역
            {
                 // 영화리스트 (지역)
                 $sQuery = "Select SangFilm,                    ".
                           "       Sum(Score) As SumOfScore     ".
                           "  From wrk_sangdae                  ".
                           " Where WorkDate >= '".$FromDate."'  ".
                           "   And WorkDate <= '".$ToDate."'    ".
                           "   And Location = '".$ZoneLoc."'    ".
                           " Group By SangFilm                  ".
                           " Order By SumOfScore desc           " ;
                 $QrySangFilm = mysql_query($sQuery,$connect) ;
            }
            if   (strlen($ZoneLoc) == 2)  // 구역
            {
                 // 해당 구역의 모든 지역들을 구한다.
                 $AddedLoc = " and " ;

                 $sQuery = "Select Location                  ".
                           "  From bas_filmsupplyzoneloc     ".
                           " Where Code = '".$FilmSupply."'  ".
                           "   And Zone = '".$ZoneLoc."'     " ;
                 $QryZoneloc = mysql_query($sQuery,$connect) ;
                 while ($ArrZoneloc = mysql_fetch_array($QryZoneloc))
                 {            
                      if  ($AddedLoc == " and ")
                          $AddedLoc .= "( Location = '".$ArrZoneloc["Location"]."' "  ;
                      else
                          $AddedLoc .= " or Location = '".$ArrZoneloc["Location"]."' "  ;
                 }
                 $AddedLoc .= ")" ;                     

                 // 영화리스트 (구역)
                 $sQuery = "Select SangFilm,                    ".
                           "       Sum(Score) As SumOfScore     ".
                           "  From wrk_sangdae                  ".
                           " Where WorkDate >= '".$FromDate."'  ".
                           "   And WorkDate <= '".$ToDate."'    ".
                           $AddedLoc                             .
                           " Group By SangFilm                  ".
                           " Order By SumOfScore desc           " ;
                 $QrySangFilm = mysql_query($sQuery,$connect) ;
            }
       }

       $Rankiong = 0 ;
       
       while ($ArrSangFilm = mysql_fetch_array($QrySangFilm))
       {
            
            if  ($Rankiong < 10)
            {
                
                $SangFilmCode = $ArrSangFilm["SangFilm"] ;                             
                
                $ArrSangDaeFilm[$Rankiong] = $SangFilmCode ;  // 영화코드을 저장한다.
                
                $Rankiong ++ ;
                
            } 
       }

       $timestamp2 = mktime(0,0,0,substr($FromDate,4,2),substr($FromDate,6,2),substr($FromDate,0,4));
       $dur_time2  = (time() - $timestamp2) / 86400;      

       $timestamp1 = mktime(0,0,0,substr($ToDate,4,2),substr($ToDate,6,2),substr($ToDate,0,4));
       $dur_time1  = (time() - $timestamp1) / 86400;       

       $dur_day    = $dur_time2 - $dur_time1;  // 일수

       $temp = mktime(0,0,0,substr($FromDate,4,2),substr($FromDate,6,2),substr($FromDate,0,4));
       
       for ($i=0 ; $i<=$dur_day ; $i++)
       {
           $objDate = date("Ymd",$temp + ($i * 86400)) ;   
           $prnDate = date("md",$temp + ($i * 86400)) ;   


           $xmlObj->startElement('Total'); 
           $xmlObj->writeElement('Day',$prnDate);
           
           
           for ($j=0 ; $j<$Rankiong ; $j++)
           {
                $SangFilmCode = $ArrSangDaeFilm[$j] ;

                // 영화명을 구함..
                $sQuery = "Select * From bas_sangfilmtitle   ".
                          " Where Code = '".$SangFilmCode."' " ;
                $QryIndyfilmtitle = mysql_query($sQuery,$connect) ;
                if  ($ArrIndyfilmtitle = mysql_fetch_array($QryIndyfilmtitle))
                {
                    $indyfilmtitleName = $ArrIndyfilmtitle["TagName"] ;
                }
                else
                {
                    $indyfilmtitleName = "알수없음" ;
                }

                if   (($ZoneLoc == "9999") || ($ZoneLoc == "0")) // 전체
                {
                     // 영화리스트 (전체)
                     $sQuery = "Select Sum(Score) As SumOfScore       ".
                               "  From wrk_sangdae                    ".
                               " Where WorkDate = '".$objDate."'      ".
                               "   And SangFilm = '".$SangFilmCode."' " ;
                }
                else
                {
                     if   (strlen($ZoneLoc) == 3) // 지역
                     {
                          // 영화리스트 (지역)
                          $sQuery = "Select Sum(Score) As SumOfScore       ".
                                    "  From wrk_sangdae                    ".
                                    " Where WorkDate = '".$objDate."'      ".                               
                                    "   And Location = '".$ZoneLoc."'      ".
                                    "   And SangFilm = '".$SangFilmCode."' " ;
                     }

                     if   (strlen($ZoneLoc) == 2)  // 구역
                     {
                          // 해당 구역의 모든 지역들을 구한다.
                          $AddedLoc = " and " ;

                          $sQuery = "Select Location                  ".
                                    "  From bas_filmsupplyzoneloc     ".
                                    " Where Code = '".$FilmSupply."'  ".
                                    "   And Zone = '".$ZoneLoc."'     " ;
                          $QryZoneloc = mysql_query($sQuery,$connect) ;
                          while ($ArrZoneloc = mysql_fetch_array($QryZoneloc))
                          {            
                               if  ($AddedLoc == " and ")
                                   $AddedLoc .= "( Location = '".$ArrZoneloc["Location"]."' "  ;
                               else
                                   $AddedLoc .= " or Location = '".$ArrZoneloc["Location"]."' "  ;
                          }
                          $AddedLoc .= ")" ;                     

                          // 영화리스트 (구역)
                          $sQuery = "Select Sum(Score) As SumOfScore       ".
                                    "  From wrk_sangdae                    ".
                                    " Where WorkDate = '".$objDate."'      ".
                                    $AddedLoc."                            ".
                                    "   And SangFilm = '".$SangFilmCode."' " ;
                     }
                }

                $QrySangFilm = mysql_query($sQuery,$connect) ;
                if  ($ArrSangFilm = mysql_fetch_array($QrySangFilm))
                {
                    $sTemp = mb_convert_encoding($indyfilmtitleName,"UTF-8","EUC-KR") ;


                    $xmlObj->writeElement($sTemp,$ArrSangFilm["SumOfScore"]);          
                }
           }

           $xmlObj->endElement(); 
       }


       $xmlObj->endElement(); 
     
       print $xmlObj->outputMemory(true);    
   }
   
   
 
   mysql_close($connect) ;     
?>
