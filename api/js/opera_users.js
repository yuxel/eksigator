//get event from opera
window.opera.addEventListener('BeforeEventListener.load', function(ev){ 
    if(loaded) return false;
    whenPageLoaded();
    loaded = true;
}, false);

