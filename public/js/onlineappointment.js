var onlineAppointmentModal = $("#onlineAppointmentModal");
var Api = '/nut_sol/public';
let _location = window.location.href;
//"global" form handling
let formAdd = document.querySelector("#addOnlineAppointmentForm");

function fillForm(form, responseData) {
    form.reset();
    for (data in responseData) {
        try {
            //fill from json in response
            let jsonValues = JSON.parse(responseData[data])
            //console.log(jsonValues);
            var finaljsonValues = [];

            var loop = 0;
            //for (let key in finaljsonValues) {
            $.each( jsonValues, function( key, value ) {
               // console.log(value);
                //console.log(value);
                //var key =0;
                //$.each(form.elements, function( key, formele ) {
                loop = loop + 1;
                var formele = form.elements[key];

                //console.log(form.elements);
                //console.log(formele.type);



                if(jsonValues[key]=="true"){
                    jsonValues[key]=true
                }
                if(jsonValues[key]=="false"){
                    jsonValues[key]=false
                }

                switch (formele.type) {
                    case "checkbox":
                        jQuery(formele).prop("checked" , jsonValues[key]);
                        break;
                    default:
                        formele.value=jsonValues[key]
                        break;
                }
            });
        } catch (e) {
            // no json, fill as normal
           if(form.elements[data] && form.elements[data].type != 'file') {
               form.elements[data].value = responseData[data];
           }
           else if(form.elements[data] && form.elements[data].type == 'file') {
               $("#modal_attachments").parent().append('Uploaded pdf ' + responseData[data]);
               $("#attachments").parent().append('Uploaded pdf ' + responseData[data]);
           }


        }
    }
}



let appointmentLoaded = false;


function loadPreviousAppointment(patientId) {
    if (!appointmentLoaded) {
        $.ajax({
            url: Api + '/loadPreviousOnlineAppointment',
            type: "get",
            data: {
                id: patientId,
            },
            dataType: 'json',
            success: function (responseJson) {
                fillForm(formAdd, responseJson)
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //alert(xhr.responseText.errors);
            }
        });

        $.ajaxSetup({headers: {'csrftoken': '{{ csrf_token() }}'}});

    }
}









var clicked = false;

function editOnlineAppointment(id) {

    if (!clicked) { //this prevents request spam
        clicked = true;
        $.ajax({
            url: Api + '/showSingleOnlineAppointment',
            type: "get",
            data: {
                id: id,
            },
            dataType: 'json',
            cache:false,
            async:false,
            success: function (responseJson) {

                let formBody = document.querySelector("#modalFormBody");
                formBody.innerHTML="";

                //clone formAdd if it we don't have something in modalFormBody
                if (formBody.childElementCount === 0) {
                    window.formEdit = formAdd.cloneNode(true)
                    formEdit.name = "editOnlineAppointmentForm"
                    formEdit.id = formEdit.name;
                    formEdit.action = Api+"/updateOnlineAppointment"
                    //formEdit.removeAttribute("action")

                    //prefix ids so they don't clash
                    let prefix = "modal_";
                    for (element of formEdit.elements) {
                        if (element.id.length) {
                            element.id = prefix + element.id;

                        }
                    }

                    //create hidden id field to validate server side
                    let appId = document.createElement("input")
                    appId.type = "hidden"
                    appId.name = "appId"
                    appId.id = appId.name
                    appId.value = id;

                    let parentDiv = document.createElement("div")
                    parentDiv.className = "form-group row text-right"

                    let Deletediv = document.createElement("div")
                    Deletediv.className = "col col-sm-2 col-lg-3"

                    let div = document.createElement("div")
                    div.className = "col col-sm-10 col-lg-8 offset-sm-1 offset-lg-0"

                    let SubmitBtn =  document.createElement("button")
                    SubmitBtn.type = "button"
                    SubmitBtn.className = "btn btn-space btn-success"
                    SubmitBtn.setAttribute('onclick',"submitForm()")
                    SubmitBtn.innerHTML="Actualizar"

                    let deleteBtn =  document.createElement("button")
                    deleteBtn.className = "btn btn-space btn-danger"
                    deleteBtn.innerHTML="Eliminar"
                    deleteBtn.setAttribute('onclick',"deleteOnlineAppointment()")


                    div.appendChild(SubmitBtn)
                    Deletediv.appendChild(deleteBtn)
                    parentDiv.appendChild( Deletediv)
                    parentDiv.appendChild(div)

                    formEdit.appendChild(appId)
                    formEdit.appendChild(parentDiv)
                    formBody.insertAdjacentElement("beforeend", formEdit);
                }
                //document.write(responseJson);

                fillForm2(responseJson)
                onlineAppointmentModal.modal("show");
                clicked = false;
            },
            error: function (xhr, ajaxOptions, thrownError) {

                alert(xhr.responseText);
            }
        });

        $.ajaxSetup({headers: {'csrftoken': '{{ csrf_token() }}'}});



    }
}

function  fillForm2(responseData) {

    for(data in responseData) {

        try {
            let responseDataJS = JSON.parse(responseData[data])
            for (let key in responseDataJS) {
                let id = "modal_" + key
                let inputElement = document.getElementById(id)
                if (typeof inputElement !== undefined && inputElement != null) {

                    if (responseDataJS[key]=="true"){
                        responseDataJS[key] = true;
                    }
                    if(responseDataJS[key]=="false"){
                        responseDataJS[key]=false;
                    }
                    switch (inputElement.type) {
                        case "checkbox":
                            inputElement.checked = responseDataJS[key];
                            break;
                        default:
                            inputElement.value=responseDataJS[key]
                            break;
                    }

                }
            }
        }catch (e) {
            let id = "modal_" + data
            let elm =  document.getElementById(id)
            if(data !="attachments"){
                elm.value = responseData[data];
            }else if(data =="attachments") {
                $("#modal_attachments").parent().append('Uploaded pdf ' + responseData[data]);
                $("#attachments").parent().append('Uploaded pdf ' + responseData[data]);
            }

        }

    }
}

function submitForm() {


        var a,b,c,d,e,f,g,h;
        a =  $( "#modal_body_height" ).val();
        b =  $( "#modal_body_weight" ).val();
        c =  $( "#modal_body_bmi" ).val();
        d =  $( "#modal_body_fat_mass" ).val();
        e =  $( "#modal_body_chest" ).val();
        f =  $( "#modal_body_waist" ).val();
        g =  $( "#modal_body_waist_hip" ).val();
        h =  $( "#modal_body_hip" ).val();

    if($.isNumeric(a) && $.isNumeric(b) && $.isNumeric(c) && $.isNumeric(d) && $.isNumeric(e)&& $.isNumeric(f)&& $.isNumeric(g)&& $.isNumeric(h)){
        $('#editOnlineAppointmentForm').submit();
    }else{
        if (!$.isNumeric(a)){
            $("#modal_body_height").parent().append("<p class='text-danger'>Not valid</p>");
        }
        if (!$.isNumeric(b)){
            $("#modal_body_weight").parent().append("<p class='text-danger'>Not valid</p>");
        }
        if (!$.isNumeric(c)){
            $("#modal_body_bmi").parent().append("<p class='text-danger'>Not valid</p>");
        }
        if (!$.isNumeric(d)){
            $("#modal_body_fat_mass").parent().append("<p class='text-danger'>Not valid</p>");
        }
        if (!$.isNumeric(e)){
            $("#modal_body_chest").parent().append("<p class='text-danger'>Not valid</p>");
        }
        if (!$.isNumeric(f)){
            $("#modal_body_waist").parent().append("<p class='text-danger'>Not valid</p>");
        }
        if (!$.isNumeric(g)){
            $("#modal_body_waist_hip").parent().append("<p class='text-danger'>Not valid</p>");
        }
        if (!$.isNumeric(h)){
            $("#modal_body_hip").parent().append("<p class='text-danger'>Not valid</p>");
        }

    }



}




function updateOnlineAppointment() {
    let appId = document.getElementById("appId").value;
    let appDate = document.getElementById("modal_appDate").value;
    let tone = document.getElementById("modal_tone").value;
    let l_health = document.getElementById("modal_lifestyle_health").value;
    let muscle_mass = document.getElementById("modal_muscle_mass").value;
    let weight_target = document.getElementById("modal_weight_target").value;
    let weight_target_kg = document.getElementById("modal_weight_target_kg").value;
    let weight_max_date = document.getElementById("modal_weight_max_date").value;
    let weight_max= document.getElementById("modal_weight_max").value;
    let weight_min = document.getElementById("modal_weight_min").value;
    let weight_min_date = document.getElementById("modal_weight_min_date").value;
    let weight_desired = document.getElementById("modal_weight_desired").value;
    let weight_desired_date = document.getElementById("modal_weight_desired_date").value;
    let weight_history = document.getElementById("modal_weight_history").value;
    let excercise_freq = document.getElementById("modal_excercise_freq").value;
    let exercise_time = document.getElementById("modal_exercise_time").value;
    let glicemia_detail = document.getElementById("modal_glicemia_detail").value;
    let colesterol_detail = document.getElementById("modal_colesterol_detail").value;
    let trigli_detail = document.getElementById("modal_trigli_detail").value;
    let uric_acid_detail = document.getElementById("modal_uric_acid_detail").value;
    let f_intestinal_detail = document.getElementById("modal_f_intestinal_detail").value;
    let f_alergies_detail = document.getElementById("modal_f_alergies_detail").value;
    let history_personal_detail = document.getElementById("modal_history_personal_detail").value;
    let history_family_detail = document.getElementById("modal_history_family_detail").value;
    let medication_detail = document.getElementById("modal_medication_detail").value;
    let body_height= document.getElementById("modal_body_height").value;
    let body_weight= document.getElementById("modal_body_weight").value;
    let body_bmi= document.getElementById("modal_body_bmi").value;
    let body_fat_mass= document.getElementById("modal_body_fat_mass").value;
    let body_chest= document.getElementById("modal_body_chest").value;
    let body_waist= document.getElementById("modal_body_waist").value;
    let body_waist_hip= document.getElementById("modal_body_waist_hip").value;
    let body_hip= document.getElementById("modal_body_hip").value;
    let observations= document.getElementById("modal_observations").value;
    let attachments= document.getElementById("modal_attachments").value;
    let Wakeup_time                 =   document.getElementById('modal_Wakeup_time').value;
    let bed_time                    =   document.getElementById('modal_bed_time').value;
    let breakfast_time              =   document.getElementById('modal_breakfast_time').value;
    let breakfast_Description      =   document.getElementById('modal_breakfast_Description').value;
    let breakfast_place             =   document.getElementById('modal_breakfast_place').value;
    let MorningS1_time              =   document.getElementById('modal_MorningS1_time').value;
    let MorningS1_Description      =   document.getElementById('modal_MorningS1_Description').value;
    let MorningS1_place             =   document.getElementById('modal_MorningS1_place').value;
    let MorningS2_time              =   document.getElementById('modal_MorningS2_time').value;
    let MorningS2_Description      =   document.getElementById('modal_MorningS2_Description').value;
    let MorningS2_place             =   document.getElementById('modal_MorningS2_place').value;
    let lunch_time                  =   document.getElementById('modal_lunch_time').value;
    let lunch_Description         =   document.getElementById('modal_lunch_Description').value;
    let lunch_place                 =   document.getElementById('modal_lunch_place').value;
    let AfternoonS1_time            =   document.getElementById('modal_AfternoonS1_time').value;
    let AfternoonS1_Description    =   document.getElementById('modal_AfternoonS1_Description').value;
    let AfternoonS1_place           =   document.getElementById('modal_AfternoonS1_place').value;
    let AfternoonS2_time            =   document.getElementById('modal_AfternoonS2_time').value;
    let AfternoonS2_Description    =   document.getElementById('modal_AfternoonS2_Description').value;
    let AfternoonS2_place           =   document.getElementById('modal_AfternoonS2_place').value;
    let dinner_time                 =   document.getElementById('modal_dinner_time').value;
    let dinner_Description         =   document.getElementById('modal_dinner_Description').value;
    let dinner_place                =   document.getElementById('modal_dinner_place').value;
    let supper_time                 =   document.getElementById('modal_supper_time').value;
    let supper_Description         =   document.getElementById('modal_supper_Description').value;
    let supper_place                =   document.getElementById('modal_supper_place').value;
    let weekend_Description        =   document.getElementById('modal_weekend_Description').value;


    $.ajax({

        type: "POST",

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        data: {
           //"_token": $('meta[name="csrf-token"]').attr('content'),
            "id" : appId,
            "appDate" : appDate,
            "tone" : tone,
            "lifestyle_health" : l_health,
            "muscle_mass" : muscle_mass,
            "weight_target" : weight_target,
            "weight_target_kg" : weight_target_kg,
            "weight_max_date " : weight_max_date ,
            "weight_min" : weight_min,
            "weight_max":weight_max,
            "weight_min_date" : weight_min_date,
            "weight_desired" : weight_desired,
            "weight_desired_date " : weight_desired_date ,
            "weight_history" : weight_history,
            "exercise_freq " : excercise_freq ,
            "exercise_time" : exercise_time,
            "glicemia_detail" : glicemia_detail,
            "colesterol_detail" : colesterol_detail,
            "trigli_detail" : trigli_detail,
            "uric_acid_detail" : uric_acid_detail,
            "f_intestinal_detail" : f_intestinal_detail,
            "f_alergies_detail" : f_alergies_detail,
            "history_personal_detail" : history_personal_detail,
            "history_family_detail" : history_family_detail,
            "medication_detail" : medication_detail,
            "body_height" : body_height,
            "body_weight" : body_weight,
            "body_bmi" : body_bmi,
            "body_fat_mass" : body_fat_mass,
            "body_chest" : body_chest,
            "body_waist" : body_waist,
            "body_waist_hip" : body_waist_hip,
            "body_hip" : body_hip,
            "observations" : observations,
            "attachments":attachments,
            "Wakeup_time":Wakeup_time,
            "bed_time":bed_time ,
            "breakfast_time":breakfast_time ,
            "breakfast_Description":breakfast_Description ,
            "breakfast_place":breakfast_place  ,
            "MorningS1_time":MorningS1_time ,
            "MorningS1_Description":MorningS1_Description,
            "MorningS1_place":MorningS1_place ,
            "MorningS2_time":MorningS2_time,
            "MorningS2_Description":MorningS2_Description,
            "MorningS2_place":MorningS2_place ,
            "lunch_time":lunch_time  ,
            "lunch_Description":lunch_Description  ,
            "lunch_place":lunch_place  ,
            "AfternoonS1_time":AfternoonS1_time   ,
            "AfternoonS1_Description":AfternoonS1_Description ,
            "AfternoonS1_place":AfternoonS1_place   ,
            "AfternoonS2_time":AfternoonS2_time  ,
            "AfternoonS2_Description":AfternoonS2_Description  ,
            "AfternoonS2_place":AfternoonS2_place  ,
            "dinner_time":dinner_time     ,
            "dinner_Description":dinner_Description  ,
            "dinner_place":dinner_place ,
            "supper_time":supper_time ,
            "supper_Description":supper_Description ,
            "supper_place":supper_place ,
            "weekend_Description":weekend_Description  ,
        },

        url: Api + '/updateOnlineAppointment',
        dataType: 'json',
        cache: false,
        processData: false,
        success: function (responseJson) {
            alert("Consulta Actualizada");
            window.location.href = Api +'/online-appointments';
        },

        error: function (xhr, ajaxOptions, thrownError) {

            var data=xhr.responseText;
            var jsonResponse = JSON.parse(data);

            for (let key in jsonResponse['errors']) {
               alert(jsonResponse['errors'][key]);
            }

        }
    });



}


function deleteOnlineAppointment() {
    let Id = document.getElementById("appId").value;

    $.ajax({

        type: "GET",
        data: {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "id": Id,

        },

        url: Api + '/deleteOnlineAppointment',
        dataType: 'json',
        success: function (responseJson) {
            alert("Consulta Apagada");
            window.location.href = Api +'/online-appointments';
        },

        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
    })
}
