document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("user-form");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirmPassword");

    form.addEventListener("submit", (e) => {
        // Validasi apakah password dan konfirmasi password cocok
        if (password.value !== confirmPassword.value) {
            e.preventDefault(); // Mencegah pengiriman formulir
            alert("Password dan Konfirmasi Password tidak cocok!");
            return;
        }

        // Validasi panjang password
        if (password.value.length < 8 || password.value.length > 10) {
            e.preventDefault(); // Mencegah pengiriman formulir
            alert("Password harus memiliki panjang antara 8 dan 10 karakter!");
            return;
        }

        // Validasi format email
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email.value)) {
            e.preventDefault(); // Mencegah pengiriman formulir
            alert("Format email tidak valid!");
            return;
        }
    });
});