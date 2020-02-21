<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title><?= $this->title ?></title>
        <style>
            body {
                font-family: 'Segoe UI', Roboto, sans-serif;
                font-size: 18px;
                height: 100vh;
                margin: 0;
                padding: 15px;
                box-sizing: border-box;
            }
            .content {
                position: relative;
                border: 1px solid #eaeaea;
                height: 100%;
            }
            .inner {
                position: absolute;
                top: 50%;
                left: 15px;
                right: 15px;
                transform: translateY(-50%);
                text-align: center;
            }
            h1 {
                font-family: Calibri, Helvectia, sans-serif;
                font-size: 28px;
                margin: 0;
            }
            p {
                margin: 15px 0 0 0;
            }
        </style>
    </head>
    <body>
        <div class="content">
            <div class="inner">
                <h1><?= $this->title; ?></h1>
                <p><?= $this->message; ?></p>
            </div>
        </div>
    </body>
</html>
