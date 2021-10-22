function uniqueCheck(url, inputBox, id, defaultValue = "") {
  value = $(inputBox).val();
  $.ajax({
      url: location.protocol + "//" + location.host + url,
      type: "POST",
      data: {
        term: value,
        id: id,
      },
      cache: false,
      success: function(dataResult){
        var dataResult = JSON.parse(dataResult);
        if(dataResult.statusCode==200) {
          if (dataResult.unique == false) {
            alert(value.concat(" is not unique in the database.  Please try a different value."));
            $(inputBox).val(defaultValue);
          }
        }
        else if(dataResult.statusCode==201) {  // Error
          alert("Error checking if ".concat(value, " already exists in the database.\nPlese reload the page and try again."));
        }
      }
    });
}
