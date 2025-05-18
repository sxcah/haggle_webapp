document.getElementById('admin-login-form').addEventListener('submit', function(e) {
    e.preventDefault();

    // Clear previous errors
    document.getElementById('general-error').textContent = '';
    document.getElementById('username-error').textContent = '';
    document.getElementById('password-error').textContent = '';

    const username = document.getElementById('admin-username').value.trim();
    const password = document.getElementById('admin-password').value.trim();

    let hasError = false;

    if (!username) {
        document.getElementById('username-error').textContent = 'Username is required.';
        hasError = true;
    }
    if (!password) {
        document.getElementById('password-error').textContent = 'Password is required.';
        hasError = true;
    }
    if (hasError) return;

    fetch('../database/login-admin.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({username, password})
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'admin-dashboard.html';
        } else {
            document.getElementById('general-error').textContent = data.error || 'Login failed.';
        }
    })
    .catch(() => {
        document.getElementById('general-error').textContent = 'Network error. Please try again.';
    });
});