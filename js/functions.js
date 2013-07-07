(function (win, doc) {
    var lis = [], colors = null;

    function getStyle(el, styleProp) {
        var y, x = el;
        if (x.currentStyle)
            y = x.currentStyle[styleProp];
        else if (window.getComputedStyle)
            y = doc.defaultView.getComputedStyle(x, null).getPropertyValue(styleProp);
        return y;
    }

    if (window.addEventListener && document.querySelectorAll && Array.prototype.forEach) {
        window.addEventListener('load', function () {
            lis = Array.prototype.slice.call(document.querySelectorAll('li'));
            colors = new Array(lis.length);
            var docElem = doc.documentElement;
            docElem.className = docElem.className.replace(/\bno-js\b/,'js');
            lis.forEach(function (li, i) {
                var a = li.querySelectorAll('a')[0];
                colors[i] = getStyle(a, "background-color");
                a.addEventListener('mouseover', function () {
                    var color = colors[i];
                    if (color) {
                        docElem.style.backgroundColor = color;
                        docElem.className += ' chosen'
                    }
                });
                a.addEventListener('mouseout', function () {
                    docElem.style.backgroundColor = '';
                    docElem.className = docElem.className.replace(/\bchosen\b/,'');

                });
            });
        }, false);
    }
})(window, document);
