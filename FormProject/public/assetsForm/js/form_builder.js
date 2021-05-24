

$(document).ready(function () {
 
    $('.form_bal_textfield').draggable({
        helper: function () {
            var id = ($(this)).attr('id');
            var field = generateField();

            $.ajax({
                url: Routing.generate('getConstraintByElementType'),
                data: {
                    id: id
                },
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                    for (let i = 0; i < data.constraint.length; i++) {
                        if ( data.constraint[i].contrainte != 'required') {
                            $('.modal-body' + field + '').append('<div class="col-md-12"><div class="form-group"><label class="control-label" >'+data.constraint[i].contrainte +

                            '</label>' + '<input id="inputConstraint'+field+''+i+'" elementType="' + id + '" data-field="' + field + '" type="'+data.constraint[i].html+
        
                            '" class="form-control '+data.constraint[i].html+' id="" placeholder=""></div></div> ');
                         }

                        else {
                            $('.modal-body' + field + '').append('<div class="col-md-12"><div class="checkbox checkbox-primary">'+
                                
                            '<input id="checkbox1" type="checkbox"><label for="checkbox1">Required</label></div></div> ');
                        }

                          
                    }
                    $('.modal-body' + field + '').append('<div class="modal-footer"><button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>'

                    + '<button elementType="'+ id +'" type="button" class="btn btn-info waves-effect waves-light btnElmt" data-field="'+field+'" data-dismiss="modal">Save changes</button> '
            
                    + '</div>');

                    
                }, 
                error: function (error) {}
            })
            return getTextFieldHTML(field);
        },
        connectToSortable: '.block-area'
    })

    $(document).on('click', '.btn-section', function () {

        var url = document.URL;
        var slugIndex = url.lastIndexOf('/');
        if (url.substr(slugIndex) !== '') {
            url = url.substring(slugIndex+1, url.length);
           }
        
        var field = generateField();
        
        $('.form_builder_area').append('<div id="sortable" data-field="'+ field +
        '" data-block="" class="block_'+field+' block "><div class="col-md-12"><div class="form-group "><div class="pull-right"><a class="table-action-btn h3"><i class="mdi mdi-close-box-outline text-danger remove_block "data-field="'+field 
        + '" ></i></a></div></div><br><div class="jFiler-input-dragDrop block-area ui-sortable " style="width:100%;" ></div></div><div>');
        $('.block-area').sortable();
        
        
        updateOrder();
        var order = $('.block_'+field+'').attr('data-block');
        console.log(order);
        $.ajax({
            
            url: Routing.generate('createSection'),
            data: { name :  field,
                    order: order,
                    form: url },
            type: 'POST',    
           })     
    })
   
   
       $('.form_builder_area').sortable({
        start: function( event, ui ) {
            
            var start_pos = ui.item.index();
            ui.item.data('start_pos', start_pos);      
},
        update: function( event, ui ) {
            
            updateOrder();
           
        },
       
        stop: function( event, ui ) {
            var start_pos = ui.item.data('start_pos');
            console.log(start_pos)
            var index = ui.item.index();
                console.log(index);
                var field = ui.item.attr('data-field');
            console.log(field);
            

                $.ajax({
            
                    url: Routing.generate('updateOrder'),
                    data: { 
                            sortedSection : field,
                            isDelete: "false",
                            finalOrderSectionSort: index,
                            initialOrderSectionSort: start_pos,  
                         },
                    type: 'POST',
                   })                     
},   
    });

      $('.form_builder_area').disableSelection();
    
    function getTextFieldHTML(field) {
        
        $('.block-area').sortable( {
           
                
            start: function (event, ui) {
                 
            },
        
            receive: function(event, ui) { 
               elementType = $(ui.item).attr("id");
    
              },
            
            stop: function( event, ui ) {
      
          
             var index = ui.item.index();
             var section = ($(this)).attr('id');

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
                },
                success: function(data) {
                    $('.li_'+field+'')
            .attr('data-elmt',  (data.id) )      
                },
                type: 'POST',
              
            })  
              field1 = $(ui.item).attr("data-elmt");
        }      
        });
       
        var html = '';

          html += '<div class="form-group " data-field="' + field 

        + '"><div class="  pull-right"><td ><div><a  class="table-action-btn h3" d"><i class="mdi mdi-settings-box text-success" id="openModalButton" data-toggle="modal" data-target="#con-close-modal_'+field+'" data-animation="fadein" data-plugin="custommodal" data-overlayspeed="200" data-overlaycolor="#36404a"></i></a>'
        
        +'<a  class="table-action-btn h3"><i class="mdi mdi-close-box-outline text-danger remove_bal_field" data-field="' + field 

        + '"></i></a></div></td></div></div><div ><div class=" row" ><label id="label' + field 

        + '" class="control-label labelform">Label</label></div><input id="placeholder' + field 

        + '" type="text" name="' + name 

        + '" placeholder="placeholder" class="form-control " ' + '/></div><br></div></div></div></div></div>'

        + '<div id="con-close-modal_'+ field +'" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" ><div class="modal-dialog"><div class="modal-content"><div class="modal-header">' 

        + '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'

        + '<h4 class="modal-title">Parametrage du champ </h4></div><div class=" modal-body' + field + ' custom-modal-text text-left">'

        + '<div class="col-md-12"><div class="form-group"><label id="'+ field +'" for="name">Label</label>'

        +'<input type="text" class="form-control" id="inputLabel'+ field +'" placeholder="Label"></div></div>'

        +'<div class="col-md-12"><div class="form-group"><label id="'+ field +'" for="name">Placeholder</label>'

        +'<input type="text" class="form-control" id="inputPlaceholder'+ field +'" placeholder="Placeholder">'

        +'</div></div></div></div></div>'


        return $('<div>').addClass('li_' + field + ' form_builder_field')
        .attr('data-field', field)
        .html(html)
    }
 

    $(document).on('click', '.remove_bal_field', function (e) {
        console.log(e)
        e.preventDefault()
        var field = $(this).attr('data-field')
        $(this).closest('.li_' + field).hide('400', function () {
            $('.li_' + field).remove()
        })
    })

    $(document).on('click', '.remove_block', function (e) {
        
        var field = $(this).attr('data-field');
        var block = $("div[data-field='" + field +"']");
        console.log(block);
        var order = block.attr('data-block');
        
        console.log(order);
        
        $(this).closest('.block_' + field).hide('400', function () {
            $('.block_' + field).remove()
            updateOrder();
         
        })
  
        updateOrderAjax(true, field, order);            
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

        $(document).on('click', '.btnElmt', function () {
            var field = $(this).attr('data-field');
            var elementType = $(this).attr('elementType');

          
            var label = document.getElementById('inputLabel' + field).value
            document.getElementById('label' + field).innerHTML = label;

            var placeholder = document.getElementById('inputPlaceholder' + field).value;
            document.getElementById('placeholder' + field).placeholder = placeholder;
        
            values = [];
            for ( let i=0; i<2; i++){
            var valueConstraint = document.getElementById('inputConstraint'+field+''+i).value;
            //var valueConstraint1 = document.getElementById('inputConstraint'+field+''+1).value;
            values.push(valueConstraint);
        }
            console.log(values);
           // values = [4,5]
           //values = [valueConstraint, valueConstraint1];
           
            $.ajax({
                url: Routing.generate('updateSettingsElement'),
                data: { 
                        elementField : field,
                        label: label,
                        placeholder: placeholder,
                        elementType: elementType,
                        value: values,
                     },
                type: 'POST', 
               })
        })

    function generateField() {
        return Math.floor(Math.random() * (100000 - 1 + 1) + 57)
    }

    function updateOrder() {
 
        $( '.block' ).each(function() {
            var field = $(this).attr('data-field');
            var order = $('.block').index($('.block_'+field+''));

            $('.block_'+field+'')
            .attr('data-block',  (order+1) )
          });
    }

    function updateOrderAjax(isDelete, deletedSection, order) {

            $.ajax({
                url: Routing.generate('updateOrder'),
                data: { 
                        name : deletedSection,
                        isDelete: isDelete,
                        ordersection: order,
                  
                     },
                type: 'POST',
                 
               })
    }
})