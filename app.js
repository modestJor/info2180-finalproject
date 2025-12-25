function setupNewUserForm() {
    const form = document.getElementById('new-user-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Password validation
            const password = document.getElementById('new-password').value;
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
            
            if (!passwordRegex.test(password)) {
                alert('Password must be at least 8 characters with: \n• One uppercase letter\n• One lowercase letter\n• One number');
                return;
            }
            
            const formData = new FormData(form);
            
            fetch('add_user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                if (data.includes('successfully')) {
                    form.reset(); // Clear the form on success
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding user. Check console for details.');
            });
        });
    }
}

function loadDashboard() {
    fetch("dashboard.php")
    .then(response => response.text())
    .then(html => {
        document.querySelector('main').innerHTML = html; // Load dashboard content into main section
    })
    .catch(error => console.error("Error loading dashboard:", error));
}

function loadNewUser() {
    fetch("new_user.php")
    .then(response => response.text())
    .then(html => {
        document.querySelector('main').innerHTML = html;
        setupNewUserForm(); // Initialize the form's listener
    });
}

document.getElementById("login-form").addEventListener("submit", function(e) {
    e.preventDefault();
    /*
    const password = document.getElementById("password").value;
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

    if (!passwordRegex.test(password)) {
        alert("Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, and one number.");
        return;
    }
    */

    const formData = new FormData(document.getElementById("login-form"));
    fetch("dolphin.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === "success") {
            loadNewUser(); 
        } else {
            alert("Login failed. Please check your credentials.");
        }
    });
});