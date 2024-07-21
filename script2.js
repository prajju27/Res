
document.getElementById('customerLoginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const customerName = document.getElementById('customerName').value;
    const phonenumber = document.getElementById('phonenumber').value;
    const location = document.getElementById('location').value;

    if (customerName&& phone && location) {
        // Store farmer information in localStorage (for demo purposes)
        localStorage.setItem('customerName', customerName);
        localStorage.setItem('phonenumber', phonenumber);
        localStorage.setItem('location', location);

    
        window.location.href = 'customer.html';
    } else {
        alert('Please fill out all fields.');
    }
});
