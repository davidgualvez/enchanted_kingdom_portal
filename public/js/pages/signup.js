$(document).ready(function(){ 
    initGuide();
});

//GUIDE FOR THIS PAGE
function initGuide() {
    if (getStorage('guide-signup') != 1) {
        startGuide();
    }
}

function startGuide() {
    var intro = introJs();
    intro.setOptions({
        steps: [{
                element: '.step1',
                intro: 'To register. Just fillup the given fields and click signup button',
                position: 'right'
            },
            {
                element: '.step2',
                intro: 'Want to go back in the login page? click here',
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
        setStorage('guide-signup', 1);
        console.log('exit');
    });
    intro.start();
}
 