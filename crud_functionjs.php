<script>
function deleteRecord(table, idValue, divId){   
    swal({
        title: "Confirm Delete",
        text: "This record will be permanently removed.",
        icon: "warning",
        buttons: {
            cancel: {
                text: "Cancel",
                visible: true,
                className: "btn btn-secondary"
            },
            confirm: {
                text: "Delete",
                visible: true,
                className: "btn btn-danger"
            }
        },
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            swal({
                title: "Deleting...",
                text: "Please wait while we remove the record.",
                icon: "info",
                buttons: false,
                closeOnClickOutside: false,
                closeOnEsc: false
            });

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function(){
                if (xhr.readyState === 4){
                    if(xhr.status === 200 && xhr.responseText.trim() === "Success"){
                        swal("Deleted!", "The record has been removed.", "success")
                        .then(() => {
                            if(divId){
                                document.getElementById(divId).style.transition = "opacity 0.5s";
                                document.getElementById(divId).style.opacity = 0;
                                setTimeout(function(){
                                    document.getElementById(divId).remove();
                                }, 500);
                            }
                        });
                    } else {
                        swal("Error", xhr.responseText, "error");
                    }
                }
            };
            xhr.open("POST","crud_functions.php",true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("table="+encodeURIComponent(table)+"&id="+encodeURIComponent(idValue));
        }
    });
}

function ajaxCreateRecord(table, data, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            var response = null;
            try { response = JSON.parse(xhr.responseText); } catch(e) {}
            if (callback) callback(xhr.status === 200, response);
        }
    };
    xhr.open("POST", "crud_functions.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    var params = "action=create&table=" + encodeURIComponent(table);
    for (var key in data) {
        if (data.hasOwnProperty(key)) {
            params += "&data[" + encodeURIComponent(key) + "]=" + encodeURIComponent(data[key]);
        }
    }
    xhr.send(params);
}

function ajaxReadRecord(table, id, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            var response = null;
            try { response = JSON.parse(xhr.responseText); } catch(e) {}
            if (callback) callback(xhr.status === 200, response);
        }
    };
    xhr.open("GET", "crud_functions.php?action=read&table=" + encodeURIComponent(table) + "&id=" + encodeURIComponent(id), true);
    xhr.send();
}

function ajaxUpdateRecord(table, id, data, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            var response = null;
            try { response = JSON.parse(xhr.responseText); } catch(e) {}
            if (callback) callback(xhr.status === 200, response);
        }
    };
    xhr.open("POST", "crud_functions.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    var params = "action=update&table=" + encodeURIComponent(table) + "&id=" + encodeURIComponent(id);
    for (var key in data) {
        if (data.hasOwnProperty(key)) {
            params += "&data[" + encodeURIComponent(key) + "]=" + encodeURIComponent(data[key]);
        }
    }
    xhr.send(params);
}

function ajaxDeleteRecord(table, id, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            var response = null;
            try { response = JSON.parse(xhr.responseText); } catch(e) {}
            if (callback) callback(xhr.status === 200, response);
        }
    };
    xhr.open("POST", "crud_functions.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("action=delete&table=" + encodeURIComponent(table) + "&id=" + encodeURIComponent(id));
}
</script>