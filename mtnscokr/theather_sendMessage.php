<?

    $Now        = strtotime('now');
    $sCurrYear  = date("Y",$Now) ;    // �����..

    $sDate = date("Ymd",$Now) ; // 
    $sTime = date("His",$Now) ; // 

    $xw = new xmlWriter();
    $xw->openMemory();

    $xmlObj = new xmlWriter();

    $xmlObj->openMemory();

     
    $xmlObj->startDocument('1.0','euc-kr');
    $xmlObj->startElement ('Root'); 
    
    include "config.php";        // {[������ ���̽�]} : ȯ�漳��
                    
    $connect = dbconn() ;        // {[������ ���̽�]} : ����

    mysql_select_db($cont_db) ;  // {[������ ���̽�]} : �����


    $MaxMsgNo = 0 ;
    $sQuery = "Select Max(MsgNo) As MaxMsgNo     ".
              "  From chk_theather_sndmsg        ".
              " Where UserID  = '".$UserID."'    ".
              "   And MsgYear = '".$sCurrYear."' " ;
    $QryMaxMsgNo = mysql_query($sQuery,$connect) ;
    if  ($ArrMaxMsgNo = mysql_fetch_array($QryMaxMsgNo))
    {
        $MaxMsgNo = $ArrMaxMsgNo["MaxMsgNo"] ;
    }

    $MaxMsgNo ++ ; // 1 ���� ..

    $sQuery = "Insert Into chk_theather_sndmsg ".
              "Values                          ".
              "(                               ".
              "      '".$UserID."',            ".
              "      '".$sCurrYear."',         ".
              "      ".$MaxMsgNo.",            ".
              "      'N',                      ".
              "      '".$sDate."',             ".
              "      '".$sTime."',             ".
              "      '".$Title."',             ".
              "      '".$Content."',           ".
              "      '".$Attach1."',           ".
              "      '".$Attach2."',           ".
              "      '".$Attach3."',           ".
              "      '' ,                      ".
              "      '' ,                      ".
              "      ''                        ".
              ")                               " ;
tmp_query($sQuery,$connect) ;
    mysql_query($sQuery,$connect) ;

    mysql_close($connect);       // {[������ ���̽�]} : ����


    $xmlObj->endElement(); 
     
    print $xmlObj->outputMemory(true);    
?>                                       