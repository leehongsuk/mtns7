<?
    $xw = new xmlWriter();
    $xw->openMemory();

    $xmlObj = new xmlWriter();

    $xmlObj->openMemory();

    $CurDay    = date("d",time()) ;     
    $YearMonth = date("Ym",time()) ;          // ��� ...
    $AgoMonth  = date("Ym",strtotime("-1 month",strtotime(substr($YearMonth,0,4)."-".substr($YearMonth,4,2)."-01"))) ;

    if  ($CurDay == "01") 
    {
        $ObjectMonth = $AgoMonth ; // ���� ������ ����..
    }                
    else
    {
        $ObjectMonth = $YearMonth ; // �׿ܴ� �ݿ�..
    }

     
    $xmlObj->startDocument('1.0','euc-kr');
    $xmlObj->startElement ('Root'); 
    
    include "config.php";        // {[������ ���̽�]} : ȯ�漳��
                    
    $connect = dbconn() ;        // {[������ ���̽�]} : ����

    mysql_select_db($cont_db,$connect) ;  // {[������ ���̽�]} : �����


    ////////////////////////////////
    $bEq       = 0 ;
    $bTmpQuery = 0 ;
    trace_init($connect) ;  
    /////////////////////////////////////

    $sQuery = "Update chk_theather_rcvmsg         ". 
              "   Set Readed = 'Y'                ".
              " Where Theather = '".$Theather."'  ".
              "   And MsgYear  = '".$MsgYear ."'  ".
              "   And MsgNo    = '".$MsgNo   ."'  " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
    mysql_query($sQuery,$connect) ;

    

    mysql_close($connect);       // {[������ ���̽�]} : ����


    $xmlObj->endElement(); 
     
    print $xmlObj->outputMemory(true);    
?>                                       