<?
    /////////////////////////////////////////////////////////////////////////////////

    $ToDate = date("Ymd",time()) ; // ���� ...

    $Today = time()-(3600*7) ; // ���� 7�� ���� ���÷� �����Ѵ�...

    if (!$WorkDate)
    {
       $WorkDate = date("Ymd",$Today) ;
    }

    // �Ϸ� ������ ���Ѵ�.
    $AgoDate  = date("Ymd",strtotime("-1 day",strtotime(substr($WorkDate,0,4)."-".substr($WorkDate,4,2)."-".substr($WorkDate,6,2).""))) ;

    // �Ϸ� �������� ���Ѵ�.
    $TmroDate = date("Ymd",strtotime("+1 day",strtotime(substr($WorkDate,0,4)."-".substr($WorkDate,4,2)."-".substr($WorkDate,6,2).""))) ;

    // ������ ������ ���Ѵ�.
    $AgoWeek  = date("Ymd",strtotime("-7 day",strtotime(substr($WorkDate,0,4)."-".substr($WorkDate,4,2)."-".substr($WorkDate,6,2).""))) ;

    /////////////////////////////////////////////////////////////////////////////////

    $cont_db = "mtns" ;

    function dbconn()
    {
        global $connect ;

        if(!$connect) $connect = mysql_connect( "localhost", "mtns", "5421")  or  Error("DB ���ӽ� ������ �߻��߽��ϴ�");
        //if(!$connect) $connect = mysql_connect( "localhost", "root", "root0922")  or  Error("DB ���ӽ� ������ �߻��߽��ϴ�");

        return $connect;
    }

    /////////////////////////////////////////////////////////////////////////////////

    function tmp_query($_Query,$_connect)
    {
        $sQuery = "INSERT INTO `tmp_query`          ".
                  "       ( `SeqNo` , `Content` )   ".
                  "Values ( NULL, \"".$_Query."\" ) " ;
        mysql_query($sQuery,$_connect) ;
    }

    function eq($_Query)  // ������ ����Ѵ�.
    {
        echo $_Query."<br>" ;
    }



    /////////////////////////////////////////////////////////////////////////////////

    function get_filmproduce_code($_UserId,$_connect)
    {
        $sCode = "" ;

        $sQuery = "Select Code From bas_filmproduce ".
                  " Where UserId = '".$_UserId."'   " ; //echo $sQuery ;
        $QryFilmproduce = mysql_query($sQuery,$_connect) ;
        if  ($ArrFilmproduce = mysql_fetch_array($QryFilmproduce))
        {
            $sCode = $ArrFilmproduce["Code"] ;
        }

        return $sCode ;
    }

?>
