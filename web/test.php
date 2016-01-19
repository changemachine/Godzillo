<?php
//Header("content-type: application/x-javascript");

$IMGPATH = "imgs/";

function returnimages($dirname="."){
	$pattern="\.(jpg|jpeg|png|gif|bmp)$";
	$files = array();
	$curimage=0;
	if ($handle = opendir($dirname)){
		while(false !== ($file = readdir($handle))){
			if(eregi($pattern, $file)){
				echo 'galleryarray[' . $curimage .']=["' . $file . '"];' . "\n";
				$curimage++;
			}
		}

		closedir($handle);
	}
	return($files);
}
?>
<html>
<head>

<script type="text/javascript">
var galleryarray=new Array();
<?php echo implode("\n", returnimages($IMGPATH)); ?>
var curimg=0;
function rotateimages () {
	document.getElementById("slideshow").setAttribute ("src", "<?php echo $IMGPATH; ?>"+galleryarray[curimg])
	curimg = (curimg < galleryarray.length-1) ? curimg+1 : 0
}
window.onload = function() {
	setInterval("rotateimages()", 2500)
}
</script>

</head>
<body>

<img src="#" id="slideshow" />

</body>
</html>
