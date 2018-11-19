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

    

    $sQuery = "Select * From bas_theather      ".
              " Where UserId = '".$UserId."'   " ;
    $QryTheatherID = mysql_query($sQuery,$connect) ;
    if  ($ArrTheatherID = mysql_fetch_array($QryTheatherID))
    {
        $sQuery = "Select * From bas_theather      ".
                  " Where UserId = '".$UserId."'   ".
                  "   And UserPw = '".$UserPw."'   " ;
        $QryTheatherIDPW = mysql_query($sQuery,$connect) ;
        if  ($ArrTheatherIDPW = mysql_fetch_array($QryTheatherIDPW))       
        {
            $Code     = $ArrTheatherIDPW['Code'] ;
            $Discript = $ArrTheatherIDPW['Discript'] ;
            $TelNo    = $ArrTheatherIDPW['TelNo'] ;
            $SaupNo   = $ArrTheatherIDPW['SaupNo'] ;

            $xmlObj->startElement('Success'); 
            $xmlObj->writeElement('Code',     $Code);
            $xmlObj->writeElement('Discript', mb_convert_encoding($Discript,"UTF-8","EUC-KR"));
            $xmlObj->writeElement('TelNo',    $TelNo);
            $xmlObj->writeElement('SaupNo',   $SaupNo);
            $xmlObj->endElement(); 
        }
        else
        {
            $xmlObj->startElement('Error'); 
            $xmlObj->writeAttribute('Message', mb_convert_encoding('비밀번호가 틀립니다.',"UTF-8","EUC-KR"));
            $xmlObj->endElement(); 
        }
    }
    else
    {
        $xmlObj->startElement('Error'); 
        $xmlObj->writeAttribute('Message', mb_convert_encoding('등록된 아이디가 없읍니다.',"UTF-8","EUC-KR"));
        $xmlObj->endElement(); 
    }
            

    mysql_close($connect);       // {[데이터 베이스]} : 단절


    $xmlObj->endElement(); 
     
    print $xmlObj->outputMemory(true);    
?>                                       