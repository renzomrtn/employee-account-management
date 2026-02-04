function login() {
    fetch("../backend/api/endpoints/account/login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            username: document.getElementById("username").value,
            password: document.getElementById("password").value
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
    })
    .catch(err => console.error(err));
}
