const accountImage = document.querySelector('.account img');
if (accountImage) {
    accountImage.addEventListener('click', function () {
        const account = this.closest('.account');
        account.classList.toggle('active');
    });

    document.addEventListener('click', function (e) {
        const account = document.querySelector('.account');
        if (account && !account.contains(e.target)) {
            account.classList.remove('active');
        }
    });
}

const startButton = document.querySelector('.button-primary');
if (startButton) {
    startButton.addEventListener('click', function(e) {
        e.preventDefault();
        const modal = document.getElementById('loginModal');
        if (modal) modal.classList.remove('hidden');
    });
}

const modalClose = document.querySelector('.modal-close');
if (modalClose) {
    modalClose.addEventListener('click', function () {
        const modal = document.getElementById('loginModal');
        if (modal) modal.classList.add('hidden');
    });
}

const modal = document.getElementById('loginModal');
if (modal) {
    modal.addEventListener('click', function (e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
}

let page = 1;
const button = document.getElementById("loadMoreBtn");
const container = document.getElementById("cars-container");

let loading = false;

let page = 2;
const limit = 10;
const totalCars = <?= (int)$totalCars ?>;   //Er is geen fouten
let loadedCars = <?= count($cars) ?>;       //En hier ook

const loadMoreBtn = document.getElementById('loadMoreBtn');

if (loadMoreBtn) {

    if (loadedCars >= totalCars) {
        loadMoreBtn.style.display = 'none';
    }

    loadMoreBtn.addEventListener('click', () => {
        fetch(`/database/load-more-cars.php?page=${page}&q=<?= urlencode($search) ?>`)
            .then(res => res.text())
            .then(data => {
                if (!data.trim()) {
                    loadMoreBtn.style.display = 'none';
                    return;
                }

                document
                    .getElementById('cars-container')
                    .insertAdjacentHTML('beforeend', data);

                page++;
                loadedCars += limit;

                if (loadedCars >= totalCars) {
                    loadMoreBtn.style.display = 'none';
                }
            })
            .catch(console.error);
    });
}