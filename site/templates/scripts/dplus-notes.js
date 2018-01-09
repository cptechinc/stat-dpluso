$(function() {
    var formfields = {
        form1: '#note1', form2: '#note2', form3: '#note3', form4: '#note4', form5: '#form5', type: '.type', key1: '.key1', key2: '.key2', recnbr: '.recnbr'
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
                $('#note'+i).bootstrapToggle(togglearray[note["form"+i]]);
            }
            
            $(form + ' .note').val(note.notefld);
            $(form + ' .editorinsert').val('edit');
            $(form + ' .recnbr').val(note.recno);
            button.addClass('bg-warning');
        });
    });
    
    $("body").on("submit", "#notes-form", function(e)  {
        e.preventDefault();
        var thisform = $(this);
        var validateurl = config.urls.json.dplusnotes+"?key1="+$(formfields.key1).val()+"&key2="+$(formfields.key2).val()+"&type="+$(formfields.type).val();
        var formid = "#"+$(this).attr('id');
        var formvalues = new dplusquotenotevalues(formfields, false);
        var formcombo = formvalues.form1 + formvalues.form2 + formvalues.form3 + formvalues.form4 + formvalues.form5;
        var loadinto = config.modals.ajax+" .modal-body";
        var loadurl = $(formid +' .notepage').val();
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
            
            if (alreadyexists && recnbr != $(form.recnbr).val()) {
                var onclick = '$(".rec'+recnbr+'").click()';
                var button = "<button type='button' class='btn btn-primary salesnote' onclick='"+onclick+"'>Click to Edit note</button>";
                $('#notes-form .response').createalertpanel('This note already exists <br> '+button, 'Error!', 'warning');
            } else {
                $(thisform).find('.editorinsert').val('insert');
                $(formid).postform({}, function() { 
                    wait(500, function() {
                        $(loadinto).loadin(loadurl, function() {
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
    
    // TODO
    // $("body").on("click", "#delete-note", function(e) {
    //     var button = $(this);
    //     var form = button.data('form');
    //     $(form + ' .action').val('delete-quote-note');
    //     $('#submit-note').click();
    // });
});
