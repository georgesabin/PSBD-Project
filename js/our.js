var api = 'ajax_requests'
var apiRoutes = {
    autocomplete_date_client: "autocomplete_date_client.php",
    rezervare_camere_popup: "rezervare_camere_popup.php"
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
            $('#verificaCnpButton').hide();
            $('.input-hidden').show();
        })
}