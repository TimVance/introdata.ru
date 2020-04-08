</section>
			<aside class="as2">
				<p class="cwd">
					<a href="#" target="_blank" class="cwda _openQueryForm" title="Вопрос лектору">Вопрос лектору</a>
				</p>
				<form action="feedback.php" method="POST" id="feedback-form" target="for_forms">
					<input type="hidden" name="material_mark" value="3">
					<input type="hidden" name="service_mark" value="3">
					
					<div class="pb">
						<p>Оценить материал</p>
						<p id="material_mark_list" class="fa-starts-block">
							<span class="fa fa-star checked"></span>
							<span class="fa fa-star checked"></span>
							<span class="fa fa-star checked"></span>
							<span class="fa fa-star"></span>
							<span class="fa fa-star"></span>
						</p>
					</div>
					<div class="pb">
						<p>Оценить сервис</p>
						<p id="service_mark_list" class="fa-starts-block">
							<span class="fa fa-star checked"></span>
							<span class="fa fa-star checked"></span>
							<span class="fa fa-star checked"></span>
							<span class="fa fa-star"></span>
							<span class="fa fa-star"></span>
						</p>
					</div>
					<p><input type="text" name="email" required placeholder="E-mail"></p>
					<p class="noClear">
						<textarea rows="5" name="message" cols="30" placeholder="Оставить отзыв"></textarea>
						<input type="submit" name="submit" value="Отправить">
					</p>
				</form>
			</aside>
		</div>

	</section>