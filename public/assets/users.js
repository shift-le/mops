document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
            
            submitButtons.forEach(button => {
                // 無効化
                button.disabled = true;
            });
        });
    });
});

