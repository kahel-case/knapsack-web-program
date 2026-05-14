
<!-- Bundles up all the necessary scripts to run the datatables and bootstrap -->
<script src="resources/jquery-3.7.1.js"></script>
<script src="resources/dataTables.js"></script>
<script src="resources/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

<!-- Initializes datatable -->
<script>
    $(document).ready( function () {
        $('#myTable').DataTable();
    } );
</script>