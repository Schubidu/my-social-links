<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Stefan
 * Date: 01.04.12
 * Time: 12:12
 * To change this template use File | Settings | File Templates.
 */
session_start();
function __autoload($class_name)
{
	$file = 'classes/' . $class_name . '.php';
	if (file_exists($file)) {
		require_once($file);
	} else {
		throw new Exception("Unable to load $class_name.");
	}
}

$collection = SocialLinkCollection::initFromHtaccess();

if(isset($_SESSION['myid']) && isset($_REQUEST['patchwork'])){
	@header("Content-Type:text/plain");

	$image = base64_decode($_REQUEST['patchwork']);
	$fileName = 'images/' . $_REQUEST['fileName'];
	file_put_contents($fileName, $image);
	echo $fileName;
	exit;
}

$_SESSION['myid'] = '001';

@header("Content-Type:text/html;charset=utf-8");
?>

<script>
	(function(collection){
		var sizes = [
			{
				name: "apple-touch-icon",
				size: 57
			},
			{
				name: "apple-touch-icon-114",
				size: 114
			},
			{
				name: "apple-touch-icon-72",
				size: 72
			},
			{
				name: "apple-touch-icon-144",
				size: 144

			},
			{
				name: "favicon",
				type: "vnd.microsoft.icon",
				suffix: 'ico',
				size: 16

			}
		];

		function sendPatchwork(callback, dataUrl, item){
			var patchWorkImage = new Image(), _canvas = document.createElement('canvas'), patchworkCtx= _canvas.getContext('2d'),
				maxDim = item.size, fileName = item.name, fileType = (item.type) ? item.type : 'png', fileSuffix = (item.suffix) ? item.suffix : 'png';
			_canvas.width = _canvas.height = maxDim;

			patchWorkImage.addEventListener('load', function () {
				patchworkCtx.drawImage(patchWorkImage, 0, 0, maxDim, maxDim);

				var patchWorkData = _canvas.toDataURL('image/' + fileType);
				document.documentElement.appendChild(_canvas);

				var formData = new FormData();

				formData.append("fileName", [fileName,fileSuffix].join('.'));
				formData.append("fileType", fileType);
				formData.append("patchwork", patchWorkData.substr(("data:image/" + fileType + ";base64,").length));

				var oXHR = new XMLHttpRequest();
				oXHR.open("POST", "<?php echo $_SERVER["PHP_SELF"]; ?>");
				oXHR.onreadystatechange = function(){
					if (this.readyState == 4) {
						callback(this.responseText);
					}
				};
				oXHR.send(formData);

			});
			patchWorkImage.src = dataUrl;
			patchWorkImage.width = patchWorkImage.height = maxDim;

		}

		var sqrt = Math.sqrt(collection.length);
		var defaultIcon = "/images/512x512/00.png";
		var maxDimension = 0;
		var images = [];
		var canvas = document.createElement('canvas');
		canvas.width = canvas.height = 0;
		var ctx = canvas.getContext('2d');
		var allImagesLoaded = 0, allPatchworkSended = 0;
		collection.forEach(function(name, collPos){
			var img = new Image();
			img.addEventListener('load', function () {
				var mod = collPos%sqrt, myWidth = this.width;
				var calcPos = collPos/sqrt, yPos = Math.floor(calcPos), xPos = Math.round((calcPos-yPos) * sqrt);
				if(canvas.width == 0){
					canvas.width = canvas.height = myWidth * sqrt;
					maxDimension = myWidth;
				}
				ctx.drawImage(this, xPos * myWidth, yPos *myWidth);
				allImagesLoaded++;
				if(allImagesLoaded == collection.length){
					sizes.forEach(function(sizeItem){
						sendPatchwork(function(){
							allPatchworkSended++;
							if(allPatchworkSended == sizes.length){
								var href = parent.document.location.href;
								parent.document.location.href = href.substring(0, href.indexOf('?'));
							}
						},canvas.toDataURL(), sizeItem.size, sizeItem.name, sizeItem.type);
					});
				}
			});
			img.src = (defaultIcon).replace('00', name);
		});
	})(<?php echo json_encode($collection->toIcons()); ?>);
</script>

