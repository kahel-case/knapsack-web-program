document.addEventListener("DOMContentLoaded", function () {
    const today = new Date();

    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2,'0');
    const dd = String(today.getDate()).padStart(2,'0');

    const startDate = document.getElementById('startDate');
    if(startDate){
        startDate.value = `${yyyy}-${mm}-${dd}`;
    }
});