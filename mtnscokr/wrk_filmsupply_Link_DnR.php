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
				// Ȯ��� �̹����� ��Ÿ���� ���̾��� �����κ��Դϴ�.
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
   include "config.php";        // {[������ ���̽�]} : ȯ�漳��

    $connect = dbconn() ;        // {[������ ���̽�]} : ����

    mysql_select_db($cont_db,$connect) ;  // {[������ ���̽�]} : �����

   ////////////////////////////////
   $bEq       = 0 ;
   $bTmpQuery = 0 ;
   trace_init($connect) ;
   /////////////////////////////////////


   $FilmOpen = substr($FilmTile,0,6) ;
   $FilmCode = substr($FilmTile,6,2) ;

   $sSingoName = get_singotable($FilmOpen,$FilmCode,$connect) ;  // �Ű� ���̺� �̸�..
   $sAccName   = get_acctable($FilmOpen,$FilmCode,$connect) ;    // accumulate �̸�..
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
    <td class=textarea bgcolor=<?=$ColorA?> align=center width=50>����</td>
    <td class=textarea bgcolor=<?=$ColorA?> align=center width=200>�����</td>
    <td class=textarea bgcolor=<?=$ColorA?> align=center width=150>������</td>
    <td class=textarea bgcolor=<?=$ColorA?> align=center width=150>���</td>
    <td class=textarea bgcolor=<?=$ColorA?> align=center width=150>���ٵ�</td>
    <td class=textarea bgcolor=<?=$ColorA?> align=center width=150>����</td>
    <td class=textarea bgcolor=<?=$ColorA?> align=center width=150>��ƿ</td>
   </tr>
   <?


   if   ($ZoneCode=="9999") // "��ü"
   {
       $filmtitleNameTitle = "" ; // �ι��̻� �ݺ��Ǹ� ��ȭ���� ����� ���� ..


       //-----------
       // ���� ���
       //-----------
       $zoneName  = "����" ;
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
       // ������
       //-----------
       $zoneName  = "���" ;

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

       // ���
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
       // �λ� ���
       //-----------

       $zoneName  = "�λ�" ;
       $AddedLoc = "    ( Theather.Location = '200'   " . // �λ�
                    "  or Theather.Location = '203'   " . // �뿵
                    "  or Theather.Location = '600'   " . // ���
                    "  or Theather.Location = '207'   " . // ����
                    "  or Theather.Location = '205'   " . // ����
                    "  or Theather.Location = '208'   " . // ����
                    "  or Theather.Location = '202'   " . // ����
                    "  or Theather.Location = '211'   " . // ��õ
                    "  or Theather.Location = '212'   " . // ��â
                    "  or Theather.Location = '213'   " . // ���
                    "  or Theather.Location = '201' ) " ; // â��


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
       // �氭 ���
       //-----------
       $zoneName  = "�氭" ;

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

       if  ($AddedLoc != "") // �氭������ �ش��ϴ� �ڷᰡ �ִ°��..
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
       // ��û ���
       //-----------
       $zoneName  = "��û" ;

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

       if  ($AddedLoc != "") // ��û������ �ش��ϴ� �ڷᰡ �ִ°��..
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
       // �泲 ���
       //-----------
       $zoneName  = "�泲" ;

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

       if  ($AddedLoc != "") // �泲������ �ش��ϴ� �ڷᰡ �ִ°��..
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
       // ��� ���
       //-----------
       $zoneName  = "���" ;

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

       if  ($AddedLoc != "") // �氭������ �ش��ϴ� �ڷᰡ �ִ°��..
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
       // ȣ�� ���
       //-----------
       $zoneName  = "ȣ��" ;

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

       if  ($AddedLoc != "") // �氭������ �ش��ϴ� �ڷᰡ �ִ°��..
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
       // �������
       //-----------
       $zoneName  = "����" ;


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
       $AddedLoc .= " and Theather.Location <> '100' "  ; // ����
       $AddedLoc .= " and Theather.Location <> '200' "  ; // �λ�
       $AddedLoc .= " and Theather.Location <> '203' "  ; // �뿵
       $AddedLoc .= " and Theather.Location <> '600' "  ; // ���
       $AddedLoc .= " and Theather.Location <> '207' "  ; // ����
       $AddedLoc .= " and Theather.Location <> '205' "  ; // ����
       $AddedLoc .= " and Theather.Location <> '208' "  ; // ����
       $AddedLoc .= " and Theather.Location <> '202' "  ; // ����
       $AddedLoc .= " and Theather.Location <> '211' "  ; // ��õ
       $AddedLoc .= " and Theather.Location <> '212' "  ; // ��â
       $AddedLoc .= " and Theather.Location <> '213' "  ; // ���
       $AddedLoc .= " and Theather.Location <> '201' "  ; // â��
       $AddedLoc .= ")" ;

       // ��� + ���� + �λ� + ��� + â�� + ���� �� ������ �������� �������� �Ѵ�.

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

   //if   ($ZoneCode!="0000") // ��ü�� �ƴ� ��������..
   else
   {
       $AddedLoc = "" ; // �߰����� �˻�����

       // Ư�������� ���������� ������ �� ���
       if  (($LocationCode) && ($LocationCode!=""))
       {
           $sQuery = "Select * From bas_location        ".
                     " Where Code = '".$LocationCode."' " ; trace(__FILE__,__LINE__,$sQuery,$connect,$bEq,$bTmpQuery) ;
           $qryzone = mysql_query($sQuery,$connect) ;
           if  ($zone_data = mysql_fetch_array($qryzone))
           {
               $zoneName = $zone_data["Name"] ;
           }

           if  ($LocationCode=="200")//  �λ��� (�λ�+���+����+â��)
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

       // Ư�������� ���������� ������ �� ���
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
               if  ($ZoneCode == '20') // �泲�ΰ�� �λ��� �����Ѵ�.
               {
                    $AddedLoc .= " Or Theather.Location = '200' " ;

                    $AddedLoc .= " Or Theather.Location <> '203' ".  // �뿵
                                 " Or Theather.Location <> '600' ".  // ���
                                 " Or Theather.Location <> '207' ".  // ����
                                 " Or Theather.Location <> '205' ".  // ����
                                 " Or Theather.Location <> '208' ".  // ����
                                 " Or Theather.Location <> '202' ".  // ����
                                 " Or Theather.Location <> '211' ".  // ��õ
                                 " Or Theather.Location <> '212' ".  // ��â
                                 " Or Theather.Location <> '213' ".  // ���
                                 " Or Theather.Location <> '201' " ; // â��
               }
               $AddedLoc .= ")" ;
           }
           else
           {
               $AddedLoc = "" ;
           }
       }

       if  ($AddedLoc != "") // �ش��ϴ� �ڷᰡ �ִ°��..
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


   mysql_close($connect) ;  // {[������ ���̽�]} : ����
?>
</body>

</html>