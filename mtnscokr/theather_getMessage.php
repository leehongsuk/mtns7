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

    mysql_select_db($cont_db,$connect) ;  // {[������ ���̽�]} : �����


    ////////////////////////////////
    $bEq       = 0 ;
    $bTmpQuery = 1 ;
    trace_init($connect) ;  
    /////////////////////////////////////

    // ���� ���� ���۵� �޽����� ���Ѵ�.
    $sQuery = "Select * From chk_theather_sndmsg    ".
              " Where UserID   = '".$UserID."'      ".
              "   And MsgYear  = '".$sCurrYear."'   ".
              " Order By MsgNo Desc                 ".
              " Limit 0 , 20                        " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
    $QryMessage = mysql_query($sQuery,$connect) ;
    while  ($ArrMessage = mysql_fetch_array($QryMessage))
    {
        $MsgYear    = $ArrMessage["MsgYear"] ;
        $MsgNo      = $ArrMessage["MsgNo"] ;
        $Readed     = $ArrMessage["Readed"] ;
        $MsgDate    = $ArrMessage["MsgDate"] ;
        $MsgTime    = $ArrMessage["MsgTime"] ;
        $MsgTitle   = $ArrMessage["MsgTitle"] ;
        $MsgContent = $ArrMessage["MsgContent"] ;

        $xmlObj->startElement('Record');  
        $xmlObj->writeElement('MsgYear',    $MsgYear) ;
        $xmlObj->writeElement('MsgNo',      $MsgNo) ;
        $xmlObj->writeElement('Readed',     $Readed) ;
        $xmlObj->writeElement('MsgDate',    $MsgDate) ;
        $xmlObj->writeElement('MsgTime',    $MsgTime) ;
        $xmlObj->writeElement('MsgTitle',   mb_convert_encoding($MsgTitle,"UTF-8","EUC-KR")) ;
        $xmlObj->writeElement('MsgContent', mb_convert_encoding($MsgContent,"UTF-8","EUC-KR")) ;
        $xmlObj->endElement(); 
    }

    mysql_close($connect);       // {[������ ���̽�]} : ����


    $xmlObj->endElement();

    print $xmlObj->outputMemory(true);
?>
