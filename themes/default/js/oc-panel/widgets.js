$(function  () {
   var group = $("ul.plholder").sortable({
          group: 'plholder',
          onDrop: function (item, container, _super) {
        
             _super(item, container);

            var item_id = item.attr("id");
            var placeholder = '';
            var data = {};
            $('.plholder').each(function() {
                val = $(this).sortable().sortable('serialize').get();

                //empty UL
                if (val == '[object HTMLUListElement]') {
                    val = '';
                }
                else{
                    //array of values
                    val = val[0].split(',');

                    //we get the placeholder where we drop
                    if ($.inArray(item_id,val)>-1)
                        placeholder = this.id;
                }
                
                //generating the array to send to the server
                data[this.id] = val;
            });

            //item update the placeholder form input
            var input_placeholder = $('#form_widget_'+item_id+' [name=placeholder]');
            input_placeholder.val(placeholder);
            input_placeholder.trigger("liszt:updated");
            
            //saving the order
            $.ajax({
                type: "GET",
                url: $('#ajax_result').data('url'),
                data: data,
                success: function(text) {
                    $('#ajax_result').text(text);
                }               
            });

          },
          serialize: function (parent, children, isContainer) {
            return isContainer ? children.join() : parent.attr("id");
          }
    });

})


// var searchLocation = _debounce(function(query, process)
//     {
//         $.get( $('#city').data('source'), { s: query, countries: $('#countries').val() }, function ( data ) 
//         {
//             location = {};
//             locationLabels = [];
//             data = $.parseJSON(data);
//             for (var item in data) 
//             {
//                 location[ data[item].label ] = {
//                     label: data[item].label,
//                     id: data[item].id,
//                 }
//                 locationLabels.push( data[item].label );
//             }    
//             process( locationLabels );
//         });
//     },300);