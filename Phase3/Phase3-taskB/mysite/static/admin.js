// Helper function to send fetch request
function handleFormSubmit(formId, url) {
    document.getElementById(formId).addEventListener('submit', function(event) {
        event.preventDefault();

        // Create FormData object to send form fields if needed
        const formData = new FormData(this);

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            document.querySelector('.results-output').innerHTML = data;
        })
        .catch(error => {
            console.error('Error:', error);
            document.querySelector('.results-output').innerHTML = "Failed to load data.";
        });
    });
}

// Connect forms to their PHP scripts
handleFormSubmit('viewAllTransactionsForm', '../php/getAllTransactions.php');
handleFormSubmit('viewTransactionForm', '../php/getTransaction.php');
handleFormSubmit('viewAllMealsForm', '../php/getAllMeals.php');
handleFormSubmit('updateMealForm', '../php/updateMeal.php');
handleFormSubmit('addMealForm', '../php/addMeal.php');
handleFormSubmit('removeMealForm', '../php/removeMeal.php');
