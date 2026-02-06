document.addEventListener('DOMContentLoaded', function () {

    // ---------- DELETE MODAL ACTION ----------
    document.querySelectorAll('[data-bs-target="#globalDeleteModal"]').forEach(button => {
        button.addEventListener('click', function () {
            const action = this.getAttribute('data-action');
            const form = document.getElementById('globalDeleteForm');
            if (form) form.setAttribute('action', action);
        });
    });

    // ---------- SUCCESS ALERT SLIDE-IN + AUTO HIDE ----------
    const successAlert = document.getElementById('successAlert');
    if (successAlert) {
        // Trigger slide-in + fade-in after short delay
        setTimeout(() => {
            successAlert.classList.add('show');
        }, 50); // small delay ensures transition triggers

        // Auto-hide after 3 seconds
        setTimeout(() => {
            bootstrap.Alert.getOrCreateInstance(successAlert).close();
        }, 3000);
    }

        // ---------- ERROR ALERT SLIDE-IN + AUTO HIDE ----------
    const errorAlert = document.getElementById('errorAlert');
    if (errorAlert) {
        setTimeout(() => errorAlert.classList.add('show'), 50); // trigger slide-in
        setTimeout(() => bootstrap.Alert.getOrCreateInstance(errorAlert).close(), 5000); // auto-hide after 5s
    }


});
