var appointmentModal = $("#initialModal");
var Api = '/nut_sol/public';

var clicked=false;
function editInitialPlan(id) {
    if (!clicked) { //this prevents request spam
        clicked = true;
        $.ajax({
            url: Api + '/ajaxhandler',
            type: "get",
            data: {
                id: id,
            },
            dataType: 'json',

            success: function (responseJson) {

                var obj = responseJson[0] || responseJson;
                var planData = JSON.parse(obj.planData);
                var observations =  JSON.parse(obj.observations);
                document.querySelector("#objective").value = planData.objective;
                document.querySelector("#exercise").value = planData.exercise;
                document.querySelector("#intensity").value = planData.intensity;
                document.querySelector("#volume").value = planData.volume;
                document.querySelector("#series").value = planData.series;
                document.querySelector("#repetitions").value = planData.repetitions;
                document.querySelector("#rest_time").value = planData.rest_time;
                document.querySelector("#dntropemtric_data").value = observations.dntropemtric_data;
                document.querySelector("#goal").value = observations.goal;
                document.querySelector("#time_frame").value = observations.time_frame;
                document.querySelector("#motivation").value = observations.motivation;
                document.querySelector("#id").value = obj.id;
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



function deleteInitialPlan() {
    let a_Id = document.getElementById("id").value;

    $.ajax({

        type: "GET",
        data: {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "id": a_Id,

        },

        url: Api + '/deleteplan',
        dataType: 'json',
        success: function (responseJson) {
            alert("Consulta Apagada");
            window.location.href = Api +'/initial-training-Plan';
        },

        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
    })


}
