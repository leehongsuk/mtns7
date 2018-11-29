<html>

<link rel=stylesheet href=./LinkStyle.css type=text/css>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=euc-kr">

<head>
    
    <title>전국총괄(자동)</title>


    <script src="AC_OETags.js" language="javascript"></script>

    <script language="JavaScript" type="text/javascript">
    <!--
        var requiredMajorVersion = 9 ; // Major version of Flash required
        var requiredMinorVersion = 0 ;
        var requiredRevision     = 0 ;
    //-->
    </script>

    <!-- FABridge.js 인클루드 -->
    <script src="bridge/FABridge.js"></script>

    <script language="JavaScript" type="text/javascript">
    <!--
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
               putFlex() ;   // 3 초후에 Flex를 구동한다..
           }
        }
        timerId=setInterval(setIntervalMethod, 1000);        
    // -->
    </script>   
	
</head>





<body BGCOLOR=#fafafa topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>




   <!-- <input type=button name=ok value="확인" onclick="putFlex()"> -->

   <br>
   <center>
   


   <!-- ############### As Js 연동을 위한 부분 [종료] ############# -->
   <!-- ########## Flex Builder 2의 래퍼가 자동으로 생성하는 스크립트 [시작] ######## -->
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
            "width", "925",
            "height", "2702",
            "align", "middle",
            "id", "Flx_TotalInfo",
            "quality", "high",
            "bgcolor", "#869ca7",
            "name", "Flx_TotalInfo",
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
             "src", "Flx_TotalInfo",
             "width", "925",
             "height", "2702",
             "align", "middle",
             "id", "Flx_TotalInfo",
             "quality", "high",
             "bgcolor", "#869ca7",
             "name", "Flx_TotalInfo",
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
         id="Flx_TotalInfo" width="925" height="2702"
         codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
         <param name="movie" value="Flx_TotalInfo.swf" />
         <param name="quality" value="high" />
         <param name="bgcolor" value="#869ca7" />
         <param name="allowScriptAccess" value="sameDomain" />
         <embed src="Flx_TotalInfo.swf" 
                  quality="high" bgcolor="#869ca7"
                  width="925" height="2702" name="Flx_TotalInfo" align="middle"
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
       // Flex호출 ..
       function putFlex()
       {
           if  ( FABridge.flash )
           {
               var flexApp = FABridge.flash.root(); // 액션스크립트 사용	

               flexApp.TimerStart("<?=$WorkDate?>","<?=$FilmTitle?>") ; // 액션스크립트 호출 
           }
       }
   // -->
   </script>   
</body>

</html>
