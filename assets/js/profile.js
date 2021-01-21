(function($) {

  // Run a function when the page is fully loaded including graphics.
  $(window).on('load', function() {

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

  });

})(jQuery);

