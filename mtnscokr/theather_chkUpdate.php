<?

    $Now        = strtotime('now');
    $sCurrYear  = date("Y",$Now) ;    // �����..

    $xw = new xmlWriter();
    $xw->openMemory();

    $xmlObj = new xmlWriter();

    $xmlObj->openMemory();

     
    $xmlObj->startDocument('1.0','euc-kr');
    $xmlObj->startElement ('Root'); 
    
    include "config.php";        // {[������ ���̽�]} : ȯ�漳��
                    
    $connect = dbconn() ;        // {[������ ���̽�]} : ����

    mysql_select_db($cont_db) ;  // {[������ ���̽�]} : �����


    $sQuery = "Select * From chk_extension_update " ;
    $QryUpdate = mysql_query($sQuery,$connect) ;
    if  ($ArrUpdate = mysql_fetch_array($QryUpdate))
    {
        $Update = $ArrUpdate["ObjectDate"].$ArrUpdate["ObjectTime"] ;
    }


    if  ($FileDate < $Update)  // ������Ʈ ���θ� ����...
    {
        $xmlObj->startElement('DoIt'); 
        $xmlObj->endElement(); 
    }
    else
    {
        $xmlObj->startElement('Dont'); 
        $xmlObj->endElement(); 
    }


    mysql_close($connect);       // {[������ ���̽�]} : ����


    $xmlObj->endElement(); 
     
    print $xmlObj->outputMemory(true);    
?>                                       