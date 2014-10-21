
$(document).ready(function() {

  /*---------------------------------

      Wow            such code
               wow

      ░░░░░░░░░▄░░░░░░░░░░░░░░▄ 
      ░░░░░░░░▌▒█░░░░░░░░░░░▄▀▒▌ 
      ░░░░░░░░▌▒▒█░░░░░░░░▄▀▒▒▒▐ 
      ░░░░░░░▐▄▀▒▒▀▀▀▀▄▄▄▀▒▒▒▒▒▐ 
      ░░░░░▄▄▀▒░▒▒▒▒▒▒▒▒▒█▒▒▄█▒▐ 
      ░░░▄▀▒▒▒░░░▒▒▒░░░▒▒▒▀██▀▒▌ 
      ░░▐▒▒▒▄▄▒▒▒▒░░░▒▒▒▒▒▒▒▀▄▒▒ 
      ░░▌░░▌█▀▒▒▒▒▒▄▀█▄▒▒▒▒▒▒▒█▒ 
      ░▐░░░▒▒▒▒▒▒▒▒▌██▀▒▒░░░▒▒▒▀ ▌ 
      ░▌░▒▄██▄▒▒▒▒▒▒▒▒▒░░░░░░▒▒▒ ▌ 
      ▀▒▀▐▄█▄█▌▄░▀▒▒░░░░░░░░░░▒▒ ▐ 
      ▐▒▒▐▀▐▀▒░▄▄▒▄▒▒▒▒▒▒░▒░▒░▒▒ ▒▌ 
      ▐▒▒▒▀▀▄▄▒▒▒▄▒▒▒▒▒▒▒▒░▒░▒░▒ ▐ 
      ░▌▒▒▒▒▒▒▀▀▀▒▒▒▒▒▒░▒░▒░▒░▒▒ ▌ 
      ░▐▒▒▒▒▒▒▒▒▒▒▒▒▒▒░▒░▒░▒▒▄▒▒ 
      ░░▀▄▒▒▒▒▒▒▒▒▒▒▒░▒░▒░▒▄▒▒▒▒ 
      ░░░░▀▄▒▒▒▒▒▒▒▒▒▒▄▄▄▀▒▒▒▒▄▀ 
      ░░░░░░▀▄▄▄▄▄▄▀▀▀▒▒▒▒▒▄▄▀ 
      ░░░░░░░░░▒▒▒▒▒▒▒▒▒▒   reloading

  ajax
---------------------------------*/

  //---------------------------------
  var desiredThread   = -1; //-------
  var desiredPost     = null; //-----
  /*var currentURL      = document.URL;
  var defaultBoard    = "b"; //------
  var defaultPage     = 1; //--------
  var board           = defaultBoard;
  var page            = defaultPage;
  var index_start     = 0; //--------
  var index_end       = 6; //--------
  //---------------------------------

  //?nav=board#page#threadID
  initializeImageboard();

  function initializeImageboard()
  {

    clearContainer();
    currentURL = currentURL.split("/");
    if (currentURL[(currentURL.length-1)].substring(0,4) == "?nav") {

      var splitSpec = currentURL[(currentURL.length-1)].split("=");

      var a = splitSpec[1].split("#");
      var b = null;

      if (a.length > 0) {

        splitSpec = splitSpec[1].split("#");
        b = splitSpec[0];

      } else {

        b = splitSpec[1];

      }

      board = splitSpec[1];
      var tid = "";

      if (b != null) {

        splitSpec = b.split(":");

        board = splitSpec[0];
        page = splitSpec[1];

        if (splitSpec.length == 3) {

          tid = splitSpec[2];

        }

      }

      renderThreads(board,page,tid);

    } else {

      window.location = '?nav=' + board + ':1';

    }

  }

  function navigate(page)
  {

    var pages = new Array();
    pages[1] = [0,6];
    pages[2] = [6,12];
    pages[3] = [12,18];
    pages[4] = [18,24];
    pages[5] = [24,30];
    pages[6] = [36,42];

    var istart = pages[page][0];
    var iend = pages[page][1];
    return [istart, iend];

  }

  // Clear #threadContainer
  function clearContainer()
  {

      // simple and quick
      $("#threadContainer").html("");

    }

  // 6 threads per page ?
  function renderThreads(board, page, idThread) 
  {

    if (idThread.length > 0) {

      index_start = 0;
      index_end = 1;

    } else {

      var indexes = navigate(page);
      index_start = indexes[0];
      index_end = indexes[1];

    }

    $.post( "../includes/buzzfeed/gethreads.php", { 'board':board }, function( data ) 
    {

      var timestamps   = new Array();
      timestamps[0]    = new Array();
      timestamps[1]    = new Array();
      var threads      = new Array();
      var rData        = JSON.parse(data);
      var finData      = new Array();

      //alert(JSON.parse(returnData[1])[3]);
      // Sort by time
      for (var i=0;i<rData.length;i++)
      { 

        // don't think about the following lines of code, just embrace them
        var jrData = JSON.parse(rData[i]);

        var splitPostDate = jrData[7].split(" ");
        var splitDate = splitPostDate[0].split("-");
        var splitTime = splitPostDate[1].split(":");
        var threadID = jrData[0];

        var time = parseInt(splitDate[0] + splitDate[1] + splitDate[2] + splitTime[0] + splitTime[1] + splitTime[2]);

        if (jrData[3] == 0) {

          timestamps[0].push({"time":time, "threadID":threadID, "index":i});

        }

        for (key in timestamps[0])
        { 

          if (timestamps[0][key]["threadID"] == threadID) {

            if ((timestamps[0][key]["time"] - time) < 0) {

              timestamps[0][key]["time"] = time;

            }

          }

        }

        timestamps[1].push({"time":time, "threadID":threadID, "index":i});

      }

      // SORT SHIT
      timestamps[1].sort( function(a,b) { 

        return parseFloat(b.time) - parseFloat(a.time) 

      });

      timestamps[0].sort( function(a,b) { 

        return parseFloat(b.time) - parseFloat(a.time) 

      });

      var x = 0;
      for (var a=0;a<timestamps[0].length;a++)
      { 

        var jrData = JSON.parse(rData[(timestamps[0][a]["index"])]);
        var index = jrData[3];
        var threadID = jrData[0];

        // check if post is OP
        if (index == 0) {

          finData[x] = new Array();
          finData[x][0] = jrData;

          var y = 1;
          for (key in timestamps[1])
          {

            jrData = JSON.parse(rData[(timestamps[1][key]["index"])]);

            if (jrData[3] != 0) {

              if (jrData[0] == threadID) {

                finData[x][y] = jrData;
                y = y + 1;

              }

            }

          }

          x = x + 1;

        }

      }

      for (var i=index_start;i<index_end;i++) // OP
      {

        var j = idThread;

        for (key in finData) {

          if (finData[key][0][0] == idThread) {

            j = key;

          }

        }

        var z = 1;
        if (!j.length > 0) {

          j = i;

          if (finData[j].length > 3) {

            z = (finData[j].length - 2);

          }

        }

        if (finData[j][0][8] == board) {

          var id        = finData[j][0][0];
          var timestamp = finData[j][0][7];
          var comment   = finData[j][0][4];

          renderThread(id, timestamp, comment, j);

          if (finData[j].length > 0) {

            for (var x=z;x<finData[j].length;x++)
            {

              var id        = finData[j][x][0];
              var alias     = finData[j][x][1];
              var postID    = finData[j][x][2];
              var index     = finData[j][x][3];
              var comment   = finData[j][x][4];
              var image     = finData[j][x][5];
              var status    = finData[j][x][6];
              var timestamp = finData[j][x][7];

              renderReply(id, alias, postID, index, comment, image, status, timestamp);

            }

          }

          id        = finData[j][0][0];
          alias     = finData[j][0][1];
          postID    = finData[j][0][2];
          index     = finData[j][0][3];
          comment   = finData[j][0][4];
          image     = finData[j][0][5];
          status    = finData[j][0][6];
          timestamp = finData[j][0][7];

          renderReply(id, alias, postID, index, comment, image, status, timestamp);

        }

      }

      function renderThread(threadID, timestamp, comment, i) {

        var cmnt = comment;

        if (cmnt != "") {

          if (cmnt.length > 30) {

            cmnt = cmnt.substring(0,30) + "..."; 

          }

        } else {

          cmnt = "[image]";

        }
            // <a class="openThread" id="i ' + i + '">S</a>  
            $("#threadContainer").append('<div id="tid_' + threadID + '" class="threadHeader row"><p class="headerText">' + timestamp + " | " + '<a href="?nav=' + board + ':' + page + ':' + threadID + '" class="openThread">' + threadID + '</a>' + " | " + cmnt + '</p></div>');
            $("#threadContainer").append('<div id="threadID_' + threadID + '" class="row"></div>');

          }

          function renderReply(threadID, alias, postID, index, comment, img, status, timestamp) 
          {

            var image = img;

            var hasimage = false;

            if (image.substring(0,1) == "t") {

              hasimage = true;
              image    = image.split(":");

            }

            var colorClass = "";

            if (status == "beekeeper") { 

              colorClass = "beekeeper"; 

            } else if (status == "queen_bee") {

              colorClass = "queenbee";

            } else if (status == "removed") {

              colorClass = "removed";

            }

            var elements = [];
        // headpost
        elements[0] = '<div id="tid_' + threadID + '" class="panel headPost Posts">';
        // replypost
        elements[1] = '<div id="tid_' + threadID + '" class="panel replyPost Posts">';
        // replyButton
        elements[2] = '<div class="row"><p class="status">'+ timestamp + '</p><a href="#postThread" class="replyButton ' + colorClass + ' p_' + postID + ':' + threadID + '">' + alias + ' Nr. ' + postID + '</a></div>';
        // text only
        elements[3] = '<pre><p class="postText">' + comment + '</p></pre></div>';
        // image and text
        elements[4] = '<pre><p class="postText"><a href="img/posts/' + postID + '.' + image[1] + '"><img class="postImage" src="img/posts/thumbs/' + postID + '.jpeg"></a>' + comment + '</p></pre></div>';

        if ( index == 0 ) { // OP

          if (hasimage == true) { 

            $("#threadID_" + threadID).prepend(elements[0] + elements[2] + elements[4]);

          } else {

            $("#threadID_" + threadID).prepend(elements[0] + elements[2] + elements[3]);

          }

        } else { // Reply

          if (hasimage == true) { 

            $("#threadID_" + threadID).prepend(elements[1] + elements[2] + elements[4]);

          } else {

            $("#threadID_" + threadID).prepend(elements[1] + elements[2] + elements[3]);

          }

        }

        //autoCollapse(0,6);

      }  

    });

}

/*$("#threadContainer").on('click', '.threadHeader', function() {

  var Class = this.id;
  Class = Class.split("_");
  $("#threadID_" + Class[1]).slideToggle();

});*/

/*Char limit indicator
function charLimit(){
  var remaining = 1500 - $('.comment').val().length;
  $('.countdown').text(remaining);
}

$('.comment').outerHeight("50px");

$('.comment').keyup(function(){
  charLimit();
  $(this).height( this.scrollHeight );
});*/
/*
//Replying
$("#threadContainer").on('click', 'a.replyButton', function() {

  var Class = this.className;
  Class = Class.split(" ");
  Class = Class[2].replace("p_"," ");
  Class = Class.split(":");
  alert("no");

  var postID = Class[0];
  var threadID = Class[1];

  desiredThread = threadID;
  desiredPost = postID;

  var text = $('textarea[name=comment]').val();
  $('textarea[name=comment]').val(text + ">>" + desiredPost + "\n");

});

//Sending
$(".prefix").click(function() {

  var comment = $('textarea[name=comment]').val();
  var file = $('input[name=file]').val();

  if (comment.length > 0 && comment.length < 1501 || file.length > 0) {

    if (file.length > 0) {

      var File = document.getElementById('file');
      if (File.files[0].size < 3245728) {

        post_data = {'id':desiredThread, 'comment':comment, 'board':board};
        $.post( "../includes/buzzfeed/post.php", post_data, function( data ) {

          alert(data);
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

        alert(data);
        location.reload();

      });

    }

  } else {

    alert("In order to post you need to either write something or upload an image");

  }

});

//Navigation
$(".navPage").click(function() {

  window.location = '?nav=' + board + ':' + this.text;

});

});*/