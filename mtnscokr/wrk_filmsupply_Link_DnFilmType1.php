
   <table name=score cellpadding=0 cellspacing=0 border=1 bordercolor=#C0B0A0>
   <?


   while ($ArySingo = mysql_fetch_array($QrySingo))
   {
        $singoTheather    = $ArySingo["Theather"] ;      // �Ű�󿵰�
        $singoDiscript    = $ArySingo["Discript"] ;
        $singoOpen        = $ArySingo["Open"] ;          // �Ű�ȭ
        $singoFilm        = $ArySingo["Film"] ;          //

        $CntRoom = 1 ;
        $sQuery = "Select Count(Room) CntRoom                ".
                  "  From bas_showroom                       ".
                  " Where Theather   = '".$singoTheather."'  " ;
        $QryCntRoom = mysql_query($sQuery,$connect) ;
        if  ($AryCntRoom = mysql_fetch_array($QryCntRoom))
        {
            $CntRoom = $AryCntRoom["CntRoom"] ;
        }
        $bFirst = true ;

        $sQuery = "Select * From bas_showroom                ".
                  " Where Theather   = '".$singoTheather."'  ".
                  " Order By Room                            " ;
        $QryShowRoom = mysql_query($sQuery,$connect) ;
        while ($AryShowRoom = mysql_fetch_array($QryShowRoom))
        {
            $Room = $AryShowRoom["Room"] ; //
            ?>
            <tr>
                  <?
                  if  ($bFirst == true)
                  {
                      ?>
                      <td class=textarea width=100 align=center rowspan=<?=$CntRoom?>>
                              <?=$singoDiscript?><BR><?=$singoTheather?>
                      </td>
                      <?
                      $bFirst = false ;
                  }
                  ?>

                  <td class=textarea width=40 align=center>
                          <?=$Room?> ��
                  </td>

                  <!-- 35mm�� 35, ������ ����� 2 ������ ������� 3 ���̸ƽ� ����� 29 ���̸ƽ� ������� 39 -->

                  <?
                  $FilmType = "0" ;

                  $sQuery = "Select Type From ".$tblFilmTypePrv."    ".
                            " Where WorkDate <= '".$WorkDate."'      ".
                            "   And Open     = '".$FilmOpen."'       ".
                            "   And Code     = '".$FilmCode."'       ".
                            "   And Theather = '".$singoTheather."'  ".
                            "   And Room     = '".$Room."'           ".
                            " Order By WorkDate desc                 ".
                            " Limit 0 , 1                            "; //echo $sQuery."<BR>" ;
                  $QryFilmType = mysql_query($sQuery,$connect) ;
                  if  ($ArrFilmType = mysql_fetch_array($QryFilmType))
                  {
                       $FilmType = $ArrFilmType["Type"] ;
                  }

                  $sQuery = "Select FilmType From ".$sSingoName."   ". //////////// 9��5�� //////
                            " Where SingoDate = '".$WorkDate."'     ".
                            "   And Open     = '".$FilmOpen."'      ".
                            "   And Film     = '".$FilmCode."'      ".
                            "   And Theather = '".$singoTheather."' ".
                            "   And Room     = '".$Room."'          " ; //if  (($Room=="03")&&($singoTheather=='2550')) echo $sQuery."<BR>" ;
                  $QryFilmType = mysql_query($sQuery,$connect) ;
                  if  ($ArrFilmType = mysql_fetch_array($QryFilmType))
                  {
                      $FilmType = $ArrFilmType["FilmType"] ;
                  }

                  if   ($FilmType == "") $FilmType = "0" ;


                  if   ($FilmType == 0)
                  {
                       $sQuery = "Select Type From ".$tblFilmType."       ".
                                 " Where Open     = '".$FilmOpen."'       ".
                                 "   And Code     = '".$FilmCode."'       ".
                                 "   And Theather = '".$singoTheather."'  ".
                                 "   And Room     = '".$Room."'           "; //echo $sQuery."<BR>" ;
                       $QryFilmType = mysql_query($sQuery,$connect) ;
                       if  ($ArrFilmType = mysql_fetch_array($QryFilmType))
                       {
                           $FilmType = $ArrFilmType["Type"] ;
                       }
                       else
                       {
                           $sQuery = "Insert Into ".$tblFilmType."  ".
                                     " Value                        ".
                                     "(                             ".
                                     "   '".$FilmOpen."',           ".
                                     "   '".$FilmCode."',           ".
                                     "   '".$singoTheather."',      ".
                                     "   '".$Room."',               ".
                                     "   '35'                       ".
                                     ")                             " ; //echo $sQuery."<BR>" ;
                           mysql_query($sQuery,$connect) ;

                           $sQuery = "Insert Into ".$tblFilmTypePrv." ".
                                     " Value                          ".
                                     "(                               ".
                                     "   '".$WorkDate."',             ".
                                     "   '".$FilmOpen."',             ".
                                     "   '".$FilmCode."',             ".
                                     "   '".$singoTheather."',        ".
                                     "   '".$Room."',                 ".
                                     "   '35'                         ".
                                     ")                               " ; //echo $sQuery."<BR>" ;
                           mysql_query($sQuery,$connect) ;//echo $sQuery."<BR>" ;

                           $FilmType = 35 ;
                       }
                  }


                  $Chk1 = "" ;
                  $Chk2 = "" ;
                  $Chk3 = "" ;
                  $Chk4 = "" ;
                  $Chk5 = "" ;

                  if  ($FilmType == "35") $Chk1 = "checked" ;
                  if  ($FilmType == "2")  $Chk2 = "checked" ;
                  if  ($FilmType == "3")  $Chk3 = "checked" ;
                  if  ($FilmType == "29") $Chk4 = "checked" ;
                  if  ($FilmType == "39") $Chk5 = "checked" ;
                  ?>
                  <td class=textarea >
                          &nbsp;&nbsp;
                          <input type="radio" class="FilmeType" value=35 Theather=<?=$singoTheather?> Room=<?=$Room?> name="rdoFilmeType<?=$singoTheather?><?=$Room?>" <?=$Chk1?>>35mm
                          <input type="radio" class="FilmeType" value=2  Theather=<?=$singoTheather?> Room=<?=$Room?> name="rdoFilmeType<?=$singoTheather?><?=$Room?>" <?=$Chk2?>>������ 2D
                          <input type="radio" class="FilmeType" value=3  Theather=<?=$singoTheather?> Room=<?=$Room?> name="rdoFilmeType<?=$singoTheather?><?=$Room?>" <?=$Chk3?>>������ 3D
                          <input type="radio" class="FilmeType" value=29 Theather=<?=$singoTheather?> Room=<?=$Room?> name="rdoFilmeType<?=$singoTheather?><?=$Room?>" <?=$Chk4?>>���̸ƽ� 2D
                          <input type="radio" class="FilmeType" value=39 Theather=<?=$singoTheather?> Room=<?=$Room?> name="rdoFilmeType<?=$singoTheather?><?=$Room?>" <?=$Chk5?>>���̸ƽ� 3D
                          &nbsp;&nbsp;&nbsp;
                  </td>

            </tr>
            <?
        }
   }
   ?>
   </table>

