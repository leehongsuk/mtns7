
// http://tong.nate.com/lhs0806/48212103
function checkLength(objname,maxleng) 
{
				var textObj = document.getElementById(objname) ;
				var msglen  = 0 ;  //메시지길이(byte단위)
				var msg     = "" ; //메시지를 임시로저장
			
				for(i=0; i<textObj.value.length; i++) 
				{
								var curr = textObj.value.charAt(i);
						
								// 엔터값에서 '\r'값을 카운트 하지 않음
								// textarea애서는 \r값을 없앨수가 없는듯함.. 메시지발송전에 값을 변경하도록함
								if  ( escape(curr)=='%0D' ) continue;
						
								// 한글(유니코드)은 escape()함수를 사용했을때
								// 앞에 %u가 붙고, 뒤에 16진수로 변환값이 붙는다
								if  ( escape(curr).indexOf("%u")!=-1 ) 
								{
												msglen += 2 ;
								}
								else 
								{
												msglen ++ ;
								}
						
								// 제한길이를 넘어가면 중지
								if( msglen > maxleng ) break ;
						
								// 제한길이를 넘어갈때를 위해 저장
								msg += curr ;
				}
				
				if  ( msglen > maxleng ) 
				{
								alert("제한길이("+maxleng+")를 초과하셨습니다. ");
								textObj.value = msg;
				}
}
