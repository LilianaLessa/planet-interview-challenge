<!doctype html>
<html class="no-js" lang="en">

<head>
  <title>Cart</title>

  <meta charset="utf-8" />

  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Planet interview challenge answer by Liliana Lessa" />
  <meta name="theme-color" content="#fafafa" />

  <meta property="og:title" content="Cart" />
  <meta property="og:type" content="website" />
  <meta property="og:description" content="Planet interview challenge answer by Liliana Lessa" />
  <meta property="og:url" content="" />
  <meta property="og:image" content="" />
  <meta property="og:image:alt" content="" />

  <link rel="icon" href="favicon.ico" sizes="any" />
  <link rel="icon" href="icon.svg" type="image/svg+xml" />
  <link rel="apple-touch-icon" href="icon.png" />
  <link rel="manifest" href="site.webmanifest" />

  <style>
    span.appVersion {
      position: absolute;
      bottom: 10px;
      left: 50%;
      color: #aaa;
    }
  </style>
</head>

<body>
  {$ShopCart->display()}
  <footer>
    <span class="appVersion">App version: {$AppVersion}</span>
  </footer>
</body>

</html>
