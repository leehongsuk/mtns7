<?
    session_start();
?>
<html>
	<head>

        <title>MTNS ��������</title>

        <meta http-equiv="Content-Type" content="text/html; charset=euc-kr">

        <style type="text/css">
			<!--
			TD
			{
				FONT-FAMILY: ����;
				line-height : 1.2;
				FONT-SIZE: 9pt
			}
			.input {  border-style: solid; font-size: 9pt; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px}
		-->
        </style>

        <script language="JavaScript" type="text/JavaScript">
        <!--



			function MM_preloadImages() //v3.0  // ����ü..
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


            // ���Ź�ȣ �߰� ��ư �ڵ鷯
			function receive_add()
			{
				var intCount = 0;
				var strMobile = document.form1.receive_input.value;  // �����ڹ�ȣ

                strMobile = strMobile.replace("-", "", strMobile); // �����ڹ�ȣ ���� "-" ����

				// �ߺ���ȣ üũ
                for (i = 0; i < document.form1.receive_buffer.length; i++)
				{
					if (strMobile == document.form1.receive_buffer.options[i].value) // receive_buffer ���� ��
					{
						return alert("���� ��ȣ�� ���Է� �ϽǼ� �����ϴ�"); // return�� ����

      document.form1.receive_buffer.options.remove(i);

						intCount = intCount - 1;

                        document.form1.count.value = intCount ;
						document.form1.receive_input.focus();
					}
				}


                //
				strDigit= "0123456789-";  // �Է���� ���ڵ�...
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
					alert ("�߰��� ���Ź�ȣ�� �Է��� �ּ���");
				}
				else if (strMobile.length < 10 || strMobile.length > 13 )
				{
					alert ("���Ź�ȣ�� �ִ� 13��, �ּ� 10���̳��� �Է��� �ּ���.\n\n ��) 01X-123-4567 �Ǵ� 01X1234567  ");

                    document.form1.receive_input.value="";
					document.form1.receive_input.focus();
				}
				else if ( !blnChkFlag )
				{
					alert("���Ź�ȣ�� ���ڸ� �����մϴ�.");

                    document.form1.receive_input.value="";
					document.form1.receive_input.focus();
				}
				else
				{
					// �ϳ� Ȥ�� �������� ���� ��ȣ�� ����.... receive_number:�������� �����ڹ�ȣ(��)..
                    document.form1.receive_number.value = document.form1.receive_number.value + document.form1.receive_input.value + "," ;

					add() ; // �������� �����ڹ�ȣ �߰� ����
				}
			}

            // �������� �����ڹ�ȣ �߰� ����
			function add()
			{
					var intCount = document.form1.count.value ;
					var newOpt = document.createElement('OPTION'); // ���ο� �������� �����Ѵ�.

                    newOpt.text =  document.form1.receive_input.value; // text�� �����ڹ�ȣ
					newOpt.value = document.form1.receive_input.value; // value�� �����ڹ�ȣ
					document.form1.receive_buffer.options.add(newOpt); // ������ �������� Select�� �߰��Ѵ�.

					document.form1.receive_input.value = "" ; // ����� �Ǿ����Ƿ� �����ڹ�ȣ�� �����.

					intCount = intCount + 1 ;  // ī���� �ϳ� �߰�...

                    document.form1.count.value = intCount ; // �����ڼ� ����

					document.form1.receive_input.focus(); // �����ڹ�ȣ�� ��Ŀ���� ������ ��..
			}



            // ���� ��ư �ڵ鷯
            function receive_del()
			{
				if (document.form1.receive_buffer.selectedIndex < 0)
				{
					alert ("������ ��ȣ�� ������ �ּ���");
				}
				else
				{
					var aaa;

					aaa = document.form1.receive_number.value ;
					aaa = aaa.replace(document.form1.receive_buffer.value + ",","");
					document.form1.receive_number.value = aaa ;  // ���õ� �����ڹ�ȣ�� �����.

					var num ;
					var intCount = document.form1.count.value ;

                    num = document.form1.receive_buffer.selectedIndex ;
					document.form1.receive_buffer.options.remove(num); // select���� option�ϳ��� �����.

                    intCount = intCount - 1;    // ī���� �ϳ� ����
					document.form1.count.value = intCount ;   // ������ �� ����
				}
			}

			// ��λ��� ��ư �ڵ鷯
            function receive_alldel()
			{
                document.form1.receive_number.value = "0" ; //������ ��ȣ�� 0���� ����

                var intCount = document.form1.count.value ;
                for (i = 0; i < intCount; i++)
                {
                    document.form1.receive_buffer.options.remove(0); // ī���ͼ���� ���ʷ� �����.
                }
                document.form1.count.value = "0" ; //  ������ �� 0 �� ����
			}

        //-->
        </script>
	</head>


	<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <!-- action="SendSMS.php" -->
        <form name="form1"  method=post>

			<input type="hidden" name="biz_id" value="vnmtns">							       <!-- Xonda/Biz ID  -->
			<input type="hidden" name="return_url" value="<?=$PHP_SELF?>">		<!-- sms ������ ���ƿ� URL  -->
			<INPUT type="hidden" name="receive_number" size=20 value="">				<!-- ���� ���۵� ��ȣ -->

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
           <td><img src="./Image/skinL1_btnsnd.gif" width="76" height="30" onclick="alert('�α׾ƿ� �Ǿ����ϴ� \n�ٽ� �α����ϼ���');"></td>
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
									  <td width="52" align="center" valign="middle">�߽Ź�ȣ</td>
									  <td width="102" height="22"> <input name="send_number" type="text" class="input" size="10" value="<?=$send_number?>"></td>
									</tr>
								  </table></td>
							  </tr>
							  <tr>
								<td>&nbsp;</td>
								<td valign="bottom"><table width="164" border="0" cellspacing="0" cellpadding="0">
									<tr>
									  <td width="10" align="right"><img src="./Image/skinL1_icon.gif" width="8" height="8"></td>
									  <td width="52" align="center" valign="middle">���Ź�ȣ</td>
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
										��</td>
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
								  </select>��<select name="reserved_month" style="font-size:8pt">
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
								  </select>��<select name="reserved_day" style="font-size:8pt">
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
								  </select>��</td>
							  </tr>
							  <tr>
								<td>&nbsp;</td>
								<td height="23"><input type="checkbox" name="reserved_flag" value="true">����&nbsp;&nbsp;&nbsp; <select name="reserved_hour" style="font-size:8pt">
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
								  </select>��<select name="reserved_minute" style="font-size:8pt">
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
								  </select>��</td>
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

    //���� �Է�
    $http->setParam("biz_id", "xnmtns");
    $http->setParam("password", "0922");
    $http->setParam("DNSName", "mtns7.co.kr");
    $http->setParam("send_number", $send_number);
    $http->setParam("receive_number", $receive_number);
    $http->setParam("sms_contents", $sms_contents);
    $http->setParam("merge_name", "");
    if  ($reserved_flag==true) // ���������ϰ��
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

    // DOM ��ü �� ��Ʈ�ٸ���Ʈ ����
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
* HTTP ���� Ŭ����
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
     * URL �����Լ�
     *
     * @param string $url : URL
     * @return boolean
     */
    function setURL($url)
    {
        if(!$m = parse_url($url)) return $this->setError("�Ľ��� �Ұ����� URL�Դϴ�.");
        if($m['scheme'] != "http") return $this->setError("HTTP URL�� �ƴմϴ�.");

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
     * �������� �����Ѵ�.
     *
     * @param string $key : ������, �迭�ε� ������ �ִ�.
     * @param string $value : ������
     */
    function setParam($key, $value="")
    {
        if(is_array($key)) foreach($key as $k => $v) $this->variable[$k] = $v;
        else $this->variable[$key] = $value;
    }

    /**
     * Referer�� �����Ѵ�.
     *
     * @param string $referer : Referer
     */
    function setReferer($referer)
    {
        $this->referer = $referer;
    }

    /**
     * ��Ű�� �����Ѵ�.
     *
     * @param string $key : ��Ű������, �迭�ε� ������ �ִ�.
     * @param string $value : ��Ű������
     */
    function setCookie($key, $value="")
    {
        if(is_array($key)) foreach($key as $k => $v) $this->cookie .= "; $k=$v";
        else $this->cookie .= "; $key=$value";

        if(substr($this->cookie, 0, 1) == ";") $this->cookie = substr($this->cookie, 2);
    }

    /**
     * ���������Լ�
     *
     * @param string $id : ���̵�
     * @param string $pass : �н�����
     */
    function setAuth($id, $pass)
    {
        $this->auth = base64_encode($id.":".$pass);
    }

    /**
     * POST ����� ��������Լ�
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
     * GET ����� ��������Լ�
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
     * ����Ÿ �����Լ�
     *
     * @param string $mode : POST, GET �� �ϳ��� �Է��Ѵ�.
     * @return string
     */
    function send($mode="GET")
    {

        // �������� �����Ѵ�.
        $fp = fsockopen($this->host, $this->port, $errno, $errstr, 10);
        if(!$fp) return $this->setError($this->host."���� ���ӿ� �����߽��ϴ�.");

        // GET, POST ��Ŀ� ���� ����� �ٸ��� �����Ѵ�.
        if(strtoupper($mode) == "POST") $this->query = $this->postMethod();
        else $this->query = $this->getMethod();

        fputs($fp,$this->query);

        // ��� �κ��� ���Ѵ�.
        $this->_header = ""; // ����� ������ �ʱ�ȭ �Ѵ�.
        while(trim($buffer = fgets($fp,1024)) != "")
        {
            $this->_header .= $buffer;
        }

        // �ٵ� �κ��� ���Ѵ�.
        while(!feof($fp))
        {
            $body .= fgets($fp,1024);
        }

        // ������ �����Ѵ�.
        fclose($fp);

        return $body;
    }

    /**
     * ����� ���ϴ� �Լ�
     *
     * @return string
     */
    function getHeader()
    {
        return $this->_header;
    }

    /**
     * ��Ű���� ���ϴ� �Լ�
     *
     * @param string $key : ��Ű����
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

