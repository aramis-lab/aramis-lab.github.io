/**
 * Team Members Admin JS
 */

;(function($){
$(document).ready(function (){

  /* Spencer Tipping jQuery's clone method fix (for select fields). */
  (function (original) {
    jQuery.fn.clone = function () {
      var result           = original.apply(this, arguments),
          my_textareas     = this.find('textarea').add(this.filter('textarea')),
          result_textareas = result.find('textarea').add(result.filter('textarea')),
          my_selects       = this.find('select').add(this.filter('select')),
          result_selects   = result.find('select').add(result.filter('select'));
  
      for (var i = 0, l = my_textareas.length; i < l; ++i) $(result_textareas[i]).val($(my_textareas[i]).val());
      for (var i = 0, l = my_selects.length;   i < l; ++i) result_selects[i].selectedIndex = my_selects[i].selectedIndex;
  
      return result;
    };
  }) (jQuery.fn.clone);


  /* Defines folder slug. */
  var pluginFolderSlug = 'team-members';


  /* Color conversions. */
  var hexDigits = new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f");
  function dmb_rgb2hex(rgb) {
    if (rgb) {
      rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
      return "#" + dmb_hex(rgb[1]) + dmb_hex(rgb[2]) + dmb_hex(rgb[3]);
    } else {
      return;
    }
  }
  function dmb_hex(x) {
    return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
  } 


  /* Inits color pickers. */
  $('.dmb_color_picker').each(function(i, obj){$(this).wpColorPicker();});
  

  /* Gathers data into single input. */
  function dmbGatherData(keyUpParam) {
    var member = keyUpParam.closest('.dmb_main'),

    firstname = member.find('.dmb_firstname_of_member').val() || '',
    lastname = member.find('.dmb_lastname_of_member').val() || '',
    job = member.find('.dmb_job_of_member').val() || '';

    if ($('#acf-fallback-bio').length) {
      description = $.trim(member.find('.dmb_description_of_member_fb').text()) || '';
    } else {
      description = $.trim(member.find('.dmb_description_of_member').html()) || '';
    }

    var sclType1 = member.find('.dmb_scl_type1_of_member').val() || '',
    sclTitle1 = member.find('.dmb_scl_title1_of_member').val() || '',
    sclUrl1 = member.find('.dmb_scl_url1_of_member').val() || '',

    sclType2 = member.find('.dmb_scl_type2_of_member').val() || '',
    sclTitle2 = member.find('.dmb_scl_title2_of_member').val() || '',
    sclUrl2 = member.find('.dmb_scl_url2_of_member').val() || '',

    sclType3 = member.find('.dmb_scl_type3_of_member').val() || '',
    sclTitle3 = member.find('.dmb_scl_title3_of_member').val() || '',
    sclUrl3 = member.find('.dmb_scl_url3_of_member').val() || '',

    /* Image URL. */
    memberPhoto = member.find('.dmb_img_data_url').attr('data-img') || '',
    /* Image link URL. */
    memberPhotoUrl = member.find('.dmb_photo_url_of_member').val() || '',

    /* Finds single input. */
    dataDump = member.find('.dmb_data_dump');

    /* Fills single input. */
    dataDump.val(
      firstname + ']--[' + 
      lastname + ']--[' + 
      job + ']--[' +
      $("<div/>").html(description).text() + ']--[' +
      sclType1 + ']--[' +
      sclTitle1 + ']--[' +
      sclUrl1 + ']--[' +
      sclType2 + ']--[' +
      sclTitle2 + ']--[' +
      sclUrl2 + ']--[' +
      sclType3 + ']--[' +
      sclTitle3 + ']--[' +
      sclUrl3 + ']--[' +
      memberPhoto + ']--[' +
      memberPhotoUrl
    );
  }


  /* Defines trigger for single input update. */
  $('body').on('keyup', '.dmb_field', function(e) { dmbGatherData($(this)); });
  
  $('body').on('change', '.dmb_scl_type_select', function(e) { dmbGatherData($(this)); });
  
  $('body').on('change', '.dmb_img_data_url', function(e) { dmbGatherData($(this)); });


  /* Shows img/remove button if exists on page load. */
  $('.dmb_img_data_url').each(function(i, obj) {
    var imgUrl = $(this).attr("data-img");
    if (imgUrl != ''){
      $("<a class='dmb_remove_img_btn dmb_button dmb_button_large dmb_button_compact' href='#'><span class='dashicons dashicons-trash'></span></a><img src='"+imgUrl+"' class='dmb_img'/>").insertAfter($(this).parent().find('.dmb_upload_img_btn'));
    }
  });


  /* Processes member's description fields. */
  /* Initial single input update. */
  $('.dmb_main').not('.dmb_empty_row').each(function(i, obj){

    $(this).find('.dmb_description_of_member').each(function(i, obj){
      if ($.trim($(this).text()) == ''){
        $(this).hide();
      } else {
        $(this).show();
      }
      $(this).html($(this).text()); 
    });

    /* Triggers single input update. */
    dmbGatherData($(this).find('.dmb_field').first());

  });


  /* Shows/hides no row notice. */
  function refreshRowCountRelatedUI(){
    /* Shows notice when team has no member. */
    if($('.dmb_main').not('.dmb_empty_row').length > 0){
      $( '.dmb_no_row_notice' ).hide();
    } else {
      $( '.dmb_no_row_notice' ).show();
    }
  }

  refreshRowCountRelatedUI();


  /* Removes member's img. */
  $('body').on('click', '.dmb_remove_img_btn', function(e) {

    $(this).parent().find('.dmb_img').remove();

    /* Empties img URL (primary or hover). */
    $(this)
      .parent()
      .find('.dmb_img_data_url')
      .attr('data-img', '')
      .trigger('change');

    $(this).remove();

    return false;

  });


  /* Uploads members's img. */
  $('.dmb_upload_img_btn').click(function(e) {

    e.preventDefault();
 
    		var button = $(this),
    		    custom_uploader = wp.media({
			title: 'Insert image',
			library : {
				// uncomment the next line if you want to attach image to the current post
				// uploadedTo : wp.media.view.settings.post.id, 
				type : 'image'
			},
			button: {
				text: 'Use this image' // button label text
			},
			multiple: false // for multiple image selection set to true
		}).on('select', function() { // it also has "open" and "close" events 
      var attachment = custom_uploader.state().get('selection').first().toJSON();
      button.siblings('img, .dmb_remove_img_btn, .dashicons-trash').remove();
			$("<a class='dmb_remove_img_btn dmb_button dmb_button_large dmb_button_compact' href='#'><span class='dashicons dashicons-trash'></span></a><img src='"+attachment.url+"' class='dmb_img'/>").insertAfter(button);
      button.siblings('.dmb_img_data_url').attr('data-img', attachment.url).trigger('change');
    })
		.open();

  });


  /* Adds a member to the team. */
  $( '.dmb_add_row' ).on('click', function() {
    
    /* Clones/cleans/displays the empty row. */
    var row = $( '.dmb_empty_row' ).clone(true);
    row.removeClass( 'dmb_empty_row' ).addClass('dmb_main').show();
    row.insertBefore( $('.dmb_empty_row') );

    row.find('.dmb_firstname_of_member').focus();

    /* Inits color picker. */
    row.find('.dmb_color_picker_ready').removeClass('.dmb_color_picker_ready').addClass('.dmb_color_picker').wpColorPicker().css({'padding':'3px'});
    
    /* Defaults handle title. */
    row.find('.dmb_handle_title').html(objectL10n.untitled);
    
    /* Hides empty member description. */
    row.find('.dmb_description_of_member').hide();

    refreshRowCountRelatedUI();
    return false;

  });


  /* Removes a row. */
  $('.dmb_remove_row_btn').click(function(e) {

    $(this).closest('.dmb_main').remove();

    refreshRowCountRelatedUI();
    return false;

  });


  /* Expands/collapses handle. */
  $('.dmb_handle').click(function(e) {
    
    $(this).siblings('.dmb_inner').slideToggle(50);
    
    ($(this).hasClass('closed')) 
      ? $(this).removeClass('closed') 
      : $(this).addClass('closed');

    return false;

  });


  /* Collapses all rows. */
  $('.dmb_collapse_rows').click(function(e) {

    $('.dmb_handle').each(function(i, obj){
      if(!$(this).closest('.dmb_empty_row').length){ // Makes sure not to collapse empty row.
        if($(this).hasClass('closed')){
          
        } else {
          
          $(this).siblings('.dmb_inner').slideToggle(50);
          $(this).addClass('closed');

        }
      }
    });

    return false;

  });


  /* Expands all rows. */
  $('.dmb_expand_rows').click(function(e) {

    $('.dmb_handle').each(function(i, obj){
      if($(this).hasClass('closed')){
        
        $(this).siblings('.dmb_inner').slideToggle(50);
        $(this).removeClass('closed');

      }
    });

    return false;

  });


  /* Shifts a row down (clones and deletes). */
  $('.dmb_move_row_down').click(function(e) {

    if($(this).closest('.dmb_main').next().hasClass('dmb_main')){ // If there's a next row.
      /* Clones the row. */
      var movingRow = $(this).closest('.dmb_main').clone(true);
      /* Inserts it after next row. */
      movingRow.insertAfter($(this).closest('.dmb_main').next());
      /* Removes original row. */
      $(this).closest('.dmb_main').remove();
    }

    return false;

  });


  /* Shifts a row up (clones and deletes). */
  $('.dmb_move_row_up').click(function(e) {

    if($(this).closest('.dmb_main').prev().hasClass('dmb_main')){ // If there's a previous row.
      /* Clones the row. */
      var movingRow = $(this).closest('.dmb_main').clone(true);
      /* Inserts it before previous row. */
      movingRow.insertBefore($(this).closest('.dmb_main').prev());
      /* Removes original row. */
      $(this).closest('.dmb_main').remove();
    }

    return false;

  });


  /* Duplicates a row. */
  $('.dmb_clone_row').click(function(e) {

    /* Clones the row. */
    var clone = $(this).closest('.dmb_main').clone(true);
    /* Inserts it after original row. */
    clone.insertAfter($(this).closest('.dmb_main'));
    /* Adds 'copy' to title. */
    clone.find('.dmb_handle_title').html(clone.find('.dmb_firstname_of_member').val() + ' ('+objectL10n.copy+')');
    clone.find('.dmb_firstname_of_member').focus();

    updateHandleTitle(clone.find('.dmb_firstname_of_member'), true);
    refreshRowCountRelatedUI(); 
    return false;

  });


  /* Adds row title to handle. */
  $('.dmb_main').not('.dmb_empty_row').each(function(i, obj){

    if($(this).find('.dmb_firstname_of_member').val() != ''){

      var handleTitle = $(this).find('.dmb_handle_title'),
      firstname = $(this).find('.dmb_firstname_of_member').val(),
      lastname = $(this).find('.dmb_lastname_of_member').val();
      handleTitle.html($.parseHTML(firstname + ' ' + lastname));

    }

  });

  function encodeHTML(dirtyString) {
    var container = document.createElement('div');
    var text = document.createTextNode(dirtyString);
    container.appendChild(text);
    return dirtyString;
    return container.innerHTML; // innerHTML will be a xss safe string
  }


  /* Updates handle bar title. */
  function updateHandleTitle(firstnameField, wasCloned) {

    if (!wasCloned) { wasCloned = false; }

    /* Makes current title. */
    var firstnameField = firstnameField,
    lastname = firstnameField.closest('.dmb_main').find('.dmb_lastname_of_member').val() || '';
    firstname = firstnameField.val() || '';
    handleTitle = firstnameField.closest('.dmb_main').find('.dmb_handle_title');
    cloneCopyText = '';
    (wasCloned) ? cloneCopyText = ' copy' : cloneCopyText = '';
    title = firstname + ' ' + lastname + cloneCopyText;

    
    /* Updates handle title. */
    (firstnameField.val() != '')
      ? handleTitle.html(encodeHTML(title))
      : handleTitle.html(objectL10n.untitled + cloneCopyText);

  }


  /* Watches member firstname/lastname and updates handle. */
  $('body').on('keyup', '.dmb_firstname_of_member', function(e) { updateHandleTitle($(this)) });
  $('body').on('keyup', '.dmb_lastname_of_member', function(e) {
    firstnameField = $(this).closest('.dmb_main').find('.dmb_firstname_of_member');
    updateHandleTitle(firstnameField);
  });


  /* Previews team. */
  $('.dmb_show_preview_team').click(function(){
    
    var settings = {};
    var team = {};
    var preview_html = '';

    settings.columns = $("select[name='team_columns'] option:selected").val();
    settings.bio_alignment = $("select[name='team_bio_align'] option:selected").val();
    settings.piclink_beh = $("select[name='team_piclink_beh'] option:selected").val();
    settings.piclink_beh == 'new' ? team.plb = 'target="_blank"' : team.plb = '';
    settings.color = dmb_rgb2hex($('.dmb_color_of_team').find(".wp-color-result").css('backgroundColor')) || '#8dba09';

    /* Counts the members. */
    team.memberCount = $('.dmb_main').not('.dmb_empty_row').length;

    /* Prepares the output. */
    preview_html += '<div class="tmm" style="margin-top: 100px;">';
      preview_html += '<div class="tmm_' + settings.columns + '_columns tmm_wrap tmm_plugin_f">';

        $('.dmb_main').not('.dmb_empty_row').each(function(i, obj){

          /* Gets row fields. */
          var fields = {};
      
          fields.firstname = encodeHTML($(this).find(".dmb_firstname_of_member").val());
          fields.lastname = encodeHTML($(this).find(".dmb_lastname_of_member").val());
          fields.job = encodeHTML($(this).find(".dmb_job_of_member").val());

          if ($('#acf-fallback-bio').length ) {
            fields.bio = $.trim($(this).find('.dmb_description_of_member_fb').text()) || '';
            
          } else {
            fields.bio = $.trim($(this).find('.dmb_description_of_member').html()) || '';
          }

          fields.scl_type1 = $(this).find(".dmb_scl_type1_of_member").find(":selected").val();
          fields.scl_title1 = encodeHTML($(this).find(".dmb_scl_title1_of_member").val());
          fields.scl_url1 = encodeHTML($(this).find(".dmb_scl_url1_of_member").val());
          fields.scl_type2 = $(this).find(".dmb_scl_type2_of_member").find(":selected").val();
          fields.scl_title2 = encodeHTML($(this).find(".dmb_scl_title2_of_member").val());
          fields.scl_url2 = encodeHTML($(this).find(".dmb_scl_url2_of_member").val());
          fields.scl_type3 = $(this).find(".dmb_scl_type3_of_member").find(":selected").val();
          fields.scl_title3 = encodeHTML($(this).find(".dmb_scl_title3_of_member").val());
          fields.scl_url3 = encodeHTML($(this).find(".dmb_scl_url3_of_member").val());
          fields.photoUrl = $(this).find(".dmb_img").attr('src');
          fields.photoLinkUrl = encodeHTML($(this).find(".dmb_photo_url_of_member").val());

          /* Creates team container. */
          if(i%2 == 0) {
            /* If group of two (alignment). */
            preview_html += '<span class="tmm_two_containers_tablet"></span>';
          }
          if(i%settings.columns == 0) {
            /* If first member of group. */
            if(i > 0) {
              preview_html += '</div><span class="tmm_columns_containers_desktop"></span>';
            }
            preview_html += '<div class="tmm_container">';
          }

          preview_html += '<div class="tmm_member" style="border-top:' + settings.color + ' solid 5px;">';

            /* Displays photo. */
            if (fields.photoLinkUrl)
              preview_html += '<a ' + settings.piclink_beh + ' href="' + fields.photoLinkUrl + '" title="' + fields.firstname + ' ' + fields.lastname + '">';

              if (fields.photoUrl)
              preview_html += '<div class="tmm_photo tmm_pic_' + i + '" style="background: url(' + fields.photoUrl + '); margin-left: auto; margin-right:auto; background-size:cover !important;"></div>';

            if (fields.photoLinkUrl)
              preview_html += '</a>';

            /* Creates text block. */
            preview_html += '<div class="tmm_textblock">';

              /* Displays names. */
              preview_html += '<div class="tmm_names">';
              if (fields.firstname) {
                preview_html += '<span class="tmm_fname">' + fields.firstname + '</span> ';
              }
              if (fields.lastname) {
                preview_html += '<span class="tmm_lname">' + fields.lastname + '</span>';
              }
              preview_html += '</div>';

              /* Displays jobs. */
              if (fields.job) {
                preview_html += '<div class="tmm_job">' + fields.job + '</div>';
              }

              /* Displays bio. */
              if (fields.bio) {
                preview_html += '<div class="tmm_desc" style="text-align:' + settings.bio_alignment + '">' + fields.bio + '</div>';
              }

              /* Creates social block. */
              preview_html += '<div class="tmm_scblock">';
              
                /* Displays social links. */
                for (var j = 1; j <= 3; j++) {
                  
                  if (fields['scl_type' + j] != 'nada') {

                    var currentUrl = (fields['scl_url' + j]) ? fields['scl_url' + j] : '';
                    var currentTitle = (fields['scl_title' + i]) ? fields['scl_title' + j] : '';
                    if (fields['scl_type' + j] == 'email') {
                      preview_html += '<a class="tmm_sociallink" href="mailto:' + currentUrl + '" title="' + currentTitle + '"><img alt="' + currentTitle + '" src="../wp-content/plugins/' + pluginFolderSlug + '/inc/img/links/' + fields['scl_type' + j] + '.png"/></a>';
                    } else {
                      preview_html += '<a target="_blank" class="tmm_sociallink" href="' + currentUrl + '" title="' + currentTitle + '"><img alt="' + currentTitle + '" src="../wp-content/plugins/' + pluginFolderSlug + '/inc/img/links/' +fields['scl_type' + j] + '.png"/></a>';
                    }

                  }
                }

              preview_html += '</div>'; // Closes social block.
            preview_html += '</div>'; // Closes text block.
          preview_html += '</div>'; // Closes member.

          if (i == (team.memberCount - 1))
            preview_html += '<div style="clear:both;"></div>';

        });

        preview_html += '</div>'; // Closes container.
      preview_html += '</div>'; // Closes wrap.
    preview_html += '</div>'; // Closes tmm.
      
    preview_html += '<div style="clear:both;"></div>';

    preview_html += '<div class="dmb_accuracy_preview_notice">' + objectL10n.previewAccuracy + '</div>';

    /* Attaches content the preview to container. */
    (team.memberCount == 0)
    ? $('#dmb_preview_team').append('<div class="dmb_no_row_preview_notice">' + objectL10n.noMemberNotice + '</div>')
    : $('#dmb_preview_team').append(preview_html);
    
    /* Shows the preview box. */
    $('#dmb_preview_team').fadeIn(100);
    
  });

  
  /* Closes the preview. */
  $('.dmb_preview_team_close').click(function(){
    $('#dmb_preview_team .tmm, .dmb_accuracy_preview_notice, .dmb_no_row_preview_notice').remove();
    $('#dmb_preview_team').fadeOut(100);
  });


  /* Unique editor. */
  if (!$('#acf-fallback-bio').length) {
    var lastEditedBio = '';
    /* Opens the UE to edit bios. */
    $('.dmb_edit_description_of_member').click(function(){

      lastEditedBio = $(this).parent().find('.dmb_description_of_member');
      var currentContent = lastEditedBio.text();
      if ($("#wp-dmb_editor-wrap").hasClass("tmce-active")){
        tinymce.activeEditor.setContent(currentContent);
      } else {
        $('#dmb_editor').val($.trim(currentContent));
      }
      $('#dmb_unique_editor').fadeIn(100);
      if (tinyMCE.activeEditor !== null) { tinyMCE.activeEditor.focus(); } 
      
    });
  }


  /* Saves the UE data. */
  if (!$('#acf-fallback-bio').length ) {
    $('.dmb_ue_update').click(function(){

      if ($("#wp-dmb_editor-wrap").hasClass("tmce-active")){
        var dmb_ue_content = tinyMCE.activeEditor.getContent();
      } else {
        var dmb_ue_content = $('#dmb_editor').val();
      }
      
      /* Hides bio block if empty. */
      (!dmb_ue_content) ? lastEditedBio.hide() : lastEditedBio.show();

      /* Adds bio content if there is. */
      lastEditedBio.text(dmb_ue_content);

      /* Closes and empties UE. */
      $('#dmb_unique_editor').fadeOut(100);
      if (tinymce.activeEditor !== null) { tinymce.activeEditor.setContent(''); }

      dmbGatherData(lastEditedBio);
      return false;

    });
  }


  /* Cancels the UE updates. */
  if (!$('#acf-fallback-bio').length ) {
    $('.dmb_ue_cancel').click(function(){
      $('#dmb_unique_editor').fadeOut(100);
    });
  }


  /* Debounce function for fallback keyup. */
  // http://davidwalsh.name/javascript-debounce-function
  function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
  };


  /* Fallback editor (keyup) */
  $('body').on('paste keyup', '#acf-fallback-bio', debounce(function(){

    $(this).siblings('.dmb_description_of_member_fb').text($(this).val());
    dmbGatherData($(this));

  }, 600));

});
})(jQuery);