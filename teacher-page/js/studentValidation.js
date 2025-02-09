
    document.getElementById("studentForm").addEventListener("submit", function (event) {
        // Prevent form submission
        event.preventDefault();

        // Get form fields
        const firstName = document.getElementById("firstName").value.trim();
        const lastName = document.getElementById("lastName").value.trim();
        const email = document.getElementById("email").value.trim();
        const contactNumber = document.getElementById("contactNumber").value.trim();
        const guardianName = document.getElementById("guardianName").value.trim();
        const guardianContactNumber = document.getElementById("guardianContactNumber").value.trim();
        const address = document.getElementById("address").value.trim();
        const admissionNumber = document.getElementById("admissionNumber").value.trim();
        const dateOfBirth = document.getElementById("dob").value.trim();
        const batch = document.getElementById("batch").value.trim();
        const yearOfStudy = document.getElementById("yearOfStudy").value;
        const courseId = document.getElementById("course_id").value;
        const semesterId = document.getElementById("semester_id").value;
        const photo = document.getElementById("photo").files[0];

        // Initialize error messages array
        let errorMessages = [];

        // Validate fields
        if (!firstName) errorMessages.push("First Name is required.");
        if (!lastName) errorMessages.push("Last Name is required.");
        if (!email) {
            errorMessages.push("Email is required.");
        } else if (!/^\S+@\S+\.\S+$/.test(email)) {
            errorMessages.push("Invalid email format.");
        }
        if (!contactNumber) {
            errorMessages.push("Contact Number is required.");
        } else if (!/^\d{10}$/.test(contactNumber)) {
            errorMessages.push("Contact Number must be 10 digits.");
        }
        if (!guardianName) errorMessages.push("Guardian's Name is required.");
        if (!guardianContactNumber) {
            errorMessages.push("Guardian's Contact Number is required.");
        } else if (!/^\d{10}$/.test(guardianContactNumber)) {
            errorMessages.push("Guardian's Contact Number must be 10 digits.");
        }
        if (!address) errorMessages.push("Address is required.");
        if (!admissionNumber) errorMessages.push("Admission Number is required.");
        if (!dateOfBirth) errorMessages.push("Date of Birth is required.");
        if (!batch) errorMessages.push("Batch is required.");
        if (!yearOfStudy) errorMessages.push("Year of Study is required.");
        if (!courseId) errorMessages.push("Course Enrolled is required.");
        if (!semesterId) errorMessages.push("Semester is required.");
        if (!photo) {
            errorMessages.push("Photo upload is required.");
        } else if (!["image/jpeg", "image/png", "image/jpg"].includes(photo.type)) {
            errorMessages.push("Photo must be a JPEG, JPG, or PNG file.");
        }

        // Show errors or submit form
        if (errorMessages.length > 0) {
            alert(errorMessages.join("\n"));
        } else {
            // If no errors, submit the form
            alert("Student added successfully.");
            this.submit(); // Submit the form
        }
    });
