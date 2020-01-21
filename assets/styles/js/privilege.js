function get_privilege(url, form) {

    $.getJSON(url + "gl_privilege/get_forms_privilege_name/" + form,
        function(data, textStatus, jqXHR) {
            // console.log(data);
            if (data) {

                if (data['ad'] == 0) {
                    if (document.getElementById("btn_new")) {
                        document.getElementById("btn_new").style.visibility = "hidden";
                    }
                    if (document.getElementById("btn_add")) {
                        document.getElementById("btn_add").style.visibility = "hidden";
                    }
                }
                if (data['updte'] == 0) {

                    if (document.getElementById("btn_edit")) {
                        document.getElementById("btn_edit").style.visibility = "hidden";
                    }
                }
                if (data['delte'] == 0) {

                    if (document.getElementById("btn_delete")) {
                        document.getElementById("btn_delete").style.visibility = "hidden";
                    }
                    if (document.getElementById("delte")) {
                        document.getElementById("delte").style.visibility = "hidden";
                    }
                    if (document.getElementById("delte1")) {
                        document.getElementById("delte1").style.visibility = "hidden";
                    }
                }
                if (data['view'] == 0) {
                    if (document.getElementById("j_data")) {
                        document.getElementById("j_data").style.visibility = "hidden";
                    }
                    if (document.getElementById("show")) {
                        document.getElementById("show").style.visibility = "hidden";
                    }
                    if (document.getElementById("print_j")) {
                        document.getElementById("print_j").style.visibility = "hidden";
                    }
                    if (document.getElementById("excel")) {
                        document.getElementById("excel").style.visibility = "hidden";
                    }
                    if (document.getElementById("word")) {
                        document.getElementById("word").style.visibility = "hidden";
                    }

                }
                if (data['include'] == 0) {
                    if (form != 'blanck')
                        window.location.href = url + "blanck";
                }
            } else {
                if (form != 'blanck')
                    window.location.href = url + "blanck";

            }
        }
    );
}