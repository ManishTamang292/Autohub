document.getElementById("ajaxSearch").addEventListener("keyup", doAjaxSearch);

function doAjaxSearch() {

    let search = document.getElementById("ajaxSearch").value.trim();
    let results = document.getElementById("ajaxResults");

    if (search.length < 2) {
        results.innerHTML = "";
        return;
    }

    fetch("ajax/car-search.php?search=" + encodeURIComponent(search))
        .then(response => response.json())
        .then(cars => {

            results.innerHTML = "";

            if (cars.length === 0) {
                results.innerHTML = '<div class="alert alert-info">No cars found.</div>';
                return;
            }

            cars.forEach(car => {

                let link = document.createElement("a");

                link.href = "car-details.php?id=" + car.car_id;
                link.textContent = car.make + " " + car.model;
                link.className = "list-group-item list-group-item-action";

                results.appendChild(link);
            });

        })
        .catch(error => {
            console.error("AJAX search error:", error);
            results.innerHTML = '<div class="alert alert-danger">Unable to search cars.</div>';
        });
}