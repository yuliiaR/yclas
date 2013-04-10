$(function  () {
   var group = $("ul.plholder").sortable({
          group: 'plholder',
          onDrop: function (item, container, _super) {
        
            var data = {};
            $('.plholder').each(function() {
                val = $(this).sortable().sortable('serialize').get();
                if (val == '[object HTMLUListElement]') val = '';
                data[this.id] = val;
            });
            
            $.ajax({
                type: "GET",
                url: $('#ajax_result').data('url'),
                data: data,
                success: function(text) {
                    $('#ajax_result').text(text);
                }               
            });

            _super(item, container);
          },
          serialize: function (parent, children, isContainer) {
            return isContainer ? children.join() : parent.attr("id");
          }
    });

})