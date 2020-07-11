




var nutritionistModal = $("#trainerModal");

var clicked = false;

function editPersonalTrainer(id) {

    if (!clicked) { //this prevents request spam
        clicked = true;
        $.ajax({
            url: '/nut_sol/public/ajaxPersonalTrainer',
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
                document.querySelector("#mnutriIdhidden").value = obj.id;
                document.querySelector("#mtrainerId").value = obj.id;
                document.querySelector("#mpName").value = obj.name;
                document.querySelector("#mpPhone").value = obj.phone;
                document.querySelector("#mpDate").value = obj.dob;
                document.querySelector("#mpEmail").value = obj.email;
                document.querySelector("#mpdelete").href = "trainer-delete/"+obj.id;


                nutritionistModal.modal('show');
                clicked = false;
            },

            error: function (xhr, ajaxOptions, thrownError) {

                alert(xhr.responseText);
            }
        });

        $.ajaxSetup({headers: {'csrftoken': '{{ csrf_token() }}'}});
    }
}
