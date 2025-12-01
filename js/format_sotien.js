document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const input = document.getElementById("SoTienDong") || document.getElementById("editSoTienDong");

    form.addEventListener("submit", function (e) {
        let raw = input.value.replace(/\./g, "").trim();

        if (!raw || isNaN(raw)) {
            alert("Vui lòng nhập số tiền hợp lệ.");
            e.preventDefault();
            input.focus();
            return;
        }

        if (raw % 1000 !== 0) {
            alert("Số tiền phải là bội số của 1.000 (kết thúc bằng 000)");
            e.preventDefault();   // ⛔ chặn submit
            input.focus();
            return;
        }
    });
});
