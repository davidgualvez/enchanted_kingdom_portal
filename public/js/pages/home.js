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
}); 
 
 