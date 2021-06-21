function validateForm() {
    let error = false;
    $(".error_form").remove();
    if (!$("#nombre").val()) {
        $("#nombre").focus();
        $("#nombre").parent().append("<div class='error_form'><p style='color: red'>Campo obligatorio</p> </>");
        error = true;
    }
    if (!$("#porcentaje").val()) {
        $("#porcentaje").focus();
        $("#porcentaje").parent().append("<div class='error_form'><p style='color: red'>Campo obligatorio</p> </>");
        error = true;
    }
    return error;
}

function findName(route) {
    if (!validateForm()) {
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
                $("#findName").addClass('btn-warning');
                $("#findName").empty().html("<i class='fa fa-search'></i> Buscando...");
                $("#compare-result").parent().hide();
            },
            complete: function () {
                $("#findName").removeClass('btn-warning');
                $("#findName").empty().html("<i class='fa fa-search'></i> Buscar");
            },
            success: function (data) {
                showResultsTable(data.result.resultados);
                $("#notificaciones").empty();
                if (data.result.registros_encontrados > 0) {
                    $("#notificaciones").html(`<p>Registros encontrados: ${data.result.registros_encontrados} </p>`);
                } else {
                    $("#notificaciones").html(`<p> ${data.result.estado_ejecucion} </p>`);
                    $("#compare-result").parent().hide();
                }
            },
            error: function (exception) {
                $("#notificaciones").html(`<p style="color: red"> Error: ${exception.responseJSON.message} </p>`);
                $("#compare-result").parent().hide();
            }
        });
    }
}

function showResultsTable(data) {
    $("#compare-result").empty();
    $.each(data, function (key, name) {
        $("#compare-result").append(
            $("<tr>").append(
                $("<td>").text(name.nombre)
            ).append(
                $("<td>").text(parseFloat(name.porcentaje).toFixed(2))
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
            )
        )
    });
    $("#compare-result").parent().show();
}

function exportReport(route, formato, element) {
    let id_element = $(element).attr('id');
    if (!validateForm()) {
        $.ajax({
            url: route,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            xhrFields: {
                responseType: 'blob',
            },
            data: {
                nombre: $("#nombre").val(),
                porcentaje: $("#porcentaje").val(),
                formato: formato
            },
            cache: false,
            datatype: 'json',
            beforeSend: function () {
                $('#' + id_element).addClass('btn-warning');
                $('#' + id_element).empty().html("<i class='fa fa-download'></i> Exportando...</a>");
            },
            complete: function () {
                $('#' + id_element).removeClass('btn-warning');
                $('#' + id_element).empty().html(`<i class='fa fa-download'></i> Exportar ${$('#' + id_element).data('export')} </a>`);
            },
            success: function (result, status, xhr) {
                var disposition = xhr.getResponseHeader('content-disposition');
                var filename = 'SearchReport.' + formato;

                // The actual download
                var blob = new Blob([result]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;

                document.body.appendChild(link);

                link.click();
                document.body.removeChild(link);
            },
            error: function () {
                $("#notificaciones").html(`<p style="color: red"> Error al exportar </p>`);
                $("#compare-result").parent().hide();
            }
        });
    }
}

