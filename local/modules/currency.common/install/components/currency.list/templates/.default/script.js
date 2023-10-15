BX.ready(function () {

    let currentPage = 1,
        currentSort = 'date',
        currentDirection = 'asc';

    const curContainer = document.getElementById('currency-box'),
        curMain = document.getElementById('currency-main'),
        btnSortBy = document.getElementById('currency-sort-by'),
        btnSortDirection = document.getElementById('currency-sort-direction');

    curContainer.addEventListener('click', (evt) => {
        const page = evt.target.dataset.page;
        if (page) {
            currentPage = page;
            UpdateList();
        }
    });

    btnSortBy.addEventListener('change', (evt) => {
        currentSort = btnSortBy.value;
        UpdateList();
    });

    btnSortDirection.addEventListener('change', (evt) => {
        currentDirection = btnSortDirection.value;
        UpdateList();
    });

    function UpdateList() {
        BX.ajax.runComponentAction('currency.common:currency.list', 'ajaxUpdateList', {
            mode: 'class',
            data: {
                page: currentPage,
                sort: currentSort,
                direction: currentDirection,
            },
        }).then(function (response) {
            curMain.innerHTML = response.data;
        }).catch(function (response) {
            console.log(response);
        })
    }

});

