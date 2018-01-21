<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inphinit PHP framework</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300" rel="stylesheet" type="text/css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0">
    <style type="text/css">
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    body {
        background-color: #F7F6F6;
        min-width: 210px;
    }
    .container {
        height: 100%;
        text-align: center;
    }
    .container .header {
        display: inline;
        display: inline-block;
        vertical-align: middle;
        width: 96%;
        font-family: 'Roboto', 'Helvetica Neue', Helvetica, Arial, freesans, sans-serif;
        color: #5F5656;
    }
    .container h1 {
        font-size: 48pt;
        font-weight: 100;
        padding: 5px 0;
        margin: 0;
    }
    .container h2 {
        padding: 25px 0 30px 0;
        font-weight: normal;
        font-size: 18pt;
    }
    .container::before {
        display: inline-block;
        vertical-align: middle;
        content: "";
        height: 100%;
        width: 0;
    }

    @media only screen and (max-width: 400px) {
        .container h1 {
            font-size: 36pt;
        }
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Inphinit - PHP framework</h1>
            <p><?php echo $intro; ?></p>
        </div>
    </div>
</body>
</html>
