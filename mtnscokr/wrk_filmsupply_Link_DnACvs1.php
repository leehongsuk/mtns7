   <?
echo "\n" ;
echo "����"; 
echo (','); 
echo "����"; 
echo (','); 
echo "��ũ���ڵ�";
echo (','); 
echo "�����ڵ�";
echo (','); 
echo "�����"; 
echo (','); 
echo "�¼�"; 
echo (','); 
echo "ȸ��"; 
echo (','); 
echo "���"; 
echo (','); 
echo "���ھ�"; 
echo (','); 
echo "�հ�"; 
echo (','); 
echo "�����հ�"; 
echo (','); 
echo "�����հ�"; 
echo (','); 
echo "����"; 
echo (','); 
echo "���ϱݾ�"; 
echo (','); 
echo "����ݾ�";
echo "\n" ;

   while ($singo_data = mysql_fetch_array($qry_singo))
   {
        for ($i = 1 ; $i <= 12 ; $i++)
        {
            $arrySumOfDegree[$i] = 0 ;  // ȸ���� ���ھ� �հ�
        }

        $singoSilmooja    = $singo_data["Silmooja"] ;      // �Ű�ǹ���
        $singoTheather    = $singo_data["Theather"] ;      // �Ű�󿵰�
        $singoRoom        = $singo_data["Room"] ;          //
        $singoOpen        = $singo_data["Open"] ;          // �Ű�ȭ
        $singoFilm        = $singo_data["Film"] ;          //
        $silmoojaName     = $singo_data["SilmoojaName"] ;  // �Ű� �ǹ��ڸ�  
        $showroomDiscript = $singo_data["Discript"] ;      // �Ű� �󿵰���  
        $showroomLocation = $singo_data["Location"] ;      // �Ű� �󿵰�����
        $locationName     = $singo_data["LocationName"] ;  // �Ű� �󿵰�������
        $showroomSeat     = $singo_data["ShowRoomSeat"] ;  // �Ű� �󿵰��¼�            
        $SumNumPersons    = $singo_data["SumNumPersons"] ; // �� ���ھ�
        $showroomCntDgree = $singo_data["CntDgree"] ;      // ��ȸ����
        $cntUnitPrice     = $singo_data["cntUnitPrice"] ;  // ����������� (����.�󿵰� �÷��� Ȯ���� ���� ����)     

        $sSingoName = get_singotable($singoOpen,$singoFilm,$connect) ;  // �Ű� ���̺� �̸�..
        $sAccName   = get_acctable($singoOpen,$singoFilm,$connect) ;  // accumulate �̸�..

        // �������θ� �˻��Ѵ�.
        $sQuery = "Select * From bas_silmoojatheatherfinish  ".
                  " Where Silmooja = '".$singoSilmooja."'    ".
                  "   And WorkDate <= '".$WorkDate."'        ".
                  "   And Theather = '".$singoTheather."'    ".
                  "   And Room     = '".$singoRoom."'        ".
                  "   And Open     = '".$singoOpen."'        ".
                  "   And Film     = '".$singoFilm."'        " ;
        $qry_silmoojatheatherfinish = mysql_query($sQuery,$connect) ;
        if  ($silmoojatheatherfinish_data = mysql_fetch_array($qry_silmoojatheatherfinish))
        { 
            $isFinished = true ;                                   // ������ �Ǿ���

            $TempDate = $silmoojatheatherfinish_data["WorkDate"] ; // �������� 

            // �Ϸ� ������ ���Ѵ�.
            $FinishDate = date("Ymd",strtotime("-1 day",strtotime(substr($TempDate,0,4)."-".substr($TempDate,4,2)."-".substr($TempDate,6,2).""))) ;
        }
        else
        {
            $isFinished = false ;  // �������� �ʾ���
            $FinishDate = "" ;     //
        }

        // ��ȭ ������ ���ϵ� ��ȭ�� �ٲ�� �������� �����ϰ�
        // �ι��̻� �ݺ��Ǹ� ��ȭ���� �����.
        if  ($filmtitleNameTitle != $singo_data["FilmTitleName"])
        {
            $filmtitleName      = $singo_data["FilmTitleName"] ;
            $filmtitleNameTitle = $singo_data["FilmTitleName"] ;
        }
        else
        {
            $filmtitleName = "" ;
        }

        mysql_free_result($qry_silmoojatheatherfinish) ;



        // �� ���¼��� = ȸ���� * �󿵰� �ڸ��� 
        $showroomTotDgree = $showroomCntDgree * $showroomSeat ;  

/**************************?><table border=1>

         <!--             -->
         <!-- Ÿ��Ʋ ��� -->
         <!--             -->
         <tr>
              <td>����</td>

              <td>����</td>
              
              <td>����</td>

              <td>�¼�</td>

              <td>ȸ��</td>

              <td>���</td>

              <td>���ھ�</td>

              <td>�հ�</td>

              <td>�����հ�</td>
              
              <td>�����հ�</td>
              
              <td>����</td>
              
              <td>���ϱݾ�</td>
              
              <td>����ݾ�</td>               
         </tr><?**************************/
    
         $SumOf99Degree = 0 ; // �ɾ� ȸ�� �հ�
         $SumOfPsToday  = 0 ; // ���� �հ� �հ�
         $SumOfPsAgoDay = 0 ; // ���� �հ� �հ�

         $isFinishBlock = false ; // ��������

         for ($i = 1 ; $i <= 11 ; $i++)
         {
             if  ($i<11) $ShowDegree = sprintf("%02d",$i) ; // 1ȸ ���� 10ȸ ����..
             else        $ShowDegree = "99" ;              // �ɾ�

             
             $qry_dgree = mysql_query("Select distinct UnitPrice, NumPersons      ".
                                      "  From ".$sSingoName."                     ".
                                      " Where SingoDate  = '".$WorkDate."'        ".
                                      "   And Theather   = '".$singoTheather."'   ".
                                      "   And Room       = '".$singoRoom."'       ".
                                      "   And Open       = '".$singoOpen."'       ".
                                      "   And Film       = '".$singoFilm."'       ".
                                      "   And ShowDgree  = '".$ShowDegree."'      ".
                                      " Order By UnitPrice desc                   ",$connect) ;

             $affected_row = mysql_affected_rows() ;

             if  ($affected_row > 0)
             {
                 while ($dgree_data = mysql_fetch_array($qry_dgree))
                 {
                       $UnitPrice  = $dgree_data["UnitPrice"] ;
                       $NumPersons = $dgree_data["NumPersons"] ;
                       ?>
<?/******?>
                       <tr>       
                            <!-- ���� -->
                            <td><B><?=$WorkDate?></B></td>
<?******/echo $WorkDate ; 
echo (',');?>
<?/******?>
                            <!-- ���� -->
                            <td><?=$locationName?></td>
<?******/echo $locationName ; 
echo (',');
echo $singoRoom ;
echo (',');
echo $singoTheather ;
echo (',');?>
<?/******?>
                            <!-- �󿵰��� -->
                            <td><?=$showroomDiscript?></td>
<?******/echo $showroomDiscript ; 
echo (',');?>
<?/******?>
                            <!-- �¼��� -->
                            <td><?=$showroomSeat?></td>
<?******/echo $showroomSeat ; 
echo (',');?>
<?/******?>
                            <!-- ȸ�� -->
                            <td><?=$ShowDegree?></td>
<?******/echo $ShowDegree ; 
echo (',');?>
<?/******?>
                            <!-- ��� (0=������) -->
<?******/

                            if  ($UnitPrice > 0) 
                            {
/******?>
                                <td><?=$UnitPrice?></td>
<?******/echo $UnitPrice ; 
echo (',');
                            }
                            else
                            {
/******?>
                                <td>������</td>
<?******/echo "������" ; 
echo (',');?>
<?
                            }

/******
                            if  ($isFinished == true)
                            {                            
                                if  ($isFinishBlock == false)
                                {                      
                                    $cntUnitPriceP1 = $cntUnitPrice+1 ;

                                    ?>
                                    <td rowspan=<?=$cntUnitPriceP1?>>
                                    ����(<?=substr($FinishDate,2,2)?>/<?=substr($FinishDate,4,2)?>/<?=substr($FinishDate,6,2)?>)ó����
                                    </td>

                                    <?
                                }
                                $isFinishBlock = true ;
                            }
                            else
                            {     
                                
                            }
*********/?>
<?/******?>
                            <!-- ���ھ� -->
                            <td><?=$NumPersons?></td>
<?******/echo $NumPersons ; 
echo (',');
                            $qry_SumNumPersons = mysql_query("Select Sum(NumPersons) As SumNumPersons    ".
                                                             "  From ".$sSingoName."                     ".
                                                             " Where SingoDate  = '".$WorkDate."'        ".
                                                             "   And Theather   = '".$singoTheather."'   ".
                                                             "   And Room       = '".$singoRoom."'       ".
                                                             "   And Open       = '".$singoOpen."'       ".
                                                             "   And Film       = '".$singoFilm."'       ".
                                                             "   And ShowDgree  = '".$ShowDegree."'      ",$connect) ;
                            if  ( $SumNumPersons_data = mysql_fetch_array($qry_SumNumPersons) )
                            {
                                $SumOfDegree = $SumNumPersons_data["SumNumPersons"] ;
                            }
                            else
                            {
                                $SumOfDegree = 0 ;
                            }
?>
<?/******?>
                            <!-- �հ� -->
                            <td><?=$SumOfDegree?></td>
<?******/echo $SumOfDegree ; 
echo (',');
                            $qry_SumNumPersons = mysql_query("Select Sum(NumPersons) As SumNumPersons    ".
                                                             "  From ".$sSingoName."                     ".
                                                             " Where SingoDate  = '".$WorkDate."'        ".
                                                             "   And Theather   = '".$singoTheather."'   ".
                                                             "   And Room       = '".$singoRoom."'       ".
                                                             "   And Open       = '".$singoOpen."'       ".
                                                             "   And Film       = '".$singoFilm."'       ".
                                                             "   And UnitPrice  = '".$UnitPrice."'       ",$connect) ;
                            if  ( $SumNumPersons_data = mysql_fetch_array($qry_SumNumPersons) )
                            {
                                $SumOfUnitPrice = $SumNumPersons_data["SumNumPersons"] ;
                            }
                            else
                            {
                                $SumOfUnitPrice = 0 ;
                            }
?>
<?/******?>
                            <!-- �����հ� -->
                            <td><?=$SumOfUnitPrice?></td>
<?******/echo $SumOfUnitPrice ; 
echo (',');
                            $qry_singo2 = mysql_query("Select Sum(NumPersons) As SumNumPersons   ".
                                                      "  From ".$sSingoName."                    ".
                                                      " Where SingoDate  = '".$AgoDate."'        ".
                                                      "   And Theather   = '".$singoTheather."'  ".
                                                      "   And Room       = '".$singoRoom."'      ".
                                                      "   And Open       = '".$singoOpen."'      ".
                                                      "   And Film       = '".$singoFilm."'      ".
                                                      "   And UnitPrice  = '".$UnitPrice."'      ",$connect) ;
                            if  ($singo2_data = mysql_fetch_array($qry_singo2))
                            {
                                $SumAgoDay = $singo2_data["SumNumPersons"]+0 ;
                                                           
                                $SumOfPsAgoDay += $SumAgoDay ; // ���� �հ� �հ�
                            }
                            else
                            {                                
                                $SumAgoDay = "0" ;
                                
                            }
?>
<?/******?>
                            <!-- �����հ� ��� -->
                            <td><?=$SumAgoDay?></td>
<?******/echo $SumAgoDay ; 
echo (',');
                            $qry_accumulate = mysql_query("Select Accu, TotAccu, AcMoney, TotAcMoney  ".
                                                          "  From ".$sAccName."                      ".
                                                          " Where WorkDate   = '".$WorkDate."'        ".
                                                          "   And Theather   = '".$singoTheather."'   ".
                                                          "   And Open       = '".$singoOpen."'       ".
                                                          "   And Film       = '".$singoFilm."'       ".
                                                          "   And UnitPrice  = '".$UnitPrice."'       ",$connect) ; 
                            $accumulate_data = mysql_fetch_array($qry_accumulate) ;
                            if  (!$accumulate_data)  // ������
                            {     
                                // ���ϴ���
                                $qry_singo2 = mysql_query("Select Sum(NumPersons) As SumNumPersons,  ".
                                                          "       Sum(TotAmount)  As SumTotAmount    ".
                                                          "  From ".$sSingoName."                   ".
                                                          " Where SingoDate  <= '".$WorkDate."'      ".
                                                          "   And Theather   = '".$singoTheather."'  ".
                                                          "   And Open       = '".$singoOpen."'       ".
                                                          "   And Film       = '".$singoFilm."'       ".
                                                          "   And UnitPrice  = '".$UnitPrice."'      ",$connect) ;
                                $NumPersons_data = mysql_fetch_array($qry_singo2) ;
                                if  ($NumPersons_data)
                                {
                                    mysql_query("Insert Into ".$sAccName."                   ".
                                                "Values                                       ".
                                                "(                                            ".
                                                "    '".$WorkDate."',                         ".
                                                "    '".$singoSilmooja."',                    ".
                                                "    '".$filmsupplyCode."',                   ".
                                                "    '".$singoTheather."',                    ".
                                                "    '".$singoOpen."',                        ".
                                                "    '".$singoFilm."',                        ".
                                                "    '".$UnitPrice."',                        ".
                                                "    '".$NumPersons_data["SumNumPersons"]."', ".
                                                "    '0',                                     ".
                                                "    '".$NumPersons_data["SumTotAmount"]."',  ".
                                                "    '0',                                     ".
                                                "    '".$showroomLocation."',                 ".
                                                "    '".$NumPersons."',                       ".
                                                "    '".$NumPersons*$UnitPrice."'             ".
                                                ")                                            ",$connect) ; 
                                }

                                // ���ϴ���
                                $qry_singo2 = mysql_query("Select Sum(NumPersons) As SumNumPersons,  ".
                                                          "       Sum(TotAmount)  As SumTotAmount    ".
                                                          "  From ".$sSingoName."                    ".
                                                          " Where SingoDate  <= '".$WorkDate."'      ".
                                                          "   And Theather   = '".$singoTheather."'  ".
                                                          "   And Open       = '".$singoOpen."'      ".
                                                          "   And Film       = '".$singoFilm."'      ",$connect) ;
                                $NumPersons_data = mysql_fetch_array($qry_singo2) ;
                                if  ($NumPersons_data)
                                {
                                    mysql_query("Update ".$sAccName."                                        ".
                                                "   Set TotAccu    = '".$NumPersons_data["SumNumPersons"]."', ".
                                                "       TotAcMoney = '".$NumPersons_data["SumTotAmount"]."',  ".
                                                "       Location   = '".$showroomLocation."',                 ".
                                                "       TodayScore = '".$NumPersons."',                       ".
                                                "       TodayMoney = '".$NumPersons*$UnitPrice."'             ".
                                                " Where WorkDate   = '".$WorkDate."'                          ".
                                                "   And Silmooja   = '".$singoSilmooja."'                     ".
                                                "   And Theather   = '".$singoTheather."'                     ".
                                                "   And Open       = '".$singoOpen."'                         ".
                                                "   And Film       = '".$singoFilm."'                         ",$connect) ; 
                                }
                            }

                            
                            $qry_accumulate = mysql_query("Select Accu, TotAccu, AcMoney, TotAcMoney  ".
                                                          "  From ".$sAccName."                      ".
                                                          " Where WorkDate   = '".$WorkDate."'        ".
                                                          "   And Theather   = '".$singoTheather."'   ".
                                                          "   And Open       = '".$singoOpen."'       ".
                                                          "   And Film       = '".$singoFilm."'       ".
                                                          "   And UnitPrice  = '".$UnitPrice."'       ",$connect) ; 
                            
                            $accumulate_data = mysql_fetch_array($qry_accumulate) ;                            
?>
<?/******?>
                            <!-- ���� -->
                            <td><?=$accumulate_data["Accu"]?></td>
<?******/echo $accumulate_data["Accu"] ; 
echo (',');?>
<?/******?>
                            <!-- ���ϱݾ� -->
                            <td><?=($SumOfUnitPrice * $UnitPrice)?></td>
<?******/echo ($SumOfUnitPrice * $UnitPrice) ; 
echo (',');?>
<?/******?>
                            <!-- ����ݾ� -->
                            <td><?=($accumulate_data["Accu"] * $UnitPrice)?></td>
<?******/                            
echo ($accumulate_data["Accu"] * $UnitPrice) ;
echo "\n";?>
<?/******?>
                       </tr>
<?******/
                 }
             }
         }
   }         
?>