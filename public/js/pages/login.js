$(document).ready(function(){
    console.log('...');

    initGuide();
});


//GUIDE FOR THIS PAGE
function initGuide() {
    if (getStorage('guide-login') != 1) {
        startGuide();
    }
}

function startGuide() {
    var intro = introJs();
    intro.setOptions({
        steps: [{
                element: '.step1',
                intro: 'This is the area where you place your credential in order to login in the system.',
                position: 'right'
            },
            {
                element: '.step2',
                intro: 'If you have no account yet. Click here to register for free!',
                position: 'left'
            },
            {
                element: '.step3',
                intro: 'Click here to return at the home page.',
                position: 'left'
            },
        ]
    });

    intro.onexit(function () {
        setStorage('guide-login', 1);
        console.log('exit');
    });
    intro.start();
}