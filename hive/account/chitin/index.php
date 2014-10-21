

<?php
    require('includes/chitin.php');

?>

<!DOCTYPE html>
<!--[if IE 8]>               <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">

    <title>Hivemind - Chitin</title>
    <link rel="stylesheet" href="../../../css/foundation.css">

    <script src="../../../js/vendor/custom.modernizr.js"></script>

    <link rel="stylesheet" href="../../../css/style.css">

    <style type="text/css">
    <!--
    .header{
    background-image: url(http://tsuts.tskoli.is/hopar/gru_h1/hive/img/header/<?php echo $selectedBg; ?>);
    }
    -->
    </style>
</head>
<body>

    <!--Header-->
    <?php include($root . '\hopar\GRU_H1\includes\header.php') ?>

    <div class="row">

        <div class="large-9 large-centered panel customPanel columns">
            
            <h2>Edit Account</h2>

              <form action='' method="post" enctype="multipart/form-data">
                <span>Confirm Password to change settings:</span>
                <input placeholder="Confirm Buzzword" type="password" name="confirmPassword" class="chitinConfirmBuzzword" pattern="^.{6,20}$" min="6" max="20" autocomplete="off">
                <div class="passwordResult"></div>
            

                <div class="panel chitinSettings">
                  <span>Change Alias:</span>
                  <input disabled="true" value="<?php echo $profile['alias'] ?>" placeholder="Change Alias" type="text" name="alias" class="chitinChangeAlias" min="1" max="30" autocomplete="off">

                  <span>Change Bio:</span>
                  <textarea disabled="true" placeholder="Change Bio" autocomplete="off" name="bio" class="chitinChangeBio"><?php echo $profile['bio'] ?></textarea>

                  <span>Change Password:</span>
                  <input disabled="true" placeholder="Change Buzzword" type="password" name="password" class="chitinChangeBuzzword" pattern="^.{6,20}$" min="6" max="20" autocomplete="off">
                  
                  <span>Profile picture:</span>
                  <input disabled="true" class="chitinUpload" type="file" name="file" id="file">
                  <input disabled="true" class="chitinSubmit button" type="submit" value="Update Account" />
                </div>
                
            </form>

            <a href="abscond"><button class="button alert">Abscond</button></a>

        </div>

    </div>

    <!-- Footer -->
    <?php include($root . '\hopar\GRU_H1\includes\footer.php') ?>

    

    <script>
    document.write('<script src=' +
        ('__proto__' in {} ? '../../../js/vendor/zepto' : '../../../js/vendor/jquery') +
        '.js><\/script>')
</script>

<script src="../../../js/foundation.min.js"></script>
      <!--
      
      <script src="../../../js/foundation/foundation.js"></script>
      
      <script src="../../../js/foundation/foundation.interchange.js"></script>
      
      <script src="../../../js/foundation/foundation.abide.js"></script>
      
      <script src="../../../js/foundation/foundation.dropdown.js"></script>
      
      <script src="../../../js/foundation/foundation.placeholder.js"></script>
      
      <script src="../../../js/foundation/foundation.forms.js"></script>
      
      <script src="../../../js/foundation/foundation.alerts.js"></script>
      
      <script src="../../../js/foundation/foundation.magellan.js"></script>
      
      <script src="../../../js/foundation/foundation.reveal.js"></script>
      
      <script src="../../../js/foundation/foundation.tooltips.js"></script>
      
      <script src="../../../js/foundation/foundation.clearing.js"></script>
      
      <script src="../../../js/foundation/foundation.cookie.js"></script>
      
      <script src="../../../js/foundation/foundation.joyride.js"></script>
      
      <script src="../../../js/foundation/foundation.orbit.js"></script>
      
      <script src="../../../js/foundation/foundation.section.js"></script>
      
      <script src="../../../js/foundation/foundation.topbar.js"></script>
      
    -->
    
    <script>
        $(document).foundation();
    </script>

    <script src="../../../js/jquery-2.0.2.min.js"></script>
    <script src="../../../js/script.js"></script>

</body>
</html>

