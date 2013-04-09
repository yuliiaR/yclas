$(function  () {
   var group = $("ul.plholder").sortable({
          group: 'plholder',
          onDrop: function (item, container, _super) {
            $('#serialize_output').text(group.sortable("serialize").get().join("\n"))
            _super(item, container)

          },
          serialize: function (parent, children, isContainer) {
            return isContainer ? children.join() : parent.attr("id")
          }
    });

})