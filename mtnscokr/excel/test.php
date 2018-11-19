<?
 require_once 'Excel/reader.php';
 $data = new Spreadsheet_Excel_Reader();
 $data->setOutputEncoding('UTF-8');
 $data->read('etcList.xls');
 error_reporting(E_ALL ^ E_NOTICE);

?>
<style type="text/css">
body {background-color: #ffffff; color: #000000;}
body, td, th, h1, h2 {font-family: sans-serif;}

table {border-collapse: collapse;}
.center {text-align: center;}
.center table { margin-left: auto; margin-right: auto; text-align: left;}
.center th { text-align: center !important; }
td, th { border: 1px solid #000000; font-size: 75%; vertical-align: baseline;}
</style>
<TABLE border="0" cellpadding="3" >
<?
 for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++)
 {
     ?><TR><?
     for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++)
     {
         echo "<TD>".$data->sheets[0]['cells'][$i][$j]."</TD>";
     }
     ?><TR><?
     //echo "<br>\n";
}

?>
</TABLE>