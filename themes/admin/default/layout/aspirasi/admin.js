var _aspirasi = null;
var _list = null;
dftAspirasi.on("click", ".mdl#delete", function() {
	_aspirasi = $("tr[id=" + $(this).data("id") + "]");
	const url_delete = $(this).data("url");
	$(".modal#logout")
		.find(".btn.url")
		.attr("href", url_delete)
		.html("Delete");
	$(".modal#logout")
		.find(".modal-title")
		.html("Jika Anda menghapus maka data yang berkaitan akan terhapus juga");
});
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
$("a.url.mdl").on("click", function(e) {
	e.preventDefault();
	var parents = $(this).parents(".modal-content");
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
				_aspirasi.remove();
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
dftAspirasi.on("click", ".mdl#edit", function() {
	_aspirasi = $("tr[id=" + $(this).data("id") + "]");
	let url = baseUrl + "api/aspirasi?id_aspirasi=" + $(this).data("id");
	let komisi_id = $(this).data("komisi");
	let message = $(this).data("message");
	$("#formInput").attr("action", url);
	$("#formInput")
		.find("option[value=" + komisi_id + "]")
		.attr("selected", true);
	$("textarea[name=message]").val(message);
});
$().ready(function() {
	$.ajax({
		url: baseUrl + "api/aspirasi",
		type: "get",
		dataType: "json",
		beforeSend: () => {
			$("thead.load").after("<div class='mdlProgresBarr'></div>");
		},
		success: respon => {
			if (respon.status) {
				let Urut = 1;
				respon.data.forEach(e => {
					let tr = `
                        <tr id="${e.id_aspirasi}">
                            <th scope="row">${Urut}</th>
                            <td name='username'>${e.username}</td>
                            <td name='message'>${e.message}</td>
                            <td name='komisi_id'>${e.komisi}</td>
                            <td>
                                <a href="#" class="badge badge-primary mdl btn" data-komisi="${e.komisi_id}" data-message="${e.message}" data-id="${e.id_aspirasi}" data-toggle="modal" data-target=".modal#edit" id="edit">Edit</a>
                                <a href="" class="mdl badge badge-danger" id="delete"  data-id="${e.id_aspirasi}" data-toggle="modal" data-url="${baseUrl}api/aspirasi?id_aspirasi=${e.id_aspirasi}" data-target=".modal#logout">delete</a>
                            </td>
                        </tr>
                    `;
					dftAspirasi.append(tr);
					Urut++;
				});
			} else {
				dftAspirasi.html(`<tr><td col=5>${respon.message}</td></tr>`);
			}
		},
		error: e => {
			console.log(e);
		},
		complete: () => {
			$(".mdlProgresBarr").remove();
		}
	});
});

$("#formInput").on("submit", function(e) {
	e.preventDefault();
	var myForm = $(this);
	let url = myForm.attr("action");
	var data = $(this).serializeArray(); //serialize form inputs and pass them to php
	$.ajax({
		url: url,
		type: "PUT",
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
				_aspirasi
					.children("td[name=komisi_id]")
					.text($("#komisi_id option:selected").text());

				_aspirasi
					.children("td[name=message]")
					.text($("textarea[name=message]").val());
			}
			status = dt.status;
			message = dt.message;
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
