document.getElementById("employee-form").addEventListener("submit", async function (e) {
    e.preventDefault();

    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value;

    const alphanumericRegex = /^[a-zA-Z0-9]+$/;

    if (!alphanumericRegex.test(username)) {
        alert("Username can only contain letters and numbers.");
        return;
    }

    if (!alphanumericRegex.test(password)) {
        alert("Password can only contain letters and numbers.");
        return;
    }

    try {
        const response = await fetch(
            "/Activity1/backend/api/endpoints/account/login.php",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    username: username,
                    password: password
                }),
                credentials: "include"
            }
        );

        const data = await response.json();

        if (response.ok) {
            alert("Login successful!");
            console.log(data);
            console.log("Full data:", data);
            console.log("Role value:", data.role);
            console.log("Role type:", typeof data.role);

            if (data.role === 'Admin' || data.role === 'Global Admin') {
                window.location.href = "/Activity1/admin/admin-dashboard.php";
            } else if (data.role === 'User') {
                window.location.href = "/Activity1/employee/employee-dashboard.php";
            } else {
                alert("Unknown role");
            }

        } else {
            alert(data.message || "Login failed");
        }

    } catch (error) {
        console.error("Error:", error);
        alert("Server error");
    }
});
