<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Registration Page</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="main">
    <div class="wrap">
        <div class="header">
        </div>        
        <div align="center">
            <form class="vertical-form-bottom">
                <p><input id="info" type="hidden" readonly><label for="info">ID: <?=$userId?><br>
                Username: <?=$userName?><br>
                Email: <?=$userEmail?></label></p>
                
                <p align="center"><a class="sign-in" href="../login?action=logout">Logout</a></p>
            </form>
        </div>
    </div>
    <footer>
        Personal Info / 2016
    </footer>
</body>

</html>