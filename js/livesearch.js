// livesearch.js
$(document).ready(function() {
    $("#item-name").on("input", function() {
      var query = $("#item-name").val();
      var store = $("#store").val();
      var location = $("#location").val();
  
      if (query.length >= 2) {
        $.get("livesearch.php?item-name=" + query + "&store=" + store + "&location=" + location, function(data) {
          var suggestions = JSON.parse(data);
          $("#suggestions").empty();
  
          suggestions.forEach(function(suggestion) {
            var listItem = $("<li></li>").text(suggestion.name);
            listItem.on("click", function() {
              $("#item-name").val(suggestion.name);
              $("#suggestions").empty();
              // Submit the search form
              $("form[method='post']").submit();
            });
            $("#suggestions").append(listItem);
          });
        });
      } else {
        $("#suggestions").empty();
      }
    });
  });
  