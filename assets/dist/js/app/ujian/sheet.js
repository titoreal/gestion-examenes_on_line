$(document).ready(function() {
    var t = $('.sisaduracion');
    if (t.length) {
        sisaduracion(t.data('time'));
    }

    buka(1);
    simpan_sementara();

    widget = $(".step");
    btnnext = $(".next");
    btnback = $(".back");
    btnsubmit = $(".submit");

    $(".step, .back, .selesai").hide();
    $("#widget_1").show();
});

function getFormData($form) {
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};
    $.map(unindexed_array, function(n, i) {
        indexed_array[n['name']] = n['value'];
    });
    return indexed_array;
}

function buka(id_widget) {
    $(".next").attr('rel', (id_widget + 1));
    $(".back").attr('rel', (id_widget - 1));
    $(".ragu_ragu").attr('rel', (id_widget));
    cek_status_ragu(id_widget);
    cek_terakhir(id_widget);

    $("#preguntake").html(id_widget);

    $(".step").hide();
    $("#widget_" + id_widget).show();

    simpan();
}

function next() {
    var berikutnya = $(".next").attr('rel');
    berikutnya = parseInt(berikutnya);
    berikutnya = berikutnya > total_widget ? total_widget : berikutnya;

    $("#preguntake").html(berikutnya);

    $(".next").attr('rel', (berikutnya + 1));
    $(".back").attr('rel', (berikutnya - 1));
    $(".ragu_ragu").attr('rel', (berikutnya));
    cek_status_ragu(berikutnya);
    cek_terakhir(berikutnya);

    var sudah_akhir = berikutnya == total_widget ? 1 : 0;

    $(".step").hide();
    $("#widget_" + berikutnya).show();

    if (sudah_akhir == 1) {
        $(".back").show();
        $(".next").hide();
    } else if (sudah_akhir == 0) {
        $(".next").show();
        $(".back").show();
    }

    simpan();
}

function back() {
    var back = $(".back").attr('rel');
    back = parseInt(back);
    back = back < 1 ? 1 : back;

    $("#preguntake").html(back);

    $(".back").attr('rel', (back - 1));
    $(".next").attr('rel', (back + 1));
    $(".ragu_ragu").attr('rel', (back));
    cek_status_ragu(back);
    cek_terakhir(back);

    $(".step").hide();
    $("#widget_" + back).show();

    var sudah_awal = back == 1 ? 1 : 0;

    $(".step").hide();
    $("#widget_" + back).show();

    if (sudah_awal == 1) {
        $(".back").hide();
        $(".next").show();
    } else if (sudah_awal == 0) {
        $(".next").show();
        $(".back").show();
    }

    simpan();
}

function tidak_jawab() {
    var id_step = $(".ragu_ragu").attr('rel');
    var status_ragu = $("#rg_" + id_step).val();

    if (status_ragu == "N") {
        $("#rg_" + id_step).val('Y');
        $("#btn_pregunta_" + id_step).removeClass('btn-success');
        $("#btn_pregunta_" + id_step).addClass('btn-primary');

    } else {
        $("#rg_" + id_step).val('N');
        $("#btn_pregunta_" + id_step).removeClass('btn-primary');
        $("#btn_pregunta_" + id_step).addClass('btn-success');
    }

    cek_status_ragu(id_step);

    simpan();
}

function cek_status_ragu(id_pregunta) {
    var status_ragu = $("#rg_" + id_pregunta).val();

    if (status_ragu == "N") {
        $(".ragu_ragu").html('Doubt');
    } else {
        $(".ragu_ragu").html('No doubt');
    }
}

function cek_terakhir(id_pregunta) {
    var jml_pregunta = $("#jml_pregunta").val();
    jml_pregunta = (parseInt(jml_pregunta) - 1);

    if (jml_pregunta === id_pregunta) {
        $('.next').hide();
        $(".selesai, .back").show();
    } else {
        $('.next').show();
        $(".selesai, .back").hide();
    }
}

function simpan_sementara() {
    var f_asal = $("#resultados");
    var form = getFormData(f_asal);
    //form = JSON.stringify(form);
    var jml_pregunta = form.jml_pregunta;
    jml_pregunta = parseInt(jml_pregunta);

    var hasil_respuesta = "";

    for (var i = 1; i < jml_pregunta; i++) {
        var idx = 'opsi_' + i;
        var idx2 = 'rg_' + i;
        var jawab = form[idx];
        var ragu = form[idx2];

        if (jawab != undefined) {
            if (ragu == "Y") {
                if (jawab == "-") {
                    hasil_respuesta += '<a id="btn_pregunta_' + (i) + '" class="btn btn-default btn_pregunta btn-sm" onclick="return buka(' + (i) + ');">' + (i) + ". " + jawab + "</a>";
                } else {
                    hasil_respuesta += '<a id="btn_pregunta_' + (i) + '" class="btn btn-primary btn_pregunta btn-sm" onclick="return buka(' + (i) + ');">' + (i) + ". " + jawab + "</a>";
                }
            } else {
                if (jawab == "-") {
                    hasil_respuesta += '<a id="btn_pregunta_' + (i) + '" class="btn btn-default btn_pregunta btn-sm" onclick="return buka(' + (i) + ');">' + (i) + ". " + jawab + "</a>";
                } else {
                    hasil_respuesta += '<a id="btn_pregunta_' + (i) + '" class="btn btn-success btn_pregunta btn-sm" onclick="return buka(' + (i) + ');">' + (i) + ". " + jawab + "</a>";
                }
            }
        } else {
            hasil_respuesta += '<a id="btn_pregunta_' + (i) + '" class="btn btn-default btn_pregunta btn-sm" onclick="return buka(' + (i) + ');">' + (i) + ". -</a>";
        }
    }
    $("#tampil_respuesta").html('<div id="yes"></div>' + hasil_respuesta);
}

function simpan() {
    simpan_sementara();
    var form = $("#resultados");

    $.ajax({
        type: "POST",
        url: base_url + "resultados/simpan_satu",
        data: form.serialize(),
        dataType: 'json',
        success: function(data) {
            // $('.ajax-loading').show();
            console.log(data);
        }
    });
}

function selesai() {
    simpan();
    ajaxcsrf();
    $.ajax({
        type: "POST",
        url: base_url + "resultados/simpan_akhir",
        data: { id: id_tes },
        beforeSend: function() {
            simpan();
            // $('.ajax-loading').show();    
        },
        success: function(r) {
            console.log(r);
            if (r.status) {
                window.location.href = base_url + 'resultados/list';
            }
        }
    });
}

function duracionHabis() {
    selesai();
    alert('Exam time is up!');
}

function simpan_akhir() {
    simpan();
    if (confirm('Are you sure you want to end the test?')) {
        selesai();
    }
}