// API URLS
const API = {
    create_e: "../backend/api/endpoints/employee/create.php",
    read_e: "../backend/api/endpoints/employee/read.php",
    create_a: "../backend/api/endpoints/account/create.php",
    read_a: "../backend/api/endpoints/account/read.php",
    // create_sl: "../backend/api/endpoints/session_log/create.php",
    // read_sl: "../backend/api/endpoints/session_log/read.php"
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

function createSessionLog(data) {
    return fetch(API.create_sl, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
    .then(res => {
        if (!res.ok) {
            throw new Error("Server error while creating session log");
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

function getSessionLogs() {
    return fetch(API.read_sl)
        .then(res => {
            if (!res.ok) {
                throw new Error("Server error while fetching session logs");
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
                data.e_id = response.e_id; // get the new employee ID
                console.log("Data being sent to createAccount:", data);
                return createAccount(data); // chain account creation
            } else {
                throw new Error(response.message);
            }
        })
        .then(response => {
            alert(response.message);
            this.reset();
            loadTable(); // single load call
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

/* render function specifically for session logs
function renderSessionLogs(logs, accounts) {
    const tbody = document.querySelector("#session-log-data tbody");
    tbody.innerHTML = "";

    if (logs.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7">No logs found</td></tr>`;
        return;
    }

    // Create account map for username lookup
    const accountMap = {};
    accounts.forEach(acc => {
        accountMap[acc.a_id] = acc;
    });

    logs.forEach(log => {
        const acc = accountMap[log.a_id];
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${log.sl_id || log.log_id}</td>
            <td>${log.e_id}</td>
            <td>${log.a_id}</td>
            <td>${acc ? acc.username : "N/A"}</td>
            <td>${log.status}</td>
            <td>${log.login_timestamp}</td>
            <td>${log.logout_timestamp || "N/A"}</td>
        `;

        tbody.appendChild(row);
    });
}
*/
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

/* load session logs
function loadSessionLogs() {
    Promise.all([getSessionLogs(), getAccounts()])
        .then(([logData, accData]) => {
            const logs = Array.isArray(logData) ? logData : logData.data;
            const accounts = Array.isArray(accData) ? accData : accData.data;
            renderSessionLogs(logs, accounts);
        })
        .catch(err => {
            console.error("Error:", err);
            alert("Failed to load session logs");
        });
}
*/

// LOAD ON PAGE START
document.addEventListener("DOMContentLoaded", () => {
    loadTable();
    // loadSessionLogs();
});


// LOAD ON REFRESH CLICK
document.querySelector(".refresh").addEventListener("click", loadTable);
// document.querySelector(".refresh-logs").addEventListener("click", loadSessionLogs);