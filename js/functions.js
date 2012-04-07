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

    function getRGB(img) {
        var canvas = doc.createElement('canvas');
        var ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0);
        var c = Array.prototype.slice.call(ctx.getImageData(2, 2, 1, 1).data);
        c.pop();
        return c;
    }


    if (window.addEventListener && document.querySelectorAll && Array.prototype.forEach) {
        window.addEventListener('load', function () {
            lis = Array.prototype.slice.call(document.querySelectorAll('li'));
            colors = new Array(lis.length);
            var docElem = doc.documentElement;
            lis.forEach(function (li, i) {
                var imageUrl = getStyle(li.querySelectorAll('a')[0], "background-image").replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
				var a = li.querySelectorAll('a')[0];
                var img = new Image();
                img.src = imageUrl;
                img.addEventListener('load', function () {
                    colors[i] = (getRGB(this));
                });
                a.addEventListener('mouseover', function () {
                    var color = colors[i];
                    if (color)
                        docElem.style.backgroundColor = 'rgb(' + color.join(',') + ')';
                });
                a.addEventListener('mouseout', function () {
                    docElem.style.backgroundColor = '';
                });
            });
        }, false);
    }
})(window, document);
