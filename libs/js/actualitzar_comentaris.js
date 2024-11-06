"use strict";

function main() {
  $("#editModal").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var id = button.data("id");
    var comentaris = button.data("comentaris");

    var modal = $(this);
    modal.find("#edit-id").val(id);
    modal.find("#edit-comentaris").val(comentaris);
  });
}

document.addEventListener('DOMContentLoaded', main);