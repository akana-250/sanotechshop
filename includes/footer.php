    </main>

</div>
<!-- End Wrapper -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const toggle = document.getElementById("sidebarToggle");

    const sidebar =
        document.querySelector(".admin-sidebar") ||
        document.querySelector(".user-sidebar");

    if (toggle && sidebar) {

        toggle.addEventListener("click", function () {

            sidebar.classList.toggle("show");

        });

    }

});

</script>

</body>
</html>