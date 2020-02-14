loadFileJs(baseUrl + "node_modules/sweetalert2/dist/sweetalert2.min.js", "url");
addCss(baseUrl + "node_modules/sweetalert2/sweetalert2.min.css", "url");
addCss("layout/user/css/style.css");

let formLogin = document.querySelector("#form-login");
formLogin.addEventListener("submit", async function(e) {
	e.preventDefault();
	let email = formLogin.querySelector("[name='email'").value;
	let password = formLogin.querySelector("[name='password'").value;
	try {
		let formdata = new FormData();
		formdata.append("email", email);
		formdata.append("password", password);
		Swal.showLoading();
		let requestOptions = {
			method: "POST",
			body: formdata
		};
		let login = await getData("api/user/login", requestOptions);
		await Swal.fire({
			position: "top-center",
			icon: "success",
			title: "Login Success",
			showConfirmButton: false,
			timer: 1500
		});
		location.replace(baseUrl + "admin/permition/auth?id=" + login.id_user);
	} catch (error) {
		Swal.fire({
			icon: "error",
			title: "Oops...",
			// onBeforeOpen: () => {
			// 	Swal.showLoading();
			// },
			html: error
		});
	}
});
