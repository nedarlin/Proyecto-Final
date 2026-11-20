document.querySelectorAll('input[name="hora"]').forEach(function (radio) {
    radio.addEventListener("change", function () {
        document.getElementById("resumen-hora").textContent = this.value;
    });
});
