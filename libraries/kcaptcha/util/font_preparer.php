<?

# preparing PNG fonts to use with KCAPTCHA.
# reads files from folder "../fonts0", scans for symbols ans spaces and writes new font file with cached symbols positions to filder "../fonts"

# comment or remove next line for using (commented for security reason):
exit();

if ($handle = opendir('../fonts0')) {
    while (false !== ($file = readdir($handle))) {
        if ($file == "." || $file == "..") {
        	continue;
        }

        $img=imagecreatefrompng('../fonts0/'.$file);
        imageAlphaBlending($img, false);
		imageSaveAlpha($img, true);
        $transparent=imagecolorallocatealpha($img,255,255,255,127);
        $white=imagecolorallocate($img,255,255,255);
        $black=imagecolorallocate($img,0,0,0);
        $gray=imagecolorallocate($img,100,100,100);

        for($x=0;$x<imagesx($img);$x++){
        	$space=true;
        	$column_opacity=0;
        	for($y=1;$y<imagesy($img);$y++){
        		$rgb = ImageColorAt($img, $x, $y);
        		$opacity=$rgb>>24;
        		if($opacity!=127){
        			$space=false;
        		}
        		$column_opacity+=127-$opacity;
        	}
        	if(!$space){
        		imageline($img,$x,0,$x,0,$column_opacity<200?$gray:$black);
        	}
        }
        imagepng($img,'../fonts/'.$file);
    }
    closedir($handle);
}
