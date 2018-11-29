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

    mysql_select_db($cont_db,$connect) ;  // {[데이터 베이스]} : 디비선택


    ////////////////////////////////
    $bEq       = 0 ;
    $bTmpQuery = 1 ;
    trace_init($connect) ;  
    /////////////////////////////////////

    // 읽지 않은 전송된 메시지를 구한다.
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

    mysql_close($connect);       // {[데이터 베이스]} : 단절


    $xmlObj->endElement();

    print $xmlObj->outputMemory(true);
?>
