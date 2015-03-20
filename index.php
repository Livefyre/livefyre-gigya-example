<?php

// have config drive doc root
require_once 'config/config.php';


?><!DOCTYPE html>
<html>
  <head>
    <title>Gigya</title>
    <script type="text/javascript">
    // Livefyre configuration used globally.
    var LIVEFYRE_NETWORK = "<?php echo LIVEFYRE_NETWORK; ?>";
    var LIVEFYRE_SITE_ID = "<?php echo LIVEFYRE_SITE_ID; ?>";
    var LIVEFYRE_COOKIE_NAME = "<?php echo LIVEFYRE_COOKIE_NAME; ?>";
    </script>
    <script src="client/js/jquery.min.js"></script>
    <script src="client/js/jquery.cookie.min.js"></script>
    <script src="http://cdn.gigya.com/JS/socialize.js?apiKey=<?php echo GIGYA_API_KEY; ?>&lang=en" type="text/javascript">{
      connectWithoutLoginBehavior: "alwaysLogin",           // Turn off temporary users
      sessionExpiration: <?php echo SESSION_EXPIRATION; ?>  // Standardize sessionExpiration
    }</script>
    <script src="http://zor.livefyre.com/wjs/v3.0/javascripts/livefyre.js" type="text/javascript"></script>
    <script src="client/js/init.js" type="text/javascript"></script>
  </head>
  <body>
    <div class="user-functions"><ul>
      <li class="login logged-out" style="display: none;"><a href="javascript:void(0);">Login</a></li>
      <li class="logout logged-in" style="display: none;"><a href="javascript:void(0);">Logout</a></li>
    </ul></div>
    <div id="livefyre-comments"></div>
  </body>
</html>
