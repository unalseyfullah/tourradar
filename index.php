<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

if(empty($_POST['action'])) {
?>
<input type="text" name="string1" placeholder="string1"><br><br>
<input type="text" name="string2" placeholder="string2"><br><br>
<button type="button" name="send">Send</button><br><br>
<div id="result"></div>
<?php
} elseif($_POST['action'] == "anagram") {
	$str1 = str_replace(" ", "", strtolower($_POST['string1']));
	$str2 = str_replace(" ", "", strtolower($_POST['string2']));
	$str1_arr = str_split($str1);
	sort($str1_arr);
	$str2_arr = str_split($str2);
	sort($str2_arr);
	$error = 0;

	if(strlen($str1) === strlen($str2))
		$error = 0;
	else
		$error = 1;

	if($error == 1) {
		echo "FALSE";
	} else {
		for ($i=0; $i < strlen($str1); $i++) { 
			if($str1_arr[$i] == $str2_arr[$i]) {
				$error = 0;
			} else {
				$error = 1;
				break;
			}
		}
		if($error == 1)
			echo "FALSE";
		else
			echo "TRUE";
	}
}	
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("button[name='send']").click(function(){
			//alert("hello");
	        string1 = $("input[name='string1']").val();
	        string2 = $("input[name='string2']").val();
            if(!string1 || !string2){
            	$("#result").html("FALSE");
            } else {
	            $.ajax({
	                url: "index.php",
	                type: "post",
	                data: "action=anagram&string1="+string1+"&string2="+string2,
	                success: function (response) {
						// you will get response from your php page (what you echo or print)                 
						$("#result").html(response);
	                },
	                error: function(jqXHR, textStatus, errorThrown) {
	                    console.log(textStatus, errorThrown);
	                    $("#result").html("Danger! "+textStatus+" : "+errorThrown);
	                }
	            });
	        }
		});
	});
</script>