<?php 
	$swfaddress = plugins_url( 'assets/js/swfaddress.js' , __FILE__ ); 
//	$KorsakowPlayer = plugins_url( 'assets/KorsakowPlayer.swf' , __FILE__ ); 
	$KorsakowPlayer = $kpath . 'data/KorsakowPlayer.swf'; 
	$expressInstall = plugins_url( 'assets/swf/expressInstall.swf' , __FILE__ ); 
?>


<script type='text/javascript'>
var atts = {};
var params = {};
var flashvars = {};

atts.name = 
atts.id = 'swf_container';
params.base = '<?php echo $kpath; ?>data/';
params.wmode = 'transparent';
params.allowScriptAccess = 'always';
params.allowFullScreen = 'true';
flashvars.basePath = '';
flashvars.externalBindings = 'network';
flashvars.onReady = 'korsakowReady';
flashvars.onError = 'korsakowError';
if (window['SWFAddress'] && SWFAddress.getParameter('snu'))
    flashvars.starter = SWFAddress.getParameter('snu');
//flashvars.requireVersion = '20.3';

// pull 'k_' prefixed query params into flashvars
var qs = $k.joinQueryString(window.location.search);
for (var i = 0; i < qs.length; ++i) {
	if (qs[i].name.indexOf('k_') ==0) {
		flashvars[qs[i].name.substring('k_'.length)] = qs[i].value;
	}
}

swfobject.embedSWF('<?php echo $KorsakowPlayer; ?>', 'swf_container', '100%', '100%', '10.0', '<?php echo $expressInstall; ?>', flashvars, params, atts);
</script>