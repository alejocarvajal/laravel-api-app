function findName(route) {
    $.ajax({
        url: route,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            nombre: $("#nombre").val(),
            porcentaje: $("#porcentaje").val()
        },
        cache: false,
        datatype: 'json',
        beforeSend: function () {
            $("#compare-result").parent().hide();
        },
        complete: function () {
            //$("#loadingSaveOrder").hide();
        },
        success: function (data) {
            showResultsTable(data.result.resultados);
            $("#notificaciones").empty();
            if(data.result.registros_encontrados > 0) {
                $("#notificaciones").html(`<p>Registros encontrados: ${data.result.registros_encontrados} </p>`);
            }else{
                $("#notificaciones").html(`<p> ${data.result.estado_ejecucion} </p>`);
                $("#compare-result").parent().hide();
            }
        },
        error: function (exception) {
            console.log(exception.responseJSON);
            $("#compare-result").parent().show();
        }
    });
}

function showResultsTable(data) {
    $("#compare-result").empty();
    $.each(data, function (key, name) {
        $("#compare-result").append(
            $("<tr>").append(
                $("<td>").text(name.nombre)
            ).append(
                $("<td>").text(name.departamento)
            ).append(
                $("<td>").text(name.localidad)
            ).append(
                $("<td>").text(name.municipio)
            ).append(
                $("<td>").text(name.anios_activo)
            ).append(
                $("<td>").text(name.tipo_persona)
            ).append(
                $("<td>").text(name.tipo_cargo)
            ).append(
                $("<td>").text(parseFloat(name.porcentaje).toFixed(2))
            )
        )
    });
    $("#compare-result").parent().show();
}

