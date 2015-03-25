(function(root, $, Livefyre, gigya) {
    "use strict";

    if (!gigya) {
        console.log("MISSING GIGYA - SHUTTING DOWN");
        return;
    }


    if (!Livefyre) {
        console.log("MISSING GIGYA - SHUTTING DOWN");
        return;
    }


    // CONFIGURE GIGYA


    gigya.accounts.addEventHandlers({
        onLogin: function() {

            // if (waitingForLogin) {
            //     waitingForLogin = false;
            //     // WHYYYYYY
            //     if (promise) {
            //         promise.success();
            //     }
            // }
        }
    });




    var USER;

    var loginBTN$ = $(".login");
    var logoutBTN$ = $(".logout");


    var LF_READY = false;

    var LF_READY = new $.Deferred();
    // Livefyre.required Apps
    root.LF_APPS = {};



    var SETTINGS = {
        networkConfig: {

        },
        convConfig: {

        }
    };



    var error = function(message) {
        if(console && console.log) {
            console.log(message);
        }
    }


    var triggerLogin = function (promise) {

        gigya.accounts.showScreenSet({
            screenSet: "Login-web"
        });




    // HACKADELIC - REWORK THIS!
        // Detect if screen was closed
        // In the future, Gigya will have "onHide"
        // var isScreenClosed = function () {
        //     // Logged in!
        //     if (USER || !waitingForLogin) {
        //         return;
        //     }

        //     // Screen closed
        //     if (!$(".gigya-screen-dialog:visible").length) {
        //         // WHYYYYYYYY!?
        //         return promise.failure();
        //     }

        //     setTimeout(isScreenClosed, 500);

        // }


        // setTimeout(isScreenClosed, 1000);

    };






    var triggerLogout = function (promise) {
        gigya.accounts.logout({
            callback: promise.success
        });
    }



    // User state event handlers
    var onLogin = function (user) {
        USER = user;
        onUserStateChange();
    }



    var onLogout = function() {
        USER = undefined;
        onUserStateChange();
    }



    var onUserStateChange = function() {
        if (USER) {
          // Logged in
          loginLivefyre();

          loginBTN$.hide();
          logoutBTN$.show();

        } else {
          // Logged out
          logoutLivefyre();

          loginBTN$.show();
          logoutBTN$.hide();
        }
    }



    var loginLivefyre = function(promise) {
        if(!$.cookie(LIVEFYRE_COOKIE_NAME)) {
        // Only if not already logged into Livefyre
          // With valid user signature, returns LiveFyre cookie

            $.ajax({
                url: "server/ajax/token-endpoint.php",
                data: {
                UID: USER.UID,
                UIDSignature: USER.UIDSignature,
                signatureTimestamp: USER.signatureTimestamp
                },
                type: "POST",
                dataType: "json",
                cache: false
            }).done(function (response) {
                //     return error("Livefyre token request failed");
                $.cookie(LIVEFYRE_COOKIE_NAME, response.token);

                authLivefyre();

                if (promise) {
                    promise.success();
                }

            });


        } else {
          // Already logged in
            authLivefyre();

            if (promise) {
                promise.success();
            }
        }
    }





  var logoutLivefyre = function (auth) {
    if($.cookie(LIVEFYRE_COOKIE_NAME)) {
      // Only if currently logged into Livefyre
      $.removeCookie(LIVEFYRE_COOKIE_NAME);
    }
  }

  var authLivefyre = function (auth) {
    if($.cookie(LIVEFYRE_COOKIE_NAME)) {
      try {
        // NEW STUB
        // auth.login();

        fyre.conv.login($.cookie(LIVEFYRE_COOKIE_NAME));


      } catch(e) {
        error(e);
      }
    }
  }




  var authDelegateLivefyre = new fyre.conv.RemoteAuthDelegate();


  authDelegateLivefyre.login = function(delegate) {
    if(USER) {
      // Already logged in -- generate Livefyre token to sync login state
      loginLivefyre(delegate);
    } else {
        console.log("what's this delegate", delegate);
      triggerLogin(delegate);
    }
  }


  authDelegateLivefyre.logout = function(delegate) {
    triggerLogout(delegate);
  }
  authDelegateLivefyre.viewProfile = function(delegate) {
    gigya.accounts.showScreenSet({
      screenSet: "Profile-web"
    });
    delegate.success();
  }
  authDelegateLivefyre.editProfile = function(delegate) {
    gigya.accounts.showScreenSet({
      screenSet: "Profile-web"
    });
    delegate.success();
  }




  // Bind to Gigya login/logout global events
  gigya.accounts.addEventHandlers({
    onLogin: onLogin,
    onLogout: onLogout
  });


  // Query user state and render initial UI
  gigya.accounts.getAccountInfo({
    callback: function(response) {
      USER = response.errorCode === 0 ? response : undefined;
      // UUUUUGGGGGHHHHHH!
      $(document).ready(onUserStateChange);
    }
  });





  function loadLivefyreSDK (LF, settings) {

    Livefyre.require(['fyre.conv#3', 'auth'],
      function(Conv, auth) {

        // new Conv(networkConfig, [convConfig],
        //   function(commentsWidget) {}());

        root.LF_APPS.Conv = Conv;
        root.LF_APPS.auth = auth;


      });
  }





  $(document).ready(function() {
    // Bind to login/logout links

    loginBTN$.on("click", function() {
      triggerLogin();
      return false;
    });


    logoutBTN$.on("click", function() {
        // WHYYYYYYYY?
        //triggerLogout();

        gigya.accounts.logout({
            callback: function () {
                console.log("gigglez logout", arguments);

                fyre.conv.logout();
            }
        });

        return false;
    });



    // Render Livefyre comments
    var articleId = fyre.conv.load.makeArticleId(null);

    // loadLivefyreSDK(root.Livefyre, SETTINGS).then(
    //   function(){},
    //   function(){}
    // );


    fyre.conv.load(
      // Global config:
      {
        network: "gigya-0.fyre.co",
        authDelegate: authDelegateLivefyre
      },

      // Comment streams
      /*
      [{
        el: "livefyre-comments",
        siteId: LIVEFYRE_SITE_ID,
        articleId: articleId,
        signed: false,
        collectionMeta: {
          articleId: articleId,
          url: fyre.conv.load.makeCollectionUrl(),
        }
      }],
      */
      [{"collectionMeta":"eyJ0eXAiOiJqd3QiLCJhbGciOiJIUzI1NiJ9.eyJ0aXRsZSI6IkNvbW1lbnQgVGVzdCIsInVybCI6Imh0dHA6XC9cL2ZhZ2FuLmdpZ3lhLWNzLmNvbVwvbGl2ZWZ5cmVcLyIsInRhZ3MiOiIiLCJjaGVja3N1bSI6IjE0NTU5Yjk4ZGVlODFjYjY0NzE3ZTIwYTU3NzEwMzQwIiwiYXJ0aWNsZUlkIjoiMTIzIn0.l5bm_fv5avLzbBGMj8iO5dPZDoOvyphJjsYPGDcq3IU","checksum":"14559b98dee81cb64717e20a57710340","siteId":"303862","articleId":"123","el":"livefyre-comments"}],

      // onLoad
      authLivefyre
    );


  });
}(this, jQuery, Livefyre, gigya));
