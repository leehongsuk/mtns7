<?
    session_start();

    if ($ToExel == "True")
    {
        header("Content-type: application/vnd.ms-excel"); 
        header("Content-Disposition: attachment; filename=excel_name.xls"); 
        header("Content-Description: GamZa Excel Data"); 

        $NBSP="" ;
    }
    else
    {
        $NBSP="&nbsp;" ;
    }
?>
<html>
<?
    if  (session_is_registered("logged_UserId"))
    {
        include "config.php";

        $Today = time()-(3600*7) ; // ���� 7�� ���� ���÷� �����Ѵ�...  

        if  (!$WorkDate)
        {
            $WorkDate = date("Ymd",$Today) ;
        }

        if  (!$CurrDate)  {  $CurrDate = $WorkDate  ; }
        if  (!$FromDate)  {  $FromDate = $WorkDate  ; }
        if  (!$ToDate)    {  $ToDate   = $WorkDate  ; }
        if  (!$PrnKind)   {  $PrnKind  = "Date"  ; }


        $connect=dbconn();

        mysql_select_db($cont_db) ;



        if  ($PrnKind == "Term") // �Ⱓ�� 
        {
            $timestamp2 = mktime(0,0,0,substr($FromDate,4,2),substr($FromDate,6,2),substr($FromDate,0,4));
            $dur_time2  = (time() - $timestamp2) / 86400;      

            $timestamp1 = mktime(0,0,0,substr($ToDate,4,2),substr($ToDate,6,2),substr($ToDate,0,4));
            $dur_time1  = (time() - $timestamp1) / 86400;       

            $dur_day    = $dur_time2 - $dur_time1;  // �ϼ�
        }
        if  ($PrnKind == "Date") // ���ں�
        {
            $timestamp2 = mktime(0,0,0,substr($CurrDate,4,2),substr($CurrDate,6,2),substr($CurrDate,0,4));
            $dur_time2  = (time() - $timestamp2) / 86400;      

            $timestamp1 = mktime(0,0,0,substr($CurrDate,4,2),substr($CurrDate,6,2),substr($CurrDate,0,4));
            $dur_time1  = (time() - $timestamp1) / 86400;       

            $dur_day    = $dur_time2 - $dur_time1;  // �ϼ�
        }

?>

<link rel=stylesheet href=./LinkStyle.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
<title>��뿵ȭ ��Ȳ</title>

    <script src="AC_OETags.js" language="javascript"></script>

    <script language="JavaScript" type="text/javascript">
    <!--
        var requiredMajorVersion = 9 ; // Major version of Flash required
        var requiredMinorVersion = 0 ;
        var requiredRevision     = 0 ;
    //-->
    </script>

    <!-- FABridge.js ��Ŭ��� -->
    <script src="bridge/FABridge.js"></script>
</head>

<body bgcolor=#fafafa  topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
  
<script>
         //---------------------------------------------------------------
         //
         // ������ 2�ڸ����ڷ� ���鶧..  0����ä����
         //
         function fn(m) 
         {
            z = '00' ;

            return z.substr(0,z.length-String(m).length) + m ;
         }


         function select_PrnKind(item)
         {
               if  (item.value=="Date")
               {
                   strScript   = "<select name=CurrYear>" ;
                   strScript  += "  <option value=2004 <?if (substr($CurrDate,0,4)=='2004') echo "selected";?>>2004</option>" ;
                   strScript  += "  <option value=2005 <?if (substr($CurrDate,0,4)=='2005') echo "selected";?>>2005</option>" ;
                   strScript  += "  <option value=2006 <?if (substr($CurrDate,0,4)=='2006') echo "selected";?>>2006</option>" ;
                   strScript  += "  <option value=2007 <?if (substr($CurrDate,0,4)=='2007') echo "selected";?>>2007</option>" ;
                   strScript  += "  <option value=2008 <?if (substr($CurrDate,0,4)=='2008') echo "selected";?>>2008</option>" ;
                   strScript  += "  <option value=2009 <?if (substr($CurrDate,0,4)=='2009') echo "selected";?>>2009</option>" ;
                   strScript  += "  <option value=2010 <?if (substr($CurrDate,0,4)=='2010') echo "selected";?>>2010</option>" ;
                   strScript  += "  <option value=2011 <?if (substr($CurrDate,0,4)=='2011') echo "selected";?>>2011</option>" ;
                   strScript  += "  <option value=2012 <?if (substr($CurrDate,0,4)=='2012') echo "selected";?>>2012</option>" ;
                   strScript  += "  <option value=2013 <?if (substr($CurrDate,0,4)=='2013') echo "selected";?>>2013</option>" ;
                   strScript  += "</select> ��" ;

                   strScript  += "<select name=CurrMonth>" ;
                   strScript  += "   <option value=1 <?if (substr($CurrDate,4,2)=='01') echo "selected";?>><?=$NBSP?>1</option>" ;
                   strScript  += "   <option value=2 <?if (substr($CurrDate,4,2)=='02') echo "selected";?>><?=$NBSP?>2</option>" ;
                   strScript  += "   <option value=3 <?if (substr($CurrDate,4,2)=='03') echo "selected";?>><?=$NBSP?>3</option>" ;
                   strScript  += "   <option value=4 <?if (substr($CurrDate,4,2)=='04') echo "selected";?>><?=$NBSP?>4</option>" ;
                   strScript  += "   <option value=5 <?if (substr($CurrDate,4,2)=='05') echo "selected";?>><?=$NBSP?>5</option>" ;
                   strScript  += "   <option value=6 <?if (substr($CurrDate,4,2)=='06') echo "selected";?>><?=$NBSP?>6</option>" ;
                   strScript  += "   <option value=7 <?if (substr($CurrDate,4,2)=='07') echo "selected";?>><?=$NBSP?>7</option>" ;
                   strScript  += "   <option value=8 <?if (substr($CurrDate,4,2)=='08') echo "selected";?>><?=$NBSP?>8</option>" ;
                   strScript  += "   <option value=9 <?if (substr($CurrDate,4,2)=='09') echo "selected";?>><?=$NBSP?>9</option>" ;
                   strScript  += "   <option value=10 <?if (substr($CurrDate,4,2)=='10') echo "selected";?>><?=$NBSP?>10</option>" ;
                   strScript  += "   <option value=11 <?if (substr($CurrDate,4,2)=='11') echo "selected";?>><?=$NBSP?>11</option>" ;
                   strScript  += "   <option value=12 <?if (substr($CurrDate,4,2)=='12') echo "selected";?>><?=$NBSP?>12</option>" ;
                   strScript  += "</select> ��" ;
                   
                   strScript  += "<select name=CurrDay>" ;
                   strScript  += "   <option value=1 <?if (substr($CurrDate,6,2)=='01') echo "selected";?>><?=$NBSP?>1</option>" ;
                   strScript  += "   <option value=2 <?if (substr($CurrDate,6,2)=='02') echo "selected";?>><?=$NBSP?>2</option>" ;
                   strScript  += "   <option value=3 <?if (substr($CurrDate,6,2)=='03') echo "selected";?>><?=$NBSP?>3</option>" ;
                   strScript  += "   <option value=4 <?if (substr($CurrDate,6,2)=='04') echo "selected";?>><?=$NBSP?>4</option>" ;
                   strScript  += "   <option value=5 <?if (substr($CurrDate,6,2)=='05') echo "selected";?>><?=$NBSP?>5</option>" ;
                   strScript  += "   <option value=6 <?if (substr($CurrDate,6,2)=='06') echo "selected";?>><?=$NBSP?>6</option>" ;
                   strScript  += "   <option value=7 <?if (substr($CurrDate,6,2)=='07') echo "selected";?>><?=$NBSP?>7</option>" ;
                   strScript  += "   <option value=8 <?if (substr($CurrDate,6,2)=='08') echo "selected";?>><?=$NBSP?>8</option>" ;
                   strScript  += "   <option value=9 <?if (substr($CurrDate,6,2)=='09') echo "selected";?>><?=$NBSP?>9</option>" ;
                   strScript  += "   <option value=10 <?if (substr($CurrDate,6,2)=='10') echo "selected";?>>10</option>" ;
                   strScript  += "   <option value=11 <?if (substr($CurrDate,6,2)=='11') echo "selected";?>>11</option>" ;
                   strScript  += "   <option value=12 <?if (substr($CurrDate,6,2)=='12') echo "selected";?>>12</option>" ;
                   strScript  += "   <option value=13 <?if (substr($CurrDate,6,2)=='13') echo "selected";?>>13</option>" ;
                   strScript  += "   <option value=14 <?if (substr($CurrDate,6,2)=='14') echo "selected";?>>14</option>" ;
                   strScript  += "   <option value=15 <?if (substr($CurrDate,6,2)=='15') echo "selected";?>>15</option>" ;
                   strScript  += "   <option value=16 <?if (substr($CurrDate,6,2)=='16') echo "selected";?>>16</option>" ;
                   strScript  += "   <option value=17 <?if (substr($CurrDate,6,2)=='17') echo "selected";?>>17</option>" ;
                   strScript  += "   <option value=18 <?if (substr($CurrDate,6,2)=='18') echo "selected";?>>18</option>" ;
                   strScript  += "   <option value=19 <?if (substr($CurrDate,6,2)=='19') echo "selected";?>>19</option>" ;
                   strScript  += "   <option value=20 <?if (substr($CurrDate,6,2)=='20') echo "selected";?>>20</option>" ;
                   strScript  += "   <option value=21 <?if (substr($CurrDate,6,2)=='21') echo "selected";?>>21</option>" ;
                   strScript  += "   <option value=22 <?if (substr($CurrDate,6,2)=='22') echo "selected";?>>22</option>" ;
                   strScript  += "   <option value=23 <?if (substr($CurrDate,6,2)=='23') echo "selected";?>>23</option>" ;
                   strScript  += "   <option value=24 <?if (substr($CurrDate,6,2)=='24') echo "selected";?>>24</option>" ;
                   strScript  += "   <option value=25 <?if (substr($CurrDate,6,2)=='25') echo "selected";?>>25</option>" ;
                   strScript  += "   <option value=26 <?if (substr($CurrDate,6,2)=='26') echo "selected";?>>26</option>" ;
                   strScript  += "   <option value=27 <?if (substr($CurrDate,6,2)=='27') echo "selected";?>>27</option>" ;
                   strScript  += "   <option value=28 <?if (substr($CurrDate,6,2)=='28') echo "selected";?>>28</option>" ;
                   strScript  += "   <option value=29 <?if (substr($CurrDate,6,2)=='29') echo "selected";?>>29</option>" ;
                   strScript  += "   <option value=30 <?if (substr($CurrDate,6,2)=='30') echo "selected";?>>30</option>" ;
                   strScript  += "   <option value=31 <?if (substr($CurrDate,6,2)=='31') echo "selected";?>>31</option>" ;
                   strScript  += "</select> ��" ;
               }
               if  (item.value=="Term")
               {
                   strScript   = "<select name=FromYear>" ;
                   strScript  += "  <option value=2004 <?if (substr($FromDate,0,4)=='2004') echo "selected";?>>2004</option>" ;
                   strScript  += "  <option value=2005 <?if (substr($FromDate,0,4)=='2005') echo "selected";?>>2005</option>" ;
                   strScript  += "  <option value=2006 <?if (substr($FromDate,0,4)=='2006') echo "selected";?>>2006</option>" ;
                   strScript  += "  <option value=2007 <?if (substr($FromDate,0,4)=='2007') echo "selected";?>>2007</option>" ;
                   strScript  += "  <option value=2008 <?if (substr($FromDate,0,4)=='2008') echo "selected";?>>2008</option>" ;
                   strScript  += "  <option value=2009 <?if (substr($FromDate,0,4)=='2009') echo "selected";?>>2009</option>" ;
                   strScript  += "  <option value=2010 <?if (substr($FromDate,0,4)=='2010') echo "selected";?>>2010</option>" ;
                   strScript  += "  <option value=2011 <?if (substr($FromDate,0,4)=='2011') echo "selected";?>>2011</option>" ;
                   strScript  += "  <option value=2012 <?if (substr($FromDate,0,4)=='2012') echo "selected";?>>2012</option>" ;
                   strScript  += "  <option value=2013 <?if (substr($FromDate,0,4)=='2013') echo "selected";?>>2013</option>" ;
                   strScript  += "</select> ��" ;

                   strScript  += "<select name=FromMonth>" ;
                   strScript  += "   <option value=1 <?if (substr($FromDate,4,2)=='01') echo "selected";?>><?=$NBSP?>1</option>" ;
                   strScript  += "   <option value=2 <?if (substr($FromDate,4,2)=='02') echo "selected";?>><?=$NBSP?>2</option>" ;
                   strScript  += "   <option value=3 <?if (substr($FromDate,4,2)=='03') echo "selected";?>><?=$NBSP?>3</option>" ;
                   strScript  += "   <option value=4 <?if (substr($FromDate,4,2)=='04') echo "selected";?>><?=$NBSP?>4</option>" ;
                   strScript  += "   <option value=5 <?if (substr($FromDate,4,2)=='05') echo "selected";?>><?=$NBSP?>5</option>" ;
                   strScript  += "   <option value=6 <?if (substr($FromDate,4,2)=='06') echo "selected";?>><?=$NBSP?>6</option>" ;
                   strScript  += "   <option value=7 <?if (substr($FromDate,4,2)=='07') echo "selected";?>><?=$NBSP?>7</option>" ;
                   strScript  += "   <option value=8 <?if (substr($FromDate,4,2)=='08') echo "selected";?>><?=$NBSP?>8</option>" ;
                   strScript  += "   <option value=9 <?if (substr($FromDate,4,2)=='09') echo "selected";?>><?=$NBSP?>9</option>" ;
                   strScript  += "   <option value=10 <?if (substr($FromDate,4,2)=='10') echo "selected";?>><?=$NBSP?>10</option>" ;
                   strScript  += "   <option value=11 <?if (substr($FromDate,4,2)=='11') echo "selected";?>><?=$NBSP?>11</option>" ;
                   strScript  += "   <option value=12 <?if (substr($FromDate,4,2)=='12') echo "selected";?>><?=$NBSP?>12</option>" ;
                   strScript  += "</select> ��" ;
                   
                   strScript  += "<select name=FromDay>" ;
                   strScript  += "   <option value=1 <?if (substr($FromDate,6,2)=='01') echo "selected";?>><?=$NBSP?>1</option>" ;
                   strScript  += "   <option value=2 <?if (substr($FromDate,6,2)=='02') echo "selected";?>><?=$NBSP?>2</option>" ;
                   strScript  += "   <option value=3 <?if (substr($FromDate,6,2)=='03') echo "selected";?>><?=$NBSP?>3</option>" ;
                   strScript  += "   <option value=4 <?if (substr($FromDate,6,2)=='04') echo "selected";?>><?=$NBSP?>4</option>" ;
                   strScript  += "   <option value=5 <?if (substr($FromDate,6,2)=='05') echo "selected";?>><?=$NBSP?>5</option>" ;
                   strScript  += "   <option value=6 <?if (substr($FromDate,6,2)=='06') echo "selected";?>><?=$NBSP?>6</option>" ;
                   strScript  += "   <option value=7 <?if (substr($FromDate,6,2)=='07') echo "selected";?>><?=$NBSP?>7</option>" ;
                   strScript  += "   <option value=8 <?if (substr($FromDate,6,2)=='08') echo "selected";?>><?=$NBSP?>8</option>" ;
                   strScript  += "   <option value=9 <?if (substr($FromDate,6,2)=='09') echo "selected";?>><?=$NBSP?>9</option>" ;
                   strScript  += "   <option value=10 <?if (substr($FromDate,6,2)=='10') echo "selected";?>>10</option>" ;
                   strScript  += "   <option value=11 <?if (substr($FromDate,6,2)=='11') echo "selected";?>>11</option>" ;
                   strScript  += "   <option value=12 <?if (substr($FromDate,6,2)=='12') echo "selected";?>>12</option>" ;
                   strScript  += "   <option value=13 <?if (substr($FromDate,6,2)=='13') echo "selected";?>>13</option>" ;
                   strScript  += "   <option value=14 <?if (substr($FromDate,6,2)=='14') echo "selected";?>>14</option>" ;
                   strScript  += "   <option value=15 <?if (substr($FromDate,6,2)=='15') echo "selected";?>>15</option>" ;
                   strScript  += "   <option value=16 <?if (substr($FromDate,6,2)=='16') echo "selected";?>>16</option>" ;
                   strScript  += "   <option value=17 <?if (substr($FromDate,6,2)=='17') echo "selected";?>>17</option>" ;
                   strScript  += "   <option value=18 <?if (substr($FromDate,6,2)=='18') echo "selected";?>>18</option>" ;
                   strScript  += "   <option value=19 <?if (substr($FromDate,6,2)=='19') echo "selected";?>>19</option>" ;
                   strScript  += "   <option value=20 <?if (substr($FromDate,6,2)=='20') echo "selected";?>>20</option>" ;
                   strScript  += "   <option value=21 <?if (substr($FromDate,6,2)=='21') echo "selected";?>>21</option>" ;
                   strScript  += "   <option value=22 <?if (substr($FromDate,6,2)=='22') echo "selected";?>>22</option>" ;
                   strScript  += "   <option value=23 <?if (substr($FromDate,6,2)=='23') echo "selected";?>>23</option>" ;
                   strScript  += "   <option value=24 <?if (substr($FromDate,6,2)=='24') echo "selected";?>>24</option>" ;
                   strScript  += "   <option value=25 <?if (substr($FromDate,6,2)=='25') echo "selected";?>>25</option>" ;
                   strScript  += "   <option value=26 <?if (substr($FromDate,6,2)=='26') echo "selected";?>>26</option>" ;
                   strScript  += "   <option value=27 <?if (substr($FromDate,6,2)=='27') echo "selected";?>>27</option>" ;
                   strScript  += "   <option value=28 <?if (substr($FromDate,6,2)=='28') echo "selected";?>>28</option>" ;
                   strScript  += "   <option value=29 <?if (substr($FromDate,6,2)=='29') echo "selected";?>>29</option>" ;
                   strScript  += "   <option value=30 <?if (substr($FromDate,6,2)=='30') echo "selected";?>>30</option>" ;
                   strScript  += "   <option value=31 <?if (substr($FromDate,6,2)=='31') echo "selected";?>>31</option>" ;
                   strScript  += "</select> ��" ;

                   strScript  += "<?=$NBSP?>~<?=$NBSP?>" ;

                   strScript  += "<select name=ToYear>" ;
                   strScript  += "  <option value=2004 <?if (substr($ToDate,0,4)=='2004') echo "selected";?>>2004</option>" ;
                   strScript  += "  <option value=2005 <?if (substr($ToDate,0,4)=='2005') echo "selected";?>>2005</option>" ;
                   strScript  += "  <option value=2006 <?if (substr($ToDate,0,4)=='2006') echo "selected";?>>2006</option>" ;
                   strScript  += "  <option value=2007 <?if (substr($ToDate,0,4)=='2007') echo "selected";?>>2007</option>" ;
                   strScript  += "  <option value=2008 <?if (substr($ToDate,0,4)=='2008') echo "selected";?>>2008</option>" ;
                   strScript  += "  <option value=2009 <?if (substr($ToDate,0,4)=='2009') echo "selected";?>>2009</option>" ;
                   strScript  += "  <option value=2010 <?if (substr($ToDate,0,4)=='2010') echo "selected";?>>2010</option>" ;
                   strScript  += "  <option value=2011 <?if (substr($ToDate,0,4)=='2011') echo "selected";?>>2011</option>" ;
                   strScript  += "  <option value=2012 <?if (substr($ToDate,0,4)=='2012') echo "selected";?>>2012</option>" ;
                   strScript  += "  <option value=2013 <?if (substr($ToDate,0,4)=='2013') echo "selected";?>>2013</option>" ;
                   strScript  += "</select> ��" ;
                   
                   strScript  += "<select name=ToMonth>" ;
                   strScript  += "   <option value=1 <?if (substr($ToDate,4,2)=='01') echo "selected";?>><?=$NBSP?>1</option>" ;
                   strScript  += "   <option value=2 <?if (substr($ToDate,4,2)=='02') echo "selected";?>><?=$NBSP?>2</option>" ;
                   strScript  += "   <option value=3 <?if (substr($ToDate,4,2)=='03') echo "selected";?>><?=$NBSP?>3</option>" ;
                   strScript  += "   <option value=4 <?if (substr($ToDate,4,2)=='04') echo "selected";?>><?=$NBSP?>4</option>" ;
                   strScript  += "   <option value=5 <?if (substr($ToDate,4,2)=='05') echo "selected";?>><?=$NBSP?>5</option>" ;
                   strScript  += "   <option value=6 <?if (substr($ToDate,4,2)=='06') echo "selected";?>><?=$NBSP?>6</option>" ;
                   strScript  += "   <option value=7 <?if (substr($ToDate,4,2)=='07') echo "selected";?>><?=$NBSP?>7</option>" ;
                   strScript  += "   <option value=8 <?if (substr($ToDate,4,2)=='08') echo "selected";?>><?=$NBSP?>8</option>" ;
                   strScript  += "   <option value=9 <?if (substr($ToDate,4,2)=='09') echo "selected";?>><?=$NBSP?>9</option>" ;
                   strScript  += "   <option value=10 <?if (substr($ToDate,4,2)=='10') echo "selected";?>><?=$NBSP?>10</option>" ;
                   strScript  += "   <option value=11 <?if (substr($ToDate,4,2)=='11') echo "selected";?>><?=$NBSP?>11</option>" ;
                   strScript  += "   <option value=12 <?if (substr($ToDate,4,2)=='12') echo "selected";?>><?=$NBSP?>12</option>" ;
                   strScript  += "</select> ��" ;
                   
                   strScript  += "<select name=ToDay>" ;
                   strScript  += "   <option value=1 <?if (substr($ToDate,6,2)=='01') echo "selected";?>><?=$NBSP?>1</option>" ;
                   strScript  += "   <option value=2 <?if (substr($ToDate,6,2)=='02') echo "selected";?>><?=$NBSP?>2</option>" ;
                   strScript  += "   <option value=3 <?if (substr($ToDate,6,2)=='03') echo "selected";?>><?=$NBSP?>3</option>" ;
                   strScript  += "   <option value=4 <?if (substr($ToDate,6,2)=='04') echo "selected";?>><?=$NBSP?>4</option>" ;
                   strScript  += "   <option value=5 <?if (substr($ToDate,6,2)=='05') echo "selected";?>><?=$NBSP?>5</option>" ;
                   strScript  += "   <option value=6 <?if (substr($ToDate,6,2)=='06') echo "selected";?>><?=$NBSP?>6</option>" ;
                   strScript  += "   <option value=7 <?if (substr($ToDate,6,2)=='07') echo "selected";?>><?=$NBSP?>7</option>" ;
                   strScript  += "   <option value=8 <?if (substr($ToDate,6,2)=='08') echo "selected";?>><?=$NBSP?>8</option>" ;
                   strScript  += "   <option value=9 <?if (substr($ToDate,6,2)=='09') echo "selected";?>><?=$NBSP?>9</option>" ;
                   strScript  += "   <option value=10 <?if (substr($ToDate,6,2)=='10') echo "selected";?>>10</option>" ;
                   strScript  += "   <option value=11 <?if (substr($ToDate,6,2)=='11') echo "selected";?>>11</option>" ;
                   strScript  += "   <option value=12 <?if (substr($ToDate,6,2)=='12') echo "selected";?>>12</option>" ;
                   strScript  += "   <option value=13 <?if (substr($ToDate,6,2)=='13') echo "selected";?>>13</option>" ;
                   strScript  += "   <option value=14 <?if (substr($ToDate,6,2)=='14') echo "selected";?>>14</option>" ;
                   strScript  += "   <option value=15 <?if (substr($ToDate,6,2)=='15') echo "selected";?>>15</option>" ;
                   strScript  += "   <option value=16 <?if (substr($ToDate,6,2)=='16') echo "selected";?>>16</option>" ;
                   strScript  += "   <option value=17 <?if (substr($ToDate,6,2)=='17') echo "selected";?>>17</option>" ;
                   strScript  += "   <option value=18 <?if (substr($ToDate,6,2)=='18') echo "selected";?>>18</option>" ;
                   strScript  += "   <option value=19 <?if (substr($ToDate,6,2)=='19') echo "selected";?>>19</option>" ;
                   strScript  += "   <option value=20 <?if (substr($ToDate,6,2)=='20') echo "selected";?>>20</option>" ;
                   strScript  += "   <option value=21 <?if (substr($ToDate,6,2)=='21') echo "selected";?>>21</option>" ;
                   strScript  += "   <option value=22 <?if (substr($ToDate,6,2)=='22') echo "selected";?>>22</option>" ;
                   strScript  += "   <option value=23 <?if (substr($ToDate,6,2)=='23') echo "selected";?>>23</option>" ;
                   strScript  += "   <option value=24 <?if (substr($ToDate,6,2)=='24') echo "selected";?>>24</option>" ;
                   strScript  += "   <option value=25 <?if (substr($ToDate,6,2)=='25') echo "selected";?>>25</option>" ;
                   strScript  += "   <option value=26 <?if (substr($ToDate,6,2)=='26') echo "selected";?>>26</option>" ;
                   strScript  += "   <option value=27 <?if (substr($ToDate,6,2)=='27') echo "selected";?>>27</option>" ;
                   strScript  += "   <option value=28 <?if (substr($ToDate,6,2)=='28') echo "selected";?>>28</option>" ;
                   strScript  += "   <option value=29 <?if (substr($ToDate,6,2)=='29') echo "selected";?>>29</option>" ;
                   strScript  += "   <option value=30 <?if (substr($ToDate,6,2)=='30') echo "selected";?>>30</option>" ;
                   strScript  += "   <option value=31 <?if (substr($ToDate,6,2)=='31') echo "selected";?>>31</option>" ;
                   strScript  += "</select> ��" ;
               }
               divdate.innerHTML = strScript ;
         }

         function click_search(dir)
         {            
               // ����ڰ� �����ѳ�¥.
               sPrnKind      = form1.PrnKind.value ;
               sDisplayType  = form1.DisplayType.value ;
               sZoneLoc      = form1.ZoneLoc.value ;

               if  (dir==1)
               {
                   url = '<?=$PHP_SELF?>?'
                       + 'ToExel=False&'
                       + 'FilmSupply=20003&'
                       + 'DisplayType='+sDisplayType+'&'
                       + 'ZoneLoc='+sZoneLoc ;
               }
               else
               {
                   url = '<?=$PHP_SELF?>?'
                       + 'ToExel=True&'
                       + 'FilmSupply=20003&'
                       + 'DisplayType='+sDisplayType+'&'
                       + 'ZoneLoc='+sZoneLoc ;
               }

               if   (sPrnKind == "Date")  // �Ϻ��϶�
               {
                   sCurrDate = form1.CurrYear.value + fn(form1.CurrMonth.value) + fn(form1.CurrDay.value) ;

                   url += '&'
                       + 'CurrDate='+sCurrDate ;
               }
               if   (sPrnKind == "Term")  // �Ⱓ���϶�
               {
                   sFromDate = form1.FromYear.value + fn(form1.FromMonth.value) + fn(form1.FromDay.value) ;
                   sToDate   = form1.ToYear.value   + fn(form1.ToMonth.value)   + fn(form1.ToDay.value) ;
                   
                   url += '&'
                       + 'FromDate='+sFromDate+'&'
                       + 'ToDate='+sToDate ;
               }
               url += '&PrnKind='+sPrnKind ;

               form1.action =  url ;  // action �� �ְ�
               form1.submit() ;
         }
</script>




<center>

   <br><b>*��뿵ȭ��Ȳ*</b><br>


   <form method=post name=form1>

   
   <input name=FilmSupply type=hidden value=<?=$FilmSupply?>>

   

   

   <?
   if ($ToExel != "True")
   {
   ?>   
   <table>
   <tr>
       <td>             
            <select name=DisplayType>
              <option value=1 <?if ($DisplayType=='1') echo "selected";?>>���庰</option>
              <option value=2 <?if ($DisplayType=='2') echo "selected";?>>��ȭ��</option>
            </select>

            <select name=ZoneLoc>
              <option value=0 selected>��������</option>
              <option value=-1>--------</option>
              <?
              
              
              if  ($ZoneLoc == "100")  echo "<option value=100 selected>����</option>" ;
              else                     echo "<option value=100>����</option>" ;
              if  ($ZoneLoc == "04")   echo "<option value=04 selected>���</option>" ;
              else                     echo "<option value=04>���</option>" ;
              if  ($ZoneLoc == "200")  echo "<option value=200 selected>�λ�</option>" ;
              else                     echo "<option value=200>�λ�</option>" ;
              if  ($ZoneLoc == "99")   echo "<option value=99 selected>����</option>" ;
              else                     echo "<option value=99>����</option>" ;
              if  ($ZoneLoc == "9999") echo "<option value=9999 selected>��ü</option>" ;
              else                     echo "<option value=9999>��ü</option>" ;
              
              ?>
            </select> 

            <select name=PrnKind onchange='select_PrnKind(this);'>
               <option value=0 selected>�������</option>
               <option value=-1>--------</option>
               <option value=Date <?if  ($PrnKind=="Date") echo "selected"; ?>>�Ϻ�</option>
               <option value=Term <?if  ($PrnKind=="Term") echo "selected"; ?>>�Ⱓ��</option>
            </select> 

           
       </td>
       <td>
           <?=$NBSP?><?=$NBSP?><?=$NBSP?><?=$NBSP?><?=$NBSP?><?=$NBSP?><?=$NBSP?><?=$NBSP?>
       </td>
       <td>
           <div id=divdate>
           <?
           if  ($PrnKind=="Date")
           {
               ?>
               <select name=CurrYear>
                 <option value=2004 <?if (substr($CurrDate,0,4)=='2004') echo "selected";?>>2004</option>
                 <option value=2005 <?if (substr($CurrDate,0,4)=='2005') echo "selected";?>>2005</option>
                 <option value=2006 <?if (substr($CurrDate,0,4)=='2006') echo "selected";?>>2006</option>
                 <option value=2007 <?if (substr($CurrDate,0,4)=='2007') echo "selected";?>>2007</option>
                 <option value=2008 <?if (substr($CurrDate,0,4)=='2008') echo "selected";?>>2008</option>
                 <option value=2009 <?if (substr($CurrDate,0,4)=='2009') echo "selected";?>>2009</option>
                 <option value=2010 <?if (substr($CurrDate,0,4)=='2010') echo "selected";?>>2010</option>
                 <option value=2011 <?if (substr($CurrDate,0,4)=='2011') echo "selected";?>>2011</option>
                 <option value=2012 <?if (substr($CurrDate,0,4)=='2012') echo "selected";?>>2012</option>
                 <option value=2013 <?if (substr($CurrDate,0,4)=='2013') echo "selected";?>>2013</option>
               </select> ��

               <select name=CurrMonth>
                  <option value=1 <?if (substr($CurrDate,4,2)=='01') echo "selected";?>><?=$NBSP?>1</option>
                  <option value=2 <?if (substr($CurrDate,4,2)=='02') echo "selected";?>><?=$NBSP?>2</option>
                  <option value=3 <?if (substr($CurrDate,4,2)=='03') echo "selected";?>><?=$NBSP?>3</option>
                  <option value=4 <?if (substr($CurrDate,4,2)=='04') echo "selected";?>><?=$NBSP?>4</option>
                  <option value=5 <?if (substr($CurrDate,4,2)=='05') echo "selected";?>><?=$NBSP?>5</option>
                  <option value=6 <?if (substr($CurrDate,4,2)=='06') echo "selected";?>><?=$NBSP?>6</option>
                  <option value=7 <?if (substr($CurrDate,4,2)=='07') echo "selected";?>><?=$NBSP?>7</option>
                  <option value=8 <?if (substr($CurrDate,4,2)=='08') echo "selected";?>><?=$NBSP?>8</option>
                  <option value=9 <?if (substr($CurrDate,4,2)=='09') echo "selected";?>><?=$NBSP?>9</option>
                  <option value=10 <?if (substr($CurrDate,4,2)=='10') echo "selected";?>><?=$NBSP?>10</option>
                  <option value=11 <?if (substr($CurrDate,4,2)=='11') echo "selected";?>><?=$NBSP?>11</option>
                  <option value=12 <?if (substr($CurrDate,4,2)=='12') echo "selected";?>><?=$NBSP?>12</option>
               </select> ��
               
               <select name=CurrDay>
                  <option value=1 <?if (substr($CurrDate,6,2)=='01') echo "selected";?>><?=$NBSP?>1</option>
                  <option value=2 <?if (substr($CurrDate,6,2)=='02') echo "selected";?>><?=$NBSP?>2</option>
                  <option value=3 <?if (substr($CurrDate,6,2)=='03') echo "selected";?>><?=$NBSP?>3</option>
                  <option value=4 <?if (substr($CurrDate,6,2)=='04') echo "selected";?>><?=$NBSP?>4</option>
                  <option value=5 <?if (substr($CurrDate,6,2)=='05') echo "selected";?>><?=$NBSP?>5</option>
                  <option value=6 <?if (substr($CurrDate,6,2)=='06') echo "selected";?>><?=$NBSP?>6</option>
                  <option value=7 <?if (substr($CurrDate,6,2)=='07') echo "selected";?>><?=$NBSP?>7</option>
                  <option value=8 <?if (substr($CurrDate,6,2)=='08') echo "selected";?>><?=$NBSP?>8</option>
                  <option value=9 <?if (substr($CurrDate,6,2)=='09') echo "selected";?>><?=$NBSP?>9</option>
                  <option value=10 <?if (substr($CurrDate,6,2)=='10') echo "selected";?>>10</option>
                  <option value=11 <?if (substr($CurrDate,6,2)=='11') echo "selected";?>>11</option>
                  <option value=12 <?if (substr($CurrDate,6,2)=='12') echo "selected";?>>12</option>
                  <option value=13 <?if (substr($CurrDate,6,2)=='13') echo "selected";?>>13</option>
                  <option value=14 <?if (substr($CurrDate,6,2)=='14') echo "selected";?>>14</option>
                  <option value=15 <?if (substr($CurrDate,6,2)=='15') echo "selected";?>>15</option>
                  <option value=16 <?if (substr($CurrDate,6,2)=='16') echo "selected";?>>16</option>
                  <option value=17 <?if (substr($CurrDate,6,2)=='17') echo "selected";?>>17</option>
                  <option value=18 <?if (substr($CurrDate,6,2)=='18') echo "selected";?>>18</option>
                  <option value=19 <?if (substr($CurrDate,6,2)=='19') echo "selected";?>>19</option>
                  <option value=20 <?if (substr($CurrDate,6,2)=='20') echo "selected";?>>20</option>
                  <option value=21 <?if (substr($CurrDate,6,2)=='21') echo "selected";?>>21</option>
                  <option value=22 <?if (substr($CurrDate,6,2)=='22') echo "selected";?>>22</option>
                  <option value=23 <?if (substr($CurrDate,6,2)=='23') echo "selected";?>>23</option>
                  <option value=24 <?if (substr($CurrDate,6,2)=='24') echo "selected";?>>24</option>
                  <option value=25 <?if (substr($CurrDate,6,2)=='25') echo "selected";?>>25</option>
                  <option value=26 <?if (substr($CurrDate,6,2)=='26') echo "selected";?>>26</option>
                  <option value=27 <?if (substr($CurrDate,6,2)=='27') echo "selected";?>>27</option>
                  <option value=28 <?if (substr($CurrDate,6,2)=='28') echo "selected";?>>28</option>
                  <option value=29 <?if (substr($CurrDate,6,2)=='29') echo "selected";?>>29</option>
                  <option value=30 <?if (substr($CurrDate,6,2)=='30') echo "selected";?>>30</option>
                  <option value=31 <?if (substr($CurrDate,6,2)=='31') echo "selected";?>>31</option>
               </select> ��
               <?
           }
           
           if  ($PrnKind=="Term")
           {
               ?>
               <select name=FromYear>
                 <option value=2004 <?if (substr($FromDate,0,4)=='2004') echo "selected";?>>2004</option>
                 <option value=2005 <?if (substr($FromDate,0,4)=='2005') echo "selected";?>>2005</option>
                 <option value=2006 <?if (substr($FromDate,0,4)=='2006') echo "selected";?>>2006</option>
                 <option value=2007 <?if (substr($FromDate,0,4)=='2007') echo "selected";?>>2007</option>
                 <option value=2008 <?if (substr($FromDate,0,4)=='2008') echo "selected";?>>2008</option>
                 <option value=2009 <?if (substr($FromDate,0,4)=='2009') echo "selected";?>>2009</option>
                 <option value=2010 <?if (substr($FromDate,0,4)=='2010') echo "selected";?>>2010</option>
                 <option value=2011 <?if (substr($FromDate,0,4)=='2011') echo "selected";?>>2011</option>
                 <option value=2012 <?if (substr($FromDate,0,4)=='2012') echo "selected";?>>2012</option>
                 <option value=2013 <?if (substr($FromDate,0,4)=='2013') echo "selected";?>>2013</option>
               </select> ��

               <select name=FromMonth>
                  <option value=1 <?if (substr($FromDate,4,2)=='01') echo "selected";?>><?=$NBSP?>1</option>
                  <option value=2 <?if (substr($FromDate,4,2)=='02') echo "selected";?>><?=$NBSP?>2</option>
                  <option value=3 <?if (substr($FromDate,4,2)=='03') echo "selected";?>><?=$NBSP?>3</option>
                  <option value=4 <?if (substr($FromDate,4,2)=='04') echo "selected";?>><?=$NBSP?>4</option>
                  <option value=5 <?if (substr($FromDate,4,2)=='05') echo "selected";?>><?=$NBSP?>5</option>
                  <option value=6 <?if (substr($FromDate,4,2)=='06') echo "selected";?>><?=$NBSP?>6</option>
                  <option value=7 <?if (substr($FromDate,4,2)=='07') echo "selected";?>><?=$NBSP?>7</option>
                  <option value=8 <?if (substr($FromDate,4,2)=='08') echo "selected";?>><?=$NBSP?>8</option>
                  <option value=9 <?if (substr($FromDate,4,2)=='09') echo "selected";?>><?=$NBSP?>9</option>
                  <option value=10 <?if (substr($FromDate,4,2)=='10') echo "selected";?>><?=$NBSP?>10</option>
                  <option value=11 <?if (substr($FromDate,4,2)=='11') echo "selected";?>><?=$NBSP?>11</option>
                  <option value=12 <?if (substr($FromDate,4,2)=='12') echo "selected";?>><?=$NBSP?>12</option>
               </select> ��
               
               <select name=FromDay>
                  <option value=1 <?if (substr($FromDate,6,2)=='01') echo "selected";?>><?=$NBSP?>1</option>
                  <option value=2 <?if (substr($FromDate,6,2)=='02') echo "selected";?>><?=$NBSP?>2</option>
                  <option value=3 <?if (substr($FromDate,6,2)=='03') echo "selected";?>><?=$NBSP?>3</option>
                  <option value=4 <?if (substr($FromDate,6,2)=='04') echo "selected";?>><?=$NBSP?>4</option>
                  <option value=5 <?if (substr($FromDate,6,2)=='05') echo "selected";?>><?=$NBSP?>5</option>
                  <option value=6 <?if (substr($FromDate,6,2)=='06') echo "selected";?>><?=$NBSP?>6</option>
                  <option value=7 <?if (substr($FromDate,6,2)=='07') echo "selected";?>><?=$NBSP?>7</option>
                  <option value=8 <?if (substr($FromDate,6,2)=='08') echo "selected";?>><?=$NBSP?>8</option>
                  <option value=9 <?if (substr($FromDate,6,2)=='09') echo "selected";?>><?=$NBSP?>9</option>
                  <option value=10 <?if (substr($FromDate,6,2)=='10') echo "selected";?>>10</option>
                  <option value=11 <?if (substr($FromDate,6,2)=='11') echo "selected";?>>11</option>
                  <option value=12 <?if (substr($FromDate,6,2)=='12') echo "selected";?>>12</option>
                  <option value=13 <?if (substr($FromDate,6,2)=='13') echo "selected";?>>13</option>
                  <option value=14 <?if (substr($FromDate,6,2)=='14') echo "selected";?>>14</option>
                  <option value=15 <?if (substr($FromDate,6,2)=='15') echo "selected";?>>15</option>
                  <option value=16 <?if (substr($FromDate,6,2)=='16') echo "selected";?>>16</option>
                  <option value=17 <?if (substr($FromDate,6,2)=='17') echo "selected";?>>17</option>
                  <option value=18 <?if (substr($FromDate,6,2)=='18') echo "selected";?>>18</option>
                  <option value=19 <?if (substr($FromDate,6,2)=='19') echo "selected";?>>19</option>
                  <option value=20 <?if (substr($FromDate,6,2)=='20') echo "selected";?>>20</option>
                  <option value=21 <?if (substr($FromDate,6,2)=='21') echo "selected";?>>21</option>
                  <option value=22 <?if (substr($FromDate,6,2)=='22') echo "selected";?>>22</option>
                  <option value=23 <?if (substr($FromDate,6,2)=='23') echo "selected";?>>23</option>
                  <option value=24 <?if (substr($FromDate,6,2)=='24') echo "selected";?>>24</option>
                  <option value=25 <?if (substr($FromDate,6,2)=='25') echo "selected";?>>25</option>
                  <option value=26 <?if (substr($FromDate,6,2)=='26') echo "selected";?>>26</option>
                  <option value=27 <?if (substr($FromDate,6,2)=='27') echo "selected";?>>27</option>
                  <option value=28 <?if (substr($FromDate,6,2)=='28') echo "selected";?>>28</option>
                  <option value=29 <?if (substr($FromDate,6,2)=='29') echo "selected";?>>29</option>
                  <option value=30 <?if (substr($FromDate,6,2)=='30') echo "selected";?>>30</option>
                  <option value=31 <?if (substr($FromDate,6,2)=='31') echo "selected";?>>31</option>
               </select> ��

               <?=$NBSP?>~<?=$NBSP?>

               <select name=ToYear>
                 <option value=2004 <?if (substr($ToDate,0,4)=='2004') echo "selected";?>>2004</option>
                 <option value=2005 <?if (substr($ToDate,0,4)=='2005') echo "selected";?>>2005</option>
                 <option value=2006 <?if (substr($ToDate,0,4)=='2006') echo "selected";?>>2006</option>
                 <option value=2007 <?if (substr($ToDate,0,4)=='2007') echo "selected";?>>2007</option>
                 <option value=2008 <?if (substr($ToDate,0,4)=='2008') echo "selected";?>>2008</option>
                 <option value=2009 <?if (substr($ToDate,0,4)=='2009') echo "selected";?>>2009</option>
                 <option value=2010 <?if (substr($ToDate,0,4)=='2010') echo "selected";?>>2010</option>
                 <option value=2011 <?if (substr($ToDate,0,4)=='2011') echo "selected";?>>2011</option>
                 <option value=2012 <?if (substr($ToDate,0,4)=='2012') echo "selected";?>>2012</option>
                 <option value=2013 <?if (substr($ToDate,0,4)=='2013') echo "selected";?>>2013</option>
               </select> ��
               
               <select name=ToMonth>
                  <option value=1 <?if (substr($ToDate,4,2)=='01') echo "selected";?>><?=$NBSP?>1</option>
                  <option value=2 <?if (substr($ToDate,4,2)=='02') echo "selected";?>><?=$NBSP?>2</option>
                  <option value=3 <?if (substr($ToDate,4,2)=='03') echo "selected";?>><?=$NBSP?>3</option>
                  <option value=4 <?if (substr($ToDate,4,2)=='04') echo "selected";?>><?=$NBSP?>4</option>
                  <option value=5 <?if (substr($ToDate,4,2)=='05') echo "selected";?>><?=$NBSP?>5</option>
                  <option value=6 <?if (substr($ToDate,4,2)=='06') echo "selected";?>><?=$NBSP?>6</option>
                  <option value=7 <?if (substr($ToDate,4,2)=='07') echo "selected";?>><?=$NBSP?>7</option>
                  <option value=8 <?if (substr($ToDate,4,2)=='08') echo "selected";?>><?=$NBSP?>8</option>
                  <option value=9 <?if (substr($ToDate,4,2)=='09') echo "selected";?>><?=$NBSP?>9</option>
                  <option value=10 <?if (substr($ToDate,4,2)=='10') echo "selected";?>><?=$NBSP?>10</option>
                  <option value=11 <?if (substr($ToDate,4,2)=='11') echo "selected";?>><?=$NBSP?>11</option>
                  <option value=12 <?if (substr($ToDate,4,2)=='12') echo "selected";?>><?=$NBSP?>12</option>
               </select> ��
               
               <select name=ToDay>
                  <option value=1 <?if (substr($ToDate,6,2)=='01') echo "selected";?>><?=$NBSP?>1</option>
                  <option value=2 <?if (substr($ToDate,6,2)=='02') echo "selected";?>><?=$NBSP?>2</option>
                  <option value=3 <?if (substr($ToDate,6,2)=='03') echo "selected";?>><?=$NBSP?>3</option>
                  <option value=4 <?if (substr($ToDate,6,2)=='04') echo "selected";?>><?=$NBSP?>4</option>
                  <option value=5 <?if (substr($ToDate,6,2)=='05') echo "selected";?>><?=$NBSP?>5</option>
                  <option value=6 <?if (substr($ToDate,6,2)=='06') echo "selected";?>><?=$NBSP?>6</option>
                  <option value=7 <?if (substr($ToDate,6,2)=='07') echo "selected";?>><?=$NBSP?>7</option>
                  <option value=8 <?if (substr($ToDate,6,2)=='08') echo "selected";?>><?=$NBSP?>8</option>
                  <option value=9 <?if (substr($ToDate,6,2)=='09') echo "selected";?>><?=$NBSP?>9</option>
                  <option value=10 <?if (substr($ToDate,6,2)=='10') echo "selected";?>>10</option>
                  <option value=11 <?if (substr($ToDate,6,2)=='11') echo "selected";?>>11</option>
                  <option value=12 <?if (substr($ToDate,6,2)=='12') echo "selected";?>>12</option>
                  <option value=13 <?if (substr($ToDate,6,2)=='13') echo "selected";?>>13</option>
                  <option value=14 <?if (substr($ToDate,6,2)=='14') echo "selected";?>>14</option>
                  <option value=15 <?if (substr($ToDate,6,2)=='15') echo "selected";?>>15</option>
                  <option value=16 <?if (substr($ToDate,6,2)=='16') echo "selected";?>>16</option>
                  <option value=17 <?if (substr($ToDate,6,2)=='17') echo "selected";?>>17</option>
                  <option value=18 <?if (substr($ToDate,6,2)=='18') echo "selected";?>>18</option>
                  <option value=19 <?if (substr($ToDate,6,2)=='19') echo "selected";?>>19</option>
                  <option value=20 <?if (substr($ToDate,6,2)=='20') echo "selected";?>>20</option>
                  <option value=21 <?if (substr($ToDate,6,2)=='21') echo "selected";?>>21</option>
                  <option value=22 <?if (substr($ToDate,6,2)=='22') echo "selected";?>>22</option>
                  <option value=23 <?if (substr($ToDate,6,2)=='23') echo "selected";?>>23</option>
                  <option value=24 <?if (substr($ToDate,6,2)=='24') echo "selected";?>>24</option>
                  <option value=25 <?if (substr($ToDate,6,2)=='25') echo "selected";?>>25</option>
                  <option value=26 <?if (substr($ToDate,6,2)=='26') echo "selected";?>>26</option>
                  <option value=27 <?if (substr($ToDate,6,2)=='27') echo "selected";?>>27</option>
                  <option value=28 <?if (substr($ToDate,6,2)=='28') echo "selected";?>>28</option>
                  <option value=29 <?if (substr($ToDate,6,2)=='29') echo "selected";?>>29</option>
                  <option value=30 <?if (substr($ToDate,6,2)=='30') echo "selected";?>>30</option>
                  <option value=31 <?if (substr($ToDate,6,2)=='31') echo "selected";?>>31</option>
               </select> ��
               <?
           }
           ?>
           </div>
       </td>
       <td>
           <input type=button name=Search value="��ȸ(ȭ��)" onclick="click_search(1)">
           <input type=button name=Search value="��ȸ(����)" onclick="click_search(2)">
       </td>
   </tr>
   </table>
   <?
   }
   ?>
   


   
   

   

   

   </form>

      <?
      if  ($DisplayType=='1')   // ���庰
      {
      ?>
           <TABLE style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1>
           <TR>
               <TD class=textarea bgcolor=#ffebcd align=center width=40 >����</TD>
               <TD class=textarea bgcolor=#ffebcd align=center width=120>����</TD>
               <TD class=textarea bgcolor=#ffebcd align=center width=60 >�¼���</TD>
               <TD class=textarea bgcolor=#ffebcd align=center width=150>��ȭ</TD>
               <? 
               for ($i=0 ; $i<=$dur_day ; $i++)
               {
                     $objDate = date("Ymd",$timestamp2 + ($i * 86400)) ;   

                     $WeekDay=date("w", mktime(0, 0, 0, substr($objDate,4,2), substr($objDate,6,2), substr($objDate,0,4)));
                     if  ($WeekDay==0)  $Week = "��" ;
                     if  ($WeekDay==1)  $Week = "��" ;
                     if  ($WeekDay==2)  $Week = "ȭ" ;
                     if  ($WeekDay==3)  $Week = "��" ;
                     if  ($WeekDay==4)  $Week = "��" ;
                     if  ($WeekDay==5)  $Week = "��" ;
                     if  ($WeekDay==6)  $Week = "��" ;                
               ?>
                   <TD class=textarea bgcolor=#ffebcd align=center width=70>
                   <?=date("m/d",$timestamp2 + ($i * 86400)) ;?><BR><?=$Week?>
                   </TD>
               <?
               } 
               ?>
               <TD class=textarea bgcolor=#ffebcd align=center width=80>�հ�</TD>
               <TD class=textarea bgcolor=#ffebcd align=center width=80>�Ѵ���</TD>
           </TR>
           
           <?
           
           if  ($PrnKind == "Term") // �Ⱓ��(����)
           {
                $OldSangTheatherCode = '' ;
                
                // ����,�����,��ũ��,�¼���,��ȭ��,��ȸ��¥, �հ�
                
                if   (($ZoneLoc == "9999") || ($ZoneLoc == "0")) // ��ü
                {
                     // ���帮��Ʈ (��ü)
                     $sQuery = "Select distinct s.theather             ". 
                               "From wrk_sangdae As s,                 ". 
                               "     bas_showroomorder As o            ". 
                               "Where s.WorkDate >= '".$FromDate."'    ".
                               "  And s.WorkDate <= '".$ToDate."'      ".
                               "  And s.Theather = o.Theather          ". 
                               "  And o.Room = '01'                    ". 
                               "Order By o.Seq                         " ;
                     $QryTheather = mysql_query($sQuery,$connect) ;
                }
                else
                {
                     if   (strlen($ZoneLoc) == 3) // ����
                     {
                          // ���帮��Ʈ (����)
                          $sQuery = "Select distinct theather           ".
                                    "  From wrk_sangdae                 ".
                                    " Where WorkDate >= '".$FromDate."' ".
                                    "   And WorkDate <= '".$ToDate."'   ".
                                    "   And Location = '".$ZoneLoc."'   ".
                                    " Order By TheatherName             " ;
                          $QryTheather = mysql_query($sQuery,$connect) ;
                     }
                     if   (strlen($ZoneLoc) == 2)  // ����
                     {
                          $sQuery = "Select Location From bas_filmsupplyzoneloc ".
                                    " Where Zone = '".$ZoneLoc."'               " ;
                          $QryZoneloc = mysql_query($sQuery,$connect) ;

                          // �ش� ������ ��� �������� ���Ѵ�.
                          $AddedLoc = " and " ;

                          while ($ArrZoneloc = mysql_fetch_array($QryZoneloc))
                          {            
                               if  ($AddedLoc == " and ")
                                   $AddedLoc .= "( Location = '".$ArrZoneloc["Location"]."' "  ;
                               else
                                   $AddedLoc .= " or Location = '".$ArrZoneloc["Location"]."' "  ;
                          }
                          $AddedLoc .= ")" ;                     

                          // ���帮��Ʈ (����)
                          $sQuery = "Select distinct theather           ".
                                    "  From wrk_sangdae                 ".
                                    " Where WorkDate >= '".$FromDate."' ".
                                    "   And WorkDate <= '".$ToDate."'   ".
                                    $AddedLoc                            .
                                    " Order By TheatherName             " ;
                          $QryTheather = mysql_query($sQuery,$connect) ;
                     }
                }

                while ($ArrTheather = mysql_fetch_array($QryTheather))
                {                          
                     $SangTheatherCode = $ArrTheather["theather"] ; // �����ڵ带 ���Ѵ�.                 

                     $strSeat = "" ;

                     $sQuery = "Select Room, Seat                         ".
                               "  From bas_showroom                       ".
                               " Where theather = '".$SangTheatherCode."' " ;
                     $QryShowroom = mysql_query($sQuery,$connect) ;
                     while ($ArrShowroom = mysql_fetch_array($QryShowroom))
                     {
                         $showroom_Room = $ArrShowroom["Room"] ; // ���ȣ
                         $showroom_Seat = $ArrShowroom["Seat"] ; // �¼���

                         $strSeat .=  $showroom_Room . "/" . $showroom_Seat . "<br>" ;
                     }
                     $sQuery = "Select * From bas_theather            ".
                               " Where code = '".$SangTheatherCode."' " ;
                     $QryTheatherName = mysql_query($sQuery,$connect) ;
                     if  ($TheatherName_data = mysql_fetch_array($QryTheatherName) )
                     {                 
                         $SangTheatherName     = $TheatherName_data["Discript"] ; // ������� ���Ѵ�.
                         $SangTheatherLocation = $TheatherName_data["Location"] ; // ���������� ���Ѵ�.

                         $sQuery = "Select * From bas_location                 ".
                                   " Where Code = '".$SangTheatherLocation."'  " ;
                         $QryLocation = mysql_query($sQuery,$connect) ;
                         if  ($ArrLocation = mysql_fetch_array($QryLocation))
                         {
                             $LocationName =  $ArrLocation["Name"] ;
                         }
                     }

                     

                     // ���峻 ��ȭ�� ����Ʈ�� ���Ѵ�.
                     $sQuery = "Select distinct SangFilm                  ".
                               "  From wrk_sangdae                        ".
                               " Where SangFilm != ''                     ".
                               "   And WorkDate >= '".$FromDate."'        ".
                               "   And WorkDate <= '".$ToDate."'          ".
                               "   And theather = '".$SangTheatherCode."' ".
                               " Order By SangFilm                        " ;
                     $QrySangFilm = mysql_query($sQuery,$connect) ;
                     $nNumFilm = mysql_num_rows($QrySangFilm) ;
                     while ($ArrSangFilm = mysql_fetch_array($QrySangFilm))
                     {
                           $SangFilmCode = $ArrSangFilm["SangFilm"] ;         

                           ?>
                           
                           <TR>
                           <?
                           if   ($OldSangTheatherCode <> $SangTheatherCode) // �󿵰� �������..
                           {
                               $OldSangTheatherCode = $SangTheatherCode ;

                               if  ($Color == "#c0c0c0") 
                               {
                                   $Color =  "#dcdcdc" ;
                               }
                               else
                               {
                                   $Color =  "#c0c0c0" ;
                               }
                               ?>                                
                               
                               <!-- ���� -->
                               <TD class=textarea bgcolor=<?=$Color?> align=center rowspan=<?=$nNumFilm?>>
                               <?=$LocationName?>
                               </TD> 
                                     
                               <!-- ��뿵ȭ ����� -->
                               <TD class=textarea bgcolor=<?=$Color?> align=center rowspan=<?=$nNumFilm?>>
                               <?=$SangTheatherName?>
                               </TD>                                 
                                
                               <!-- �¼��� -->
                               <TD class=textarea bgcolor=<?=$Color?> align=center rowspan=<?=$nNumFilm?>>
                               <?=$strSeat?>
                               </TD>  

                               <?
                           }

                           // ��ȭ�� ���
                           $sQuery = "Select * From bas_sangfilmtitle   ".
                                     " Where Code = '".$SangFilmCode."' " ;
                           $QryIndyfilmtitle = mysql_query($sQuery,$connect) ;
                           if  ($ArrIndyfilmtitle = mysql_fetch_array($QryIndyfilmtitle))
                           {
                               ?>
                               <TD class=textarea bgcolor=<?=$Color?> align=center><?=$ArrIndyfilmtitle["Name"]?></TD>
                               <?

                               $TotSumOfScore = 0 ;

                               $temp = mktime(0,0,0,substr($FromDate,4,2),substr($FromDate,6,2),substr($FromDate,0,4));

                               for ($i=0 ; $i<=$dur_day ; $i++)
                               {                            
                                    $objDate = date("Ymd",$temp + ($i * 86400)) ;   

                                    
                                    $sQuery = "Select Sum(Score) As SumOfScore           ".
                                              "  From wrk_sangdae                        ".
                                              " Where SangFilm = '".$SangFilmCode."'     ".
                                              "   And WorkDate = '".$objDate."'          ".
                                              "   And theather = '".$SangTheatherCode."' " ;
                                    $QrySumScore = mysql_query($sQuery,$connect) ;
                                    if   ($ArrSumScore = mysql_fetch_array($QrySumScore)) 
                                    {
                                         $SumOfScore = $ArrSumScore["SumOfScore"] ;
                                         $TotSumOfScore += $SumOfScore ;
                                         
                                         $RoomTitle = "" ;
                                         
                                         $sQuery = "Select Room                               ".
                                                   "  From wrk_sangdae                        ".
                                                   " Where SangFilm = '".$SangFilmCode."'     ".
                                                   "   And WorkDate = '".$objDate."'          ".
                                                   "   And theather = '".$SangTheatherCode."' " ;
                                         $QryRooms = mysql_query($sQuery,$connect) ;
                                         while ($ArrRooms = mysql_fetch_array($QryRooms))
                                         {
                                              $RoomTitle .= $ArrRooms["Room"] . "," ;
                                         }
                                         $RoomTitle = substr($RoomTitle,0,strlen($RoomTitle)-1) ; // ������ �ڸӸ� �߶󳽴�.                                     


                                         if   ($SumOfScore<>0)
                                         {
                                              ?>
                                              <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?><?=number_format($SumOfScore)?><?=$NBSP?><br><?=$NBSP?>[<?=$RoomTitle?>]<?=$NBSP?></TD>
                                              <?
                                         }
                                         else
                                         {
                                              ?>
                                              <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?></TD>
                                              <?                                 
                                         }
                                    }
                                    else
                                    {
                                    ?>
                                    <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?></TD>
                                    <?
                                    }
                               }
                           }
                           
                           
                           ?>
                                <!-- �հ� -->
                                <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?><?=number_format($TotSumOfScore)?><?=$NBSP?></TD>
                           <?
                                $sQuery = "Select Sum(Score) As SumOfScore           ".
                                          "  From wrk_sangdae                        ".
                                          " Where SangFilm = '".$SangFilmCode."'     ".
                                          "   And theather = '".$SangTheatherCode."' " ;
                                $QrySumScore = mysql_query($sQuery,$connect) ;
                                if   ($ArrSumScore = mysql_fetch_array($QrySumScore)) 
                                {
                           ?>
                                <!-- �Ѵ��� -->
                                <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?><?=number_format($ArrSumScore["SumOfScore"])?><?=$NBSP?></TD>
                           <?
                                } 
                           ?>
                           </TR>
                     <?
                     }                 
                }
           }

           if  ($PrnKind == "Date") // ���ں�(����)
           {
                $OldSangTheatherCode = '' ;
                
                // ����,�����,��ũ��,�¼���,��ȭ��,��ȸ��¥, �հ�
                
                if   (($ZoneLoc == "9999") || ($ZoneLoc == "0")) // ��ü
                {
                     // ���帮��Ʈ (��ü)
                     $sQuery = "Select distinct s.theather             ". 
                               "From wrk_sangdae As s,                 ". 
                               "     bas_showroomorder As o            ". 
                               "Where s.WorkDate = '".$CurrDate."'     ".
                               "  And s.Theather = o.Theather          ". 
                               "  And o.Room = '01'                    ".
                               "Order By o.Seq                         " ;
                     $QryTheather = mysql_query($sQuery,$connect) ;
                }
                else
                {
                     if   (strlen($ZoneLoc) == 3) // ����
                     {
                          // ���帮��Ʈ (����)
                          $sQuery = "Select distinct theather           ".
                                    "  From wrk_sangdae                 ".
                                    " Where WorkDate = '".$CurrDate."'  ".
                                    "   And Location = '".$ZoneLoc."'   ".
                                    " Order By TheatherName             " ;
                          $QryTheather = mysql_query($sQuery,$connect) ;
                     }
                     if   (strlen($ZoneLoc) == 2)  // ����
                     {
                          $sQuery = "Select Location From bas_filmsupplyzoneloc ".
                                    " Where Zone = '".$ZoneLoc."'               " ;
                          $QryZoneloc = mysql_query($sQuery,$connect) ;

                          // �ش� ������ ��� �������� ���Ѵ�.
                          $AddedLoc = " and " ;

                          while ($ArrZoneloc = mysql_fetch_array($QryZoneloc))
                          {            
                               if  ($AddedLoc == " and ")
                                   $AddedLoc .= "( Location = '".$ArrZoneloc["Location"]."' "  ;
                               else
                                   $AddedLoc .= " or Location = '".$ArrZoneloc["Location"]."' "  ;
                          }
                          $AddedLoc .= ")" ;                     

                          // ���帮��Ʈ (����)
                          $sQuery = "Select distinct theather           ".
                                    "  From wrk_sangdae                 ".
                                    " Where WorkDate = '".$CurrDate."'  ".
                                    $AddedLoc                            .
                                    " Order By TheatherName             " ;
                          $QryTheather = mysql_query($sQuery,$connect) ;
                     }
                }

                while ($ArrTheather = mysql_fetch_array($QryTheather))
                {                          
                     $SangTheatherCode = $ArrTheather["theather"] ; // �����ڵ带 ���Ѵ�.                 

                     $strSeat = "" ;

                     $sQuery = "Select Room, Seat                         ".
                               "  From bas_showroom                       ".
                               " Where theather = '".$SangTheatherCode."' " ;
                     $QryShowroom = mysql_query($sQuery,$connect) ;
                     while ($ArrShowroom = mysql_fetch_array($QryShowroom))
                     {
                         $showroom_Room = $ArrShowroom["Room"] ; // ���ȣ
                         $showroom_Seat = $ArrShowroom["Seat"] ; // �¼���

                         $strSeat .=  $showroom_Room . "/" . $showroom_Seat . "<br>" ;
                     }
                     $sQuery = "Select * From bas_theather            ".
                               " Where code = '".$SangTheatherCode."' " ;
                     $QryTheatherName = mysql_query($sQuery,$connect) ;
                     if  ($TheatherName_data = mysql_fetch_array($QryTheatherName) )
                     {                 
                         $SangTheatherName     = $TheatherName_data["Discript"] ; // ������� ���Ѵ�.
                         $SangTheatherLocation = $TheatherName_data["Location"] ; // ���������� ���Ѵ�.

                         $sQuery = "Select * From bas_location                 ".
                                   " Where Code = '".$SangTheatherLocation."'  " ;
                         $QryLocation = mysql_query($sQuery,$connect) ;
                         if  ($ArrLocation = mysql_fetch_array($QryLocation))
                         {
                             $LocationName =  $ArrLocation["Name"] ;
                         }
                     }

                     

                     // ���峻 ��ȭ�� ����Ʈ�� ���Ѵ�.
                     $sQuery = "Select distinct SangFilm                  ".
                               "  From wrk_sangdae                        ".
                               " Where SangFilm != ''                     ".
                               "   And WorkDate = '".$CurrDate."'         ".
                               "   And theather = '".$SangTheatherCode."' ".
                               " Order By SangFilm                        " ;
                     $QrySangFilm = mysql_query($sQuery,$connect) ;
                     $nNumFilm = mysql_num_rows($QrySangFilm) ;
                     while ($ArrSangFilm = mysql_fetch_array($QrySangFilm))
                     {
                           $SangFilmCode = $ArrSangFilm["SangFilm"] ;         

                           ?>
                           
                           <TR>
                           <?
                           if   ($OldSangTheatherCode <> $SangTheatherCode) // �󿵰� �������..
                           {
                               $OldSangTheatherCode = $SangTheatherCode ;

                               if  ($Color == "#c0c0c0") 
                               {
                                   $Color =  "#dcdcdc" ;
                               }
                               else
                               {
                                   $Color =  "#c0c0c0" ;
                               }
                               ?>                                
                               
                               <!-- ���� -->
                               <TD class=textarea bgcolor=<?=$Color?> align=center rowspan=<?=$nNumFilm?>>
                               <?=$LocationName?>
                               </TD> 
                                     
                               <!-- ��뿵ȭ ����� -->
                               <TD class=textarea bgcolor=<?=$Color?> align=center rowspan=<?=$nNumFilm?>>
                               <?=$SangTheatherName?>
                               </TD>                                 
                                
                               <!-- �¼��� -->
                               <TD class=textarea bgcolor=<?=$Color?> align=center rowspan=<?=$nNumFilm?>>
                               <?=$strSeat?>
                               </TD>  

                               <?
                           }

                           // ��ȭ�� ���
                           $sQuery = "Select * From bas_sangfilmtitle   ".
                                     " Where Code = '".$SangFilmCode."' " ;
                           $QryIndyfilmtitle = mysql_query($sQuery,$connect) ;
                           if  ($ArrIndyfilmtitle = mysql_fetch_array($QryIndyfilmtitle))
                           {
                               ?>
                               <TD class=textarea bgcolor=<?=$Color?> align=center><?=$ArrIndyfilmtitle["Name"]?></TD>
                               <?

                               $TotSumOfScore = 0 ;

                               $temp = mktime(0,0,0,substr($CurrDate,4,2),substr($CurrDate,6,2),substr($CurrDate,0,4));

                               for ($i=0 ; $i<=$dur_day ; $i++)
                               {                            
                                    $objDate = date("Ymd",$temp + ($i * 86400)) ;   

                                    
                                    $sQuery = "Select Sum(Score) As SumOfScore           ".
                                              "  From wrk_sangdae                        ".
                                              " Where SangFilm = '".$SangFilmCode."'     ".
                                              "   And WorkDate = '".$objDate."'          ".
                                              "   And theather = '".$SangTheatherCode."' " ;
                                    $QrySumScore = mysql_query($sQuery,$connect) ;
                                    if   ($ArrSumScore = mysql_fetch_array($QrySumScore)) 
                                    {
                                         $SumOfScore = $ArrSumScore["SumOfScore"] ;
                                         $TotSumOfScore += $SumOfScore ;
                                         
                                         $RoomTitle = "" ;
                                         
                                         $sQuery = "Select Room                               ".
                                                   "  From wrk_sangdae                        ".
                                                   " Where SangFilm = '".$SangFilmCode."'     ".
                                                   "   And WorkDate = '".$objDate."'          ".
                                                   "   And theather = '".$SangTheatherCode."' " ;
                                         $QryRooms = mysql_query($sQuery,$connect) ;
                                         while ($ArrRooms = mysql_fetch_array($QryRooms))
                                         {
                                              $RoomTitle .= $ArrRooms["Room"] . "," ;
                                         }
                                         $RoomTitle = substr($RoomTitle,0,strlen($RoomTitle)-1) ; // ������ �ڸӸ� �߶󳽴�.
                                         


                                         if   ($SumOfScore<>0)
                                         {
                                              ?>
                                              <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?><?=number_format($SumOfScore)?><?=$NBSP?><br><?=$NBSP?>[<?=$RoomTitle?>]<?=$NBSP?></TD>
                                              <?
                                         }
                                         else
                                         {
                                              ?>
                                              <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?></TD>
                                              <?                                 
                                         }
                                    }
                                    else
                                    {
                                    ?>
                                    <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?></TD>
                                    <?
                                    }
                               }

                           }

                           
                           ?>
                                <!-- �հ� -->
                                <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?><?=number_format($TotSumOfScore)?><?=$NBSP?></TD>
                           <?
                                $sQuery = "Select Sum(Score) As SumOfScore           ".
                                          "  From wrk_sangdae                        ".
                                          " Where SangFilm = '".$SangFilmCode."'     ".
                                          "   And theather = '".$SangTheatherCode."' " ;
                                $QrySumScore = mysql_query($sQuery,$connect) ;
                                if   ($ArrSumScore = mysql_fetch_array($QrySumScore)) 
                                {
                           ?>
                                <!-- �Ѵ��� -->
                                <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?><?=number_format($ArrSumScore["SumOfScore"])?><?=$NBSP?></TD>
                           <?
                                } 
                           ?>
                           </TR>
                     <?
                     }                 
                }
           }
           ?>
           </TABLE>      
      <?
      }
      


      if  ($DisplayType=='2')   // ��ȭ��
      {
      ?>
           <TABLE style='table-layout:fixed' cellpadding=0 cellspacing=0 border=1>
           <TR>
               <TD class=textarea bgcolor=#ffebcd align=center width=40 >����</TD>
               <TD class=textarea bgcolor=#ffebcd align=center width=150>��ȭ</TD>
               <? 
               for ($i=0 ; $i<=$dur_day ; $i++)
               {
                     $objDate = date("Ymd",$timestamp2 + ($i * 86400)) ;   

                     $WeekDay=date("w", mktime(0, 0, 0, substr($objDate,4,2), substr($objDate,6,2), substr($objDate,0,4)));
                     if  ($WeekDay==0)  $Week = "��" ;
                     if  ($WeekDay==1)  $Week = "��" ;
                     if  ($WeekDay==2)  $Week = "ȭ" ;
                     if  ($WeekDay==3)  $Week = "��" ;
                     if  ($WeekDay==4)  $Week = "��" ;
                     if  ($WeekDay==5)  $Week = "��" ;
                     if  ($WeekDay==6)  $Week = "��" ;                
               ?>
                   <TD class=textarea bgcolor=#ffebcd align=center width=50>
                   <?=date("m/d",$timestamp2 + ($i * 86400)) ;?><BR><?=$Week?>
                   </TD>
               <?
               } 
               ?>
               <TD class=textarea bgcolor=#ffebcd align=center width=80>�հ�</TD>
               <TD class=textarea bgcolor=#ffebcd align=center width=80>�Ѵ���</TD>
               <TD class=textarea bgcolor=#ffebcd align=center width=80>������</TD>
           </TR>
           
           <?           
           if  ($PrnKind == "Term") // �Ⱓ��(��ȭ)
           {
                $OldSangTheatherCode = '' ;

                // ����,�����,��ũ��,�¼���,��ȭ��,��ȸ��¥, �հ�
                
                if   (($ZoneLoc == "9999") || ($ZoneLoc == "0")) // ��ü
                {
                     // ��ȭ����Ʈ (��ü)
                     $sQuery = "Select SangFilm,                   ".
                               "       Sum(Score) As SumOfScore    ".
                               "  From wrk_sangdae                 ".
                               " Where WorkDate >= '".$FromDate."' ".
                               "   And WorkDate <= '".$ToDate."'   ".
                               " Group By SangFilm                 ".
                               " Order By SumOfScore desc          " ;
                     $QrySangFilm = mysql_query($sQuery,$connect) ;
                }
                else
                {
                     if   (strlen($ZoneLoc) == 3) // ����
                     {
                          // ��ȭ����Ʈ (����)
                          $sQuery = "Select SangFilm,                    ".
                                    "       Sum(Score) As SumOfScore     ".
                                    "  From wrk_sangdae                  ".
                                    " Where WorkDate >= '".$FromDate."'  ".
                                    "   And WorkDate <= '".$ToDate."'    ".
                                    "   And Location = '".$ZoneLoc."'    ".
                                    " Group By SangFilm                  ".
                                    " Order By SumOfScore desc           " ;
                          $QrySangFilm = mysql_query($sQuery,$connect) ;
                     }
                     if   (strlen($ZoneLoc) == 2)  // ����
                     {
                          $sQuery = "Select Location From bas_filmsupplyzoneloc ".
                                    " Where Zone = '".$ZoneLoc."'               " ;
                          $QryZoneloc = mysql_query($sQuery,$connect) ;

                          // �ش� ������ ��� �������� ���Ѵ�.
                          $AddedLoc = " and " ;

                          while ($ArrZoneloc = mysql_fetch_array($QryZoneloc))
                          {            
                               if  ($AddedLoc == " and ")
                                   $AddedLoc .= "( Location = '".$ArrZoneloc["Location"]."' "  ;
                               else
                                   $AddedLoc .= " or Location = '".$ArrZoneloc["Location"]."' "  ;
                          }
                          $AddedLoc .= ")" ;                     

                          // ��ȭ����Ʈ (����)
                          $sQuery = "Select SangFilm,                    ".
                                    "       Sum(Score) As SumOfScore     ".
                                    "  From wrk_sangdae                  ".
                                    " Where WorkDate >= '".$FromDate."'  ".
                                    "   And WorkDate <= '".$ToDate."'    ".
                                    $AddedLoc                             .
                                    " Group By SangFilm                  ".
                                    " Order By SumOfScore desc           " ;
                          $QrySangFilm = mysql_query($sQuery,$connect) ;
                     }
                }

                $Rankiong = 0 ;
                
                while ($ArrSangFilm = mysql_fetch_array($QrySangFilm))
                {                          
                     
                     $SangFilmCode = $ArrSangFilm["SangFilm"] ; 

                     // ��ȭ���� ����..
                     $sQuery = "Select * From bas_sangfilmtitle   ".
                               " Where Code = '".$SangFilmCode."' " ;
                     $QryIndyfilmtitle = mysql_query($sQuery,$connect) ;
                     if  ($ArrIndyfilmtitle = mysql_fetch_array($QryIndyfilmtitle)) // ��ȭ���� �ִ°Ǹ�..
                     {
                         $indyfilmtitleName = $ArrIndyfilmtitle["Name"] ; // ��ȭ��
                         ?>
                         <TR height=30>                     
                         <?                              
                         

                         if  ($Color == "#c0c0c0") 
                         {
                             $Color =  "#dcdcdc" ;
                         }
                         else
                         {
                             $Color =  "#c0c0c0" ;
                         }
                             
                         $Rankiong ++ ;
                         ?>
                           <TD class=textarea bgcolor=<?=$Color?> align=center><?=$Rankiong?></TD>
                           <TD class=textarea bgcolor=<?=$Color?> align=center><?=$indyfilmtitleName?></TD>
                         <?


                         $TotSumOfScore = 0 ;

                         $temp = mktime(0,0,0,substr($FromDate,4,2),substr($FromDate,6,2),substr($FromDate,0,4));

                         for ($i=0 ; $i<=$dur_day ; $i++)
                         {                            
                              $objDate = date("Ymd",$temp + ($i * 86400)) ;   

                              $sQuery = "Select Sum(Score) As SumOfScore           ".
                                        "  From wrk_sangdae                        ".
                                        " Where SangFilm = '".$SangFilmCode."'     ".
                                        "   And WorkDate = '".$objDate."'          " ;
                              $QrySumScore = mysql_query($sQuery,$connect) ;
                              if   ($ArrSumScore = mysql_fetch_array($QrySumScore)) 
                              {
                                   $SumOfScore = $ArrSumScore["SumOfScore"] ;
                                   $TotSumOfScore += $SumOfScore ;
                                   
                                   if   ($SumOfScore<>0)
                                   {
                                        ?>
                                        <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?><?=number_format($SumOfScore)?><?=$NBSP?></TD>
                                        <?
                                   }
                                   else
                                   {
                                        ?>
                                        <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?></TD>
                                        <?                                 
                                   }
                              }
                              else
                              {
                              ?>
                              <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?></TD>
                              <?
                              }
                         }
                         ?>
                         
                              <!-- �հ� -->
                              <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?><?=number_format($TotSumOfScore)?><?=$NBSP?></TD>
                         <?
                              $sQuery = "Select Sum(Score) As SumOfScore           ".
                                        "  From wrk_sangdae                        ".
                                        " Where SangFilm = '".$SangFilmCode."'     " ;
                              $QrySumScore = mysql_query($sQuery,$connect) ;
                              if   ($ArrSumScore = mysql_fetch_array($QrySumScore)) 
                              {
                         ?>
                              <!-- �Ѵ��� -->
                              <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?><?=number_format($ArrSumScore["SumOfScore"])?><?=$NBSP?></TD>
                         <?
                              } 
                         ?>
                         <?
                              $sQuery = "Select Sum(Score) As SumOfScore           ".
                                        "  From wrk_sangdae                        ".
                                        " Where WorkDate >= '".$FromDate."'        ".
                                        "   And WorkDate <= '".$ToDate."'          " ;
                              $QrySumScore = mysql_query($sQuery,$connect) ;
                              if   ($ArrSumScore = mysql_fetch_array($QrySumScore)) 
                              {
                         ?>
                              <!-- �Ѵ��� -->
                              <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?><?=number_format($TotSumOfScore / $ArrSumScore["SumOfScore"] * 100)?><?=$NBSP?></TD>
                         <?
                              } 
                         ?>
                         </TR>
                         <?

                     }
                }                 
                
           }

           if  ($PrnKind == "Date" ) // ���ں�(��ȭ)
           {
                $OldSangTheatherCode = '' ;

                // ����,�����,��ũ��,�¼���,��ȭ��,��ȸ��¥, �հ�
                
                if   (($ZoneLoc == "9999") || ($ZoneLoc == "0")) // ��ü
                {
                     // ��ȭ����Ʈ (��ü)
                     $sQuery = "Select SangFilm,                   ".
                               "       Sum(Score) As SumOfScore    ".
                               "  From wrk_sangdae                 ".
                               " Where WorkDate = '".$CurrDate."'  ".
                               " Group By SangFilm                 ".
                               " Order By SumOfScore desc          " ;
                     $QrySangFilm = mysql_query($sQuery,$connect) ;
                }
                else
                {
                     if   (strlen($ZoneLoc) == 3) // ����
                     {
                          // ��ȭ����Ʈ (����)
                          $sQuery = "Select SangFilm,                    ".
                                    "       Sum(Score) As SumOfScore     ".
                                    "  From wrk_sangdae                  ".
                                    " Where WorkDate = '".$CurrDate."'   ".
                                    "   And Location = '".$ZoneLoc."'    ".
                                    " Group By SangFilm                  ".
                                    " Order By SumOfScore desc           " ;
                          $QrySangFilm = mysql_query($sQuery,$connect) ;
                     }
                     if   (strlen($ZoneLoc) == 2)  // ����
                     {
                          $sQuery = "Select Location                  ".
                                    "  From bas_filmsupplyzoneloc     ".
                                    " Where Zone = '".$ZoneLoc."'     " ;
                          $QryZoneloc = mysql_query($sQuery,$connect) ;

                          // �ش� ������ ��� �������� ���Ѵ�.
                          $AddedLoc = " and " ;

                          while ($ArrZoneloc = mysql_fetch_array($QryZoneloc))
                          {            
                               if  ($AddedLoc == " and ")
                                   $AddedLoc .= "( Location = '".$ArrZoneloc["Location"]."' "  ;
                               else
                                   $AddedLoc .= " or Location = '".$ArrZoneloc["Location"]."' "  ;
                          }
                          $AddedLoc .= ")" ;                     

                          // ��ȭ����Ʈ (����)
                          $sQuery = "Select SangFilm,                    ".
                                    "       Sum(Score) As SumOfScore     ".
                                    "  From wrk_sangdae                  ".
                                    " Where WorkDate = '".$CurrDate."'   ".
                                    $AddedLoc                             .
                                    " Group By SangFilm                  ".
                                    " Order By SumOfScore desc           " ;
                          $QrySangFilm = mysql_query($sQuery,$connect) ;
                     }
                }

                $Rankiong = 0 ;
                
                while ($ArrSangFilm = mysql_fetch_array($QrySangFilm))
                {                          
                                                   
                     $SangFilmCode = $ArrSangFilm["SangFilm"] ; 

                     // ��ȭ���� ����..
                     $sQuery = "Select * From bas_sangfilmtitle   ".
                               " Where Code = '".$SangFilmCode."' " ;
                     $QryIndyfilmtitle = mysql_query($sQuery,$connect) ;
                     if  ($ArrIndyfilmtitle = mysql_fetch_array($QryIndyfilmtitle))
                     {
                         ?>
                         <TR height=30>                     
                         <?
                         
                         $indyfilmtitleName = $ArrIndyfilmtitle["Name"] ;

                         if  ($Color == "#c0c0c0") 
                         {
                             $Color =  "#dcdcdc" ;
                         }
                         else
                         {
                             $Color =  "#c0c0c0" ;
                         }
                             
                         $Rankiong ++ ;
                         ?>
                           <TD class=textarea bgcolor=<?=$Color?> align=center><?=$Rankiong?></TD>
                           <TD class=textarea bgcolor=<?=$Color?> align=center><?=$indyfilmtitleName?></TD>
                         <?


                         $TotSumOfScore = 0 ;

                         $temp = mktime(0,0,0,substr($CurrDate,4,2),substr($CurrDate,6,2),substr($CurrDate,0,4));

                         for ($i=0 ; $i<=$dur_day ; $i++)
                         {                            
                              $objDate = date("Ymd",$temp + ($i * 86400)) ;   
                              
                              $sQuery = "Select Sum(Score) As SumOfScore           ".
                                        "  From wrk_sangdae                        ".
                                        " Where SangFilm = '".$SangFilmCode."'     ".
                                        "   And WorkDate = '".$objDate."'          " ;
                              $QrySumScore = mysql_query($sQuery,$connect) ;
                              if   ($ArrSumScore = mysql_fetch_array($QrySumScore)) 
                              {
                                   $SumOfScore = $ArrSumScore["SumOfScore"] ;
                                   $TotSumOfScore += $SumOfScore ;
                                   
                                   if   ($SumOfScore<>0)
                                   {
                                        ?>
                                        <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?><?=number_format($SumOfScore)?><?=$NBSP?></TD>
                                        <?
                                   }
                                   else
                                   {
                                        ?>
                                        <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?></TD>
                                        <?                                 
                                   }
                              }
                              else
                              {
                              ?>
                              <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?></TD>
                              <?
                              }
                         }
                         ?>

                         <!-- �հ� -->
                         <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?><?=number_format($TotSumOfScore)?><?=$NBSP?></TD>
                         <?
                              $sQuery = "Select Sum(Score) As SumOfScore           ".
                                        "  From wrk_sangdae                        ".
                                        " Where SangFilm = '".$SangFilmCode."'     " ;
                              $QrySumScore = mysql_query($sQuery,$connect) ;
                              if   ($ArrSumScore = mysql_fetch_array($QrySumScore)) 
                              {
                         ?>
                              <!-- �Ѵ��� -->
                              <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?><?=number_format($ArrSumScore["SumOfScore"])?><?=$NBSP?></TD>
                         <?
                              } 
                         ?>
                         <?
                              $sQuery = "Select Sum(Score) As SumOfScore     ".
                                        "  From wrk_sangdae                  ".
                                        " Where WorkDate = '".$CurrDate."'   " ;
                              $QrySumScore = mysql_query($sQuery,$connect) ;
                              if   ($ArrSumScore = mysql_fetch_array($QrySumScore)) 
                              {
                         ?>
                              <!-- �Ѵ��� -->
                              <TD class=textarea bgcolor=<?=$Color?> align=center><?=$NBSP?><?=number_format($TotSumOfScore / $ArrSumScore["SumOfScore"] * 100)?><?=$NBSP?></TD>
                         <?
                              } 
                         ?>
                         </TR>
                         <?
                     }

                     
                     
                     
                }
           }
           ?>
           </TABLE>      
      <?
      } 
      ?>


      <br>
      <br>






      <center> 










      <?
      if ($ToExel != "True")
      {
      ?>




      <?      
      if  ($PrnKind == "Term") // �Ⱓ�� 
      {
          ?>
          <!-- ############### As Js ������ ���� �κ� [����] ############# -->
          <!-- ########## Flex Builder 2�� ���۰� �ڵ����� �����ϴ� ��ũ��Ʈ [����] ######## -->
          <script language="JavaScript" type="text/javascript" src="history.js"></script>

          <script language="JavaScript" type="text/javascript">
          <!--
              // Version check for the Flash Player that has the ability to start Player Product Install (6.0r65)
              var hasProductInstall = DetectFlashVer(6, 0, 65);

              // Version check based upon the values defined in globals
              var hasRequestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);


              // Check to see if a player with Flash Product Install is available and the version does not meet the requirements for playback
              if  ( hasProductInstall && !hasRequestedVersion ) 
              {
                  // MMdoctitle is the stored document.title value used by the installation process to close the window that started the process
                  // This is necessary in order to close browser windows that are still utilizing the older version of the player after installation has completed
                  // DO NOT MODIFY THE FOLLOWING FOUR LINES
                  // Location visited after installation is complete if installation is required
                  var MMPlayerType = (isIE == true) ? "ActiveX" : "PlugIn";
                  var MMredirectURL = window.location;
                  
                  document.title = document.title.slice(0, 47) + " - Flash Player Installation";
                  var MMdoctitle = document.title;

                  AC_FL_RunContent(
                   "src", "playerProductInstall",
                   "FlashVars", 
                   "MMredirectURL="+MMredirectURL+'&MMplayerType='+MMPlayerType+'&MMdoctitle='+MMdoctitle+"",
                   "width", "900",
                   "height", "364",
                   "align", "middle",
                   "id", "Flx_SangdaeTerm",
                   "quality", "high",
                   "bgcolor", "#869ca7",
                   "name", "Flx_SangdaeTerm",
                   "allowScriptAccess","sameDomain",
                   "type", "application/x-shockwave-flash",
                   "pluginspage", "http://www.adobe.com/go/getflashplayer"
                    );
              } 
              else if (hasRequestedVersion) 
              {
                  // if we've detected an acceptable version
                  // embed the Flash Content SWF when all tests are passed
                  AC_FL_RunContent(
                    "src", "Flx_SangdaeTerm",
                    "width", "900",
                    "height", "364",
                    "align", "middle",
                    "id", "Flx_SangdaeTerm",
                    "quality", "high",
                    "bgcolor", "#869ca7",
                    "name", "Flx_SangdaeTerm",
                    "flashvars",'historyUrl=history.htm%3F&lconid=' + lc_id + '',
                    "allowScriptAccess","sameDomain",
                    "type", "application/x-shockwave-flash",
                    "pluginspage", "http://www.adobe.com/go/getflashplayer"
                  );
              } 
              else 
              {  // flash is too old or we can't detect the plugin
                  var alternateContent = 'Alternate HTML content should be placed here. '
                                       + 'This content requires the Adobe Flash Player. '
                                       + '<a href=http://www.adobe.com/go/getflash/>Get Flash</a>';
                  document.write(alternateContent);  // insert non-flash content
              }
          // -->
          </script>   
          

          <noscript>
               <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                id="Flx_SangdaeTerm" width="900" height="364"
                codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
                <param name="movie" value="Flx_SangdaeTerm.swf" />
                <param name="quality" value="high" />
                <param name="bgcolor" value="#869ca7" />
                <param name="allowScriptAccess" value="sameDomain" />
                <embed src="Flx_SangdaeTerm.swf" 
                         quality="high" bgcolor="#869ca7"
                         width="900" height="364" name="Flx_SangdaeTerm" align="middle"
                         play="true"
                         loop="false"
                         quality="high"
                         allowScriptAccess="sameDomain"
                         type="application/x-shockwave-flash"
                         pluginspage="http://www.adobe.com/go/getflashplayer">
                </embed>
              </object>
          </noscript>

          <iframe name='_history' src='history.htm' frameborder='0' scrolling='yes' width='22' height='0'></iframe>



          </center>
          <br>
          

          <script language="JavaScript" type="text/javascript">
          <!--
              // Flexȣ�� ..
              function putFlex()
              {
                  if  ( FABridge.flash )
                  {
                      var flexApp = FABridge.flash.root(); // �׼ǽ�ũ��Ʈ ���	

                      flexApp.FetchData("20003","<?=$FromDate?>","<?=$ToDate?>","<?=$ZoneLoc?>") ; // �׼ǽ�ũ��Ʈ ȣ�� 
                  }
              }

              count = 0 ;

              function setIntervalMethod()
              {
                 if  ( count < 3 )
                 {
                     count++ ;
                 }
                 else
                 {
                     clearInterval(timerId);
                     putFlex() ;   // 3 ���Ŀ� Flex�� �����Ѵ�..
                 }
              }
              timerId=setInterval(setIntervalMethod, 1000);        
          // -->
          </script>   

          <?
      }

      if  ($PrnKind == "Date") // ���ں�
      {          
          ?>
          <!-- ############### As Js ������ ���� �κ� [����] ############# -->
          <!-- ########## Flex Builder 2�� ���۰� �ڵ����� �����ϴ� ��ũ��Ʈ [����] ######## -->
          <script language="JavaScript" type="text/javascript" src="history.js"></script>

          <script language="JavaScript" type="text/javascript">
          <!--
              // Version check for the Flash Player that has the ability to start Player Product Install (6.0r65)
              var hasProductInstall = DetectFlashVer(6, 0, 65);

              // Version check based upon the values defined in globals
              var hasRequestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);


              // Check to see if a player with Flash Product Install is available and the version does not meet the requirements for playback
              if  ( hasProductInstall && !hasRequestedVersion ) 
              {
                  // MMdoctitle is the stored document.title value used by the installation process to close the window that started the process
                  // This is necessary in order to close browser windows that are still utilizing the older version of the player after installation has completed
                  // DO NOT MODIFY THE FOLLOWING FOUR LINES
                  // Location visited after installation is complete if installation is required
                  var MMPlayerType = (isIE == true) ? "ActiveX" : "PlugIn";
                  var MMredirectURL = window.location;
                  
                  document.title = document.title.slice(0, 47) + " - Flash Player Installation";
                  var MMdoctitle = document.title;

                  AC_FL_RunContent(
                   "src", "playerProductInstall",
                   "FlashVars", 
                   "MMredirectURL="+MMredirectURL+'&MMplayerType='+MMPlayerType+'&MMdoctitle='+MMdoctitle+"",
                   "width", "900",
                   "height", "490",
                   "align", "middle",
                   "id", "Flx_SangdaeDate",
                   "quality", "high",
                   "bgcolor", "#869ca7",
                   "name", "Flx_SangdaeDate",
                   "allowScriptAccess","sameDomain",
                   "type", "application/x-shockwave-flash",
                   "pluginspage", "http://www.adobe.com/go/getflashplayer"
                    );
              } 
              else if (hasRequestedVersion) 
              {
                  // if we've detected an acceptable version
                  // embed the Flash Content SWF when all tests are passed
                  AC_FL_RunContent(
                    "src", "Flx_SangdaeDate",
                    "width", "900",
                    "height", "490",
                    "align", "middle",
                    "id", "Flx_SangdaeDate",
                    "quality", "high",
                    "bgcolor", "#869ca7",
                    "name", "Flx_SangdaeDate",
                    "flashvars",'historyUrl=history.htm%3F&lconid=' + lc_id + '',
                    "allowScriptAccess","sameDomain",
                    "type", "application/x-shockwave-flash",
                    "pluginspage", "http://www.adobe.com/go/getflashplayer"
                  );
              } 
              else 
              {  // flash is too old or we can't detect the plugin
                  var alternateContent = 'Alternate HTML content should be placed here. '
                                       + 'This content requires the Adobe Flash Player. '
                                       + '<a href=http://www.adobe.com/go/getflash/>Get Flash</a>';
                  document.write(alternateContent);  // insert non-flash content
              }
          // -->
          </script>   
          

          <noscript>
               <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                id="Flx_SangdaeDate" width="900" height="490"
                codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
                <param name="movie" value="Flx_SangdaeDate.swf" />
                <param name="quality" value="high" />
                <param name="bgcolor" value="#869ca7" />
                <param name="allowScriptAccess" value="sameDomain" />
                <embed src="Flx_SangdaeDate.swf" 
                         quality="high" bgcolor="#869ca7"
                         width="900" height="490" name="Flx_SangdaeDate" align="middle"
                         play="true"
                         loop="false"
                         quality="high"
                         allowScriptAccess="sameDomain"
                         type="application/x-shockwave-flash"
                         pluginspage="http://www.adobe.com/go/getflashplayer">
                </embed>
              </object>
          </noscript>

          <iframe name='_history' src='history.htm' frameborder='0' scrolling='yes' width='22' height='0'></iframe>



          </center>
          <br>
          

          <script language="JavaScript" type="text/javascript">
          <!--
              // Flexȣ�� ..
              

              function putFlex()
              {                  
                  if  ( FABridge.flash )
                  {
                      var flexApp = FABridge.flash.root(); // �׼ǽ�ũ��Ʈ ���	
                              
                      flexApp.FetchData("20003","<?=$CurrDate?>","<?=$ZoneLoc?>") ; // �׼ǽ�ũ��Ʈ ȣ�� 
                  }
              }

              count = 0 ;

              function setIntervalMethod()
              {
                 if  ( count < 3 )
                 {
                     count++ ;
                 }
                 else
                 {
                     clearInterval(timerId);
                     putFlex() ;   // 3 ���Ŀ� Flex�� �����Ѵ�..
                 }
              }
              timerId=setInterval(setIntervalMethod, 1000);        
          // -->
          </script>   
          <?
      }
      ?>


      <?
      }
      ?>



      <br>

</center>

</body>

        <?
        mysql_close($connect);
    }
    else // �α������� �ʰ� �ٷε��´ٸ�..
    {
        ?>
        
        <!-- �α������� �ʰ� �ٷε��´ٸ� -->
        <body>
            <script language="JavaScript">
                <!-- 
                window.top.location = '../index_cokr.php' ; 
                //-->
            </script>
        </body>      
        
        <?
    }
    ?>
</html>
