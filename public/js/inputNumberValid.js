
    $('#modal_body_height,#modal_body_weight,#modal_body_bmi,#modal_body_fat_mass,#modal_body_chest,#modal_body_waist,#modal_body_waist_hip,#modal_body_hip').keypress(function (eve) {
        if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this).caret().start == 0)) {
            eve.preventDefault();
        }

        $('#modal_body_height,#modal_body_weight,#modal_body_bmi,#modal_body_fat_mass,#modal_body_chest,#modal_body_waist,#modal_body_waist_hip,#modal_body_hip').keyup(function (eve) {
            if ($(this).val().indexOf('.') == 0) {
                $(this).val($(this).val().substring(1));
            }
        });
    });




$('#body_height,#body_weight,#body_bmi,#body_fat_mass,#body_chest,#body_waist,#body_waist_hip,#body_hip').keypress(function(eve) {
    if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this).caret().start == 0) ) {
        eve.preventDefault();
    }

    $('#body_height,#body_weight,#body_bmi,#body_fat_mass,#body_chest,#body_waist,#body_waist_hip,#body_hip').keyup(function(eve) {
        if($(this).val().indexOf('.') == 0) {    $(this).val($(this).val().substring(1));
        }
    });
});



