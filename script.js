document.addEventListener('DOMContentLoaded', () => {
    const farmerLoginForm = document.getElementById('farmerLoginForm');
    const customerLoginForm = document.getElementById('customerLoginForm');
    
    if (farmerLoginForm) {
        farmerLoginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const name = document.getElementById('Name').value;
            const phone = document.getElementById('Phone').value;
            const location = document.getElementById('Location').value;
            const password = document.getElementById('Password').value;
            console.log(`Farmer: ${name}, Phone: ${phone}, Location: ${location}`);
            window.location.href = 'dashboard.html';
        });
    }

    if (customerLoginForm) {
        customerLoginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const name = document.getElementById('customerName').value;
            const location = document.getElementById('location').value;
            const phonenumber = document.getElementById('phonenumber').value;
            console.log(`Customer: ${name}, Location: ${location}`,'phonenumber:${phonenumber}');
            window.location.href = 'dashboard.php';
        });
    }

});
