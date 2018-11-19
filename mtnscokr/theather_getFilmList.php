<?
    $xmlObj = new xmlWriter();
    $xmlObj->openMemory();


    /*
    $xmlObj->writeAttribute( 'xmlns', 'http://www.wapforum.org/DTD/xhtml-mobile10.dtd');
    $xmlObj->writeAttribute( 'xm:lang', 'en');

    $xmlObj->startElement('head'); // <head>

    $xmlObj->writeElement ('title', 'Test WAP Document');

    $xmlObj->endElement(); // </head>
    $xmlObj->startElement('body'); // <body>

    $xmlObj->startElement('ol'); // <ol>

    $xmlObj->writeElement ('li', 'One Item &amp;  <sss <ss />></ss>');
    $xmlObj->writeElement ('li', 'Another Item');
    $xmlObj->writeElement ('li', 'Another Item');

    $xmlObj->endElement(); // </ol>

    $xmlObj->endElement(); // </body>
    */

    $xmlObj->startDocument('1.0','euc-kr');
    $xmlObj->startElement ('Root');

    include "config.php";        // {[데이터 베이스]} : 환경설정

    $connect = dbconn() ;        // {[데이터 베이스]} : 연결

    mysql_select_db($cont_db) ;  // {[데이터 베이스]} : 디비선택


    $sQuery = "Select * From bas_filmtitle  ".
              " Order By Open Desc          " ;
$xmlObj->writeElement('sQuery', $sQuery);
    $QryFilmtitle = mysql_query($sQuery,$connect) ;
    while ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
    {
        $FilmtitleOpen    = $ArrFilmtitle['Open'] ;
        $FilmtitleCode    = $ArrFilmtitle['Code'] ;
        $FilmtitleName    = $ArrFilmtitle['Name'] ;

        $xmlObj->startElement('Record');
        $xmlObj->writeElement('Open', $FilmtitleOpen);
        $xmlObj->writeElement('Code', $FilmtitleCode);
        $xmlObj->writeElement('Name', mb_convert_encoding($FilmtitleName,"UTF-8","EUC-KR"));
        $xmlObj->endElement();
    }

    mysql_close($connect);       // {[데이터 베이스]} : 단절


    $xmlObj->endElement();

    print $xmlObj->outputMemory(true);
?>