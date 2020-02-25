addCss("assets/backgroundanimated.css");
document.addEventListener("click", async e => {
	let el = e.target;
	if (el.classList.contains("kmt-delete")) {
		e.preventDefault();
		let elRoot = el.closest("li");
		let requestOptions = {
			method: "DELETE"
		};

		elRoot.classList.add("c-animated-background");
		let res = await getData(
			"api/komentar/" + elRoot.dataset.id,
			requestOptions
		);
		if (!res.status) return;
		elRoot.remove();
		console.log("delete " + elRoot.dataset.id);
	}
});
function cardKomentar({ image, username, id_komentar, komentar, ...element }) {
	let date_created = getTanggal(element.date_created);
	let lists = `<li data-id=${id_komentar}>
                    <div class="comment-main-level reply">
                        <div class="comment-avatar"><img src="${image}" alt=""></div>
                        <div class="comment-box">
                            <div class="comment-head">
                                <h6 class="comment-name by-author"><a href="#">${username}</a></h6>
                                <span>${date_created}</span>
                                <i class="material-icons kmt-delete mt-0">delete_forever</i>
                                <i class="fa fa-reply kmt-reply" data-id="${id_komentar}"></i>            
                            </div>
                            <div class="comment-content">
                                ${komentar}
                            </div>
                        </div>
                    </div>
                </li>`;
	return lists;
}
