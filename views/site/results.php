<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\Carousel;

$this->registerCssFile('@web/css/results.css');
$this->registerJsFile('@web/js/jquery.scrollbox.js');

$this->title = 'Search results';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="container">
    <div id="carousel" class="scroll-img">
		<ul>
			<?php
			foreach ($images as $filename) {
				echo '<li><img class="image" src="/img/' . $filename . '"></li>';
			}
			?>
		</ul>
    </div>
    <div id="carousel-btn" class="text-center">
		<button class="btn" id="carousel-backward"><i class="icon-chevron-left"></i> Backward</button>
		<button class="btn" id="carousel-forward">Forward <i class="icon-chevron-right"></i></button>
    </div>
</div>

<script>
	$(function () {
		$('#carousel').scrollbox({
			direction: 'h',
			distance: 134,
			autoPlay: false,
			switchItems: 1
		});

		$('#carousel-backward').click(function () {
			$('#carousel').trigger('backward');
		});
		$('#carousel-forward').click(function () {
			$('#carousel').trigger('forward');
		});

		$(".image").click(function () {
			var img = $(this);
			var src = img.attr('src');
			$("body").css('overflow', 'hidden');
			$("body").append("<div class='popup'>" +
					"<div class='popup_bg'></div>" +
					"<img src='" + src + "' class='popup_img' />" +
					"</div>");
			$(".popup").fadeIn(600);
			$(".popup_bg").click(function () {
				$(".popup").fadeOut(600);
				setTimeout(function () {
					$(".popup").remove();
					$("body").css('overflow', 'auto');
				}, 600);
			});
		});
	});
</script>