jQuery(function($){

  $(".js_treeWidgetSelect__select").on("change", function(e){
  
    e.preventDefault();
    if (val = $(this).val()) {
      location.href = val;
    }
  });
});
