// selectize for category and location selects
$(function(){
    
    // create 1st category select
    category_select = createCategorySelect();
    // remove hidden class
    $('#category-chained .select-category[data-level="0"]').parent('div').removeClass('hidden');
    
    // load options for 1st category select
    category_select.load(function(callback) {
        $.ajax({
            url: $('#category-chained').data('apiurl'),
            type: 'GET',
            data: { 
                "id_category_parent": 1,
                "sort": 'order',
            },
            success: function(results) {
                callback(results.categories);
            },
            error: function() {
                callback();
            }
        });
    });
    
    // create 1st location select
    location_select = createLocationSelect();
    // remove hidden class
    $('#location-chained .select-location[data-level="0"]').parent('div').removeClass('hidden');
    
    // load options for 1st location select
    location_select.load(function(callback) {
        $.ajax({
            url: $('#location-chained').data('apiurl'),
            type: 'GET',
            data: { 
                "id_location_parent": 1,
                "sort": 'order',
            },
            success: function(results) {
                callback(results.locations);
            },
            error: function() {
                callback();
            }
        });
    });
});

function createCategorySelect () {
    
    // count how many category selects we have rendered
    num_category_select = $('#category-chained .select-category[data-level]').length;
    
    // clone category select from template
    $('#select-category-template').clone().attr('id', '').insertBefore($('#select-category-template')).find('select').attr('data-level', num_category_select);
    
    // initialize selectize on created category select
    category_select = $('.select-category[data-level="'+ num_category_select +'"]').selectize({
        valueField:  'id_category',
        labelField:  'name',
        searchField: 'name',
        onChange: function (value) {
            
            if (!value.length) return;
            
            // get current category level
            current_level = $('#category-chained .option[data-value="'+ value +'"]').closest('.selectize-control').prev().data('level');
            
            // is allowed to post on selected category?
            if ( current_level > 0 || (current_level == 0 && $('#category-chained').is('[data-isparent]')))
            {
                // update #category-selected input value
                $('#category-selected').attr('value', value);
                
                // show custom fields for this category
                showCustomFieldsByCategory($('#category-selected'));
                
                //get category price
                $.ajax({
                    url: $('#category-chained').data('apiurl') + '/' + value,
                    success: function(results) {
                        if (results.category.price != $('#category-chained').data('price0')) {
                            price_txt = $('#paid-category .help-block').data('title').replace(/%s/g, results.category.name).replace(/%d/g, results.category.price);
                            $('#paid-category').removeClass('hidden').find('.help-block span').text(price_txt);
                        }
                        else {
                            $('#paid-category').addClass('hidden');
                        }
                    }
                });
            }
            else
            {
                // set empty value
                $('#category-selected').attr('value', '');
                $('#paid-category').addClass('hidden');
                showCustomFieldsByCategory($('#category-selected'));
            }
            
            // get current category level
            current_level = $('#category-chained .option[data-value="'+ value +'"]').closest('.selectize-control').prev().data('level');
            
            destroyCategoryChildSelect(current_level);
            
            // create category select
            category_select = createCategorySelect();
            
            // load options for category select
            category_select.load(function (callback) {
                $.ajax({
                    url: $('#category-chained').data('apiurl'),
                    data: { 
                        "id_category_parent": value,
                        "sort": 'order',
                    },
                    type: 'GET',
                    success: function (results) {
                        if (results.categories.length > 0)
                        {
                            callback(results.categories);
                            $('#category-chained .select-category[data-level="' + (current_level + 1) + '"]').parent('div').removeClass('hidden');
                        }
                        else
                        {
                            destroyCategoryChildSelect(current_level);
                        }
                    },
                    error: function () {
                        callback();
                    }
                });
            });
        }
    });
    
    // return selectize control
    return category_select[0].selectize;
}

function createLocationSelect () {
    
    // count how many location selects we have rendered
    num_location_select = $('#location-chained .select-location[data-level]').length;
    
    // clone location select from template
    $('#select-location-template').clone().attr('id', '').insertBefore($('#select-location-template')).find('select').attr('data-level', num_location_select);
    
    // initialize selectize on created location select
    location_select = $('.select-location[data-level="'+ num_location_select +'"]').selectize({
        valueField:  'id_location',
        labelField:  'name',
        searchField: 'name',
        onChange: function (value) {
            
            if (!value.length) return;
            
            // update #location-selected input value
            $('#location-selected').attr('value', value);
            
            // get current location level
            current_level = $('#location-chained .option[data-value="'+ value +'"]').closest('.selectize-control').prev().data('level');
            
            destroyLocationChildSelect(current_level);
            
            // create location select
            location_select = createLocationSelect();
            
            // load options for location select
            location_select.load(function (callback) {
                $.ajax({
                    url: $('#location-chained').data('apiurl'),
                    data: { 
                        "id_location_parent": value,
                        "sort": 'order',
                    },
                    type: 'GET',
                    success: function (results) {
                        if (results.locations.length > 0)
                        {
                            callback(results.locations);
                            $('#location-chained .select-location[data-level="' + (current_level + 1) + '"]').parent('div').removeClass('hidden');
                        }
                        else
                        {
                            destroyLocationChildSelect(current_level);
                        }
                    },
                    error: function () {
                        callback();
                    }
                });
            });
        }
    });
    
    // return selectize control
    return location_select[0].selectize;
}

function destroyCategoryChildSelect (level) {
    if (level === undefined) return;
    $('#category-chained .select-category[data-level]').each(function () {
        if ($(this).data('level') > level) {
            $(this).parent('div').remove();
        }
    });
}

function destroyLocationChildSelect (level) {
    if (level === undefined) return;
    $('#location-chained .select-location[data-level]').each(function () {
        if ($(this).data('level') > level) {
            $(this).parent('div').remove();
        }
    });
}

$('#category-edit button').click(function(){
    $('#category-chained').removeClass('hidden');
    $('#category-edit').addClass('hidden');
});
    
$('#location-edit button').click(function(){
    $('#location-chained').removeClass('hidden');
    $('#location-edit').addClass('hidden');
});

// sceditor
$('textarea[name=description]:not(.disable-bbcode)').sceditorBBCodePlugin({
    toolbar: "bold,italic,underline,strike,|left,center,right,justify|" +
    "bulletlist,orderedlist|link,unlink,youtube|source",
    resizeEnabled: "true",
    emoticonsEnabled: false,
    style: $('meta[name="application-name"]').data('baseurl') + "themes/default/css/jquery.sceditor.default.min.css",
});

// paste plain text in sceditor
$(".sceditor-container iframe").contents().find("body").bind('paste', function(e) {
    e.preventDefault();
    var text = (e.originalEvent || e).clipboardData.getData('text/plain');
    $(".sceditor-container iframe")[0].contentWindow.document.execCommand('insertText', false, text);
});

//sceditorBBCodePlugin for validation, updates iframe on submit 
$("button[name=submit]").click(function(){
    $("textarea[name=description]").data("sceditor").updateOriginal();
});

// google map set marker on address
if($('#map').length !== 0){
    map = new GMaps({
        div: '#map',
        zoom: parseInt($('#map').attr('data-zoom')),
        lat: $('#map').attr('data-lat'),
        lng: $('#map').attr('data-lon')
    });
    map.setCenter($('#map').attr('data-lat'), $('#map').attr('data-lon'));
    map.addMarker({
        lat: $('#map').attr('data-lat'),
        lng: $('#map').attr('data-lon')
    });
    var typingTimer;                //timer identifier
    var doneTypingInterval = 500;  //time in ms, 5 second for example
    //on keyup, start the countdown
    $('#address').keyup(function () {
        clearTimeout(typingTimer);
        if ($(this).val()) {
           typingTimer = setTimeout(doneTyping, doneTypingInterval);
        }
    });
    //user is "finished typing," refresh map
    function doneTyping () {
        GMaps.geocode({
            address: $('#address').val(),
            callback: function (results, status) {
                if (status == 'OK') {
                    var latlng = results[0].geometry.location;
                    map = new GMaps({
                        div: '#map',
                        lat: latlng.lat(),
                        lng: latlng.lng(),
                    }); 
                    map.setCenter(latlng.lat(), latlng.lng());
                    map.addMarker({
                        lat: latlng.lat(),
                        lng: latlng.lng(),
                    });
                    $('#publish-latitude').val(latlng.lat());
                    $('#publish-longitude').val(latlng.lng());
                }
            }
        });
    }
}

// auto locate user
$('.locateme').click(function() {
    var lat;
    var lng;
    GMaps.geolocate({
        success: function(position) {
            lat = position.coords.latitude;
            lng = position.coords.longitude
            map = new GMaps({
                div: '#map',
                lat: lat,
                lng: lng,
            }); 
            map.setCenter(lat, lng);
            map.addMarker({
                lat: lat,
                lng: lng,
            });
            $('#publish-latitude').val(lat);
            $('#publish-longitude').val(lng);
            GMaps.geocode({
                lat: lat,
                lng: lng,
                callback: function(results, status) {
                    if (status == 'OK') {
                        $("input[name='address']").val(results[0].formatted_address)
                    }
                }
            });
        },
        error: function(error) {
            alert('Geolocation failed: '+error.message);
        },
        not_supported: function() {
            alert("Your browser does not support geolocation");
        },
    });
});

// VALIDATION with chosen fix
$(function(){
    $.validator.addMethod(
        "regex",
        function(value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        },
        "Please check your input."
    );

    // some extra rules for custom fields
    if ($('.cf_decimal_fields').length !== 0)
        var $decimal = $(".cf_decimal_fields").attr("name");
    if ($('.cf_integer_fields').length !== 0)
        var $integer = $(".cf_integer_fields").attr("name");

    var $params = {rules:{}, messages:{}};
    $params['rules'][$integer] = {regex: "^[0-9]{1,18}([,.]{1}[0-9]{1,3})?$"};
    $params['messages'][$integer] = "Format is incorect";
    $params['rules'][$decimal] = {regex: "^[0-9]{1,18}([,.]{1}[0-9]{1,3})?$"};
    $params['messages'][$decimal] = "Format is incorect";
    $params['rules']['price'] = {regex: "^[0-9]{1,18}([,.]{1}[0-9]{1,3})?$"};
    $params['messages']['price'] = "Format is incorect";
    $params['rules']['website'] = {maxlength: 200};

    $.validator.setDefaults({ ignore: ":hidden:not(select)" });
    var $form = $(".edit_ad_form");
    $form.validate($params);

    //chosen fix
    var settings = $.data($form[0], 'validator').settings;
    settings.ignore += ':not(.cf_select_fields)'; // post_new location(any chosen) texarea
    // settings.ignore += ':not(.sceditor-container)'; // post_new description texarea
    settings.ignore += ':not(#description)'; // post_new description texarea
});

// processing modal
$(function(){
    $('.edit_ad_form').submit(function(){
        if ($(this).valid()) {
            $('#processing-modal').modal('show');
        }
    });
});

//datepicker in case date field exists
if ($('.cf_date_fields').length !== 0){
    $('.cf_date_fields').datepicker();
}

$(function(){
    showCustomFieldsByCategory($('#category-selected'));
});

function showCustomFieldsByCategory(element){
    id_categ = $(element).val();
    // only custom fields have class data-custom
    $(".data-custom").each(function(){
        // get data-category, contains json array of set categories
        field = $(this);
        dataCategories = field.attr('data-categories');
        if(dataCategories)
        {
            // show if cf fields if they dont have categories set
            if(dataCategories.length != 2){
                field.closest('#cf_new').css('display','none');
                field.prop('disabled', true);
            }
            else{
                field.closest('#cf_new').css('display','block');
                field.prop('disabled', false);
                $(".cf_select_fields").chosen('destroy'); // refresh chosen
                $(".cf_select_fields").chosen({
                    no_results_text: getChosenLocalization("no_results_text"),
                    placeholder_text_multiple: getChosenLocalization("placeholder_text_multiple"),
                    placeholder_text_single: getChosenLocalization("placeholder_text_single")
                }); // refresh chosen
            }
            if(dataCategories !== undefined)  
            {   
                if(dataCategories != "")
                {
                    // apply if they have equal id_category 
                    $.each($.parseJSON(dataCategories), function (index, value) { 
                        if(id_categ == value){
                            field.closest('#cf_new').css('display','block');
                            field.prop('disabled', false);
                            $(".cf_select_fields").chosen('destroy'); // refresh chosen
                            $(".cf_select_fields").chosen({
                                no_results_text: getChosenLocalization("no_results_text"),
                                placeholder_text_multiple: getChosenLocalization("placeholder_text_multiple"),
                                placeholder_text_single: getChosenLocalization("placeholder_text_single")
                            }); // refresh chosen
                        }
                    });
                }
            }
        }
    });
}

$(function(){
    $(".img-delete").click(function(e) {
        var href = $(this).attr('href');
        var title = $(this).data('title');
        var text = $(this).data('text');
        var img_id = $(this).attr('value');
        var confirmButtonText = $(this).data('btnoklabel');
        var cancelButtonText = $(this).data('btncancellabel');
        e.preventDefault();
        swal({
            title: title,
            text: text,
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: confirmButtonText,
            cancelButtonText: cancelButtonText,
            allowOutsideClick: true,
        },
        function(){
            $('#processing-modal').modal('show');
            $.ajax({
                type: "POST",
                url: href,
                data: {img_delete: img_id},
                cache: false
            }).done(function(result) {
                $('#img' + img_id).toggle('slide');
                $('#processing-modal').modal('hide');
            }).fail(function() {
                $('#processing-modal').modal('hide');
            });
        });
    }); 
});
