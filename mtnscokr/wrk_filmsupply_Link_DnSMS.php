<?
    session_start();
?>
<html>
	<head>

        <title>MTNS 문자전송</title>

        <meta http-equiv="Content-Type" content="text/html; charset=euc-kr">

        <style type="text/css">
			<!--
			TD
			{
				FONT-FAMILY: 굴림;
				line-height : 1.2;
				FONT-SIZE: 9pt
			}
			.input {  border-style: solid; font-size: 9pt; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px}
		-->
        </style>

        <script language="JavaScript" type="text/JavaScript">
        <!--



			function MM_preloadImages() //v3.0  // 도대체..
            {
			    var d=document;
                if(d.images)
                {
                    if (!d.MM_p)
                    {
                        d.MM_p = new Array();
				    }

                    var i, j=d.MM_p.length, a=MM_preloadImages.arguments;

                    for(i=0; i<a.length; i++)
                    {
                        if (a[i].indexOf("#")!=0)
                        {
                            d.MM_p[j]=new Image;
                            d.MM_p[j++].src=a[i];
                        }
                    }
                }
			}


            // 수신번호 추가 버튼 핸들러
			function receive_add()
			{
				var intCount = 0;
				var strMobile = document.form1.receive_input.value;  // 수신자번호

                strMobile = strMobile.replace("-", "", strMobile); // 수신자번호 에서 "-" 제거

				// 중복번호 체크
                for (i = 0; i < document.form1.receive_buffer.length; i++)
				{
					if (strMobile == document.form1.receive_buffer.options[i].value) // receive_buffer 에서 비교
					{
						return alert("같은 번호는 재입력 하실수 없습니다"); // return에 유의

      document.form1.receive_buffer.options.remove(i);

						intCount = intCount - 1;

                        document.form1.count.value = intCount ;
						document.form1.receive_input.focus();
					}
				}


                //
				strDigit= "0123456789-";  // 입력허용 문자들...
				intIdLength = strMobile.length;

				var blnChkFlag;

                for (i = 0; i < intIdLength; i++)
				{
					strNumberChar = strMobile.charAt(i);
					blnChkFlag = false;

					for (j = 0; j < strDigit.length ; j++)
					{
						strCompChar = strDigit.charAt(j);



						if (strNumberChar == strCompChar)
						{
							blnChkFlag = true;
						}
					}

					if (blnChkFlag == false)
					{
						break;
					}
				}

				if (strMobile == "" )
				{
					alert ("추가할 수신번호를 입력해 주세요");
				}
				else if (strMobile.length < 10 || strMobile.length > 13 )
				{
					alert ("수신번호는 최대 13자, 최소 10자이내로 입력해 주세요.\n\n 예) 01X-123-4567 또는 01X1234567  ");

                    document.form1.receive_input.value="";
					document.form1.receive_input.focus();
				}
				else if ( !blnChkFlag )
				{
					alert("수신번호는 숫자만 가능합니다.");

                    document.form1.receive_input.value="";
					document.form1.receive_input.focus();
				}
				else
				{
					// 하나 혹은 여러개의 수신 번호가 들어간다.... receive_number:실제적인 수신자번호(들)..
                    document.form1.receive_number.value = document.form1.receive_number.value + document.form1.receive_input.value + "," ;

					add() ; // 실제적인 수신자번호 추가 동작
				}
			}

            // 실제적인 수신자번호 추가 동작
			function add()
			{
					var intCount = document.form1.count.value ;
					var newOpt = document.createElement('OPTION'); // 새로운 아이템을 생성한다.

                    newOpt.text =  document.form1.receive_input.value; // text도 수신자번호
					newOpt.value = document.form1.receive_input.value; // value도 수신자번호
					document.form1.receive_buffer.options.add(newOpt); // 생성된 아이템을 Select에 추가한다.

					document.form1.receive_input.value = "" ; // 등록이 되었으므로 수신자번호는 지운다.

					intCount = intCount + 1 ;  // 카운터 하나 추가...

                    document.form1.count.value = intCount ; // 수신자수 지정

					document.form1.receive_input.focus(); // 수신자번호에 포커스를 가지고 감..
			}



            // 삭제 버튼 핸들러
            function receive_del()
			{
				if (document.form1.receive_buffer.selectedIndex < 0)
				{
					alert ("삭제할 번호를 선택해 주세요");
				}
				else
				{
					var aaa;

					aaa = document.form1.receive_number.value ;
					aaa = aaa.replace(document.form1.receive_buffer.value + ",","");
					document.form1.receive_number.value = aaa ;  // 선택된 수신자번호를 지운다.

					var num ;
					var intCount = document.form1.count.value ;

                    num = document.form1.receive_buffer.selectedIndex ;
					document.form1.receive_buffer.options.remove(num); // select에서 option하나를 지운다.

                    intCount = intCount - 1;    // 카운터 하나 삭제
					document.form1.count.value = intCount ;   // 수신자 수 지정
				}
			}

			// 모두삭제 버튼 핸들러
            function receive_alldel()
			{
                document.form1.receive_number.value = "0" ; //수신자 번호를 0으로 지정

                var intCount = document.form1.count.value ;
                for (i = 0; i < intCount; i++)
                {
                    document.form1.receive_buffer.options.remove(0); // 카운터수대로 차례로 지운다.
                }
                document.form1.count.value = "0" ; //  수신자 수 0 로 지정
			}

        //-->
        </script>
	</head>


	<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <!-- action="SendSMS.php" -->
        <form name="form1"  method=post>

			<input type="hidden" name="biz_id" value="vnmtns">							       <!-- Xonda/Biz ID  -->
			<input type="hidden" name="return_url" value="<?=$PHP_SELF?>">		<!-- sms 전송후 돌아올 URL  -->
			<INPUT type="hidden" name="receive_number" size=20 value="">				<!-- 실제 전송될 번호 -->

			<table width="200" height="300" border="0" cellpadding="0" cellspacing="0" align="center">
				 <tr>
					<td align="center" valign="middle"><table border="0" width="182" border="0" cellspacing="0" cellpadding="0">
						<tr>
						  <td height="184" colspan="3" background="./Image/skinL1_top.gif" align=center><bR><br><br>
						  <textarea  style="background opacity=0;color:#000000; border-width: 0; ooverflow-x:hidden;overflow-y: hidden; font-size: 9pt; " name=sms_contents rows=6 cols="16" ><?=$sms_contents?></textarea></td>
						</tr>
						<tr>
						  <td width="39"><img src="./Image/skinL1_img1.gif" width="39" height="20"></td>
						  <td width="102"><img src="./Image/skinL1_img2.gif" width="102" height="20"></td>
						  <td><img src="./Image/skinL1_img3.gif" width="41" height="20"></td>
						</tr>

						<tr valign="top" >
						  <td height="226" colspan="3" background="./Image/skinL1_under.gif"><table border="0" width="172" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td width="10" height="13"></td>
								<td width="162" height="13"></td>
							  </tr>
							  <tr>
								<td>&nbsp;</td>
								<td align="center"><table width="156" height="37" border="0" cellpadding="0" cellspacing="0">
									<tr>
									  <?
           if  (session_is_registered("logged_UserId"))
           {
           ?>
           <td><input type=image src="./Image/skinL1_btnsnd.gif" width="76" height="30"></td>
           <?
           }
           else
           {
           ?>
           <td><img src="./Image/skinL1_btnsnd.gif" width="76" height="30" onclick="alert('로그아웃 되었읍니다 \n다시 로그인하세요');"></td>
           <?
           }
           ?>
									  <td align="right"><img src="./Image/skinL1_btncnl.gif" width="76" height="30"></td>
									</tr>
								  </table></td>
							  </tr>
							  <tr>
								<td>&nbsp;</td>
								<td><table width="164" border="0" cellspacing="0" cellpadding="0">
									<tr>
									  <td width="10" align="right"><img src="./Image/skinL1_icon.gif" width="8" height="8"></td>
									  <td width="52" align="center" valign="middle">발신번호</td>
									  <td width="102" height="22"> <input name="send_number" type="text" class="input" size="10" value="<?=$send_number?>"></td>
									</tr>
								  </table></td>
							  </tr>
							  <tr>
								<td>&nbsp;</td>
								<td valign="bottom"><table width="164" border="0" cellspacing="0" cellpadding="0">
									<tr>
									  <td width="10" align="right"><img src="./Image/skinL1_icon.gif" width="8" height="8"></td>
									  <td width="52" align="center" valign="middle">수신번호</td>
									  <td width="70"> <input name="receive_input" type="text" class="input" size="10" value="<?=$receive_input?>"></td>
									  <td width="32" height="20" align="center"><a href="javascript:receive_add();"><img src="./Image/skinL1_btnpls.gif" width="27" height="18" border=0></a></td>
									</tr>
								  </table></td>
							  </tr>
							  <tr>
								<td>&nbsp;</td>
								<td><table width="164" border="0" cellspacing="0" cellpadding="0">
									<tr>
									  <td height="22" align="right">&nbsp;</td>
									  <td width="52" align="center" valign="middle"><input type="text" name="count" size="3" class="input" readonly>
										명</td>
									  <td width="102" rowspan="2"><select name="receive_buffer"  size=4 style="font-size: 9pt; border: 0; width = 100 ;" ></select>
									  </td>
									</tr>
									<tr>
									  <td width="10" height="22" align="right">&nbsp;</td>
									  <td align="center" valign="middle"><table width="43" border="0" cellspacing="0" cellpadding="0">
										  <tr>
											<td><a href="javascript:receive_del();"><img src="./Image/skinL1_btndel.gif" width="17" height="32" border=0></a></td>
											<td align="right"><a href="javascript:receive_alldel();"><img src="./Image/skinL1_btnalldel.gif" width="24" height="32" border=0></a></td>
										  </tr>
										</table></td>
									</tr>
								  </table></td>
							  </tr>
							</table>
							<table width="179" border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td height="10"></td>
								<td height="8" ></td>
							  </tr>
							  <tr>
								<td width="12">&nbsp;</td>
								<td width="164" height="25"><select name="reserved_year" style="font-size:8pt">
									<option>2008</option>
									<option>2009</option>
								  </select>년<select name="reserved_month" style="font-size:8pt">
									<option>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
									<option>6</option>
									<option>7</option>
									<option>8</option>
									<option>9</option>
									<option>10</option>
									<option>11</option>
									<option>12</option>
								  </select>월<select name="reserved_day" style="font-size:8pt">
									<option>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
									<option>6</option>
									<option>7</option>
									<option>8</option>
									<option>9</option>
									<option>10</option>
									<option>11</option>
									<option>12</option>
									<option>13</option>
									<option>14</option>
									<option>15</option>
									<option>16</option>
									<option>17</option>
									<option>18</option>
									<option>19</option>
									<option>20</option>
									<option>21</option>
									<option>22</option>
									<option>23</option>
									<option>24</option>
									<option>25</option>
									<option>26</option>
									<option>27</option>
									<option>28</option>
									<option>29</option>
									<option>30</option>
									<option>31</option>
								  </select>일</td>
							  </tr>
							  <tr>
								<td>&nbsp;</td>
								<td height="23"><input type="checkbox" name="reserved_flag" value="true">예약&nbsp;&nbsp;&nbsp; <select name="reserved_hour" style="font-size:8pt">
									<option>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
									<option>6</option>
									<option>7</option>
									<option>8</option>
									<option>9</option>
									<option>10</option>
									<option>11</option>
									<option>12</option>
									<option>13</option>
									<option>14</option>
									<option>15</option>
									<option>16</option>
									<option>17</option>
									<option>18</option>
									<option>19</option>
									<option>20</option>
									<option>21</option>
									<option>22</option>
									<option>23</option>
									<option>24</option>
								  </select>시<select name="reserved_minute" style="font-size:8pt">
									<option>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
									<option>6</option>
									<option>7</option>
									<option>8</option>
									<option>9</option>
									<option>10</option>
									<option>11</option>
									<option>12</option>
									<option>13</option>
									<option>14</option>
									<option>15</option>
									<option>16</option>
									<option>17</option>
									<option>18</option>
									<option>19</option>
									<option>20</option>
									<option>21</option>
									<option>22</option>
									<option>23</option>
									<option>24</option>
									<option>25</option>
									<option>26</option>
									<option>27</option>
									<option>28</option>
									<option>29</option>
									<option>30</option>
									<option>31</option>
									<option>32</option>
									<option>33</option>
									<option>34</option>
									<option>35</option>
									<option>36</option>
									<option>37</option>
									<option>38</option>
									<option>39</option>
									<option>40</option>
									<option>41</option>
									<option>42</option>
									<option>43</option>
									<option>44</option>
									<option>45</option>
									<option>46</option>
									<option>47</option>
									<option>48</option>
									<option>49</option>
									<option>50</option>
									<option>51</option>
									<option>52</option>
									<option>53</option>
									<option>54</option>
									<option>55</option>
									<option>56</option>
									<option>57</option>
									<option>58</option>
									<option>59</option>
									<option>60</option>
								  </select>분</td>
							  </tr>
							</table></td>
						</tr>
					  </table></td>
				  </tr>
				</table>


			 </form>


			  <p>&nbsp;</p>


<?
if  ($receive_input!="")
{
    echo "<script>receive_add();</script>";
}


if  ($return_url!="")
{
    $http = new Http;

    $http->setURL("http://aspdll.xonda.net/smsws/xsmswebservice.asmx/SendSMS");

    //정보 입력
    $http->setParam("biz_id", "xnmtns");
    $http->setParam("password", "0922");
    $http->setParam("DNSName", "mtns7.co.kr");
    $http->setParam("send_number", $send_number);
    $http->setParam("receive_number", $receive_number);
    $http->setParam("sms_contents", $sms_contents);
    $http->setParam("merge_name", "");
    if  ($reserved_flag==true) // 예약전송일경우
    {
        $http->setParam("reserved_Date", $reserved_year.":".$reserved_month.":".$reserved_day.":".$reserved_hour.":".$reserved_minute);
    }
    else
    {
        $http->setParam("reserved_Date", "");
    }
    $http->setParam("userData1", "");
    $http->setParam("userData2", "");
    $http->setParam("userData3", "");

    $Return= $http->send("POST");

    // DOM 객체 및 루트앨리먼트 생성
    $doc = new DOMDocument();
    $doc->loadXML($Return);

    $params = $doc->getElementsByTagName("string");


    foreach ($params as $param)
    {
        $string =  $param -> nodeValue ;
        //echo  $string;
        $tok = strtok($string, ";");
        while ($tok)
        {
           $arr[$i++] = $tok;

           $tok = strtok(";");
        }

        foreach($arr as $itm )
        {
            $first_token  = strtok($itm, ':');
            $second_token = strtok(':');

            $$first_token = $second_token ;

            //echo  $first_token ."/". $second_token."<br>" ;
        }
    }
    //echo $error_msg;
    if   ($error_msg!="")
    {
        echo "<script>alert('".iconv("UTF-8","EUC-KR",$error_msg)."')</script>" ;
    }
    //echo $return_url;

    //echo "<script>document.location='".$return_url."'</script>" ;
}

/**
* HTTP 소켓 클래스
*/
class Http
{
    var $host;
    var $port;
    var $path;
    var $cookie;
    var $variable;
    var $referer;
    var $_header;
    var $auth;
    var $debug;
    var $query;

    # constructor
    function Http($url="")
    {
        $this->port = 80;
        if($url) $this->setURL($url);
    }

    /**
     * URL 지정함수
     *
     * @param string $url : URL
     * @return boolean
     */
    function setURL($url)
    {
        if(!$m = parse_url($url)) return $this->setError("파싱이 불가능한 URL입니다.");
        if($m['scheme'] != "http") return $this->setError("HTTP URL이 아닙니다.");

        $this->host = $m['host'];
        $this->port = ($m['port']) ? $m['port'] : 80;
        $this->path = ($m['path']) ? $m['path'] : "/";
        if($m['query'])
        {
            $arr1 = explode("&", $m['query']);
            foreach($arr1 as $value)
            {
                $arr2 = explode("=", $value);
                $this->setParam($arr2[0], $arr2[1]);
            }
        }
        if($m['user'] && $m['pass']) $this->setAuth($m['user'], $m['pass']);
        return true;
    }

    /**
     * 변수값을 지정한다.
     *
     * @param string $key : 변수명, 배열로도 넣을수 있다.
     * @param string $value : 변수값
     */
    function setParam($key, $value="")
    {
        if(is_array($key)) foreach($key as $k => $v) $this->variable[$k] = $v;
        else $this->variable[$key] = $value;
    }

    /**
     * Referer를 지정한다.
     *
     * @param string $referer : Referer
     */
    function setReferer($referer)
    {
        $this->referer = $referer;
    }

    /**
     * 쿠키를 지정한다.
     *
     * @param string $key : 쿠키변수명, 배열로도 넣을수 있다.
     * @param string $value : 쿠키변수값
     */
    function setCookie($key, $value="")
    {
        if(is_array($key)) foreach($key as $k => $v) $this->cookie .= "; $k=$v";
        else $this->cookie .= "; $key=$value";

        if(substr($this->cookie, 0, 1) == ";") $this->cookie = substr($this->cookie, 2);
    }

    /**
     * 인증설정함수
     *
     * @param string $id : 아이디
     * @param string $pass : 패스워드
     */
    function setAuth($id, $pass)
    {
        $this->auth = base64_encode($id.":".$pass);
    }

    /**
     * POST 방식의 헤더구성함수
     *
     * @return string
     */
    function postMethod()
    {
        if(is_array($this->variable))
        {
            $parameter = "\r\n";
            foreach($this->variable as $key => $val)
            {
                    $parameter .= trim($key)."=".urlencode(trim($val))."&";
            }
            $parameter .= "\r\n";
        }
        $query .= "POST ".$this->path." HTTP/1.0\r\n";
        $query .= "Host: ".$this->host."\r\n";
        if($this->auth) $query .= "Authorization: Basic ".$this->auth."\r\n";
        if($this->referer) $query .= "Referer: ".$this->referer."\r\n";
        if($this->cookie) $query .= "Cookie: ".$this->cookie."\r\n";
        $query .= "User-agent: PHP/HTTP_CLASS\r\n";
        $query .= "Content-type: application/x-www-form-urlencoded\r\n";
        $query .= "Content-length: ".strlen($parameter)."\r\n";
        if($parameter) $query .= $parameter;
        $query .= "\r\n";
        return $query;
    }

    /**
     * GET 방식의 헤더구성함수
     *
     * @return string
     */
    function getMethod()
    {
        if(is_array($this->variable))
        {
            $parameter = "?";
            foreach($this->variable as $key => $val)
            {
                    $parameter .= trim($key)."=".urlencode(trim($val))."&";
            }
            //$parameter = substr($parameter, 0, -1);
        }
        $query = "GET ".$this->path.$parameter." HTTP/1.0\r\n";
        $query .= "Host: ".$this->host."\r\n";
        if($this->auth) $query .= "Authorization: Basic ".$this->auth."\r\n";
        if($this->referer) $query .= "Referer: ".$this->referer."\r\n";
        if($this->cookie) $query .= "Cookie: ".$this->cookie."\r\n";
        $query .= "User-agent: PHP/HTTP_CLASS\r\n";
        $query .= "\r\n";
        return $query;
    }

    /**
     * 데이타 전송함수
     *
     * @param string $mode : POST, GET 중 하나를 입력한다.
     * @return string
     */
    function send($mode="GET")
    {

        // 웹서버에 접속한다.
        $fp = fsockopen($this->host, $this->port, $errno, $errstr, 10);
        if(!$fp) return $this->setError($this->host."로의 접속에 실패했습니다.");

        // GET, POST 방식에 따라 헤더를 다르게 구성한다.
        if(strtoupper($mode) == "POST") $this->query = $this->postMethod();
        else $this->query = $this->getMethod();

        fputs($fp,$this->query);

        // 헤더 부분을 구한다.
        $this->_header = ""; // 헤더의 내용을 초기화 한다.
        while(trim($buffer = fgets($fp,1024)) != "")
        {
            $this->_header .= $buffer;
        }

        // 바디 부분을 구한다.
        while(!feof($fp))
        {
            $body .= fgets($fp,1024);
        }

        // 접속을 해제한다.
        fclose($fp);

        return $body;
    }

    /**
     * 헤더를 구하는 함수
     *
     * @return string
     */
    function getHeader()
    {
        return $this->_header;
    }

    /**
     * 쿠키값을 구하는 함수
     *
     * @param string $key : 쿠키변수
     * @return string or array
     */
    function getCookie($key="")
    {
        if($key)
        {
            $pattern = "/".$key."=([^;]+)/";
            if(preg_match($pattern, $this->_header, $ret)) return $ret[1];
        }
        else
        {
            preg_match_all("/Set-Cookie: [^\n]+/", $this->_header, $ret);
            return $ret[0];
        }
    }

}
?>
	</body>

</html>

