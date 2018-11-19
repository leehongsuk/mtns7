<html>

<head>
<script language="JavaScript">
<!--
	var ie=document.all
	var ns=document.layers
	var ns6=document.getElementById&&!document.all

	function enlarge(which,e)
	{
		//Render image code for IE 4+ and NS6
		if (ie||ns6)
		{
			crossobj=document.getElementById? document.getElementById("showimage") : document.all.showimage

			if (crossobj.style.visibility=="hidden")
			{
				crossobj.style.left=ns6? pageXOffset+e.clientX : document.body.scrollLeft+event.clientX
				crossobj.style.top=ns6? pageYOffset+e.clientY : document.body.scrollTop+event.clientY-100

				crossobj.innerHTML='<div align=right id=drag></a></div><img src="'+which+'" width=400 height=240>'
				// 확대된 이미지가 나타나는 레이어의 수정부분입니다.
				crossobj.style.visibility="visible"
			}
			else
				crossobj.style.visibility="hidden"
			return false
		}
		//Render image code for NS 4
		else if (document.layers)
		{
			if (document.showimage.visibility=="hide")
			{
				document.showimage.document.write('<a href="#" onMouseover="drag_dropns(showimage)"><img src="'+which+'" border=0 width=400 height=240></a>')
				document.showimage.document.close()
				document.showimage.left=e.x
				document.showimage.top=e.y
				document.showimage.visibility="show"
			}
			else
   {
			 document.showimage.visibility="hide"
   }
			return false
		}
		//if NOT IE 4+ or NS 4, simply display image in full browser window
		else
			return true
	}

	function enlargeOut(e)
	{
		//Render image code for IE 4+ and NS6
		if (ie||ns6)
		{
			crossobj=document.getElementById? document.getElementById("showimage") : document.all.showimage

			crossobj.style.visibility="hidden"
			return false
		}
		//Render image code for NS 4
		else if (document.layers)
		{
			document.showimage.visibility="hide"
			return false
		}
		//if NOT IE 4+ or NS 4, simply display image in full browser window
		else
			return true
	}

//-->
</script>

<script language="JavaScript">
<!--

//By Dynamicdrive.com

//drag drop function for NS 4/////////////////////////////////////

var nsx,nsy,nstemp

function drag_dropns(name)
{
 temp=eval(name)
 temp.captureEvents(Event.MOUSEDOWN | Event.MOUSEUP)
 temp.onmousedown=gons
 temp.onmousemove=dragns
 temp.onmouseup=stopns
}

function gons(e)
{
 temp.captureEvents(Event.MOUSEMOVE)
 nsx=e.x
 nsy=e.y
}
function dragns(e)
{
 temp.moveBy(e.x-nsx,e.y-nsy)
 return false
}

function stopns()
{
 temp.releaseEvents(Event.MOUSEMOVE)
}

//drag drop function for IE 4+ and NS6/////////////////////////////////////

function drag_drop(e)
{
 if (ie&&dragapproved)
 {
  crossobj.style.left=tempx+event.clientX-offsetx
  crossobj.style.top=tempy+event.clientY-offsety
 }
 else if (ns6&&dragapproved)
 {
  crossobj.style.left=tempx+e.clientX-offsetx
  crossobj.style.top=tempy+e.clientY-offsety
 }
 return false
}

function initializedrag(e)
{
 if (ie&&event.srcElement.id=="drag"||ns6&&e.target.id=="drag")
 {
  offsetx=ie? event.clientX : e.clientX
  offsety=ie? event.clientY : e.clientY

  tempx=parseInt(crossobj.style.left)
  tempy=parseInt(crossobj.style.top)

  dragapproved=true
  document.onmousemove=drag_drop
 }
}

document.onmousedown=initializedrag
document.onmouseup=new Function("dragapproved=false")

//-->
</script>

</head>

<body>
<?
   include "config.php";        // {[데이터 베이스]} : 환경설정

    $connect = dbconn() ;        // {[데이터 베이스]} : 연결

    mysql_select_db($cont_db,$connect) ;  // {[데이터 베이스]} : 디비선택

   ////////////////////////////////
   $bEq       = 0 ;
   $bTmpQuery = 0 ;
   trace_init($connect) ;
   /////////////////////////////////////


   $FilmOpen = substr($FilmTile,0,6) ;
   $FilmCode = substr($FilmTile,6,2) ;

   $sSingoName = get_singotable($FilmOpen,$FilmCode,$connect) ;  // 신고 테이블 이름..
   $sAccName   = get_acctable($FilmOpen,$FilmCode,$connect) ;    // accumulate 이름..
   $sDgrName   = get_degree($FilmOpen,$FilmCode,$connect) ;
   $sDgrpName  = get_degreepriv($FilmOpen,$FilmCode,$connect) ;
   $sShowroomorder = get_showroomorder($FilmOpen,$FilmCode,$connect) ;


   $ColorA =  '#ffebcd' ;
   $ColorB =  '#dcdcec' ;
   $ColorC =  '#dcdcdc' ;
   $ColorD =  '#c0c0c0' ;


   ?>
   <div id="showimage" style="position:absolute;visibility:hidden;border:1px solid black"></div>

   <table cellpadding=0 cellspacing=0 border=1 bordercolor='#C0B0A0'>
   <tr>
    <td class=textarea bgcolor=<?=$ColorA?> align=center width=50>지역</td>
    <td class=textarea bgcolor=<?=$ColorA?> align=center width=200>극장명</td>
    <td class=textarea bgcolor=<?=$ColorA?> align=center width=150>포스터</td>
    <td class=textarea bgcolor=<?=$ColorA?> align=center width=150>배너</td>
    <td class=textarea bgcolor=<?=$ColorA?> align=center width=150>스텐디</td>
    <td class=textarea bgcolor=<?=$ColorA?> align=center width=150>전단</td>
    <td class=textarea bgcolor=<?=$ColorA?> align=center width=150>스틸</td>
   </tr>
   <?


   if   ($ZoneCode=="9999") // "전체"
   {
       $filmtitleNameTitle = "" ; // 두번이상 반복되면 영화명을 지우기 위해 ..


       //-----------
       // 서울 출력
       //-----------
       $zoneName  = "서울" ;
       $AddedLoc = " Theather.Location = '100' " ;

       $sQuery = "Select distinct Theather.Code As Code,           ".
                 "       Theather.Discript As Discript,            ".
                 "       Theather.Location As Location,            ".
                 "       Location.Name As LocationName             ".
                 "  From bas_theather AS Theather                  ".
                 "  Left Join bas_location AS Location             ".
                 "    on Location.Code = Theather.location         ".
                 "  Left Join ".$sShowroomorder." AS Showroomorder ".
                 "    on Showroomorder.Theather  = Theather.Code   ".
                 " Where ".$AddedLoc."                             ".
                 " Order By Showroomorder.Seq,                     ".
                 "          Theather.Discript,                     ".
                 "          Theather.Code                          " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
       $QryTheather = mysql_query($sQuery,$connect) ;

       include "wrk_filmsupply_Link_DnR1.php";


       //-----------
       // 경기출력
       //-----------
       $zoneName  = "경기" ;

       $AddedLoc = "" ;

       $sQuery = "Select Location from bas_filmsupplyzoneloc  ".
                 " Where Zone = '04'                          " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
       $qryzoneloc = mysql_query($sQuery,$connect) ;
       while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
       {
            if  ($AddedLoc == "")
                $AddedLoc .= "( Theather.Location = '".$zoneloc_data["Location"]."' "  ;
            else
                $AddedLoc .= " or Theather.Location = '".$zoneloc_data["Location"]."' "  ;
       }
       $AddedLoc .= ")" ;

       // 경기
       $sQuery = "Select distinct Theather.Code As Code,           ".
                 "       Theather.Discript As Discript,            ".
                 "       Theather.Location As Location,            ".
                 "       Location.Name As LocationName             ".
                 "  From bas_theather AS Theather                  ".
                 "  Left Join bas_location AS Location             ".
                 "    on Location.Code = Theather.location         ".
                 "  Left Join ".$sShowroomorder." AS Showroomorder ".
                 "    on Showroomorder.Theather  = Theather.Code   ".
                 " Where ".$AddedLoc."                             ".
                 " Order By Showroomorder.Seq                      " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
       $QryTheather = mysql_query($sQuery,$connect) ;

       include "wrk_filmsupply_Link_DnR1.php";





       //-----------
       // 부산 출력
       //-----------

       $zoneName  = "부산" ;
       $AddedLoc = "    ( Theather.Location = '200'   " . // 부산
                    "  or Theather.Location = '203'   " . // 통영
                    "  or Theather.Location = '600'   " . // 울산
                    "  or Theather.Location = '207'   " . // 김해
                    "  or Theather.Location = '205'   " . // 진주
                    "  or Theather.Location = '208'   " . // 거제
                    "  or Theather.Location = '202'   " . // 마산
                    "  or Theather.Location = '211'   " . // 사천
                    "  or Theather.Location = '212'   " . // 거창
                    "  or Theather.Location = '213'   " . // 양산
                    "  or Theather.Location = '201' ) " ; // 창원


       $sQuery = "Select distinct Theather.Code As Code,           ".
                 "       Theather.Discript As Discript,            ".
                 "       Theather.Location As Location,            ".
                 "       Location.Name As LocationName             ".
                 "  From bas_theather AS Theather                  ".
                 "  Left Join bas_location AS Location             ".
                 "    on Location.Code = Theather.location         ".
                 "  Left Join ".$sShowroomorder." AS Showroomorder ".
                 "    on Showroomorder.Theather  = Theather.Code   ".
                 " Where ".$AddedLoc."                             ".
                 " Order By Showroomorder.Seq                      " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
       $QryTheather = mysql_query($sQuery,$connect) ;

       include "wrk_filmsupply_Link_DnR1.php";




       //-----------
       // 경강 출력
       //-----------
       $zoneName  = "경강" ;

       $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                 " Where Zone  = '10'                   " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
       $query1 = mysql_query($sQuery,$connect) ;

       $AddedLoc = "" ;
       while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
       {
           if  ($AddedLoc == "")
           {
               $AddedLoc .= "( Theather.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
           else
           {
               $AddedLoc .= " Or Theather.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
       }

       if  ($AddedLoc != "")
       {
           $AddedLoc .= ")" ;
       }
       else
       {
           $AddedLoc = "" ;
       }

       if  ($AddedLoc != "") // 경강지역에 해당하는 자료가 있는경우..
       {

           $sQuery = "Select distinct Theather.Code As Code,           ".
                     "       Theather.Discript As Discript,            ".
                     "       Theather.Location As Location,            ".
                     "       Location.Name As LocationName             ".
                     "  From bas_theather AS Theather                  ".
                     "  Left Join bas_location AS Location             ".
                     "    on Location.Code = Theather.location         ".
                     "  Left Join ".$sShowroomorder." AS Showroomorder ".
                     "    on Showroomorder.Theather  = Theather.Code   ".
                     " Where ".$AddedLoc."                             ".
                     " Order By Showroomorder.Seq                      " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
           $QryTheather = mysql_query($sQuery,$connect) ;

           include "wrk_filmsupply_Link_DnR1.php";
       }

       //-----------
       // 충청 출력
       //-----------
       $zoneName  = "충청" ;

       $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                 " Where Zone  = '35'                   " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
       $query1 = mysql_query($sQuery,$connect) ;

       $AddedLoc = "" ;
       while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
       {
           if  ($AddedLoc == "")
           {
               $AddedLoc .= "( Theather.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
           else
           {
               $AddedLoc .= " Or Theather.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
       }

       if  ($AddedLoc != "")
       {
           $AddedLoc .= ")" ;
       }
       else
       {
           $AddedLoc = "" ;
       }

       if  ($AddedLoc != "") // 충청지역에 해당하는 자료가 있는경우..
       {
           $sQuery = "Select distinct Theather.Code As Code,           ".
                     "       Theather.Discript As Discript,            ".
                     "       Theather.Location As Location,            ".
                     "       Location.Name As LocationName             ".
                     "  From bas_theather AS Theather                  ".
                     "  Left Join bas_location AS Location             ".
                     "    on Location.Code = Theather.location         ".
                     "  Left Join ".$sShowroomorder." AS Showroomorder ".
                     "    on Showroomorder.Theather  = Theather.Code   ".
                     " Where ".$AddedLoc."                             ".
                     " Order By Showroomorder.Seq                      " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
           $QryTheather = mysql_query($sQuery,$connect) ;


           include "wrk_filmsupply_Link_DnR1.php";
       }
       //-----------
       // 경남 출력
       //-----------
       $zoneName  = "경남" ;

       $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                 " Where Zone  = '20'                   " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
       $query1 = mysql_query($sQuery,$connect) ;

       $AddedLoc = "" ;
       while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
       {
           if  ($AddedLoc == "")
           {
               $AddedLoc .= "( Theather.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
           else
           {
               $AddedLoc .= " Or Theather.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
       }

       if  ($AddedLoc != "")
       {
           $AddedLoc .= " Or Theather.Location <> '600' " ;
           $AddedLoc .= " Or Theather.Location <> '201' " ;
           $AddedLoc .= " Or Theather.Location <> '207' " ;
           $AddedLoc .= ")" ;
       }
       else
       {
           $AddedLoc = "" ;
       }

       if  ($AddedLoc != "") // 경남지역에 해당하는 자료가 있는경우..
       {
           $sQuery = "Select distinct Theather.Code As Code,           ".
                     "       Theather.Discript As Discript,            ".
                     "       Theather.Location As Location,            ".
                     "       Location.Name As LocationName             ".
                     "  From bas_theather AS Theather                  ".
                     "  Left Join bas_location AS Location             ".
                     "    on Location.Code = Theather.location         ".
                     "  Left Join ".$sShowroomorder." AS Showroomorder ".
                     "    on Showroomorder.Theather  = Theather.Code   ".
                     " Where ".$AddedLoc."                             ".
                     " Order By Showroomorder.Seq                      " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
           $QryTheather = mysql_query($sQuery,$connect) ;

           include "wrk_filmsupply_Link_DnR1.php";
       }
       //-----------
       // 경북 출력
       //-----------
       $zoneName  = "경북" ;

       $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                 " Where Zone  = '21'                   " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
       $query1 = mysql_query($sQuery,$connect) ;

       $AddedLoc = "" ;
       while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
       {
           if  ($AddedLoc == "")
           {
               $AddedLoc .= "( Theather.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
           else
           {
               $AddedLoc .= " Or Theather.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
       }

       if  ($AddedLoc != "")
       {
           $AddedLoc .= ")" ;
       }
       else
       {
           $AddedLoc = "" ;
       }

       if  ($AddedLoc != "") // 경강지역에 해당하는 자료가 있는경우..
       {
           $sQuery = "Select distinct Theather.Code As Code,           ".
                     "       Theather.Discript As Discript,            ".
                     "       Theather.Location As Location,            ".
                     "       Location.Name As LocationName             ".
                     "  From bas_theather AS Theather                  ".
                     "  Left Join bas_location AS Location             ".
                     "    on Location.Code = Theather.location         ".
                     "  Left Join ".$sShowroomorder." AS Showroomorder ".
                     "    on Showroomorder.Theather  = Theather.Code   ".
                     " Where ".$AddedLoc."                             ".
                     " Order By Showroomorder.Seq                      " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
           $QryTheather = mysql_query($sQuery,$connect) ;

           include "wrk_filmsupply_Link_DnR1.php";
       }
       //-----------
       // 호남 출력
       //-----------
       $zoneName  = "호남" ;

       $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                 " Where Zone  = '50'                   " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
       $query1 = mysql_query($sQuery,$connect) ;

       $AddedLoc = "" ;
       while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
       {
           if  ($AddedLoc == "")
           {
               $AddedLoc .= "( Theather.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
           else
           {
               $AddedLoc .= " Or Theather.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
           }
       }

       if  ($AddedLoc != "")
       {
           $AddedLoc .= ")" ;
       }
       else
       {
           $AddedLoc = "" ;
       }

       if  ($AddedLoc != "") // 경강지역에 해당하는 자료가 있는경우..
       {
           $sQuery = "Select distinct Theather.Code As Code,           ".
                     "       Theather.Discript As Discript,            ".
                     "       Theather.Location As Location,            ".
                     "       Location.Name As LocationName             ".
                     "  From bas_theather AS Theather                  ".
                     "  Left Join bas_location AS Location             ".
                     "    on Location.Code = Theather.location         ".
                     "  Left Join ".$sShowroomorder." AS Showroomorder ".
                     "    on Showroomorder.Theather  = Theather.Code   ".
                     " Where ".$AddedLoc."                             ".
                     " Order By Showroomorder.Seq                      " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
           $QryTheather = mysql_query($sQuery,$connect) ;

           include "wrk_filmsupply_Link_DnR1.php";
       }



       //-----------
       // 지방출력
       //-----------
       $zoneName  = "지방" ;


       $AddedLoc = "" ;

       $sQuery = "select Location from bas_filmsupplyzoneloc ".
                 " Where Zone = '04'                         " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
       $qryzoneloc = mysql_query($sQuery,$connect) ;
       while ($zoneloc_data = mysql_fetch_array($qryzoneloc))
       {
            if  ($AddedLoc == "")
                $AddedLoc .= "( Theather.Location <> '".$zoneloc_data["Location"]."' "  ;
            else
                $AddedLoc .= " and Theather.Location <> '".$zoneloc_data["Location"]."' "  ;
       }
       $AddedLoc .= " and Theather.Location <> '100' "  ; // 서울
       $AddedLoc .= " and Theather.Location <> '200' "  ; // 부산
       $AddedLoc .= " and Theather.Location <> '203' "  ; // 통영
       $AddedLoc .= " and Theather.Location <> '600' "  ; // 울산
       $AddedLoc .= " and Theather.Location <> '207' "  ; // 김해
       $AddedLoc .= " and Theather.Location <> '205' "  ; // 진주
       $AddedLoc .= " and Theather.Location <> '208' "  ; // 거제
       $AddedLoc .= " and Theather.Location <> '202' "  ; // 마산
       $AddedLoc .= " and Theather.Location <> '211' "  ; // 사천
       $AddedLoc .= " and Theather.Location <> '212' "  ; // 거창
       $AddedLoc .= " and Theather.Location <> '213' "  ; // 양산
       $AddedLoc .= " and Theather.Location <> '201' "  ; // 창원
       $AddedLoc .= ")" ;

       // 경기 + 서울 + 부산 + 울산 + 창원 + 김해 를 제외한 나머지를 지방으로 한다.

       $sQuery = "Select distinct Theather.Code As Code,           ".
                 "       Theather.Discript As Discript,            ".
                 "       Theather.Location As Location,            ".
                 "       Location.Name As LocationName             ".
                 "  From bas_theather AS Theather                  ".
                 "  Left Join bas_location AS Location             ".
                 "    on Location.Code = Theather.location         ".
                 "  Left Join ".$sShowroomorder." AS Showroomorder ".
                 "    on Showroomorder.Theather  = Theather.Code   ".
                 " Where ".$AddedLoc."                             ".
                 " Order By Showroomorder.Seq                      " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
       $QryTheather = mysql_query($sQuery,$connect) ;

       include "wrk_filmsupply_Link_DnR1.php";
   }

   //if   ($ZoneCode!="0000") // 전체가 아닌 지역별로..
   else
   {
       $AddedLoc = "" ; // 추가적인 검색조건

       // 특정지역만 선택적으로 보고자 할 경우
       if  (($LocationCode) && ($LocationCode!=""))
       {
           $sQuery = "Select * From bas_location        ".
                     " Where Code = '".$LocationCode."' " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
           $qryzone = mysql_query($sQuery,$connect) ;
           if  ($zone_data = mysql_fetch_array($qryzone))
           {
               $zoneName = $zone_data["Name"] ;
           }

           if  ($LocationCode=="200")//  부산은 (부산+울산+김해+창원)
           {
               $AddedLoc = "  ( Theather.Location = '200'  ".
                           " Or Theather.Location = '203'  ".
                           " Or Theather.Location = '600'  ".
                           " Or Theather.Location = '207'  ".
                           " Or Theather.Location = '205'  ".
                           " Or Theather.Location = '208'  ".
                           " Or Theather.Location = '202'  ".
                           " Or Theather.Location = '211'  ".
                           " Or Theather.Location = '212'  ".
                           " Or Theather.Location = '213'  ".
                           " Or Theather.Location = '201') " ;
           }
           else
           {
               $AddedLoc = " Theather.Location = '".$LocationCode."'  ";
           }
       }

       // 특정구역만 선택적으로 보고자 할 경우
       if  (($ZoneCode) && ($ZoneCode!=""))
       {
           $sQuery = "Select * From bas_zone          ".
                     " Where Code = '".$ZoneCode."'   " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
           $qryzone = mysql_query($sQuery,$connect) ;
           if  ($zone_data = mysql_fetch_array($qryzone))
           {
               $zoneName = $zone_data["Name"] ;
           }

           $sQuery = "Select * From bas_filmsupplyzoneloc   ".
                     " Where Zone  = '".$ZoneCode."'        " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
           $query1 = mysql_query($sQuery,$connect) ;

           $AddedLoc = "" ;
           while ($filmsupplyzoneloc_data = mysql_fetch_array($query1))
           {
               if  ($AddedLoc == "")
               {
                   $AddedLoc .= "( Theather.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
               else
               {
                   $AddedLoc .= " Or Theather.Location = '".$filmsupplyzoneloc_data["Location"]."' " ;
               }
           }

           if  ($AddedLoc != " And ")
           {
               if  ($ZoneCode == '20') // 경남인경우 부산을 포함한다.
               {
                    $AddedLoc .= " Or Theather.Location = '200' " ;

                    $AddedLoc .= " Or Theather.Location <> '203' ".  // 통영
                                 " Or Theather.Location <> '600' ".  // 울산
                                 " Or Theather.Location <> '207' ".  // 김해
                                 " Or Theather.Location <> '205' ".  // 김주
                                 " Or Theather.Location <> '208' ".  // 거제
                                 " Or Theather.Location <> '202' ".  // 마산
                                 " Or Theather.Location <> '211' ".  // 사천
                                 " Or Theather.Location <> '212' ".  // 거창
                                 " Or Theather.Location <> '213' ".  // 양산
                                 " Or Theather.Location <> '201' " ; // 창원
               }
               $AddedLoc .= ")" ;
           }
           else
           {
               $AddedLoc = "" ;
           }
       }

       if  ($AddedLoc != "") // 해당하는 자료가 있는경우..
       {
           $sQuery = "Select distinct Theather.Code As Code,           ".
                     "       Theather.Discript As Discript,            ".
                     "       Theather.Location As Location,            ".
                     "       Location.Name As LocationName             ".
                     "  From bas_theather AS Theather                  ".
                     "  Left Join bas_location AS Location             ".
                     "    on Location.Code = Theather.location         ".
                     "  Left Join ".$sShowroomorder." AS Showroomorder ".
                     "    on Showroomorder.Theather  = Theather.Code   ".
                     " Where ".$AddedLoc."                             ".
                     " Order By Showroomorder.Seq                      " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
           $QryTheather = mysql_query($sQuery,$connect) ;

           include "wrk_filmsupply_Link_DnR1.php";
       }
   }
   ?>
   </table>
   <?


   mysql_close($connect) ;  // {[데이터 베이스]} : 단절
?>
</body>

</html>