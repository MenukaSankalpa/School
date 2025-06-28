
document.addEventListener("DOMContentLoaded", function () {
    const container = document.querySelector('.container');
    const registerBtn = document.querySelector('.register-btn');
    const loginBtn = document.querySelector('.login-btn');
    const roleSelect = document.getElementById('role');
    const childNameBox = document.getElementById('child-name-box');

    registerBtn.addEventListener('click', () => {
        container.classList.add('active');
    });

    loginBtn.addEventListener('click', () => {
        container.classList.remove('active');
    });

    roleSelect.addEventListener('change', () => {
        if (roleSelect.value === '1') {
            childNameBox.style.display = 'block';
            childNameBox.querySelector('input').required = true;
        } else {
            childNameBox.style.display = 'none';
            childNameBox.querySelector('input').required = false;
        }
    });

    // Trigger change on load to handle default selection
    roleSelect.dispatchEvent(new Event('change'));
});
