<?
    /////////////////////////////////////////////////////////////////////////////////
    $MinPrice = 3000 ; // �ּ� �ݾ�.

    if (!$ToDate)
    {
        $ToDate = date("Ymd",time()) ; // ���� ...
    }
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

    $cont_db   = "mtns" ;

    function dbconn()
    {
        global $connect ;

        if(!$connect) $connect = mysql_connect( "localhost", "mtns", "5421")  or  Error("DB ���ӽ� ������ �߻��߽��ϴ�");

        return $connect;
    }

    //////////////////////////////////////////

    //
    function trace_init($_connect)
    {
        mysql_query("TRUNCATE TABLE `tmp_query2` ",$_connect) ;
    }

    //trace(__FILE__,__LINE__,$sQuery,$connect,0,0) ;

    function trace($_File,$_Line,$_Query,$_connect,$_bEq,$_bTmpQuery)
    {
        if  ($_bEq == true)
        {
            eq("<FONT COLOR='#C0C0C0'><I>".$_File."</I>[<B>".$_Line."</B>]<br></FONT>".$_Query) ;
        }
        if  ($_bTmpQuery == true)
        {
            mysql_query("INSERT INTO `tmp_query2`                                     ".
                        "       ( `SeqNo` , `LineNo`, `Content`, `FileName` )         ".
                        "Values ( NULL, '".$_Line."', \"".$_Query."\", '".$_File."' ) ",$_connect) ;
        }
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
        echo "<xmp>";
        print_r($_Query);
        echo "</xmp>";
        //echo $_Query."<br>" ;
    }

    function ee($_Query)  // ������ ����Ѵ�.
    {
        ?>
        <table border="1" cellpadding="0" cellspacing="0" width="100%">
        <tr>
         <td align=center width=70>����<BR>�޽���</td>
         <td><?=mysql_error()?></td>
        </tr>
        <tr>
         <td align=center width=70>������</td>
         <td><?=$_Query?></td>
        </tr>
        </table>
        <?
    }

    function get_singotable($_Open,$_Code,$_connect)
    {
        $sSingoName = "" ;

        $sQuery = "Select SingoName            \n".
                  "  From bas_filmtitle        \n".
                  " Where Open = '".$_Open."'  \n".
                  "   And Code = '".$_Code."'  " ;
//eq($sQuery);
        $QryFilmtitle = mysql_query($sQuery,$_connect) ;
        if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $sSingoName = $ArrFilmtitle["SingoName"] ;
        }

        return $sSingoName ;
    }

    function get_acctable($_Open,$_Code,$_connect)
    {
        $sAccName = "" ;

        $sQuery = "Select AccName              ".
                  "  From bas_filmtitle        ".
                  " Where Open = '".$_Open."'  ".
                  "   And Code = '".$_Code."'  " ;
        $QryFilmtitle = mysql_query($sQuery,$_connect) ;
        if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $sAccName = $ArrFilmtitle["AccName"] ;
        }

        return $sAccName ;
    }

    function get_degree($_Open,$_Code,$_connect)
    {
        $sDegree = "" ;

        $sQuery = "Select DgrName              ".
                  "  From bas_filmtitle        ".
                  " Where Open = '".$_Open."'  ".
                  "   And Code = '".$_Code."'  " ;
        $QryFilmtitle = mysql_query($sQuery,$_connect) ;
        if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $sDegree = $ArrFilmtitle["DgrName"] ;
        }

        return $sDegree ;
    }

    function get_degreepriv($_Open,$_Code,$_connect)
    {
        $sDegreePriv = "" ;

        $sQuery = "Select DgrpName             ".
                  "  From bas_filmtitle        ".
                  " Where Open = '".$_Open."'  ".
                  "   And Code = '".$_Code."'  " ;
        $QryFilmtitle = mysql_query($sQuery,$_connect) ;
        if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $sDegreePriv = $ArrFilmtitle["DgrpName"] ;
        }

        return $sDegreePriv ;
    }

    function get_titlename($_Open,$_Code,$_connect)
    {
        $sDegreePriv = "" ;

        $sQuery = "Select Name                 ".
                  "  From bas_filmtitle        ".
                  " Where Open = '".$_Open."'  ".
                  "   And Code = '".$_Code."'  " ;
        $QryFilmtitle = mysql_query($sQuery,$_connect) ;
        if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
        {
            $sName = $ArrFilmtitle["Name"] ;
        }

        return $sName ;
    }

    function get_showroomorder($_Open,$_Code,$_connect)
    {
        $sRoomOrder = "" ;

        $sQuery = "Select RoomOrder            ".
                  "  From bas_filmtitle        ".
                  " Where Open = '".$_Open."'  ".
                  "   And Code = '".$_Code."'  " ;
 //eq($sQuery);
        $QryRoomOrder = mysql_query($sQuery,$_connect) ;
        if  ($ArrRoomOrder = mysql_fetch_array($QryRoomOrder))
        {
            $sRoomOrder = $ArrRoomOrder["RoomOrder"] ;
        }

        return $sRoomOrder ;
    }

    function get_FilmType($_Open,$_Code,$_connect)
    {
        $sRoomOrder = "" ;

        $sQuery = "Select FtName               ".
                  "  From bas_filmtitle        ".
                  " Where Open = '".$_Open."'  ".
                  "   And Code = '".$_Code."'  " ;
        $QryRoomOrder = mysql_query($sQuery,$_connect) ;
        if  ($ArrRoomOrder = mysql_fetch_array($QryRoomOrder))
        {
            $FtName = $ArrRoomOrder["FtName"] ;
        }

        return $FtName ;
    }

    function get_FilmTypePrv($_Open,$_Code,$_connect)
    {
        $sRoomOrder = "" ;

        $sQuery = "Select FtpName              ".
                  "  From bas_filmtitle        ".
                  " Where Open = '".$_Open."'  ".
                  "   And Code = '".$_Code."'  " ;
        $QryRoomOrder = mysql_query($sQuery,$_connect) ;
        if  ($ArrRoomOrder = mysql_fetch_array($QryRoomOrder))
        {
            $FtpName = $ArrRoomOrder["FtpName"] ;
        }

        return $FtpName ;
    }



    function xml_convert($_String,$_Sparater)
    {
        $Str = mb_convert_encoding($_String,"UTF-8","EUC-KR");

        $Str = eregi_replace($_Sparater , " " , $Str);

        return $Str ;
    }

    //
    // Get ������� ���޵� ������ �������� ����Ѵ�.
    //
    function Display_GetVar()
    {
        ?>
        <table border=1>
        <tr>
             <td align=center colspan=2>GET</td>
        </tr>
        <?
        foreach ($_GET as $key=>$value)
        {
            ?>
            <tr>
             <td><?=$key?></td>
             <td><?=$value?></td>
            </tr>
            <?
        }
        ?>
        </table>
        <?
    }

    //
    // Post ������� ���޵� ������ �������� ����Ѵ�.
    //
    function Display_PostVar()
    {
        ?>
        <table border=1>
        <tr>
             <td align=center colspan=2>POST</td>
        </tr>
        <?
        foreach ($_POST as $key=>$value)
        {
            ?>
            <tr>
             <td><?=$key?></td>
             <td><?=$value?></td>
            </tr>
            <?
        }
        ?>
        </table>
        <?
    }

    function get_GikumAount($_UnitPrice, $_NumPersons )
    {
        return round($_UnitPrice / 1.03) * $_NumPersons ;
    }

    function get_GikumAount2($_UnitPrice, $_GikumRate, $_NumPersons )
    {
        return round($_UnitPrice / $_GikumRate) * $_NumPersons ;
    }


    function drop_table($_TableName,$_connect)
    {
        $sRoomOrder = "" ;

        $sQuery = "SHOW TABLES LIKE '".$_TableName."'  " ; // eq($sQuery);

        $QryRoomOrder = mysql_query($sQuery,$_connect) ;
        if  ($ArrRoomOrder = mysql_fetch_array($QryRoomOrder))
        {
            $sQuery = "DROP TABLE ".$_TableName."  " ;
            mysql_query($sQuery,$_connect) ;  //Eq($sQuery) ;
        }
    }

    // ������� ���̺� ����.. (�󿵰����� ���̺��� ��������� �̾Ƴ���.)
    function create_tbleorder($_TableOrder,$_Showroomorder,$_connect)
    {
        $sQuery = "Create Table ".$_TableOrder."
                   As
                   (
                      Select min(seq) seq, theather
                      From ".$_Showroomorder."
                      Group By theather
                   )
                  ";  //Eq($sQuery) ;
        mysql_query($sQuery,$_connect) ;

        $sQuery = "Alter Table ".$_TableOrder." ADD PRIMARY KEY ( seq )" ;
        mysql_query($sQuery,$_connect) ;
    }

    // ���� ���̺�
    function get_theather_rate($_Open,$_Film,$_connect)
    {
        $sTableName = "wrk_tr_".$_Open."_".$_Film ;

        $sQuery = "SHOW TABLES LIKE '".$sTableName."'  " ;   //eq($sQuery);

        $QryTableName = mysql_query($sQuery,$_connect) ;
        if  (!mysql_fetch_array($QryTableName))
        {
            $sQuery = "CREATE TABLE $sTableName
                       (
                        Workdate  varchar(8) NOT NULL default '',
                        Theather  varchar(4) NOT NULL default '',
                        Open      varchar(6) NOT NULL default '',
                        Film      varchar(2) NOT NULL default '',
                        Rate       decimal(3,1) default NULL,
                        PRIMARY KEY  (Workdate,Theather,Open,Film)
                      )" ;
            mysql_query($sQuery,$_connect) ;
        }

        return $sTableName ;
    }

	$TblTheatherRate = "" ; // ���庰/�ʸ��� ������ ������ ���̺�� - ��������..
	
	// ������ - ����Ʈ
    function get_theather_rate_value_default($_Location,$_Theather,$_Open,$_Film,$_connect)
    {
        $sQuery = "Select Rate                         ".
                  "  From bas_theather_rate            ".
                  " Where Theather = '".$_Theather."'  ".
                  "   And Open     = '".$_Open."'      ".
                  "   And Film     = '".$_Film."'      " ; //Eq($sQuery) ;	
        $QryTheatherRate = mysql_query($sQuery,$_connect);
        if  ($ArrTheatherRate = mysql_fetch_array($QryTheatherRate))
        {
            $TheatherRate = $ArrTheatherRate["Rate"] ;
        }
        else
        {
            if  ($_Location=="100") $TheatherRate = 60 ; // ������ ������ 60 %
            else                    $TheatherRate = 50 ; // �׿� ������ 50 %

            $sQuery = "Insert Into bas_theather_rate   ".
                      "Values                          ".
                      "(                               ".
                      "      '".$_Theather."',         ".
                      "      '".$_Open."',             ".
                      "      '".$_Film."',             ".
                      "      '".$TheatherRate."'       ".
                      ")                               " ;
            mysql_query($sQuery,$_connect);
        }
		
		return $TheatherRate ;
	}

    // ������ - ���ں�
	function get_theather_rate_value_date($TblTheatherRate,$_TblTheatherRate,$_WorkDate,$_Theather,$_Open,$_Film,$_connect)
    {
        $sQuery = "Select * From ".$TblTheatherRate."  ".
                  " Where WorkDate = '".$_WorkDate."'  ".
                  "   And Theather = '".$_Theather."'  ".
                  "   And Open     = '".$_Open."'      ".
                  "   And Film     = '".$_Film."'      " ; //Eq($sQuery) ;	
        $QryTheatherRate = mysql_query($sQuery,$_connect);
        if  ($ArrTheatherRate = mysql_fetch_array($QryTheatherRate))
        {
            $TheatherRate = $ArrTheatherRate["Rate"] ;
        }
        else
        {
			$TheatherRate  = $_TblTheatherRate ;
			
            $sQuery = "Insert Into ".$TblTheatherRate."  ".
                      "Values                            ".
                      "(                                 ".
                      "      '".$_WorkDate."',           ".
                      "      '".$_Theather."',           ".
                      "      '".$_Open."',               ".
                      "      '".$_Film."',               ".
                      "      '".$TheatherRate."'         ".
                      ")                                 " ;
            mysql_query($sQuery,$_connect);
        }

        return $TheatherRate ;
    }
	
    // ������
	/*
    function get_theather_rate_value($_WorkDate,$_Location,$_Theather,$_Open,$_Film,$_connect)
    {
        $TblTheatherRate  = get_theather_rate($_Open,$_Film,$_connect) ;

        $sQuery = "Select Rate                         ".
                  "  From bas_theather_rate            ".
                  " Where Theather = '".$_Theather."'  ".
                  "   And Open     = '".$_Open."'      ".
                  "   And Film     = '".$_Film."'      " ; //Eq($sQuery) ;	
        $QryTheatherRate = mysql_query($sQuery,$_connect);
        if  ($ArrTheatherRate = mysql_fetch_array($QryTheatherRate))
        {
            $TheatherRate = $ArrTheatherRate["Rate"] ;
        }
        else
        {
            if  ($_Location=="100")  $TheatherRate = 60 ; // ������ ������ 60 %
            else                     $TheatherRate = 50 ; // �׿� ������ 50 %

            $sQuery = "Insert Into bas_theather_rate   ".
                      "Values                          ".
                      "(                               ".
                      "      '".$_Theather."',         ".
                      "      '".$_Open."',             ".
                      "      '".$_Film."',             ".
                      "      '".$TheatherRate."'       ".
                      ")                               " ;
            mysql_query($sQuery,$_connect);
        }

        $sQuery = "Select * From ".$TblTheatherRate."  ".
                  " Where WorkDate = '".$_WorkDate."'  ".
                  "   And Theather = '".$_Theather."'  ".
                  "   And Open     = '".$_Open."'      ".
                  "   And Film     = '".$_Film."'      " ; //Eq($sQuery) ;	
        $QryTheatherRate = mysql_query($sQuery,$_connect);
        if  ($ArrTheatherRate = mysql_fetch_array($QryTheatherRate))
        {
            $TheatherRate = $ArrTheatherRate["Rate"] ;
        }
        else
        {
            $sQuery = "Insert Into ".$TblTheatherRate."  ".
                      "Values                            ".
                      "(                                 ".
                      "      '".$_WorkDate."',           ".
                      "      '".$_Theather."',           ".
                      "      '".$_Open."',               ".
                      "      '".$_Film."',               ".
                      "      '".$TheatherRate."'         ".
                      ")                                 " ;
            mysql_query($sQuery,$_connect);
        }

        return $TheatherRate ;
    }
	*/

    // ���� ���Ϻ� ���� - ���ھ� ������ ������ �����ϵ��� �Ұ�
    function update_AccTable($_sSingoName,
                             $_sAccName,
                             $_WorkDate,
                             $_Silmooja,
                             $_Theather,
                             $_Open,
                             $_Film,
                             $_FilmType,
                             $_UnitPrice,
                             $_connect)
    {
        // ���ϴ��� - �ʸ� ��ü
        $sQuery = "Select Sum(NumPersons) As SumNumPersons,  \n".
                  "       Sum(TotAmount)  As SumTotAmount    \n".
                  "  From ".$_sSingoName."                   \n".
                  " Where SingoDate <= '".$_WorkDate."'      \n".
                  "   And Silmooja  = '".$_Silmooja."'       \n".
                  "   And Theather  = '".$_Theather."'       \n".
                  "   And Open      = '".$_Open."'           \n".
                  "   And Film      = '".$_Film."'           \n".
                  "   And UnitPrice = '".$_UnitPrice."'      \n" ; //eq($sQuery);
        $QrySumSingo = mysql_query($sQuery,$_connect) or die(ee($sQuery)) ;
        if  ($ArySumSingo = mysql_fetch_array($QrySumSingo))
        {
            $sQuery = "Select Accu, TotAccu, AcMoney, TotAcMoney  \n".
                      "  From ".$_sAccName."                      \n".
                      " Where WorkDate   = '".$_WorkDate."'       \n".
                      "   And Silmooja   = '".$_Silmooja."'       \n".
                      "   And Theather   = '".$_Theather."'       \n".
                      "   And Open       = '".$_Open."'           \n".
                      "   And Film       = '".$_Film."'           \n".
                      "   And FilmType   = '0'                    \n".
                      "   And UnitPrice  = '".$_UnitPrice."'      \n" ; //eq($sQuery);
            $QryAccumulate = mysql_query($sQuery,$_connect) or die(ee($sQuery)) ;
            if  ($AryAccumulate = mysql_fetch_array($QryAccumulate))  // ���� ���������� ���� ���
            {
                 // Update
                 $sQuery = "Update ".$_sAccName."                                    \n".
                           "   Set Accu       = '".$ArySumSingo["SumNumPersons"]."', \n".
                           "       AcMoney    = '".$ArySumSingo["SumTotAmount"]."'   \n".
                           " Where WorkDate   = '".$_WorkDate."'                     \n".
                           "   And Silmooja   = '".$_Silmooja."'                     \n".
                           "   And Theather   = '".$_Theather."'                     \n".
                           "   And Open       = '".$_Open."'                         \n".
                           "   And Film       = '".$_Film."'                         \n".
                           "   And FilmType   = '0'                                  \n".
                           "   And UnitPrice  = '".$_UnitPrice."'                    \n" ; //eq($sQuery);
                 mysql_query($sQuery,$_connect) or die(ee($sQuery)) ;
            }
            else
            {
                // Insert
                $sQuery = "Insert Into ".$_sAccName."                   \n".
                          "Values                                       \n".
                          "(                                            \n".
                          "    '".$_WorkDate."',                        \n".
                          "    '".$_Silmooja."',                        \n".
                          "    '".$_Theather."',                        \n".
                          "    '".$_Open."',                            \n".
                          "    '".$_Film."',                            \n".
                          "    '0',                                     \n".
                          "    '".$_UnitPrice."',                       \n".
                          "    '".$ArySumSingo["SumNumPersons"]."',     \n".
                          "    '0',                                     \n".
                          "    '".$ArySumSingo["SumTotAmount"]."',      \n".
                          "    '0',                                     \n".
                          "    '".$showroomLocation."',                 \n".
                          "    '0',                                     \n".
                          "    '0'                                      \n".
                          ")                                            \n" ;  //eq($sQuery);
                mysql_query($sQuery,$_connect) or die(ee($sQuery)) ;
            }
        }

        // ���ϴ��� - �ʸ� Ÿ�Ժ�
        $sQuery = "Select Sum(NumPersons) As SumNumPersons,  \n".
                  "       Sum(TotAmount)  As SumTotAmount    \n".
                  "  From ".$_sSingoName."                   \n".
                  " Where SingoDate <= '".$_WorkDate."'      \n".
                  "   And Silmooja  = '".$_Silmooja."'       \n".
                  "   And Theather  = '".$_Theather."'       \n".
                  "   And Open      = '".$_Open."'           \n".
                  "   And Film      = '".$_Film."'           \n".
                  "   And FilmType  = '".$_FilmType."'       \n".
                  "   And UnitPrice = '".$_UnitPrice."'      \n" ;  //eq($sQuery);
        $QrySumSingo = mysql_query($sQuery,$_connect) or die(ee($sQuery)) ;
        if  ($ArySumSingo = mysql_fetch_array($QrySumSingo))
        {
            $arrRet = array($ArySumSingo["SumNumPersons"], $ArySumSingo["SumTotAmount"]);    // ���հ� , �ѱݾ� .

            $sQuery = "Select Accu, TotAccu, AcMoney, TotAcMoney  \n".
                      "  From ".$_sAccName."                      \n".
                      " Where WorkDate   = '".$_WorkDate."'       \n".
                      "   And Silmooja   = '".$_Silmooja."'       \n".
                      "   And Theather   = '".$_Theather."'       \n ".
                      "   And Open       = '".$_Open."'           \n".
                      "   And Film       = '".$_Film."'           \n".
                      "   And FilmType   = '".$_FilmType."'       \n".
                      "   And UnitPrice  = '".$_UnitPrice."'      \n" ; //eq($sQuery);
            $QryAccumulate = mysql_query($sQuery,$_connect) or die(ee($sQuery)) ;
            if  ($AryAccumulate = mysql_fetch_array($QryAccumulate))  // ���� ���������� ���� ���
            {
                 // Update
                 $sQuery = "Update ".$_sAccName."                                    \n".
                           "   Set Accu       = '".$ArySumSingo["SumNumPersons"]."', \n".
                           "       AcMoney    = '".$ArySumSingo["SumTotAmount"]."'   \n".
                           " Where WorkDate   = '".$_WorkDate."'                     \n".
                           "   And Silmooja   = '".$_Silmooja."'                     \n".
                           "   And Theather   = '".$_Theather."'                     \n".
                           "   And Open       = '".$_Open."'                         \n".
                           "   And Film       = '".$_Film."'                         \n".
                           "   And FilmType   = '".$_FilmType."'                     \n".
                           "   And UnitPrice  = '".$_UnitPrice."'                    \n" ; //eq($sQuery);
                 mysql_query($sQuery,$_connect) or die(ee($sQuery)) ;
            }
            else
            {
                // Insert
                $sQuery = "Insert Into ".$_sAccName."                   \n".
                          "Values                                       \n".
                          "(                                            \n".
                          "    '".$_WorkDate."',                        \n".
                          "    '".$_Silmooja."',                        \n".
                          "    '".$_Theather."',                        \n".
                          "    '".$_Open."',                            \n".
                          "    '".$_Film."',                            \n".
                          "    '".$_FilmType."',                        \n".
                          "    '".$_UnitPrice."',                       \n".
                          "    '".$ArySumSingo["SumNumPersons"]."',     \n".
                          "    '0',                                     \n".
                          "    '".$ArySumSingo["SumTotAmount"]."',      \n".
                          "    '0',                                     \n".
                          "    '".$showroomLocation."',                 \n".
                          "    '0',                                     \n".
                          "    '0'                                      \n".
                          ")                                            \n" ; //eq($sQuery);
                mysql_query($sQuery,$_connect) or die(ee($sQuery)) ;
            }
        }
        else
        {
            $arrRet = array(0, 0);
        }


        return  $arrRet ;
    }

    // ���� ���Ϻ� ���� - ���ھ� ������ ������ �����ϵ��� �Ұ�
    function delete_AccTable($_sAccName,
                             $_WorkDate,
                             $_Silmooja,
                             $_Theather,
                             $_Open,
                             $_Film,
                             $_connect)
    {
        // Delete
        $sQuery = "Delete From ".$_sAccName."             \n".
                  " Where WorkDate   > '".$_WorkDate."'   \n".
                  "   And Silmooja   = '".$_Silmooja."'   \n".
                  "   And Theather   = '".$_Theather."'   \n".
                  "   And Open       = '".$_Open."'       \n".
                  "   And Film       = '".$_Film."'       \n" ;
        mysql_query($sQuery,$_connect) or die(ee($sQuery)) ;
    }
?>
