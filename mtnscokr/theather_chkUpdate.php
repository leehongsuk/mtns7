<?

    $Now        = strtotime('now');
    $sCurrYear  = date("Y",$Now) ;    // 현재년..

    $xw = new xmlWriter();
    $xw->openMemory();

    $xmlObj = new xmlWriter();

    $xmlObj->openMemory();

     
    $xmlObj->startDocument('1.0','euc-kr');
    $xmlObj->startElement ('Root'); 
    
    include "config.php";        // {[데이터 베이스]} : 환경설정
                    
    $connect = dbconn() ;        // {[데이터 베이스]} : 연결

    mysql_select_db($cont_db) ;  // {[데이터 베이스]} : 디비선택


    $sQuery = "Select * From chk_extension_update " ;
    $QryUpdate = mysql_query($sQuery,$connect) ;
    if  ($ArrUpdate = mysql_fetch_array($QryUpdate))
    {
        $Update = $ArrUpdate["ObjectDate"].$ArrUpdate["ObjectTime"] ;
    }


    if  ($FileDate < $Update)  // 업데이트 여부를 결정...
    {
        $xmlObj->startElement('DoIt'); 
        $xmlObj->endElement(); 
    }
    else
    {
        $xmlObj->startElement('Dont'); 
        $xmlObj->endElement(); 
    }


    mysql_close($connect);       // {[데이터 베이스]} : 단절


    $xmlObj->endElement(); 
     
    print $xmlObj->outputMemory(true);    
?>                                       