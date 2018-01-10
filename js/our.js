var api = 'ajax_requests'
var apiRoutes = {
    autocomplete_date_client: "autocomplete_date_client.php",
    rezervare_camere_popup: "rezervare_camere_popup.php"
}
function updateRezervare(button) {
    var arr = [...button.parentNode.parentNode.querySelectorAll('input')]
    var retObj = [...arr.map(item => {
        console.log(item.name)
        var obj = {}
        obj[item.name] = item.value;
        return obj;
    })]
    retObj = [Object.assign({}, ...retObj)]
    console.log(retObj)
    $.ajax({
        url: './ajax_requests/editare_rezervare.php',
        method: 'POST',
        dataType: 'json',
        data: {
            editRezervare: JSON.stringify(retObj),
        },
        beforeSend: function () {
            var div = $("#rezervari");
            div.find('h1').empty();
        },
        success: function (data) {
            if (data.error) {
                var div = $("#rezervari");
                div.append(`<h1 style="color: red">${data.error}</h1>`)                
            } else {
                alert('Rezervare actualizata!');                
            }
        }
    });
}
function ocupareCamera(button) {
    var arr = [...button.parentNode.parentNode.querySelectorAll('input')]
    var retObj = [...arr.map(item => {
        console.log(item.name)
        var obj = {}
        obj[item.name] = item.value;
        return obj;
    })]
    retObj = [Object.assign({}, ...retObj)]
    console.log(retObj)
    $.ajax({
        url: './ajax_requests/ocupare_rezervare.php',
        method: 'POST',
        dataType: 'json',
        data: {
            ocupareRezervare: JSON.stringify(retObj),
        },
        beforeSend: function () {
            var div = $("#rezervari");
            div.find('h1').empty();
        },
        success: function () {
            alert('Camera a fost ocupata cu succes!');
            $('#ocupare-rezervare').trigger('click');
        }
    });
}
function verificaClient(cnpID) {
    var cnp = document.getElementById(cnpID).value;
    $.post(`${api}/${apiRoutes.autocomplete_date_client}`, {cnp})
        .then(data => {
            data = JSON.parse(data)
            if (data.nume && data.nr_telefon) {
                $('input[name="rezervare_nume"]')[0].value = data.nume;
                $('input[name="rezervare_telefon"]')[0].value = data.nr_telefon;
            }
            if (cnp != '') {
                $('#verificare_client_error').html('');
                $('#verificaCnpButton').hide();
                $('.input-hidden').show();
            } else {
                $('#verificare_client_error').html('<i class="mdi mdi-alert-circle"></i> Completeaza CNP!');
            }
        })
}

function submit_form(form_selector, target) {
    
    $('#' + form_selector).ajaxSubmit({
        target: target,
        beforeSubmit: function() {
            $('#' + target).html('');
            $('#eroareCameraOcupata').html('');
        },
        success: function(data) {
            $('body').find('.text-danger').html('');
            if (data != '') {
                result = $.parseJSON(data);
                if ($.type(result.errors) == 'string') {
                    console.log(result.errors);
                    $('#eroareCameraOcupata').html('<i class="mdi mdi-alert-circle"></i> ' + result.errors);
                } else if (!$.isEmptyObject(result.errors)) {
                    $.each(result.errors, function(key, value) {
                        console.log(key, value);
                        if (value != undefined) {
                            $('*[name="' + key + '"]').siblings('label').html('<i class="mdi mdi-alert-circle"></i>' + value);
                        } else {
                            console.log('here');
                            $('*[name="' + key + '"]').siblings('label').html('');
                        }
                    });
                }
            } else {
                alert('Rezervare efectuata!');
            }
        }
    });

}

var camere = [];

$(document).ready(function() {
    
    // Setez min pe data plecare. Sa inceapa cu o zi dupa cea de sosire
    $('body').on('change', '*[name="rezervare_data_sosire"]', function() {
        var dataSosire = $(this).val();
        var minDatePlecare = moment(dataSosire, "YYYY-MM-DD").add(1, 'd').format("YYYY-MM-DD");
        $('*[name="rezervare_data_plecare"]').attr('min', minDatePlecare);
    });

    // Setez max pe data sosire. Sa fie pana la dataPleacare - o zi
    $('body').on('change', '*[name="rezervare_data_plecare"]', function() {
        var dataPlecare = $(this).val();
        var maxDateSosire = moment(dataPlecare, "YYYY-MM-DD").subtract(1, 'd').format("YYYY-MM-DD");
        $('*[name="rezervare_data_sosire"]').attr('max', maxDateSosire);
    });

    $('body').on('change', '*[name="rezervare_tip_camera"], *[name="rezervare_data_sosire"], *[name="rezervare_data_plecare"]', function() {
        console.log($(this).val());
        if ($('*[name="rezervare_tip_camera"]').val() != '' && $('*[name="rezervare_data_sosire"]').val() != '' && $('*[name="rezervare_data_plecare"]').val() != '') {
            $.ajax({
                url: './ajax_requests/rezervare_camere_popup.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    tip_camera: $('*[name="rezervare_tip_camera"]').val(),
                    data_sosire: $('*[name="rezervare_data_sosire"]').val(),
                    data_plecare: $('*[name="rezervare_data_plecare"]').val(),
                },
                beforeSend: function() {
                    $('body').find('#myModal').find('#camere_disponibile').empty();
                },
                success: function(data) {
                    $.each(data, function(key, val) {
                        html = '';
                        html += '<div class="row">';
                        html += '<div class="col-md-2"><label>Etaj</label><input type="text" class="form-control" name="rezervare_etaj" value="' + val.etaj + '" disabled/></div>';
                        html += '<div class="col-md-4"><label>Numar camera</label><input type="text" class="form-control" name="rezervare_numar" value="' + val.numar + '" disabled/></div>';
                        html += '<div class="col-md-3"><label>Pret per zi</label><input type="text" class="form-control" name="rezervare_pret_per_zi" value="' + val.pret_per_zi + '" disabled/></div>';
                        html += '<div class="col-md-3"><input type="checkbox" name="rezervare_camera_' + $('*[name="rezervare_tip_camera"]').val() + key + '" class="form-control" value="{\'id\':' + val.id + ', \'etaj\':' + val.etaj + ', \'numar\':' + val.numar + ', \'pret_per_zi\':' + val.pret_per_zi + '}"></div>';
                        html += '</div>';

                        $('body').find('#myModal').find('#camere_disponibile').append(html);

                        // Event on change pe checkbox-uri. Daca e bifat pun valoare in array, daca nu o sterg
                        $('*[name="rezervare_camera_' + $('*[name="rezervare_tip_camera"]').val() + key +'"]').change(function() {
                            if(this.checked) {
                                $('#rezervare_ok_button').removeAttr('disabled');
                                camere = [];
                                camere.push($(this).val());
                            } else {
                                camere = [];
                                $('#rezervare_ok_button').prop('disabled', true);                                
                            }
                        });
                    });

                    // Cand clientul apasa pe butonul OK din modal, setez valoare pe input-ul hidden din form-ul principal
                    $('body').on('click', '#rezervare_ok_button', function() {
                        $('*[name="camere_rezervate"]').val(JSON.stringify(camere));
                    });
                }
            });
            $('#myModal').modal('show'); 
        }
    });

    // Aduc toate rezervarile dupa id-ul clientului
    $('body').on('click', '#editare-rezervare', function() {
        $.ajax({
            url: './ajax_requests/editare_rezervare.php',
            method: 'POST',
            dataType: 'json',
            data: {
                actionType: 'rezervari',
                cnp: $('*[name="editare_cnp"]').val()
            },
            beforeSend: function() {
                $("#rezervari").empty();
            },
            success: function(data) {
                var div = $("#rezervari");
                div.innerHTML = "";
                $.each(data, function (key, value) {
                    var rezervare = `
                        <div class="row">
                            <div class="col-md-4">
                                <input class="form-control" type="date" name="rezervare_data_sosire" value="${moment(value.data_start, 'DD-MMM-YYYY').format('YYYY-MM-DD')}">
                            </div>
                            <div class="col-md-4">                            
                                <input class="form-control" type="date" name="rezervare_data_plecare" value="${moment(value.data_sfarsit, 'DD-MMM-YYYY').format('YYYY-MM-DD')}">
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-success" onclick="updateRezervare(this)">Trimite</button>
                            </div>
                            <input type="hidden" name="id_rezervare" value="${value.id}">
                            <input type="hidden" name="id_camera" value="${value.id_camera}">
                        </div>
                    
                    `
                    div.append(rezervare)
                });
            }
        });
    });

    $('body').on('click', '#ocupare-rezervare', function() {
        $.ajax({
            url: './ajax_requests/ocupare_rezervare.php',
            method: 'POST',
            dataType: 'json',
            data: {
                actionType: 'ocupare',
                cnp: $('*[name="editare_cnp"]').val()
            },
            beforeSend: function() {
                $("#rezervari").empty();
            },
            success: function(data) {
                var div = $("#rezervari");
                div.innerHTML = "";
                $.each(data, function (key, value) {
                    var rezervare = `
                        <div class="row">
                            <div class="col-md-4">
                                <input class="form-control" type="date" name="rezervare_data_sosire" value="${moment(value.data_start, 'DD-MMM-YYYY').format('YYYY-MM-DD')}" disabled>
                            </div>
                            <div class="col-md-4">                            
                                <input class="form-control" type="date" name="rezervare_data_plecare" value="${moment(value.data_sfarsit, 'DD-MMM-YYYY').format('YYYY-MM-DD')}" disabled>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-success" onclick="ocupareCamera(this)">Ocupa</button>
                            </div>
                            <input type="hidden" name="id_rezervare" value="${value.id}">
                            <input type="hidden" name="id_camera" value="${value.id_camera}">
                        </div>
                    
                    `
                    div.append(rezervare)
                });
            }
        });
    });


    // Scoatere rezervari
    $('body').on('click', '#eliberare_camere', function() {
        $.ajax({
            url: './ajax_requests/scoatere_rezervari.php',
            method: 'GET'
        });
    });
    
});
