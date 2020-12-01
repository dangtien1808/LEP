$(document).ready(function(){
    $('.img-review').each((k, v) => {
        let el = $(v);
        animateDiv(el);
    })
    
});

function makeNewPosition(){
    
    var h = $(window).height() - 50;
    var w = $(window).width() - 50;
    
    var nh = Math.floor(Math.random() * h);
    var nw = Math.floor(Math.random() * w);
    
    return [nh,nw];    
    
}

function animateDiv(element, option = {opacity: 1, height:500, width:450}, last = false){
    let new_option = {
        opacity: option.opacity - 0.1, 
        height: option.height - 25, 
        width: option.width - 25,
    }
    if(last) {
        $(element).animate({ top: 50, left: 50, opacity: 0.9, width: option.width, height: option.height}, 500);
        return
    }
    if (option.opacity <= 0.3) {
        animateDiv(element, new_option, true);
        return;
    }
    var newq = makeNewPosition();
    $(element).animate({ top: newq[0], left: newq[1], opacity: option.opacity, width: option.width, height: option.height}, 1000,function(){
        animateDiv(element, new_option);
    });
    
};
