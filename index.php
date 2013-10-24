<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>LinkedIn JavaScript API Sample Application</title>
<!-- Load in the JavaScript framework and register a callback function when it's loaded -->
<script type="text/javascript" src="http://platform.linkedin.com/in.js">/*
api_key: pivh49jw1izb
onLoad: onLinkedInLoad
authorize: true
*/</script>
 
<script type="text/javascript">
function onLinkedInLoad() {
     // Listen for an auth event to occur
     IN.Event.on(IN, "auth", onLinkedInAuth);
}
 
function onLinkedInAuth() {
     // After they've signed-in, print a form to enable keyword searching
     var div = document.getElementById("peopleSearchForm");
 
     div.innerHTML = '<h2>Find People on LinkedIn</h2>';
     div.innerHTML += '<form action="javascript:PeopleSearch();">' +
                      '<input id="keywords" size="30" value="JavaScript Ninja" type="text">' +
                      '<input type="submit" value="Search!" /></form>';
}
 
function PeopleSearch(keywords) {
     // Call the PeopleSearch API with the viewer's keywords
     // Ask for 4 fields to be returned: first name, last name, distance, and Profile URL
     // Limit results to 10 and sort by distance
     // On success, call displayPeopleSearch(); On failure, do nothing.
     var keywords = document.getElementById('keywords').innerText; 
     IN.API.PeopleSearch()
         .fields("firstName", "lastName", "distance", "siteStandardProfileRequest")
         .params({"keywords": keywords, "count": 10, "sort": "distance"})
         .result(displayPeopleSearch)
         .error(function error(e) { /* do nothing */ }
     );
}
 
function displayPeopleSearch(peopleSearch) {
     var div = document.getElementById("peopleSearchResults");
 
     div.innerHTML = "<ul>";
 
     // Loop through the people returned
     var members = peopleSearch.people.values;
     for (var member in members) {
 
         // Look through result to make name and url.
         var nameText = members[member].firstName + " " + members[member].lastName;
         var url = members[member].siteStandardProfileRequest.url;
 
         // Turn the number into English
         var distance = members[member].distance;
         var distanceText = '';
         switch (distance) {
         case 0:  // The viewer
             distanceText = "you!"
             break;
         case 1: // Within three degrees
         case 2: // Falling through
         case 3: // Keep falling!
             distanceText = "a connection " + distance + " degrees away.";
             break;
         case 100: // Share a group, but nothing else
             distanceText = "a fellow group member.";
             break;
         case -1: // Out of netowrk
         default: // Hope we never get this!
             distanceText = "far, far, away.";
         }
 
         div.innerHTML += "<li><a href=\"" + url + "\">" + nameText + 
         "</a> is " + distanceText + "</li>"
     }
 
     div.innerHTML += "</ul>";
}
</script>
</head>
<body>
<script type="IN/Login"></script>
<div id="peopleSearchForm"></div>
<div id="peopleSearchResults"></div>
</body>
</html>