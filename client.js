document.getElementById('formReservation').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const errorMessages = validateForm(formData);

    if (errorMessages.length > 0) {
        showErrors(errorMessages);
        return;
    }

    fetch('inserer_client.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('message').innerHTML = data;
        if (data.includes("✅")) {
            this.reset();
        }
    })
    .catch(error => console.error('Error:', error));
});

function validateForm(formData) {
    const errors = [];
    
    if (!formData.get('nom').match(/^[a-zA-ZÀ-ÿ\s-]{2,}$/)) {
        errors.push("Nom invalide");
    }
    
    if (!formData.get('email').match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        errors.push("Email invalide");
    }
    
    return errors;
}

function showErrors(errors) {
    const errorHtml = errors.map(error => 
        `<div class="error">❌ ${error}</div>`
    ).join('');
    document.getElementById('message').innerHTML = errorHtml;
}