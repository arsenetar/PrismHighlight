// jQuery.ready() fires when still at 'interactive' which is does not work for this script
document.onreadystatechange = function () {
    if (document.readyState == "complete") {
        Prism.highlightAll();
    }
}