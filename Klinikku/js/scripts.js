/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
// 
// Skrip
// 

window.addEventListener('DOMContentLoaded', event => {

    // Toggle (beralih) navigasi sisi
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Batalkan komentar di bawah untuk mempertahankan toggle sidebar antara refresh
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});
// Fungsi untuk memperbarui salam dan tanggal
function updateGreetingAndTime() {
    const now = new Date();
    const currentHour = now.getHours();

    // Menentukan salam berdasarkan jam saat ini
    let greeting = "Selamat Pagi";
    let logo = "☀️";

    if (currentHour >= 12 && currentHour < 17) {
        greeting = "Selamat Siang";
        logo = "🌤️";
    } else if (currentHour >= 17) {
        greeting = "Selamat Malam";
        logo = "🌙";
    }

    // Format tanggal
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    const datePart = now.toLocaleDateString('en-US', options);
    const timePart = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: false });
    const formattedDate = `${datePart} • ${timePart}`;

    // Memperbarui ke HTML
    document.getElementById("logo").textContent = logo;
    document.getElementById("greeting").textContent = greeting;
    document.getElementById("currentDateTime").textContent = formattedDate;
}
// Jalankan fungsi
updateGreetingAndTime();

// Opsional: Memperbarui setiap detik
setInterval(updateGreetingAndTime, 1000);



