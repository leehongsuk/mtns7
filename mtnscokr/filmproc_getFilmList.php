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


    $sQuery = "Select Open,           ".
              "       Code,           ".
              "       Name            ".
              "  From bas_filmtitle   ".
              " Where Finish <> 'Y'   " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
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