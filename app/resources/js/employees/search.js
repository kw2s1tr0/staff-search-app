const searchForm = document.querySelector('#employee-search-form');
const clearButton = document.querySelector('[data-employee-search-clear]');

if (searchForm instanceof HTMLFormElement && clearButton instanceof HTMLButtonElement) {
    clearButton.addEventListener('click', () => {
        const keywordInput = searchForm.elements.namedItem('keyword');
        const departmentSelect = searchForm.elements.namedItem('department_id');
        const positionSelect = searchForm.elements.namedItem('position_id');
        const employmentStatusSelect = searchForm.elements.namedItem('employment_status');

        if (keywordInput instanceof HTMLInputElement) {
            keywordInput.value = '';
            keywordInput.focus();
        }

        if (departmentSelect instanceof HTMLSelectElement) {
            departmentSelect.value = '';
        }

        if (positionSelect instanceof HTMLSelectElement) {
            positionSelect.value = '';
        }

        if (employmentStatusSelect instanceof HTMLSelectElement) {
            employmentStatusSelect.value = '';
        }
    });
}
