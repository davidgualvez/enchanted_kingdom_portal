$(document).ready(function(){
    console.log('loaded...');
    
    

    $('#btnPrev').on('click', function(){
        console.log('prev...');
        $('.shape').shape('flip left');
    });

    $('#btnNext').on('click', function(){
        console.log('next...');
        $('.shape').shape('flip right');
    });
    
    // initGuide();
}); 


//GUIDE FOR THIS PAGE
function initGuide(){
    if (getStorage('guide-home') != 1) {
        startGuide();
    }
}

function startGuide() {
    var intro = introJs();
    intro.setOptions({
        steps: [ 
            {
                element: '#step1',
                intro: 'Click here to Login!'
            }, 
            {
                element: '#step2',
                intro: 'To create an account. Click here',
                position: 'left'
            },
            {
                element: '#step3',
                intro: 'Want to see the whole map of Enchanted Kingdom? Click here.'
            },
            {
                element: '#step4',
                intro: 'Want to see all the items from the store? Click here.'
            },
            {
                element: '#step5',
                intro: 'To see all the activities and services. Click here'
            }
        ]
    });

    intro.onexit(function () {
        setStorage('guide-home',1);
        console.log('exit');
    }); 
    intro.start();
}