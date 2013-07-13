				<? if ($this->uri->segment(1) == 'search') { ?>
					<div id="b3" class="banner"></div>
				<? } ?>
			</div>
			<div id="advert">
				<? if ($this->uri->segment(1) == 'search') { ?>
					<div id="b4" class="banner">
						<a href="/reklama"><img src="/img/248x398.jpg" width="248" height="398" alt="Реклама на сайте"></a>
						<? /* <a href="http://expert-n.su" target="_blank"><img src="/img/banners/expert-n.gif" width="250" height="400"></a> */ ?>
					</div>
					<div id="b5" class="banner"></div>
				<? } ?>
				
<? /*
					<a href="http://expert-n.su" target="_blank"><img src="/img/banners/expert-n.gif" width="250" height="400"></a>
*/ ?>
				<!-- Яндекс.Директ -->
				<div id="yandex_ad"></div>
				<script type="text/javascript">
				(function(w, d, n, s, t) {
					w[n] = w[n] || [];
					w[n].push(function() {
						Ya.Direct.insertInto(94063, "yandex_ad", {
							site_charset: "utf-8",
							ad_format: "direct",
							font_size: 1,
							type: "vertical",
							limit: 4,
							title_font_size: 3,
							site_bg_color: "FFFFFF",
							title_color: "BB0000",
							url_color: "274FAB",
							text_color: "000000",
							hover_color: "BB0000",
							favicon: true
						});
					});
					t = d.documentElement.firstChild;
					s = d.createElement("script");
					s.type = "text/javascript";
					s.src = "http://an.yandex.ru/system/context.js";
					s.setAttribute("async", "true");
					t.insertBefore(s, t.firstChild);
				})(window, document, "yandex_context_callbacks");
				</script>
<!--
				<div id="b6" class="banner"></div>
				<div id="b7" class="banner"></div>
-->
			</div>
		</div>
	</div>

	<? $this->load->view('footer'); ?>

	</body>
</html>
