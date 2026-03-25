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

if (button) {
    button.addEventListener("click", () => {
        if (loading) return;

        loading = true;
        page++;

        fetch(`/database/load-more-cars.php?page=${page}`)
            .then(response => response.text())
            .then(data => {
                if (data.trim() === "") {
                    button.style.display = "none";
                } else {
                    container.insertAdjacentHTML("beforeend", data);
                }
                loading = false;
            })
            .catch(error => {
                console.error("Error:", error);
                loading = false;
            });
    });
}