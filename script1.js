
document.getElementById('farmerLoginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const farmerName = document.getElementById('farmerName').value;
    const phone = document.getElementById('phone').value;
    const location = document.getElementById('location').value;
    const password = document.getElementById('password').value;

    if (farmerName && phone && location && password) {
        localStorage.setItem('farmerName', farmerName);
        localStorage.setItem('phone', phone);
        localStorage.setItem('location', location);

        window.location.href = 'farmer.html';
    } else {
        alert('Please fill out all fields.');
    }
});
