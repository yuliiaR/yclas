$(function  () {
    var group = $("ol.plholder").sortable({
        group: 'plholder',
        onDrop: function (item, container, _super) {
            //first we execute the normal plugins behaviour
            _super(item, container);

            //where we drop the category
            var parent = $(item).parent();

            //values of the list
            val = $(parent).sortable().sortable('serialize').get();
            val = val[0].split(',');

            //how deep are we?
            var deep = $(item).parentsUntil($("ol.plholder"),'ol')['length'];
            

            //building data to send
            var data = {
                  "id_category" : $(item).data('id'),
                  "id_category_parent" : $(parent).data('id'),
                  "order" : $.inArray($(item).attr('id'),val),
                  "deep" : deep,
                };

            //saving the order
            $.ajax({
                type: "GET",
                url: $('#ajax_result').data('url'),
                beforeSend: function(text) {
                    $('#ajax_result').text('Saving').removeClass().addClass("label label-warning");
                },
                data: data,
                success: function(text) {
                    $('#ajax_result').text(text).removeClass().addClass("label label-success");
                }               
            });
        
             
        },
        serialize: function (parent, children, isContainer) {
             return isContainer ? children.join() : parent.attr("id");
        },

    })
})


function dump(obj) {
    var out = '';
    for (var i in obj) {
        out += i + ": " + obj[i] + "\n";
    }

    alert(out);
}