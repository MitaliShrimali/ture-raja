document.addEventListener('DOMContentLoaded', function() {
    const signInForm = document.getElementById('signInForm');
    const editPackageForm = document.getElementById('editPackageForm');

    if (signInForm) {
        signInForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            const password = this.querySelector('input[type="password"]').value;

            if (email && password.length >= 8) {
                toastr.success('Signed in successfully! Redirecting...');
                setTimeout(() => {
                    window.location.href = 'pages/dashboard.php';
                }, 1500);
            } else {
                toastr.error('Please enter valid credentials.');
            }
        });
    }

    if (editPackageForm) {
        editPackageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Success!',
                text: 'Package details saved successfully.',
                icon: 'success',
                confirmButtonColor: '#F0642F'
            });
        });
    }
});
