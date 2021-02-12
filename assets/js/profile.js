(function($) {

  // Run a function when the page is fully loaded including graphics.
  $(window).on('load', function() {
      $.fn.setDateFields();
      $('[id^="licence-type-"]').change(function() { $.fn.setLicenceType($(this)); });

      $('[id^="licence-type-"]').each(function() { 
	  $.fn.setLicenceType($(this)); 
      });

      $.fn.setExpertOptions();

      $('[id^="add-language-"],[id^="add-attestation-"],[id^="add-licence-"]').click(function() { $.fn.addItem(this); });
      $('#honorary-member').click(function() { $.fn.honoraryMemberSetting($(this)); });
      $('#update-data, #replace-photo').click(function() { $.fn.setTask($(this)); });
  });


  $.fn.setTask = function(btn) {
      $('#task').val(btn.attr('id'));
  }

  $.fn.setTabs = function(xhr) {
    let keys = Object.keys(xhr.responseJSON.X_OCTOBER_ERROR_FIELDS);
    let key = keys[0];
    let path = key.split('.');

    //alert($.fn.setElementName(path));
    let name = $.fn.setElementName(path);
    console.log(name);
    $('#myTab a[href="#'+path[0]+'"]').tab('show');
    //setTimeout(function(){ $('[name="'+name+'"]').focus(); }, 1000);
    $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
	$('[name="'+name+'"]').focus();
    });
  }

  $.fn.setElementName = function(path) {
      let name = path[0];

      if (path.length > 1) {
          for (let i = 1; i < path.length; i++) {
	      name = name+'['+path[i]+']';
	  }
      }
      
      return name;
  };

  /*
   * Only one licence can be of the Expert type.
   */
  $.fn.setExpertOptions = function(index) {
      if (index === undefined) {
	  // Searches the licence of the Expert type (if any).
	  $('[id^="licence-type-"]').each(function() {
	      if ($(this).val() == 'expert') {
		  // Gets the index number set after "licence-type-".
		  index = $(this).attr('id').substring(13);
	      }
	  });

	  // No licence of the Expert type have been found. 
	  if (index === undefined) {
	      // Resets the options just in case.
	      $('[id^="licence-type-"]').each(function() {
		  $('#'+$(this).attr('id')+' option[value="expert"]').prop('disabled', false);
	      });

	      return;
	  }
      }

      $('[id^="licence-type-"]').each(function() {
	  // Disables the Expert option of the other licences. 
	  if ($(this).attr('id') != 'licence-type-'+index) {
	      $('#'+$(this).attr('id')+' option[value="expert"]').prop('disabled', true);
	  }
      });
  };

  $.fn.honoraryMemberSetting = function(elem) {
      if (elem.is(':checked')) {
	  $('#membership').css({'visibility':'hidden','display':'none'});
	  $('#licences').css({'visibility':'hidden','display':'none'});
      }
      else {
	  $('#membership').css({'visibility':'visible','display':'block'});
	  $('#licences').css({'visibility':'visible','display':'block'});
      }
  };

  $.fn.setLicenceType = function(elem) {
      // Gets the index number set after "licence-type-".
      let index = elem.attr('id').substring(13);

      switch (elem.val()) {
	  case 'expert':
	      $('#court-'+index).prop('disabled', true);
	      $('#appeal-court-'+index).prop('disabled', false);

	      $('[id^="interpreter-'+index+'-"],[id^="translator-'+index+'-"]').attr('type', 'checkbox');
	      $('[id^="interpreter-cassation-'+index+'-"],[id^="translator-cassation-'+index+'-"]').attr('type', 'checkbox');
	      $('label[for|="interpreter-'+index+'"],label[for|="translator-'+index+'"]').css({'visibility':'visible','display':'inline'});
	      $('label[for|="interpreter-cassation-'+index+'"],label[for|="translator-cassation-'+index+'"]').css({'visibility':'visible','display':'inline'});

	      $.fn.linkCassation(index);
	      $.fn.setExpertOptions(index);
	      break;

	  case 'ceseda':
	      $('#court-'+index).prop('disabled', false);
	      $('#appeal-court-'+index).prop('disabled', true);

	      $('[id^="interpreter-'+index+'-"],[id^="translator-'+index+'-"]').attr('type', 'hidden').removeAttr('checked');
	      $('[id^="interpreter-cassation-'+index+'-"],[id^="translator-cassation-'+index+'-"]').attr('type', 'hidden').removeAttr('checked').val('');
	      $('label[for|="interpreter-'+index+'"],label[for|="translator-'+index+'"]').css({'visibility':'hidden','display':'none'});
	      $('label[for|="interpreter-cassation-'+index+'"],label[for|="translator-cassation-'+index+'"]').css({'visibility':'hidden','display':'none'});
	      break;

	  default:
	      $('#court-'+index).prop('disabled', true);
	      $('#appeal-court-'+index).prop('disabled', true);
      }
  };

  $.fn.linkCassation = function(licenceIndex) {
      let skills = ['interpreter', 'translator'];

      for (let i = 0; i < skills.length; i++) {
	  $('[id^="'+skills[i]+'-'+licenceIndex+'-"]').each(function( index ) {
	      // Gets the pattern index.
	      let regex = new RegExp(skills[i]+'-([0-9]+-[0-9]+-[0-9]+)');
	      let results = $(this).attr('id').match(regex);

	      // Initialises the cassation checkboxes.
	      if ($(this).is(':checked')) {
		  $('#'+skills[i]+'-cassation-'+results[1]).prop('disabled', false);
	      }
	      else {
		  $('#'+skills[i]+'-cassation-'+results[1]).prop('disabled', true);
	      }

	      // When the skill checkbox is clicked the corresponding cassation 
	      // checkbox is enabled or disabled accordingly.
	      $(this).click(function() { 
		  if ($(this).is(':checked')) {
		      $('#'+skills[i]+'-cassation-'+results[1]).prop('disabled', false);
		  }
		  else {
		      $('#'+skills[i]+'-cassation-'+results[1]).prop('checked', false);
		      $('#'+skills[i]+'-cassation-'+results[1]).prop('disabled', true);
		  }
	      });
	  });
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
	  $.fn.setExpertOptions();
      }
      else if (item.type == 'attestation' && item.action == 'add') {
	  let lastIndex = $('#attestation-container-'+item.i).attr('data-last-index');
	  $('#add-attestation-'+item.i+'-'+lastIndex).click(function() { $.fn.addItem(this); });
	  $('#add-language-'+item.i+'-'+lastIndex).click(function() { $.fn.addItem(this); });
	  $.fn.setDateFields('#dp_expiry-date-'+item.i+'-'+lastIndex);
	  // Sets the interpreter/translator attributes according to the licence type.
	  $.fn.setLicenceType($('#licence-type-'+item.i)); 
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

      if (item.type == 'licence') {
	  $.fn.setExpertOptions();
      }
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

