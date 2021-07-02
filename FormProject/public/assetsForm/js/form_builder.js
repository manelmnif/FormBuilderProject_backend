

$(document).ready(function () {
  
    $('.form_bal_textfield').draggable({
        cursorAt: {left: -10, top: -10},
        helper: function () {
            var dropdownfield = generateField();
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
                        if (data.constraint[i].contrainte != 'required' && data.constraint[i].contrainte != 'input_only_number') {

                            $('.modal-body-form' + field + '').append('<div class="col-md-12"><div class="form-group"><label class="control-label" >' + data.constraint[i].contrainte + '</label>' + '<input name="' + data.constraint[i].id + '" id="inputConstraint' + field + '' + i + '" elementType="' + id + '" data-field="' + field +
                            
                            '" type="' + data.constraint[i].html + '" class="contrainte'+field+' form-control ' + data.constraint[i].html + '" id="" placeholder=""> <label  class=" error text-error "  style="display :none;">la valeur de maxlength doit être supérieur à la valeur de minlength!</label><label  class="error number-error " for="form_name" style =" display :none; ">la valeur de max doit être supérieur à la valeur de min!</label></div></div></div> ');
                        } else {
                            $('.modal-body-form' + field + '').append('<div class="col-md-12"><div class="checkbox checkbox-primary">' + '<input type="checkbox" name="' + data.constraint[i].id + '" id="inputConstraint' + field + '" value="1" ><label for="checkbox1">'+data.constraint[i].contrainte+'</label></div></div> ');

                        }
                    }
                    if(elementType=="Dropdown List" || elementType=="Multiple Checkbox" ){
                        $('.modal-body-form' + field + '').append('<div class="row"><div class="col-md-8 "><div class="form-group choice'+field+
        
        '"><label >Les Options</label>&nbsp;&nbsp;&nbsp;<a class="table-action-btn h3"><i class="mdi mdi-plus-box text-info addChoice" data-field="'+field+'" ></i></a>' + '<div class="row dropdown_'+dropdownfield+'" data-dropdown-field="'+dropdownfield+'" ><div class="col-md-8"><input data-name="choice_'+field+'" dropdownfield="'+dropdownfield+'" data_field="'+field+'" id="'+dropdownfield+'" type="text" class="form-control option'+field+'" placeholder="Choix"></div><div class="col-md-4"><a class="table-action-btn h3"><i class="mdi mdi-close-box-outline text-danger remove_choice" data-dropdown-field="'+dropdownfield+
        
        '"></i></a></div></div></div></div></div>');
                    }
                    $('.modal-body-form' + field + '').append('<div class="row"><div class="com-md-12"><center><button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fermer</button> ' + '<button  elementTypeName="' + elementType + '" elementType="' + id + '" type="button" class="btn btn-info waves-effect waves-light btnElmt" data-field="' + field + '">Enregistrer</button></center></div></div>');
                },
                error: function (error) {
                    
                }
            })
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
        
        '" > <div class="form-group " style="margin-top: 20px; margin-bottom: -10px;"><input class="titre" placeholder="Titre de la section" style="border:none; font-family:Garamond, serif; font-weight: bold; font-size: 30px; color: black; "><div class="pull-right"><a class="table-action-btn h3"><i style="display : none;" class=" mdi mdi-close-box-outline text-danger remove_block removehover_'+field+' "data-field="' + field +
        
        '" ></i></a></div></div><br><div  id="'+field+'" class="section-box block-area ui-sortable block_hover_'+field+' " data-field="' + field +
        
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
                    label: 'Label',
                    placeholder: 'Placeholder',
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
console.log(elementType);
                if (elementType !=6){
                $.ajax({
                    url: Routing.generate('createElement'),

                    data: {
                        name: field,
                        section: section,
                        elementType: elementType,
                        order: index,
                        label: 'Label',
                        placeholder: 'Placeholder',
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
            //elementType== email
            else {
                $.ajax({
                    url: Routing.generate('createElement'),

                    data: {
                        name: field,
                        section: section,
                        elementType: elementType,
                        order: index,
                        label: 'E-mail',
                        placeholder: 'email@adress.com',
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
            }
        });
      
        var html = '';     
    if (elementType == "Date") {

    html += '<div class="form-group " data-field="' + field 
        
    + '"><div class="  pull-right"><td ><div><a  class="table-action-btn h3" ><i class="mdi mdi-settings-box text-success" id="openModalButton" data-toggle="modal" data-target="#con-close-modal_' + field +
    
    '" data-animation="fadein" data-plugin="custommodal" data-overlayspeed="200" data-overlaycolor="#36404a"></i></a>' +
    
    '<a  class="table-action-btn h3"><i class="mdi mdi-close-box-outline text-danger remove_bal_field" data-field="' + field +
    
    '"></i></a></div></td></div></div><div ><div class="form-group m-l-15 form_elmt" ><p id="label' + field +
    
    '" class="control-label labelform">Label</p> <div class=" row"><div class="col-md-12"><div class="input-group"><'+balise+' id="" type="'+type+'" name="' + name + '" placeholder="Placeholder" class="form-control datepicker" ' +
    
    '/><span class="input-group-addon bg-custom b-0"><i class="mdi mdi-calendar text-white"></i></span></div></div></div></div><br></div></div></div></div></div>' +
    
    '<div id="con-close-modal_' + field + '" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" ><div class="modal-dialog"><div class="modal-content "><div class="modal-header">' +
    
    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="display:block;">×</button>' + '<h4 class="modal-title"><center>Parametrage du champ</center></h4></div><div class=" modal-body' + field + ' custom-modal-text text-left"><form id="form_' 
    
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
        
        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="display:block;">×</button>' + '<h4 class="modal-title"><center>Parametrage du champ</center> </h4></div><div class=" modal-body' + field + ' custom-modal-text text-left"><form id="form_' 
        
        + field + '" class="modal-body-form' + field + '">' + '<div class="col-md-12"><div class="form-group"><label id="' + field + '" for="name">Label</label>' + '<input type="text" class="form-control" id="inputLabel' + field + '" placeholder="Label"></div></div>' 
        
        + '<div class="col-md-12"><div class="form-group"><label id="' + field + '" for="name">Placeholder</label>' + '<input type="text" class="form-control" id="inputPlaceholder' + field + '" placeholder="Placeholder">' + '</div></div></div></form></div></div>'
    }

    else if (elementType == "E-mail") {

        html += '<div class="form-group elmt_hover" data-field="' + field 
        
        + '"><div class="  pull-right"><td ><div><a  class="table-action-btn h3" ><i class="mdi mdi-settings-box text-success" id="openModalButton" data-toggle="modal" data-target="#con-close-modal_' + field +
        
        '" data-animation="fadein" data-plugin="custommodal" data-overlayspeed="200" data-overlaycolor="#36404a"></i></a>' +
        
        '<a  class="table-action-btn h3"><i class="mdi mdi-close-box-outline text-danger remove_bal_field" data-field="' + field +
        
        '"></i></a></div></td></div></div><div ><div class="form-group m-l-15 form_elmt" ><p id="label' + field +
        
        '" class="control-label labelform">E-mail</p> <div class=" row"><div class="col-md-12"><'+balise+' id="placeholder' + field + '" type="'+type+'" name="' + name + '" placeholder="email@adress.com" class="form-control " ' +
        
        '/></div></div></div><br></div></div></div></div></div>' +
        
        '<div id="con-close-modal_' + field + '" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" ><div class="modal-dialog "><div class="modal-content modal-dialog' + field +
        
        '"><div class="modal-header">' +
        
        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="display:block;">×</button>' + '<h4 class="modal-title"><center>Parametrage du champ</center></h4></div><div class=" modal-body' + field + ' custom-modal-text text-left"><form name="settingsform" id="form_' 
        
        + field + '" class="modal-body-form' + field + '">' + '<div class="col-md-12"><div class="form-group"><label id="' + field + '" for="name">Label</label>' + '<input required type="text" class="form-control" id="inputLabel' + field + '" placeholder="Email"></div></div>' 
        
        + '<div class="col-md-12"><div class="form-group"><label id="' + field + '" for="name">Placeholder</label>' + '<input type="text" class="form-control" id="inputPlaceholder' + field + '" placeholder="email@adress.com">' + '</div></div></div></form></div></div>'
    }
    else if (elementType == "Dropdown List"){

        html += '<div class="form-group elmt_hover" data-field="' + field 
        
        + '"><div class="  pull-right"><td ><div><a  class="table-action-btn h3" ><i class="mdi mdi-settings-box text-success" id="openModalButton" data-toggle="modal" data-target="#con-close-modal_' + field +
        
        '" data-animation="fadein" data-plugin="custommodal" data-overlayspeed="200" data-overlaycolor="#36404a"></i></a>' +
        
        '<a  class="table-action-btn h3"><i class="mdi mdi-close-box-outline text-danger remove_bal_field" data-field="' + field +
        
        '"></i></a></div></td></div></div><div ><div class="form-group m-l-15 form_elmt" ><p id="label' + field +
        
        '" class="control-label labelform">Label</p> <div class=" row"><select title="select" class="form-control"></select></div></div></div><br></div></div></div></div></div>' +
        
        '<div id="con-close-modal_' + field + '" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" ><div class="modal-dialog "><div class="modal-content modal-dialog' + field +
        
        '"><div class="modal-header">' +
        
        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="display:block;">×</button>' + '<h4 class="modal-title"><center>Parametrage du champ</center></h4></div><div class=" modal-body' + field + ' custom-modal-text text-left"><form name="settingsform" id="form_' 
        
        + field + '" class="modal-body-form' + field + '">' + '<div class="row"><div class="col-md-12"><div class="form-group"><label id="' + field + '" for="name">Label</label>' + '<input required type="text" class="form-control" id="inputLabel' + field + '" placeholder="Label"></div></div></div>' 
        
        + '<div class="row"><div class="col-md-12"><div class="form-group"><label id="' + field + '" for="name">Placeholder</label>' + '<input  type="text" class="form-control" id="inputPlaceholder' + field +
        
        '" placeholder="Placeholder">' + '</div></div></div></form></div></div>'
    }

    else if (elementType == "Multiple Checkbox") {

        html += '<div class="form-group " data-field="' + field 
        
        + '"><div class="  pull-right"><td ><div><a  class="table-action-btn h3" ><i class="mdi mdi-settings-box text-success" id="openModalButton" data-toggle="modal" data-target="#con-close-modal_' + field +
        
        '" data-animation="fadein" data-plugin="custommodal" data-overlayspeed="200" data-overlaycolor="#36404a"></i></a>' +
        
        '<a  class="table-action-btn h3"><i class="mdi mdi-close-box-outline text-danger remove_bal_field" data-field="' + field +
        
        '"></i></a></div></td></div></div><div ><div class="form-group m-l-15 form_elmt" ><p id="label' + field +
        
        '" class="control-label labelform">Label</p><div class="checkbox checkbox-primary"><input value="" name="" id="placeholder' + field + '" type="checkbox"><label for="checkbox1">option 1</label></div><div class="checkbox checkbox-primary"><input value="" name="" id="placeholder' + field + '" type="checkbox"><label for="checkbox1">option 2</label></div><div class="checkbox checkbox-primary"><input value="" name="" id="placeholder' + field + '" type="checkbox"><label for="checkbox1">option 3</label></div></div></div></div></div></div></div></div></div>' +
        
        '<div id="con-close-modal_' + field + '" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" ><div class="modal-dialog"><div class="modal-content"><div class="modal-header">' +
        
        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="display:block;">×</button>' + '<h4 class="modal-title"><center>Parametrage du champ</center> </h4></div><div class=" modal-body' + field + ' custom-modal-text text-left"><form id="form_' 
        
        + field + '" class="modal-body-form' + field + '">' + '<div class="col-md-12"><div class="form-group"><label id="' + field + '" for="name">Label</label>' + '<input type="text" class="form-control" id="inputLabel' + field + '" placeholder="Label"></div></div>' 
        
        + '<div class="col-md-12"><div class="form-group"><label id="' + field + '" for="name">Placeholder</label>' + '<input type="text" class="form-control" id="inputPlaceholder' + field + '" placeholder="Placeholder">' + '</div></div></div></form></div></div>'
    }

    
        else {

            html += '<div class="form-group elmt_hover" data-field="' + field 
            
            + '"><div class="  pull-right"><td ><div><a  class="table-action-btn h3" ><i class="mdi mdi-settings-box text-success" id="openModalButton" data-toggle="modal" data-target="#con-close-modal_' + field +
            
            '" data-animation="fadein" data-plugin="custommodal" data-overlayspeed="200" data-overlaycolor="#36404a"></i></a>' +
            
            '<a  class="table-action-btn h3"><i class="mdi mdi-close-box-outline text-danger remove_bal_field" data-field="' + field +
            
            '"></i></a></div></td></div></div><div ><div class="form-group m-l-15 form_elmt" ><p id="label' + field +
            
            '" class="control-label labelform">Label</p> <div class=" row"><div class="col-md-12"><'+balise+' id="placeholder' + field + '" type="'+type+'" name="' + name + '" placeholder="Placeholder" class="form-control " ' +
            
            '/></div></div></div><br></div></div></div></div></div>' +
            
            '<div id="con-close-modal_' + field + '" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" ><div class="modal-dialog "><div class="modal-content modal-dialog' + field +
            
            '"><div class="modal-header">' +
            
            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="display:block;">×</button>' + '<h4 class="modal-title"><center>Parametrage du champ</center></h4></div><div class=" modal-body' + field + ' custom-modal-text text-left"><form name="settingsform" id="form_' 
            
            + field + '" class="modal-body-form' + field + '">' + '<div class="col-md-12"><div class="form-group"><label id="' + field + '" for="name">Label</label>' + '<input required type="text" class="form-control" id="inputLabel' + field + '" placeholder="Label"></div></div>' 
            
            + '<div class="col-md-12"><div class="form-group"><label id="' + field + '" for="name">Placeholder</label>' + '<input type="text" class="form-control" id="inputPlaceholder' + field + '" placeholder="Placeholder">' + '</div></div></div></form></div></div>'
        }

        
       
        return $('<div>').addClass('li_' + field + ' form_builder_field elmt elmt_hover  elmt_hover_'+field+'').attr('data-field', field).html(html)

    }

    $(document).on('click', '.addChoice', function (e) {
        var field = $(this).attr('data-field');
        var dropdownfield = generateField();
        $('.choice'+field).append('<br class="dropdown_'+dropdownfield+'"><div class="row dropdown_'+dropdownfield+'" data-dropdown-field="'+dropdownfield+'"><div class="col-md-8"><input data-name="choice_'+field+'" dropdownfield="'+dropdownfield+'" data_field="'+field+'" id="'+dropdownfield+'" type="text" class="form-control option'+field+'" placeholder="Choix"></div><div class="col-md-4"><a class="table-action-btn h3"><i class="mdi mdi-close-box-outline text-danger remove_choice" data-dropdown-field="'+dropdownfield+'"></i></a></div></div>')
        

    })

    $(document).on('click', '.remove_choice', function (e) {
        
        e.preventDefault()
        var field = $(this).attr('data-dropdown-field');
        $(this).closest('.dropdown_' +field).hide('400', function () {
                $('.dropdown_' +field).remove()
            })  
    })
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

    $(document).on('keyup', '.formlabel', function () {})

    $(document).on('click', '#openModalButton', function () {
        $('.btnElmt').removeAttr('data-dismiss');


    })

    $(document).on('click', '.btnElmt', function () {
        var field = $(this).attr('data-field');
        var elementType = $(this).attr('elementtype');
        var type = $(this).attr('elementTypeName');
        console.log(type);
        console.log(field);

        var label = document.getElementById('inputLabel' + field).value
        document.getElementById('label' + field).innerHTML = label;
  

       
        var placeholder = document.getElementById('inputPlaceholder' + field).value;
        if(type != "Dropdown List"){
        document.getElementById('placeholder' + field).placeholder = placeholder;
        }

      
        var formData = $('#form_'+field).serializeArray();
      
  if(type == "Text Field" || type == "Text Area"){
        if ( (parseInt(formData[0].value)) < (parseInt(formData[1].value))  ) 
        
        {
            $('.text-error').css("display", "block");
            $('.contrainte'+field+'').addClass('error');
         
        }
        else {

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

        }
    }
    else if (type == "Number"){
     if ( (parseInt(formData[1].value)) < (parseInt(formData[0].value))  )
     {
        $('.contrainte'+field+'').addClass('error');
        console.log('error');
        $('.number-error').css("display", "block");
     }
     else {
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
        /*for (let i = 0; i < formData.length; i++) {
            console.log(parseInt(formData[i].name))
        }*/
        
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
    }
    else if (type=='Dropdown List' || type=='Multiple Checkbox'){
        var values = $("input[data-name='choice_"+field+"']")
        .map(function(){return $(this).val();}).get();
        var dropdownFields = [];

$('.option'+field).each(function () {
    dropdownFields.push( $(this).attr("dropdownfield") );
});     
        $.ajax({
          url: Routing.generate('createMultipleElement'),
          data: {
              elementField:field,
              value: values,
              dropdownField: dropdownFields
          },
          type: 'POST',  
      })
    
    }
        else 
        {  
            
            var test = $(this).attr('data-dismiss');
        if ( test == undefined) {
        $(this).attr('data-dismiss','modal');
        //second click
        $(this).trigger('click');
        }
      
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
        var test = $(this).attr('data-dismiss');
        if ( test == undefined) {
        $(this).attr('data-dismiss','modal');
        //second click
        $(this).trigger('click');
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

    // multiple datepicker
    $('.datepick').each(function(){
        $(this).datepicker();
    });

      // multiple close button modal
      $('.close').each(function(){
        $(this).css("display", "block");
    });

    // manage forms edit form

   
    $(document).on('click', '.edit', function (e) {
        

        var id = $(this).attr('id');

        var name = document.getElementById('name_' + id+'').value;
        var description = document.getElementById('description_' + id+'').value;
        console.log(description);
       //console.log($('formEdit_'+id).validate().checkForm());
       formEdit = 'formEdit_'+id;
       console.log($('#form_'+id).valid());
        if ($('#form_'+id).valid()){
        $.ajax({

            url: Routing.generate('updateForm'),
            data: {
                id: id,
                name: name,
                description: description
                
            },
            type: 'POST',
            success: function(data){
              //  setTimeout(function(){// wait 
                //    location.reload(); // then reload the page.(3)
             //  }); 
             }
        })
    }
    })

       // manage data

       $(document).on('click', '.remove_data', function (e) {

        var id = $(this).attr('id');
     
        $(this).closest('.form_' +id).hide('400', function () {
            $('.form_' +id).remove()
        })

        $.ajax({

            url: Routing.generate('deleteFormData'),
            data: {
                id: id,    
            },
            type: 'POST'
        })
    })  
})
