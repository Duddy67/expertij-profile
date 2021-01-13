(function($) {

  // Run a function when the page is fully loaded including graphics.
  $(window).on('load', function() {

    var picker = new Pikaday({
      field: $('#birth-datepicker')[0],
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
	  $('#birth-date').val(formattedDate);

	  let locale = $('#locale').val();
	  moment.locale(locale); 
	  let localLocale = moment(formattedDate);
          // Displays date in current locale format.
	  $('#birth-datepicker').val(localLocale.format('L'));
      },
    });

    let birthDate = $('#birth-date').val();

    if (birthDate) {
      picker.setDate(new Date(birthDate));
    }
  });

})(jQuery);

