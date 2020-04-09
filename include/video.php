<style>
    .vjs-error .vjs-error-display { display: none; }
</style>
<section>
				<div class="content ost">
					<p>Осталось времени: <span id="timer"></span></p>
				</div>
				<video class="video-js" id="my-player" controls width="680" autoplay controlsList="nodownload" data-setup='{}'>
					<source src="<?=$link?>" type="video/mp4"></source>
				</video>
                <div class="video_description"><p><?=$description_video; ?></p></div>