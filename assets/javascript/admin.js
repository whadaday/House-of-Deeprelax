// @codekit-prepend '../../node_modules/jquery/dist/jquery.js'

jQuery(function ($) {

  // $('.acf-label').click(function() {
  //   console.log('huh');
  //   var $this = $(this);
  //   var $description = $this.find('.description');
  //   if($description.length) {
  //     $description.toggle();
  //   }
  //   else {
  //     console.log('huh');
  //   }
  // });

  // move save draft and preview page next to publish
  $('#publishing-action').prepend($('#minor-publishing-actions'));
  $('#minor-publishing-actions').addClass('show');

  function buildPostboxTabs() {
    var $normal = $('#normal-sortables');
    if (!$normal.length) return;

    // Postbox verplaatsen uitschakelen
    $normal.find('h2.hndle.ui-sortable-handle')
      .removeClass('hndle ui-sortable-handle');

    var $acfHeader = $('#acf-group_60c75c027eebf');

    // ACF header bovenaan
    if ($acfHeader.length) {
      $normal.prepend($acfHeader);
    }

    var $postboxes = $normal.children('.postbox');

    // Als er maar één postbox is, gewoon alles tonen
    if ($postboxes.length <= 1) {
      $postboxes.addClass('active');
      $normal.find('#list-tabs-content').remove();
      return;
    }

    // Bestaande tabs opruimen
    $normal.find('#list-tabs-content').remove();

    // Tabs html opbouwen
    var html = '<ul id="list-tabs-content">';
    $postboxes.each(function() {
      var $box = $(this);
      var id   = $box.attr('id');
      if (!id) return;

      var $titleEl = $box.find('.postbox-header h2, > h2').first();
      var title    = $titleEl.length ? $titleEl.html() : '&nbsp;';

      html += '<li><a href="#' + id + '">' + title + '</a></li>';
    });
    html += '</ul>';

    $normal.prepend(html);

    var $tabs = $('#list-tabs-content a');
    if (!$tabs.length) return;

    // Eerste tab actief
    $tabs.removeClass('active').first().addClass('active');
    $postboxes.removeClass('active').first().addClass('active');

    // Als ACF header bestaat, tweede tab actief maken
    if ($acfHeader.length && $tabs.eq(1).length) {
      var $contentTab = $tabs.eq(1);
      $tabs.removeClass('active');
      $contentTab.addClass('active');

      $postboxes.removeClass('active');
      var targetId = $contentTab.attr('href');
      if (targetId) {
        $(targetId).addClass('active');
      }
    }

    // Klik handler, elke keer schoon en opnieuw gekoppeld
    $('#list-tabs-content')
      .off('click.postboxTabs')
      .on('click.postboxTabs', 'a', function(e) {
        e.preventDefault();
        var $link  = $(this);
        if ($link.hasClass('active')) return;

        var targetId = $link.attr('href');

        var $links = $('#list-tabs-content a');
        $links.removeClass('active');
        $link.addClass('active');

        $postboxes.removeClass('active');
        if (targetId) {
          $(targetId).addClass('active');
        }
      });

    // ACF opties tab stylen
    $('.acf-tab-button:contains(Opties)').addClass('tab-options');
  }

  // Wachten tot de metaboxen er zijn
  function initWithRetry(attempt) {
    attempt = attempt || 0;
    if (attempt > 10) return;

    if ($('#normal-sortables .postbox').length) {
      buildPostboxTabs();
    } else {
      setTimeout(function() {
        initWithRetry(attempt + 1);
      }, 150);
    }
  }

  // Start op admin load
  initWithRetry();

  // Nog een keer nadat ACF velden heeft geladen
  $(document).on('acf/ready acf/load_fields', function() {
    buildPostboxTabs();
  });

   // Change copy text
    $('.edit_as_new_draft').each(function() {
    var $this = $(this);
    $this.find('a').html('Copy');
   });

   
   

    var r = document.querySelector(':root');

    function setCssVariable(element, attribute, value) {
      if(element == 'body') {element = 'p';}
      var property = '--'+element+'-'+attribute;

      r.style.setProperty(property, value);
    }
    

    // Font size
    $('.acf-field[data-name="font-size"]').change(function() {
      // console.log(this);
      var value = $(this).find('input').val();
      value = (value / 16).toFixed(3)+'rem';
      
      var element = $(this).parents('.element-styles').data('name');
      setCssVariable(element, 'font-size', value);
      
    });

    // Font family
    $('.acf-field[data-name="font-family"]').change(function() {
      // console.log(this);
      var value = $(this).find('select').val();
      value = 'var(--font-'+value+')';
      
      var element = $(this).parents('.element-styles').data('name');
      setCssVariable(element, 'font-family', value);
    });

    // Font weight
    $('.acf-field[data-name="font-weight"]').change(function() {
      // console.log(this);
      var value = $(this).find('select').val();
      
      var element = $(this).parents('.element-styles').data('name');
      setCssVariable(element, 'font-weight', value);
    });

    // Font style
    $('.acf-field[data-name="font-style"]').change(function() {
      var value = $(this).find('select').val();
      // console.log(value);

      var element = $(this).parents('.element-styles').data('name');
      setCssVariable(element, 'font-style', value);
    });

    // Line height
    $('.acf-field[data-name="line-height"]').change(function() {
      var value = $(this).find('input').val();
      // console.log(value);

      var element = $(this).parents('.element-styles').data('name');
      setCssVariable(element, 'line-height', value);
    });

    // Letter spacing
    $('.acf-field[data-name="letter-spacing"]').change(function() {
      var value = $(this).find('input').val();
      if(value != 0) { value = value+'rem'; }
      // console.log(value);

      var element = $(this).parents('.element-styles').data('name');
      setCssVariable(element, 'letter-spacing', value);
    });

    // Text transform
    $('.acf-field[data-name="text-transform"]').change(function() {
      var value = $(this).find('select').val();
      // console.log(value);

      var element = $(this).parents('.element-styles').data('name');
      setCssVariable(element, 'text-transform', value);
    });

    // Text decoration
    $('.acf-field[data-name="text-decoration"]').change(function() {
      var value = $(this).find('select').val();
      // console.log(value);

      var element = $(this).parents('.element-styles').data('name');
      setCssVariable(element, 'text-decoration', value);
    });

    // Margin
    $('.acf-field[data-name="margin"]').change(function() {
      var valueTop = $(this).find('.acf-field[data-name="top"] input').val();
      if(valueTop != 0) { valueTop = valueTop+'rem'; }

      var valueRight = $(this).find('.acf-field[data-name="right"] input').val();
      if(valueRight != 0) { valueRight = valueRight+'rem'; }

      var valueBottom = $(this).find('.acf-field[data-name="bottom"] input').val();
      if(valueBottom != 0) { valueBottom = valueBottom+'rem'; }

      var valueLeft = $(this).find('.acf-field[data-name="left"] input').val();
      if(valueLeft != 0) { valueLeft = valueLeft+'rem'; }
      
      var value =  valueTop+' '+valueRight+' '+valueBottom+' '+valueLeft

      var element = $(this).parents('.element-styles').data('name');
      setCssVariable(element, 'margin', value);
    });



    // 2 columns
    $('select').on("select2:select", function(e) { 

      var $container = $(this).parents('.acf-field[data-name="options"]');

      var $column_image = $container.find('.acf-field[data-name="column-image"]');
      var $column_text  = $container.find('.acf-field[data-name="column-text"]');

      if($column_image.length && $column_text.length) {

        var $image_column_width = acf.getFields({
            parent: $column_image,
            name: 'column-width'
        });
        $image_column_width = $image_column_width[0];

        var $image_column_offset = acf.getFields({
            parent: $column_image,
            name: 'column-offset'
        });
        $image_column_offset = $image_column_offset[0];

        var $text_column_width = acf.getFields({
            parent: $column_text,
            name: 'column-width'
        });
        $text_column_width = $text_column_width[0];

        var $text_column_offset = acf.getFields({
            parent: $column_text,
            name: 'column-offset'
        });
        $text_column_offset = $text_column_offset[0];

        if($(this).parents('.acf-field[data-name="column-image"]').length) {
          var type = 'image';
        } else {
          var type = 'text';
        }

        var image_width   = parseInt($image_column_width.val());
        var image_offset  = parseInt($image_column_offset.val());

        var text_width    = parseInt($text_column_width.val());
        var text_offset   = parseInt($text_column_offset.val());

        var total_columns = image_width + image_offset + text_width + text_offset;
        var total_image   = image_width + image_offset;
        var total_text    = text_width + text_offset;

        // console.log('image width: '+image_width);
        // console.log('image offset: '+image_offset);
        // console.log('text width: '+text_width);
        // console.log('text offset: '+text_offset);

        // console.log(total_columns);

        if(total_columns > 12) {
          
          // console.log(type);
          // console.log('image '+total_image);
          // console.log('text '+total_text);
          // console.log('');

          // Als een tekst kolom veld wordt aangepast?
          if(type == 'text') {

            //Aantal kolommen gelijk aan 8?
            if(total_text == 8) {
              setFieldValue($image_column_width, 3);
              setFieldValue($image_column_offset, 1);
            }
            
            
            //Aantal kolommen gelijk aan 7
            else if(total_text == 7) {
              setFieldValue($image_column_width, 4);
              setFieldValue($image_column_offset, 1);
            }

            //Aantal kolommen gelijk aan 6
            else if(total_text == 6) {
              if(total_image > 6) {
                setFieldValue($image_column_width, 5);
                setFieldValue($image_column_offset, 1);
              }
            }

          }

          // Als een image kolom veld wordt aangepast?
          else if(type == 'image') {

            //Aantal kolommen gelijk aan 8?
            if(total_image == 8) {
              setFieldValue($text_column_width, 5);
              setFieldValue($text_column_offset, 1);
              setFieldValue($image_column_width, 5)
              setFieldValue($image_column_offset, 1);
            }
            
            //Aantal kolommen gelijk aan 7
            else if(total_image == 7) {
              setFieldValue($text_column_width, 5);
              setFieldValue($text_column_offset, 0);
            }

            //Aantal kolommen gelijk aan 6
            else if(total_image == 6) {
              if(total_text == 8) {
                setFieldValue($image_column_width, 4);
                setFieldValue($text_column_offset, 0);
              }
              if(total_text == 7) {
                setFieldValue($text_column_width, 5);
                setFieldValue($text_column_offset, 1);
              }
            }

            //Aantal kolommen gelijk aan 5
            else if(total_image == 5) {
              setFieldValue($text_column_offset, 1);
            }

          }

        }
      }

    });


    /* TO FIX
      -- overlay fields not working for not active overlay (image/video)
      -- check group/clone/repeater within repeater field
      -- FAQ: globale vraag er uithalen? FAQ module?
      -- testimonial module
      -- projects module
    */

    $('.acf-field[data-name="block-templates"]').find('select').change(function() {
        var template = this.value;
        this.value = 0;

        var $blocksContainer = $('#acf-beam_blocks').find('.acf-flexible-content');
        var $blocks = $blocksContainer.find('.values');

        let
          data = { // JS data object with variables defined in PHP Ajax handler
            'action': 'load_templates', // Ajax function defined in handler that contains data we're pulling in
            'post_id': template_params.post_id, // inline post ID variable
            "security": template_params.security, // inline nonce variable
            "block_templates": template,
          };

        $.post({ // can also use $.ajax and declare 'post' type, but this is a shortcut in newer jQuery versions
          url: template_params.ajaxurl, // inline WordPress Ajax URL variable defined in handler
          data: data, // object defined above
            dataType:"json",
          beforeSend: function (xhr) { // runs before ajax request is sent

            // add loading state. you can do any number of things here, get creative! 
            // button.addClass('loading'); 
            // button.text('Loading...'); 

          }, 
          success: function (data) { // runs right after Ajax call succeeds
            if (data) { // only run if the data object exists

              // Flexible content field
              var pagebuilder = acf.getField( 'beam_blocks_content' );
              var iLayout = $('#acf-beam_blocks').find('.values').children().length;
              // console.log('index: '+iLayout);

              // Loop through each block

              $.each( data, function( index, value ) {

                // console.log(value);

                // Add layout to pagebuilder
                pagebuilder.add( { layout: value.layout } );

                // Get new layout element
                var $layout = $('#acf-beam_blocks').find('.values .layout').eq(iLayout).find('> .acf-fields > .acf-field');

                // Global block
                var global_block = value.block;

                // if global block exists?
                if(global_block) {

                  var $field = acf.getFields({
                      parent: $layout.parent('.acf-fields'),
                      name: 'block'
                  });

                  setFieldValue($field[0], global_block);

                }
                else {
                  
                  // Block content
                  var content = value.content;
                  
                  // Block options
                  var options = value.options; 


                  // Loop through each content field
                  $.each( content, function( content_key, content_value ) {

                    // Find field with key
                    // TO FIX: check if parent is correct. Maybe find the added layout as a parent instead of pagebuilder
                    var $field = acf.getFields({
                        parent: $layout,
                        name: content_key
                    });

                    var field_type = $field[0].data.type;

                    // console.log(field_type);

                    // console.log($field);
                    // console.log('wat is het?');
                    // console.log(content_value); 

                    if(field_type == 'clone' || field_type == 'group') {
                      getGroupFields($field[0], content_value);
                    
                    }
                    else if(field_type == 'repeater') {
                      // console.log(content_value);
                      getRepeaterFields($field[0], content_value);
                    }
                    else {
                      setFieldValue($field[0], content_value);
                    }

                  });


                  // Loop through each content field
                  $.each( options, function( option_key, option_value ) {

                    // Find field with key
                    // TO FIX: check if parent is correct. Maybe find the added layout as a parent instead of pagebuilder
                    var $field = acf.getFields({
                        parent: $layout,
                        name: option_key
                    });

                    // console.log($field);

                    var field_type = $field[0].data.type;

                    if(field_type == 'clone' || field_type == 'group') {
                      getGroupFields($field[0], option_value);
                    }
                    else if(field_type == 'repeater') {
                      getRepeaterFields($field[0], option_value);
                    }
                    else {
                      setFieldValue($field[0], option_value);
                    }             

                  });

                }

                iLayout++;

              });

            }
          }
        });

        
    });

    function getRepeaterFields(group, values) {

      var initRows   = false;
      var rowsLength = group.$el.find('.acf-row:not(".acf-clone")').length;
      var iRow       = 0;
      
      $.each( values, function( key, row ) {

        if(rowsLength == 0 || initRows) {
          group.add();
        }

        var $row = group.$el.find('.acf-row:nth-child('+(iRow+1)+')');

        $.each( row, function( key, value ) {

          if (value) {

            var $field = acf.getFields({
                parent: $row,
                name: key
            });

            var field_type = $field[0].data.type;
            
            // Is there a group in a group? Redo this function?
            if(field_type == 'clone' || field_type == 'group') {
              getGroupFields($field[0], value);
            }
            else if(field_type == 'repeater') {
              getRepeaterFields($field[0], value);
            }
            else {
              setFieldValue($field[0], value);
            }
          }

        });

        iRow++;
        initRows = true;

      });
    }

    function getGroupFields(group, values) {
      
      // Loop through each field within the group
      $.each( values, function( key, value ) {
        if (value) {

          var $field = acf.getFields({
              parent: group.$el,
              name: key
          });

          // console.log(key);
          // console.log(value);
          // console.log(group.$el);
          // console.log($field);

          var field_type = $field[0].data.type;

          // Is there a group in a group? Redo this function?
          if(field_type == 'clone' || field_type == 'group') {
            getGroupFields($field[0], value);
          }
          else if(field_type == 'repeater') {
            getRepeaterFields($field[0], value);
          }
          else {
              setFieldValue($field[0], value);
          }
        }

      });

    }

    function setFieldValue($field, value) {

      if(value != null) {

        var field_type = $field.data.type;
        var field_obj  = $field.$el;
        var field_name = $field.data.name;

        // console.log('set value:');
        // console.log(field_type+': '+field_name);
        // console.log('');
        // console.log($field);

        if(field_type == 'taxonomy') {
          // console.log('set value:');
          // console.log(field_type+': '+field_name);
          // console.log('');
          // console.log($field);
          // console.log(value[0]);

          $field.val(value);

          $field.selectTerm(value[0]);

          // testing
          field_obj.addClass('doing'); 
        }

        // Oembed
        if(field_type == 'oembed') {

          // console.log('test');
          // console.log(value);

          value = value.match('src="(.*)\\?feature=oembed"');
          value = value[1];

          field_obj.find('.input-search').val(value);
          $field.maybeSearch();

          // testing
          field_obj.addClass('tested'); 
        }

        // Link
        if(field_type == 'range') {

          $field.val(value);

          // testing
          field_obj.addClass('tested'); 
        }

        // Link
        if(field_type == 'link') {

          $field.val(value);

          // testing
          field_obj.addClass('tested'); 
        }

        // Image
        if(field_type == 'image') {

          if(value != false) {

            $field.val(value);

            var image_id = value.ID;
            var image_url = value.sizes.medium;

            field_obj.find('input[type="hidden"]').val(image_id);
            field_obj.find('img').val(image_id).attr('src', image_url);
            field_obj.find('.acf-image-uploader').addClass('has-value');

          }

          // testing
          field_obj.addClass('tested'); 
        }

        // Gallery
        if(field_type == 'gallery') {

          $field.val(value);

          $.each( value, function( key, img ) {
            $field.appendAttachment(img);
          });

          // testing
          field_obj.addClass('tested'); 
        }

        // Url
        if(field_type == 'url') {

          $field.val(value);

          // testing
          field_obj.addClass('tested'); 
        }

        // Radio
        if(field_type == 'radio') {

          // console.log('bg value: '+ value);
          // console.log($field);

          // find input with value and make active
          field_obj.find('input[value="'+value+'"]').trigger( "click" );

          // testing
          field_obj.addClass('tested'); 
        }

        // select
        if(field_type == 'select') {

          // console.log(value);

          // field_obj.find('select').val(value);
          // field_obj.find('select').trigger('change');

          $field.val(value);

          // testing
          field_obj.addClass('tested'); 
        }

        // true_false
        if(field_type == 'true_false') {

          $field.val(value);

          var isActive = $field.getValue();

          if(isActive != value) {
            field_obj.find('.acf-switch-input').trigger( "click" );
          }

          // testing
          field_obj.addClass('tested'); 
        }

        // text or textarea or number 
        if(field_type == 'text' || field_type == 'textarea' || field_type == 'number') {

          $field.val(value);

          // testing
          field_obj.addClass('tested'); 
        }

        // Wysiwyg
        if(field_type == 'wysiwyg') {

          $field.val(value);

          var field_id = $field.data.id;
          tinyMCE.get(field_id).setContent(value);

          // testing
          field_obj.addClass('tested');
        }

        // checkbox
        if(field_type == 'checkbox') {

          $field.val(value);
          
          // maak alle checkboxes leeg
          field_obj.find('input[type="checkbox"]').prop( "checked", false );

          // maak ingevulde actief
          if(value.length) {
            // loop door de std waarden heen
            $.each( value, function( key, checkbox_value ) {
              var checkbox_field = field_obj.find('input[value="'+checkbox_value+'"]');
              checkbox_field.prop( "checked", true );
              checkbox_field.trigger( "change" );
            });
          }

          // testing
          field_obj.addClass('tested'); 
        }

        setTimeout(() => {
            field_obj.removeClass('tested'); 
        }, 1000);
      }
    }

    // Verborgen blocks
    // Add class hide blocks on page load
    $('#acf-beam_blocks .values .layout').each(function( index ) {
      var $layout = $(this);

      var hiddenField = acf.getFields({
          parent: $layout,
          name: 'hide-block'
      });
      if(hiddenField.length) {
        var val = hiddenField[0].val();

        if(val == 1) {
          $layout.addClass('hide-block');
        }

        addHideBlockControl();
      }
    });

    function addHideBlockControl() {
      var $hiddenFields = acf.getFields({
          parent: $('.page-builder'),
          name: 'hide-block'
      });
      $hiddenFields.forEach(function ($field) {
        
        var $layout = $($field.$el[0]).parents('.layout');
        var $controls = $layout.find('.acf-fc-layout-controls');

        if (!$controls.find('.icon-disable').length) {
          $controls.prepend('<a class="acf-icon icon-disable small light" href="#" data-name="disable-block"></a>')
        }

        var $checkbox = $($field.$el[0]).find('.acf-switch-input');
        $checkbox.on("change", function() {
          var $this = $(this);
          var $layout = $this.parents('.layout');
          let fieldValue = $this.prop('checked') ? true : false;
          if(fieldValue) {
            $layout.addClass('hide-block');
            $layout.addClass('-collapsed');
          } else {
            $layout.removeClass('hide-block');
          }
        });
      });
    }

    // Make hide block btn toggle the real control
    $(document).on('click', '.icon-disable[data-name="disable-block"]', function() {
        var $layout = $(this).parents('.layout');
        var $toggle = $layout.find('.acf-field[data-name="hide-block"] .acf-true-false label');
        $toggle.trigger('click');
    });

    // Selecteer de container waarin .layout elementen worden toegevoegd
    const pagebuilderNode = document.querySelector('.values');

    const pagebuilderObserver = new MutationObserver(function(mutationsList) {
        mutationsList.forEach(function(mutation) {
            // Controleer alleen toevoegingen van nieuwe child elementen
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                mutation.addedNodes.forEach(function(node) {
                    // Controleer of het een .layout element is
                    if (node.nodeType === 1 && node.classList.contains('layout')) {
                      addHideBlockControl();
                    }
                });
            }
        });
    });

    // Configuratie van de observer: Alleen nieuwe directe children monitoren
    const config = { childList: true, subtree: false };

    // Start de observer
    if (pagebuilderNode) {
        pagebuilderObserver.observe(pagebuilderNode, config);
    }

    // Callback functie om extra acties te doen als een nieuw .layout element wordt toegevoegd
    function onNewLayoutAdded(layoutElement) {
        // console.log('Callback uitgevoerd voor nieuw .layout element:', layoutElement);
        // Hier kun je extra functionaliteit toevoegen
    }


  
}); 