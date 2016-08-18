
<!doctype html>
<html class="no-js" lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title> ServCup - Hosting Admin </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/css/bootstrap-table.min.css">
        <link rel="stylesheet" href="/assets/css/vendor.css">
        <script>
           var themeSettings = (localStorage.getItem('themeSettings')) ? JSON.parse(localStorage.getItem('themeSettings')) :
           {};
           var themeName = themeSettings.themeName || '';
           console.log(themeName);
           if (themeName)
           {
               document.write('<link rel="stylesheet" id="theme-style" href="/assets/css/app-' + themeName + '.css">');
           }
           else
           {
               document.write('<link rel="stylesheet" id="theme-style" href="/assets/css/app.css">');
           }
       </script>
       <?php
        if (isset($cssFiles) && count($cssFiles) > 0) {
        foreach ($cssFiles as $key => $value):?>
        <link rel="stylesheet" href="<?php echo MODPATH; ?>assets/css/<?php echo $value;?>">
        <?php
        endforeach;
        }
        ?>
        <link rel="stylesheet" href="/assets/css/main.css">

    </head>
    <body>
        <div class="main-wrapper">
            <div class="app" id="app">
