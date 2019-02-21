<?

      function Get_MaxDate_BoxOffice($connect_)
      {
          $MaxDate = "" ;

          $sQuery = " SELECT MAX(Date) MaxDate
                        FROM kofic_boxoffice
                    " ;  //echo "<br>".iconv("EUC-KR", "UTF-8",$sQuery); ;
          $QryMaxDate = mysql_query($sQuery,$connect_) ;
          if  ($ArrMaxDate = mysql_fetch_array($QryMaxDate))
          {
              $MaxDate = $ArrMaxDate["MaxDate"]; // 박스오피스 가장최근일자..
          }

          return $MaxDate ;
      }


      function Make_Fix_Boxoffice($BaseDate_,$connect_)
      {
          $sQuery = "    SELECT count(*) cntMovie
                           FROM kofic_fix_boxoffice
                          WHERE `Date` = '$BaseDate_'
                    " ; // echo "<br>".iconv("EUC-KR", "UTF-8",$sQuery); ;
          $QryBoxOffice = mysql_query($sQuery,$connect_) ;
          if  ($ArrBoxOffice = mysql_fetch_array($QryBoxOffice))
          {
              $cntMovie = $ArrBoxOffice["cntMovie"];
          }

          if  ($cntMovie==0)
          {
              $Rank = 0;

              $a1 = iconv("UTF-8", "EUC-KR", '(디지털)');
              $a2 = iconv("UTF-8", "EUC-KR", '(필름)');
              $a3 = iconv("UTF-8", "EUC-KR", '(디지털더빙)');
              $a4 = "(IMAX 3D)" ;
              $a5 = "(4D)";

              $sQuery = "      SELECT IFNULL(`Date`,'$BaseDate_') `Date`
                                     ,bo.Rank
                                     ,IFNULL(bo.MovieCd,mv.Code) MovieCd
                                     ,replace(replace(replace(replace(replace(if(bo.MovieNm is null,mv.MovieName,bo.MovieNm),'$a1',''),'$a2',''),'$a3',''),'$a4',''),'$a5','') MovieNm
                                 FROM (select * from kofic_boxoffice WHERE `Date` = '$BaseDate_') bo
                            LEFT JOIN kofic_movie mv
                                   ON bo.MovieCd = mv.Code
                                UNION
                               SELECT IFNULL(`Date`,'$BaseDate_') `Date`
                                     ,IFNULL(bo.Rank,99999) Rank
                                     ,IFNULL(bo.MovieCd,mv.Code) MovieCd
                                     ,replace(replace(replace(replace(replace(if(bo.MovieNm is null,mv.MovieName,bo.MovieNm),'$a1',''),'$a2',''),'$a3',''),'$a4',''),'$a5','') MovieNm
                                 FROM (select * from kofic_boxoffice WHERE `Date` = '$BaseDate_') bo
                           RIGHT JOIN kofic_movie mv
                                   ON bo.MovieCd = mv.Code
                             ORDER BY Rank
                       " ; // echo "<br>".iconv("EUC-KR", "UTF-8",$sQuery);   // 박스오피스+영화 리스트를 구한다.
              $QryBoxOffice = mysql_query($sQuery,$connect_) ;
              while  ($ArrBoxOffice = mysql_fetch_array($QryBoxOffice))
              {
                  $Date     = $ArrBoxOffice["Date"];
                  $Rank ++ ;
                  $MovieCd  = $ArrBoxOffice["MovieCd"];
                  $MovieNm  = $ArrBoxOffice["MovieNm"];

                  $sQuery = "  INSERT INTO kofic_fix_boxoffice
                                           (Date, Rank, MovieCd, MovieNm)
                                    VALUES ('$Date',$Rank,'$MovieCd','$MovieNm')
                             " ; // echo "<br>".iconv("EUC-KR", "UTF-8",$sQuery); ;
                  mysql_query($sQuery,$connect_) ;
              }
          }
      }

?>