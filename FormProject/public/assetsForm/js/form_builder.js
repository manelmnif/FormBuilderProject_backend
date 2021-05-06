$(document).ready(function () {


    $('.form_bal_textfield').draggable({
        helper: function () {
            var id = ($(this)).attr('id');
            var field = generateField();
            console.log(field);
            // var field = $('.element').attr('data-field');
            // console.log(field);
            $.ajax({
                url: urlAjaxValidation,
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

            return getTextFieldHTML(field)
        },
        connectToSortable: '.form_builder_area'
    })

    $('.form_builder_area').sortable({
        cursor: 'move',

        stop: function (ev, ui) {}
    })


    $('.form_builder_area').disableSelection()

    $('.btn-block').on({
        helper: function () {
            return getBlock();
        },
        connectToSortable: '.block_area'
    })


    $('.block_area').sortable({
        cursor: 'move',

        stop: function (ev, ui) {}
    })

    $('.block_area').disableSelection()


    function getBlock() {
        html = '<div class="jFiler-input-dragDrop "><div class="jFiler-input-text ui-sortable-handle"><h3>Drag &amp; Drop Elements here</h3><span style="display:inline-block; margin: 15px 0"></span></div></div>'

        return $('<div>').html(html)
    }

    function getTextFieldHTML(field) {


        console.log(field)

        var name = $(this).find('.form_input_name').val()

        var html = '';

        html += '<div class="form-group " data-field="' + field 
        + '"><div class="  pull-right"<td ><div><a aria-expanded="false" data-toggle="collapse" class="table-action-btn h3" data-target="#bg-default' + field
        + '"><i class="mdi mdi-chevron-down"></i></a><a  class="table-action-btn h3"><i class="mdi mdi-close-box-outline text-danger remove_bal_field" data-field="' + field 
        + '"></i></a></div></td></div></div><div ><div class=" row" ><label id="demo' + field 
        + '" class="control-label labelform">Label</label></div><input type="text" name="' + name 
        + '" placeholder="' + '" class="form-control " ' + '/></div><br><div class="clearfix element"></div></div><div id="bg-default' + field 
        + '" class="collapse" aria-expanded="false" "><div class="portlet-body "><div class="constraint' + field 
        + '"<div class="form-group "><label class="col-sm-3 control-label" for="example-input-small">Label</label><div class="col-sm-6"><input type="text" id="input' + field 
        + '" name="example-input-small" class="form-control input-sm" placeholder="Label" data-field="' + field 
        + '" ></div><br><br><br></div></div></div></div></div>'


        return $('<div>').addClass('li_' + field + ' form_builder_field').html(html)
    }

    $(document).on('click', '.btn-info', function (e) {
        console.log('test')
        e.preventDefault()
        var label = $('.formlabel').val()
        console.log(label)
    })

    $(document).on('click', '.remove_bal_field', function (e) {
        console.log(e)
        e.preventDefault()
        var field = $(this).attr('data-field')
        // console.log(field);
        $(this).closest('.li_' + field).hide('400', function () {
            $('.li_' + field).remove()
        })
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
})
