BX.ready(function(){

    let currentPage = 1,
        currentSort = 'UF_COURSE_DATE',
        currentDirection = 'asc';

    const container = document.querySelector('#currency-box');

    container.addEventListener('click', (evt) => {
        const page = evt.target.dataset.page;
        if (page) {
            currentPage = page;
            UpdateList();
        }
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
            container.innerHTML = response.data;
        }).catch(function (response) {
            console.log(response);
        })
    }

});

