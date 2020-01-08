var status, message;

function tbodySort() {
	$("tbody > tr")
		.sort(function(a, b) {
			return $("td.number", b).text() > +$("td.number", a).text();
		})
		.appendTo("tbody")
		.find("th:first")
		.text(function(index) {
			return ++index + ".";
		});
}
var UserId = "";
$(".mdl#delete").on("click", function() {
	const url_delete = $(this).data("url");
	UserId = $(this).data("iduser");
	console.log(UserId);
	$(".modal#logout")
		.find(".btn.url")
		.attr("href", url_delete)
		.html("Delete");
	$(".modal#logout")
		.find(".modal-title")
		.html("Jika Anda menghapus maka data yang berkaitan akan terhapus juga");
});

$("a.url").on("click", function(e) {
	e.preventDefault();
	let parents = $(this).parents(".modal-content");
	let url = $(this).attr("href");

	$.ajax({
		url: url,
		type: "DELETE",
		dataType: "json",
		beforeSend: () => {
			parents.after("<div class='mdlProgresBarr'></div>");
			$(".fullpage").addClass("tampil");
		},
		success: data => {
			if (data.status) {
				let id = ".user-id#" + UserId;
				$(id).remove();
				tbodySort();
			}
			message = data.message;
			status = data.status;
		},
		error: function(e) {
			console.log("error");
		},
		complete: function() {
			$("head").append("<style>.mdlProgresBarr::before{width:100%;}</style>");
			$(".mdlProgresBarr").remove();
			$(".modal").modal("hide");
			let css = status ? "success" : "danger";
			let notif = `<div class="alert alert-${css}">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <i class="material-icons">close</i>
            </button>
            <span>${message}</span>
          </div>`;
			$(".fullpage").hide("slow", function() {
				$(".fullpage").remove();
			});
			$(".notif#ajax").html(notif);
			$(".main-panel").animate(
				{
					scrollTop: $(".notif#ajax").offset().top
				},
				"slow"
			);
		}
	});
});
$(".userActive").on("click", function() {
	const userId = $(this).data("userid");
	const is_active = $(this).data("active");
	const url = $(this).data("url");
	console.log(is_active);
	console.log(userId);
	$.ajax({
		url: url,
		type: "post",
		dataType: "json",
		data: {
			id_user: userId,
			is_active: is_active == 1 ? 0 : 1
		},
		beforeSend: function() {
			$("#loader-wrapper").show();
		},
		success: function(e) {
			if (e.status) {
				console.log("sukses");
				$(".userActive." + userId).data("active", e.data.is_active);
				const lbl = e.data.is_active == 1 ? "active" : "Non Active";
				$(".label." + userId).text(lbl);
			}
			status = e.status;
			message = e.message;
		},
		error: e => {
			console.log(e);
		},
		complete: respon => {
			$("#loader-wrapper").hide();
			let css = status ? "success" : "danger";
			let notif = `<div class="alert alert-${css}">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <i class="material-icons">close</i>
            </button>
            <span>${message}</span>
          </div>`;
			$(".notif#ajax").html(notif);
			$(".main-panel").animate(
				{
					scrollTop: $(".notif#ajax").offset().top
				},
				"slow"
			);
		}
	});
});

$(".setUser").on("click", function() {
	const userId = $(this).data("id_user");
	const url = $(this).data("url");
	$(".input.user-id").val(userId);
	$(".form.setKomisi").attr("action", url);
	console.log(url);
});

$(".setKomisi").on("submit", function(e) {
	e.preventDefault();
	var myForm = $(this);
	let url = myForm.attr("action");
	var data = $(this).serializeArray(); //serialize form inputs and pass them to php
	console.log();
	$.ajax({
		url: url,
		type: "post",
		data: data,
		dataType: "json",
		beforeSend: () => {
			myForm.after("<div class='mdlProgresBarr'></div>");
			$(".fullpage").addClass("tampil");
		},
		success: dt => {
			let userId = $('input[name="user_id"]').val();
			let nameKomisi = $("select[name='komisi_id'] option:selected").text();
			if (dt.status) {
				$(".komisi." + userId).text(nameKomisi);
			}
			console.log("Gagal");
			status = dt.status;
			message = dt.message;
		},
		error: e => {
			console.log(e);
		},
		complete: function() {
			$("head").append("<style>.mdlProgresBarr::before{width:100%;}</style>");
			$(".mdlProgresBarr").remove();
			$(".modal").modal("hide");
			let css = status ? "success" : "danger";
			let notif = `<div class="alert alert-${css}">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <i class="material-icons">close</i>
            </button>
            <span>${message}</span>
          </div>`;
			$(".fullpage").hide("slow", function() {
				$(".fullpage").remove();
			});
			$(".notif#ajax").html(notif);
			$(".main-panel").animate(
				{
					scrollTop: $(".notif#ajax").offset().top
				},
				"slow"
			);
		}
	});
});
