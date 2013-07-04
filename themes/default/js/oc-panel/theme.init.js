$(function (){
    $('.tips').popover();
    $("select").chosen();

    $('#formorm_description, textarea[name=description]').addClass('span6').sceditorBBCodePlugin({
        toolbar: "bold,italic,underline,strike|left,center,right,justify|" +
        "bulletlist,orderedlist|link,unlink|source",
        resizeEnabled: "true"});

    $('.radio > input:checked').parentsUntil('div .accordion').addClass('in');
});

_debounce = function(func, wait, immediate) {
    var timeout, result;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) result = func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) result = func.apply(context, args);
        return result;
    };
};