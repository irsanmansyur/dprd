var _aspirasi = null;
var aspirasi_all = null;
var aspirasi_selected = null;
var komentar_all = null;
var komentar_selected = null;
var _list = null;
dftAspirasi.on("click", ".mdl#lihat", function() {
	let _parent = $(this).parents("tr");
	let message = _parent.children("td[name=message]").text();
	let name = _parent.children("td[name=username]").text();
	let status = _parent.children("td[name=status]").data("status");
	let image = $(this).data("image");
	let url = baseUrl + "api/aspirasi?id_aspirasi=" + $(this).data("id");
	let komisi_id = $(this).data("komisi");
	aspirasi_selected = aspirasi_all.find(obj => {
		return obj.id_aspirasi === $(this).data("id");
	});

	aspirasi_selected = {
		id_aspirasi: $(this).data("id"),
		status: _parent.children("td[name=status]").data("status"),
		message: _parent.children("td[name=message]").text(),
		image:  $(this).data("image"),
		komisi_id: $(this).data("komisi")
	};

	$(".meta-info#komentar").data(
		"url",
		baseUrl + "api/komentar?aspirasi_id=" + $(this).data("id")
	);
	$(".meta-info#tanggapan").data(
		"url",
		baseUrl + "api/komentar?aspirasi_id=" + $(this).data("id")
	);
	if (status != 1 && status != 2) {
		$.ajax({
			url: url,
			type: "PUT",
			data: {
				status: "2"
			},
			dataType: "json"
		});
		loadAspirasi();
	}
	$(".content-aspirasi")
		.find(".card-description")
		.text(message);
	$(".content-aspirasi")
		.find(".card-title")
		.text(name);
	$(".content-aspirasi")
		.find(".image")
		.attr("src", image);
	htmlTanggapi();
	empty();
});
$().ready(function() {
	loadAspirasi();
});

function htmlTanggapi(myDiv = $("#comments-list")) {
	$("#comments-list")
		.find(".submit.tanggapi")
		.remove();
	let html = `
	<form action='' method='post' class='submit tanggapi'>
		<div class="form-group">
			<label for="textKomentar">Tanggapan Anda</label>
			<textarea class="form-control" id="textKomentar" name='komentar' rows="3"></textarea>
		</div>
		<div class="form-group">
		<input type='submit' value='Send'/>
	</form>
	`;
	myDiv.append(html);
	$("#comments-list .submit.tanggapi").validate({
		rules: {
			komentar: {
				required: true,
				minlength: 12
			},
			action: "required"
		},
		messages: {
			komentar: {
				required: "Please enter some data",
				minlength: "Your data must be at least 12 characters"
			},
			action: "Please provide some data"
		},
		submitHandler: function(form) {
			async function putAspirasi(url = "", data = {}) {
				// Default options are marked with *
				const response = await fetch(url, {
					method: "PUT", // *GET, POST, PUT, DELETE, etc.
					mode: "cors", // no-cors, *cors, same-origin
					cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
					credentials: "same-origin", // include, *same-origin, omit
					headers: {
						"Content-Type": "application/json"
						// 'Content-Type': 'application/x-www-form-urlencoded',
					},
					redirect: "follow", // manual, *follow, error
					referrerPolicy: "no-referrer", // no-referrer, *client
					body: JSON.stringify(data) // body data type must match "Content-Type" header
				});
				return await response.json(); // parses JSON response into native JavaScript objects
			}
			if (aspirasi_selected.status != 1 && komentar_selected == null) {
				putAspirasi(
					baseUrl + "api/aspirasi?id_aspirasi=" + aspirasi_selected.id_aspirasi,
					{ status: 1 }
				).then(data => {
					loadAspirasi();
					console.log(data); // JSON data parsed by `response.json()` call
				});
			}
			let data = $(form).serializeArray();
			data.push(
				{
					name: "aspirasi_id",
					value: aspirasi_selected.id_aspirasi
				},
				{
					name: "user_id",
					value: user.id_user
				},
				{
					name: "parent",
					value: komentar_selected == null ? "0" : komentar_selected.id_komentar
				}
			);

			let status,
				message = null;
			$.post(baseUrl + "api/komentar", data)
				.done(res => {
					status = res.status;
					message = res.message;
				})
				.fail(function(err) {
					message = "Tanggapan Erros " + err;
				})
				.always(function() {
					let type = status ? "success" : "warning";
					let html = `
								<div class="alert alert-${type}">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<i class="material-icons">close</i>
									</button>
									<span>${message}</span>
								</div>
							`;
					$("textarea[name=komentar]").val("");
					$("#comments-list").prepend(html);
				});
			return false;
		}
	});
}

$(".tanggapi").click(function(e) {
	$(".meta-info").removeClass("is-active");
	$("#comments-list").html("");
	komentar_selected == null;
	komentar_all == null;
	$(this).addClass("is-active");
	e.preventDefault();
	htmlTanggapi();
});

function getTanggal(dateInt) {
	let d = new Date(parseInt(dateInt, 10) * 1000);
	let days = [
		"Sunday", //senin
		"Monday", //selasa
		"Tuesday", //rabu
		"Wednesday", //kamis
		"Thursday", //jumat
		"Friday", //sabtu
		"Saturday" //minggu
	];
	let months = [
		"January",
		"February",
		"March",
		"April",
		"May",
		"June",
		"July",
		"August",
		"September",
		"October",
		"November",
		"December"
	];
	return days[d.getDay()] + "/" + months[d.getMonth()] + "/" + d.getFullYear();
}
$(".getInfo").click(function(e) {
	$(".meta-info").removeClass("is-active");
	e.preventDefault();
	let url = $(this).data("url");
	$.ajax({
		url: url,
		type: "GET",
		data: {},
		dataType: "json",
		beforeSend: () => {
			$(this).addClass("on-process");
			$(".complaint-content .content").html("Wait.....!");
		},
		success: dt => {
			if (dt.status) {
				var list = "";
				reply = "";
				komentar_all = dt.data;
				dt.data.forEach(row => {
					if ($(this).data("role") == row.role_id && row.parent == 0) {
						let date_created = getTanggal(row.date_created);
						list += `<li>
								<div class="comment-main-level">
									<div class="comment-avatar"><img src="${row.file}" alt=""></div>
									<div class="comment-box">
										<div class="comment-head">
											<h6 class="comment-name by-author"><a href="#">${row.username}</a></h6>
											<span>${date_created}</span>
											<i class="fa fa-reply kmt-reply" data-id="${row.id_komentar}"></i>
											
										</div>
										<div class="comment-content">
											${row.komentar}
										</div>
									</div>
								</div>
								<ul class="comments-list reply-list">
								`;
						let reply = listReplies(row.id_komentar, dt.data);
						list += reply;
					}
					list += "</ul></li>";
					// list += reply;
				});
				$("#comments-list").html(list);
			} else {
				$("#comments-list").html("Tidak Ada Komentar");
			}
			$(this).addClass("is-active");

			// status = dt.status;
			// message = dt.message;
		},
		complete: function() {
			$(".meta-info").removeClass("on-process");
		}
	});
});
let ReplyKomentar = $(".comments-list").on("click", ".kmt-reply", function() {
	// tentukan id komentar target
	let id_komentar = $(this).data("id");
	let parent = $(this).parents(".comment-main-level");

	komentar_selected = komentar_all.find(obj => {
		return obj.id_komentar === id_komentar;
	});
	let htmlRefly = `

	`;
	htmlTanggapi(parent);
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

function listReplies(parent, list) {
	var reply = ``;
	list.forEach(element => {
		if (element.parent == parent) {
			let date_created = getTanggal(element.date_created);

			let lists = `<li>
							<div class="comment-main-level reply">
								<div class="comment-avatar"><img src="${element.file}" alt=""></div>
								<div class="comment-box">
									<div class="comment-head">
										<h6 class="comment-name by-author"><a href="#">${element.username}</a></h6>
										<span>${date_created}</span>
										<i class="fa fa-reply kmt-reply" data-id="${element.id_komentar}"></i>
										
									</div>
									<div class="comment-content">
										${element.komentar}
									</div>
								</div>
							</div>
						</li>`;
			reply += lists;
			reply += listReplies(element.id_komentar, list);
		}
	});
	return reply;
}

function loadAspirasi() {
	$.ajax({
		url: urlLoadAspirasi,
		type: "get",
		dataType: "json",
		beforeSend: () => {
			$("thead.load").after("<div class='mdlProgresBarr'></div>");
		},
		success: respon => {
			if (respon.status) {
				let Urut = 1;
				let tr = "";
				aspirasi_all = respon.data;
				respon.data.forEach(e => {
					let status =
						e.status == 1
							? ["success", "Ditanggapi"]
							: e.status == 2
							? ["warning", "Belum Di tanggapi"]
							: ["danger", "Belum di Baca"];
					tr += `
                        <tr id="${e.id_aspirasi}">
                            <th scope="row">${Urut}</th>
                            <td name='username'>${e.username}</td>
                            <td name='message'>${e.message}</td>
                            <td name='status' data-status="${e.status}"><span class="badge badge-${status[0]}">${status[1]}</span></td>
                            <td>
                                <a href="" data-image="${e.file}" class="mdl badge badge-danger" id="lihat"  data-id="${e.id_aspirasi}" data-toggle="modal" data-url="${baseUrl}api/aspirasi?id_aspirasi=${e.id_aspirasi}" data-target=".modal#lihat">Lihat</a>
                            </td>
						</tr>
					
					`;
					Urut++;
				});
				dftAspirasi.html(tr);
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
}

function empty() {
	$("#comments-list").html("");
}
