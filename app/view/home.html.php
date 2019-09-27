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
        margin: 15px;
        padding: 15px;
        border: 1px solid #eaeaea;
      }
      h1 {
        font-family: Calibri, Helvectia, sans-serif;
        font-size: 28px;
        margin: 0 0 10px 0;
      }
      p {
        margin: 0 0 10px 0;
      }
      p:last-child {
        margin: 0;
      }
    </style>
  </head>
  <body>

    <h1><?= $this->title; ?></h1>
    <p><?= $this->message; ?></p>

  </body>
</html>
