<!-- Global Delete Modal -->
<div class="modal fade" id="globalDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <!-- Header -->
            <div class="modal-header border-0 pb-0 justify-content-center">
                <div class="text-center w-100">
                    <div class="bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                         style="width:60px; height:60px;">
                        <i class="bi bi-trash-fill fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-danger mb-0">Delete Confirmation</h5>
                </div>
                <button type="button" class="btn-close position-absolute end-0 top-0 m-3" data-bs-dismiss="modal"></button>
            </div>

            <!-- Body -->
            <div class="modal-body text-center pt-2">
                <p class="text-muted mb-0">
                    Are you sure you want to delete this?  
                    <br><span class="fw-semibold text-dark">This action cannot be undone.</span>
                </p>
            </div>

            <!-- Footer -->
            <div class="modal-footer border-0 d-flex justify-content-center gap-2 pb-4">

                <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">
                    Cancel
                </button>

                <form id="globalDeleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4 rounded-pill shadow-sm">
                        <i class="bi bi-trash me-1"></i> Yes, Delete
                    </button>
                </form>

            </div>

        </div>
    </div>
</div>
