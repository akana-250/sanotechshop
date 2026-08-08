<!-- Statistics Cards -->

<div class="row mb-4">

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-box-seam fs-1 text-primary"></i>
                <h3><?= $totalProducts ?></h3>
                <small>Total Products</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-check-circle fs-1 text-success"></i>
                <h3><?= $activeProducts ?></h3>
                <small>Active</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                <h3><?= $lowStock ?></h3>
                <small>Low Stock</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-x-circle fs-1 text-danger"></i>
                <h3><?= $outOfStock ?></h3>
                <small>Out of Stock</small>
            </div>
        </div>
    </div>

</div>