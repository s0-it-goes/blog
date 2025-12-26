
document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.themeButton');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const theme = btn.dataset.theme;

            fetch('/ajax/theme', {
                method: 'POST',
                headers : {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'theme=' +encodeURIComponent(theme)
            })
        
        .then(response => response.text())
        .then(theme => {
            document.body.classList.remove('light', 'dark');
            document.body.classList.add(theme);
        })
        .catch(err => console.error(err));
        });
    });
});
