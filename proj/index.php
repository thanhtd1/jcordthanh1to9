<!DOCTYPE html>
<html ng-app="UserApp">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=11" >
		<script type="text/javascript">
			function openUrl(name,url,notprm) {
				var w = screen.availWidth - 20 ;
				var h = screen.availHeight - 50;
				var prm = "left=0,top=0,width="+ w +",height="+ h +",scrollbars=yes,location=no,resizable=yes,directories=no,toolbar=no,status=no" ;
				if ( notprm == 1 )      {
					prm ="";
				}
				win = window.open(url, name, prm);
			}
		</script>
	</head>
	<body>
		<div id="page">
			<!-- 最初に表示する画面のURLを設定する -->
			<div id="single">
				<h1>臍帯TEST MENU</h1>
				<ul  style="font-size:20px;text-align:center">
					<li style="display:block; margin-top:20px;">
<a href="#" onclick="openUrl('login','/jcord/web/site/jcord',0)" >画面XXX:ログイン</a>
<a href="#" onclick="openUrl('login','/jcord/web/site/jcord',1)" >[枠あり]</a>
					</li>
					<li style="display:block; margin-top:20px;">
<a href="#" onclick="openUrl('login','/jcord/web/site/jcord',0)" >画面XXX:ログイン</a>
<a href="#" onclick="openUrl('login','/jcord/web/site/jcord',1)" >[枠あり]</a>
					</li>
					<li style="display:block; margin-top:20px;">
<a href="#" onclick="openUrl('login','/jcord/web/site/jcord',0)" >画面XXX:ログイン</a>
<a href="#" onclick="openUrl('login','/jcord/web/site/jcord',1)" >[枠あり]</a>
					</li>
				</ul>
			</div>
		</div>

<hr>
	</body>
</html>
