document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (e) {
        const checked = document.querySelectorAll('input[name="MonDongTien[]"]:checked');
        if (checked.length === 0) {
            alert("Vui lòng tích chọn ít nhất 1 môn");
            e.preventDefault();
            return;
        }
    });
});
