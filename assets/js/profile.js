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

  /*
   * Creates an item div container. 
   * Called before the AJAX request is sent.
   */
  $.fn.addItem = function(elem) {
    let item = $.fn.parseElement(elem);
    // Computes the new item index from the data attribute value of the item container.
    let newIndex = parseInt($('#'+item.type+'-container-'+item.indexPattern).attr('data-last-index')) + 1;

    if (item.type == 'licence') {
      // N.B: The licence container has no index as it's unique.
      newIndex = parseInt($('#'+item.type+'-container').attr('data-last-index')) + 1;
      $('#licence-container').append('<div class="row licence-row" id="licence-'+newIndex+'">');
      // Updates the last item index.
      $('#licence-container').attr('data-last-index', newIndex);
    }
    else {
      $('#'+item.type+'-container-'+item.indexPattern).append('<div class="row '+item.type+'-row" id="'+item.type+'-'+item.indexPattern+'-'+newIndex+'">');
      $('#'+item.type+'-container-'+item.indexPattern).attr('data-last-index', newIndex);
    }

    // Sets the hidden input value which is going to be sent to the AJAX handler.
    $('#item-new-index').val(newIndex);
  };

  /*
   * Called by the data attributes API after a new item has been added
   * to the div container.
   */
  $.fn.initNewItem = function(elem) {
    let item = $.fn.parseElement(elem);

    if (item.type == 'licence' && item.action == 'add') {
      // N.B: The licence container has no index as it's unique.
      let lastIndex = $('#licence-container').attr('data-last-index');
      // Links the "sub-items" (drop down lists, buttons...) to their corresponding JS function. 
      $('#add-attestation-'+lastIndex+'-0').click(function() { $.fn.addItem(this); });
      $('#add-language-'+lastIndex+'-0').click(function() { $.fn.addItem(this); });
      $.fn.setDateFields('#dp_expiry-date-'+lastIndex+'-0');
      $('#licence-type-'+lastIndex).change(function() { $.fn.setLicenceType($(this)); });
      $.fn.setLicenceType($('#licence-type-'+lastIndex));
      $('#add-licence-'+lastIndex).click(function() { $.fn.addItem(this); });
    }
    else if (item.type == 'attestation' && item.action == 'add') {
      let lastIndex = $('#attestation-container-'+item.i).attr('data-last-index');
      $('#add-attestation-'+item.i+'-'+lastIndex).click(function() { $.fn.addItem(this); });
      $('#add-language-'+item.i+'-'+lastIndex).click(function() { $.fn.addItem(this); });
      $.fn.setDateFields('#dp_expiry-date-'+item.i+'-'+lastIndex);
    }
    else if (item.type == 'language' && item.action == 'add') {
      // Sets the interpreter/translator attributes according to the licence type.
      $.fn.setLicenceType($('#licence-type-'+item.i)); 
    }
  };

  /*
   * Called by the data attributes API after an item has been removed
   * from the div container.
   */
  $.fn.deleteItem = function(elem) {
    let item = $.fn.parseElement(elem);
    // Removes the remaining div container.
    $('#'+item.type+'-'+item.indexPattern).remove();
  };

  /*
   * Parses the given element's identifier.
   * Pattern description: action-item-i[, -j[, -k]]
   * ie: i = licence index, j = attestation index, k = language index
   */
  $.fn.parseElement = function(elem) {
    let id = elem.id;
    let regex = new RegExp('([a-z]+)-([a-z]+)-([0-9]+)-?([0-9]+)?-?([0-9]+)?');
    let results = id.match(regex);
    let obj = {'action': results[1], 'type': results[2], 'i': results[3], 'j': results[4], 'k': results[5]};

    // Sets the licence index pattern by default.
    obj.indexPattern = obj.i;

    // Sets the index pattern accordingly.
    if ((obj.type == 'language' && obj.action == 'add') || (obj.type == 'attestation' && obj.action == 'delete')) {
      obj.indexPattern = obj.indexPattern+'-'+obj.j;
    }
    else if (obj.type == 'language' && obj.action == 'delete') {
      obj.indexPattern = obj.indexPattern+'-'+obj.j+'-'+obj.k;
    }

    return obj;
  };

})(jQuery);

