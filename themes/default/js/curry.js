/*!
 * Curry currency conversion jQuery Plugin v0.8.1
 * https://bitbucket.org/netyou/curry-currency-ddm
 *
 * Copyright 2015, NetYou
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/GPL-2.0
 */
function getlocale() {
	switch($('.curry').data('locale')){
	    case 'en_US':
	        siteCurrency = 'USD';
	        break;
	    case 'en_UK':
	        siteCurrency = 'GBP';
	        break;
	    case 'ar':
	        siteCurrency = 'AED';
	        break;
	    case 'bg_BG':
	        siteCurrency = 'BGN';
	        break;
	    case 'bn_BD':
	        siteCurrency = 'BDT';
	        break;
	    case 'ca_ES':
	        siteCurrency = 'EUR';
	        break;
	    case 'cs_CZ':
	        siteCurrency = 'CZK';
	        break;
	    case 'da_DK':
	        siteCurrency = 'DKK';
	        break;
	    case 'de_DE':
	        siteCurrency = 'EUR';
	        break;
	    case 'el_GR':
	        siteCurrency = 'EUR';
	        break;
	    case 'en_UK':
	        siteCurrency = 'GBP';
	        break;
	    case 'es_ES':
	        siteCurrency = 'EUR';
	        break;
	    case 'fr_FR':
	        siteCurrency = 'EUR';
	        break;
	    case 'hi_IN':
	        siteCurrency = 'INR';
	        break;
	    case 'hr_HR':
	        siteCurrency = 'HRK';
	        break;
	    case 'hu_HU':
	        siteCurrency = 'HUF';
	        break;
	    case 'in_ID':
	        siteCurrency = 'IDR';
	        break;
	    case 'it_IT':
	        siteCurrency = 'EUR';
	        break;
	    case 'ja_JP':
	        siteCurrency = 'JPY';
	        break;
	    case 'ml_IN':
	        siteCurrency = 'INR';
	        break;
	    case 'nl_NL':
	        siteCurrency = 'EUR';
	        break;
	    case 'no_NO':
	        siteCurrency = 'NOK';
	        break;
	    case 'pl_PL':
	        siteCurrency = 'PLN';
	        break;
	    case 'pt_PT':
	        siteCurrency = 'EUR';
	        break;
	    case 'ro_RO':
	        siteCurrency = 'RON';
	        break;
	    case 'ru_RU':
	        siteCurrency = 'RUB';
	        break;
	    case 'sk_SK':
	        siteCurrency = 'EUR';
	        break;
	    case 'sn_ZW':
	        siteCurrency = 'USD';// ZWD not available
	        break;
	    case 'sq_AL':
	        siteCurrency = 'ALL';
	        break;
	    case 'sr_RS':
	        siteCurrency = 'RSD';
	        break;
	    case 'sv_SE':
	        siteCurrency = 'SEK';
	        break;
	    case 'ta_IN':
	        siteCurrency = 'INR';
	        break;
	    case 'tl_PH':
	        siteCurrency = 'PHP';
	        break;
	    case 'tr':
	        siteCurrency = 'TRY';
	        break;
	    case 'ur_PK':
	        siteCurrency = 'PKR';
	        break;
	    case 'vi_VN':
	        siteCurrency = 'VND';
	        break;
	    case 'zh_CN':
	        siteCurrency = 'CNY';
	        break;
	    default:
	        siteCurrency = 'USD';
	        break;
	}
	return siteCurrency;
}

(function($) {

  $.fn.curry = function(options) {

    // Setup a global cache for other curry plugins
    if (!window.jQCurryPluginCache)
      window.jQCurryPluginCache = [{}, false];

    var output = '',
      rates = {},
      t = this,
      requestedCurrency = window.jQCurryPluginCache[1],
      $document = $(document),
      dropDownMenu,
      item, keyName,
      i, l, rate;

    // Create some defaults, extending them with any options that were provided
    var settings = $.extend({
      target: 'price-curry',
      change: true,
      base: siteCurrency,
      symbols: {}
    }, options);

    this.each(function() {

      var $this = $(this),
        id = $this.attr('id'),
        classes = $this.attr('class'),
        attrs = '',
        tempHolder;

      // Add class or id if replaced element had either of them
      attrs += id ? ' id="' + id + '"' : '';

      if (classes) {

        attrs += ' class="selectpicker curry-ddm';

        if (classes)
          attrs += ' ' + classes + '"';
        else
          attrs += '"';

      } else {

        attrs += '';

      }

      // keep any classes attached to the original element
      output = '<select' + attrs + '></select>';

      // Replace element with generated select
      tempHolder = $(output).insertAfter($this);
      $this.detach();

      // Add new drop down to jquery list (jquery object)
      dropDownMenu = !dropDownMenu ? tempHolder : dropDownMenu.add(tempHolder);

    });

    // Create the html for the drop down menu
    var generateDDM = function(rates) {

      output = '';

      // Change all target elements to drop downs
      dropDownMenu.each(function() {

        for (i in rates) {

          rate = rates[i];

          output += '<option value="' + i + '" data-rate="' + rate + '">' + i + '</option>';

        }

        $(output).appendTo(this);

        $('.selectpicker').selectpicker('refresh');
      });

    };

    if (!settings.customCurrency) {

      // Only get currency hash once
      if (!requestedCurrency) {
        var query = '';
        var selected_currencies = $('.curry').data('currencies');
        selected_currencies = selected_currencies.split(',');
        // Request currencies from yahoo finance
        if(selected_currencies == '') {
          query = 'select * from yahoo.finance.xchange where pair="\
                                          '+savedCurrency+'USD,\
                                          '+savedCurrency+'EUR,\
                                          '+savedCurrency+'INR,\
                                          '+savedCurrency+'GBP,\
                                          '+savedCurrency+'CAD,\
                                          '+savedCurrency+'AED,\
                                          '+savedCurrency+'BGN,\
                                          '+savedCurrency+'BDT,\
                                          '+savedCurrency+'CZK,\
                                          '+savedCurrency+'DKK,\
                                          '+savedCurrency+'HRK,\
                                          '+savedCurrency+'HUF,\
                                          '+savedCurrency+'IDR,\
                                          '+savedCurrency+'JPY,\
                                          '+savedCurrency+'NOK,\
                                          '+savedCurrency+'PLN,\
                                          '+savedCurrency+'RON,\
                                          '+savedCurrency+'RUB,\
                                          '+savedCurrency+'ALL,\
                                          '+savedCurrency+'SEK,\
                                          '+savedCurrency+'PHP,\
                                          '+savedCurrency+'TRY,\
                                          '+savedCurrency+'PKR,\
                                          '+savedCurrency+'VND,\
                                          '+savedCurrency+'RSD,\
                                          '+savedCurrency+'CNY\
                                          "';
        } else {
          query = 'select * from yahoo.finance.xchange where pair="'+savedCurrency+siteCurrency+',';
          for (i = 0; i < selected_currencies.length; i++) { 
            query += savedCurrency+selected_currencies[i]+',';
          }
          query = query.slice(0, -1);
          query += '"';
        }
        // Request currencies from yahoo finance
        var jqxhr = $.ajax({
            url: 'https://query.yahooapis.com/v1/public/yql',
            dataType: 'jsonp',
            data: {
              q: query,
              format: 'json',
              env: 'store://datatables.org/alltableswithkeys'
            }
          });
        // Set flag so we know we made a request
        window.jQCurryPluginCache[1] = true;

        jqxhr
          .done(function(data) {

            var items = data.query.results.rate;

            // Add the base currency to the rates
            rates[settings.base] = 1;

            for (var i = 0, l = items.length; i < l; i++) {

              item = items[i];
              keyName = item.Name.substr(item.Name.length - 3);

              rates[keyName] = +item.Rate;

            }

            generateDDM(rates);

            window.jQCurryPluginCache[0] = rates;
            $document.trigger('jQCurryPlugin.gotRates');

          })
          .fail(function(err) {

            console.log(err);

          });

      } else {

        $document.on('jQCurryPlugin.gotRates', function() {

          generateDDM(window.jQCurryPluginCache[0]);

        });

      }

    } else {

      generateDDM(settings.customCurrency);

    }

    // only change target when change is set by user
    //if (settings.change) {

      // Add default currency symbols
      var symbols = $.extend({
          'USD': '&#36;',
          'GBP': '&pound;',
          'EUR': '&euro;',
          'JPY': '&yen;'
        }, settings.symbols),
        $priceTag, symbol;

      $document.on('change', this.selector, function() {

        var $target = $(settings.target),
          $option = $(this).find(':selected'),
          rate = $option.data('rate'),
          has_comma = false,
          money, result, l = $target.length;

        for (var i = 0; i < l; i++) {

          $price = $($target[i]);
          money = $price.text();

          // Check if field has comma instead of decimal and replace with decimal
          if ( money.indexOf(',') !== -1 ){
            has_comma = true;
            money = money.replace( ',' , '.' );
          }

          // Remove anything but the numbers and decimals and convert string to Number
          money = Number(money.replace(/[^0-9\.]+/g, ''));

          if ($price.data('base-figure')) {

            // If the client changed the currency there should be a base stored on the element
            result = rate * $price.data('base-figure');

          } else {

            // Store the base price on the element
            $price.data('base-figure', money);
            result = rate * money;

          }

          // Parse as two decimal number with .
          result = Number(result.toString().match(/^\d+(?:\.\d{2})?/));

          // Replace decimal with comma after calculations
          if ( has_comma ){
            result = result.toString().replace( '.' , ',' );
            has_comma = false;
          }

          symbol = symbols[$option.val()] || $option.val();

          $price.html('<span class="symbol">' + symbol + '</span> ' + result);

        }

      });

    //}

    // Returns jQuery object for chaining
    return dropDownMenu;

  };

})(jQuery);
