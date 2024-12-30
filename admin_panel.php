<?php
session_start();
include 'db.php'; // Ensure your database connection is correct

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login_test.html"); // Redirect to login page if not logged in
    exit();
}

// Check if user is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "Access denied. You do not have permission to view this page.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        .logout {
            color: red;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <h1>Welcome, Admin</h1>
    <h2>Statistics</h2>
    <p>Total Users: <span id="totalUsers">Loading...</span></p>
    <p>Total Posts: <span id="totalPosts">Loading...</span></p>

    <h2>Admin Actions</h2>
    <ul>
        <li><a href="admin_panel.php">Manage Users</a></li>
        <li><a href="view_logs.php">View Logs</a></li>
        <li><a href="content_management.php">Content Management</a></li>
        <li><a href="logout.php" class="logout">Logout</a></li>
    </ul>

    <h2>Users Management</h2>
    <!-- Add User Form -->
    <form id="addUserForm">
        <h3>Add User</h3>
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <select name="role" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select><br>
        <button type="submit">Add User</button>
    </form>

    <br>

    <!-- Users List -->
    <h2>Users List</h2>
    <table id="usersTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Users will be dynamically loaded here -->
        </tbody>
    </table>

    <script>
        // Fetch and display users
        function fetchUsers() {
            fetch('admin_read.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const usersTable = document.getElementById('usersTable').querySelector('tbody');
                        usersTable.innerHTML = '';
                        data.users.forEach(user => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${user.id}</td>
                                <td>${user.username}</td>
                                <td>${user.role}</td>
                                <td>
                                    <button onclick="deleteUser(${user.id})">Delete</button>
                                    <button onclick="updateUser(${user.id}, '${user.username}', '${user.role}')">Edit</button>
                                </td>
                            `;
                            usersTable.appendChild(row);
                        });
                    } else {
                        alert(data.message);
                    }
                });
        }

        // Fetch statistics
        function fetchStatistics() {
            fetch('admin_statistics.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('totalUsers').textContent = data.totalUsers;
                        document.getElementById('totalPosts').textContent = data.totalPosts;
                    } else {
                        alert(data.message);
                    }
                });
        }

        // Add User
        document.getElementById('addUserForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('admin_add.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) fetchUsers(); // Refresh the users list
                });
        });

        // Delete User
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch('admin_delete.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `id=${id}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.success) fetchUsers(); // Refresh the users list
                    });
            }
        }

        // Update User
        function updateUser(id, username, role) {
            const newRole = prompt('Enter new role for the user (user/admin):', role);
            if (newRole) {
                fetch('admin_update.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `id=${id}&role=${newRole}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.success) fetchUsers(); // Refresh the users list
                    });
            }
        }

        // Initial Fetch
        fetchUsers();
        fetchStatistics();
    </script>
</body>

</html>
