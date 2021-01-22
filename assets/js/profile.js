(function($) {

  // Run a function when the page is fully loaded including graphics.
  $(window).on('load', function() {
    $.fn.setDateFields();
    $('[id^="licence-type-"]').change(function() { $.fn.setLicenceType($(this)); });

    $('[id^="licence-type-"]').each(function() { 
      $.fn.setLicenceType($(this)); 
    });
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
  $.fn.setDateFields = function() {
    $('.date-picker').each(function() {
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

})(jQuery);

