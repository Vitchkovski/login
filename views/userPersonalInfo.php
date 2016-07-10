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
            <form class="vertical-form-info" action="../login/index.php?action=logout" method="post">
                <h1>Personal Info</h1><br>
                <p align = left><input id="info" type="hidden" readonly><label for="info">ID: <?=$userId?><br></label>
                Username: <?=$userName?><br>
                Email: <?=$userEmail?></p>
                
                <input type="submit" value="Log Out"><br>
            </form>
        </div>
    </div>
    <footer>
        Personal Info / 2016
    </footer>
</body>

</html>