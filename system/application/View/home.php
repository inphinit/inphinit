<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inphinit - php framework</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100" rel="stylesheet" type="text/css">
    <style type="text/css">
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    body {
        background: #F7F6F6;
    }
    .container {
        height: 100%;
        text-align: center;
    }
    .container .header {
        display: inline;
        display: inline-block;
        vertical-align: middle;
        width: 98%;
        font-family: 'Roboto', 'Helvetica Neue', Helvetica, Arial, freesans, sans-serif;
        color: #5F5656;
    }
    .container h1 {
        font-size: 48pt;
        font-weight: 100;
    }
    .container:before {
        display: inline-block;
        vertical-align: middle;
        content: " ";
        height: 100%;
        width: 0;
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Inphinit - php framework</h1>
            <p><?php echo $intro; ?></p>
        </div>
    </div>
</body>
</html>