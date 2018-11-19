<?
    $xmlObj = new xmlWriter();
    $xmlObj->openMemory();
    
     
    $xmlObj->startDocument('1.0','euc-kr');
    $xmlObj->startElement ('Root'); 
    
    include "config.php";        // {[데이터 베이스]} : 환경설정
                    
    $connect = dbconn() ;        // {[데이터 베이스]} : 연결

    mysql_select_db($cont_db,$connect) ;  // {[데이터 베이스]} : 디비선택

    ////////////////////////////////
    $bEq       = 0 ;
    $bTmpQuery = 0 ;
    trace_init($connect) ;  
    /////////////////////////////////////

    
    $sQuery = "Select Max( RoomOrder.Seq ) AS MaxSeq,    ".
              "       RoomOrder.Theather,                ".
              "       Location.Name As Location,         ".
              "       Theather.Discript,                 ".
              "       Theather.TelNo,                    ".
              "       Theather.SaupNo                    ".
              "  From bas_showroomorder AS RoomOrder,    ".
              "       bas_theather AS Theather,          ".
              "       bas_location AS Location           ".
              " Where Theather.Code = RoomOrder.THeather ".
              "   And Location.Code = Theather.location  ".
              " Group By RoomOrder.Theather              ".
              " Order By MaxSeq                          " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
    $QryTheather = mysql_query($sQuery,$connect) ;
    while  ($ArrTheather = mysql_fetch_array($QryTheather))
    {
        $Code     = $ArrTheather["Theather"] ;
        $Location = $ArrTheather["Location"] ;
        $Discript = $ArrTheather["Discript"] ;
        $TelNo    = $ArrTheather["TelNo"] ;
        $SaupNo   = $ArrTheather["SaupNo"] ;

        $xmlObj->startElement('RecordTheather') ;   // 극장리스트 
        $xmlObj->writeElement('Code',     $Code) ;
        $xmlObj->writeElement('Location', mb_convert_encoding($Location,"UTF-8","EUC-KR")) ;
        $xmlObj->writeElement('Discript', mb_convert_encoding($Discript,"UTF-8","EUC-KR")) ;
        $xmlObj->writeElement('TelNo',    $TelNo) ;
        $xmlObj->writeElement('SaupNo',   $SaupNo) ;
        $xmlObj->endElement() ;  
    }

    $sQuery = "Select Open,           ".
              "       Code,           ".
              "       Name            ".
              "  From bas_filmtitle   ".
              " Where Code   <> '99'  ".
              "   And Finish <> 'Y'   " ;
    $QryTheather = mysql_query($sQuery,$connect) ;
    while  ($ArrTheather = mysql_fetch_array($QryTheather))
    {
        $Open  = $ArrTheather["Open"] ;
        $Film  = $ArrTheather["Code"] ;
        $Name  = $ArrTheather["Name"] ;

        $xmlObj->startElement('RecordFilm') ;   // 영화리스트
        $xmlObj->writeElement('Open',     $Open) ;
        $xmlObj->writeElement('Film',     $Film) ;
        $xmlObj->writeElement('Name', mb_convert_encoding($Name,"UTF-8","EUC-KR")) ;
        $xmlObj->endElement() ;  
    }

    mysql_close($connect) ;       // {[데이터 베이스]} : 단절


    $xmlObj->endElement(); 
     
    print $xmlObj->outputMemory(true);    
?>                                       