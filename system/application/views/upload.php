<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>AJAX загрузка файлов на сервер.</title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
		<script src="/js/jquery.ocupload.js"></script>

	<style type="text/css">
		.foto {			border: 1px solid #ddd;
			    float: left;
    margin: 10px;
    padding: 10px;		}
	</style>

  </head>

	<body>

		<form name="" action="" method="post">

			<input name="name" type="text" value="">


			<a id="upload" href="#">Выберите файл</a> <div id="process_upload"></div>

			<div id="result_upload" style="owerflow:hidden;">
			</div>






			<input type="submit" value="Send">

		</form>

		<script language="javascript">
			$(document).ready ( function() {
				$('#upload').upload ( {
					name: 'userfile',
					method: 'post',
					enctype: 'multipart/form-data',
					action: 'upload/do_upload',
					onSubmit: function() {
						$('#process_upload').html('Uploading...');
					},
					onComplete: function(data) {
						$('#process_upload').html('');
						var res = data.split('/=/');
						var text = '<div id="foto' + res[2] + '" class="foto">';
						if ( res[0] == 'ok' ) {							text+= '<input name="foto[]" type="text" value="' + res[1] + '">';						}
						if ( res[0] == 'error' ) {							text+= 'Ошибка: ' + res[1];						}
						text+= '<a OnClick="delFoto(\'' + res[2] + '\')">X</a></div>';

						$('#result_upload').html ( $('#result_upload').html() + text );
					}
				});
			});
			function delFoto ( id ) {
				$('#foto'+id).remove();
			}
		</script>


	</body>

</html>