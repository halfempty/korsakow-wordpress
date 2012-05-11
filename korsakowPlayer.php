<style>
#swf_container { <?php echo $kheight;?> }
</style>
<div id='swf_container'>Korsakow</div>
<div id='embedOverlayBackground'></div>
<div id='embedOverlay'>
    <div class='button close' onclick='closeEmbedOverlay(); return false;'><img src='<?php echo plugins_url( 'assets/css/cross.png' , __FILE__ ); ?>' alt='close' /></div>
    <div class='content'>
        <div class='p'>
            <div class='label'>Share this film</div>
            <div class='info'>Use this link to share the entire film.</div>
            <input id='shareFilm' />
        </div>
        <div class='p'>
            <div class='label'>Share this SNU</div>
            <div class='info'>This link will start the film at the current SNU.</div>
            <input id='shareSNU' />
        </div>
        <div class='p'>
            <div class='label'>Embed code</div>
            <div class='info'>Paste this code into your website or blog to embed this SNU.</div>
            <textarea id='embedCode' cols='32' rows='6'></textarea>
        </div>
    </div>
</div>
<div style='color: 1234; line-height: 0; width:0; height: 0; overflow: hidden'>OTgyNTYwODQtOWYwNi00ODJkLTg5MmQtYWFmNTQ5MTAwOWI0</div>