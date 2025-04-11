document.addEventListener('DOMContentLoaded', () => {

    document.querySelector('#to-track').addEventListener('click', () => {
        document.querySelector('#place').style.animation = 'fadeOut 1s 0s ease-in-out 1 forwards running';
    });

    document.querySelector('#place').addEventListener('animationend', (event) => {
        if (event['animationName'] === 'fadeOut') {
            
            document.querySelector('#place').style.display = 'none';
            document.querySelector('#track').style.display = 'block';
            document.querySelector('#track').style.animation = 'fadeIn 1s 0s ease-in-out 1 forwards running';
        }
    });

    document.querySelector('#to-order').addEventListener('click', () => {
        document.querySelector('#track').style.animation = 'fadeOut 1s 0s ease-in-out 1 forwards running';
    });

    document.querySelector('#track').addEventListener('animationend', (event) => {
        if (event['animationName'] === 'fadeOut') {
            document.querySelector('#track').style.display = 'none';
            document.querySelector('#place').style.display = 'block';
            document.querySelector('#place').style.animation = 'fadeIn 1s 0s ease-in-out 1 forwards running';
        }
    });

})