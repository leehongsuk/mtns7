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


    if  ($Theather)
    {
        $MaxMsgNo = 0 ;
        $sQuery = "Select Max(MsgNo) As MaxMsgNo     ".
                  "  From chk_theather_rcvmsg        ".
                  " Where Theather= '".$Theather."'  ".
                  "   And MsgYear = '".$sCurrYear."' " ;
        $QryMaxMsgNo = mysql_query($sQuery,$connect) ;
        if  ($ArrMaxMsgNo = mysql_fetch_array($QryMaxMsgNo))
        {
            $MaxMsgNo = $ArrMaxMsgNo["MaxMsgNo"] ;
        }

        $MaxMsgNo ++ ; // 1 ���� ..

        $sQuery = "Insert Into chk_theather_rcvmsg ".
                  "Values                          ".
                  "(                               ".
                  "      '".$Theather."',          ".
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
                  "      '' ,           ".
                  "      '' ,           ".
                  "      ''             ".
                  ")                               " ;
    //eq($sQuery) ;
        mysql_query($sQuery,$connect) ;
    }

    if  ($Theathers)
    {
        $TheatherList = split(",", $Theathers); // ������������ �и�.

        for ($i=0;$i<count($TheatherList);$i++) 
        {
            $Theather  = $TheatherList[$i] ;

            $MaxMsgNo = 0 ;

            $sQuery = "Select Max(MsgNo) As MaxMsgNo     ".
                      "  From chk_theather_rcvmsg        ".
                      " Where Theather= '".$Theather."'  ".
                      "   And MsgYear = '".$sCurrYear."' " ;
            $QryMaxMsgNo = mysql_query($sQuery,$connect) ;
            if  ($ArrMaxMsgNo = mysql_fetch_array($QryMaxMsgNo))
            {
                $MaxMsgNo = $ArrMaxMsgNo["MaxMsgNo"] ;
            }

            $MaxMsgNo ++ ; // 1 ���� ..

            $sQuery = "Insert Into chk_theather_rcvmsg ".
                      "Values                          ".
                      "(                               ".
                      "      '".$Theather."',          ".
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
                      "      '' ,           ".
                      "      '' ,           ".
                      "      ''             ".
                      ")                               " ;
//eq($sQuery) ;
            mysql_query($sQuery,$connect) ;
        }
    }

    mysql_close($connect);       // {[������ ���̽�]} : ����


    $xmlObj->endElement(); 
     
    print $xmlObj->outputMemory(true);    
?>                                       