/*==============================================================
  TASK FUNCTIONS
=============================================================*/
    $(".page").on("change", "#view-completed-tasks", function(e) {
        e.preventDefault();
        var loadinto= $(this).data('loadinto');
        var focuson = $(this).data('focus');
        var href = '';
        if ($(this).is(':checked')) {
            href = URI($(this).data('url').addQuery('completed', 'true')).toString();
        } else {
            href = $(this).data('url');
        }
        console.log(url);
        loadin(href, loadinto, function() {
            if (focuson.length > 0) { $('html, body').animate({scrollTop: $(focuson).offset().top - 60}, 1000); }
            $('.check-toggle').bootstrapToggle({on: 'Yes', off: 'No', onstyle: 'info'});
        });
    });

    $(".page").on("change", "#view-task-status", function(e) {
        e.preventDefault();
        var status = $(this).val();
        var loadinto= $(this).data('loadinto');
        var focuson = $(this).data('focus');
        var href = URI($(this).data('url')).addQuery('tasks-status', status).toString();
        // href = URI($(this).data('url').addQuery('completed', 'true')).toString();
        console.log(href);
        loadin(href, loadinto, function() {
            if (focuson.length > 0) { $('html, body').animate({scrollTop: $(focuson).offset().top - 60}, 1000); }
            $('.check-toggle').bootstrapToggle({on: 'Yes', off: 'No', onstyle: 'info'});
        });
    });

    $(".page").on("click", ".load-task-item", function(e) {
        e.preventDefault();
        var button = $(this);
        var ajaxloader = new ajaxloadedmodal(button);
        showajaxloading();
        console.log(ajaxloader.url);
        wait(500, function() { loadin(ajaxloader.url, ajaxloader.loadinto, function() { $(ajaxloader.modal).resizemodal('lg'); hideajaxloading(); $(ajaxloader.modal).modal(); $('.task-popover').popover('hide');}); });
    });

    $("body").on("click", ".complete-task", function(e) {
        e.preventDefault();
        var button = $(this);
        var url = button.attr('href');
        var taskid = button.data('id');
        console.log(config.urls.json.loadtask+"?id="+taskid);
        $.getJSON(config.urls.json.loadtask+"?id="+taskid, function(json) {
            swal({
                title: "Would you like to confirm this task as complete?",
                text: json.response.textbody,
                type: "question",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(function () {
                swal.close();
                $.get(url, function() { $('.tasks-refresh').click(); $(config.modals.ajax).modal('hide'); });
            }).catch(swal.noop);
        });
    });

    $("body").on("click", ".reschedule-task", function(e) {
        e.preventDefault();
        var button = $(this);
        var url = button.attr('href');
        var modal = config.modals.ajax;
        var loadinto = config.modals.ajax+" .modal-content";
        $(loadinto).loadin(url, function() {
            $(modal).resizemodal('lg').modal();
        });

    });

    $("body").on("submit", "#new-task-form", function(e) {
        e.preventDefault();
        var form = $(this);
        var modal = form.data('modal');
        var formid = "#"+$(this).attr('id');
        var action = form.attr('action');
        console.log(action);
        var elementreload = form.data('refresh');
        var isformcomplete = form.formiscomplete('tr');
        if (isformcomplete) {
            $(formid).postform({formdata: false, jsoncallback: true}, function(json) {
                $.notify({
                    icon: json.response.icon,
                    message: json.response.message,
                },{
                    element: modal + " .modal-body",
                    type: json.response.notifytype,
                    placement: {
                        from: "top",
                        align: "center"
                    },
                    onClose: function() {
                        wait(500, function() {
                            $(elementreload + " .tasks-refresh").click();
                            $(modal).modal('hide');
                            swal({
                                title: "Your task was created!",
                                text: "Would you like to create a note for this task?",
                                type: "success",
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: "Yes, create Note",
                            }).then(function () {
                                swal.close();
                                var href = addtoquerystring($('#notes-panel .add-note').attr('href'), ['task'], [json.response.taskid]);
                                $('#notes-panel .load-crm-note').attr('href', href).click();
                                var url = URI($('#tasks-panel .add-new-task').attr('href')).removeSearch("noteID").normalizeQuery();
                                $('#tasks-panel .add-new-task').attr('href', url.toString());
                            }).catch(swal.noop);
                        });
                    }
                });
            });
        } else {

        }
    });
