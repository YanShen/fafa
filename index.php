<html>
	<script>
	function getUrlVars()
	{
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++)
		{
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		return vars;
	}
	
	var result = getUrlVars()["result"];
	document.location="/ms/latest_surveys.php" + (result ? "?result="+result : "");
	</script>
</html>