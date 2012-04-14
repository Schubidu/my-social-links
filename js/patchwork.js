/**
 * Created with JetBrains PhpStorm.
 * User: Stefan
 * Date: 14.04.12
 * Time: 23:04
 * To change this template use File | Settings | File Templates.
 */
(function () {
	var sizes = [
		{
			name:"apple-touch-icon",
			size:57
		},
		{
			name:"apple-touch-icon-114",
			size:114
		},
		{
			name:"apple-touch-icon-72",
			size:72
		},
		{
			name:"apple-touch-icon-144",
			size:144

		},
		{
			name:"favicon",
			type:"vnd.microsoft.icon",
			suffix:'ico',
			size:16

		}
	];

	function sendPatchwork(callback, dataUrl, item) {
		var patchWorkImage = new Image(), _canvas = document.createElement('canvas'), patchworkCtx = _canvas.getContext('2d'),
			maxDim = item.size, fileName = item.name, fileType = (item.type) ? item.type : 'png', fileSuffix = (item.suffix) ? item.suffix : 'png';
		_canvas.width = _canvas.height = maxDim;

		patchWorkImage.addEventListener('load', function () {
			patchworkCtx.drawImage(patchWorkImage, 0, 0, maxDim, maxDim);

			var patchWorkData = _canvas.toDataURL('image/' + fileType);

//			document.documentElement.appendChild(_canvas);

			var formData = new FormData();

			formData.append("fileName", [fileName, fileSuffix].join('.'));
			formData.append("fileType", fileType);
			formData.append("patchwork", patchWorkData.substr(("data:image/" + fileType + ";base64,").length));

			var oXHR = new XMLHttpRequest();
			oXHR.open("POST", "");
			oXHR.onreadystatechange = function () {
				if (this.readyState == 4) {
					var responseImage = new Image();
					responseImage.src = this.responseText;
					document.documentElement.appendChild(responseImage);

					callback(this.responseText);
				}
			};
			oXHR.send(formData);

		});

		patchWorkImage.src = dataUrl;
		patchWorkImage.width = patchWorkImage.height = maxDim;

	}

	/**
	 *
	 * @param collection
	 * @param defaultIcon for example "/images/512x512/00.png";

	 */
	window.patchwork = function(collection, defaultIcon) {
		var maxDimension = 0;
		var images = [];
		var canvas = document.createElement('canvas');
		canvas.width = canvas.height = 0;
		var ctx = canvas.getContext('2d');
		var allImagesLoaded = 0, allPatchworkSended = 0;
		collection.forEach(function (name, collPos) {
			var sqrt = Math.sqrt(collection.length);
			var img = new Image();
			img.addEventListener('load', function () {
				var mod = collPos % sqrt, myWidth = this.width;
				var calcPos = collPos / sqrt, yPos = Math.floor(calcPos), xPos = Math.round((calcPos - yPos) * sqrt);
				if (canvas.width == 0) {
					canvas.width = canvas.height = myWidth * sqrt;
					maxDimension = myWidth;
				}
				ctx.drawImage(this, xPos * myWidth, yPos * myWidth);
				allImagesLoaded++;
				if (allImagesLoaded == collection.length) {
					sizes.forEach(function (sizeItem) {
						sendPatchwork(function () {
							allPatchworkSended++;
							if (allPatchworkSended == sizes.length) {
								location.href = function(href){
									return href.substring(0, href.indexOf('?'));
								}(location.href)
							}
						}, canvas.toDataURL(), sizeItem);
					});
				}
			});
			img.src = (defaultIcon).replace('00', name);
		});
	}
})();
