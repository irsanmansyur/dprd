$(".toast").toast({
	delay: 6000
});

window.data = {
	aspirasi: null,
	idasp: null,
	komentar: [],
	progress: true
};

async function init(datas, status = 0) {
	let listAspirasi = document.getElementById("data-aspirasi");
	if (datas.status) {
		data.komentar = [];
		listAspirasi.innerHTML = "await....!";
		let aspirasis = data.aspirasi.data;
		status != 0
			? (aspirasis = data.aspirasi.data.filter(res => res.status == status))
			: "";
		if (aspirasis.length > 0) {
			listAspirasi.innerHTML = "";
			let dt = await aspirasis.map(async res => {
				let card = await tampilkanAspirasiIni(res);

				let komentar = await getData(
					"api/komentar?aspirasi_id=" + res.id_aspirasi
				);
				if (komentar.status) {
					komentar.data.map(res => this.data.komentar.push(res));
					let CountKomentr = komentar.data.filter(
						rs => rs.role_id != 2 && rs.parent == 0
					).length;

					let CountTanggapan = komentar.data.filter(
						rs => rs.role_id == 2 && rs.parent == 0
					).length;

					let meta = card.querySelectorAll(".meta-info");

					Array.from(meta).map(res => {
						if (res.dataset.jenis == "komentar") {
							res.querySelector("span.num").innerHTML = CountKomentr;
						} else res.querySelector("span.num").innerHTML = CountTanggapan;
					});
				}
				card
					.querySelector(".loading")
					.classList.remove("c-animated-background");
			});
		} else listAspirasi.innerHTML = "Tidak Ada Aspirasi..!";
	} else {
		listAspirasi.innerHTML = "Tidak Ada Aspirasi..!";
	}
}

function tampilkanAspirasiIni(res) {
	let listAspirasi = document.getElementById("data-aspirasi");
	let card = document.createElement("div");
	card.setAttribute("class", "card mb-2 aspirasi");
	card.setAttribute("data-idAsp", res.id_aspirasi);

	let date_created = getTanggal(res.date_created);

	let tbl =
		res.status != 1
			? `<br/><button type="button" class="badge badge-danger" data-toggle="modal" data-target="#modalDeleteAsp" id="deleteAsp">Delete</button>`
			: "";
	let html = `
			<div class="loading c-animated-background"></div>
			<div class="card-header identitas position-relative" style='padding-left:80px'>
				<img src="${res.file}"  alt="" class="position-absolute rounded-circle cardImg-profile">
				<h5 class="card-title mb-0">${res.username}</h5>
				<span class="text-muted">Di arahkan ke : </span><span class="komisi font-weight-bold">${res.komisi}</span>
				<span class="position-absolute text-muted text-right" style="top:12px;right:15px">${date_created} ${tbl}</span>
			</div>
			<div class="card-body">
				<p class="card-text text-justify text-monospace">${res.message}</p><hr />
				<div class="complaint-meta">
					<a class="meta-info mr-2" data-jenis="tanggapan" href="#tanggapan" role="button" aria-expanded="true" aria-controls="tanggapan"><i class="fas fa-reply"></i> Tanggapan <span class="align-text-bottom num">${res.jml_tanggapan}</span></a>
					<a class="meta-info mr-2" data-jenis="komentar" href="#komentar" role="button" aria-expanded="true" aria-controls="komentar"><i class="far fa-comments"></i> Komentar <span class="align-top num">${res.jml_komentar}</span></a>
					<a class="badge btn-utama ml-2 p-2" href="${baseUrl}aspirasi/id/${res.id_aspirasi}">Lihat</a>
				</div>
				<div class="collapse list-komentar mt-3">
				</div>
			</div>`;
	card.innerHTML = html;
	listAspirasi.appendChild(card);
	return card;
}

function cardParent(res) {
	let aspirasi = document.querySelector(`[data-idasp='${res.aspirasi_id}']`);
	const elKomentar = aspirasi.querySelector(".card-body .list-komentar");
	let card = document.createElement("div");
	card.setAttribute("class", "card mt-2 komentar");
	card.setAttribute("data-idKmt", res.id_komentar);
	var img = baseUrl + "assets/img/thumbnail/";
	let date_created = getTanggal(res.date_created);

	let komentars = data.komentar.filter(
		komentar =>
			komentar.parent == res.id_komentar &&
			komentar.aspirasi_id == res.aspirasi_id
	);
	const htmlBtn = `
		<div class="bl-rp d-flex">
			<a class="btn btn-secondary btn-sm" data-toggle="collapse" href="#balasan_${res.id_komentar}"><span class="align-top mr-2">${komentars.length} </span> Balasan</a>
			<a class="btn btn-primary ml-2 btn-sm" data-toggle="collapse" href="#reply_${res.id_komentar}" role="button" aria-expanded="true" aria-controls="reply_${res.id_komentar}">Balas</a>
		</div>`;
	const htmlCollapseForm = `
		<div class="collapse mt-2 pt-2" id="reply_${res.id_komentar}">
			<!-- =balasTanggapan -->
			<form class="pt-3 komentar" method="post" action="${baseUrl}aspirasi/send">
				<input type="text" hidden value="${res.aspirasi_id}" name="aspirasi_id">
				<input type="text" hidden value="${res.id_komentar}" name="parent">
				<div class="form-group mt-3 komentar position-relative">
					<textarea name='komentar' required style="    background: transparent;" class="form-control komentar_${res.id_komentar}" id="add_komentar_${res.id_komentar}" rows="3"></textarea>
					<label for="add_komentar" class="label-komentar">
						<span class="label-text">Ketikkan Komentar Anda Disini</span></label>
					<input type="submit" value="Kirim" class="btn btn-primary submit-komentar">
				</div>
			</form>
		</div>`;

	let htmlCardChildrend = `<div class='collapse' id="balasan_${res.id_komentar}">`;
	if (komentars.length > 0) {
		htmlCardChildrend += komentars
			.map(komentar => cardChildrend(komentar))
			.reduce((html, komentar) => html + komentar);
	}
	htmlCardChildrend += "</div>";
	let html = `
			<div class="card-header identitas position-relative" style='padding-left:80px'>
				<img src="${
					res.file
				}" alt="" class="position-absolute rounded-circle cardImg-profile">
				<h5 class="card-title komentar mb-0">${res.username}</h5>
				<span class="text-muted">${
					res.role_id == 2 ? "Menanggapi : " : "Berkomentar : "
				} </span>
				<span class="position-absolute text-muted" style="top:12px;right:15px">${date_created}</span>
			</div>
			<div class="card-body">
				<p class="card-text text-justify text-monospace">${res.komentar}.</p>
				${htmlBtn}
				${htmlCollapseForm}
				${htmlCardChildrend}
			</div>`;
	card.innerHTML = html;
	elKomentar.appendChild(card);
	elKomentar.style.visibility = "visible";
}

function cardChildrend(komentar) {
	let date_created = getTanggal(asp.date_created);

	let htmlChildrend = `
		<div class="card mt-2 komentar childrend">
			<div class="card-header identitas position-relative mt-3" style='padding-left:80px;background: transparent;
			border: none;'>
				<img src="${komentar.file}" alt="" class="position-absolute rounded-circle cardImg-profile">
				<h5 class="card-title komentar mb-0">${komentar.username}</h5>
				<span class="text-muted date-created" style="">${date_created}</span>
				<p class="card-text text-justify text-monospace"><span class="text-muted">Membalas : </span>
				${komentar.komentar}.</p>
			</div>
		</div>`;
	return htmlChildrend;
}

function showTanggapan(e) {
	e.preventDefault();
	const el = e.target;
	let listAspirasi = el.closest("#data-aspirasi");
	let elAspirasi = el.closest(".card.aspirasi");

	let data = {
		idasp: elAspirasi.dataset.idasp,
		role: el.dataset.jenis == "tanggapan" ? 2 : 0,
		parent: 0
	};

	let meta = listAspirasi.querySelectorAll(".meta-info");
	Array.from(meta).map(res => {
		res.classList.remove("is-active");
		let lainya = res.closest(".card-body").querySelector(".list-komentar");
		lainya.classList.remove("show");
		lainya.innerHTML = "";
	});

	let listKomentar = el.closest(".card-body").querySelector(".list-komentar");

	if (data.role != 2) {
		listKomentar.innerHTML += `<a href='#reply_${data.idasp}' data-togle="collapse" class='btn btn-primary btn-sm mr-2 reply' data-idasp='${data.idasp}' data-parent="${data.parent}">Tambah Komentar</a>
		<div class="collapse mt-3" id="reply_${data.idasp}">
			<form class="komentar" method="post" action="${baseUrl}aspirasi/send">
				<input type="text" hidden value="${data.idasp}" name="aspirasi_id">
				<input type="text" hidden value="0" name="parent">
				<div class="form-group komentar position-relative">
					<textarea name='komentar' required class="form-control komentar_${data.idasp} mt-2" id="add_komentar_${data.idasp}" rows="3"></textarea>
					<label for="add_komentar_${data.idasp}" class="label-komentar">
						<span class="label-text">Ketikkan Komentar Anda Disini</span></label>
					<input type="submit" value="Kirim" class="btn btn-primary submit-komentar">
				</div>
			</form>
		</div>
		`;
	}

	const komentar = this.data.komentar.filter(
		res =>
			res.parent == data.parent &&
			res.aspirasi_id == data.idasp &&
			(data.role == 2 ? res.role_id == 2 : res.role_id != 2)
	);
	el.classList.toggle("is-active");
	listKomentar.classList.toggle("show");
	if (komentar.length > 0) {
		listKomentar.innerHTML += `<a href='#' class='btn btn-danger btn-sm tutup'>Tutup</a>`;
		komentar.map(res => {
			cardParent(res);
		});
	}
}

docReady(async function() {
	$(".toast").toast("show");

	// add new css style
	addCss(theme.folder + "layout/user/css/main.css");

	let elListAsp = document.querySelector("#data-aspirasi");
	data.aspirasi = await getData("api/aspirasi?id_user=" + user.id_user);
	let aspbaru = await getData("api/aspirasi?baru='yes'");

	let baru = () => {
		let elAspbaru = document.getElementById("asp-terbaru");
		if (aspbaru.status) {
			let datas = aspbaru.data.map(asp => {
				let card = document.createElement("div");
				card.setAttribute("class", "card mb-2 aspirasi baru");
				card.setAttribute("data-idAsp", asp.id_aspirasi);

				let date_created = getTanggal(asp.date_created);

				let html = `
				<div class="lihat"><a href="${baseUrl}/aspirasi/id/${asp.id_aspirasi}" class="btn bg-utama">Lihat</a></div>
				<div class="card-header identitas position-relative" style='padding-left:80px'>
					<img src="${asp.file}"  alt="" class="position-absolute rounded-circle cardImg-profile">
					<h5 class="card-title mb-0">${asp.username}</h5>
					<span class="text-muted">Di arahkan ke : </span><span class="komisi font-weight-bold">${asp.komisi}</span>
					<span class="position-absolute text-muted text-right" style="top:12px;right:15px">${date_created}</span>
				</div>
				<div class="card-body">
					<p class="card-text text-justify text-monospace">${asp.message}</p>
				</div>`;
				card.innerHTML = html;
				elAspbaru.appendChild(card);
				return card;
			});
			return datas;
		} else return "Tidak Ada Aspirasi Terbaru";
	};
	baru();
	init(data.aspirasi);
	$(".full").hide();

	// data aspirasi
	let type = document.querySelectorAll("li.asp");
	for (const asp of type) {
		asp.addEventListener("click", async function(event) {
			event.preventDefault();
			let inisialisasi = () => {
				for (const asp of type) {
					asp.querySelector(".nav-link").classList.remove("active");
				}
				let status = asp.dataset.status;
				asp.querySelector(".nav-link").classList.add("active");
				init(data.aspirasi, status);
			};
			inisialisasi();
			// console.log(data.progress);

			// !data.progress ? await inisialisasi() : "";
		});
	}
	let listAspirasi = document.getElementById("data-aspirasi");
	listAspirasi.addEventListener("click", function(e) {
		el = e.target;

		if (el.classList.contains("meta-info")) {
			showTanggapan(e);
		} else if (el.classList.contains("tutup")) {
			e.preventDefault();
			el = e.target;
			tutupList(el);
		} else if (el.classList.contains("reply")) {
			e.preventDefault();
			let id = el.dataset.idasp;
			let pr = el.closest(".collapse");
			pr.querySelector("#reply_" + id).classList.toggle("show");
		} else if (el.id == "deleteAsp") {
			let init = () => {
				e.preventDefault();
				let aspirasi = el.closest(".card.aspirasi");
				let idAsp = aspirasi.dataset.idasp;
				data.idasp = idAsp;
			};
			init();
		}
	});

	document.addEventListener("click", function(e) {
		let el = e.target;
		if (el.id == "deletedAsp") {
			console.log(data.idasp);
			let init = async () => {
				e.preventDefault();
				let idAsp = data.idasp;
				let res = await getData("api/aspirasi?id_aspirasi=" + idAsp, {
					method: "DELETE"
				});
				if (res.status) {
					data.aspirasi.data = data.aspirasi.data.filter(
						asp => asp.id_aspirasi != data.idasp
					);

					let eLselected = document.querySelectorAll(
						`[data-idasp="${data.idasp}"]`
					);
					Array.from(eLselected).forEach(el => {
						el.remove();
					});
					data.idasp = null;
					el.closest(".modal")
						.querySelector('[data-dismiss="modal"]')
						.click();
				}
			};
			init();
		}
	});
});

function tutupList(el) {
	let elAspirasi = el.closest(".card.aspirasi");
	Array.from(elAspirasi.querySelectorAll(".meta-info")).map(el =>
		el.classList.remove("is-active")
	);
	let parent = elAspirasi.querySelector(".list-komentar");
	parent.classList.remove("show");

	setTimeout(() => {
		parent.innerHTML = "";
	}, 300);
}
