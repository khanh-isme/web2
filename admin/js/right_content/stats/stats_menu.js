function handleStatsMenu() {
    let statsByOption = document.querySelector("#stats-by-time-option");
    statsByOption.addEventListener("change", () => {
        let statsTime = document.querySelector("#stats-time");
        if (statsByOption.value === 'all') {
            statsTime.classList.add("disabled");
        }
        else {
            statsTime.classList.remove("disabled");
        }
    });
}
