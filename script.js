// Slideshow for About Us
let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function showSlides(n) {
    let slides = document.getElementsByClassName("slide");
    if (slides.length === 0) return; // Skip if no slides exist on the page
    if (n > slides.length) { slideIndex = 1; }
    if (n < 1) { slideIndex = slides.length; }
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slides[slideIndex - 1].style.display = "block";
}

// Pop-up Modal Functions
function showLoginModal(event) {
    event.preventDefault();
    console.log("showLoginModal triggered");
    const loginModal = document.getElementById("loginModal");
    const registerModal = document.getElementById("registerModal");
    if (loginModal && registerModal) {
        loginModal.style.display = "flex";
        registerModal.style.display = "none";
    } else {
        console.error("Login modal not found in DOM");
    }
}

function showRegisterModal(event) {
    event.preventDefault();
    console.log("showRegisterModal triggered");
    const loginModal = document.getElementById("loginModal");
    const registerModal = document.getElementById("registerModal");
    if (loginModal && registerModal) {
        loginModal.style.display = "none";
        registerModal.style.display = "flex";
    } else {
        console.error("Register modal not found in DOM");
    }
}

function closeModal(modalId) {
    console.log("Attempting to close modal: " + modalId);
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = "none";
    } else {
        console.error("Modal with ID " + modalId + " not found");
    }
}

function validateRegisterForm(event) {
    if (event) event.preventDefault();

    const passwordField = document.getElementById("password");
    const confirmPasswordField = document.getElementById("confirm-password");
    const contactField = document.getElementById("contact");
    const emailField = document.getElementById("email");
    const registerForm = document.getElementById("registerForm");

    console.log("Validating form - registerForm:", registerForm, "passwordField:", passwordField);

    if (!registerForm || !passwordField || !confirmPasswordField || !contactField || !emailField) {
        console.log("Register form or required fields not found. Skipping validation on form ID:", registerForm ? registerForm.id : "no form");
        return false;
    }

    console.log("Validating registration form...");
    const password = passwordField.value;
    const confirmPassword = confirmPasswordField.value;
    const contact = contactField.value;
    const email = emailField.value;

    console.log("Password match check:", password === confirmPassword);
    if (password !== confirmPassword) {
        alert("Passwords do not match!");
        return false;
    }

    const contactPattern = /^[0-9]{10}$/;
    console.log("Contact pattern check:", contactPattern.test(contact));
    if (!contactPattern.test(contact)) {
        alert("Please enter a valid 10-digit mobile number!");
        return false;
    }

    console.log("Email check:", email.includes('@'));
    if (!email.includes('@')) {
        alert("Please enter a valid email address containing '@'!");
        return false;
    }

    console.log("Validation passed, closing modal and submitting registration form...");
    closeModal('registerModal');
    console.log("Modal close attempted, now submitting form...");
    registerForm.submit();
    console.log("Form submission triggered");
    return true;
}

// Delete Functions (from previous fix)
function deleteDoctor(id) {
    if (confirm("Are you sure you want to delete this doctor?")) {
        fetch(`delete-doctor.php?id=${id}`, { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Doctor deleted successfully!");
                    location.reload();
                } else {
                    alert("Error deleting doctor: " + data.error);
                }
            })
            .catch(error => console.error("Error deleting doctor:", error));
    }
}

function deleteNews(id) {
    if (confirm("Are you sure you want to delete this news item?")) {
        fetch(`delete-news.php?id=${id}`, { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("News item deleted successfully!");
                    location.reload();
                } else {
                    alert("Error deleting news: " + data.error);
                }
            })
            .catch(error => console.error("Error deleting news:", error));
    }
}