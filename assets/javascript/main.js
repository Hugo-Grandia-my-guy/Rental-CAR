
const account = document.querySelector('.account');

if (account) {
    const accountImage = account.querySelector('img');

    accountImage.addEventListener('click', function (e) {
        e.stopPropagation();
        account.classList.toggle('active');
    });

    document.addEventListener('click', function (e) {
        if (!account.contains(e.target)) {
            account.classList.remove('active');
        }
    });
}


const modal = document.getElementById('loginModal');
const openButtons = document.querySelectorAll('[data-open-login]');
const closeButton = document.querySelector('.modal-close');

openButtons.forEach(btn => {
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    });
});

if (closeButton) {
    closeButton.addEventListener('click', function () {
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });
}

if (modal) {
    modal.addEventListener('click', function (e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
});