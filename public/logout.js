document.getElementById("logout-btn").addEventListener("click", async function (e) {
    e.preventDefault();

    try {
        const response = await fetch(
            "../backend/api/endpoints/account/logout.php",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                credentials: "include"
            }
        );

        const data = await response.json();

        if (response.ok) {
            window.location.href = "http://localhost/Activity1";
        } else {
            alert("Logout failed");
        }

    } catch (error) {
        console.error("Error:", error);
        window.location.href = "http://localhost/Activity1";
    }
});