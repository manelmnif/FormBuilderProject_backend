

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

                        $('.constraint' + field + '').append(' <label class="col-sm-3 control-label " for="example-input-small">'+
                        data.constraint[i].contrainte +'</label><div class="col-sm-6"><input type="'+
                        data.constraint[i].html +'" name="example-input-small" class="form-control '+data.constraint[i].html+'-sm" placeholder="Label" data-field="' + field 
       + '" ></input></div><br><br><br>');
                    }

                },
                error: function (error) {}

            })
         
           
            return getTextFieldHTML(field);


        },
       

        connectToSortable: '.block-area'
    })

    $('.block-area').sortable({
        start: function (event, ui) {
             attr = ($(ui.item));
             console.log(attr);
        },
    
        receive: function(event, ui) { 
           elementType = $(ui.item).attr("id");

          },
        stop: function( event, ui ) {

         var index = ui.item.index();
         var section = ($(this)).attr('id');
         var field = $(ui.item).attr("data-field");
      
         
         $.ajax({
            url: Routing.generate('createElement'),
            data: {
                name: field,
                section: section,
                elementType: elementType,
                order: index,
                label: 'test',
                placeholder: 'test',
                class: 'test'
            },
            type: 'POST',
          
        })
           
    }      
    });


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
            /*success: function(data) {
                $('.block_'+field+'')
            .attr('data-field',  (data.id) )
            console.log(data.id);
                return data;
                
            },*/
                  
           })
          // updateOrderAjax(false, field);
           
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


        console.log(field)

        var name = $(this).find('.form_input_name').val()

        var html = '';

        html += '<div class="form-group " data-field="' + field 
        + '"><div class="  pull-right"<td ><div><a aria-expanded="false" data-toggle="collapse" class="table-action-btn h3" data-target="#bg-default' + field
        + '"><i class="mdi mdi-settings-box text-success"></i></a><a  class="table-action-btn h3"><i class="mdi mdi-close-box-outline text-danger remove_bal_field" data-field="' + field 
        + '"></i></a></div></td></div></div><div ><div class=" row" ><label id="demo' + field 
        + '" class="control-label labelform">Label</label></div><input type="text" name="' + name 
        + '" placeholder="' + '" class="form-control " ' + '/></div><br></div><div id="bg-default' + field 
        + '" class="collapse" aria-expanded="false" "><div class="portlet-body "><div class="constraint' + field 
        + '"<div class="form-group "><label class="col-sm-3 control-label" for="example-input-small">Label</label><div class="col-sm-6"><input type="text" id="input' + field 
        + '" name="example-input-small" class="form-control input-sm" placeholder="Label" data-field="' + field 
        + '" ></div><br><br><br></div></div></div></div></div>'


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

    $(document).on('keyup', '.input-sm', function () {
        var field = $(this).attr('data-field')
        // console.log(field)

        var x = document.getElementById('input' + field).value

        document.getElementById('demo' + field).innerHTML = x
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