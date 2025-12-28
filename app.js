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

function loadDashboard(filter = 'all') {
    fetch(`dashboard.php?filter=${filter}`)
    .then(response => response.text())
    .then(html => {
        document.querySelector('main').innerHTML = html;
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

/* Contact Details + Notes */
function loadContactDetails(contactId) {
    fetch("contact_details.php?id=" + contactId)
    .then(response => response.text())
    .then(html => {
        document.querySelector('main').innerHTML = html;
        setupAddNoteForm(); // Initialize note submission listener
    })
    .catch(error => console.error("Error loading contact details:", error));
}

function setupAddNoteForm() {
    const form = document.getElementById('add-note-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch('add_note.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.ok) {
                    form.reset();
                    loadContactDetails(formData.get('contact_id')); // Reload details to show new note
                } else {
                    alert(data.message || "Error adding note.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding note. Check console for details.');
            });
        });
    }
}

function loadUsers() {
    fetch("users.php")
    .then(r => r.text())
    .then(html => {
        document.querySelector("main").innerHTML = html;
    })
    .catch(err => console.error("Error loading users:", err));
}

function loadNewContact() {
    fetch("new_contact.php")
    .then(response => response.text())
    .then(html => {
        document.querySelector('main').innerHTML = html;
        setupNewContactForm();
    })
    .catch(error => console.error("Error loading new contact form:", error));
}

function setupNewContactForm() {
    const form = document.getElementById('new-contact-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            
            fetch('add_contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.ok) {
                    loadDashboard();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding contact. Check console for details.');
            });
        });
    }
}

function logout() {
    fetch('logout.php')
    .then(() => {
        window.location.href = 'dolphin_crm.html';
    });
}

function handleAction(contactId, actionType) {
    const formData = new FormData();
    formData.append('id', contactId);
    formData.append('action', actionType);

    fetch('update_contact.php', {
        method: 'POST',
        body: formData
    })
    .then(() => loadContactDetails(contactId)); // Refresh details view
}

document.getElementById("login-form").addEventListener("submit", function(e) {
    e.preventDefault();

    const formData = new FormData(document.getElementById("login-form"));
    fetch("dolphin.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === "success") {
            // Hide login section
            document.getElementById("overview").style.display = "none";
            loadDashboard();
        } else {
            alert("Login failed. Please check your credentials.");
        }
    });
});
