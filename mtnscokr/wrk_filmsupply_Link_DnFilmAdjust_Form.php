<?
    session_start();
?>
<html>
      <?
      $db1   = "mtns" ;
      $db2   = "mtnsback" ;

      $connect1 = mysql_connect( "localhost", "mtns",     "5421")  or  Error("DB 접속시 에러가 발생했습니다");
      $connect2 = mysql_connect( "localhost", "mtnsback", "5421")  or  Error("DB 접속시 에러가 발생했습니다");

      mysql_select_db($db1,  $connect1) ;
      mysql_select_db($db2,  $connect2) ;

      ////
      /// 해당 테이블의 레코드 갯수를 구한다.. 단 테이블이 없으면 -1
      //
      function GetCount($_Table,$_connect,$_db)
      {
          $Result = -1 ;

          if  ($_Table!="")
          {
              $sQuery = "show tables where tables_in_".$_db." ='".$_Table."' " ; //echo $sQuery ;
              if ($QryCnt = mysql_query($sQuery,$_connect))
              {
                  if ($ArrCnt = mysql_fetch_array($QryCnt))
                  {
                      $sQuery = "Select Count(*) As Cnt From ".$_Table." " ; //echo $sQuery ;
                      $QryCnt = mysql_query($sQuery,$_connect) ;
                      if ($ArrCnt = mysql_fetch_array($QryCnt))
                      {
                          $Result = $ArrCnt["Cnt"] ;
                      }
                  }
              }
          }

          return $Result ;
      }

      ////
      /// 해당 테이블의 레코드 갯수를 구한다..
      //
      function GetCountRow($_Table,$_FieldOpen,$_FieldFilm,$_Open,$_Film,$_connect)
      {
          $Result = 0 ;

          $sQuery = "Select Count(*) As Cnt 
                       From ".$_Table." 
                      Where $_FieldOpen = '".$_Open."'
                        And $_FieldFilm = '".$_Film."'
                    " ; //echo $sQuery ;
          $QryCnt = mysql_query($sQuery,$_connect) ;
          if ($ArrCnt = mysql_fetch_array($QryCnt))
          {
              $Result = $ArrCnt["Cnt"] ;
          }

          return $Result ;
      }


      ////
      ///  필름 추가
      //
      function AddFilmTitle($_No,$_txtOpen,$_txtCode1,$_txtCode,$_txtName,$_txtExcelTitle,$_connect)  ///////////////////////////////////////////////////////////////////////////
      {
          if  ($_txtCode != "") // 필름코드 여부 조사
          {
              $sQuery = "Select Count(*) As Cnt
                           From bas_filmtitle
                          Where Open = '".$_txtOpen."'
                            And Code = '".$_txtCode."'
                        " ;  ////echo $sQuery."<br>" ;
              $QryCnt = mysql_query($sQuery,$_connect) ;
              if ($ArrCnt = mysql_fetch_array($QryCnt))
              {
                  $Cnt = $ArrCnt["Cnt"] ;
              }
              if  ($Cnt==1)
              {
                  $Message = "$_txtOpen/$_txtCode 은 이미 존재합니다.. \n" ;
              }
              else
              {
                  $wrk_singo      = "wrk_singo_".$_txtOpen."_".$_txtCode1."" ;
                  $bas_acc        = "bas_acc_".$_txtOpen."_".$_txtCode1."" ;
                  $bas_dgr        = "bas_dgr_".$_txtOpen."_".$_txtCode1."" ;
                  $bas_dgrp       = "bas_dgrp_".$_txtOpen."_".$_txtCode1."" ;
                  $bas_srorder    = "bas_srorder_".$_txtOpen."_".$_txtCode1."" ;
                  $bas_filmtype   = "bas_filmtype_".$_txtOpen."_".$_txtCode1."" ;
                  $bas_filmtypep  = "bas_filmtypep_".$_txtOpen."_".$_txtCode1."" ;

                  if  ($_No==1)  { $Extension = "Y";  $ExcelTitle = $_txtExcelTitle; }
                  else           { $Extension = "N";  $ExcelTitle = ""; }


                  $sQuery = "Insert Into bas_filmtitle
                             Values
                             (
                                   '".$_txtOpen."',
                                   '".$_txtCode."',
                                   '".$_txtName."',
                                   '400001',
                                   '워너브라더스',
                                   '".$ExcelTitle."',
                                   '".$wrk_singo."',
                                   '".$bas_acc."',
                                   '".$bas_dgr."',
                                   '".$bas_dgrp."',
                                   '".$bas_srorder."',
                                   '".$bas_filmtype."',
                                   '".$bas_filmtypep."',
                                   '',
                                   'N',
                                   '".$Extension."'
                             )
                            " ; ////echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect) ;

                  if  ($_No==1)
                  {
                       //////////// 9月5日 //////
                      $sQuery      = "Create Table ".$wrk_singo."
                                      (
                                         SingoTime      varchar(14) NOT NULL default '',
                                         SingoDate      varchar(8) NOT NULL default '',
                                         Silmooja       varchar(6) NOT NULL default '',
                                         Location       char(3) NOT NULL default '',
                                         Theather       varchar(4) NOT NULL default '',
                                         Room           char(2) NOT NULL default '',
                                         Open           varchar(6) NOT NULL default '',
                                         Film           char(2) NOT NULL default '',
                                         FilmType       char(3) NOT NULL default '',
                                         ShowDgree      char(2) NOT NULL default '',
                                         UnitPrice      decimal(6,0) NOT NULL default '0',
                                         NumPersons     decimal(10,0) NOT NULL default '0',
                                         TotAmount      decimal(20,0) NOT NULL default '0',
                                         TotAmountGikum decimal(20,0) NOT NULL,
                                         Phoneno        varchar(11) default NULL,
                                         RoomOrder      int(11) NOT NULL default '0',
                                         PRIMARY KEY  (SingoDate,Silmooja,Location,Theather,Room,Open,Film,ShowDgree,UnitPrice),
                                         KEY Idx_Silmooja  (Silmooja),
                                         KEY Idx_FilmTitle (Open,Film),
                                         KEY Idx_ShowRoom  (Theather,Room),
                                         KEY Idx_Location  (Location),
                                         KEY Idx_RoomOrder (RoomOrder)
                                      )
                                     " ; //echo $sQuery."<br>" ;
                      mysql_query($sQuery,$_connect) ;

                      $sQuery        = "Create Table ".$bas_acc."
                                        (
                                           WorkDate      varchar(8) NOT NULL default '',
                                           Silmooja      varchar(6) NOT NULL default '',
                                           Theather      varchar(4) NOT NULL default '',
                                           Open          varchar(6) NOT NULL default '',
                                           Film          char(2) NOT NULL default '',
                                           FilmType      char(3) NOT NULL default '',
                                           UnitPrice     decimal(6,0) NOT NULL default '0',
                                           Accu          int(11) default NULL,
                                           TotAccu       int(11) default NULL,
                                           AcMoney       int(10) unsigned default NULL,
                                           TotAcMoney    int(10) unsigned default NULL,
                                           Location      char(3) default NULL,
                                           TodayScore    int(11) default NULL,
                                           TodayMoney    int(11) default NULL,
                                           PRIMARY KEY  (WorkDate,Open,Film,FilmType,Theather,Silmooja,UnitPrice)
                                        )
                                       " ; //echo $sQuery."<br>" ;
                      mysql_query($sQuery,$_connect) ;

                      $sQuery        = "Create Table ".$bas_dgr."
                                        (
                                           Theather      varchar(4) NOT NULL default '',
                                           Room          char(2) NOT NULL default '',
                                           Degree        char(2) NOT NULL default '',
                                           Silmooja      varchar(6) NOT NULL default '',
                                           Open          varchar(6) NOT NULL default '',
                                           Film          char(2) NOT NULL default '',
                                           Time          varchar(4) default NULL,
                                           Discript      varchar(100) NOT NULL default '',
                                           PRIMARY KEY  (Theather,Room,Degree,Silmooja,Open,Film)
                                        )
                                       " ; //echo $sQuery."<br>" ;
                      mysql_query($sQuery,$_connect) ;

                      $sQuery       = "Create Table ".$bas_dgrp."
                                       (
                                          Silmooja      varchar(6) NOT NULL default '',
                                          WorkDate      varchar(8) NOT NULL default '',
                                          Open          varchar(6) NOT NULL default '',
                                          Film          char(2) NOT NULL default '',
                                          Theather      varchar(4) NOT NULL default '',
                                          Room          char(2) NOT NULL default '',
                                          Degree        char(2) NOT NULL default '',
                                          Time          varchar(4) NOT NULL default '',
                                          Discript      varchar(100) NOT NULL default '',
                                          PRIMARY KEY  (Silmooja,WorkDate,Open,Film,Theather,Room,Degree)
                                       )
                                      " ; //echo $sQuery."<br>" ;
                      mysql_query($sQuery,$_connect) ;

                      $sQuery    = "Create Table ".$bas_srorder."
                                    (
                                       Seq           int(11) NOT NULL default '0',
                                       SeqNo1        int(6) NOT NULL,
                                       SeqNo2        int(2) NOT NULL,
                                       Theather      varchar(4) NOT NULL default '',
                                       Room          char(2) NOT NULL default '',
                                       Location      char(3) NOT NULL default '',
                                       Discript      varchar(100) default NULL,
                                       PRIMARY KEY  (SeqNo1,SeqNo2),
                                       KEY Idx_seq  (Seq),
									   KEY Idx_theather_seq (Theather,Seq)
                                    )
                                   " ; //echo $sQuery."<br>" ;
                      mysql_query($sQuery,$_connect) ;


                      ///////////////

                      $sQuery = "Select Open, RoomOrder
                                   From bas_filmtitle
                                  Where Open <> '999999'
                                    And Open <> '".$_txtOpen."'
                                  Order By Open Desc
                                  Limit 0,1
                                " ;  //echo $sQuery."<br>" ;
                      $QryRoomOrder = mysql_query($sQuery,$_connect) ;
                      if  ($ArrRoomOrder = mysql_fetch_array($QryRoomOrder))
                      {
                          $RoomOrder = $ArrRoomOrder["RoomOrder"] ;

                          $sQuery = "Insert Into ".$bas_srorder." SELECT * FROM ".$RoomOrder." " ; //echo $sQuery."<br>" ;
                          mysql_query($sQuery,$_connect) ;
                      }

                      ///////////////

                      $sQuery   = "Create Table ".$bas_filmtype."
                                   (
                                      Open          varchar(6) NOT NULL default '',
                                      Code          char(2) NOT NULL default '',
                                      Theather      varchar(4) NOT NULL default '',
                                      Room          char(2) NOT NULL default '',
                                      Type          char(3) NOT NULL default '',
                                      PRIMARY KEY  (Open,Code,Theather,Room,Type)
                                   )
                                  " ; //echo $sQuery."<br>" ;
                      mysql_query($sQuery,$_connect) ;






                      $sQuery  = "Create Table ".$bas_filmtypep."
                                  (
                                     WorkDate      varchar(8) NOT NULL default '',
                                     Open          varchar(6) NOT NULL default '',
                                     Code          char(2) NOT NULL default '',
                                     Theather      varchar(4) NOT NULL default '',
                                     Room          char(2) NOT NULL default '',
                                     Type          char(3) NOT NULL default '',
                                     PRIMARY KEY  (WorkDate,Open,Code,Theather,Room,Type)
                                  )
                                 " ; //echo $sQuery."<br>" ;
                      mysql_query($sQuery,$_connect) ;
                  }

                  $Message = "영화 추가 완료!" ;
              }
          }
          //echo $Message."<br>" ;;
          return $Message;
      }

      ////
      /// 테이블 이동.. 권한관계 확인 할 것..
      //
      function MoveTable($_Table,$_connectA,$_connectB,$_dbA,$_dbB)  //_______________________________________________________________
      {
          $Cnt1 = GetCount($_Table,$_connectA,$_dbA) ;
          $Cnt2 = GetCount($_Table,$_connectB,$_dbB) ;

          if  ($Cnt1 != -1) // 테이블1이 존재하면
          {
              if  ($Cnt2 != -1) // 테이블2이 존재하면
              {
                  $sQuery = "Drop Table $_Table " ; //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connectB) ;
              }
              $sQuery = "Create Table $_dbB.$_Table Select * From $_Table " ;  //echo $sQuery."<br>" ;
              mysql_query($sQuery,$_connectA) ;



              $Cnt2 = GetCount($_Table,$_connectB,$_dbB) ;
//echo $Cnt1.":".$Cnt2."<BR>";
              if  ($Cnt1 == $Cnt2) // 백업이 올바로 되었는지 확인......
              {
                  $sQuery = "Drop Table $_Table " ; //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connectA) ;
              }
          }

          return $Cnt1 ;
      }

      ////
      /// 테이블의 Row 이동.. 권한관계 확인 할 것..
      //
      function MoveTableRow($_Table,$_FieldOpen,$_FieldFilm,$_Open,$_Code,$_connectA,$_connectB,$_dbA,$_dbB)  //_______________________________________________________________
      {
          $Cnt1 = GetCountRow($_Table,$_FieldOpen,$_FieldFilm,$_Open,$_Code,$_connectA) ;
          if  ($Cnt1 > 0)
          {              
              $sQuery = "Delete From $_Table 
                               Where $_FieldOpen = '".$_Open."'
                                 And $_FieldFilm = '".$_Code."'
                        " ; //echo $sQuery."<br>" ;
              mysql_query($sQuery,$_connectB) ;

              $sQuery = "Insert Into $_dbB.$_Table 
                              Select * From $_Table
                               Where $_FieldOpen = '".$_Open."'
                                 And $_FieldFilm = '".$_Code."'                          
                        " ;  //echo $sQuery."<br>" ;
              mysql_query($sQuery,$_connectA) ;

              $Cnt1 = GetCountRow($_Table,$_FieldOpen,$_FieldFilm,$_Open,$_Code,$_connectA) ;
              $Cnt2 = GetCountRow($_Table,$_FieldOpen,$_FieldFilm,$_Open,$_Code,$_connectB) ;
//echo $Cnt1.":".$Cnt2."<BR>";
              if  ($Cnt1 == $Cnt2) // 백업이 올바로 되었는지 확인......
              {
                  $sQuery = "Delete From $_Table 
                                   Where $_FieldOpen = '".$_Open."'
                                     And $_FieldFilm = '".$_Code."'
                            " ; //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connectA) ;
              }
          }

          return $Cnt1 ;
      }

      ////
      /// 백업시작
      //
      function BackupFilm($_Open,$_Code,$_connect1,$_connect2,$_db1,$_db2)  ///////////////////////////////////////////////////////////////////////////
      {
          $sQuery = "Select * From bas_filmtitle
                      Where Open = '".$_Open."'
                        And Code = '".$_Code."'
                    " ;  //echo $sQuery."<br>" ;
          $QryFilmtitle = mysql_query($sQuery,$_connect1) ;
          if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
          {
              $SingoName = $ArrFilmtitle["SingoName"] ;
              $AccName   = $ArrFilmtitle["AccName"] ;
              $DgrName   = $ArrFilmtitle["DgrName"] ;
              $DgrpName  = $ArrFilmtitle["DgrpName"] ;
              $FtName    = $ArrFilmtitle["FtName"] ;
              $FtpName   = $ArrFilmtitle["FtpName"] ;
              $RoomOrder = $ArrFilmtitle["RoomOrder"] ;

              $_Table = $SingoName ; $Cnt = MoveTable($_Table,$_connect1,$_connect2,$_db1,$_db2) ;

              if  ($Cnt != -1) /// 인덱스 추가..
              {
                  $sQuery = "ALTER TABLE $_Table ADD PRIMARY KEY ( SingoDate, Silmooja, Location, Theather, Room, Open, Film, ShowDgree, UnitPrice )  " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect2) ;
                  $sQuery = "ALTER TABLE $_Table ADD INDEX Idx_Silmooja ( Silmooja )  " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect2) ;
                  $sQuery = "ALTER TABLE $_Table ADD INDEX Idx_FilmTitle ( Open, Film ) " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect2) ;
                  $sQuery = "ALTER TABLE $_Table ADD INDEX Idx_ShowRoom ( Theather, Room ) " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect2) ;
                  $sQuery = "ALTER TABLE $_Table ADD INDEX Idx_Location ( Location ) " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect2) ;
                  $sQuery = "ALTER TABLE $_Table ADD INDEX Idx_RoomOrder ( RoomOrder ) " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect2) ;
              }

              $_Table = $AccName ; $Cnt = MoveTable($_Table,$_connect1,$_connect2,$_db1,$_db2) ;

              if  ($Cnt != -1) /// 인덱스 추가..
              {
                  $sQuery = "ALTER TABLE $_Table ADD PRIMARY KEY ( WorkDate, Open, Film, Theather, Silmooja, UnitPrice )  " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect2) ;
              }

              $_Table = $DgrName ; $Cnt = MoveTable($_Table,$_connect1,$_connect2,$_db1,$_db2) ;

              if  ($Cnt != -1) /// 인덱스 추가..
              {
                  $sQuery = "ALTER TABLE $_Table ADD PRIMARY KEY ( Theather, Room, Degree, Silmooja, Open, Film )  " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect2) ;
              }

              $_Table = $DgrpName ; $Cnt = MoveTable($_Table,$_connect1,$_connect2,$_db1,$_db2) ;

              if  ($Cnt != -1) /// 인덱스 추가..
              {
                  $sQuery = "ALTER TABLE $_Table ADD PRIMARY KEY  ( Silmooja, WorkDate, Open, Film, Theather, Room, Degree ) " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect2) ;
              }

              $_Table = $FtName ; $Cnt = MoveTable($_Table,$_connect1,$_connect2,$_db1,$_db2) ;

              if  ($Cnt != -1) /// 인덱스 추가..
              {
                  $sQuery = "ALTER TABLE $_Table ADD PRIMARY KEY  ( Open, Code, Theather, Room, Type ) " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect2) ;
              }

              $_Table = $FtpName ; $Cnt = MoveTable($_Table,$_connect1,$_connect2,$_db1,$_db2) ;

              if  ($Cnt != -1) /// 인덱스 추가..
              {
                  $sQuery = "ALTER TABLE $_Table ADD PRIMARY KEY  ( WorkDate, Open, Code, Theather, Room, Type ) " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect2) ;
              }

              $_Table = $RoomOrder ; $Cnt = MoveTable($_Table,$_connect1,$_connect2,$_db1,$_db2) ;

              if  ($Cnt != -1) /// 인덱스 추가..
              {
                  $sQuery = "ALTER TABLE $_Table ADD PRIMARY KEY  ( SeqNo1, SeqNo2 ) " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect2) ;
                  $sQuery = "ALTER TABLE $_Table ADD INDEX Idx_RoomOrder ( Seq ) " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect2) ;
              }

              $_Table = "bas_silmoojatheather" ;     $Cnt = MoveTableRow($_Table,"Open","Film",$_Open,$_Code,$_connect1,$_connect2,$_db1,$_db2) ;
              $_Table = "bas_silmoojatheatherpriv" ; $Cnt = MoveTableRow($_Table,"Open","Film",$_Open,$_Code,$_connect1,$_connect2,$_db1,$_db2) ;
              $_Table = "bas_degree" ;     $Cnt = MoveTableRow($_Table,"Open","Film",$_Open,$_Code,$_connect1,$_connect2,$_db1,$_db2) ;
              $_Table = "bas_degreepriv" ; $Cnt = MoveTableRow($_Table,"Open","Film",$_Open,$_Code,$_connect1,$_connect2,$_db1,$_db2) ;
          }

          $Message = "백업완료!!";
          return $Message;
      }

      ////
      /// 복원시작
      //
      function RestoreFilm($_Open,$_Code,$_connect1,$_connect2,$_db1,$_db2)  ///////////////////////////////////////////////////////////////////////////
      {
          $sQuery = "Select * From bas_filmtitle
                      Where Open = '".$_Open."'
                        And Code = '".$_Code."'
                    " ;  //echo $sQuery."<br>" ;
          $QryFilmtitle = mysql_query($sQuery,$_connect1) ;
          if  ($ArrFilmtitle = mysql_fetch_array($QryFilmtitle))
          {
              $SingoName = $ArrFilmtitle["SingoName"] ;
              $AccName   = $ArrFilmtitle["AccName"] ;
              $DgrName   = $ArrFilmtitle["DgrName"] ;
              $DgrpName  = $ArrFilmtitle["DgrpName"] ;
              $FtName    = $ArrFilmtitle["FtName"] ;
              $FtpName   = $ArrFilmtitle["FtpName"] ;
              $RoomOrder = $ArrFilmtitle["RoomOrder"] ;

              $_Table = $SingoName ; $Cnt = MoveTable($_Table,$_connect2,$_connect1,$_db2,$_db1) ;

              if  ($Cnt != -1) /// 인덱스 추가..
              {
                  $sQuery = "ALTER TABLE $_Table ADD PRIMARY KEY ( SingoDate, Silmooja, Location, Theather, Room, Open, Film, ShowDgree, UnitPrice )  " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect1) ;
                  $sQuery = "ALTER TABLE $_Table ADD INDEX Idx_Silmooja ( Silmooja )  " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect1) ;
                  $sQuery = "ALTER TABLE $_Table ADD INDEX Idx_FilmTitle ( Open, Film ) " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect1) ;
                  $sQuery = "ALTER TABLE $_Table ADD INDEX Idx_ShowRoom ( Theather, Room ) " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect1) ;
                  $sQuery = "ALTER TABLE $_Table ADD INDEX Idx_Location ( Location ) " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect1) ;
                  $sQuery = "ALTER TABLE $_Table ADD INDEX Idx_RoomOrder ( RoomOrder ) " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect1) ;
              }

              $_Table = $AccName ; $Cnt = MoveTable($_Table,$_connect2,$_connect1,$_db2,$_db1) ;

              if  ($Cnt != -1) /// 인덱스 추가..
              {
                  $sQuery = "ALTER TABLE $_Table ADD PRIMARY KEY ( WorkDate, Open, Film, Theather, Silmooja, UnitPrice )  " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect1) ;
              }

              $_Table = $DgrName ; $Cnt = MoveTable($_Table,$_connect2,$_connect1,$_db2,$_db1) ;

              if  ($Cnt != -1) /// 인덱스 추가..
              {
                  $sQuery = "ALTER TABLE $_Table ADD PRIMARY KEY ( Theather, Room, Degree, Silmooja, Open, Film )  " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect1) ;
              }

              $_Table = $DgrpName ; $Cnt = MoveTable($_Table,$_connect2,$_connect1,$_db2,$_db1) ;

              if  ($Cnt != -1) /// 인덱스 추가..
              {
                  $sQuery = "ALTER TABLE $_Table ADD PRIMARY KEY  ( Silmooja, WorkDate, Open, Film, Theather, Room, Degree ) " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect1) ;
              }

              $_Table = $FtName ; $Cnt = MoveTable($_Table,$_connect2,$_connect1,$_db2,$_db1) ;

              if  ($Cnt != -1) /// 인덱스 추가..
              {
                  $sQuery = "ALTER TABLE $_Table ADD PRIMARY KEY  ( Open, Code, Theather, Room, Type ) " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect1) ;
              }

              $_Table = $FtpName ; $Cnt = MoveTable($_Table,$_connect2,$_connect1,$_db2,$_db1) ;

              if  ($Cnt != -1) /// 인덱스 추가..
              {
                  $sQuery = "ALTER TABLE $_Table ADD PRIMARY KEY  ( WorkDate, Open, Code, Theather, Room, Type ) " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect1) ;
              }

              $_Table = $RoomOrder ; $Cnt = MoveTable($_Table,$_connect2,$_connect1,$_db2,$_db1) ;

              if  ($Cnt != -1) /// 인덱스 추가..
              {
                  $sQuery = "ALTER TABLE $_Table ADD PRIMARY KEY  ( SeqNo1, SeqNo2 ) " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect1) ;
                  $sQuery = "ALTER TABLE $_Table ADD INDEX Idx_RoomOrder ( Seq ) " ;  //echo $sQuery."<br>" ;
                  mysql_query($sQuery,$_connect1) ;
              }

              $_Table = "bas_silmoojatheather" ;     $Cnt = MoveTableRow($_Table,"Open","Film",$_Open,$_Code,$_connect2,$_connect1,$_db2,$_db1) ;
              $_Table = "bas_silmoojatheatherpriv" ; $Cnt = MoveTableRow($_Table,"Open","Film",$_Open,$_Code,$_connect2,$_connect1,$_db2,$_db1) ;
              $_Table = "bas_degree" ;     $Cnt = MoveTableRow($_Table,"Open","Film",$_Open,$_Code,$_connect1,$_connect2,$_db1,$_db2) ;
              $_Table = "bas_degreepriv" ; $Cnt = MoveTableRow($_Table,"Open","Film",$_Open,$_Code,$_connect1,$_connect2,$_db1,$_db2) ;
          }

          $Message = "복원완료!!";
          return $Message;
      }

      ////
      /// 종영/상영 토글
      //
      function ToggleOpenFilm($_Open,$_Code,$_connect)  ///////////////////////////////////////////////////////////////////////////
      {
          $sQuery = "Select Finish, Name
                       From bas_filmtitle
                      Where Open = '".$_Open."'
                        And Code = '".$_Code."'
                    " ;  ////echo $sQuery."<br>" ;
          $QryFinish = mysql_query($sQuery,$_connect) ;
          if ($ArrFinish = mysql_fetch_array($QryFinish))
          {
              $Finish = $ArrFinish["Finish"] ;
              $Name   = $ArrFinish["Name"] ;
          }

          if  ($Finish=="Y")
          {
              $sQuery = "Update bas_filmtitle
                            Set Finish = 'N'
                          Where Open = '".$_Open."'
                            And Code = '".$_Code."'
                    " ;  ////echo $sQuery."<br>" ;
              $Message = "$Name - 상영처리함" ;
          }
          if  ($Finish=="N")
          {
              $sQuery = "Update bas_filmtitle
                            Set Finish = 'Y'
                          Where Open = '".$_Open."'
                            And Code = '".$_Code."'
                    " ;  ////echo $sQuery."<br>" ;
              $Message = "$Name - 종영처리함" ;
          }

          mysql_query($sQuery,$_connect) ;

          return $Message;
      }

      ?>
      <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">


      <head>
      </head>

      <body>

            <br>
            <br>
            <br>
                    <?
                    ////
                    /// 필름 추가....
                    //
                    if  ($hidType=="AdditionFilmTitle")
                    {
                         $Message .= AddFilmTitle(1,$txtOpen,$txtCode1,$txtCode1,$txtName1,$txtExcelTitle,$connect1) ;
                         $Message .= AddFilmTitle(2,$txtOpen,$txtCode1,$txtCode2,$txtName2,$txtExcelTitle,$connect1) ;
                         $Message .= AddFilmTitle(3,$txtOpen,$txtCode1,$txtCode3,$txtName3,$txtExcelTitle,$connect1) ;
                         $Message .= AddFilmTitle(4,$txtOpen,$txtCode1,$txtCode4,$txtName4,$txtExcelTitle,$connect1) ;
                         $Message .= AddFilmTitle(5,$txtOpen,$txtCode1,$txtCode5,$txtName5,$txtExcelTitle,$connect1) ;
                    }
                    ///
                    //

                    ////
                    /// 백업시작
                    //
                    if  ($hidType=="Backup")
                    {
                        $Message = BackupFilm($hidOpen,$hidCode,$connect1,$connect2,$db1,$db2) ;
                    }
                    ///
                    //

                    ////
                    /// 복원시작
                    //
                    if  ($hidType=="Restore")
                    {
                        $Message = RestoreFilm($hidOpen,$hidCode,$connect1,$connect2,$db1,$db2) ;
                    }
                    ///
                    //

                    ////
                    /// 종영/상영 토글
                    //
                    if  ($hidType=="OpenClose")
                    {
                        $Message = ToggleOpenFilm($hidOpen,$hidCode,$connect1) ;
                    }
                    ///
                    //
                    ?>
            <br>
            <br>
      </body>

      <?
      mysql_close($connect1);
      mysql_close($connect2);
      ?>


      <Script Language="JavaScript">
      <!--
          <?
          if ( $Message != "")
          {
              ?>alert("<?=$Message?>");<?
          }
          else
          {
              ?>alert("처리");<?
          }
          ?>

          location.href = "<?=$hidBack?>" ;
      //-->
      </script>
</html>
