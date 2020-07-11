var appointmentModal = $("#appointmentModal");
var Api = '/nut_sol_final/public';

var clicked=false;
function editAppointment(id) {
    if (!clicked) { //this prevents request spam
        clicked = true;
        $.ajax({
            url: Api + '/ajaxappointments',
            type: "get",
            data: {
                id: id,
            },
            dataType: 'json',

            success: function (responseJson) {

                var obj = responseJson[0] || responseJson;
                var appDate = new Date(obj.date);
                appDate= `${appDate.getUTCFullYear()}-${("0"+(appDate.getUTCMonth()+1)).slice(-2)}-${("0"+(appDate.getUTCDate()+1)).slice(-2)}`
                document.querySelector("#sAppId1").value = obj.id;
                document.querySelector("#sAppId").value = obj.id;
                document.querySelector("#sAppDate").value = appDate;
                document.querySelector("#sAppWeight").value = obj.weight;
                document.querySelector("#sAppmm").value = obj.muscleMass;
                document.querySelector("#sAppfm").value = obj.fatMass;
                document.querySelector("#sApptw").value = obj.totalWater;
                document.querySelector("#sApphw").value = obj.hipWaistRatio;
                document.querySelector("#sAppvf").value = obj.visceralFat;

                var attachments = obj.attachments;
                var attachmentsParse = JSON.parse(attachments);
                $("#pdfexist1").html(attachmentsParse.attach1);
                $("#pdfexist2").html(attachmentsParse.attach2);
                document.querySelector("#mattach").value = '';
                document.querySelector("#mattach2").value = '';
                appointmentModal.modal('show');
                clicked = false;
            },

            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.responseText);
            }
        });

        $.ajaxSetup({headers: {'csrftoken': '{{ csrf_token() }}'}});

    }
}


function submitbtn() {
    var a,b,c,d,e,f,g,h;
   a= document.querySelector("#sAppId").value
   b= document.querySelector("#sAppDate").value
   c= document.querySelector("#sAppWeight").value
    d=document.querySelector("#sAppmm").value
    e=document.querySelector("#sAppfm").value
   f= document.querySelector("#sApptw").value
    g=document.querySelector("#sApphw").value
   h= document.querySelector("#sAppvf").value

    if(a !='' && b!='' && c!=''  && d!=''  && e!=''  && f!=''  && g!=''  && h!='' ) {

        $('#update-ajex').submit();
    }else{
        if(a==""){
            $("#sAppId").parent().append("<p class='text-danger'> This field is required</p>");
        }
        if(b==""){
            $("#sAppDate").parent().append("<p class='text-danger'> This field is required</p>");
        }
        if(c==""){
            $("#sAppWeight").parent().append("<p class='text-danger'> This field is required</p>");
        }
        if(d==""){
            $("#sAppmm").parent().append("<p class='text-danger'> This field is required</p>");
        }
        if(e==""){
            $("#sAppfm").parent().append("<p class='text-danger'> This field is required</p>");
        }
        if(f==""){
            $("#sApptw").parent().append("<p class='text-danger'> This field is required</p>");
        }
        if(g==""){
            $("#sApphw").parent().append("<p class='text-danger'> This field is required</p>");
        }
        if(h==""){
            $("#sAppvf").parent().append("<p class='text-danger'> This field is required</p>");
        }

    }
}





//
// function deleteAppointment1() {
//
//     let a_Id = document.getElementById("sAppId1").value;
//
//         $.ajax({
//             url: Api + '/deleteappointment',
//             type: "get",
//             data: {
//                 a_Id: a_Id,
//             },
//             dataType: 'json',
//             success: function (responseJson) {
//               alert("Consulta Apagada");
//
//                 // var url="/onesource_admin/viewDetails.php?purchaseIdd="+idd;//+"&page="+pageId+"&rec_per_page="+recPerPageId;
//                window.location.href = "appointment.php";
//             },
//             error: function (xhr, ajaxOptions, thrownError) {
//               alert(xhr.responseText);
//
//             }
//         });
//
//         $.ajaxSetup({headers: {'csrftoken': '{{ csrf_token() }}'}});
//
// }

// function updateAppointment() {
//
//     let a_Id = document.getElementById("sAppId1").value;
//     let sAppDate = document.getElementById("sAppDate").value;
//     let sAppWeight = document.getElementById("sAppWeight").value;
//     let sAppmm = document.getElementById("sAppmm").value;
//     let sAppfm = document.getElementById("sAppfm").value;
//     let sApptw = document.getElementById("sApptw").value;
//     let sAppvf = document.getElementById("sAppvf").value;
//     let sApphw = document.getElementById("sApphw").value;
//
//
//     $.ajax({
//         type: "POST",
//         url: Api + '/updateappointment',
//         data: {
//
//             a_Id: a_Id,
//             sAppDate: sAppDate,
//             sAppWeight: sAppWeight,
//             sAppmm: sAppmm,
//             sAppfm: sAppfm,
//             sApptw: sApptw,
//             sAppvf: sAppvf,
//             sApphw: sApphw
//         },
//         dataType: 'json',
//         success: function (responseJson) {
//             alert("Consulta Actualizada");
//             window.location.href = "appointment.php";
//
//         },
//         error: function (xhr, ajaxOptions, thrownError) {
//             console.log(error);
//             alert("Consulta não actualizada");
//
//         }
//     });
//    $.ajaxSetup({headers: {'csrftoken': '{{ csrf_token() }}'}});
// }

//
function updateAppointment() {
    let a_Id = document.getElementById("sAppId1").value;
    let sAppDate = document.getElementById("sAppDate").value;
    let sAppWeight = document.getElementById("sAppWeight").value;
    let sAppmm = document.getElementById("sAppmm").value;
    let sAppfm = document.getElementById("sAppfm").value;
    let sApptw = document.getElementById("sApptw").value;
    let sAppvf = document.getElementById("sAppvf").value;
    let sApphw = document.getElementById("sApphw").value;
    let mattach = document.getElementById("mattach").value;


    $.ajax({
        type: "POST",
        data: {
          "_token": $('meta[name="csrf-token"]').attr('content'),
            "id": a_Id,
            'sAppDate': sAppDate,
            'sAppWeight': sAppWeight,
            'sAppmm': sAppmm,
            'sAppfm': sAppfm,
            'sApptw': sApptw,
            'sAppvf': sAppvf,
            'sApphw': sApphw,
            'mattach':mattach
        },

        url: Api + '/updateappointment',
        dataType: 'json',
        success: function (responseJson) {
            alert("Consulta Actualizada");
            window.location.href = Api +'/appointments';
        },

        error: function (xhr, ajaxOptions, thrownError) {
            var data=xhr.responseText;
            var jsonResponse = JSON.parse(data);
            alert(data);
        }
    })


    }



function deleteAppointment() {
    let a_Id = document.getElementById("sAppId1").value;

    $.ajax({

        type: "GET",
        data: {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "id": a_Id,

        },

        url: Api + '/deleteappointment',
        dataType: 'json',
        success: function (responseJson) {
            alert("Consulta Apagada");
            window.location.href = Api +'/appointments';
        },

        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
    })


}
