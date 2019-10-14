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
      thead td {
          font-weight: 700;
      }
      tbody td {
          padding: 5px 2px 5px;
          border-top: 1px solid #000000;
      }
    </style>
  </head>
  <body>

    <h1><?= $this->title; ?></h1>
    <p><?= $this->message; ?></p>

    <?php if ( !empty($this->files) ): ?>
        <table>
            <thead>
                <tr>
                    <td>File</td>
                    <td>Size</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $this->files as $file ): ?>
                    <tr>
                        <td><?= $file->getPath(); ?></td>
                        <td><?= format_bytes($file->getSize()); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

  </body>
</html>
