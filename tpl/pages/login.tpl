</head>
<body>

TEMPLATE de login.tpl<br /><br />

<form action="/?page=login&action=login" method="post">
	Login:<input type="text" name="login"><br />
	Password:<input type="text" name="password"><br />
	<input type="submit" value="Login">
</form>

<br /><br />
AUTH LEVEL : {$authLevel}<br /><br />

<A href="/?page=login&action=logout">LOGOUT</A>


