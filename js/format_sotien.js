document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("SoTienDong");

    if (!input) return; // tránh lỗi nếu trang không có input này

    // Format số theo dạng 000.000 và chỉ cho nhập số
    input.addEventListener("input", function (e) {
        let value = e.target.value.replace(/\D/g, ""); // bỏ ký tự không phải số

        if (!value) {
            e.target.value = "";
            return;
        }

        e.target.value = Number(value).toLocaleString("vi-VN");
    });

    // Kiểm tra khi mất focus: phải có 3 số 0 cuối
    input.addEventListener("blur", function (e) {
        let clean = e.target.value.replace(/\./g, "");
        if (clean % 1000 !== 0) {
            alert("Số tiền phải kết thúc bằng 000 (bội số của 1.000)");
            e.target.focus();
        }
    });
});
