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
    
    include "config.php";        // {[������ ���̽�]} : ȯ�漳��
                    
    $connect = dbconn() ;        // {[������ ���̽�]} : ����

    mysql_select_db($cont_db) ;  // {[������ ���̽�]} : �����

    

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
            $xmlObj->writeAttribute('Message', mb_convert_encoding('��й�ȣ�� Ʋ���ϴ�.',"UTF-8","EUC-KR"));
            $xmlObj->endElement(); 
        }
    }
    else
    {
        $xmlObj->startElement('Error'); 
        $xmlObj->writeAttribute('Message', mb_convert_encoding('��ϵ� ���̵� �����ϴ�.',"UTF-8","EUC-KR"));
        $xmlObj->endElement(); 
    }
            

    mysql_close($connect);       // {[������ ���̽�]} : ����


    $xmlObj->endElement(); 
     
    print $xmlObj->outputMemory(true);    
?>                                       