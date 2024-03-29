(function($) {

  // Run a function when the page is fully loaded including graphics.
  $(window).on('load', function() {
      $.fn.setDateFields();
      $('[id^="licence-type-"]').click(function() { $.fn.setLicenceType($(this)); });

      $('[id^="licence-type-"]').each(function() { 
          if ($(this).is(':checked')) {
            $.fn.setLicenceType($(this)); 
          }
      });

      $.fn.setExpertOptions();

      $('[id^="add-language-"],[id^="add-attestation-"],[id^="add-licence-"]').click(function() { $.fn.addItem(this); });
      $('#honorary-member').click(function() { $.fn.honoraryMemberSetting($(this)); });
      $('#update-data, #replace-photo, button[id^="replace-file_"]').click(function() { $.fn.setTask($(this)); });

      $('#register').click(function(e) { $.fn.checkCheckboxes(e); });

      //
      $('.previous-next-arrows').click(function(event) {
          event.preventDefault();
          $('html, body').animate({ scrollTop: $($(this).attr('href')).offset().top }, 500);
      });
  });


  $.fn.setTask = function(btn) {
      $('#task').val(btn.attr('id'));
  }

  $.fn.setTabs = function(xhr) {
      let keys = Object.keys(xhr.responseJSON.X_WINTER_ERROR_FIELDS);
      let key = keys[0];
      let path = key.split('.');

      let name = $.fn.setElementName(path);
      console.log(name);
      let tab = path[0];

      // Checks for uploaded files (ie: 2 underscores suffix).
      if (tab.includes('__')) {
	  tab = tab.substr(0, tab.indexOf('_'));
      }
      // The User attributes are contained in the profile tab.
      else if (tab == 'email' || tab == 'password' || tab == 'password_confirmation' || tab == 'password_current' || tab == 'username') {
	  tab = 'profile';
      }

      $('#myTab a[href="#'+tab+'"]').tab('show');
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
  }

  $.fn.checkCheckboxes = function(e) {

      if ($('#honorary-member').is(':checked')) {
          return;
      }

      const checkboxes = ['inputCodeOfEthics', 'inputStatuses', 'inputInternalRules'];
      // Reset the color of the checkbox labels.
      $.each(checkboxes, function(index, checkbox) {
	$('#'+checkbox+'Label').css('color', '#212529');
      });

      let unchecked = null;

      if (!$('#inputCodeOfEthics').prop('checked')) {
          unchecked = 'inputCodeOfEthics';	
      }
      else if (!$('#inputStatuses').prop('checked')) {
          unchecked = 'inputStatuses';	
      }
      else if (!$('#inputInternalRules').prop('checked')) {
          unchecked = 'inputInternalRules';	
      }

      if (unchecked) {
	  const messages = JSON.parse($('#javascript_messages').val());
	  const langVar = 'alert_'+unchecked;

	  alert(messages[langVar]);

	  $('#myTab a[href="#membership"]').tab('show');
	  $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
	      $('[name="'+name+'"]').focus();
	  });

	  $('#'+unchecked+'Label').css('color', 'red');

          e.preventDefault();
	  e.stopPropagation();
	  return false;
      }
  }

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
	  $('#licences-tab').parent().css({'visibility':'hidden','display':'none'});
	  $('#membership-tab').parent().css({'visibility':'hidden','display':'none'});
	  $('#details-navigation').css({'visibility':'hidden','display':'none'});
	  $('#photo-navigation').css({'visibility':'hidden','display':'none'});
      }
      else {
	  $('#licences-tab').parent().css({'visibility':'visible','display':'inline'});
	  $('#membership-tab').parent().css({'visibility':'visible','display':'inline'});
	  $('#details-navigation').css({'visibility':'visible','display':'block'});
	  $('#photo-navigation').css({'visibility':'visible','display':'block'});
      }
  };

  $.fn.setLicenceType = function(elem) {
      // Gets the index number set after "licence-type-".
      let parts = elem.attr('id').substring(13);
      let index = parts[parts.length - 1];

      switch (elem.val()) {
	  case 'expert':
	      $('#court-'+index).prop('disabled', true);
	      $('#appeal-court-'+index).prop('disabled', false);

	      $('[id^="interpreter-'+index+'-"],[id^="translator-'+index+'-"]').attr('type', 'checkbox');
	      $('[id^="interpreter-cassation-'+index+'-"],[id^="translator-cassation-'+index+'-"]').attr('type', 'checkbox');
	      //$('label[for|="interpreter-'+index+'"],label[for|="translator-'+index+'"]').css({'visibility':'visible','display':'inline'});
	      //$('label[for|="interpreter-cassation-'+index+'"],label[for|="translator-cassation-'+index+'"]').css({'visibility':'visible','display':'inline'});
	      $('[id^="language-skills-'+index+'-"]').css({'visibility':'visible','display':'block'});
              // Radio button toggle.
	      $('#licence-type-expert-'+index).parent().removeClass('exp-radio-tab-label-grey');
	      $('#licence-type-ceseda-'+index).parent().removeClass('exp-radio-tab-label');
	      $('#licence-type-expert-'+index).parent().addClass('exp-radio-tab-label');
	      $('#licence-type-ceseda-'+index).parent().addClass('exp-radio-tab-label-grey');

	      $.fn.linkCassation(index);
	      $.fn.setExpertOptions(index);
	      break;

	  case 'ceseda':
	      $('#court-'+index).prop('disabled', false);
	      $('#appeal-court-'+index).prop('disabled', true);

	      $('[id^="interpreter-'+index+'-"],[id^="translator-'+index+'-"]').attr('type', 'hidden').removeAttr('checked');
	      $('[id^="interpreter-cassation-'+index+'-"],[id^="translator-cassation-'+index+'-"]').attr('type', 'hidden').removeAttr('checked').val('');
	      //$('label[for|="interpreter-'+index+'"],label[for|="translator-'+index+'"]').css({'visibility':'hidden','display':'none'});
	      //$('label[for|="interpreter-cassation-'+index+'"],label[for|="translator-cassation-'+index+'"]').css({'visibility':'hidden','display':'none'});
	      $('[id^="language-skills-'+index+'-"]').css({'visibility':'hidden','display':'none'});
              // Radio button toggle.
	      $('#licence-type-expert-'+index).parent().removeClass('exp-radio-tab-label');
	      $('#licence-type-ceseda-'+index).parent().removeClass('exp-radio-tab-label-grey');
	      $('#licence-type-expert-'+index).parent().addClass('exp-radio-tab-label-grey');
	      $('#licence-type-ceseda-'+index).parent().addClass('exp-radio-tab-label');
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

          let frDate = {
              previousMonth : 'Mois Précédent',
              nextMonth     : 'Mois Suivant',
              months        : ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Decembre'],
              weekdays      : ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
              weekdaysShort : ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam']
          };

	  let picker = new Pikaday({
	    field: $(this)[0],
            i18n: frDate,
            yearRange: 90,
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
          $('[id^="licence-type-"]').click(function() { $.fn.setLicenceType($(this)); });
          // Check the expert type by default.
          $('#licence-type-expert-'+lastIndex).prop('checked',true);
	  $.fn.setLicenceType($('#licence-type-expert-'+lastIndex));
	  $('#add-licence-'+lastIndex).click(function() { $.fn.addItem(this); });
	  $.fn.setExpertOptions();
      }
      else if (item.type == 'attestation' && item.action == 'add') {
	  let lastIndex = $('#attestation-container-'+item.i).attr('data-last-index');
	  $('#add-attestation-'+item.i+'-'+lastIndex).click(function() { $.fn.addItem(this); });
	  $('#add-language-'+item.i+'-'+lastIndex).click(function() { $.fn.addItem(this); });
	  $.fn.setDateFields('#dp_expiry-date-'+item.i+'-'+lastIndex);

	  // Sets the interpreter/translator attributes according to the licence type.
          if ($('#licence-type-expert-'+item.i).is(':checked')) {
              $.fn.setLicenceType($('#licence-type-expert-'+item.i)); 
          }
          else {
              $.fn.setLicenceType($('#licence-type-ceseda-'+item.i)); 
          }
      }
      else if (item.type == 'language' && item.action == 'add') {
	  // Sets the interpreter/translator attributes according to the licence type.
          if ($('#licence-type-expert-'+item.i).is(':checked')) {
              $.fn.setLicenceType($('#licence-type-expert-'+item.i)); 
          }
          else {
              $.fn.setLicenceType($('#licence-type-ceseda-'+item.i)); 
          }
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

