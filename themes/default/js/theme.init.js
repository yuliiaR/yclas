$(function(){
    
    //sceditorBBCodePlugin for validation, updates iframe on submit 
$("button[name=submit]").click(function(){
    $("textarea[name=description]").data("sceditor").updateOriginal();
});
    
    //select2 enable/disable
    $('select').select2({
        "language": "es"
    });
    $('select').each(function(){
        if($(this).hasClass('disable-select2')){
            $(this).select2('destroy');      
        } 
    });
    //select2 responsive width
    $(window).on('resize', function() {
        $('select').each(function(){
            var width = $(this).parent().width();
            $(this).siblings('.select2-container').css({'width':width});
        });
    }).trigger('resize');
    
    $('input, select, textarea, .btn').tooltip();

    //datepicker in case date field exists
    if($('.cf_date_fields').length != 0){
        $('.cf_date_fields').datepicker();}

	$('.tips').popover();

	$('.slider_subscribe').slider();

    $('.radio > input:checked').parentsUntil('div .accordion').addClass('in');

    $(window).load(function(){
        $('#accept_terms_modal').modal('show');
    });

});

function setCookie(c_name,value,exdays)
{
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays==null) ? "" : ";path=/; expires="+exdate.toUTCString());
    document.cookie=c_name + "=" + c_value;
}
function getCookie(name) {
  var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  if (parts.length == 2) return parts.pop().split(";").shift();
}

$(function(){
    var maxHeight = 0;
    $(".latest_ads").each(function() {
        if ($(this).outerHeight() > maxHeight) {
            maxHeight = $(this).outerHeight();
        }
    }).height(maxHeight);
});

var savedRate, savedCurrency, siteCurrency;
siteCurrency = getlocale();
savedCurrency = getCookie('site_currency');
if (savedCurrency == undefined) {
    savedRate = 1;
    savedCurrency = siteCurrency;
}else {
    savedRate = getCookie('site_rate');
    savedCurrency = getCookie('site_currency');
    rate = parseFloat(savedRate);
    var prices = $('.price-curry'), money;
    prices.each(function(){
      money = $(this).text();
      money = Number(money.replace(/[^0-9\.]+/g, ''));
      converted = rate * money;
      var symbols = ({
          'USD': '&dollar;',
          'GBP': '&pound;',
          'EUR': '&euro;',
          'JPY': '&yen;'
        });
      	converted = Number(converted.toString().match(/^\d+(?:\.\d{2})?/));
        symbol = symbols[savedCurrency] || savedCurrency;
        $(this).text($(this).html(symbol + ' ' + converted).text());
    });
 }

$(function(){
 $('.my-future-ddm').curry({
    change: true,
    target: '.price-curry',
    base: savedCurrency == undefined?siteCurrency:savedCurrency,
    symbols: {}
 }).change(function(){
    var selected = $(this).find(':selected'), // get selected currency
    currency = selected.val(); // get currency name
  
    getRate(siteCurrency, currency);
    setCookie('site_currency', currency, { expires: 7, path: '' });

 });
         
});

function getRate(from, to) {
    var script = document.createElement('script');
    script.setAttribute('src', "http://query.yahooapis.com/v1/public/yql?q=select%20rate%2Cname%20from%20csv%20where%20url%3D'http%3A%2F%2Fdownload.finance.yahoo.com%2Fd%2Fquotes%3Fs%3D"+from+to+"%253DX%26f%3Dl1n'%20and%20columns%3D'rate%2Cname'&format=json&callback=parseExchangeRate");
    document.body.appendChild(script);
}

function parseExchangeRate(data) {
    var name = data.query.results.row.name;
    var rate = parseFloat(data.query.results.row.rate, 10);
    //console.log(rate);
    setCookie('site_rate', rate, { expires: 7, path: '' });
}

$(document).ready(function() {
  $('.selectpicker').selectpicker({
    style: 'btn-default',
    size: false
  });
});