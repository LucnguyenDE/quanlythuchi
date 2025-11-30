function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}
// Khi nhấn nút sửa
document.querySelectorAll('.editBtn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Lấy dữ liệu từ data-*
        document.getElementById('editID').value = this.dataset.id;
        
        const monValues = this.dataset.mon.split(','); // lấy từ data-mon của nút sửa
        const checkboxes = document.querySelectorAll('#editMonDongTien input[type="checkbox"]');
        checkboxes.forEach(cb => {
            cb.checked = monValues.includes(cb.value);
        });
        // Format số tiền 000.000
        let tien = this.dataset.tien;
        tien = parseInt(tien).toLocaleString("vi-VN");
        document.getElementById('editSoTienDong').value = tien;
        document.getElementById('editNguoiDongTien').value = this.dataset.ngdong;
        document.getElementById('editNguoiNhanTien').value = this.dataset.ngnhan;
        document.getElementById('editGhichu').value = this.dataset.ghichu;
       
        // Lấy SoCMT từ URL
        const SoCMT = getQueryParam('SoCMT');

        // Thêm input hidden nếu chưa có
        if (!document.getElementById('editSoCMT')) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'SoCMT';
            input.id = 'editSoCMT';
            input.value = SoCMT;
            document.getElementById('editForm').appendChild(input);
        }

        // Hiện modal
        let modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
    });
});
