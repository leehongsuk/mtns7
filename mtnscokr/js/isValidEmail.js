
//http://tong.nate.com/lhs0806/48222788
function isValidEmail(email_address)   
{   
				// 이메일 주소를 판별하기 위한 정규식   
				var format = /^[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+)*@[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*$/;   
							
				// 인자 email_address를 정규식 format 으로 검색   
				if (email_address.search(format) != -1)   
				{   
								// 정규식과 일치하는 문자가 있으면 true   
								return true;   
				}   
				else  
				{   
								// 없으면 false   
								return false;   
				}   
}   