$(document).ready(function(){	
		$('input.email').keyup(function() {
			var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
			var $th = $(this);
			var sEmail = $(this).val();
			if(sEmail != ''){
				if (filter.test(sEmail)) {
					$th.css('border', '1px solid #099A7D');	
				} else {
					$th.css('border', '1px solid #f52c2c');
				}
			} else {
				$th.css('border', 'none');
			}
		});
		
		$('input.skypeId').keyup(function() {
			var filter = /^[0-9-+.a-zA-Z_]+$/;
			var $th = $(this);
			var skypeId = $(this).val();
			if(skypeId != '')
			{
				if (filter.test(skypeId)) {
					$th.css('border', '1px solid #099A7D');	
				}
				else {
					$th.css('border', '1px solid #f52c2c');
				}
			}
			else
			{
				$th.css('border', 'none');
			}
		});
		 // alphabets with space
		$('input.capitalize').keyup(function() {
			var $th = $(this);		
			if($th.val().trim() != ""){
				 var regex = /^[a-zA-Z ]*$/;
				if (regex.test($th.val())) {
					$th.css('border', '1px solid #099A7D');					
				} else {
					// alert("Please use only letters");
					$th.css('border', '1px solid #f52c2c');
					return '';
				}
			}
		});
		
		// only numbers with country code + - min 10 digits(+91 9888888888 // 91-9888888888 // +91-9888888888)
		$('input.mobile').keyup(function() {
			var filter = /^[0-9-+\s]+\d{9}$/;
			var $th = $(this);
			var sPhoneNo = $(this).val();
			if(sPhoneNo != ''){
				if (filter.test(sPhoneNo)) {
					$th.css('border', '1px solid #099A7D');	
				} else {
					$th.css('border', '1px solid #f52c2c');
				}
			} else {
				$th.css('border', 'none');
			}
		});
		
		// only numbers
		$('input.numbers').keyup(function() {
			var filter = /^[0-9]+$/;
			var $th = $(this);
			var snumber = $(this).val();
			if(snumber != ''){
				if (filter.test(snumber)) {
					$th.css('border', '1px solid #099A7D');	
				} else {
					$th.css('border', '1px solid #f52c2c');
				}
			} else {
				$th.css('border', 'none');
			}
		});
		
		// number with braces , + and - ( (040) - 98765768 , +91 (080) 9869869643)
		$('input.landline').keyup(function() {
			var filter = /^[0-9-+()\s]+$/;
			var $th = $(this);
			var slandline = $(this).val();
			if(slandline != ''){
				if (filter.test(slandline)) {
					$th.css('border', '1px solid #099A7D');	
				} else {
					$th.css('border', '1px solid #f52c2c');
				}
			} else {
				$th.css('border', 'none');
			}
		});
		
		//Alphanumeric
		$('input.alphanumeric').keyup(function() {
			var filter = /^\s*[a-zA-Z0-9\s]+\s*$/;
			var $th = $(this);
			var salphanum = $(this).val();
			if(salphanum != ''){
				if (filter.test(salphanum)) {
					$th.css('border', '1px solid #099A7D');	
				} else {
					$th.css('border', '1px solid #f52c2c');
				}
			} else {
				$th.css('border', 'none');
			}
		});
		//Alphabetic
		$('input.alpha').keyup(function() {
			var filter = /^\s*[a-zA-Z\s]+\s*$/;
			var $th = $(this);
			var salpha = $(this).val();
			if(salpha != ''){
				if (filter.test(salpha)) {
					$th.css('border', '1px solid #099A7D');	
				} else {
					$th.css('border', '1px solid #f52c2c');
				}
			} else {
				$th.css('border', 'none');
			}
		});
		//Password
		$('input.password').keyup(function() {
			var filter = /^([a-zA-Z0-9@*#]{6,15})$/;
			var $th = $(this);
			var spassword = $(this).val();
			if(spassword != ''){
				if (filter.test(spassword)) {
					$th.css('border', '1px solid #099A7D');	
				} else {
					$th.css('border', '1px solid #f52c2c');
				}
			} else {
				$th.css('border', 'none');
			}
		});
		
	});
