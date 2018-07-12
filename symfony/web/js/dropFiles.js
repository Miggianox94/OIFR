var ajaxData = new FormData();

$(window).on('load', function () {
    'use strict';

    var dropZone = document.getElementById('drop-zone');
    var uploadForm = document.getElementById('js-upload-form');
    var label =  document.getElementById('zip_upload_file_label');
    var inputUpload = $('#zip_upload_file');

    var startUpload = function (files) {
        console.log(files);

        if (files && files.length > 0) {
            $.each( files, function(i, file) {
                ajaxData.append( 'inputFile', file );
                changeLabelValueOnDrop(file.name);
            });
            $('.disabledProcessButton').first().removeClass('d-none').removeClass('disabledProcessButton').addClass('sliderButton').prop("disabled",false);
            $('#zip_upload_showResult').addClass('d-none');
        }
        else{
            changeLabelValueOnDrop("Choose a file...");
            ajaxData = new FormData();
            $('.disabledProcessButton').first().addClass('d-none').removeClass('sliderButton').addClass('disabledProcessButton').prop("disabled",true);
            $('#zip_upload_showResult').removeClass('d-none');
        }
    };

    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();
        loaderOn();
        uploadForm = $(uploadForm);
        resetModal();
        $.ajax({
            url: '/recognizeUpload',
            type: uploadForm.attr('method'),
            data: ajaxData,
            //data: null,
            //dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            complete: function() {
                console.log("Completed");
                loaderOff();
            },
            success: function(data) {
                if (data.status == "success"){
                    console.log('SUCCESS '+data);
                }
                else{
                    console.log("FAIL! "+data);
                }

                $('#popupModalMessageTitle').html('YOUR RESULTS');
                if(data.result){
                    initializeLightbox(data.result);
                }
                else{
                    $('#messagePopupBodyId').html(data.message);
                }
                $('#popupModalMessage').modal('show');

                ajaxData = new FormData();
                changeLabelValueOnDrop("Choose a file...");
                $('.sliderButton').first().addClass('d-none').removeClass('sliderButton').addClass('disabledProcessButton').prop("disabled",true);
                $('#zip_upload_showResult').removeClass('d-none');

            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.error("ERROR!");
                console.error("XHR: "+xhr);
                console.error("ajaxOptions: "+ajaxOptions)
                console.error("thrownError: "+thrownError);

                $('#messagePopupBodyId').html(thrownError);
                $('#popupModalMessageTitle').html("ERROR");
                $('#popupModalMessage').modal('show');

                ajaxData = new FormData();
                changeLabelValueOnDrop("Choose a file...");
                $('.sliderButton').first().addClass('d-none').removeClass('sliderButton').addClass('disabledProcessButton').prop("disabled",true);
                $('#zip_upload_showResult').removeClass('d-none');
            }
        });
        return false;
    });

    var inputs = document.querySelectorAll('.inputfileCustomDragDrop');
    Array.prototype.forEach.call(inputs, function (input) {
        input.addEventListener('change', changeLabelValue );

        // Firefox bug fix
        input.addEventListener('focus', function () {
            input.classList.add('has-focus');
        });
        input.addEventListener('blur', function () {
            input.classList.remove('has-focus');
        });
    });

    dropZone.ondrop = function (e) {
        e.preventDefault();
        this.className = 'upload-drop-zone';

        startUpload(e.dataTransfer.files);
    };


    inputUpload.bind("change paste keyup", function(e) {
        e.preventDefault();
        startUpload(e.target.files);
    });

    dropZone.ondragover = function () {
        this.className = 'upload-drop-zone drop';
        return false;
    };

    dropZone.ondragleave = function () {
        this.className = 'upload-drop-zone';
        return false;
    };




/*------------FUNCTIONS DEFINITIONS-----------*/

    function changeLabelValueOnDrop (fileName) {
        label.querySelector('span').innerHTML = fileName;
    };

    function changeLabelValue (e) {
        var labelVal = label.innerHTML;
        var fileName = '';
        if (this.files && this.files.length > 1)
            fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
        else
            fileName = e.target.value.split('\\').pop();

        if (fileName)
            label.querySelector('span').innerHTML = fileName;
        else
            label.innerHTML = labelVal;
    };

    function initializeLightbox(results){

        var linkDiv = $('#links');
        var i;

        for (i = 0; i < results.length; i++) {
            var title = results[i].filename+": NSFW= "+results[i].NSFW+" SFW= "+results[i].SFW;
            var dangerClass = (results[i].NSFW && results[i].NSFW > 0.88)?"border-danger dangerNSFWImg":"border-success";
            var imageData =
                /*"<a href='data:image/png;base64,"+results[i].Image+"' title=\"Banana\">\n" +*/
                "<a data-toggle=\"tooltip\" data-placement=\"top\" href='data:image/png;base64,"+results[i].Image+"' title=\""+title+"\">\n" +
                "        <img class='galleryImage "+dangerClass+"' height=\"75\" width=\"75\" src='data:image/png;base64,"+results[i].Image+"' alt=\""+results[i].filename+"\">\n" +
                "</a>"
            linkDiv.append(imageData);
            linkDiv.append(imageData);
            linkDiv.append(imageData);
            linkDiv.append(imageData);
            linkDiv.append(imageData);
            linkDiv.append(imageData);
            linkDiv.append(imageData);
            linkDiv.append(imageData);
            linkDiv.append(imageData);

            $('[data-toggle="tooltip"]').tooltip();
        }

        document.getElementById('links').onclick = function (event) {
            event = event || window.event;
            var target = event.target || event.srcElement,
                link = target.src ? target.parentNode : target,
                options = {index: link, event: event},
                links = this.getElementsByTagName('a');
            blueimp.Gallery(links, options);

            $('[data-toggle="tooltip"]').tooltip('hide');
        };
    };

    function resetModal(){
        $('#links').html("");
    }


});
