var _aspirasi = null;
var _list = null;
dftAspirasi.on("click", ".mdl#delete", function () {
	_aspirasi = $("tr[id=" + $(this).data("id") + "]");
	const url_delete = $(this).data("url");

	$("#modal-delete")
		.find(".btn.url")
		.attr("href", url_delete)
		.html("Delete");
});

const notifAjax = document.querySelector(".notif#ajax");
let elMdlDelete = document.querySelector("#modal-delete");
elMdlDelete.addEventListener("click", function (e) {
	let el = e.target;
	if (el.classList.contains("url")) {
		e.preventDefault();
		let url = el.getAttribute('href');
		fetch(url, {
			method: "DELETE"
		}).then(res => res.json()).then(res => {
			let css = res.status ? "success" : "danger";
			let notif = `<div class="alert alert-${css}">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <i class="material-icons">close</i>
            </button>
            <span>${res.message}</span>
		  </div>`;
			notifAjax.innerHTML = notif;
			$(".main-panel").animate(
				{
					scrollTop: $(".notif#ajax").offset().top
				},
				"slow"
			);
			$(".modal").modal("hide");
			init();
		})
	}

})
function tbodySort() {
	$("tbody > tr")
		.sort(function (a, b) {
			return $("td.number", b).text() > +$("td.number", a).text();
		})
		.appendTo("tbody")
		.find("th:first")
		.text(function (index) {
			return ++index + ".";
		});
}
$("a.url.mdl").on("click", function (e) {
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
		error: function (e) {
			console.log("error");
		},
		complete: function () {
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
			$(".fullpage").hide("slow", function () {
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
dftAspirasi.on("click", ".mdl#edit", function () {
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
const init = () => {
	dftAspirasi.html(``);
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
                                <a href="#" class="badge badge-secondary tampilkan" data-komisi="${e.komisi_id}" data-message="${e.message}" data-id="${e.id_aspirasi}" data-toggle="modal" data-target=".modal#tampil" id="tampilkan">Lihat</a>
                                <a href="#" class="badge badge-primary mdl btn" data-komisi="${e.komisi_id}" data-message="${e.message}" data-id="${e.id_aspirasi}" data-toggle="modal" data-target=".modal#edit" id="edit">Edit</a>
                                <a href="" class="mdl badge badge-danger" id="delete"  data-id="${e.id_aspirasi}" data-toggle="modal" data-url="${baseUrl}api/aspirasi/${e.id_aspirasi}" data-target="#modal-delete">delete</a>
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
};
init();
let daftarAspirasi = document.querySelector(".daftar-aspirasi");
daftarAspirasi.addEventListener("click", function (e) {
	el = e.target;
	if (el.classList.contains("tampilkan")) {
		e.preventDefault();
		let data = {
			id: el.dataset.id,
			message: el.dataset.message,
			komisi_id: el.dataset.komisi
		};
		loadPerhitungan(data);
	}
});

const loadPerhitungan = async ({ id, message, komisi }) => {
	let table = document.querySelector("tbody.detail-aspirasi");
	table.innerHTML =
		"<tr><td colspan=5 class='text-center'>Loading..!</td></tr>";

	let tableFooter = document.querySelector("tfoot.hasil");
	tableFooter.innerHTML = "";
	try {
		let toArray = Obj => {
			if (Obj == null) {
				return null;
			}
			return Object.values(Obj.y);
		};

		let data = await getData("api/aspirasi/tampilkan/" + id);
		let message = Object.keys(data.message);
		let messageBobot = Object.values(data.message);
		let kms_001 = toArray(data.kms_001);
		let kms_002 = toArray(data.kms_002);
		let kms_003 = toArray(data.kms_003);
		let kms_004 = toArray(data.kms_004);
		let html = "";
		for (let index = 0; index < message.length; index++) {
			html += `
				<tr>
					<td>${message[index]}(${messageBobot[index]})</td>
					<td>${kms_001 ? kms_001[index] : "0"}</td>
					<td>${kms_002 ? kms_002[index] : "0"}</td>
					<td>${kms_003 ? kms_003[index] : "0"}</td>
					<td>${kms_004 ? kms_004[index] : "0"}</td>
				</tr>
			`;
		}
		table.innerHTML = html;
		let fhtml = `
			<tr class="bg-dark text-white">
				<td>Hasil Perhitungan</td>
				<td>${kms_001 ? data.kms_001.hasil.toFixed(2) : 0}</td>
				<td>${kms_002 ? data.kms_002.hasil.toFixed(2) : 0}</td>
				<td>${kms_003 ? data.kms_003.hasil.toFixed(2) : 0}</td>
				<td>${kms_004 ? data.kms_004.hasil.toFixed(2) : 0}</td>
			</tr>
			`;
		tableFooter.innerHTML = fhtml;
	} catch (error) {
		console.error(error);
	}
};

$("#formInput").on("submit", function (e) {
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
		complete: function () {
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
			$(".fullpage").hide("slow", function () {
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
