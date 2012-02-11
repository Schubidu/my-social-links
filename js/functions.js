(function () {
	var links = [], colors = null;

	function getStyle(el, styleProp) {
		var y, x = el;
		if (x.currentStyle)
			y = x.currentStyle[styleProp];
		else if (window.getComputedStyle)
			y = document.defaultView.getComputedStyle(x, null).getPropertyValue(styleProp);
		return y;
	}

	function getRGB(img) {
		var canvas = document.createElement('canvas');
		var ctx = canvas.getContext('2d');
		ctx.drawImage(img, 0, 0);
		var c = Array.prototype.slice.call(ctx.getImageData(2, 2, 1, 1).data);
		c.pop();
		return c;
	}


	if (window.addEventListener && document.querySelectorAll && Array.prototype.forEach) {
		window.addEventListener('load', function () {
			links = Array.prototype.slice.call(document.querySelectorAll('li a'));
			colors = new Array(links.length);
			var docElem = document.documentElement;
			console.debug(colors);
			links.forEach(function (link, i) {
				var imageUrl = getStyle(link, "background-image").replace(/^url\(["']?/, '').replace(/["']?\)$/, '');

				var img = new Image();
				img.src = imageUrl;
				img.addEventListener('load', function () {
					colors[i] = (getRGB(this));
				});
				link.addEventListener('mouseover', function () {
					var color = colors[i];
					if (color)
						docElem.style.backgroundColor = 'rgb(' + color.join(',') +')';
				});
				link.addEventListener('mouseout', function () {
					docElem.style.backgroundColor = '';
				});
			});
			document.documentElement.style.backgroundColor = '#f0f';
			document.documentElement.style.backgroundColor = '';
			//console.debug(document.style.backgroundColor = '#f0f');
		}, false);
	}
})()
