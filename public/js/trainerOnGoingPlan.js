var appointmentModal = $("#onGoingModal");
var Api = '/nut_sol/public';
let _location = window.location.href;
//"global" form handling
let formAdd = document.querySelector("#addOnlineAppointmentForm");

function fillForm(form, responseData) {
    form.reset();
    for (data in responseData) {

        if (data == 'trainingPlan') {
            let cardBody = document.createElement('div')
            cardBody.className = 'card-body';
            let Selecttable = document.createElement('table');
            Selecttable.className = 'display table table-hover table-striped'
            Selecttable.id="appointmentWholeTable2"
            let thead = document.createElement('thead')
            var tr = document.createElement('tr');
            tr.innerHTML = '<th>Ordem</th><th>Exercicio</th><th>intensidade</th> <th>Series (cm)</th><th>Repeticoes</th><th>Descanso (Min)</th>';
            thead.appendChild(tr);
            Selecttable.appendChild(thead);
            let responseDataJS = JSON.parse(responseData[data])
            for (get in responseDataJS) {
                var indexKey = Object.keys(responseDataJS[get]);
                var tr1 = document.createElement('tr');
                tr1.id='rows';
                for(let i=0;i<=5;i++){
                    let td = document.createElement('td');
                    let inputElement =  document.createElement('input')
                    inputElement.className = "border";
                    inputElement.name = indexKey[i]+'[]';
                    inputElement.id='modal_'+indexKey[i]+'_'+i
                    inputElement.value = responseDataJS[get][indexKey[i]]
                    inputElement.type = 'text'
                    td.appendChild(inputElement)
                    tr1.appendChild(td)
                }
                Selecttable.appendChild(tr1);
                cardBody.appendChild(Selecttable)
                formAdd.appendChild(cardBody);

            }


            let addMoreRow = document.createElement('span');
            addMoreRow.id = 'addbutton1';
            addMoreRow.innerText = "ADD ROW";
            addMoreRow.className = 'btn btn-primary btn-sm float-right';
            addMoreRow.setAttribute('onclick','add1()');
            cardBody.appendChild(addMoreRow);
            formAdd.insertBefore(cardBody,formAdd.childNodes[20]);

        }




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
                $("#modal_attachment").parent().append('Uploaded pdf ' + responseData[data]);
                $("#attachment").parent().append('Uploaded pdf ' + responseData[data]);
            }


        }
    }
}



let appointmentLoaded = false;


function loadPreviousAppointment(patientId) {
    document.getElementById('mainTrainingPlan').style.display = 'none';
    if (!appointmentLoaded) {
        $.ajax({
            url: Api + '/loadPreviousOnGoingPlan',
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

function editOnGoingPlan(id) {
    let checkID = document.getElementById('appointmentWholeTable2');
    if(checkID){
        checkID.style.display='none'
    }
    document.getElementById('mainTrainingPlan').style.display = 'none';
 //document.getElementById('addbutton').style.display = 'none';
    document.getElementById('submitButton').style.display = 'none';
    if (!clicked) { //this prevents request spam
        clicked = true;
        $.ajax({
            url: Api + '/ajaxhandlerongoing',
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
                    formEdit.name = "editOnGoingPlanForm"
                    formEdit.id = formEdit.name;
                    formEdit.action = Api+"/updateEditOnGoingPlan"
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
                    deleteBtn.setAttribute('onclick',"deleteOnGoingPlan()")


                    div.appendChild(SubmitBtn)
                    Deletediv.appendChild(deleteBtn)
                    parentDiv.appendChild( Deletediv)
                    parentDiv.appendChild(div)

                    formEdit.appendChild(appId)

                    for(data in responseJson) {
                        if (data == 'trainingPlan') {
                            let cardBody = document.createElement('div')
                            cardBody.className = 'card-body';
                            let Selecttable = document.createElement('table');
                            Selecttable.className = 'display table table-hover table-striped'
                            Selecttable.id="appointmentWholeTable"
                            let thead = document.createElement('thead')
                            var tr = document.createElement('tr');
                            tr.innerHTML = '<th>Ordem</th><th>Exercicio</th><th>intensidade</th> <th>Series (cm)</th><th>Repeticoes</th><th>Descanso (Min)</th>';
                            thead.appendChild(tr);
                            Selecttable.appendChild(thead);
                          let responseDataJS = JSON.parse(responseJson[data])
                            for (get in responseDataJS) {
                                var indexKey = Object.keys(responseDataJS[get]);
                                var tr1 = document.createElement('tr');
                                tr1.id = "rows";
                                for(let i=0;i<=5;i++){
                                    let td = document.createElement('td');
                                    let inputElement =  document.createElement('input')
                                    inputElement.className = "border";
                                    inputElement.name = indexKey[i]+'[]';
                                    inputElement.id='modal_'+indexKey[i]+'_'+i
                                    inputElement.value = responseDataJS[get][indexKey[i]]
                                    inputElement.type = 'text'
                                    td.appendChild(inputElement)
                                    tr1.appendChild(td)
                                }
                                Selecttable.appendChild(tr1);
                                cardBody.appendChild(Selecttable)
                                formEdit.appendChild(cardBody);
                            }

                            let addMoreRow = document.createElement('span');
                            addMoreRow.id = 'addbutton1';
                            addMoreRow.innerText = "ADD ROW";
                            addMoreRow.className = 'btn btn-primary btn-sm float-right';
                            addMoreRow.setAttribute('onclick','add1()');
                            cardBody.appendChild(addMoreRow);
                            formEdit.insertBefore(cardBody,formEdit.childNodes[20]);
                        }
                    }
                    formEdit.appendChild(parentDiv)
                    formBody.insertAdjacentElement("beforeend", formEdit);
                }
                //document.write(responseJson);

                fillForm2(responseJson)
                appointmentModal.modal("show");
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

                            if (responseDataJS[key] == "true") {
                                responseDataJS[key] = true;
                            }
                            if (responseDataJS[key] == "false") {
                                responseDataJS[key] = false;
                            }
                            switch (inputElement.type) {
                                case "checkbox":
                                    inputElement.checked = responseDataJS[key];
                                    break;
                                default:
                                    inputElement.value = responseDataJS[key]
                                    break;
                            }

                        }
                    }

            }catch (e) {
                let id = "modal_" + data
                let elm =  document.getElementById(id)
                if(data =="attachment"){
                    $("#modal_attachment").parent().append('Uploaded pdf ' + responseData[data]);
                    $("#attachment").parent().append('Uploaded pdf ' + responseData[data]);
                }else if(data !="attachment"){
                    if(data != 'created_at' && data !='updated_at'){
                        elm.value = responseData[data]
                    }

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
        $('#editOnGoingPlanForm').submit();
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



function deleteOnGoingPlan() {
    let Id = document.getElementById("appId").value;

    $.ajax({

        type: "GET",
        data: {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "id": Id,

        },

        url: Api + '/deleteOnGoingPlan',
        dataType: 'json',
        success: function (responseJson) {
            alert("Consulta Apagada");
            window.location.href = Api +'/ongoing-training-Plan';
        },

        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
    })
}



var z=0;
function add1(){
    z++;
    if(z<=20) {
        var newel = $("#rows:last").clone();
        // Add after last <div class='input-form'>
        $(newel).insertAfter("#rows:last");
    }
}
