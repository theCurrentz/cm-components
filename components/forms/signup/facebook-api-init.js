const fbInitializer = function() {
  window.fbAsyncInit = function() {
    FB.init({
      appId            : chromaApp.fbAppID,
      autoLogAppEvents : true,
      xfbml            : true,
      version          : 'v3.2'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

   this.fbLogin = (el) => {
     FB.login(function(response) {
       checkLoginState(el);
       // handle the response
     }, {scope: 'email'});
   }

   function checkLoginState(el) {
     FB.getLoginStatus(function(response) {
       statusChangeCallback(response, el);
     });
   }

    function statusChangeCallback(response, el) {
      if (response.status === 'connected') {
        console.log('user logged in and ready to submit data')
        formProceszr.facebookSignup(el);
      }
    }
}
export default fbInitializer;
