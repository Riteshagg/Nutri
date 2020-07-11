




var patientModal = $("#patientModal");

var clicked = false;

function editPatient(id) {

    if (!clicked) { //this prevents request spam
        clicked = true;
        $.ajax({
            url: '/nut_sol/public/ajaxpatients',
            type: "get",
            data: {
                id: id,
            },
            dataType: 'json',

            success: function (responseJson) {
                var obj = responseJson[0] || responseJson;
                if (obj.id == null) {
                    obj.id = 0;
                }

                document.querySelector("#mpatientIdhidden").value = obj.id;
                document.querySelector("#mpatientId").value = obj.id;
                let elmID =  document.querySelector("#patientNutritionistDropdown")
                if(elmID){
                    elmID.value = obj.nutritionistId;
                }

                document.querySelector("#mpName").value = obj.name;
                document.querySelector("#mpPhone").value = obj.phone;
                document.querySelector("#mpDate").value = obj.dob;
                document.querySelector("#mpEmail").value = obj.email;
                document.querySelector("#pActive").value = obj.status;
                document.querySelector("#mpdelete").href = "patients-delete/"+obj.id;
                if (document.querySelector("#nut_pat_modal"))
                    document.querySelector("#nut_pat_modal").value = obj.id

                patientModal.modal('show');
                clicked = false;
            },

            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.responseText);
            }
        });

        $.ajaxSetup({headers: {'csrftoken': '{{ csrf_token() }}'}});
    }
}
