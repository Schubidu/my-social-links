<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Stefan
 * Date: 01.04.12
 * Time: 12:12
 * To change this template use File | Settings | File Templates.
 */

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

if(isset($_REQUEST['patchwork'])){
	@header("Content-Type:text/plain");

	$image = base64_decode($_REQUEST['patchwork']);
	$fileName = 'collection-' . $_REQUEST['maxDimension'] . '.png';
	file_put_contents($fileName, $image);
	echo $fileName;
	exit;
}

@header("Content-Type:text/html;charset=utf-8");
?>

<script>
	(function(collection){
		function sendPatchwork(dataUrl, maxDimension){
			var patchWorkImage = new Image(), patchwork = document.createElement('canvas'), patchworkCtx= patchwork.getContext('2d');
			patchwork.width = patchwork.height = maxDimension;

			patchWorkImage.src = dataUrl;
			patchWorkImage.width = patchWorkImage.height = maxDimension;
			console.debug(patchWorkImage);

			patchworkCtx.drawImage(patchWorkImage, 0, 0, maxDimension, maxDimension);

			var patchWorkData = patchwork.toDataURL();

			var formData = new FormData();

			formData.append("maxDimension", maxDimension);
			formData.append("patchwork", patchWorkData.substr("data:image/png;base64,".length));

			var oXHR = new XMLHttpRequest();
			oXHR.open("POST", "<?php echo $_SERVER["PHP_SELF"]; ?>");
			oXHR.onreadystatechange = function(){
				if (this.readyState == 4) {
					console.debug(this, arguments);
				}
			};
			oXHR.send(formData);
		}

		var sqrt = Math.sqrt(collection.length);
		var defaultIcon = "/images/512x512/00.png";
		var maxDimension = 0;
		var images = [];
		var canvas = document.createElement('canvas');
		canvas.width = canvas.height = 0;
		var ctx = canvas.getContext('2d');
		var allImagesLoaded = 0;
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
					sendPatchwork(canvas.toDataURL(), maxDimension);
				}
			});
			img.src = (defaultIcon).replace('00', name);
		});
	})(<?php echo json_encode($collection->toIcons()); ?>);
</script>

