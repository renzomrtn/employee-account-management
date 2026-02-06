// API URLS
const API = {
    create_e: "../backend/api/endpoints/employee/create.php",
    read_e: "../backend/api/endpoints/employee/read.php",
    delete_e: "../backend/api/endpoints/employee/delete.php",
    create_a: "../backend/api/endpoints/account/create.php",
    read_a: "../backend/api/endpoints/account/read.php",
    delete_a: "../backend/api/endpoints/account/delete.php",
    create_sl: "../backend/api/endpoints/session_log/create.php",
    read_sl: "../backend/api/endpoints/session_log/read.php",
    delete_sl: "../backend/api/endpoints/session_log/delete.php"
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

// DELETE
function deleteAccount(a_id) {
    return fetch(API.delete_a, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ a_id: a_id })
    })
    .then(res => {
        if (!res.ok) {
            throw new Error("Server error while deleting account");
        }
        return res.json();
    });
}

function deleteEmployee(e_id) {
    return fetch(API.delete_e, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ e_id: e_id })
    })
    .then(res => {
        if (!res.ok) {
            throw new Error("Server error while deleting employee");
        }
        return res.json();
    });
}

function deleteSessionLog(sl_id) {
    return fetch(API.delete_sl, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ sl_id: sl_id })
    })
    .then(res => {
        if (!res.ok) {
            throw new Error("Server error while deleting session log");
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
        tbody.innerHTML = `<tr><td colspan="8">No records found</td></tr>`;
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
            <td>${acc ? acc.created_at : "N/A"}</td>
            <td><button class="delete-btn" data-eid="${emp.e_id}" data-aid="${acc ? acc.a_id : ''}" data-name="${emp.name}">Delete</button></td>
        `;

        tbody.appendChild(row);
    });

    // Add event listeners to all delete buttons
    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.addEventListener("click", handleDelete);
    });
}

// DELETE HANDLER for employee-account
function handleDelete(e) {
    const button = e.target;
    const e_id = button.getAttribute("data-eid");
    const a_id = button.getAttribute("data-aid");
    const name = button.getAttribute("data-name");

    if (!confirm(`Are you sure you want to delete ${name} and their associated account?`)) {
        return;
    }

    // Delete account first, then employee
    let deletePromise = Promise.resolve();

    if (a_id) {
        deletePromise = deleteAccount(a_id)
            .then(response => {
                if (!response.success) {
                    throw new Error(response.message || "Failed to delete account");
                }
                console.log("Account deleted successfully");
            });
    }

    deletePromise
        .then(() => deleteEmployee(e_id))
        .then(response => {
            if (response.success) {
                alert("Employee and account deleted successfully");
                loadTable();
            } else {
                throw new Error(response.message || "Failed to delete employee");
            }
        })
        .catch(err => {
            console.error(err);
            alert(err.message || "Failed to delete employee/account");
        });
}

function renderSessionLogs(logs, accounts) {
    const tbody = document.querySelector("#session-log-data tbody");
    tbody.innerHTML = "";

    if (logs.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6">No logs found</td></tr>`;
        return;
    }

    const accountMap = {};
    accounts.forEach(acc => {
        accountMap[acc.a_id] = acc;
    });

    logs.forEach(log => {
        const acc = accountMap[log.a_id];
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${log.sl_id || log.log_id}</td>
            <td>${log.a_id}</td>
            <td>${acc ? acc.username : "N/A"}</td>
            <td>${log.status}</td>
            <td>${log.login_time}</td>
            <td><button class="delete-log-btn" data-slid="${log.sl_id || log.log_id}">Delete</button></td>
        `;

        tbody.appendChild(row);
    });

    // Add event listeners to all delete log buttons
    document.querySelectorAll(".delete-log-btn").forEach(btn => {
        btn.addEventListener("click", handleDeleteLog);
    });
}

// DELETE HANDLER for session logs
function handleDeleteLog(e) {
    const button = e.target;
    const sl_id = button.getAttribute("data-slid");

    if (!confirm(`Are you sure you want to delete this log entry?`)) {
        return;
    }

    deleteSessionLog(sl_id)
        .then(response => {
            if (response.success) {
                alert("Log deleted successfully");
                loadSessionLogs();
            } else {
                throw new Error(response.message || "Failed to delete log");
            }
        })
        .catch(err => {
            console.error(err);
            alert(err.message || "Failed to delete session log");
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

// LOAD ON PAGE START
document.addEventListener("DOMContentLoaded", () => {
    loadTable();
    loadSessionLogs();
});

// LOAD ON REFRESH CLICK
document.querySelectorAll(".refresh").forEach(btn => {
    btn.addEventListener("click", () => {
        loadTable();
        loadSessionLogs();
    });
});