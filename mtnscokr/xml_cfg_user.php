<?
    $xmlObj = new xmlWriter();
    $xmlObj->openMemory();
    
    $xmlObj->startDocument('1.0','euc-kr');
    $xmlObj->startElement ('DataSets'); 
    


    include "config.php";        // {[데이터 베이스]} : 환경설정
                    
    $connect = dbconn() ;        // {[데이터 베이스]} : 연결

    mysql_select_db($cont_db) ;  // {[데이터 베이스]} : 디비선택

        $xmlObj->startElement('DataSet'); 
        $xmlObj->writeAttribute('name', "cfg_user") ;

            $xmlObj->startElement('Schema'); 

            $xmlObj->startElement('Fields'); 
                
                $xmlObj->startElement('Field'); 
                $xmlObj->writeAttribute('name',    "UserId") ;
                $xmlObj->writeAttribute('type',    "ftString") ;
                $xmlObj->writeAttribute('size',     "20") ;
                $xmlObj->writeAttribute('required', "True") ;
                $xmlObj->endElement(); 

                $xmlObj->startElement('Field'); 
                $xmlObj->writeAttribute('name',     "UserPw") ;
                $xmlObj->writeAttribute('type',     "ftString") ;
                $xmlObj->writeAttribute('size',     "20") ;
                $xmlObj->writeAttribute('required', "True") ;
                $xmlObj->endElement(); 
                
                $xmlObj->startElement('Field'); 
                $xmlObj->writeAttribute('name',     "Name") ;
                $xmlObj->writeAttribute('type',     "ftString") ;
                $xmlObj->writeAttribute('size',     "20") ;
                $xmlObj->writeAttribute('required', "True") ;
                $xmlObj->endElement(); 

                $xmlObj->startElement('Field'); 
                $xmlObj->writeAttribute('name',     "eMail") ;
                $xmlObj->writeAttribute('type',     "ftString") ;
                $xmlObj->writeAttribute('size',     "255") ;
                $xmlObj->writeAttribute('required', "False") ;
                $xmlObj->endElement(); 
                
                $xmlObj->startElement('Field'); 
                $xmlObj->writeAttribute('name',     "Homepage") ;
                $xmlObj->writeAttribute('type',     "ftString") ;
                $xmlObj->writeAttribute('size',     "255") ;
                $xmlObj->writeAttribute('required', "False") ;
                $xmlObj->endElement(); 

                $xmlObj->startElement('Field'); 
                $xmlObj->writeAttribute('name',     "Jumin") ;
                $xmlObj->writeAttribute('type',     "ftString") ;
                $xmlObj->writeAttribute('size',     "14") ;
                $xmlObj->writeAttribute('required', "False") ;
                $xmlObj->endElement(); 
                
                $xmlObj->startElement('Field'); 
                $xmlObj->writeAttribute('name',     "Discript") ;
                $xmlObj->writeAttribute('type',     "ftMemo") ;
                $xmlObj->writeAttribute('size',     "0") ;
                $xmlObj->writeAttribute('required', "False") ;
                $xmlObj->endElement(); 

            $xmlObj->endElement(); 

            $xmlObj->startElement('Primary'); 
            $xmlObj->writeAttribute('fields', "UserId") ;
            $xmlObj->endElement(); 

            $xmlObj->startElement('Sencond'); 
            $xmlObj->writeAttribute('name',   "Idx_Name") ;
            $xmlObj->writeAttribute('fields', "Name") ;
            $xmlObj->endElement(); 

            $CntUser = 0 ;
            $sQuery = "Select Count(*) As CntUser ".
                      "  From cfg_user            " ;  
            $QryCntUser = mysql_query($sQuery,$connect) ;
            if  ($ArrCntUser = mysql_fetch_array($QryCntUser))
            { 
                 $CntUser = $ArrCntUser["CntUser"] ;
            }

            $Total = round($CntUser / $PageUnit) ;
            if  ($Page > $Total)
            {
                $Page = $Total ;
            }
            
            $xmlObj->startElement('Page'); 
            $xmlObj->writeAttribute('unit',    $PageUnit) ;
            $xmlObj->writeAttribute('current', $Page) ;
            $xmlObj->writeAttribute('total',   $Total) ;
            $xmlObj->endElement(); 

            $xmlObj->endElement(); 



            $xmlObj->startElement('Records'); 

            //PageUnit=20
            //Page=1' ;
            
            if  ($PageUnit) 
            {
                $sQuery = "Select * From cfg_user           ".
                          " Limit ".($Page-1)*$PageUnit.",  ".
                          "       ".$PageUnit."             " ;
            }
            else
            {
                $sQuery = "Select * From cfg_user   " ;  
            }
            $QryUser = mysql_query($sQuery,$connect) ;
            while  ($ArrUser = mysql_fetch_array($QryUser))
            {            
                $UserId     = xml_convert($ArrUser["UserId"],"\|") ;
                $UserPw     = xml_convert($ArrUser["UserPw"],"\|") ;
                $Name       = xml_convert($ArrUser["Name"],"\|") ;
                $eMail      = xml_convert($ArrUser["eMail"],"\|") ;
                $Homepage   = xml_convert($ArrUser["Homepage"],"\|") ;
                $Jumin      = xml_convert($ArrUser["Jumin"],"\|") ;
                $Discript   = xml_convert($ArrUser["Discript"],"\|") ;
                                                       
                $Record = $UserId."|".$UserPw."|".$Name."|".$eMail."|".$Homepage."|".$Jumin."|".$Discript  ;

                $xmlObj->writeElement('Record', $Record) ;
            }        
            
            $xmlObj->endElement(); 


        $xmlObj->endElement(); 

      
    mysql_close($connect);       // {[데이터 베이스]} : 단절


    $xmlObj->endElement(); 
     
    print $xmlObj->outputMemory(true);    
?>                                       

<?
/*
<DataSets>

  <DataSet name=Tbl_LogIn>
      
      <Schema>
          <Fields>
             <Field name=ID     type=ftString  size=10  required=True/>
             <Field name=PassWD type=ftString  size=10  required=True />
             <Field name=Name   type=ftString  size=20  required=True />
             <Field name=Age    type=ftInteger size=0   required=True />
             <Field name=Addr   type=ftString  size=100 required=False />
             <Field name=Tel    type=ftString  size=15  required=False />
             <Field name=Memo   type=ftMemo    size=0   required=False  />
          </Fields>
          <Primary field=ID />
          <Sencond name=Idx_Name field=Name />
          <Page unit=20 current=1 total=10>
      </Schema>
      
      <Records>
          <Record>ID1|PassWord1|이름1|나이1|주소1|전화번호1|</Record>
          <Record>ID2|PassWord2|이름2|나이2|주소2|전화번호2|</Record>
          <Record>ID3|PassWord3|이름3|나이3|주소3|전화번호3|</Record>
          <Record>ID4|PassWord4|이름4|나이4|주소4|전화번호4|</Record>
          <Record>ID5|PassWord5|이름5|나이5|주소5|전화번호5|</Record>
          <Record>ID6|PassWord6|이름6|나이6|주소6|전화번호6|</Record>
          <Record>ID7|PassWord7|이름7|나이7|주소7|전화번호7|</Record>
          <Record>ID8|PassWord8|이름8|나이8|주소8|전화번호8|</Record>
          <Record>ID9|PassWord9|이름9|나이9|주소9|전화번호9|메모</Record>
      </Records>

  </DataSet>

</DataSets>
*/
?>