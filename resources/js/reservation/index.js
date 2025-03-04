const elementButtonAddCoBorrower = document.getElementById('btn-add_co_borrower');
const elementDeleteBorrower = document.getElementById('delete_borrower');

/**
 * добавление формы для заполнения "созаёмщика"
 */
elementButtonAddCoBorrower.addEventListener('click', function () {
    document.querySelector('.co-borrower__card-body').style.display = 'block';
});

/**
 * удаление формы "созаёмщика" + очищение полей
 */
elementDeleteBorrower.addEventListener('click', function () {
    document.querySelector('.co-borrower__card-body').style.display = 'none';
    // TODO: очистить все поля
});
