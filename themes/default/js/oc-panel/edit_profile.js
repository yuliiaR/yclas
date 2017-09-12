//datepicker in case date field exists
if($('.cf_date_fields').length != 0){
    $('.cf_date_fields').datepicker();
}

$('.fileinput').on('change.bs.fileinput', function() {
    //check whether browser fully supports all File API
    if (FileApiSupported())
    {
        //get the file size and file type from file input field
        var $input = $(this).find('input[name^="image"]');
        var image = $input[0].files[0];
        var max_size = $('.images').data('max-image-size')*1048576 // max size in bites
        var $closestFileInput = $(this).closest('.fileinput');

        //resize image
        canvasResize(image, {
            width: $('.images').data('image-width'),
            height: $('.images').data('image-height'),
            crop: false,
            quality: $('.images').data('image-quality'),
            callback: function(data, width, height) {

                var base64Image = new Image();
                base64Image.src = data;

                if (base64Image.size > max_size)
                {
                    swal({
                        title: '',
                        text: $('.images').data('swaltext'),
                        type: "warning",
                        allowOutsideClick: true
                    });

                    $closestFileInput.fileinput('clear');
                }
                else
                {
                    $('<input>').attr({
                    type: 'hidden',
                    name: 'base64_' + $input.attr('name'),
                    value: data
                    }).appendTo('.upload_image');
                }
            }
        });

        // Fixes exif orientation on thumbnail
        var thumbnail = $(this).find('.thumbnail > img');
        var rotation = 1;
        var rotate = {
            1: 'rotate(0deg)',
            2: 'rotate(0deg)',
            3: 'rotate(180deg)',
            4: 'rotate(0deg)',
            5: 'rotate(0deg)',
            6: 'rotate(90deg)',
            7: 'rotate(0deg)',
            8: 'rotate(270deg)'
        };

        loadImage.parseMetaData(
            image,
            function (data) {
                if (data.exif) {
                    rotation = data.exif.get('Orientation');
                    thumbnail.css('transform', rotate[rotation]);
                }
            }
        );
    }

    //unhide next box image after selecting first
    $(this).next('.fileinput').removeClass('hidden');
});

$('.fileinput').on('clear.bs.fileinput', function() {
    var $input = $(this).find('input[name^="image"]');
    $('input[name="base64_' + $input.attr('name') + '"]').remove();
});

$(".upload_image").submit( function(event) {
    clearFileInput($('input[name="image0"]'));
    return true;
});

function clearFileInput($input) {
    if ($input.val() == '') {
        return;
    }
    // Fix for IE ver < 11, that does not clear file inputs
    if (/MSIE/.test(navigator.userAgent)) {
        var $frm1 = $input.closest('form');
        if ($frm1.length) {
            $input.wrap('<form>');
            var $frm2 = $input.closest('form'),
                $tmpEl = $(document.createElement('div'));
            $frm2.before($tmpEl).after($frm1).trigger('reset');
            $input.unwrap().appendTo($tmpEl).unwrap();
        } else {
            $input.wrap('<form>').closest('form').trigger('reset').unwrap();
        }
    } else if (/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream) {
        $input.replaceWith($input.clone());
    } else {
        $input.val('');
    }
}

// check whether browser fully supports all File API
function FileApiSupported() {
    if (window.File && window.FileReader && window.FileList && window.Blob)
        return true;

    return false;
}
