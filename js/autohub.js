document.getElementById("ajaxSearch").addEventListener("keyup", doAjaxSearch);

function doAjaxSearch() {
    let search = document.getElementById("ajaxSearch").value;

    fetch("ajax/car-search.php?search=" + search)
    .then(response => response.json())
    .then(cars => {
        let results = document.getElementById("ajaxResults");

        results.innerHTML = "";

        cars.forEach(car => {
            results.innerHTML += '<a href="car-details.php?id=' + car.car_id + '">' 
                + car.make + " " + car.model 
                + '</a><br>';
    
        });
    })
}