/*==============================================================
  CRM NOTE FUNCTIONS
=============================================================*/
    $(".page").on("click", ".load-notes", function(e) {
        e.preventDefault();
        var button = $(this);
        var ajaxloader = new ajaxloadedmodal(button);
        $.get(ajaxloader.url, function() {
            showajaxloading();
            generateurl(function(url) {
                console.log(url);
                wait(500, function() { loadin(url, ajaxloader.loadinto, function() { $(ajaxloader.modal).resizemodal('lg'); $(ajaxloader.modal).modal(); hideajaxloading(); }); });
            });
        });
    });

    $(".page").on("click", ".load-crm-note", function(e) {
        e.preventDefault();
        var button = $(this);
        var ajaxloader = new ajaxloadedmodal(button);
        showajaxloading();
        console.log(ajaxloader.url);
        wait(500, function() {
            loadin(ajaxloader.url, ajaxloader.loadinto, function() {
                $(ajaxloader.modal).resizemodal('lg'); $(ajaxloader.modal).modal(); hideajaxloading(); $(ajaxloader.modal).find('.note').focus();
            });
        });
    });

    $("body").on("submit", "#crm-note-form", function(e) {
        e.preventDefault();
        var form = $(this);
        var modal = form.data('modal');
        var formid = "#"+$(this).attr('id');
        var elementreload = form.data('refresh');
        $(formid).postform({formdata: false, jsoncallback: true}, function(json) {
            $.notify({
                icon: json.response.icon,
                message: json.response.message,
            },{
                element: modal + " .modal-body",
                type: "success",
                url_target: '_self',
                placement: {
                    from: "top",
                    align: "center"
                },
                onClose: function() {
                    wait(200, function() {
                        $(elementreload + " .notes-refresh").click();
                        $(modal).modal('hide');
                        swal({
                            title: "Your note was created!",
                            text: "Would you like to create a task for this note?",
                            type: "success",
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: "Yes, create task",
                        }).then(function () {
                            swal.close();
                            newnoteurl = URI($('#notes-panel .add-note').attr('href')).removeSearch("task").normalizeQuery();
                            $('#notes-panel .add-note').attr('href', newnoteurl.toString());
                            var url = URI($('#tasks-panel .add-new-task').attr('href')).addSearch("noteID", json.response.noteid).normalizeQuery();
                            $('#tasks-panel .add-new-task').attr('href', url.toString());
                            $('#tasks-panel .add-new-task').click();
                        }).catch(swal.noop);
                    });
                }
            });
        });
    });
