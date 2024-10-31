
jQuery(document).ready(function($) {

	$(document).on('click touchstart', '.i0,.i1,.i2,.i3,.i4,.i5,.i6,.i7,.i8,.i9', function(){
		$(".chto").addClass("displayblock");
	});

	$(document).on('click touchstart', '.i10', function(){
		$(".chto").removeClass("displayblock");
	});


	function getCookie(name) {
		var matches = document.cookie.match(new RegExp(
			"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\/\+^])/g, '\$1') + "=([^;]*)"
		));
		return matches ? decodeURIComponent(matches[1]) : undefined;
	}

	/*Проверка если уже проходили голосование с этого компа*/
	$('#nps-computy').on("submit", function(ev) {
		let count_day = $('#rcooka').val();
		if(count_day === 0){
			document.cookie = "npsgolos=no; path=/; expires=" + 30;
		}
		var cuca = getCookie("npsgolos");

		if (cuca === "yes") {

			$("#nps-computy").hide();
			$("#youbil-computy").show();


		}
		else {

			if ($("input[name=radio]:checked").length > 0) {


				var msg = jQuery("#nps-computy").serialize();
				$(".nps-submit").html('<span class="spiner"></span>');

				$.ajax({

					type: "POST",
					url :  "/wp-admin/admin-ajax.php",
					data: msg,

					success: function (data) {
						$("#results-nps").html(data);
						var date = new Date;
						date.setDate(date.getDate() + count_day);
						date = date.toUTCString();
						document.cookie = "npsgolos=yes; path=/; expires=" + date;

						$("#nps-computy").hide();
					},
					error  : function (xhr, str) {
						alert("Sorry, there was an error: " + xhr.responseCode);
					}
				});
			} else {
				$(".nps-radios").addClass("error-nps");

			}
		}

	});




});






