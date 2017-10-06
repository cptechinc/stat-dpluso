$(function() {
    var form = {
        form1: '#note-field1', form2: '#note-field2', form3: '#note-field3', form4: '#note-field4', type: '.type', key1: '.key1', key2: '.key2', recnbr: '.recnbr'
    }

    $("body").on("click", ".dplusnote", function(e) {
        e.preventDefault();
        $('.bg-warning').removeClass('bg-warning');
        var button = $(this);
        var geturl = button.attr('href');
        var form = button.data('form');
        $.getJSON(geturl, function(json) {
            var note = json.note;
            for (var i = 1; i < 6; i++) {
                $('#note-field'+i).bootstrapToggle(togglearray[note["form"+i]]);
            }
            $(form + ' .note').val(note.notefld);
            $(form + ' .editorinsert').val('edit');

            $(form + ' .recnbr').val(note.recno);
            button.addClass('bg-warning');
        });
    });
    $("body").on("click", "#delete-note", function(e) {
        var button = $(this);
        var form = button.data('form');
        $(form + ' .action').val('delete-quote-note');
        $('#submit-note').click();
    });
    $("body").on("submit", "#notes-form", function(e)  {
        e.preventDefault();
        var thisform = $(this);
        var validateurl = config.urls.json.dplusnotes+"?key1="+$(form.key1).val()+"&key2="+$(form.key2).val()+"&type="+$(form.type).val();
        var formid = "#"+$(this).attr('id');
        var formvalues = new dplusquotenotevalues(form, false);
        var formcombo = formvalues.form1 + formvalues.form2 + formvalues.form3 + formvalues.form4;
        var loadinto = config.modals.ajax+" .modal-body";
        var url = $(formid +' .notepage').val();
        var alreadyexists = false;
        var recnbr = 0;

        $.getJSON(validateurl, function(json) {

            if (json.notes.length > 0) {
                $(json.notes).each(function(index, note) {
                    var notecombo = note.form1 + note.form2 + note.form3 + note.form4 + note.form5;
                    if (formcombo == notecombo) {
                        alreadyexists = true;
                    }
                    recnbr = note.recno;
                });
            } else {
                recnbr = 1;
            }

            console.log('recnbr ' + recnbr);

            if (alreadyexists && recnbr != $(form.recnbr).val()) {
                var onclick = '$(".rec'+recnbr+'").click()';
                var button = "<button type='button' class='btn btn-primary salesnote' onclick='"+onclick+"'>Click to Edit note</button>";
                createalertpanel('#notes-form .response', 'This note already exists <br> '+button, 'Error!', 'warning');
            } else {
                $(formid).postform({formdata: false, jsoncallback: false}, function() {  //{formdata: data/false, jsoncallback: true/false}
                console.log($(formid + ' .note').val());
                    wait(500, function() {
                        loadin(url, loadinto, function() {
                             $.notify({
                                icon: "&#xE8CD;",
                                message: "Your note has been saved",
                            },{
                                type: "success",
                                icon_type: 'material-icon',
                                 onShown: function() {
                                     $(".rec"+recnbr).click()
                                 },
                            });
                        });

                    });
                });
            }
        });

    });
});
