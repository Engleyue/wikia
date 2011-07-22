<section id="GamingCalendar">
	<h1>Game Calendar</h1>

	<div class="ad">
		<div id="MODAL_VERTICAL_BANNER" class="wikia-ad noprint">
			<?= AdEngine::getInstance()->getAd('MODAL_VERTICAL_BANNER', array('ghostwriter'=>true)); ?>
		</div>
	</div>

	<div class="weeks">
		<ul>
		</ul>
	</div>

	<div class="controls">
		<div class="back">
		    <div class="back-week"><span>‹</span></div>
		    <div class="back-month"><span>«</span></div>
		</div>
		<div class="forward">
			<div class="forward-week"><span>›</span></div>
			<div class="forward-month"><span>»</span></div>
		</div>
		<div class="today">Today</div>
	</div>

   <script type="text/template" id="GamingCalendarWeekTemplate">
		<li class="week">
			<h1>
				<span>%week-caption%</span>
				%startmonth% %start% - %endmonth% %end%
			</h1>
			<div class="scroll-up"><img src="<?= $wgBlankImgUrl ?>" height="0" width="0"></div>
			<div class="list">
				<ul>%items%</ul>
			</div>
			<div class="scroll-down"><img src="<?= $wgBlankImgUrl ?>" height="0" width="0"></div>
		</li>
   </script>	
</section>
