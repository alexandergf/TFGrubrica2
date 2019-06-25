function look(){
    var select=$('#select_list').val();
    if(select != "pregunta"){
        $("#submit-btn").removeAttr("disabled");
    }else{
        $("#submit-btn").prop("disabled", "disabled");
    }
};