   <?   
   $Path = "/usr/local/apache2/htdocs/pic/" ;

   while ($ArrTheather = mysql_fetch_array($QryTheather))
   {
        $TheatherCode     = $ArrTheather["Code"] ;          // 상영관
        $TheatherDiscript = $ArrTheather["Discript"] ;      // 상영관명  
        $LocationName     = $ArrTheather["LocationName"] ;  // 상영관지역명

        $File1 = $Path.$TheatherCode.$FilmCode."01.jpg" ;
        $File2 = $Path.$TheatherCode.$FilmCode."02.jpg" ;
        $File3 = $Path.$TheatherCode.$FilmCode."03.jpg" ;
        $File4 = $Path.$TheatherCode.$FilmCode."04.jpg" ;
        $File5 = $Path.$TheatherCode.$FilmCode."05.jpg" ;


        if  (file_exists($File1) || file_exists($File2) || file_exists($File3) || file_exists($File4) || file_exists($File5)) 
        {
        ?>
        <tr>     
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><?=$LocationName?></td>
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><?=$TheatherDiscript?><br>[<?=$TheatherCode?>]</td>            
            
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><?
            if  (file_exists($File1)) 
            {                                                                    
                echo "<a href='../pic/".$TheatherCode.$FilmCode."01.jpg' onMouseOut='return enlargeOut(event)' onMouseOver='return enlarge(\"../pic/".$TheatherCode.$FilmCode."01.jpg\",event)' onFocus='this.blur()'><img src='../pic/".$TheatherCode.$FilmCode."01.jpg' border='0' width=100 height=60></a>" ;
            }
            else
            {
                echo "&nbsp;";
            }
            ?>
            </td>
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><?
            if  (file_exists($File2)) 
            {
                echo "<a href='../pic/".$TheatherCode.$FilmCode."02.jpg' onMouseOut='return enlargeOut(event)' onMouseOver='return enlarge(\"../pic/".$TheatherCode.$FilmCode."02.jpg\",event)' onFocus='this.blur()'><img src='../pic/".$TheatherCode.$FilmCode."02.jpg' border='0' width=100 height=60></a>" ;
            }
            else
            {
                echo "&nbsp;";
            }
            ?>
            </td>
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><?
            if  (file_exists($File3)) 
            {
                echo "<a href='../pic/".$TheatherCode.$FilmCode."03.jpg' onMouseOut='return enlargeOut(event)' onMouseOver='return enlarge(\"../pic/".$TheatherCode.$FilmCode."03.jpg\",event)' onFocus='this.blur()'><img src='../pic/".$TheatherCode.$FilmCode."03.jpg' border='0' width=100 height=60></a>" ;
            }
            else
            {
                echo "&nbsp;";
            }
            ?>
            </td>
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><?
            if  (file_exists($File4)) 
            {
                echo "<a href='../pic/".$TheatherCode.$FilmCode."04.jpg' onMouseOut='return enlargeOut(event)' onMouseOver='return enlarge(\"../pic/".$TheatherCode.$FilmCode."04.jpg\",event)' onFocus='this.blur()'><img src='../pic/".$TheatherCode.$FilmCode."04.jpg' border='0' width=100 height=60></a>" ;
            }
            else
            {
                echo "&nbsp;";
            }
            ?>
            </td>
            <td class=textarea bgcolor=<?=$ColorC?> class=tbltitle align=center><?
            if  (file_exists($File5)) 
            {
                echo "<a href='../pic/".$TheatherCode.$FilmCode."05.jpg' onMouseOut='return enlargeOut(event)' onMouseOver='return enlarge(\"../pic/".$TheatherCode.$FilmCode."05.jpg\",event)' onFocus='this.blur()'><img src='../pic/".$TheatherCode.$FilmCode."05.jpg' border='0' width=100 height=60></a>" ;
            }
            else
            {
                echo "&nbsp;";
            }
            ?>
            </td>
        </tr>
        <?
        }
   }         
   ?> 
   
