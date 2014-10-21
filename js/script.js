$(document).ready(function() {

      //Global vars
      var desiredThread   = -1;
      var desiredPost     = null; 
      var defaultBoard    = "b";
      var defaultPage     = 1;
      var board           = defaultBoard;
      var currentThread   = 0;
      var currentURL      = document.URL;
      currentURL          = currentURL.split("/");

      if (currentURL[(currentURL.length-1)].substring(0,4) == "?nav") {

            var splitSpec = currentURL[(currentURL.length-1)].split("=");
            var splitHash = splitSpec[1].split("#");

            if (splitHash.length > 0) {

                  splitSpec = splitHash[0].split(":");

            } else {

                  splitSpec = splitSpec[1].split(":");

            }

            board = splitSpec[0];
            currentThread = splitSpec[2];

      }

      //Dropdown menu
      $('.dropdown').click(function(){
            //Skoðaði í Inspector í Firefox hvað gerist by default í foundation þegar dropdown lokar.
            $('html').click(function() {
                  $('.f-dropdown').css({'position':'absolute', 'top':'38.5px', 'left':'-99999px'}).removeClass('open');
            });
      });

      //-------------------------------Topnav tabs-----------------------------------------

      //Home
      $('#tab1').click(function(event){
            event.preventDefault();
            /*$('#home').css("display", "block");
            $('.roberto').css("display", "none");*/
            $('#home').css("display", "block");
            $('#faq').css("display", "none");
      });

      //Search Reset
      $('#searchTab').click(function(event){
            $('.searchForm').css({'display':'block'});
            $('.searchResult').css({'display':'none'});
      });

      //FAQ
      $('#tab3').click(function(event){
            event.preventDefault();
            /*$('.roberto').css("display", "block");
            $('#home').css("display", "none");*/
            $('#faq').css("display", "block");
            $('#home').css("display", "none");
      });

      //Tabs function
      $('.tabHeader').click(function(){
            $('.tabHeader').removeClass("currentTab");
            $(this).addClass("currentTab");
      });

      //------------------------Friend list------------------------------------

      var removing = 0; //Til að koma í veg fyrir að ýta á userListRow þegar maður removar
      //Remove friend
      $('.removeFriend').click(function(event){
            event.preventDefault();
            //Getting friend ID
            var row = $(this).parent().parent();

            var id = row.children('.userListId').text();

            post_data = {'id':id};

            $.post('includes/removeFriend.php', post_data, function(data){
                  row.slideUp();

            });

            removing = 1;
      });

      //Friendlist row clickable - Einnig notað í userlist
      $('.userListRow').click(function(){

            if (removing == 0) {
                  var id = $(this).children('.userListId').text();
                  window.location = "http://tsuts.tskoli.is/hopar/gru_h1/hive/profile?id=" + id;
            }
            
            removing = 0;
      });

      //------------------------REPORTS---------------------------------
      //Delete report
      $('.removeColumn').click(function(event){
            event.preventDefault();

            var row = $(this).parent();

            var reportID = row.children('.reportID').text();

            post_data = {'reportID':reportID};

            $.post('deleteReport.php', post_data, function(data){
                  row.slideUp();

            });

            removing = 1;
      });

      //Go to profile of submitter
      $('.submitterID').click(function(event){
            event.preventDefault();

            var submitter = $(this).text();

            window.location = "http://tsuts.tskoli.is/hopar/gru_h1/hive/profile?id=" + submitter;

            removing = 1;
      });

      //Report row click
      $('.reportRow').click(function(){

            if (removing == 0) {
                  var row = $(this);
                  var reportID = row.children('.reportID').text();
                  post_data = {'reportID':reportID};
                  
                  $.post('locatePost.php', post_data, function(data){
                        window.location = "http://tsuts.tskoli.is/hopar/gru_h1/hive/?nav=" + data;
                  });
            }
            
            removing = 0;
      });

      //---------------------------------USERLIST-----------------------------------
      //Delete report
      $('.userListStatus').click(function(event){
            removing = 1;
      });

      //Select option changes (Status)(Combobox)
      $(".userListSelect").change(function () {
            var status = "";
            status = $(this).children("option:selected").val();

            var row = $(this).parent().parent();

            var id = row.children('.userListId').text();

            post_data = {'id':id, 'status':status};

            $.post('updateUser.php', post_data, function(data){
                  if (data = "success") {
                        alert("Changed");
                  }

                  else{
                        alert(data);
                  }
            });
      });

      //Delete user
      $('.deleteUserClick').click(function(event){
            event.preventDefault();

            var row = $(this).parent();

            var id = row.children('.userListId').text();

            post_data = {'id':id};


            $.post('deleteUser.php', post_data, function(data){
                  alert(data);
                  row.slideUp();
            });

            removing = 1;
      });

      //----------------------------------FRIENDS-------------------------------------
      //Add friend
      $('.addFriend').click(function(){

            //Get URL
            var url = location.search;

            var id = url.split("=");

            id = id[1]; //ID

            post_data = {'id':id};

            $.get('../account/friend.php', post_data, function(data){

                  if (data == "false" ) {
                        $('.addFriend').parent().append("Bee has been added as a friend");
                        $('.addFriend').remove();
                  }

                  else{
                        $('.addFriend').parent().append("Error");
                        $('.addFriend').remove(); 
                  }

            });

      });

      //-----------------------------SEARCH--------------------------------------
      //Search button click

      $('.searchButton').click(function(){
            //event.preventDefault(); //Prevent anchor tag default

            var type = $('select[name=searchType]').val();
            var text = $('input[name=searchText]').val();
            
            post_data = {'type':type, 'text':text};

            $.post('search.php', post_data, function(data){
                  $('.searchResult').html(data);
                  $('.searchForm').fadeOut(500, function(){
                        $(".searchResult").fadeIn();
                  });
                  
                  //alert(data[0][1]);
                  //window.location = "http://tsuts.tskoli.is/hopar/gru_h1/hive/?nav=" + data;
            });
      });
      

      //Delay timer - Credit: http://stackoverflow.com/questions/1909441/jquery-keyup-delay
      var delay = (function(){
            var timer = 0;
            return function(callback, ms){
                  clearTimeout (timer);
                  timer = setTimeout(callback, ms);
            };
      })();


      //-------------------------CHITIN---------------------------------------------
      //Disable fields in Chitin + Check for password
      $('.chitinConfirmBuzzword').keyup(function(){

            $('.alert-box').slideUp().queue(function() { $(this).remove(); }); //Slideup alert-box
            

            delay(function() {
                  var confirmBuzz = $('.chitinConfirmBuzzword').val();

                  if (confirmBuzz.length > 5) {

                        post_data = {'confirmPassword':confirmBuzz};

                        $.post('includes/check.php', post_data, function(data){

                              //Success
                              if (data == 1) {
                                    $('.passwordResult').append("<div data-alert class='alert-box success'>Password is correct<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');

                                    $('.chitinConfirmBuzzword').css("border-color","rgb(45, 202, 34)"); 
                                    $('.chitinSettings').css("border-color","rgb(45, 202, 34)");
                                    $('.chitinSettings').css("background-color","rgb(223, 255, 223)");

                                    $(".chitinChangeBuzzword").prop('disabled', false);
                                    $(".chitinUpload").prop('disabled', false);
                                    $(".chitinSubmit").prop('disabled', false);
                                    $(".chitinChangeAlias").prop('disabled', false);
                                    $(".chitinChangeBio").prop('disabled', false);
                              }

                              //Fail
                              else {
                                    $('.passwordResult').append("<div data-alert class='alert-box alert'>Password is incorrect<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');

                                    $('.chitinConfirmBuzzword').css("border-color","rgb(225, 46, 46)");
                                    $('.chitinSettings').css("border-color","rgb(225, 46, 46)");
                                    $('.chitinSettings').css("background-color","rgb(255, 224, 224)");

                                    $(".chitinChangeBuzzword").prop('disabled', true);
                                    $(".chitinUpload").prop('disabled', true);
                                    $(".chitinSubmit").prop('disabled', true);
                                    $(".chitinChangeAlias").prop('disabled', true);
                                    $(".chitinChangeBio").prop('disabled', true);
                              }

                        });

}

else{
      $('.passwordResult').append("<div data-alert class='alert-box alert'>Password is incorrect<a href='#' class='close'>&times;</a></div>");
      $('.alert-box').slideDown('fast');

      $('.chitinConfirmBuzzword').css("border-color","rgb(225, 46, 46)");
      $('.chitinSettings').css("border-color","rgb(225, 46, 46)");
      $('.chitinSettings').css("background-color","rgb(255, 224, 224)"); 

      $(".chitinChangeBuzzword").prop('disabled', true);
      $(".chitinUpload").prop('disabled', true);
      $(".chitinSubmit").prop('disabled', true);
      $(".chitinChangeAlias").prop('disabled', true);
      $(".chitinChangeBio").prop('disabled', true);
}
}, 800);
});

/*--------------------- LOG-IN ------------------------------------*/

$('#loginBtn').click(function(event){

      event.preventDefault();

      $('.alert-box').remove();
      $('.loadingGif').remove();

      var email = $('input[name=email]').val();
      var password = $('input[name=password]').val();
      var rememberme;

      if ($('#rememberme').prop('checked') == true) {
            rememberme = "checked";
      }

      var emailRegex = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
      var passRegex = /^.{6,20}$/;

      var proceed = true;

      if (emailRegex.test(email) == false) {
            proceed = false;
            $('.loginResult').append("<div data-alert class='alert-box alert'>Email invalid<a href='#' class='close'>&times;</a></div>");
            $('.alert-box').slideDown('fast');
      }

      if(passRegex.test(password) == false){
            proceed = false;
            $('.loginResult').append("<div data-alert class='alert-box alert'>Password invalid<a href='#' class='close'>&times;</a></div>");
            $('.alert-box').slideDown('fast');
      }


      if (proceed == true) {
            post_data = {'email':email, 'password':password, 'rememberme':rememberme};

            $('.loginResult').append('<img class="loadingGif" src="register/img/loading.gif"/>');
            $('.loadingGif').css({'visability':'none','display':'block'});
            $('.loadingGif').fadeIn('slow');

            $.post('includes/login.php', post_data, function(data){

                        //Success
                        if (data == "success") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    window.location.replace("http://tsuts.tskoli.is/hopar/gru_h1/hive");
                              });

                        }

                        //Invalid password/email
                        if (data == "invalid") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $(this).remove();
                                    $('.loginResult').append("<div data-alert class='alert-box alert'>Email or password incorrect<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');
                              });
                        }

                        //Unconfirmed
                        if (data == "unconfirmed") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $('.loginResult').append("<div data-alert class='alert-box alert'>You have not yet confirmed your email.<br />If you did not receive an email within 24 hours, <a class='loginResendConfirm' href=''>click here</a> to re-send.<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');
                              });
                        }

                        //Banned
                        if (data == "banned") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $('.loginResult').append("<div data-alert class='alert-box alert'>You are banned<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');
                              });
                        }

                        //Unknown failure
                        if (data == "fail") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $('.loginResult').append("<div data-alert class='alert-box alert'>An unknown error occured<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');
                              });
                        }
                  });
}

});

      //Resend confirmation link
      $("#login").on("click", ".loginResendConfirm", function( event ){
            event.preventDefault();

            $('.alert-box').remove();
            $('.loadingGif').remove();

            var email = $('input[name=email]').val();
            var password = $('input[name=password]').val();

            var emailRegex = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
            var passRegex = /^.{6,20}$/;

            var proceed = true;
            
            if (emailRegex.test(email) == false) {
                  proceed = false;
                  $('.loginResult').append("<div data-alert class='alert-box alert'>Email invalid<a href='#' class='close'>&times;</a></div>");
                  $('.alert-box').slideDown('fast');
            }

            if(passRegex.test(password) == false){
                  proceed = false;
                  $('.loginResult').append("<div data-alert class='alert-box alert'>Password invalid<a href='#' class='close'>&times;</a></div>");
                  $('.alert-box').slideDown('fast');
            }

            if (proceed == true) {
                  post_data = {'email':email, 'password':password};

                  $('.loginResult').append('<img class="loadingGif" src="register/img/loading.gif"/>');
                  $('.loadingGif').css({'visability':'none','display':'block'});
                  $('.loadingGif').fadeIn('slow');

                  $.post('includes/reconfirm.php', post_data, function(data){

                        //Success
                        if (data == "success") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $(this).remove();
                                    $('.loginResult').append("<div data-alert class='alert-box success'>Confirmation link sent.<br /> Be sure to check your spam.<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');
                              });

                        }

                        //Email not found
                        if (data == "fail") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $(this).remove();
                                    $('.loginResult').append("<div data-alert class='alert-box alert'>Unable to send confirmation link.<br /> Did you change your inputs?<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');
                              });
                        }

                        //Send fail
                        if (data == "sendfail") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $(this).remove();
                                    $('.loginResult').append("<div data-alert class='alert-box alert'>Mail server was unable to send the email.<br/> Please try again later.<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');
                              });
                        }

                  });
}


});

/*---------------------- Forgot Password----------------------------------*/

$(".forgotPassButton").click(function( event ){
      event.preventDefault();

      $('.alert-box').remove();
      $('.loadingGif').remove();

      var email = $('input[name=forgotEmail]').val();

      var emailRegex = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

      var proceed = true;

      if (emailRegex.test(email) == false) {
            proceed = false;
            $('.forgotResult').append("<div data-alert class='alert-box alert'>Email invalid<a href='#' class='close'>&times;</a></div>");
            $('.alert-box').slideDown('fast');
      }

      if (proceed == true) {
            post_data = {'email':email};

            $('.forgotResult').append('<img class="loadingGif" src="register/img/loading.gif"/>');
            $('.loadingGif').css({'visability':'none','display':'block'});
            $('.loadingGif').fadeIn('slow');

            $.post('includes/forgotPass.php', post_data, function(data){

                        //Success
                        if (data == "success") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $(this).remove();
                                    $('.forgotResult').append("<div data-alert class='alert-box success'>Confirmation link sent.<br /> Be sure to check your spam.<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');
                              });

                        }

                        //Email not found
                        if (data == "fail") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $(this).remove();
                                    $('.forgotResult').append("<div data-alert class='alert-box alert'>Unable to send confirmation link.<br /> Did you change your inputs?<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');
                              });
                        }

                        //Send fail
                        if (data == "sendfail") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $(this).remove();
                                    $('.loginResult').append("<div data-alert class='alert-box alert'>Mail server was unable to send the email.<br/> Please try again later.<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');
                              });
                        }

                  });
}
});

//Reset pass
$(".resetPassButton").click(function( event ){
      event.preventDefault();

      $('.alert-box').remove();
      $('.loadingGif').remove();

      var password = $('input[name=password]').val();
      var confirm = $('input[name=confirm]').val();
      var split = location.search.replace('?', '').split('=')

      var regex = /^.{6,20}$/;

      var proceed = true;

      if (regex.test(password) == false) {
            proceed = false;
            $('.resetResult').append("<div data-alert class='alert-box alert'>Password invalid<a href='#' class='close'>&times;</a></div>");
            $('.alert-box').slideDown('fast');
      }

      if (password != confirm) {
            proceed = false;
            $('.resetResult').append("<div data-alert class='alert-box alert'>Passwords don't match<a href='#' class='close'>&times;</a></div>");
            $('.alert-box').slideDown('fast');
      }

      if (split[0] != "key" || split[1].length != 33) {
            proceed = false;
            $('.resetResult').append("<div data-alert class='alert-box alert'>Invalid reset key<a href='#' class='close'>&times;</a></div>");
            $('.alert-box').slideDown('fast');
      }

      if (proceed == true) {
            post_data = {'password':password, 'confirm':confirm, 'key':split[1]};

            $('.resetResult').append('<img class="loadingGif" src="../register/img/loading.gif"/>');
            $('.loadingGif').css({'visability':'none','display':'block'});
            $('.loadingGif').fadeIn('slow');

            $.post('resetpass.php', post_data, function(data){

                        //Success
                        if (data == "success") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $(this).remove();
                                    $('#resetPassForm').html("<div data-alert class='alert-box success'>Password reset.<br />Redirecting to log-in page....<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');

                                    setTimeout(function(){
                                          window.location.replace("http://hivemind.is");
                                    }, 2000);

                                    
                              });

                        }

                        //No match
                        if (data == "nomatch") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $(this).remove();
                                    $('.resetResult').append("<div data-alert class='alert-box alert'>Passwords do not match.<br /> Did you change your inputs?<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');
                              });
                        }

                        //No match
                        if (data == "invalidkey") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $(this).remove();
                                    $('.resetResult').append("<div data-alert class='alert-box alert'>Invalid reset key.<br /> Did you change your inputs?<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');
                              });
                        }

                  });
}
});

/*--------------------- REGISTER ------------------------------------*/

$('#register').keyup(function(){
      $('.alert-box').slideUp().queue(function() { $(this).remove(); });
});

      //Register Button
      $('#registerBtn').click(function(){

            $('.alert-box').remove();
            $('.loadingGif').remove();
            $('.registerError').empty();

            var email = $('input[name=email]').val();
            var password = $('input[name=password]').val();
            var confirm = $('input[name=confirm]').val();
            var alias = $('input[name=alias]').val();

            var emailRegex = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
            var passRegex = /^.{6,20}$/;
            var aliasRegex = /^.{3,30}$/;

            var proceed = true;
            
            if (emailRegex.test(email) == false) {
                  proceed = false;
            }

            if(passRegex.test(password) == false){
                  proceed = false;
            }

            if(aliasRegex.test(alias) == false){
                  proceed = false;
            }

            if (password !== confirm) {
                  proceed = false;
            }

            if ($('input[name=agree]').prop('checked') == false) {
                  proceed = false;
            }

            if (proceed == false) {
                  var message = registerValidateMessage(email, password, confirm, alias);

                  $('.registerError').append("<p class='panel'>" + message + "</p>");
            }
            
            if (proceed == true) {

                  post_data = {'email':email, 'password':password, 'alias':alias};

                  $('.registerResult').append('<img class="loadingGif" src="img/loading.gif"/>');
                  $('.loadingGif').css({'visability':'none','display':'block'});
                  $('.loadingGif').fadeIn('slow');


                  $.post('includes/register.php', post_data, function(data){

                        //Success
                        if (data == "success") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $(this).remove();
                                    $('.registerResult').append("<div data-alert class='alert-box success'>Registration successful.<br />Welcome to the Hive. A confirmation email has been sent to your email.<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');
                              });
                              
                        }

                        //Email Invalid
                        if (data == "emailinvalid") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $(this).remove();
                                    $('.registerResult').append("<div data-alert class='alert-box alert'>Email is invalid<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');
                              });
                        }

                        //Email Missing
                        if (data == "emailmissing") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $('.registerResult').append("<div data-alert class='alert-box alert'>Email is missing<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');
                              });
                        }

                        //Email taken
                        if (data == "emailtaken") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $(this).remove();
                                    $('.registerResult').append("<div data-alert class='alert-box alert'>Email is already a part of the Hive.<br /> One of us... One of us...<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');
                              });
                        }

                        //Alias missing
                        if (data == "aliasmissing") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $(this).remove();
                                    $('.registerResult').append("<div data-alert class='alert-box alert'>You need an alias<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');
                              });
                        }

                        //Password missing
                        if (data == "aliasmissing") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $(this).remove();
                                    $('.registerResult').append("<div data-alert class='alert-box alert'>Password is missing<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');
                              });
                        }

                        //Email sending failed
                        if (data == "sendfail") {
                              $('.loadingGif').fadeOut('slow').queue(function() {
                                    $(this).remove();
                                    $('.registerResult').append("<div data-alert class='alert-box alert'>Good news is, you registered.<br />Bad news, we couldn't send the confirmation email.<br />Have fun.<a href='#' class='close'>&times;</a></div>");
                                    $('.alert-box').slideDown('fast');
                              });
                        }

                  });
}

else{
      $('.registerResult').append("<div data-alert class='alert-box alert'>All fields are required.<a href='#' class='close'>&times;</a></div>");
      $('.alert-box').slideDown('fast');
}

});

function setAll (input, value){
      var n = input.length;

      for (var i = 0; i < n; i++) {
            input[i] = value;
      }
}

      //Búa til message-ið í registration
      function registerValidateMessage (email, password, confirm, alias){
            var emailRegex = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
            var passRegex = /^.{6,20}$/;
            var aliasRegex = /^.{3,30}$/;
            var error = [];
            var array = [];
            var message = "";

            error[0] = new Array();
            error[1] = new Array();
            error[2] = new Array();
            error[3] = new Array();

            var brightSide = [];
            brightSide[0] = new Array();
            brightSide[0][0] = "You did get the email right.";
            brightSide[0][1] = "The email is valid. Be happy.";
            brightSide[0][2] = "Be glad that the email is valid.";
            brightSide[0][3] = "Do not despair, I will not complain about the email.";
            brightSide[0][4] = "Be happy that the email is safe, for now.";

            brightSide[1] = new Array();
            brightSide[1][0] = "The password is valid";
            brightSide[1][1] = "A valid password is a happy password.";
            brightSide[1][2] = "Your password is ok at best.";
            brightSide[1][3] = "Wow, the password is not invalid, good going.";
            brightSide[1][4] = "Password is not invalid, but I am going to bitch because of reasons.";

            brightSide[2] = new Array();
            brightSide[2][0] = "The passwords match.";
            brightSide[2][1] = "Sunshine shines on your matching passwords.";
            brightSide[2][2] = "The passwords are matching, so kawaii desu desu.";
            brightSide[2][3] = "The passwords are wearing matching bracelets";
            brightSide[2][4] = "Matching passwords is a good thing.";

            brightSide[3] = new Array();
            brightSide[3][0] = "The alias is valid.";
            brightSide[3][1] = "Be thankful that I didn't bitch about your alias.";
            brightSide[3][2] = "The alias is fine.";
            brightSide[3][3] = "Oh my that alias is goofy, valid, but goofy.";
            brightSide[3][4] = "The alias is valid, but immensely stupid.";

            //Validate
            if (emailRegex.test(email) == false) {
                  array[0] = false;
                  error[0][0] = "The email is not correct";
                  error[0][1] = "You got the mail wrong. How?";
                  error[0][2] = "The email is invalid";
                  error[0][3] = "How could you get the email wrong?";
                  error[0][4] = "The email, it's like, incorrect";
                  error[0][5] = "Your email is not of this world";
                  error[0][6] = "I do not tolerate your email";
                  error[0][7] = "That email does not fulfil the requirements.";
            }

            if (array[0] == false && email == "") {
                  setAll(error[0], "Don't leave the email field blank.");
            }

            if (passRegex.test(password) == false) {
                  array[1] = false;
                  error[1][0] = "The password is invalid";
                  error[1][1] = "Your password is like not good enough for us.";
                  error[1][2] = "It would have been better just to write 'password'.";
                  error[1][3] = "The password committed suicide";
                  error[1][4] = "I don't like your password";
                  error[1][5] = "The password just left... it's gone.";
                  error[1][6] = "Vague hint: password";
                  error[1][7] = "Are you angry at your password right now?";
            }

            if (array[1] == false && password == "") {
                  setAll(error[1], "Don't leave the password field blank.");
            }

            if (password != confirm) {
                  array[2] = false;
                  error[2][0] = "Your passwords do not match";
                  error[2][1] = "Your passwords are having a feud";
                  error[2][2] = "There's war brewing between your passwords";
                  error[2][3] = "Your passwords are not soulmates";
                  error[2][4] = "The husbando password does not like the waifu password";
                  error[2][5] = "One password is North Korea, the other South Korea";
                  error[2][6] = "Password is hydrogen, the confirm password is a flame";
                  error[2][7] = "Confirming passwords... Nope.";
            }

            if (aliasRegex.test(alias) == false) {
                  array[3] = false;
                  error[3][0] = "The alias is invalid";
                  error[3][1] = "Your alias is unsuited for the Hive";
                  error[3][2] = "Please make a valid alias, I'm tired";
                  error[3][3] = "That is a horrible alias";
                  error[3][4] = "MY EYES! The horror! The alias!";
                  error[3][5] = "That alias makes me call death";
                  error[3][6] = "The alias is pretty bad";
                  error[3][7] = "Oh god, the alias is giving me cancer.";
            }

            if (array[3] == false && alias == "") {
                  setAll(error[3], "Don't leave the alias field blank.");
            }


            //Error message creation
            var rand;
            var bright = "";

            for (var i = 0; i < 4; i++) {

                  rand = 0 + Math.floor(Math.random() * 7);

                  if (i == 2 && confirm == "") {
                        message += "<span class='registerErrorside'>Don't leave the confirm password field blank.</span><br/>";
                  }

                  else if (array[i] == false) {
                        message += "<span class='registerErrorside'>" + error[i][rand] + "</span><br/>";
                  }

                  else{
                        rand = 0 + Math.floor(Math.random() * 4);
                        bright += "<span class='registerBrightside'>" + brightSide[i][rand] + "</span><br/>"; 
                  }
            }

            return message + bright;

      }

      $("#threadContainer").on('click', 'a.replyButton', function() {

          var postID = this.parentNode.parentNode.id;
          postID = postID.replace("p_", "");

          var threadID = this.parentNode.parentNode.parentNode.id;
          threadID = threadID.replace("threadID_", "");

          if (currentThread != threadID) {

            window.location = '?nav=' + board + ':1:' + threadID; 

      } else {

          desiredThread = threadID;
          desiredPost = postID;
        /*var Class = this.className;
        Class = Class.split(" ");
        Class = Class[2].replace("p_"," ");
        Class = Class.split(":");

        var postID = Class[0];
        var threadID = Class[1];

        desiredThread = threadID;
        desiredPost = postID;

        */
        var text = $('textarea[name=comment]').val();
        $('textarea[name=comment]').val(text + ">>" + desiredPost + "\n");

  }

});

      //Delete thread
      $("#threadContainer").on('click', 'a.deleteThread', function() {

            var id = this.parentNode.id;
            id = id.replace("tid_", "");
            $.post( "../includes/buzzfeed/deleteThread.php", {'id':id}, function( data ) {

                  $("#tid_" + id).slideUp();
                  $("#threadID_" + id).slideUp();
                  //window.location = '?nav=' + board + ':1';

            });

      });

      //What thread is being relocated.
      var relocate = "";
      $(".relocateThread").click(function(){
            relocate = $(this);
      });

      //Relocate thread
      $(".relocateThreadOption").click(function(event) {

            event.preventDefault(); //Prevent anchor tag default

            var newBoard = $(this).data("value"); //Get the value of what board it should be moved to
            var id = relocate.closest('.threadHeader').attr("id"); //Closest Thread headder in order to obtain ID. Uses the "relocate" variable
            id = id.replace("tid_", ""); //Get thread ID number
            post_data = {'id':id, 'board':newBoard}; //the post data

            //Ajax
            $.post( "../includes/buzzfeed/relocateThread.php", post_data, function( data ) {

                  //If success
                  if (data == "moved") {
                        alert("this thread has been moved");
                        window.location = '?nav=' + board + ':1'; 
                  }

                  //If not admin
                  if (data == "notAdmin"){
                        alert("You do not have the authority to do this");
                  }

                  //If the board specified doesn't exist
                  if (data == "notBoard"){
                        alert("The specified board does not exist");
                  }

            });

      });

      //Relocate thread
      $(".deletePost").click(function(event) {

            event.preventDefault(); //Prevent anchor tag default

            //$(this).parent().parent().children('.row').children('.replyButton').css( "border", "3px double red" );
            //var postInfo = $(this).parent().parent().children('.row').children('.replyButton').text(); //Target the replybutton for the text in order to get postID
            var postInfo = this.parentNode.parentNode.id;
            postInfo = postInfo.replace("p_", "");

            post_data = {'postInfo':postInfo}; //the post data

            //Ajax
            $.post( "../includes/buzzfeed/deletePost.php", post_data, function( data ) {

                  //If success
                  if (data == "deleted") {
                        alert("this post has been deleted");
                        window.location = '?nav=' + board + ':1'; 
                  }

                  //If not admin
                  if (data == "notAdmin"){
                        alert("You do not have the authority to do this");
                  }

            });

      });

      //Report post
      var postInfo;

      $(".reportPost").click(function(event) {
            event.preventDefault(); //Prevent anchor tag default

            postInfo = $(this).parent().parent().children('.row').children('.replyButton').text(); //Target the replybutton for the text in order to get postID

      });

      $(".reportButton").click(function(event) {
            event.preventDefault(); //Prevent anchor tag default

            var reason = $('select[name=reason]').val();
            var details = $('input[name=details]').val();
            var agree = $('input[name=reportAgree]').prop('checked');

            var proceed = true;

            if (details.length > 255) {
                  alert("Details cannot bee over 255 characters");
                  proceed = false;
            }

            if (agree == false) {
                  alert("You must accept.");
                  proceed = false;
            }

            if (proceed == true) {
                  agree = "true";
                  post_data = {'postInfo':postInfo, 'reason':reason, 'details':details, 'agree':agree}; //the post data

                  //Ajax
                  $.post( "../includes/buzzfeed/reportPost.php", post_data, function( data ) {

                        //If success
                        if (data == "reported") {
                              alert("This post has been reported"); 
                        }

                  });   
            }

      });


//Sending
$(".prefix").click(function() {

    var comment = $('textarea[name=comment]').val();
    var file = $('input[name=file]').val();

    if (currentThread > 0) {

      desiredThread = currentThread;

    }

    if (comment.length > 0 && comment.length < 1501 || file.length > 0) {

        if (file.length > 0) {

            var File = document.getElementById('file');
            if (File.files[0].size < 8388608) {

                post_data = {'id':desiredThread, 'comment':comment, 'board':board};
                $.post( "../includes/buzzfeed/post.php", post_data, function( data ) {

                    location.reload();
                    if ((file.length > 0) && (data == "Success!")) {
                        $("#postThread").submit();
                  }

            });

          } else { 

                alert("File is too big, try again") 

          }

    } else {

      post_data = {'id':desiredThread, 'comment':comment, 'board':board};
      $.post( "../includes/buzzfeed/post.php", post_data, function( data ) {

          location.reload();

    });

}

} else {

  alert("In order to post you need to either write something or upload an image");

}

});

$('.comment').css('overflow', 'hidden').autogrow();

//Char limit indicator
$('.comment').keyup(function(){
      var remaining = 1500 - $('.comment').val().length;
      $('.countdown').text(remaining);
});

//Navigation
$(".navPage").click(function() {

    window.location = '?nav=' + board + ':' + this.text;

});

});
