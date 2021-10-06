<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $status . ' ' . $title; ?></title>
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
    .container h1 strong {
        display: block;
        font-size: 200%;
    }
    .container code {
        display: inline-block;
        vertical-align: middle;
        padding: 3px 5px;
        border-radius: 3px;
        background-color: #756868;
        color: #fff;
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
            <h1>
                <strong><?php echo $status; ?></strong>
                <?php echo $title; ?>
            </h1>

            <p>
                Route <code><?php echo $route; ?></code>
                <?php if ($route !== $path): ?>
                (fullpath: <code><?php echo $path; ?></code>)
                <?php endif; ?>
            </p>
        </div>
    </div>
</body>
</html>
