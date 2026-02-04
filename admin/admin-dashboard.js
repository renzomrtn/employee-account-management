// API URLS
const API = {
    create_e: "../backend/api/endpoints/employee/create.php",
    read_e: "../backend/api/endpoints/employee/read.php",
    create_a: "../backend/api/endpoints/account/create.php",
    read_a: "../backend/api/endpoints/account/read.php"
};

// POST
function createEmployee(data) {
    return fetch(API.create_e, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
    .then(res => {
        if (!res.ok) {
            throw new Error("Server error while creating employee");
        }
        return res.json();
    });
}

function createAccount(data) {
    return fetch(API.create_a, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
    .then(res => {
        if (!res.ok) {
            throw new Error("Server error while creating account");
        }
        return res.json();
    });
}

// GET
function getEmployees() {
    return fetch(API.read_e)
        .then(res => {
            if (!res.ok) {
                throw new Error("Server error while fetching employees");
            }
            return res.json();
        });
}

function getAccounts() {
    return fetch(API.read_a)
        .then(res => {
            if (!res.ok) {
                throw new Error("Server error while fetching accounts");
            }
            return res.json();
        });
}

// FORM SUBMIT HANDLER
document.getElementById("employee-form").addEventListener("submit", function (e) {
    e.preventDefault();

    const data = {
        name: document.getElementById("name").value,
        address: document.getElementById("address").value,
        birthday: document.getElementById("birthday").value,
        position: document.getElementById("position").value,
        department: document.getElementById("department").value,
        username: document.getElementById("username").value,
        password: document.getElementById("password").value,
        role: document.getElementById("role").value
    };

    createEmployee(data)
        .then(response => {
            if (response.success) {
                data.e_id = response.e_id; 
                console.log("Data being sent to createAccount:", data);
                return createAccount(data); 
            } else {
                throw new Error(response.message);
            }
        })
        .then(response => {
            alert(response.message);
            this.reset();
            loadTable(); 
        })
        .catch(err => {
            console.error(err);
            alert(err.message || "Failed to create employee/account");
        });
});
// renderTable for employee-account data
function renderTable(employees, accounts) {
    const tbody = document.querySelector("#employee-account-data tbody");
    tbody.innerHTML = "";

    if (employees.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6">No records found</td></tr>`;
        return;
    }

    const accountMap = {};
    accounts.forEach(acc => {
        accountMap[acc.e_id] = acc;
    });

    employees.forEach(emp => {
        const acc = accountMap[emp.e_id];
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${emp.e_id}</td>
            <td>${emp.name}</td>
            <td>${emp.position}</td>
            <td>${emp.department}</td>
            <td>${acc ? acc.username : "N/A"}</td>
            <td>${acc ? acc.role : "N/A"}</td>
        `;

        tbody.appendChild(row);
    });
}

// LOAD and DISPLAY EMPLOYEES // ACCOUNTS
function loadTable() {
    Promise.all([getEmployees(), getAccounts()])
        .then(([empData, accData]) => {
            const employees = Array.isArray(empData) ? empData : empData.data;
            const accounts = Array.isArray(accData) ? accData : accData.data;
            renderTable(employees, accounts);
        })
        .catch(err => {
            console.error("Error:", err);
            alert("Failed to load employee/account data");
        });
}

// LOAD ON PAGE START
document.addEventListener("DOMContentLoaded", () => {
    loadTable();
});


// LOAD ON REFRESH CLICK
document.querySelector(".refresh").addEventListener("click", loadTable);