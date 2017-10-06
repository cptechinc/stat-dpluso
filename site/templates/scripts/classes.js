function ajaxloadedmodal(button) {
    this.modal = button.data('modal');
    this.url = button.attr('href');
    this.loadinto = button.data('modal')+" .modal-content";
    if (button.data('modalsize')) {
        this.modalsize = button.data('modalsize');
    } else {
        this.modalsize = 'lg';
    }
}

function itemform(thisform) {
	this.thisform = thisform;
	this.formID = '#'+thisform.attr('id');
	this.itemID = thisform.find('input[name="itemID"]').val();
	this.qty = parseInt(thisform.find('input[name="qty"]').val());
	this.desc = thisform.find('input[name="desc"]').val();
}

function Car(make, model, year, owner) {
  this.make = make;
  this.model = model;
  this.year = year;
  this.owner = owner;
}

function dplusquotenotevalues(form, quotetf) {
    this.form1 = 'N';
    this.form2 = 'N';
    this.form3 = 'N';
    this.form4 = 'N';
    if (quotetf) {
        this.form5 = 'N';
    }


    if ($(form.form1).prop('checked')) { this.form1 = 'Y'; }
    if ($(form.form2).prop('checked')) { this.form2 = 'Y'; }
    if ($(form.form3).prop('checked')) { this.form3 = 'Y'; }
    if ($(form.form4).prop('checked')) { this.form4 = 'Y'; }
    if (quotetf) {
        if ($(form.form5).prop('checked')) { this.form5 = 'Y'; }
    }

}
