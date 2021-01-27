(function($) {

  // Run a function when the page is fully loaded including graphics.
  $(window).on('load', function() {
    $.fn.setDateFields();
    $('[id^="licence-type-"]').change(function() { $.fn.setLicenceType($(this)); });

    $('[id^="licence-type-"]').each(function() { 
      $.fn.setLicenceType($(this)); 
    });

    $('[id^="add-language-"],[id^="add-attestation-"],[id^="add-licence-"]').click(function() { $.fn.addItem(this); });
  });


  $.fn.setLicenceType = function(elem) {
    // Gets the index number set after "licence-type-".
    let index = elem.attr('id').substring(13);

    switch (elem.val()) {
      case 'expert':
	$('#court-'+index).prop('disabled', true);
	$('#appeal-court-'+index).prop('disabled', false);

	$('[id^="interpreter-'+index+'-"],[id^="translator-'+index+'-"]').attr('type', 'checkbox');
	$('label[for|="interpreter-'+index+'"],label[for|="translator-'+index+'"]').css({'visibility':'visible','display':'inline'});
	break;

      case 'ceseda':
	$('#court-'+index).prop('disabled', false);
	$('#appeal-court-'+index).prop('disabled', true);

	$('[id^="interpreter-'+index+'-"],[id^="translator-'+index+'-"]').attr('type', 'hidden').removeAttr('checked');
	$('label[for|="interpreter-'+index+'"],label[for|="translator-'+index+'"]').css({'visibility':'hidden','display':'none'});
	break;

      default:
	$('#court-'+index).prop('disabled', true);
	$('#appeal-court-'+index).prop('disabled', true);
    }
  };

  /*
   * Sets all the date fields on the page.
   */
  $.fn.setDateFields = function(id) {
    // 
    id = id === undefined ? '.date-picker' : id;

    $(id).each(function() {
      let hiddenInputId = $(this).attr('id');
      hiddenInputId = hiddenInputId.substring(3);

      let picker = new Pikaday({
	field: $(this)[0],
	onSelect: date => {
	    // Format the selected date in MySQL date, (ie: yyyy-m-d).
	    const year = date.getFullYear(),
	    month = date.getMonth() + 1,
	    day = date.getDate(),
	    formattedDate = [
	      year,
	      month < 10 ? '0' + month : month,
	      day < 10 ? '0' + day : day
	    ].join('-');

	    // Sets the hidden input value. 
	    $('#'+hiddenInputId).val(formattedDate);

	    let locale = $('#locale').val();
	    moment.locale(locale); 
	    let localLocale = moment(formattedDate);
	    // Displays date in current locale format.
	    $(this).val(localLocale.format('L'));
	},
      });

      let date = $('#'+hiddenInputId).val();

      if (date) {
	picker.setDate(new Date(date));
      }
    });
  };

  $.fn.initNewItem = function(elem) {
    let item = $.fn.parseElement(elem);

    if (item.type == 'attestation' && item.action == 'add') {
      let lastIndex = $('#attestation-container-'+item.i).attr('data-last-index');
      $('#add-language-'+item.i+'-'+lastIndex).click(function() { $.fn.addItem(this); });
      $.fn.setDateFields('#dp_expiry-date-'+item.i+'-'+lastIndex);
    }
    else if (item.type == 'licence' && item.action == 'add') {
      let lastIndex = $('#licence-container').attr('data-last-index');
      $('#add-attestation-'+lastIndex+'-0').click(function() { $.fn.addItem(this); });
      $('#add-language-'+lastIndex+'-0').click(function() { $.fn.addItem(this); });
      $.fn.setDateFields('#dp_expiry-date-'+lastIndex+'-0');
      $('#licence-type-'+lastIndex).change(function() { $.fn.setLicenceType($(this)); });
      $.fn.setLicenceType($('#licence-type-'+lastIndex));
    }
  };

  $.fn.addItem = function(elem) {
    let item = $.fn.parseElement(elem);

    let newIndex = parseInt($('#'+item.type+'-container-'+item.indexPattern).attr('data-last-index')) + 1;
    //let newItemId = item.type+'-'+item.indexPattern+'-'+newIndex;

    if (item.type == 'licence') {
      newIndex = parseInt($('#'+item.type+'-container').attr('data-last-index')) + 1;
      $('#licence-container').append('<div class="row" id="licence-'+newIndex+'">');
      $('#licence-container').attr('data-last-index', newIndex);
    }
    else {
      $('#'+item.type+'-container-'+item.indexPattern).append('<div class="row" id="'+item.type+'-'+item.indexPattern+'-'+newIndex+'">');
      $('#'+item.type+'-container-'+item.indexPattern).attr('data-last-index', newIndex);
    }

    //alert('#'+item.type+'-container-'+item.indexPattern);
    $('#item-new-index').val(newIndex);
  };

  $.fn.deleteItem = function(elem) {
    let item = $.fn.parseElement(elem);
    $('#'+item.type+'-'+item.indexPattern).remove();
  };

  $.fn.parseElement = function(elem) {
    let id = elem.id;
    let regex = new RegExp('([a-z]+)-([a-z]+)-([0-9]+)-?([0-9]+)?-?([0-9]+)?');
    let results = id.match(regex);
    let obj = {'action': results[1], 'type': results[2], 'i': results[3], 'j': results[4], 'k': results[5]};

    obj.indexPattern = obj.i;

    if ((obj.type == 'language' && obj.action == 'add') || (obj.type == 'attestation' && obj.action == 'delete')) {
      obj.indexPattern = obj.indexPattern+'-'+obj.j;
    }
    else if (obj.type == 'language' && obj.action == 'delete') {
      obj.indexPattern = obj.indexPattern+'-'+obj.j+'-'+obj.k;
    }

    return obj;
  };

})(jQuery);

