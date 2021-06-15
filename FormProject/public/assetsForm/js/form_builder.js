

$(document).ready(function () {
  
    $('.form_bal_textfield').draggable({
        cursorAt: {left: -10, top: -10},
        helper: function () {
            var id = ($(this)).attr('id');
            var field = generateField();
            // passer type de l'input a la fct getTextFieldHTML
            var balise = ($(this)).attr('balise');
            var type = ($(this)).attr('data-type');
            var elementType = ($(this)).attr('data-elementType');
            

            $.ajax({
                url: Routing.generate('getConstraintByElementType'),
                data: {
                    id: id
                },
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                    for (let i = 0; i < data.constraint.length; i++) {
                        if (data.constraint[i].contrainte != 'required') {

                            $('.modal-body-form' + field + '').append('<div class="col-md-12"><div class="form-group"><label class="control-label" >' + data.constraint[i].contrainte + '</label>' + '<input name="' + data.constraint[i].id + '" id="inputConstraint' + field + '' + i + '" elementType="' + id + '" data-field="' + field +
                            
                            '" type="' + data.constraint[i].html + '" class="contrainte'+field+' form-control ' + data.constraint[i].html + '" id="" placeholder=""> <label  class=" error text-error "  style="display :none;">la valeur de maxlength doit être supérieur à la valeur de minlength!</label><label  class="error number-error " for="form_name" style =" display :none; ">la valeur de max doit être supérieur à la valeur de min!</label></div></div></div> ');
                        } else {
                            $('.modal-body-form' + field + '').append('<div class="col-md-12"><div class="checkbox checkbox-primary">' + '<input type="checkbox" name="' + data.constraint[i].id + '" id="inputConstraint' + field + '" value="1" ><label for="checkbox1">Required</label></div></div> ');

                        }
                    }
                    $('.modal-body-form' + field + '').append('<div class="modal-footer"><button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>' + '<button  elementTypeName="' + elementType + '" elementType="' + id + '" type="button" class="btn btn-info waves-effect waves-light btnElmt" data-field="' + field + '"  >Save changes</button> ' + '</div>');
                },
                error: function (error) {
                    
                }
            })

            
            //if ( id== 1)
            return getTextFieldHTML(field, balise, type, elementType );
        },
        connectToSortable: '.block-area'
    })

 

    $(document).on('click', '.btn-section', function () {

        var url = document.URL;
        var slugIndex = url.lastIndexOf('/');
        if (url.substr(slugIndex) !== '') {
            url = url.substring(slugIndex + 1, url.length);
        }

        var field = generateField();
        $('.form_builder_area').append('<div id="sortable" data-field="' + field +
        
        '" data-block="" class="block_' + field +
        
        ' block "><div class="col-md-12 block_hover" data-field="' + field +
        
        '" ><input><div class="form-group "><div class="pull-right"><a class="table-action-btn h3"><i style="display : none;" class=" mdi mdi-close-box-outline text-danger remove_block removehover_'+field+' "data-field="' + field +
        
        '" ></i></a></div></div><br><div  class="section-box block-area ui-sortable block_hover_'+field+' " data-field="' + field +
        
        '"  ></div></div><div>');
        
        $('.block-area').sortable({
            

            start: function(e, ui){
           
            }
        });

        //$('#element').toolbar( options );
       /* $('.btn-toolbar').toolbar({
            content: '#toolbar-options',
            position: 'right',
        });*/
        $('.block_hover').hover(function(){
            var field = $(this).attr('data-field');
            $('.block_hover_'+field+'').css("border", "2px solid #0000FF");
            $('.removehover_'+field+'').css("display", "block");
        
            }, function(){
            $('.block_hover_'+field+'').css("border", "2px solid #f3f3f3");
            $('.removehover_'+field+'').css("display", "none");
          });
          

          

        updateOrder();
        var order = $('.block_' + field + '').attr('data-block');
        $.ajax({

            url: Routing.generate('createSection'),
            data: {
                name: field,
                order: order,
                form: url
            },
            type: 'POST'
        })
    })
// cadre section hover
    $('.block_hover').hover(function(){
        var field = $(this).attr('data-field');
        $('.block_hover_'+field+'').css("border", "2px solid #0000FF");
        $('.removehover_'+field+'').css("display", "block");
    
        }, function(){
            var field = $(this).attr('data-field');
        $('.block_hover_'+field+'').css("border", "2px solid #f3f3f3");
        $('.removehover_'+field+'').css("display", "none");
      });
    
     /* $('.elmt_hover').hover(function(){
          console.log('test');
        var field = $(this).attr('data-field');
        $('.elmt_hover_'+field+'').css("border", "2px solid #0000FF");
        $('.removehover_'+field+'').css("display", "block");
    
        }, function(){
            var field = $(this).attr('data-field');
        $('.elmt_hover_'+field+'').css("border", "2px solid #f3f3f3");
        $('.removehover_'+field+'').css("display", "none");
      });*/


    $('.form_builder_area').sortable({
        cancel: '.contentedit,input,textarea',

        start: function (event, ui) {

            var start_pos = ui.item.index();
            ui.item.data('start_pos', start_pos);
        },
        update: function (event, ui) {

            updateOrder();

        },

        stop: function (event, ui) {
            var start_pos = ui.item.data('start_pos');
            var index = ui.item.index();
            var field = ui.item.attr('data-field');

            $.ajax({

                url: Routing.generate('updateOrder'),
                data: {
                    sortedSection: field,
                    isDelete: "false",
                    finalOrderSectionSort: index,
                    initialOrderSectionSort: start_pos
                },
                type: 'POST'
            })
        }
    });

    $( ".contentedit" ).attr("contentEditable",true);
// ce traitement se fait lorsque je change position d'un elmt lorque je lis le form sauvgardé dans la base de donné 
    $('.block-area').sortable({
        
     
        cursor: 'move',
        placeholder: 'placeholder',
        start: function (event, ui) {
            ui.placeholder.height(ui.helper.outerHeight());
            
            var start_pos = ui.item.index();
            ui.item.data('start_pos', start_pos);
         
        },

        update: function (event, ui) {

            updateElementOrder();
        },

        receive: function (event, ui) {
            elementType = $(ui.item).attr("id");
        },

        stop: function (event, ui) {

            var index = ui.item.index() + 1;
            var section = ($(this)).attr('id');
            var start_pos = ui.item.data('start_pos') + 1;
            var sortedElement = ui.item.attr('data-field');
            var field = ui.item.attr('data-field');

            
            $.ajax({
                url: Routing.generate('createElement'),

                data: {
                    elementExist: 'true',
                    name: field,
                    section: section,
                    order: index,
                    label: 'label',
                    placeholder: 'placeholder',
                    class: 'test',
                    sortedElement: sortedElement,
                    finalOrderElementSort: index,
                    initialOrderElementSort: start_pos


                },
                success: function (data) {
          
                },
               
                type: 'POST'
            })
        }
    });
  
   // $('.form_builder_area').disableSelection();

    function getTextFieldHTML(field, balise, type, elementType ) {

        $('.block-area').sortable({

         
            start: function (event, ui) {
                ui.placeholder.height(ui.item.height());
                var start_pos = ui.item.index();
                ui.item.data('start_pos', start_pos);
            },

            update: function (event, ui) {

                updateElementOrder();

            },

            receive: function (event, ui) {
                elementType = $(ui.item).attr("id");
             

            },
           

            stop: function (event, ui) {

                var index = ui.item.index() + 1;
                var section = ($(this)).attr('id');
                var start_pos = ui.item.data('start_pos') + 1;
                var sortedElement = ui.item.attr('data-field');

          


                $.ajax({
                    url: Routing.generate('createElement'),

                    data: {
                        name: field,
                        section: section,
                        elementType: elementType,
                        order: index,
                        label: 'label',
                        placeholder: 'placeholder',
                        class: 'test',
                        sortedElement: sortedElement,
                        finalOrderElementSort: index,
                        initialOrderElementSort: start_pos
                    },
                    success: function (data) {
                       
                    },
                    type: 'POST'
                })
            }
        });
      
        var html = '';
        console.log(elementType);

        
    if (elementType == "Date") {

    html += '<div class="form-group " data-field="' + field 
        
    + '"><div class="  pull-right"><td ><div><a  class="table-action-btn h3" ><i class="mdi mdi-settings-box text-success" id="openModalButton" data-toggle="modal" data-target="#con-close-modal_' + field +
    
    '" data-animation="fadein" data-plugin="custommodal" data-overlayspeed="200" data-overlaycolor="#36404a"></i></a>' +
    
    '<a  class="table-action-btn h3"><i class="mdi mdi-close-box-outline text-danger remove_bal_field" data-field="' + field +
    
    '"></i></a></div></td></div></div><div ><div class="form-group m-l-15 form_elmt" ><p id="label' + field +
    
    '" class="control-label labelform">Label</p> <div class=" row"><div class="col-md-12"><div class="input-group"><'+balise+' id="" type="'+type+'" name="' + name + '" placeholder="placeholder" class="form-control datepicker" ' +
    
    '/><span class="input-group-addon bg-custom b-0"><i class="mdi mdi-calendar text-white"></i></span></div></div></div></div><br></div></div></div></div></div>' +
    
    '<div id="con-close-modal_' + field + '" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" ><div class="modal-dialog"><div class="modal-content "><div class="modal-header">' +
    
    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' + '<h4 class="modal-title">Parametrage du champ </h4></div><div class=" modal-body' + field + ' custom-modal-text text-left"><form id="form_' 
    
    + field + '" class="modal-body-form' + field + '">' + '<div class="col-md-12"><div class="form-group"><label id="' + field + '" for="name">Label</label>' + '<input type="text" class="form-control" id="inputLabel' + field + '" placeholder="Label"></div></div>' 
    
    + '<div class="col-md-12"><div class="form-group"><label id="' + field + '" for="name">Placeholder</label>' + '<input type="text" class="form-control" id="inputPlaceholder' + field + '" placeholder="Placeholder">' + '</div></div></div></form></div></div>'

}
    else if (elementType == "Checkbox") {

        html += '<div class="form-group " data-field="' + field 
        
        + '"><div class="  pull-right"><td ><div><a  class="table-action-btn h3" ><i class="mdi mdi-settings-box text-success" id="openModalButton" data-toggle="modal" data-target="#con-close-modal_' + field +
        
        '" data-animation="fadein" data-plugin="custommodal" data-overlayspeed="200" data-overlaycolor="#36404a"></i></a>' +
        
        '<a  class="table-action-btn h3"><i class="mdi mdi-close-box-outline text-danger remove_bal_field" data-field="' + field +
        
        '"></i></a></div></td></div></div><div ><div class="form-group m-l-15 form_elmt" ><div class="checkbox checkbox-primary"><input value="" name="" id="placeholder' + field + '" type="checkbox"><label for="checkbox1">label</label></div></div></div></div><br></div></div></div></div></div>' +
        
        '<div id="con-close-modal_' + field + '" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" ><div class="modal-dialog"><div class="modal-content"><div class="modal-header">' +
        
        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' + '<h4 class="modal-title">Parametrage du champ </h4></div><div class=" modal-body' + field + ' custom-modal-text text-left"><form id="form_' 
        
        + field + '" class="modal-body-form' + field + '">' + '<div class="col-md-12"><div class="form-group"><label id="' + field + '" for="name">Label</label>' + '<input type="text" class="form-control" id="inputLabel' + field + '" placeholder="Label"></div></div>' 
        
        + '<div class="col-md-12"><div class="form-group"><label id="' + field + '" for="name">Placeholder</label>' + '<input type="text" class="form-control" id="inputPlaceholder' + field + '" placeholder="Placeholder">' + '</div></div></div></form></div></div>'
    }
        else {

            html += '<div class="form-group elmt_hover" data-field="' + field 
            
            + '"><div class="  pull-right"><td ><div><a  class="table-action-btn h3" ><i class="mdi mdi-settings-box text-success" id="openModalButton" data-toggle="modal" data-target="#con-close-modal_' + field +
            
            '" data-animation="fadein" data-plugin="custommodal" data-overlayspeed="200" data-overlaycolor="#36404a"></i></a>' +
            
            '<a  class="table-action-btn h3"><i class="mdi mdi-close-box-outline text-danger remove_bal_field" data-field="' + field +
            
            '"></i></a></div></td></div></div><div ><div class="form-group m-l-15 form_elmt" ><p id="label' + field +
            
            '" class="control-label labelform">Label</p> <div class=" row"><div class="col-md-12"><'+balise+' id="placeholder' + field + '" type="'+type+'" name="' + name + '" placeholder="placeholder" class="form-control " ' +
            
            '/></div></div></div><br></div></div></div></div></div>' +
            
            '<div id="con-close-modal_' + field + '" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" ><div class="modal-dialog "><div class="modal-content modal-dialog' + field +
            
            '"><div class="modal-header">' +
            
            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' + '<h4 class="modal-title">Parametrage du champ </h4></div><div class=" modal-body' + field + ' custom-modal-text text-left"><form id="form_' 
            
            + field + '" class="modal-body-form' + field + '">' + '<div class="col-md-12"><div class="form-group"><label id="' + field + '" for="name">Label</label>' + '<input type="text" class="form-control" id="inputLabel' + field + '" placeholder="Label"></div></div>' 
            
            + '<div class="col-md-12"><div class="form-group"><label id="' + field + '" for="name">Placeholder</label>' + '<input type="text" class="form-control" id="inputPlaceholder' + field + '" placeholder="Placeholder">' + '</div></div></div></form></div></div>'
        }

        
       
        return $('<div>').addClass('li_' + field + ' form_builder_field elmt elmt_hover  elmt_hover_'+field+'').attr('data-field', field).html(html)

    }


    $(document).on('click', '.remove_bal_field', function (e) {
        
        e.preventDefault()
        var field = $(this).attr('data-field');
        var elmt = $("div[data-field='" + field + "']");
        var order = elmt.attr('order-elmt');
        $(this).closest('.li_' + field).hide('400', function () {
            $('.li_' + field).remove();
            updateElementOrder();
        })
        updateElementOrderAjax(true, field, order);
    })

    $(document).on('click', '.remove_block', function (e) {

        var field = $(this).attr('data-field');
        var block = $("div[data-field='" + field + "']");
  
        var order = block.attr('data-block');


        $(this).closest('.block_' + field).hide('400', function () {
            $('.block_' + field).remove()
            updateOrder();

        })

        updateOrderAjax(true, field, order);
    }) 

    $(document).on('change', '.checkbox', function () {
        if ($(this).prop( "checked")){
            //console.log('true'); 
        }
      else{
           // console.log('false'); 
        }
        
    })
    $(document).on('change', '.form_input_req', function () {
        getPreview()
    })
    $(document).on('keyup', '.form_input_placeholder', function () {
        getPreview()
    })
    $(document).on('keyup', '.form_input_label', function () {
        getPreview()
    })
    $(document).on('keyup', '.form_input_name', function () {
        getPreview()
    })

    $(document).on('keyup', '.formlabel', function () {})

    $(document).on('click', '#openModalButton', function () {
        $('.btnElmt').removeAttr('data-dismiss');


    })

    $(document).on('click', '.btnElmt', function () {
        var field = $(this).attr('data-field');
        var elementType = $(this).attr('elementType');
        var type = $(this).attr('elementTypeName');
        console.log(type);

        var label = document.getElementById('inputLabel' + field).value
        document.getElementById('label' + field).innerHTML = label;

        var placeholder = document.getElementById('inputPlaceholder' + field).value;
        document.getElementById('placeholder' + field).placeholder = placeholder;

      
        var formData = $('#form_' + field + '').serializeArray();
        console.log(formData);

       
      
  
        if ( (parseInt(formData[0].value)) < (parseInt(formData[1].value)) && (type == "Text Field" || type == "Text Area")  ) 
        
        {
            $('.text-error').css("display", "block");
            $('.contrainte'+field+'').addClass('error');
         
        }

     else if ( (parseInt(formData[1].value)) < (parseInt(formData[0].value)) && type == "Number" )
     {
        $('.contrainte'+field+'').addClass('error');
        console.log('error');
        $('.number-error').css("display", "block");
     }
        else 
        {   
         
            $('.contrainte'+field+'').removeClass('error');
            $('.number-error').css("display", "none");
            $('.text-error').css("display", "none");
            var test = $(this).attr('data-dismiss');
            console.log(test);
            if ( test == undefined) {

            $(this).attr('data-dismiss','modal');
            //second click
            $(this).trigger('click');
            }
            console.log('1');

           
            $.ajax({
                url: Routing.generate('updateSettingsElement'),
                data: {
                    elementField: field,
                    label: label,
                    placeholder: placeholder,
                    elementType: elementType,
                    value: formData
                },
                type: 'POST',
               
              
           
            })
            
            
        }

    
          
         
      

      
    })

    function generateField() {
        return Math.floor(Math.random() * (100000 - 1 + 1) + 57)
    }
    // Update section order
    function updateOrder() {

        $('.block').each(function () {
            var field = $(this).attr('data-field');
            var order = $('.block').index($('.block_' + field + ''));

            $('.block_' + field + '').attr('data-block', (order + 1))
        });
    }

    function updateOrderAjax(isDelete, deletedSection, order) {

        $.ajax({
            url: Routing.generate('updateOrder'),
            data: {
                name: deletedSection,
                isDelete: isDelete,
                ordersection: order

            },
            type: 'POST'

        })
    }

    // Update element order
    function updateElementOrder() {

        $('.elmt').each(function () {
            var field = $(this).attr('data-field');
            var order = $('.elmt').index($('.li_' + field + ''));

            $('.li_' + field + '').attr('order-elmt', (order + 1))
        });
    }

    function updateElementOrderAjax(isDelete, deletedElement, order) {
        
        $.ajax({
            url: Routing.generate('updateElementOrder'),
            data: {
                name: deletedElement,
                isDelete: isDelete,
                orderElement: order

            },
            type: 'POST',
           

        })
    }
  
    $(document).on('focusout', '.titre', function () {
        field =  $(this).attr('id');
        titre = document.getElementById(field).value;
        
        request = $.ajax({

            url: Routing.generate('setTitleSection'),
            data: {
                name: field,
                title: titre
            },
            type: 'POST'
        })
    })

    

   

 
// visualise Form
// show modal when form is valid
  $(document).on('click', '.valid', function () {
    if ($(validationform).validate().checkForm()){
        
        $('#custom-width-modal').modal('toggle');
    }
    else {
        $(this).removeAttr('data-toggle');
    }  
    })

    $(document).on('click', '.publish', function () {
             id =  $(this).attr('id');
        $.ajax({

            url: Routing.generate('publishForm'),
            data: {
                id: id
            },
            type: 'POST'
        })

    })

    // manage forms

    $(document).on('click', '.remove_form', function (e) {

        var id = $(this).attr('id');
     
        $(this).closest('.form_' +id).hide('400', function () {
            $('.form_' +id).remove()
        })

        $.ajax({

            url: Routing.generate('deleteForm'),
            data: {
                id: id,
                
            },
            type: 'POST'
        })

    })

    $(document).on('click', '.edit', function (e) {

        var id = $(this).attr('id');

        var name = document.getElementById('name_' + id+'').value;
        var description = document.getElementById('description_' + id+'').value;
        $.ajax({

            url: Routing.generate('updateForm'),
            data: {
                id: id,
                name: name,
                description: description
                
            },
            type: 'POST',
            success: function(data){
                setTimeout(function(){// wait 
                    location.reload(); // then reload the page.(3)
               }); 
             }
        })

    })

    //Form Data Value

 /*$(document).on('click', '.addValueForm', function (e) {

    var formData = $('#dataValue').serializeArray();
    var array = [];
 
    $('form input').each(function(index, e) {
        array.push($(this).attr('id'));
        console.log($(this).attr('id'));
      });
      console.log(array);
  
 
    console.log(formData);
    $.ajax({

        url: Routing.generate('setElementsValues'),
        data: {
            
            values: formData
           
            
        },
        type: 'POST',
        success: function(data){
           
         }
    })


    })*/

  /*  function getSerialize(form)
{
 var selected = $('form input:checked');
 var serialized = '';
 selected.each(function(){
 	if(serialized != '')serialized += '&';
 	serialized += $(this).attr('name') + '=' + $(this).attr('id');
 });
 return serialized;
}*/

    

    



    
})
