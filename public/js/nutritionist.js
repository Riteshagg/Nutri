




var nutritionistModal = $("#nutritionistModal");

var clicked = false;

function editNutritionist(id) {

    if (!clicked) { //this prevents request spam
        clicked = true;
        $.ajax({
            url: '/nut_sol_final/public/ajaxnutritionist',
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
                document.querySelector("#mnutriId").value = obj.id;
                document.querySelector("#mpName").value = obj.name;
                document.querySelector("#mpPhone").value = obj.phone;
                document.querySelector("#mpDate").value = obj.dob;
                document.querySelector("#mpEmail").value = obj.email;
                document.querySelector("#mpdelete").href = "nutritionist-delete/"+obj.id;
                if (document.querySelector("#nut_pat_modal"))
                    document.querySelector("#nut_pat_modal").value = obj.id

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
