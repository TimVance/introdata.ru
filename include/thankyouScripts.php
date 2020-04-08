<script>
      function secondsToHms(d) {
          d = Number(d);
          var h = Math.floor(d / 3600);
          var m = Math.floor(d % 3600 / 60);
          var s = Math.floor(d % 3600 % 60);

          var hDisplay = h > 0 ? h + (h == 1 ? " час. " : " час. ") : "";
          var mDisplay = m > 0 ? m + (m == 1 ? " мин. " : " мин. ") : "";
          var sDisplay = s > 0 ? s + (s == 1 ? " сек." : " сек.") : "";
          //return hDisplay + mDisplay + sDisplay;
          return hDisplay + mDisplay;
      }

      function secondsToTime(time){

          var h = Math.floor(time / (60 * 60)),
              dm = time % (60 * 60),
              m = Math.floor(dm / 60),
              ds = dm % 60,
              s = Math.ceil(ds);
          if (s === 60) {
              s = 0;
              m = m + 1;
          }
          if (s < 10) {
              s = '0' + s;
          }
          if (m === 60) {
              m = 0;
              h = h + 1;
          }
          if (m < 10) {
              m = '0' + m;
          }
          if (h === 0) {
              fulltime = m + ':' + s;
          } else {
              fulltime = h + ':' + m + ':' + s;
          }
          return fulltime;
      }





      document.addEventListener('contextmenu', event => event.preventDefault());
      $(document).ready(function(){
          videoEl = document.getElementsByTagName('video')[0];



          $.ajax({
              type: "POST",
              url: "/timer.php",
              data: {'action': 'getTimer', id:'<?= $_GET['id'] ?>'},
              success: function(data){
                  if(data == 'out'){
                      //window.location.reload(true);
                  }
              }
          });
              $.ajax({
                  type: "POST",
                  url: "/timer.php",
                  data: {'action': 'userOnline', id:'<?= $_GET['id'] ?>'},
                  success: function(data){
                      console.log(data);

                  }
              });

          document.addEventListener('readystatechange', () => log('readyState:' + document.readyState));

          window.addEventListener('beforeunload', function (e) {
              $.ajax({
                  type: "POST",
                  url: "/timer.php",
                  data: {'action': 'userSession', id:'<?= $_GET['id'] ?>'},
                  success: function(data){
                      console.log(data);
                      if(data == 'session_inactive'){

                      }else {
                          if (data == 'video') {
                              // window.location.reload(true);
                          }
                      }
                  }
              });
          }, false);
          window.setInterval(function(){
              $.ajax({
                  type: "POST",
                  url: "/timer.php",
                  data: {'action': 'countTimer', id:'<?= $_GET['id'] ?>'},
                  success: function(data){
                      console.log(data);
                      if(data == 'session_inactive'){
                          $("span#timer").text('Неогранично');
                      }else{
                          $("span#timer").text(secondsToHms(data));
                          if(data == 'out'){
                              window.location.reload(false);
                          }
                          if(data == 'reload'){
                              window.location.reload(false);
                          }
                      }

                  }
              });
          }, 1000);


              window.setInterval(function(){
              $.ajax({
                  type: "POST",
                  url: "/timer.php",
                  data: {'action': 'countTimerVideo', id:'<?= $_GET['id'] ?>'},
                  success: function(data){
                      if(data == 'session_inactive'){
                          console.log('lol');
                      }else{
                          timePicker.innerHTML = secondsToTime(data);
                          console.log('Видео - ' + data);
                          if (data == 'video') {
                              window.location.reload(false);
                          }
                          if (data == 'videonewvideo') {
                              window.location.reload(false);
                          }
                      }
                  }
              });
              }, 1000);





	
	      window.setInterval(function(){
		      $.ajax({
			      type: "POST",
			      url: "/timer.php",
			      data: {'action': 'updateTimeOut', id:'<?= $_GET['id'] ?>', user_id: '<?= $_GET['user_id'] ?>'}
		      });
	      }, 15000);
      });




	  
		window.jQuery(function($) {
			var $form = $('#feedback-form');
			
			var $materialMarkList = $form.find('#material_mark_list');
			var $serviceMarkList  = $form.find('#service_mark_list');
			
			var $materialMarkInput = $form.find('input[name=material_mark]');
			var $serviceMarkInput  = $form.find('input[name=service_mark]');
			
			
			$form.submit(function(e) {
				if ($form.data('submiting'))
					return false;
				
				$form.data('submiting', true);
				$form.find('input[type=submit]').val('Отправляется...').prop('disabled', true);
				
				return true;
			});

			$('#material_mark_list').on('click', 'span', function(e) {
				var $span = $(e.target);
				
				var index = $materialMarkList.find('span').index($span);
				$materialMarkInput.val(index + 1);
			});
			
			$('#service_mark_list').on('click', 'span', function(e) {
				var $span = $(e.target);
				
				var index = $serviceMarkList.find('span').index($span);
				$serviceMarkInput.val(index + 1);
			});
			
			$('#material_mark_list, #service_mark_list').on('mouseover', 'span', function(e) {
				var $span = $(e.target);
				
				$span.prevAll().addClass('checked');
				$span.addClass('checked');
				$span.nextAll().removeClass('checked');
			});
			
			$('#material_mark_list').on('mouseout', function(e) {
				var material_mark = $materialMarkInput.val();
				
				var $materialMark = $materialMarkList.find('span').eq(material_mark - 1);
				
				$materialMark.prevAll().addClass('checked');
				$materialMark.addClass('checked');
				$materialMark.nextAll().removeClass('checked');
			});
			$('#service_mark_list').on('mouseout', function(e) {
				var service_mark  = $serviceMarkInput.val();
				var $serviceMark  = $serviceMarkList.find('span').eq(service_mark - 1);
				
				$serviceMark.prevAll().addClass('checked');
				$serviceMark.addClass('checked');
				$serviceMark.nextAll().removeClass('checked');
			});
			
			$('iframe[name=for_forms]').on('load', function() {
				if (!$form.data('submiting'))
					return;
				
				setTimeout(function() {
					$form.find('input[type=submit]').val('Отправленно');
					$form.data('submiting', false);	
				}, 1000);
			});
			
		});
		
		window.jQuery(function($) {
			var $layer = $('#query-form-layer');
			var $form = $('#query-form');
			
			$form.submit(function(e) {
				if ($form.data('submiting'))
					return false;
				
				$form.data('submiting', true);
				$form.find('input[type=submit]').val('Отправляется...').prop('disabled', true);
				
				return true;
			});
			
			$('a._openQueryForm').click(function() {
				$layer.css('display', 'flex');
				return false;
			});
			$layer.find('._close_btn').click(function() {
				$layer.css('display', '');
			});
			
			$('iframe[name=for_forms]').on('load', function() {
				if (!$form.data('submiting'))
					return;
				
				setTimeout(function() {
					$form.find('input[type=submit]').val('Отправленно');
					$form.data('submiting', false);	
				}, 1000);
			});
		});
		
		
		
  </script>