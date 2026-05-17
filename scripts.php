
<!-- Bundles up all the necessary scripts to run the datatables and bootstrap -->
<script src="resources/jquery-3.7.1.js"></script>
<script src="resources/dataTables.js"></script>
<script src="resources/bootstrap.bundle.min.js"></script>

<!-- Initializes datatable -->
<script>
    $(document).ready( function () {
        $('#myTable').DataTable();
    } );
</script>

<script>
    window.onload = function () {
        const open = new URLSearchParams(window.location.search).get("openModal");
        if (open) {
            document.getElementById("run_algorithm")?.click();
        }
    };
</script>