    <?
    $oldzoneName = "" ;
    

    while ($singo_data = mysql_fetch_array($QrySingo))
    {
        $UnitPrice     = $singo_data["UnitPrice"] ; // 
        $SumNumPersons = $singo_data["SumNumPersons"] ; // 

        if  ($clrToggle==true)
              {
                  $Color1 = "#c0c0c0" ;
                  $Color2 = "#dcdcdc" ;
              }
              else
              {
                  $Color1 = "#d0d0d0" ;
                  $Color2 = "#ececec" ;
              }
        ?>
        <tr>
            <?
            if  ($oldzoneName != $zoneName)
            {
                ?>
                <!-- ���� -->
                <td class=textarea bgcolor=<?=$Color1?> rowspan=<?=$CntUnitPrice?> class=tbltitle align=center>
                <?=$zoneName?>
                </td>
                <?
                $oldzoneName = $zoneName;
            }

            $TotSumNumPersons += $SumNumPersons ;
            $TotSumPrice += $UnitPrice*$SumNumPersons ;
            ?>

            <!-- ����� -->
            <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=right>
            <?=number_format($UnitPrice)?>&nbsp;
            </td>

            <!-- ������ -->
            <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=right>
            <?=number_format($SumNumPersons)?>&nbsp;
            </td>
            
            <!-- �ݾ� -->
            <td class=textarea bgcolor=<?=$Color1?> class=tbltitle align=right>
            <?=number_format($UnitPrice*$SumNumPersons)?>&nbsp;
            </td>
            
        </tr>
        <?
    }
    ?>



