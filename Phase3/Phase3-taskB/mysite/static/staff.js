document.addEventListener('DOMContentLoaded', () => {


    document.querySelector('#to-meal-update').addEventListener('click', () => {
        document.querySelector('#order-update').style.animation = 'fadeOut 1s 0s ease-in-out 1 forwards running';
    });

    document.querySelector('#order-update').addEventListener('animationend', (event) => {
        if (event['animationName'] === 'fadeOut') {
            
            document.querySelector('#order-update').style.display = 'none';
            document.querySelector('#meal-update').style.display = 'block';
            document.querySelector('#meal-update').style.animation = 'fadeIn 1s 0s ease-in-out 1 forwards running';
        }
    });

    document.querySelector('#to-order-update').addEventListener('click', () => {
        document.querySelector('#meal-update').style.animation = 'fadeOut 1s 0s ease-in-out 1 forwards running';
    });

    document.querySelector('#meal-update').addEventListener('animationend', (event) => {
        if (event['animationName'] === 'fadeOut') {
            document.querySelector('#meal-update').style.display = 'none';
            document.querySelector('#order-update').style.display = 'block';
            document.querySelector('#order-update').style.animation = 'fadeIn 1s 0s ease-in-out 1 forwards running';
        }
    });

})