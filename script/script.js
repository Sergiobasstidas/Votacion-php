$(document).ready(function () {
  // Cargar regiones y candidatos al cargar la página
  $.ajax({
    url: "submit_vote.php",
    method: "POST",
    data: { action: "loadData" },
    success: function (response) {
      let data = JSON.parse(response);
      let regiones = data.regiones;
      let candidatos = data.candidatos;

      regiones.forEach((region) => {
        $("#region").append(
          `<option value="${region.id}">${region.nombre}</option>`
        );
      });

      candidatos.forEach((candidato) => {
        $("#candidato").append(
          `<option value="${candidato.id}">${candidato.nombre}</option>`
        );
      });
    },
  });

  // Cargar comunas cuando se seleccione una región
  $("#region").change(function () {
    let regionId = $(this).val();
    $.ajax({
      url: "submit_vote.php",
      method: "POST",
      data: { action: "loadComunas", regionId: regionId },
      success: function (response) {
        let comunas = JSON.parse(response);
        $("#comuna").empty();
        comunas.forEach((comuna) => {
          $("#comuna").append(
            `<option value="${comuna.id}">${comuna.nombre}</option>`
          );
        });
      },
    });
  });

  // Enviar el formulario
  $("#votacionForm").submit(function (event) {
    event.preventDefault();

    let nombreApellido = $("#nombreApellido").val();
    let alias = $("#alias").val();
    let rut = $("#rut").val();
    let email = $("#email").val();
    let region = $("#region").val();
    let comuna = $("#comuna").val();
    let candidato = $("#candidato").val();
    let enterado = [];
    $('input[name="enterado"]:checked').each(function () {
      enterado.push($(this).val());
    });

    if (enterado.length < 2) {
      alert(
        'Debe seleccionar al menos dos opciones de "¿Cómo se enteró de Nosotros?".'
      );
      return;
    }

    $.ajax({
      url: "submit_vote.php",
      method: "POST",
      data: {
        action: "submitVote",
        nombreApellido: nombreApellido,
        alias: alias,
        rut: rut,
        email: email,
        region: region,
        comuna: comuna,
        candidato: candidato,
        enterado: enterado.join(", "),
      },
      success: function (response) {
        if (response === "success") {
          alert("Voto registrado con éxito.");
        } else {
          alert(response);
        }
      },
    });
  });
});
