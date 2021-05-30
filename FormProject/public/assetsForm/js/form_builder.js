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
                        if (data.constraint[i].contrainte != 'required') {

                            $('.modal-body-form' + field + '').append('<div class="col-md-12"><div class="form-group"><label class="control-label" >' + data.constraint[i].contrainte + '</label>' + '<input name="' + data.constraint[i].id + '" id="inputConstraint' + field + '' + i + '" elementType="' + id + '" data-field="' + field + '" type="' + data.constraint[i].html + '" class="form-control ' + data.constraint[i].html + ' id="" placeholder=""></div></div> ');
                        } else {
                            $('.modal-body-form' + field + '').append('<div class="col-md-12"><div class="checkbox checkbox-primary">' + '<input type="checkbox" name="' + data.constraint[i].id + '" id="inputConstraint' + field + '" value="1" ><label for="checkbox1">Required</label></div></div> ');

                        }
                    }
                    $('.modal-body-form' + field + '').append('<div class="modal-footer"><button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>' + '<button  elementType="' + id + '" type="button" class="btn btn-info waves-effect waves-light btnElmt" data-field="' + field + '" data-dismiss="modal">Save changes</button> ' + '</div>');
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
            url = url.substring(slugIndex + 1, url.length);
        }

        var field = generateField();

        $('.form_builder_area').append('<div id="sortable" data-field="' + field +
        
        '" data-block="" class="block_' + field +
        
        ' block "><div class="col-md-12"><div class="form-group "><div class="pull-right"><a class="table-action-btn h3"><i class="mdi mdi-close-box-outline text-danger remove_block "data-field="' + field +
        
        '" ></i></a></div></div><br><div class="jFiler-input-dragDrop block-area ui-sortable" style="width:100%;" ></div></div><div>');
        
        $('.block-area').sortable();

        updateOrder();
        var order = $('.block_' + field + '').attr('data-block');
        console.log(order);
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


    $('.form_builder_area').sortable({
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
// ce traitement se fait lorsque je change position d'un elmt lorque je lis le form sauvgardé dans la base de donné 
    $('.block-area').sortable({


        start: function (event, ui) {
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

            console.log(index);
            console.log(start_pos);
            
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
                    
                 console.log('testhihihi')
                },
               
                type: 'POST'
            })
        }
    });

    $('.form_builder_area').disableSelection();

    function getTextFieldHTML(field) {

        $('.block-area').sortable({


            start: function (event, ui) {
                var start_pos = ui.item.index();
                ui.item.data('start_pos', start_pos);
            },

            update: function (event, ui) {

                updateElementOrder();

            },

            receive: function (event, ui) {
                elementType = $(ui.item).attr("id");
                console.log(elementType);

            },

            stop: function (event, ui) {

                var index = ui.item.index() + 1;
                var section = ($(this)).attr('id');
                var start_pos = ui.item.data('start_pos') + 1;
                var sortedElement = ui.item.attr('data-field');

                console.log(index);
                console.log(start_pos);


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
                        console.log('testhohohoho')
                    },
                    type: 'POST'

                })

            }
        });

        var html = '';

        html += '<div class="form-group " data-field="' + field 
        
        + '"><div class="  pull-right"><td ><div><a  class="table-action-btn h3" ><i class="mdi mdi-settings-box text-success" id="openModalButton" data-toggle="modal" data-target="#con-close-modal_' + field +
        
        '" data-animation="fadein" data-plugin="custommodal" data-overlayspeed="200" data-overlaycolor="#36404a"></i></a>' +
        
        '<a  class="table-action-btn h3"><i class="mdi mdi-close-box-outline text-danger remove_bal_field" data-field="' + field +
        
        '"></i></a></div></td></div></div><div ><div class=" row" ><label id="label' + field +
        
        '" class="control-label labelform">Label</label></div><input id="placeholder' + field + '" type="text" name="' + name + '" placeholder="placeholder" class="form-control " ' +
        
        '/></div><br></div></div></div></div></div>' +
        
        '<div id="con-close-modal_' + field + '" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" ><div class="modal-dialog"><div class="modal-content"><div class="modal-header">' +
        
        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' + '<h4 class="modal-title">Parametrage du champ </h4></div><div class=" modal-body' + field + ' custom-modal-text text-left"><form id="form_' 
        
        + field + '" class="modal-body-form' + field + '">' + '<div class="col-md-12"><div class="form-group"><label id="' + field + '" for="name">Label</label>' + '<input type="text" class="form-control" id="inputLabel' + field + '" placeholder="Label"></div></div>' 
        
        + '<div class="col-md-12"><div class="form-group"><label id="' + field + '" for="name">Placeholder</label>' + '<input type="text" class="form-control" id="inputPlaceholder' + field + '" placeholder="Placeholder">' + '</div></div></div></form></div></div>'


        return $('<div>').addClass('li_' + field + ' form_builder_field elmt').attr('data-field', field).html(html)
    }


    $(document).on('click', '.remove_bal_field', function (e) {
        console.log(e)
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
        var nbConstraint = $(this).attr('nbConstraint')
        console.log(nbConstraint);

        var label = document.getElementById('inputLabel' + field).value
        document.getElementById('label' + field).innerHTML = label;

        var placeholder = document.getElementById('inputPlaceholder' + field).value;
        document.getElementById('placeholder' + field).placeholder = placeholder;

      
        var formData = $('#form_' + field + '').serializeArray();

        console.log(formData);

        $.ajax({
            url: Routing.generate('updateSettingsElement'),
            data: {
                elementField: field,
                label: label,
                placeholder: placeholder,
                elementType: elementType,
                value: formData
            },
            type: 'POST'
        })
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
            type: 'POST'

        })
    }
})
